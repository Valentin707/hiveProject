<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->with('applications')
            ->orderByDesc('created_at')
            ->orderBy('name')
            ->get();
        $applications = Application::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $activeApplications = $applications->where('is_active', true)->values();

        return view('users.index', compact('users', 'applications', 'activeApplications'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'confirmed', Password::min(12)],
            'role' => ['required', 'string', Rule::in([User::ROLE_ADMIN, User::ROLE_OPERATOR])],
            'application_ids' => ['sometimes', 'array'],
            'application_ids.*' => ['string', Rule::exists('applications', 'id')->where('is_active', true)],
        ]);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);
        $user->applications()->sync($validated['application_ids'] ?? []);

        return to_route('users.index')->with('status', 'Operator account created.');
    }

    public function updateApplications(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === User::ROLE_OPERATOR, 403);

        $validated = $request->validate([
            'application_ids' => ['sometimes', 'array'],
            'application_ids.*' => ['string', 'exists:applications,id'],
        ]);

        $user->applications()->sync($validated['application_ids'] ?? []);

        return to_route('users.index')->with('status', 'Operator node clearance updated.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        abort_if($user->is($request->user()), 403);

        $user->forceFill([
            'role' => $user->role === User::ROLE_ADMIN ? User::ROLE_OPERATOR : User::ROLE_ADMIN,
        ])->save();

        return to_route('users.index')->with('status', 'User access role updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if($user->is($request->user()), 403);

        $user->tokens()->with('refreshToken')->get()->each(function ($token): void {
            $token->refreshToken?->revoke();
            $token->revoke();
        });

        $user->delete();

        return to_route('users.index')->with('status', 'User account and connected OAuth sessions removed.');
    }
}
