<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0c">
    <title>The Hive Project | {{ config('app.name', 'Hive') }}</title>
    <style>
        :root {
            color-scheme: dark;
            background: #0a0a0c;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            overflow: hidden;
            background: #0a0a0c;
        }

        #dashboard-canvas {
            position: fixed;
            z-index: 0;
            inset: 0;
            display: block;
            width: 100%;
            height: 100%;
            touch-action: pan-x pan-y;
        }

        .top-nav {
            position: fixed;
            z-index: 3;
            inset: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px clamp(18px, 4vw, 42px);
            border-bottom: 1px solid rgba(185, 205, 220, 0.12);
            background: linear-gradient(180deg, rgba(8, 10, 13, 0.88), rgba(8, 10, 13, 0.54));
            backdrop-filter: blur(12px);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 11px;
            color: rgba(233, 239, 243, 0.94);
            font: 500 11px/1 "SFMono-Regular", Consolas, "Liberation Mono", monospace;
            letter-spacing: 0.19em;
            text-decoration: none;
            text-transform: uppercase;
        }

        .brand-mark {
            display: grid;
            width: 28px;
            height: 28px;
            place-items: center;
            border: 1px solid rgba(216, 232, 241, 0.54);
            color: #f0f5f8;
            font-size: 9px;
            letter-spacing: -0.05em;
            clip-path: polygon(25% 0, 75% 0, 100% 25%, 100% 75%, 75% 100%, 25% 100%, 0 75%, 0 25%);
            background: rgba(131, 69, 81, 0.2);
            box-shadow: inset 0 0 12px rgba(192, 133, 145, 0.16), 0 0 14px rgba(192, 133, 145, 0.12);
        }

        .hud {
            display: flex;
            align-items: center;
            gap: clamp(12px, 2.4vw, 28px);
        }

        .operator-name,
        .settings-link {
            color: rgba(207, 219, 226, 0.76);
            font: 500 9px/1 "SFMono-Regular", Consolas, "Liberation Mono", monospace;
            letter-spacing: 0.12em;
            text-decoration: none;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .settings-link { color: rgba(221, 164, 173, 0.92); }

        .settings-link:hover,
        .settings-link:focus-visible {
            color: #fff;
            outline: none;
            text-shadow: 0 0 12px rgba(221, 164, 173, 0.7);
        }

        .logout-form {
            margin: 0;
        }

        .logout-button {
            display: inline-flex;
            min-height: 36px;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 13px;
            border: 1px solid rgba(185, 205, 220, 0.25);
            color: rgba(222, 230, 235, 0.82);
            background: rgba(12, 15, 19, 0.72);
            font: 500 9px/1 "SFMono-Regular", Consolas, "Liberation Mono", monospace;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            cursor: pointer;
            transition: border-color 160ms ease, color 160ms ease, background 160ms ease;
        }

        .logout-button:hover,
        .logout-button:focus-visible {
            border-color: rgba(225, 151, 162, 0.78);
            outline: none;
            color: #fff;
            background: rgba(118, 54, 67, 0.28);
        }

        .logout-button svg {
            width: 13px;
            height: 13px;
        }

        @media (max-width: 680px) {
            .top-nav { padding: 12px 16px; }
            .brand { gap: 7px; font-size: 8px; letter-spacing: 0.1em; }
            .brand-mark { width: 24px; height: 24px; }
            .hud { display: grid; grid-template-columns: auto auto; justify-items: end; gap: 8px 12px; }
            .operator-name { grid-column: 1; grid-row: 1; font-size: 8px; }
            .settings-link { grid-column: 2; grid-row: 1; font-size: 8px; }
            .logout-form { grid-column: 2; grid-row: 2; }
            .logout-button { min-height: 30px; padding: 0 9px; font-size: 8px; }
        }

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
    </style>
</head>
<body>
<canvas id="dashboard-canvas" aria-label="The Hive Project logo over an animated octagonal glass shard background"></canvas>
<header class="top-nav">
    <a class="brand" href="{{ route('dashboard') }}" aria-label="The Hive Project dashboard">
        <span class="brand-mark" aria-hidden="true">H</span>
        <span>The Hive Project</span>
    </a>
    <div class="hud">
        <span class="operator-name">{{ auth()->user()->name }}</span>
        <a class="settings-link" href="{{ route('settings.index') }}">Security // Settings</a>
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
        <a href="{{ $application['targetUrl'] }}">{{ $application['name'] }}</a>
    @endforeach
</nav>

<script>
    window.hiveProjects = @js($applications);

    (() => {
        const COLORS = {
            background: '#0a0a0c',
            red: ['rgba(199, 126, 137, 0.94)', 'rgba(131, 69, 81, 0.91)', 'rgba(56, 35, 43, 0.93)'],
            silver: ['rgba(224, 231, 235, 0.96)', 'rgba(164, 176, 184, 0.91)', 'rgba(83, 96, 107, 0.94)']
        };
        const TAU = Math.PI * 2;
        const SHARD_COUNT = 8;
        const PERMANENT_GAP = 4;
        const GAP_OFFSET = PERMANENT_GAP / (2 * Math.sin(Math.PI / 8));

        class AmbientParticleField {
            constructor() {
                this.width = 0;
                this.height = 0;
                this.particles = [];
                this.maxDistance = 132;
                this.lastTime = 0;
            }

            resize(width, height) {
                this.width = width;
                this.height = height;
                const desiredCount = 150;
                while (this.particles.length < desiredCount) {
                    this.particles.push({
                        x: Math.random() * width,
                        y: Math.random() * height,
                        vx: (Math.random() - 0.5) * 0.24,
                        vy: (Math.random() - 0.5) * 0.24,
                        radius: 0.85 + Math.random() * 0.65,
                        phase: Math.random() * TAU,
                        tone: this.particles.length % 2 === 0 ? 'white' : 'red'
                    });
                }
                this.particles.length = desiredCount;
                for (const particle of this.particles) {
                    particle.x = Math.min(particle.x, width);
                    particle.y = Math.min(particle.y, height);
                }
            }

            draw({ ctx, time = 0 }) {
                const step = this.lastTime ? Math.min(34, time - this.lastTime) / 16.667 : 1;
                this.lastTime = time;

                for (const particle of this.particles) {
                    particle.x += particle.vx * step;
                    particle.y += particle.vy * step;
                    if (particle.x < 0 || particle.x > this.width) particle.vx *= -1;
                    if (particle.y < 0 || particle.y > this.height) particle.vy *= -1;
                    particle.x = Math.max(0, Math.min(this.width, particle.x));
                    particle.y = Math.max(0, Math.min(this.height, particle.y));
                }

                ctx.save();
                ctx.lineWidth = 0.65;
                for (let a = 0; a < this.particles.length; a += 1) {
                    const first = this.particles[a];
                    for (let b = a + 1; b < this.particles.length; b += 1) {
                        const second = this.particles[b];
                        const distance = Math.hypot(first.x - second.x, first.y - second.y);
                        if (distance < this.maxDistance) {
                            ctx.globalAlpha = (1 - distance / this.maxDistance) * 0.14;
                            ctx.strokeStyle = '#9bb9cc';
                            ctx.beginPath();
                            ctx.moveTo(first.x, first.y);
                            ctx.lineTo(second.x, second.y);
                            ctx.stroke();
                        }
                    }
                }

                for (const particle of this.particles) {
                    const pulse = (Math.sin(time * 0.0014 + particle.phase) + 1) * 0.5;
                    ctx.globalAlpha = 0.2 + pulse * 0.16;
                    ctx.fillStyle = particle.tone === 'red'
                        ? (pulse > 0.55 ? '#ffd0d5' : '#cf8791')
                        : (pulse > 0.55 ? '#ffffff' : '#c9dce6');
                    ctx.shadowColor = particle.tone === 'red'
                        ? 'rgba(226, 61, 83, 0.82)'
                        : 'rgba(238, 248, 255, 0.9)';
                    ctx.shadowBlur = 2 + pulse * 4;
                    ctx.beginPath();
                    ctx.arc(particle.x, particle.y, particle.radius * 2 * (1 + pulse * 0.18), 0, TAU);
                    ctx.fill();
                }
                ctx.restore();
            }
        }

        class GlassShard {
            constructor(index) {
                this.index = index;
                this.tone = index % 2 === 0 ? 'red' : 'silver';
                this.angle = -Math.PI / 2 + (index + 0.5) * TAU / SHARD_COUNT;
                this.hover = 0;
            }

            getPoints(octagon, hover) {
                const offsetX = Math.cos(this.angle) * (GAP_OFFSET + hover * 13);
                const offsetY = Math.sin(this.angle) * (GAP_OFFSET + hover * 13);
                const tilt = hover * (this.index % 2 ? -0.035 : 0.035);
                const cos = Math.cos(tilt);
                const sin = Math.sin(tilt);
                const base = [
                    { x: 0, y: 0 },
                    octagon[this.index],
                    octagon[(this.index + 1) % SHARD_COUNT]
                ];
                const centroid = base.reduce((sum, point) => ({
                    x: sum.x + point.x / 3,
                    y: sum.y + point.y / 3
                }), { x: 0, y: 0 });

                return base.map((point) => {
                    const dx = point.x - centroid.x;
                    const dy = point.y - centroid.y;
                    return {
                        x: centroid.x + dx * cos - dy * sin + offsetX,
                        y: centroid.y + dx * sin + dy * cos + offsetY
                    };
                });
            }

            draw(ctx, frame) {
                const { radius, octagon, hover } = frame;
                this.hover += (hover - this.hover) * 0.075;
                if (Math.abs(hover - this.hover) < 0.001) this.hover = hover;

                const points = this.getPoints(octagon, this.hover);
                const centroid = points.reduce((sum, point) => ({
                    x: sum.x + point.x / 3,
                    y: sum.y + point.y / 3
                }), { x: 0, y: 0 });
                const palette = COLORS[this.tone];

                ctx.beginPath();
                ctx.moveTo(points[0].x, points[0].y);
                ctx.lineTo(points[1].x, points[1].y);
                ctx.lineTo(points[2].x, points[2].y);
                ctx.closePath();

                const gradient = ctx.createRadialGradient(
                    -radius * 0.32 + this.hover * Math.cos(this.angle) * 7,
                    -radius * 0.38 + this.hover * Math.sin(this.angle) * 7,
                    0,
                    centroid.x,
                    centroid.y,
                    radius * 1.18
                );
                gradient.addColorStop(0, palette[0]);
                gradient.addColorStop(0.48, palette[1]);
                gradient.addColorStop(1, palette[2]);

                ctx.save();
                ctx.shadowColor = this.tone === 'red'
                    ? 'rgba(197, 91, 110, 0.34)'
                    : 'rgba(173, 203, 220, 0.32)';
                ctx.shadowBlur = 16 + this.hover * 12;
                ctx.fillStyle = gradient;
                ctx.fill();
                ctx.restore();

                // Glass sheen and a second inset-looking edge give each shard its bevel.
                ctx.save();
                ctx.clip();
                const sheen = ctx.createLinearGradient(
                    -radius, -radius,
                    radius, radius
                );
                sheen.addColorStop(0, 'rgba(255, 255, 255, 0.3)');
                sheen.addColorStop(0.24, 'rgba(255, 255, 255, 0.07)');
                sheen.addColorStop(0.64, 'rgba(255, 255, 255, 0)');
                sheen.addColorStop(1, 'rgba(7, 11, 16, 0.23)');
                ctx.fillStyle = sheen;
                ctx.fillRect(-radius, -radius, radius * 2, radius * 2);

                const innerGlow = ctx.createRadialGradient(centroid.x, centroid.y, 0, centroid.x, centroid.y, radius * 0.9);
                innerGlow.addColorStop(0, 'rgba(255, 255, 255, 0.09)');
                innerGlow.addColorStop(0.76, 'rgba(255, 255, 255, 0.015)');
                innerGlow.addColorStop(1, 'rgba(3, 7, 11, 0.2)');
                ctx.fillStyle = innerGlow;
                ctx.fillRect(-radius, -radius, radius * 2, radius * 2);
                ctx.restore();

                ctx.beginPath();
                ctx.moveTo(points[0].x, points[0].y);
                ctx.lineTo(points[1].x, points[1].y);
                ctx.lineTo(points[2].x, points[2].y);
                ctx.closePath();
                ctx.lineJoin = 'round';
                ctx.lineWidth = 1.25;
                ctx.strokeStyle = this.tone === 'red'
                    ? 'rgba(255, 190, 199, 0.9)'
                    : 'rgba(246, 250, 252, 0.95)';
                ctx.shadowColor = this.tone === 'red'
                    ? 'rgba(186, 86, 104, 0.7)'
                    : 'rgba(186, 220, 238, 0.7)';
                ctx.shadowBlur = 7;
                ctx.stroke();
                ctx.shadowBlur = 0;
            }
        }

        class CanvasBackground {
            constructor(canvas) {
                this.canvas = canvas;
                this.ctx = canvas.getContext('2d', { alpha: false });
                this.width = 0;
                this.height = 0;
                this.dpr = 1;
                this.centerX = 0;
                this.centerY = 0;
                this.radius = 0;
                this.globalRotation = 0;
                this.octagon = [];
                this.logo = new Image();
                this.logoLoaded = false;
                this.logo.addEventListener('load', () => { this.logoLoaded = true; }, { once: true });
                this.logo.src = './THP.png';
                this.pointer = { x: -10000, y: -10000, inside: false };
                this.shards = Array.from({ length: SHARD_COUNT }, (_, index) => new GlassShard(index));
                this.projectNodes = [];
                this.hoveredProject = null;
                // Extra canvas effects (such as the planned particle matrix) can register here.
                this.layers = [new AmbientParticleField()];
                this.lastFrameTime = 0;
                this.frameId = 0;
                this.renderFrame = this.render.bind(this);
                this.onResize = this.resize.bind(this);
                this.onPointerMove = this.handlePointerMove.bind(this);
                this.onPointerLeave = this.handlePointerLeave.bind(this);
                this.onCanvasClick = this.handleCanvasClick.bind(this);
                this.onVisibilityChange = this.handleVisibilityChange.bind(this);
            }

            start() {
                window.addEventListener('resize', this.onResize, { passive: true });
                this.canvas.addEventListener('pointermove', this.onPointerMove, { passive: true });
                this.canvas.addEventListener('pointerleave', this.onPointerLeave, { passive: true });
                this.canvas.addEventListener('click', this.onCanvasClick);
                document.addEventListener('visibilitychange', this.onVisibilityChange);
                this.resize();
                this.frameId = requestAnimationFrame(this.renderFrame);
            }

            resize() {
                const rect = this.canvas.getBoundingClientRect();
                this.width = rect.width;
                this.height = rect.height;
                this.dpr = Math.min(window.devicePixelRatio || 1, 2);
                this.canvas.width = Math.round(this.width * this.dpr);
                this.canvas.height = Math.round(this.height * this.dpr);
                this.ctx.setTransform(this.dpr, 0, 0, this.dpr, 0, 0);
                this.centerX = this.width / 2;
                this.centerY = this.height / 2;
                this.radius = Math.min(this.width, this.height) * 0.34;

                // Keep the geometry local to (0, 0); the render transform centers and rotates the whole logo.
                this.octagon = Array.from({ length: SHARD_COUNT }, (_, index) => {
                    const angle = -Math.PI / 2 + index * TAU / SHARD_COUNT;
                    return {
                        x: Math.cos(angle) * this.radius,
                        y: Math.sin(angle) * this.radius
                    };
                });
                for (const layer of this.layers) layer.resize?.(this.width, this.height);
            }

            addLayer(layer) {
                this.layers.push(layer);
                return () => {
                    const index = this.layers.indexOf(layer);
                    if (index !== -1) this.layers.splice(index, 1);
                };
            }

            handlePointerMove(event) {
                const rect = this.canvas.getBoundingClientRect();
                this.pointer.x = event.clientX - rect.left;
                this.pointer.y = event.clientY - rect.top;
                this.pointer.inside = true;
            }

            handlePointerLeave() {
                this.pointer.inside = false;
                this.hoveredProject = null;
                this.canvas.style.cursor = 'default';
            }

            handleCanvasClick() {
                if (this.hoveredProject) {
                    window.location.assign(this.hoveredProject.targetUrl);
                }
            }

            handleVisibilityChange() {
                if (document.hidden) {
                    cancelAnimationFrame(this.frameId);
                } else {
                    this.frameId = requestAnimationFrame(this.renderFrame);
                }
            }

            drawEnvironment() {
                const { ctx, width, height, centerX, centerY, radius } = this;
                ctx.fillStyle = COLORS.background;
                ctx.fillRect(0, 0, width, height);

                const haze = ctx.createRadialGradient(centerX, centerY, radius * 0.12, centerX, centerY, Math.max(width, height) * 0.72);
                haze.addColorStop(0, 'rgba(47, 57, 68, 0.34)');
                haze.addColorStop(0.42, 'rgba(23, 28, 34, 0.25)');
                haze.addColorStop(1, 'rgba(5, 6, 8, 0)');
                ctx.fillStyle = haze;
                ctx.fillRect(0, 0, width, height);

                const spacing = 52;
                ctx.beginPath();
                for (let x = centerX % spacing; x < width; x += spacing) {
                    ctx.moveTo(x, 0);
                    ctx.lineTo(x, height);
                }
                for (let y = centerY % spacing; y < height; y += spacing) {
                    ctx.moveTo(0, y);
                    ctx.lineTo(width, y);
                }
                ctx.strokeStyle = 'rgba(157, 179, 196, 0.055)';
                ctx.lineWidth = 1;
                ctx.stroke();

                const vignette = ctx.createRadialGradient(centerX, centerY, radius * 0.75, centerX, centerY, Math.max(width, height) * 0.72);
                vignette.addColorStop(0, 'rgba(5, 6, 8, 0)');
                vignette.addColorStop(1, 'rgba(5, 6, 8, 0.78)');
                ctx.fillStyle = vignette;
                ctx.fillRect(0, 0, width, height);
            }

            updateProjectNodes(time) {
                const nodeRadius = Math.min(this.width, this.height) * 0.43;
                const wobble = time * 0.00034;
                this.projectNodes = window.hiveProjects.map((project, index, projects) => {
                    const baseAngle = -Math.PI / 2 + (index + 0.5) * TAU / projects.length;
                    const angle = baseAngle + this.globalRotation * 0.18 + Math.sin(wobble + index * 1.7) * 0.022;
                    const trembleX = Math.sin(time * 0.0012 + index * 2.4) * 2.4 + Math.sin(time * 0.0007 + index) * 0.9;
                    const trembleY = Math.cos(time * 0.001 + index * 1.9) * 2.2 + Math.sin(time * 0.00065 + index * 2) * 0.8;
                    const x = this.centerX + Math.cos(angle) * nodeRadius + trembleX;
                    const y = this.centerY + Math.sin(angle) * nodeRadius + trembleY;
                    const fontSize = Math.max(14, Math.min(17, Math.min(this.width, this.height) * 0.02));
                    const labelOnRight = Math.cos(angle) >= -0.12;

                    this.ctx.save();
                    this.ctx.font = `500 ${fontSize}px "SFMono-Regular", Consolas, "Liberation Mono", monospace`;
                    const labelWidth = this.ctx.measureText(project.name).width;
                    this.ctx.restore();

                    const placeLabelRight = labelOnRight
                        ? x + 13 + labelWidth <= this.width - 8
                        : x - 13 - labelWidth < 8;
                    const labelX = x + (placeLabelRight ? 13 : -13);

                    return {
                        ...project,
                        index,
                        angle,
                        x,
                        y,
                        fontSize,
                        labelX,
                        labelWidth,
                        align: placeLabelRight ? 'left' : 'right',
                        hitBox: {
                            left: placeLabelRight ? x - 7 : labelX - labelWidth - 4,
                            right: placeLabelRight ? labelX + labelWidth + 4 : x + 7,
                            top: y - fontSize * 0.8,
                            bottom: y + fontSize * 0.8
                        }
                    };
                });
                this.hoveredProject = this.projectNodes.find(({ hitBox }) => (
                    this.pointer.x >= hitBox.left && this.pointer.x <= hitBox.right &&
                    this.pointer.y >= hitBox.top && this.pointer.y <= hitBox.bottom
                )) || null;
                this.canvas.style.cursor = this.hoveredProject ? 'pointer' : 'default';
            }

            drawProjectNetwork(time) {
                const { ctx, centerX, centerY } = this;
                this.updateProjectNodes(time);

                for (const node of this.projectNodes) {
                    ctx.save();
                    ctx.beginPath();
                    ctx.moveTo(centerX, centerY);
                    ctx.lineTo(node.x, node.y);
                    ctx.setLineDash([3, 8]);
                    ctx.lineDashOffset = -(time * 0.018 + node.index * 7);
                    ctx.strokeStyle = this.hoveredProject?.id === node.id
                        ? 'rgba(185, 220, 239, 0.4)'
                        : 'rgba(143, 170, 188, 0.17)';
                    ctx.lineWidth = 0.8;
                    ctx.stroke();
                    ctx.setLineDash([]);

                    const progress = (time * 0.00012 + node.index / this.projectNodes.length) % 1;
                    const particleX = centerX + (node.x - centerX) * progress;
                    const particleY = centerY + (node.y - centerY) * progress;
                    const pulse = 1 + Math.sin(time * 0.004 + node.index) * 0.2;
                    ctx.beginPath();
                    ctx.arc(particleX, particleY, 1.6 * pulse, 0, TAU);
                    ctx.fillStyle = 'rgba(190, 228, 247, 0.88)';
                    ctx.shadowColor = 'rgba(139, 207, 239, 0.85)';
                    ctx.shadowBlur = 9;
                    ctx.fill();
                    ctx.restore();
                }
            }

            drawProjectNodes(time) {
                const { ctx } = this;
                for (const node of this.projectNodes) {
                    const hovered = this.hoveredProject?.id === node.id;
                    const pulse = 1 + (Math.sin(time * 0.003 + node.index * 1.3) + 1) * 0.09;
                    const dotRadius = (hovered ? 6.8 : 5) * pulse;

                    ctx.save();
                    ctx.beginPath();
                    ctx.arc(node.x, node.y, dotRadius + (hovered ? 5 : 3), 0, TAU);
                    ctx.strokeStyle = hovered ? 'rgba(213, 242, 255, 0.72)' : 'rgba(164, 221, 246, 0.5)';
                    ctx.lineWidth = 1.25;
                    ctx.stroke();

                    ctx.beginPath();
                    ctx.arc(node.x, node.y, dotRadius, 0, TAU);
                    ctx.fillStyle = hovered ? '#ffffff' : '#d8f3ff';
                    ctx.shadowColor = hovered ? '#b8eaff' : 'rgba(139, 216, 246, 0.98)';
                    ctx.shadowBlur = hovered ? 19 : 14;
                    ctx.fill();

                    ctx.font = `500 ${node.fontSize}px "SFMono-Regular", Consolas, "Liberation Mono", monospace`;
                    ctx.textAlign = node.align;
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = hovered ? '#ffffff' : 'rgba(224, 235, 242, 0.88)';
                    ctx.shadowColor = hovered ? 'rgba(159, 218, 246, 0.85)' : 'rgba(96, 148, 174, 0.42)';
                    ctx.shadowBlur = hovered ? 12 : 5;
                    ctx.fillText(node.name, node.labelX, node.y);
                    ctx.restore();
                }
            }

            drawBranding() {
                const { ctx, centerX, centerY, radius } = this;
                const titleSize = Math.max(15, Math.min(23, radius * 0.065));
                const logoSize = Math.min(300, radius * 0.82) * 0.7;
                const title = 'The Hive Project';
                const tracking = titleSize * 0.16;
                ctx.save();
                ctx.font = `500 ${titleSize}px "Trebuchet MS", "Arial", sans-serif`;
                const titleWidth = [...title].reduce((width, character, index) => {
                    return width + ctx.measureText(character).width + (index < title.length - 1 ? tracking : 0);
                }, 0);

                ctx.textBaseline = 'middle';
                ctx.fillStyle = 'rgba(223, 232, 238, 0.96)';
                ctx.shadowColor = 'rgba(157, 199, 222, 0.75)';
                ctx.shadowBlur = 15;
                let textX = centerX - titleWidth / 2;
                const titleY = Math.max(titleSize + 12, centerY - logoSize / 2 - titleSize * 1.2);
                for (const character of title) {
                    ctx.fillText(character, textX, titleY);
                    textX += ctx.measureText(character).width + tracking;
                }

                ctx.globalAlpha = 0.96;
                if (this.logoLoaded) {
                    ctx.shadowColor = 'rgba(5, 8, 11, 0.75)';
                    ctx.shadowBlur = 18;
                    ctx.drawImage(this.logo, centerX - logoSize / 2, centerY - logoSize / 2, logoSize, logoSize);
                }
                ctx.restore();
            }

            render(time = 0) {
                const delta = this.lastFrameTime ? Math.min(34, time - this.lastFrameTime) : 0;
                this.lastFrameTime = time;
                const distance = Math.hypot(this.pointer.x - this.centerX, this.pointer.y - this.centerY);
                const hoverRange = this.radius + Math.min(this.width, this.height) * 0.2;
                const proximity = this.pointer.inside ? Math.max(0, Math.min(1, 1 - distance / hoverRange)) : 0;
                const hover = proximity * proximity * (3 - 2 * proximity);
                const frame = {
                    ctx: this.ctx,
                    width: this.width,
                    height: this.height,
                    centerX: this.centerX,
                    centerY: this.centerY,
                    radius: this.radius,
                    octagon: this.octagon,
                    hover,
                    time,
                    delta
                };

                this.drawEnvironment();
                for (const layer of this.layers) {
                    this.ctx.save();
                    layer.draw(frame);
                    this.ctx.restore();
                }

                this.drawProjectNetwork(time);
                this.globalRotation = (this.globalRotation + 0.00035) % TAU;
                this.ctx.save();
                this.ctx.translate(this.centerX, this.centerY);
                this.ctx.rotate(this.globalRotation);
                for (const shard of this.shards) shard.draw(this.ctx, frame);
                this.ctx.restore();
                this.drawBranding();
                this.drawProjectNodes(time);
                this.frameId = requestAnimationFrame(this.renderFrame);
            }
        }

        new CanvasBackground(document.getElementById('dashboard-canvas')).start();
    })();
</script>
</body>
</html>
