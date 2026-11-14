<?php

namespace App\Http\Middleware;

use App\Models\Application;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanAccessOAuthClient
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $clientId = $request->query('client_id');

        if (! $user instanceof User || ! is_string($clientId) || $clientId === '') {
            return $next($request);
        }

        $application = Application::query()
            ->where('oauth_client_id', $clientId)
            ->first();

        if ($application instanceof Application && ! $user->canAccessApplication($application)) {
            return response()->view('errors.403', [], 403);
        }

        return $next($request);
    }
}
