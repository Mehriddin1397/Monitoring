<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCTV Video Monitor Devori — Jonli Kuzatuv</title>
    <link rel="stylesheet" type="text/css" href="/admin/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/admin/assets/vendors/css/vendors.min.css" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body, html {
            width: 100%;
            height: 100%;
            background-color: #0b0f19;
            color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            overflow: hidden;
        }

        .wall-header {
            height: 48px;
            background: #111827;
            border-bottom: 1px solid #1f2937;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            z-index: 10;
        }

        .wall-container {
            height: calc(100vh - 48px);
            width: 100vw;
            display: grid;
            gap: 4px;
            padding: 4px;
            background-color: #030712;
            overflow: hidden;
        }

        /* Grid layouts */
        .grid-2x2 {
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, 1fr);
        }
        .grid-3x3 {
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(3, 1fr);
        }
        .grid-4x3 {
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(3, 1fr);
        }
        .grid-auto {
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            grid-auto-rows: 1fr;
        }

        .cam-cell {
            position: relative;
            background-color: #000000;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid #1f2937;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cam-cell:hover {
            border-color: #3b82f6;
        }

        .cam-cell iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        .cam-label {
            position: absolute;
            top: 6px;
            left: 6px;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            color: #ffffff;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 3;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .cam-action-btn {
            position: absolute;
            top: 6px;
            right: 6px;
            background: rgba(0, 0, 0, 0.65);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 3px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 11px;
            z-index: 3;
            transition: all 0.2s;
            opacity: 0;
        }

        .cam-cell:hover .cam-action-btn {
            opacity: 1;
        }

        .cam-action-btn:hover {
            background: #2563eb;
            border-color: #3b82f6;
        }

        .pulse-live {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #10b981;
            display: inline-block;
            box-shadow: 0 0 8px #10b981;
        }

        .offline-notice {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 12px;
            text-align: center;
            padding: 10px;
        }

        /* Floating controls */
        .controls-btn {
            background: #1f2937;
            color: #e2e8f0;
            border: 1px solid #374151;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .controls-btn:hover, .controls-btn.active {
            background: #2563eb;
            color: #ffffff;
            border-color: #3b82f6;
        }
    </style>
</head>
<body>
    <!-- Wall Top Bar -->
    <header class="wall-header">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('cctv.index') }}" class="controls-btn text-decoration-none">
                <i class="feather-arrow-left me-1"></i> Panelga qaytish
            </a>
            <div class="d-flex align-items-center gap-2">
                <span class="pulse-live"></span>
                <span class="fw-bold fs-14">CCTV VIDEO MONITOR DEVORI</span>
                <span class="badge bg-dark border border-secondary text-white-50 fs-11">
                    {{ count($cameras) }} ta kamera
                </span>
            </div>
        </div>

        <!-- Grid Layout Pickers -->
        <div class="d-flex align-items-center gap-2">
            <span class="fs-12 text-white-50 me-1">To'r:</span>
            <button class="controls-btn" onclick="setGrid('grid-2x2', this)">2 × 2</button>
            <button class="controls-btn" onclick="setGrid('grid-3x3', this)">3 × 3</button>
            <button class="controls-btn active" onclick="setGrid('grid-4x3', this)">4 × 3</button>
            <button class="controls-btn" onclick="setGrid('grid-auto', this)">Avto</button>

            <span class="border-start border-secondary mx-2" style="height: 18px;"></span>

            <!-- Sifat -->
            <a href="{{ request()->fullUrlWithQuery(['quality' => 'sub']) }}" 
               class="controls-btn text-decoration-none {{ $quality === 'sub' ? 'active' : '' }}">
               SD (Sub)
            </a>
            <a href="{{ request()->fullUrlWithQuery(['quality' => 'main']) }}" 
               class="controls-btn text-decoration-none {{ $quality === 'main' ? 'active' : '' }}">
               HD (Main)
            </a>

            <button class="controls-btn" onclick="toggleFullscreen()">
                <i class="feather-maximize me-1"></i> F11
            </button>
        </div>

        <!-- Live Clock -->
        <div class="d-flex align-items-center gap-2 text-white-50 fs-12">
            <span id="liveClock" class="font-monospace fw-bold text-white fs-13">--:--:--</span>
        </div>
    </header>

    <!-- Wall Container with Cameras -->
    <main class="wall-container grid-4x3" id="wallGrid">
        @foreach ($cameras as $cam)
            <div class="cam-cell" id="cam-cell-{{ $cam['id'] }}">
                <div class="cam-label">
                    <span class="{{ $cam['status'] === 'online' ? 'pulse-live' : 'bg-danger rounded-circle d-inline-block' }}" style="width: 7px; height: 7px;"></span>
                    <span>{{ $cam['name'] }}</span>
                    @if (!empty($cam['is_ptz']))
                        <span class="text-warning fs-9">PTZ</span>
                    @endif
                </div>

                <button class="cam-action-btn" onclick="expandCell({{ $cam['id'] }})" title="Kattalashtirish">
                    <i class="feather-maximize-2"></i>
                </button>

                @if ($cam['status'] === 'online' && !empty($cam['embed_url']))
                    <iframe id="frame-{{ $cam['id'] }}" 
                            src="{{ $cam['embed_url'] }}" 
                            allow="fullscreen; autoplay" 
                            allowfullscreen>
                    </iframe>
                @else
                    <div class="offline-notice">
                        <i class="feather-alert-triangle fs-24 text-warning mb-1"></i>
                        <strong>{{ $cam['name'] }}</strong>
                        <span class="text-danger fs-11 mt-1">Aloqa yo'q (Oflayn)</span>
                        <span class="text-muted fs-10 mt-1">IP: {{ $cam['ip_address'] }}</span>
                    </div>
                @endif
            </div>
        @endforeach
    </main>

    <script>
        // Live clock
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('uz-UZ', { hour12: false });
            const dateStr = now.toLocaleDateString('uz-UZ');
            document.getElementById('liveClock').innerText = dateStr + ' ' + timeStr;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Change Grid layout
        function setGrid(gridClass, btn) {
            const grid = document.getElementById('wallGrid');
            grid.className = 'wall-container ' + gridClass;

            document.querySelectorAll('.controls-btn').forEach(b => {
                if (['2 × 2', '3 × 3', '4 × 3', 'Avto'].includes(b.innerText.trim())) {
                    b.classList.remove('active');
                }
            });
            if (btn) btn.classList.add('active');
        }

        // Expand cell to full screen
        function expandCell(camId) {
            const cell = document.getElementById('cam-cell-' + camId);
            if (!cell) return;
            if (cell.requestFullscreen) {
                cell.requestFullscreen();
            } else if (cell.webkitRequestFullscreen) {
                cell.webkitRequestFullscreen();
            } else if (cell.msRequestFullscreen) {
                cell.msRequestFullscreen();
            }
        }

        // Toggle page fullscreen
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }
    </script>
</body>
</html>
