<?php

namespace App\Http\Controllers;

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
            ->orderByDesc('created_at')
            ->orderBy('name')
            ->get();

        return view('users.index', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'confirmed', Password::min(12)],
            'role' => ['required', 'string', Rule::in([User::ROLE_ADMIN, User::ROLE_OPERATOR])],
        ]);

        User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return to_route('users.index')->with('status', 'Operator account created.');
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
