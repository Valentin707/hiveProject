<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $applications = Application::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn (Application $application): array => [
                'id' => $application->getKey(),
                'name' => $application->name,
                'targetUrl' => $application->url,
                'description' => $application->description,
            ])
            ->values();

        return view('dashboard', compact('applications'));
    }
}
