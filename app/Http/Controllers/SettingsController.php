<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $tokens = $user->tokens()
            ->with('client')
            ->where('revoked', false)
            ->where(function (Builder $query): void {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->latest('created_at')
            ->get();

        return view('settings', [
            'user' => $user,
            'tokens' => $tokens,
            'twoFactorEnabled' => $user->two_factor_confirmed_at !== null,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user),
            ],
        ]);

        $user->fill($validated)->save();

        return back()->with('status', 'Operator identity updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'different:current_password',
                Password::min(12),
            ],
        ]);

        $request->user()->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        Auth::logoutOtherDevices($validated['password']);

        return back()->with('status', 'Credentials updated. Other authenticated sessions have been disconnected.');
    }

    public function revokeToken(Request $request, string $tokenId): RedirectResponse
    {
        $token = $request->user()->tokens()
            ->with('refreshToken')
            ->whereKey($tokenId)
            ->firstOrFail();

        $token->refreshToken?->revoke();
        $token->revoke();

        return back()->with('status', 'OAuth session disconnected.');
    }
}
