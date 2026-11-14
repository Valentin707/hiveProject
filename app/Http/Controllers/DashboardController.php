<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        abort_unless($user instanceof User, 401);

        $applications = ($user->role === User::ROLE_ADMIN
            ? Application::query()->where('is_active', true)
            : $user->applications()->where('is_active', true))
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
