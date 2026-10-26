@extends('layouts.admin')

@section('title', 'PTZ Kamera Boshqaruv Markazi')

@section('content')
<div class="nxl-content d-flex flex-column h-100">
    <!-- [ Page Header ] start -->
    <div class="page-header py-3 px-3 px-lg-4 shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-text avatar-lg bg-primary text-white rounded-circle shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                    <i class="feather-compass fs-24"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="mb-1 text-white fw-bold fs-22">PTZ Kamera Boshqaruv Markazi</h3>
                        <span class="badge bg-primary text-uppercase px-2 py-1 fs-11">
                            <span class="pulse-dot"></span> LIVE PTZ
                        </span>
                    </div>
                    <p class="fs-14 text-white-50 mb-0">
                        8 yo'nalishli burish (Pan/Tilt), yaqinlashtirish (Zoom), tezlik va saqlangan nuqtalar (Presets)
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Camera Select Dropdown -->
                <div class="d-flex align-items-center gap-2">
                    <span class="text-white-50 fs-13">Kamera:</span>
                    <select id="ptzCameraSelect" class="form-select form-select-sm bg-dark text-white border-secondary" style="min-width: 220px;" onchange="changePtzCamera(this.value)">
                        @foreach ($ptzCameras as $cam)
                            <option value="{{ $cam['id'] }}" {{ ($activeCamera['id'] ?? 0) == $cam['id'] ? 'selected' : '' }}>
                                {{ $cam['name'] }} {{ !empty($cam['is_ptz']) ? '(PTZ)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Quality toggle -->
                <div class="btn-group" role="group">
                    <a href="{{ request()->fullUrlWithQuery(['quality' => 'sub']) }}" 
                       class="btn btn-sm {{ $quality === 'sub' ? 'btn-primary' : 'btn-outline-light' }}">
                        Sub (SD)
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['quality' => 'main']) }}" 
                       class="btn btn-sm {{ $quality === 'main' ? 'btn-primary' : 'btn-outline-light' }}">
                        Main (HD)
                    </a>
                </div>

                <!-- Back to all cameras -->
                <a href="{{ route('cctv.index') }}" class="btn btn-outline-light btn-sm d-flex align-items-center gap-1">
                    <i class="feather-grid"></i>
                    <span>Barcha kameralar</span>
                </a>
            </div>
        </div>
    </div>
    <!-- [ Page Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content p-3 p-lg-4">
        <div class="row g-4">

            <!-- LEFT: Video Stream and Telemetry (8 cols) -->
            <div class="col-xl-8 col-lg-7">
                <!-- Video Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                    <div class="card-header bg-dark text-white px-3 py-2 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="pulse-dot-green"></span>
                            <span class="fw-bold fs-15" id="ptzCamName">{{ $activeCamera['name'] ?? 'Kamera' }}</span>
                            <span class="badge bg-secondary fs-10" id="ptzCamIp">{{ $activeCamera['ip_address'] ?? '-' }}</span>
                            @if (!empty($activeCamera['is_ptz']))
                                <span class="badge bg-warning text-dark fs-10">PTZ Faol</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span id="motionStatusBadge" class="badge bg-dark border border-secondary text-white-50 fs-11">
                                Tinch holatda
                            </span>
                            <button class="btn btn-xs btn-outline-light" onclick="toggleFullscreen()" title="To'liq ekran">
                                <i class="feather-maximize"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Video Iframe Container -->
                    <div class="card-body p-0 bg-black position-relative" id="ptzVideoWrapper">
                        <div class="ratio ratio-16x9">
                            <iframe id="ptzVideoFrame" 
                                    src="{{ $activeCamera['embed_url'] ?? '' }}" 
                                    allow="fullscreen; autoplay" 
                                    allowfullscreen 
                                    style="border: 0; width: 100%; height: 100%;">
                            </iframe>
                        </div>
                    </div>
                </div>

                <!-- Live Telemetry / Angles Bar -->
                <div class="row g-3">
                    <div class="col-4">
                        <div class="card border-0 shadow-sm rounded-4 text-center p-3">
                            <span class="fs-11 text-muted text-uppercase fw-bold letter-spacing-1 d-block mb-1">Azimut (Pan)</span>
                            <h3 class="fw-bold text-primary mb-0 font-monospace" id="statusAzimuth">0.0°</h3>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card border-0 shadow-sm rounded-4 text-center p-3">
                            <span class="fs-11 text-muted text-uppercase fw-bold letter-spacing-1 d-block mb-1">Burchak (Tilt)</span>
                            <h3 class="fw-bold text-success mb-0 font-monospace" id="statusElevation">0.0°</h3>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card border-0 shadow-sm rounded-4 text-center p-3">
                            <span class="fs-11 text-muted text-uppercase fw-bold letter-spacing-1 d-block mb-1">Kattalashtirish (Zoom)</span>
                            <h3 class="fw-bold text-info mb-0 font-monospace" id="statusZoom">1.0x</h3>
                        </div>
                    </div>
                </div>

                <!-- Keyboard shortcuts guide -->
                <div class="card border-0 shadow-sm rounded-4 mt-3 bg-light">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-2 text-muted fs-12 flex-wrap">
                            <strong class="text-dark"><i class="feather-command me-1"></i>Klaviatura tugmalari:</strong>
                            <span><kbd>W</kbd> Yuqoriga</span> •
                            <span><kbd>S</kbd> Pastga</span> •
                            <span><kbd>A</kbd> Chapga</span> •
                            <span><kbd>D</kbd> O'ngga</span> •
                            <span><kbd>Space</kbd> To'xtatish</span> •
                            <span><kbd>+</kbd> / <kbd>-</kbd> Zoom</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: PTZ Joystick & Presets Panel (4 cols) -->
            <div class="col-xl-4 col-lg-5">

                <!-- Joystick Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom text-center">
                        <h6 class="fw-bold mb-1">Burish va Yo'naltirish (PTZ Pad)</h6>
                        <span class="fs-11 text-muted">Tugmani bosib turing yoki bir marta bosing</span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column align-items-center">

                        <!-- 8-Way Joystick Pad -->
                        <div class="ptz-pad-grid mb-3">
                            <!-- Row 1 -->
                            <button class="ptz-key" title="Yuqori-Chap"
                                    onmousedown="ptzStart(-1, 1)" onmouseup="ptzStop()" ontouchstart="ptzStart(-1, 1)" ontouchend="ptzStop()">
                                ◤
                            </button>
                            <button class="ptz-key ptz-main-dir" title="Yuqoriga (W)"
                                    onmousedown="ptzStart(0, 1)" onmouseup="ptzStop()" ontouchstart="ptzStart(0, 1)" ontouchend="ptzStop()">
                                ▲
                            </button>
                            <button class="ptz-key" title="Yuqori-O'ng"
                                    onmousedown="ptzStart(1, 1)" onmouseup="ptzStop()" ontouchstart="ptzStart(1, 1)" ontouchend="ptzStop()">
                                ◥
                            </button>

                            <!-- Row 2 -->
                            <button class="ptz-key ptz-main-dir" title="Chapga (A)"
                                    onmousedown="ptzStart(-1, 0)" onmouseup="ptzStop()" ontouchstart="ptzStart(-1, 0)" ontouchend="ptzStop()">
                                ◄
                            </button>
                            <button class="ptz-key ptz-stop-key" title="To'xtatish (Space)" onclick="ptzStop()">
                                STOP
                            </button>
                            <button class="ptz-key ptz-main-dir" title="O'ngga (D)"
                                    onmousedown="ptzStart(1, 0)" onmouseup="ptzStop()" ontouchstart="ptzStart(1, 0)" ontouchend="ptzStop()">
                                ►
                            </button>

                            <!-- Row 3 -->
                            <button class="ptz-key" title="Past-Chap"
                                    onmousedown="ptzStart(-1, -1)" onmouseup="ptzStop()" ontouchstart="ptzStart(-1, -1)" ontouchend="ptzStop()">
                                ◣
                            </button>
                            <button class="ptz-key ptz-main-dir" title="Pastga (S)"
                                    onmousedown="ptzStart(0, -1)" onmouseup="ptzStop()" ontouchstart="ptzStart(0, -1)" ontouchend="ptzStop()">
                                ▼
                            </button>
                            <button class="ptz-key" title="Past-O'ng"
                                    onmousedown="ptzStart(1, -1)" onmouseup="ptzStop()" ontouchstart="ptzStart(1, -1)" ontouchend="ptzStop()">
                                ◢
                            </button>
                        </div>

                        <!-- Zoom Buttons -->
                        <div class="row g-2 w-100 mb-3" style="max-width: 250px;">
                            <div class="col-6">
                                <button class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-1 fs-12 fw-bold py-2"
                                        onmousedown="zoomStart(1)" onmouseup="ptzStop()" ontouchstart="zoomStart(1)" ontouchend="ptzStop()">
                                    <i class="feather-zoom-in"></i> Yaqinlashtirish
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-1 fs-12 fw-bold py-2"
                                        onmousedown="zoomStart(-1)" onmouseup="ptzStop()" ontouchstart="zoomStart(-1)" ontouchend="ptzStop()">
                                    <i class="feather-zoom-out"></i> Uzoqlashtirish
                                </button>
                            </div>
                        </div>

                        <!-- Sliders for Speed and Step Duration -->
                        <div class="w-100 border-top pt-3 space-y-3">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between fs-12 mb-1">
                                    <span class="text-muted fw-semibold">Harakat tezligi:</span>
                                    <strong class="text-primary font-monospace" id="speedVal">50%</strong>
                                </div>
                                <input type="range" class="form-range" id="sliderSpeed" min="10" max="100" value="50" oninput="updateSpeed(this.value)">
                            </div>
                            <div>
                                <div class="d-flex justify-content-between fs-12 mb-1">
                                    <span class="text-muted fw-semibold">Bir martalik qadam (davomiylik):</span>
                                    <strong class="text-primary font-monospace" id="durationVal">400 ms</strong>
                                </div>
                                <input type="range" class="form-range" id="sliderDuration" min="100" max="1500" step="50" value="400" oninput="updateDuration(this.value)">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Presets Card -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="feather-map-pin text-primary"></i>
                            <h6 class="fw-bold mb-0">Saqlangan Nuqtalar (Presets)</h6>
                        </div>
                        <button class="btn btn-success btn-xs d-flex align-items-center gap-1 rounded-pill px-2 py-1" onclick="promptSavePreset()">
                            <i class="feather-plus"></i> Saqlash
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div id="presetsList" class="d-flex flex-column gap-2" style="max-height: 260px; overflow-y: auto;">
                            <div class="text-center text-muted fs-12 py-3">Nuqtalar yuklanmoqda...</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<style>
    .pulse-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #ffffff;
        border-radius: 50%;
        margin-right: 4px;
        animation: pulseAnimation 1.5s infinite;
    }
    .pulse-dot-green {
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        margin-right: 4px;
        animation: pulseAnimation 1.5s infinite;
    }
    @keyframes pulseAnimation {
        0% { transform: scale(0.95); opacity: 0.7; }
        50% { transform: scale(1.3); opacity: 1; }
        100% { transform: scale(0.95); opacity: 0.7; }
    }

    /* Joystick 3x3 Grid */
    .ptz-pad-grid {
        display: grid;
        grid-template-columns: repeat(3, 70px);
        grid-template-rows: repeat(3, 70px);
        gap: 8px;
    }

    .ptz-key {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        font-size: 1.25rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.12s ease;
        user-select: none;
        -webkit-user-select: none;
    }

    .ptz-key:hover {
        background: #e2e8f0;
        border-color: #94a3b8;
    }

    .ptz-key:active {
        background: #2563eb;
        color: #ffffff;
        transform: scale(0.95);
        border-color: #2563eb;
    }

    .ptz-main-dir {
        background: #e2e8f0;
        font-size: 1.35rem;
        color: #1e293b;
    }

    .ptz-stop-key {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fca5a5;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .ptz-stop-key:hover {
        background: #fecaca;
        color: #b91c1c;
    }

    .ptz-stop-key:active {
        background: #dc2626;
        color: #ffffff;
    }

    kbd {
        background-color: #1e293b;
        color: #ffffff;
        padding: 2px 5px;
        border-radius: 4px;
        font-size: 11px;
    }
</style>
@endsection

@push('scripts')
<script>
    let currentCamId = {{ $activeCamera['id'] ?? 1 }};
    let currentSpeed = 50;
    let currentDuration = 400;
    let isMoving = false;
    let statusInterval = null;
    let presets = [];

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function () {
        loadPtzStatus();
        loadPresets();
        statusInterval = setInterval(loadPtzStatus, 3000);
    });

    function changePtzCamera(id) {
        window.location.href = "{{ route('cctv.ptz') }}?camera=" + id + "&quality={{ $quality }}";
    }

    function updateSpeed(val) {
        currentSpeed = parseInt(val);
        document.getElementById('speedVal').innerText = val + '%';
    }

    function updateDuration(val) {
        currentDuration = parseInt(val);
        document.getElementById('durationVal').innerText = val + ' ms';
    }

    // Set UI Motion Indicator
    function setMotionIndicator(moving, text = '') {
        const badge = document.getElementById('motionStatusBadge');
        if (!badge) return;
        if (moving) {
            badge.className = 'badge bg-danger text-white fs-11';
            badge.innerHTML = '<span class="pulse-dot"></span> ' + (text || 'Harakatda...');
        } else {
            badge.className = 'badge bg-dark border border-secondary text-white-50 fs-11';
            badge.innerHTML = 'Tinch holatda';
        }
    }

    // 1. Start continuous movement (pan/tilt)
    async function ptzStart(pan, tilt) {
        if (!currentCamId) return;
        isMoving = true;
        setMotionIndicator(true, 'Burilmoqda');

        const speedFactor = currentSpeed / 100;
        const p = Math.round(pan * 100 * speedFactor);
        const t = Math.round(tilt * 100 * speedFactor);

        try {
            await fetch(`/admin/cctv/${currentCamId}/ptz/continuous`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ pan: p, tilt: t, zoom: 0 })
            });
        } catch (e) {
            console.error('ptzStart error:', e);
        }
    }

    // 2. Start Zoom
    async function zoomStart(dir) {
        if (!currentCamId) return;
        isMoving = true;
        setMotionIndicator(true, dir > 0 ? 'Yaqinlashmoqda' : 'Uzoqlashmoqda');

        const z = Math.round(dir * currentSpeed);

        try {
            await fetch(`/admin/cctv/${currentCamId}/ptz/continuous`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ pan: 0, tilt: 0, zoom: z })
            });
        } catch (e) {
            console.error('zoomStart error:', e);
        }
    }

    // 3. Stop movement
    async function ptzStop() {
        if (!currentCamId) return;
        isMoving = false;
        setMotionIndicator(false);

        try {
            await fetch(`/admin/cctv/${currentCamId}/ptz/stop`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        } catch (e) {
            console.error('ptzStop error:', e);
        }

        setTimeout(loadPtzStatus, 400);
    }

    // 4. Read PTZ Telemetry Status
    async function loadPtzStatus() {
        if (!currentCamId || isMoving) return;
        try {
            const res = await fetch(`/admin/cctv/${currentCamId}/ptz/status`);
            const json = await res.json();
            if (json.success && json.status) {
                const az = (json.status.azimuth / 10).toFixed(1);
                const el = (json.status.elevation / 10).toFixed(1);
                const zm = (json.status.absoluteZoom / 10).toFixed(1);

                document.getElementById('statusAzimuth').innerText = `${az}°`;
                document.getElementById('statusElevation').innerText = `${el}°`;
                document.getElementById('statusZoom').innerText = `${zm}x`;
            }
        } catch (e) {}
    }

    // 5. Presets Management
    async function loadPresets() {
        if (!currentCamId) return;
        const listEl = document.getElementById('presetsList');
        try {
            const res = await fetch(`/admin/cctv/${currentCamId}/ptz/presets`);
            const json = await res.json();
            presets = json.presets || [];

            if (presets.length === 0) {
                listEl.innerHTML = '<div class="text-center text-muted fs-12 py-3">Hozircha saqlangan nuqtalar yo\'q.</div>';
                return;
            }

            listEl.innerHTML = '';
            presets.forEach(p => {
                const item = document.createElement('div');
                item.className = 'd-flex align-items-center justify-content-between p-2 rounded-3 bg-light border';
                item.innerHTML = `
                    <div class="d-flex align-items-center gap-2 text-truncate">
                        <span class="badge bg-primary-subtle text-primary fw-bold fs-11">#${p.id}</span>
                        <span class="fw-semibold fs-13 text-truncate" style="max-width: 140px;">${p.name || 'Nuqta #' + p.id}</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <button onclick="goToPreset(${p.id})" class="btn btn-primary btn-xs px-2" title="Shu nuqtaga o'tish">
                            O'tish
                        </button>
                        <button onclick="deletePreset(${p.id})" class="btn btn-outline-danger btn-xs px-1 text-danger" title="O'chirish">
                            &times;
                        </button>
                    </div>
                `;
                listEl.appendChild(item);
            });
        } catch (e) {
            listEl.innerHTML = '<div class="text-center text-danger fs-12 py-3">Nuqtalarni yuklab bo\'lmadi</div>';
        }
    }

    // 6. Go to Preset
    async function goToPreset(presetId) {
        if (!currentCamId) return;
        setMotionIndicator(true, 'Nuqtaga o\'tilmoqda');
        try {
            await fetch(`/admin/cctv/${currentCamId}/ptz/preset/${presetId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        } catch (e) {
            console.error(e);
        }
        setTimeout(() => {
            setMotionIndicator(false);
            loadPtzStatus();
        }, 1500);
    }

    // 7. Save Preset
    async function promptSavePreset() {
        if (!currentCamId) return;
        const idStr = prompt("Yangi nuqta raqami (1-300):", presets.length + 1);
        if (!idStr) return;
        const presetId = parseInt(idStr);
        if (isNaN(presetId) || presetId < 1 || presetId > 300) {
            alert("Raqam 1 va 300 oralig'ida bo'lishi kerak!");
            return;
        }

        const presetName = prompt("Nuqta nomi (masalan: Kirish shlakbaun):", `Nuqta #${presetId}`);
        if (!presetName) return;

        try {
            const res = await fetch(`/admin/cctv/${currentCamId}/ptz/presets`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ preset_id: presetId, name: presetName })
            });
            const json = await res.json();
            if (json.success) {
                loadPresets();
            } else {
                alert(json.message || "Saqlashda xatolik yuz berdi");
            }
        } catch (e) {
            alert("Bog'lanishda xatolik!");
        }
    }

    // 8. Delete Preset
    async function deletePreset(presetId) {
        if (!currentCamId || !confirm(`#${presetId} nuqtani o'chirmoqchimisiz?`)) return;
        try {
            await fetch(`/admin/cctv/${currentCamId}/ptz/presets/${presetId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            loadPresets();
        } catch (e) {
            alert("O'chirishda xatolik!");
        }
    }

    // 9. Fullscreen toggle
    function toggleFullscreen() {
        const frame = document.getElementById('ptzVideoFrame');
        if (frame.requestFullscreen) {
            frame.requestFullscreen();
        } else if (frame.webkitRequestFullscreen) {
            frame.webkitRequestFullscreen();
        }
    }

    // 10. Keyboard shortcuts listener
    const keyState = {};
    window.addEventListener('keydown', (e) => {
        // Ignore typing in input fields
        if (['INPUT', 'SELECT', 'TEXTAREA'].includes(e.target.tagName)) return;

        const key = e.key.toLowerCase();
        if (keyState[key]) return; // prevent repeated triggers
        keyState[key] = true;

        if (key === 'w' || key === 'arrowup') ptzStart(0, 1);
        else if (key === 's' || key === 'arrowdown') ptzStart(0, -1);
        else if (key === 'a' || key === 'arrowleft') ptzStart(-1, 0);
        else if (key === 'd' || key === 'arrowright') ptzStart(1, 0);
        else if (key === 'q') ptzStart(-1, 1);
        else if (key === 'e') ptzStart(1, 1);
        else if (key === 'z') ptzStart(-1, -1);
        else if (key === 'c') ptzStart(1, -1);
        else if (key === ' ' || key === 'spacebar') {
            e.preventDefault();
            ptzStop();
        } else if (key === '+' || key === '=') zoomStart(1);
        else if (key === '-' || key === '_') zoomStart(-1);
    });

    window.addEventListener('keyup', (e) => {
        if (['INPUT', 'SELECT', 'TEXTAREA'].includes(e.target.tagName)) return;
        const key = e.key.toLowerCase();
        keyState[key] = false;
        if (['w', 's', 'a', 'd', 'q', 'e', 'z', 'c', '+', '=', '-', '_', 'arrowup', 'arrowdown', 'arrowleft', 'arrowright'].includes(key)) {
            ptzStop();
        }
    });
</script>
@endpush
