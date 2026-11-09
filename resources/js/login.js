import '../css/auth.css';

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
