<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0c">
    <title>Authorize {{ $client->name }} | The Hive Project</title>
    @vite('resources/css/oauth-authorize.css')
</head>
<body>
    <main class="authorize-shell">
        <section class="authorize-panel" aria-labelledby="authorize-heading">
            <header class="authorize-header">
                <p class="eyebrow">Hive // Identity Core</p>
                <span class="request-status"><span aria-hidden="true"></span>OAuth Request</span>
            </header>

            <div class="authorize-content">
                <p class="panel-index">EXTERNAL APPLICATION // ACCESS REQUEST</p>
                <h1 id="authorize-heading">Authorize Access</h1>
                <p class="intro"><strong>{{ $client->name }}</strong> is requesting permission to access your Hive identity.</p>

                @if (count($scopes) > 0)
                    <div class="scope-panel">
                        <h2>Requested permissions</h2>
                        <ul>
                            @foreach ($scopes as $scope)
                                <li>{{ $scope->description }}</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="scope-panel scope-empty">This application is requesting access to your account.</p>
                @endif

                <div class="operator-card">
                    <span class="operator-label">AUTHENTICATED OPERATOR</span>
                    <span class="operator-name">{{ $user->name }}</span>
                    <span class="operator-email">{{ $user->email }}</span>
                </div>

                <div class="authorization-actions">
                    <form method="POST" action="{{ route('passport.authorizations.deny') }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="state" value="{{ $request->state }}">
                        <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                        <input type="hidden" name="auth_token" value="{{ $authToken }}">
                        <button class="deny-action" type="submit">Deny</button>
                    </form>

                    <form method="POST" action="{{ route('passport.authorizations.approve') }}">
                        @csrf
                        <input type="hidden" name="state" value="{{ $request->state }}">
                        <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                        <input type="hidden" name="auth_token" value="{{ $authToken }}">
                        <button class="approve-action" type="submit">Authorize Application</button>
                    </form>
                </div>
            </div>

            <footer class="authorize-footer">Credentials stay within the Hive Identity Core.</footer>
        </section>
    </main>
</body>
</html>
