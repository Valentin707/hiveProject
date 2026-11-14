<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use GuzzleHttp\TransferStats;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class ClusterHealthController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        abort_unless($user instanceof User, 401);

        $applications = ($user->role === User::ROLE_ADMIN
            ? Application::query()->where('is_active', true)
            : $user->applications()->where('is_active', true))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $healthByApplication = [];
        $uncachedApplications = [];

        foreach ($applications as $application) {
            $applicationId = (string) $application->getKey();
            $cachedHealth = Cache::get($this->cacheKey($applicationId));

            if (
                is_array($cachedHealth)
                && in_array($cachedHealth['status'] ?? null, ['online', 'offline'], true)
                && is_int($cachedHealth['latency_ms'] ?? null)
            ) {
                $healthByApplication[$applicationId] = $cachedHealth;

                continue;
            }

            $uncachedApplications[] = $application;
        }

        if ($uncachedApplications !== []) {
            $probedHealth = $this->probeApplications($uncachedApplications);

            foreach ($uncachedApplications as $application) {
                $applicationId = (string) $application->getKey();
                $healthByApplication[$applicationId] = Cache::remember(
                    $this->cacheKey($applicationId),
                    now()->addSeconds(15),
                    fn (): array => $probedHealth[$applicationId] ?? [
                        'status' => 'offline',
                        'latency_ms' => 0,
                    ],
                );
            }
        }

        return response()
            ->json((object) $healthByApplication)
            ->header('Cache-Control', 'no-store');
    }

    /**
     * @param  array<int, Application>  $applications
     * @return array<string, array{status: 'online'|'offline', latency_ms: int}>
     */
    private function probeApplications(array $applications): array
    {
        $startedAt = [];
        $latencies = [];
        $caBundle = config('services.cluster_health.ca_bundle') ?: true;

        try {
            $responses = Http::pool(function (Pool $pool) use ($applications, $caBundle, &$latencies, &$startedAt): void {
                foreach ($applications as $application) {
                    $applicationId = (string) $application->getKey();
                    $startedAt[$applicationId] = hrtime(true);

                    $pool->as($applicationId)
                        ->timeout(2)
                        ->withoutRedirecting()
                        ->withOptions([
                            'verify' => $caBundle,
                            'on_stats' => function (TransferStats $stats) use ($applicationId, &$latencies): void {
                                $transferTime = $stats->getTransferTime();

                                if ($transferTime !== null) {
                                    $latencies[$applicationId] = (int) round($transferTime * 1000);
                                }
                            },
                        ])
                        ->get($application->health_check_url ?: $application->url);
                }
            });
        } catch (Throwable) {
            $responses = [];
        }

        $healthByApplication = [];

        foreach ($applications as $application) {
            $applicationId = (string) $application->getKey();
            $response = $responses[$applicationId] ?? null;
            $latency = $latencies[$applicationId]
                ?? (int) round((hrtime(true) - ($startedAt[$applicationId] ?? hrtime(true))) / 1_000_000);

            $healthByApplication[$applicationId] = [
                'status' => $response instanceof ClientResponse && $response->status() < 500
                    ? 'online'
                    : 'offline',
                'latency_ms' => max(0, $latency),
            ];
        }

        return $healthByApplication;
    }

    private function cacheKey(string $applicationId): string
    {
        return 'cluster-health:'.$applicationId;
    }
}
