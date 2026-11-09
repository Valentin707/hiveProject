<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $applications = collect(config('hive.services', []))
            ->filter(fn (array $application): bool => $application['active'] ?? false)
            ->map(fn (array $application): array => [
                'id' => Str::slug($application['name']),
                'name' => $application['name'],
                'targetUrl' => $application['url'],
            ])
            ->values()
            ->all();

        return view('dashboard', compact('applications'));
    }
}
