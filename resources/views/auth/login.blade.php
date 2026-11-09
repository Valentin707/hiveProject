<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0c">
    <title>System Authenticate | Hive</title>
    <style>
        :root { color-scheme: dark; font-family: "SFMono-Regular", Consolas, "Liberation Mono", monospace; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; display: grid; place-items: center; padding: 24px; color: #e8e9eb; background: #0a0a0c; }
        #login-grid { position: fixed; z-index: 0; inset: 0; width: 100%; height: 100%; }
        main { position: relative; z-index: 1; width: min(100%, 420px); padding: 42px; border: 1px solid #34363b; background: #101114eF; box-shadow: 0 24px 80px #0009; backdrop-filter: blur(8px); }
        .eyebrow { margin: 0 0 30px; color: #ff5361; font-size: 11px; letter-spacing: .22em; text-transform: uppercase; }
        h1 { margin: 0 0 10px; font-size: clamp(23px, 6vw, 30px); font-weight: 500; letter-spacing: -.05em; }
        .intro { margin: 0 0 34px; color: #8f9299; font-size: 12px; line-height: 1.7; }
        label { display: block; margin: 0 0 9px; color: #aeb1b8; font-size: 10px; letter-spacing: .14em; text-transform: uppercase; }
        input { width: 100%; height: 48px; margin: 0 0 23px; padding: 0 13px; border: 1px solid #34363b; border-radius: 0; outline: 0; color: #f3f3f4; background: #0a0a0c; font: inherit; font-size: 13px; }
        input:focus { border-color: #ff5361; box-shadow: 0 0 0 1px #ff536155; }
        button { width: 100%; min-height: 48px; border: 1px solid #ff5361; color: #0a0a0c; background: #ff5361; font: inherit; font-size: 11px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; cursor: pointer; }
        button:hover { background: #ff7380; border-color: #ff7380; }
        .error { margin: -11px 0 20px; color: #ff7a85; font-size: 11px; line-height: 1.6; }
        .footer { margin: 28px 0 0; color: #61646b; font-size: 10px; letter-spacing: .08em; }
        @media (max-width: 480px) { main { padding: 32px 25px; } }
    </style>
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
    <script>
        const canvas = document.getElementById('login-grid');
        const context = canvas.getContext('2d');
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let width = 0;
        let height = 0;
        let frameId = 0;

        function resize() {
            const scale = Math.min(window.devicePixelRatio || 1, 2);
            width = window.innerWidth;
            height = window.innerHeight;
            canvas.width = Math.round(width * scale);
            canvas.height = Math.round(height * scale);
            context.setTransform(scale, 0, 0, scale, 0, 0);
        }

        function draw(time = 0) {
            const centerX = width / 2;
            const centerY = height / 2;
            const radius = Math.min(width, height) * 0.34;

            context.fillStyle = '#0a0a0c';
            context.fillRect(0, 0, width, height);

            const haze = context.createRadialGradient(centerX, centerY, radius * 0.12, centerX, centerY, Math.max(width, height) * 0.72);
            haze.addColorStop(0, 'rgba(47, 57, 68, 0.34)');
            haze.addColorStop(0.42, 'rgba(23, 28, 34, 0.25)');
            haze.addColorStop(1, 'rgba(5, 6, 8, 0)');
            context.fillStyle = haze;
            context.fillRect(0, 0, width, height);

            const spacing = 52;
            const offset = reducedMotion ? 0 : (time * 0.004) % spacing;
            context.beginPath();
            for (let x = (centerX % spacing) + offset; x < width; x += spacing) {
                context.moveTo(x, 0);
                context.lineTo(x, height);
            }
            for (let y = (centerY % spacing) + offset; y < height; y += spacing) {
                context.moveTo(0, y);
                context.lineTo(width, y);
            }
            context.strokeStyle = 'rgba(157, 179, 196, 0.055)';
            context.lineWidth = 1;
            context.stroke();

            const vignette = context.createRadialGradient(centerX, centerY, radius * 0.75, centerX, centerY, Math.max(width, height) * 0.72);
            vignette.addColorStop(0, 'rgba(5, 6, 8, 0)');
            vignette.addColorStop(1, 'rgba(5, 6, 8, 0.78)');
            context.fillStyle = vignette;
            context.fillRect(0, 0, width, height);

            if (! reducedMotion) {
                frameId = window.requestAnimationFrame(draw);
            }
        }

        window.addEventListener('resize', () => {
            window.cancelAnimationFrame(frameId);
            resize();
            draw();
        }, { passive: true });

        resize();
        draw();
    </script>
</body>
</html>
