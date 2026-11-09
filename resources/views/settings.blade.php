<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0c">
    <title>Security Settings | The Hive Project</title>
    <style>
        :root {
            color-scheme: dark;
            font-family: "SFMono-Regular", Consolas, "Liberation Mono", monospace;
            background: #0a0a0c;
            color: #e7ebee;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 28px 22px 56px;
            background:
                radial-gradient(ellipse at 50% -20%, rgba(71, 82, 94, 0.25), transparent 58%),
                linear-gradient(rgba(157, 179, 196, 0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(157, 179, 196, 0.035) 1px, transparent 1px),
                #0a0a0c;
            background-size: auto, 52px 52px, 52px 52px, auto;
        }

        .shell { width: min(100%, 1080px); margin: 0 auto; }

        .return-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 26px;
            color: #c7a0a6;
            font-size: 10px;
            letter-spacing: 0.16em;
            text-decoration: none;
            text-transform: uppercase;
        }

        .return-link:hover, .return-link:focus-visible { color: #fff; outline: none; }
        .return-link::before { color: #9daab3; content: "←"; font-size: 15px; }
        .eyebrow { margin: 0 0 10px; color: #bc7783; font-size: 9px; letter-spacing: 0.2em; text-transform: uppercase; }
        h1 { margin: 0; font-size: clamp(25px, 4vw, 36px); font-weight: 400; letter-spacing: 0.08em; text-transform: uppercase; }
        .lead { margin: 12px 0 28px; color: #858b93; font-size: 11px; line-height: 1.7; }

        .alert {
            margin: 0 0 18px;
            padding: 13px 16px;
            border: 1px solid rgba(116, 173, 146, 0.38);
            color: #b8dec9;
            background: rgba(51, 95, 72, 0.14);
            font-size: 11px;
            line-height: 1.6;
        }

        .panel-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }

        .panel {
            min-width: 0;
            padding: clamp(19px, 3vw, 27px);
            border: 1px solid rgba(173, 193, 207, 0.16);
            background: linear-gradient(145deg, rgba(23, 26, 31, 0.91), rgba(13, 15, 18, 0.94));
            box-shadow: 0 18px 48px rgba(0, 0, 0, 0.16), inset 0 1px rgba(255, 255, 255, 0.025);
        }

        .panel-wide { grid-column: 1 / -1; }
        .panel-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 18px; margin-bottom: 21px; }
        .panel-index { display: block; margin-bottom: 8px; color: #9c626d; font-size: 9px; letter-spacing: 0.16em; }
        h2 { margin: 0; font-size: 13px; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase; }
        .panel-copy { margin: 8px 0 0; color: #777d85; font-size: 10px; line-height: 1.6; }

        .field { margin: 0 0 17px; }
        .field:last-of-type { margin-bottom: 20px; }
        label { display: block; margin: 0 0 8px; color: #9ea5ad; font-size: 9px; letter-spacing: 0.13em; text-transform: uppercase; }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            min-height: 43px;
            padding: 0 12px;
            border: 1px solid rgba(176, 195, 208, 0.2);
            border-radius: 0;
            outline: 0;
            color: #e8ecef;
            background: rgba(7, 9, 11, 0.68);
            font: inherit;
            font-size: 11px;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border-color: rgba(207, 135, 148, 0.75);
            box-shadow: 0 0 0 1px rgba(207, 135, 148, 0.16);
        }

        .field-error { display: block; margin-top: 7px; color: #e895a0; font-size: 10px; line-height: 1.5; }
        .action {
            min-height: 39px;
            padding: 0 15px;
            border: 1px solid rgba(195, 122, 135, 0.54);
            color: #f0dadd;
            background: rgba(103, 50, 62, 0.24);
            font: 500 9px/1 "SFMono-Regular", Consolas, "Liberation Mono", monospace;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            cursor: pointer;
            transition: border-color 150ms ease, color 150ms ease, background 150ms ease;
        }

        .action:hover, .action:focus-visible { border-color: #df9aa5; outline: none; color: #fff; background: rgba(130, 58, 72, 0.42); }
        .token-wrap { overflow-x: auto; }
        .token-table { width: 100%; border-collapse: collapse; text-align: left; }
        .token-table th { padding: 0 14px 12px; color: #777e87; font-size: 8px; font-weight: 500; letter-spacing: 0.14em; text-transform: uppercase; white-space: nowrap; }
        .token-table td { padding: 13px 14px; border-top: 1px solid rgba(173, 193, 207, 0.11); color: #c5cbd0; font-size: 10px; vertical-align: middle; }
        .token-table th:first-child, .token-table td:first-child { padding-left: 0; }
        .token-table th:last-child, .token-table td:last-child { padding-right: 0; text-align: right; }
        .client-name { color: #e4e9ec; font-size: 11px; }
        .scope-list { display: flex; flex-wrap: wrap; gap: 5px; min-width: 110px; }
        .scope { padding: 4px 6px; border: 1px solid rgba(173, 193, 207, 0.13); color: #969fa7; font-size: 8px; }
        .token-date { color: #9299a1; white-space: nowrap; }
        .revoke-button { padding: 7px 9px; border: 1px solid rgba(193, 111, 125, 0.38); color: #d9929d; background: transparent; font: inherit; font-size: 8px; letter-spacing: 0.1em; text-transform: uppercase; cursor: pointer; }
        .revoke-button:hover, .revoke-button:focus-visible { border-color: #e38f9c; outline: none; color: #fff; background: rgba(119, 50, 63, 0.22); }
        .empty-state { padding: 23px 0 3px; border-top: 1px solid rgba(173, 193, 207, 0.11); color: #858c94; font-size: 10px; line-height: 1.7; }

        .security-state { display: flex; flex-wrap: wrap; align-items: center; gap: 13px; margin: 8px 0 17px; }
        .state-badge { padding: 8px 10px; border: 1px solid rgba(173, 193, 207, 0.2); color: #a4adb5; font-size: 9px; letter-spacing: 0.1em; }
        .state-badge.active { border-color: rgba(116, 173, 146, 0.42); color: #b8dec9; }
        .toggle-wrap { display: inline-flex; align-items: center; gap: 9px; color: #747b83; font-size: 9px; letter-spacing: 0.09em; text-transform: uppercase; }
        .toggle-wrap input { width: 30px; height: 16px; margin: 0; appearance: none; border: 1px solid #42474d; border-radius: 10px; background: #1a1c20; opacity: 0.7; }
        .toggle-wrap input::before { display: block; width: 10px; height: 10px; margin: 2px; border-radius: 50%; background: #777e86; content: ""; transition: transform 150ms ease; }
        .toggle-wrap input:checked::before { transform: translateX(13px); background: #bc7783; }
        .schedule-note { margin: 0; color: #777e87; font-size: 10px; line-height: 1.7; }

        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        @media (max-width: 760px) { .panel-grid { grid-template-columns: 1fr; } .panel-wide { grid-column: auto; } }
        @media (max-width: 520px) {
            body { padding: 22px 14px 38px; }
            .token-table th, .token-table td { padding-right: 8px; padding-left: 8px; }
            .token-table th:nth-child(2), .token-table td:nth-child(2) { min-width: 125px; }
        }
    </style>
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
