<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0c">
    <title>The Hive Project | {{ config('app.name', 'Hive') }}</title>
    @vite('resources/js/dashboard.js')
</head>
<body>
<canvas id="dashboard-canvas" data-applications="{{ json_encode($applications) }}" data-logo-url="{{ asset('images/THP.png') }}" aria-label="The Hive Project logo over an animated octagonal glass shard background"></canvas>
<header class="top-nav">
    <a class="brand" href="{{ route('dashboard') }}" aria-label="The Hive Project dashboard">
        <span class="brand-mark" aria-hidden="true">H</span>
        <span>The Hive Project</span>
    </a>
    <div class="hud">
        <span class="operator-name">{{ auth()->user()->name }}</span>
        <a class="settings-link" href="{{ route('settings.index') }}">Security // Settings</a>
        <a class="registry-link" href="{{ route('applications.index') }}">Nodes // Registry</a>
        <form class="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-button" type="submit">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M10 4H5.5A1.5 1.5 0 0 0 4 5.5v13A1.5 1.5 0 0 0 5.5 20H10M14 8l4 4-4 4m4-4H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter" />
                </svg>
                <span>Disconnect</span>
            </button>
        </form>
    </div>
</header>
<nav class="visually-hidden" aria-label="Hive applications">
    @foreach ($applications as $application)
        <a href="{{ $application['targetUrl'] }}" title="{{ $application['description'] ?? '' }}">{{ $application['name'] }}</a>
    @endforeach
</nav>


</body>
</html>
