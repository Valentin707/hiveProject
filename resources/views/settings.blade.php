<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0c">
    <title>Security Settings | The Hive Project</title>
    @vite('resources/css/settings.css')
</head>
<body>
    <main class="shell">
        <a class="return-link" href="{{ route('dashboard') }}">Return to Core</a>
        <p class="eyebrow">Identity Core // Operator Console</p>
        <h1>Security Settings</h1>
        <p class="lead">Manage your operator identity, credentials, and connected OAuth nodes.</p>

        @if (session('status'))
            <div class="alert" role="status">{{ session('status') }}</div>
        @endif

        <div class="panel-grid">
            <section class="panel" aria-labelledby="identity-heading">
                <div class="panel-head">
                    <div>
                        <span class="panel-index">01 // PROFILE</span>
                        <h2 id="identity-heading">Operator Identity</h2>
                        <p class="panel-copy">Update the identity attached to your central account.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('settings.profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="field">
                        <label for="name">Operator name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" autocomplete="name" required>
                        @error('name') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="email">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" autocomplete="email" required>
                        @error('email') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <button class="action" type="submit">Save identity</button>
                </form>
            </section>

            <section class="panel" aria-labelledby="password-heading">
                <div class="panel-head">
                    <div>
                        <span class="panel-index">02 // CREDENTIALS</span>
                        <h2 id="password-heading">Master Credentials</h2>
                        <p class="panel-copy">Changing your password disconnects other authenticated browser sessions.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('settings.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="field">
                        <label for="current_password">Current password</label>
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                        @error('current_password') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="password">New password</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required>
                        @error('password') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Confirm new password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
                    </div>

                    <button class="action" type="submit">Rotate credentials</button>
                </form>
            </section>

            <section class="panel panel-wide" aria-labelledby="tokens-heading">
                <div class="panel-head">
                    <div>
                        <span class="panel-index">03 // FEDERATED ACCESS</span>
                        <h2 id="tokens-heading">Active OAuth Sessions / Connected Nodes</h2>
                        <p class="panel-copy">Revoke a node's access token and refresh token to terminate its OAuth session.</p>
                    </div>
                </div>

                @if ($tokens->isEmpty())
                    <p class="empty-state">No external sub-applications are currently authorized.</p>
                @else
                    <div class="token-wrap">
                        <table class="token-table">
                            <thead>
                                <tr>
                                    <th scope="col">Client</th>
                                    <th scope="col">Scopes</th>
                                    <th scope="col">Connected</th>
                                    <th scope="col"><span class="visually-hidden">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tokens as $token)
                                    <tr>
                                        <td><span class="client-name">{{ $token->client?->name ?? 'Unknown application' }}</span></td>
                                        <td>
                                            <div class="scope-list">
                                                @forelse (($token->scopes ?? []) as $scope)
                                                    <span class="scope">{{ $scope }}</span>
                                                @empty
                                                    <span class="scope">NO SCOPES</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td class="token-date">{{ $token->created_at?->format('Y-m-d H:i') ?? 'Unknown' }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('settings.tokens.revoke', ['tokenId' => $token->getKey()]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="revoke-button" type="submit">Revoke</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

            <section class="panel panel-wide" aria-labelledby="two-factor-heading">
                <div class="panel-head">
                    <div>
                        <span class="panel-index">04 // SECONDARY VERIFICATION</span>
                        <h2 id="two-factor-heading">Two-Factor Authentication</h2>
                        <p class="panel-copy">A second verification factor will add another protection layer to this identity.</p>
                    </div>
                </div>

                <div class="security-state">
                    <span class="state-badge {{ $twoFactorEnabled ? 'active' : '' }}">
                        2FA PROTOCOL: {{ $twoFactorEnabled ? 'ACTIVE' : 'STANDBY' }}
                    </span>
                    <label class="toggle-wrap" for="two-factor-toggle">
                        <input id="two-factor-toggle" type="checkbox" disabled @checked($twoFactorEnabled)>
                        <span>{{ $twoFactorEnabled ? 'Confirmed' : 'Scheduled for future activation' }}</span>
                    </label>
                </div>

                @if ($twoFactorEnabled)
                    <p class="schedule-note">Confirmed {{ $user->two_factor_confirmed_at->format('Y-m-d H:i') }}. Two-factor management will be available in a future security update.</p>
                @else
                    <p class="schedule-note">The 2FA protocol is not active. Enrollment controls are scheduled for a future security update.</p>
                @endif
            </section>
        </div>
    </main>
</body>
</html>
