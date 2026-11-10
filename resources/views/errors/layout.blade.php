<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0c">
    <title>{{ $code }} // {{ $title }} | The Hive Project</title>
    @vite('resources/css/errors.css')
</head>
<body>
    <main class="error-shell">
        <a class="brand" href="{{ route('dashboard') }}" aria-label="The Hive Project">
            <span class="brand-mark" aria-hidden="true">H</span>
            <span class="brand-name">The Hive Project</span>
        </a>

        <section class="error-panel" aria-labelledby="error-title">
            <header class="panel-header">
                <p class="eyebrow">Hive // Identity Core</p>
                <span class="status-tag"><span class="status-light" aria-hidden="true"></span>HTTP {{ $code }} // {{ $classification }}</span>
            </header>

            <div class="error-content">
                <div class="error-visual" aria-hidden="true">
                    <span class="visual-orbit"></span>
                    <span class="visual-mark">H</span>
                    <span class="visual-code">{{ $code }}</span>
                </div>

                <div class="error-copy">
                    <p class="error-index">CORE RESPONSE // {{ $code }}</p>
                    <h1 id="error-title">{{ $title }}</h1>
                    <p class="error-message">{{ $message }}</p>
                    <a class="primary-action" href="{{ route('dashboard') }}">
                        <span>Return to Core</span>
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>

            <footer class="panel-footer">
                <span>NETWORK STATUS</span>
                <span class="footer-status">{{ $networkStatus }}</span>
            </footer>
        </section>

        <p class="page-footer">HIVE IDENTITY CORE <span aria-hidden="true">//</span> SECURE CONNECTION</p>
    </main>
</body>
</html>
