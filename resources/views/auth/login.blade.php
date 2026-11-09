<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0c">
    <title>System Authenticate | Hive</title>
    @vite('resources/js/login.js')
</head>
<body>
    <canvas id="login-grid" aria-hidden="true"></canvas>
    <main>
        <p class="eyebrow">Hive // Identity Core</p>
        <h1>System Authenticate</h1>
        <p class="intro">Authenticate to access the Hive network and its connected services.</p>

        @if ($errors->any())
            <p class="error" role="alert">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>

            <button type="submit">System Authenticate</button>
        </form>

        <p class="footer">CENTRAL AUTHORIZATION GATEWAY</p>
    </main>

</body>
</html>
