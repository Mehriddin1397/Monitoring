@extends('layouts.admin')

@section('title', 'Videokuzatuv tizimi (CCTV)')

@section('content')
<div class="nxl-content d-flex flex-column h-100">
    <!-- [ Page Header ] start -->
    <div class="page-header py-3 px-3 px-lg-4 shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-text avatar-lg bg-danger text-white rounded-circle shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                    <i class="feather-video fs-24"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="mb-1 text-white fw-bold fs-22">Videokuzatuv tizimi (CCTV)</h3>
                        <span class="badge bg-danger text-uppercase px-2 py-1 fs-11 live-badge">
                            <span class="pulse-dot"></span> LIVE STREAM
                        </span>
                    </div>
                    <p class="fs-14 text-white-50 mb-0">
                        CCTV Server (192.168.40.123) orqali hududdagi jonli videokameralar monitoringi
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Quality toggle -->
                <div class="btn-group" role="group">
                    <a href="{{ request()->fullUrlWithQuery(['quality' => 'sub']) }}" 
                       class="btn btn-sm {{ $quality === 'sub' ? 'btn-primary' : 'btn-outline-light' }}"
                       title="Tezkor va yengil sifat">
                        <i class="feather-zap me-1"></i> Sub (SD)
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['quality' => 'main']) }}" 
                       class="btn btn-sm {{ $quality === 'main' ? 'btn-primary' : 'btn-outline-light' }}"
                       title="Yuqori sifatli HD oqim">
                        <i class="feather-award me-1"></i> Main (HD)
                    </a>
                </div>

                <!-- Refresh button -->
                <a href="{{ request()->fullUrlWithQuery(['refresh' => 1]) }}" 
                   class="btn btn-outline-light btn-sm d-flex align-items-center gap-1">
                    <i class="feather-refresh-cw"></i>
                    <span>Yangilash</span>
                </a>

                <!-- PTZ Control Center -->
                <a href="{{ route('cctv.ptz') }}" 
                   class="btn btn-warning btn-sm d-flex align-items-center gap-1 shadow fw-semibold px-3 text-dark">
                    <i class="feather-compass"></i>
                    <span>PTZ Boshqaruv</span>
                </a>

                <!-- Fullscreen Video Wall -->
                <a href="{{ route('cctv.wall', ['quality' => $quality]) }}" target="_blank" 
                   class="btn btn-danger btn-sm d-flex align-items-center gap-2 shadow fw-semibold px-3">
                    <i class="feather-maximize-2"></i>
                    <span>Monitor devori (Wall)</span>
                </a>
            </div>
        </div>
    </div>
    <!-- [ Page Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content p-3 p-lg-4">

        <!-- [ Live Stat Cards ] start -->
        <div class="row g-3 g-lg-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3 p-lg-4 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fs-12 text-muted text-uppercase fw-bold letter-spacing-1">Jami kameralar</span>
                            <h2 class="fw-bolder mb-0 text-dark mt-2 fs-28">{{ $totalCount }} ta</h2>
                        </div>
                        <div class="rounded-circle bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="feather-camera fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3 p-lg-4 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fs-12 text-muted text-uppercase fw-bold letter-spacing-1">Onlayn faol</span>
                            <h2 class="fw-bolder mb-0 text-success mt-2 fs-28">{{ $onlineCount }} ta</h2>
                        </div>
                        <div class="rounded-circle bg-success-subtle text-success p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="feather-check-circle fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3 p-lg-4 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fs-12 text-muted text-uppercase fw-bold letter-spacing-1">Nosoz / Oflayn</span>
                            <h2 class="fw-bolder mb-0 {{ $offlineCount > 0 ? 'text-danger' : 'text-muted' }} mt-2 fs-28">{{ $offlineCount }} ta</h2>
                        </div>
                        <div class="rounded-circle bg-danger-subtle text-danger p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="feather-alert-triangle fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3 p-lg-4 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fs-12 text-muted text-uppercase fw-bold letter-spacing-1">Server manzili</span>
                            <h5 class="fw-bold mb-0 text-dark mt-2 fs-15 text-break">192.168.40.123:8000</h5>
                            <span class="badge bg-success-subtle text-success fs-11 mt-1">API Bog'langan</span>
                        </div>
                        <div class="rounded-circle bg-info-subtle text-info p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="feather-server fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Live Stat Cards ] end -->

        <!-- [ Filter & View Mode Toolbar ] start -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <!-- Hududlar (Groups) filtri -->
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="fs-13 fw-semibold text-muted me-1">Hudud:</span>
                        <a href="{{ request()->fullUrlWithQuery(['group' => null]) }}" 
                           class="btn btn-sm rounded-pill {{ empty($selectedGroupId) ? 'btn-dark' : 'btn-outline-secondary' }}">
                            Barchasi ({{ count($allCameras) }})
                        </a>
                        @foreach ($groups as $grpId => $grpName)
                            <a href="{{ request()->fullUrlWithQuery(['group' => $grpId]) }}" 
                               class="btn btn-sm rounded-pill {{ $selectedGroupId == $grpId ? 'btn-dark' : 'btn-outline-secondary' }}">
                                {{ $grpName }}
                            </a>
                        @endforeach

                        <span class="border-start mx-2" style="height: 20px;"></span>

                        <!-- Status filtri -->
                        <span class="fs-13 fw-semibold text-muted me-1">Holati:</span>
                        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" 
                           class="btn btn-sm rounded-pill {{ empty($selectedStatus) ? 'btn-secondary' : 'btn-outline-secondary' }}">
                            Barchasi
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'online']) }}" 
                           class="btn btn-sm rounded-pill {{ $selectedStatus === 'online' ? 'btn-success' : 'btn-outline-success' }}">
                            Onlayn
                        </a>
                        @if ($offlineCount > 0)
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'error']) }}" 
                           class="btn btn-sm rounded-pill {{ $selectedStatus === 'error' ? 'btn-danger' : 'btn-outline-danger' }}">
                            Nosoz ({{ $offlineCount }})
                        </a>
                        @endif
                    </div>

                    <!-- Ko'rinish rejimi (Grid vs Bitta kamera) -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-13 fw-semibold text-muted me-1">Rejim:</span>
                        <div class="btn-group" role="group">
                            <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" 
                               class="btn btn-sm {{ $viewMode === 'grid' ? 'btn-primary' : 'btn-outline-primary' }}"
                               title="To'r (Grid) ko'rinishi">
                                <i class="feather-grid me-1"></i> To'r (Grid)
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['view' => 'single']) }}" 
                               class="btn btn-sm {{ $viewMode === 'single' ? 'btn-primary' : 'btn-outline-primary' }}"
                               title="Katta ekran (Bitta kamera)">
                                <i class="feather-monitor me-1"></i> Katta ekran
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Filter & View Mode Toolbar ] end -->

        @if (empty($cameras))
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                <div class="py-5">
                    <i class="feather-video-off text-muted fs-48 mb-3 d-block"></i>
                    <h5 class="fw-bold text-dark">Tanlangan mezon bo'yicha kameralar topilmadi</h5>
                    <p class="text-muted">Filtr parametrlarini o'zgartiring yoki sahifani yangilang.</p>
                    <a href="{{ route('cctv.index') }}" class="btn btn-primary rounded-pill px-4">Filtrlarni tozalash</a>
                </div>
            </div>
        @else

            @if ($viewMode === 'single')
                <!-- ================= SINGLE CAMERA FOCUS VIEW ================= -->
                <div class="row g-4">
                    <!-- Katta Video Oyna -->
                    <div class="col-xl-9 col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-dark text-white p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-16 fw-bold" id="mainCamTitle">
                                        {{ $activeCamera['name'] ?? 'Kamera' }}
                                    </span>
                                    @if (($activeCamera['status'] ?? '') === 'online')
                                        <span class="badge bg-success-subtle text-success fs-11 px-2 py-1" id="mainCamStatus">
                                            <span class="pulse-dot-green"></span> ONLAYN
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger fs-11 px-2 py-1" id="mainCamStatus">
                                            OFFLINE
                                        </span>
                                    @endif
                                    @if (!empty($activeCamera['is_ptz']))
                                        <span class="badge bg-warning text-dark fs-10">PTZ</span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-white-50 fs-12" id="mainCamIp">
                                        IP: {{ $activeCamera['ip_address'] ?? '-' }}
                                    </span>
                                    <button class="btn btn-xs btn-outline-light" onclick="toggleIframeFullscreen('mainVideoFrame')" title="To'liq ekranga olish">
                                        <i class="feather-maximize"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0 bg-black position-relative" style="min-height: 480px;">
                                <div class="ratio ratio-16x9">
                                    <iframe id="mainVideoFrame" 
                                            src="{{ $activeCamera['embed_url'] ?? '' }}" 
                                            allow="fullscreen; autoplay" 
                                            allowfullscreen 
                                            style="border: 0; width: 100%; height: 100%;">
                                    </iframe>
                                </div>
                            </div>
                            <div class="card-footer bg-light p-3">
                                <div class="row g-3 fs-13">
                                    <div class="col-md-3 col-6">
                                        <span class="text-muted d-block">Ishlab chiqaruvchi:</span>
                                        <strong id="mainCamVendor">{{ $activeCamera['vendor'] ?? '-' }} ({{ $activeCamera['model'] ?? '-' }})</strong>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <span class="text-muted d-block">Ruxsat (Resolution):</span>
                                        <strong id="mainCamRes">{{ $activeCamera['resolution_main'] ?? '-' }} @ {{ $activeCamera['fps'] ?? 25 }}fps</strong>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <span class="text-muted d-block">Hudud:</span>
                                        <strong id="mainCamGroup">{{ !empty($activeCamera['groups']) ? implode(', ', array_column($activeCamera['groups'], 'name')) : '-' }}</strong>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <span class="text-muted d-block">Oxirgi faollik:</span>
                                        <span id="mainCamSeen">{{ !empty($activeCamera['last_seen_at']) ? \Carbon\Carbon::parse($activeCamera['last_seen_at'])->diffForHumans() : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kameralar ro'yxati (Side list) -->
                    <div class="col-xl-3 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-white p-3 border-bottom d-flex align-items-center justify-content-between">
                                <h6 class="mb-0 fw-bold">Kameralar ro'yxati</h6>
                                <span class="badge bg-primary-subtle text-primary">{{ count($cameras) }} ta</span>
                            </div>
                            <div class="card-body p-2" style="max-height: 560px; overflow-y: auto;">
                                <div class="list-group list-group-flush gap-1">
                                    @foreach ($cameras as $cam)
                                        <a href="javascript:void(0);" 
                                           onclick="switchSingleCamera({{ $cam['id'] }})"
                                           id="cam-item-{{ $cam['id'] }}"
                                           class="list-group-item list-group-item-action rounded-3 border-0 p-2 d-flex align-items-center justify-content-between {{ ($activeCamera['id'] ?? 0) == $cam['id'] ? 'active-cam bg-primary text-white' : '' }}">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="feather-video fs-16 {{ ($activeCamera['id'] ?? 0) == $cam['id'] ? 'text-white' : 'text-muted' }}"></i>
                                                <div>
                                                    <span class="fw-semibold d-block fs-13 text-truncate" style="max-width: 150px;">
                                                        {{ $cam['name'] }}
                                                    </span>
                                                    <small class="{{ ($activeCamera['id'] ?? 0) == $cam['id'] ? 'text-white-50' : 'text-muted' }} fs-11">
                                                        {{ $cam['ip_address'] }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div>
                                                @if ($cam['status'] === 'online')
                                                    <span class="badge {{ ($activeCamera['id'] ?? 0) == $cam['id'] ? 'bg-light text-success' : 'bg-success-subtle text-success' }} fs-10">
                                                        ● Online
                                                    </span>
                                                @else
                                                    <span class="badge {{ ($activeCamera['id'] ?? 0) == $cam['id'] ? 'bg-light text-danger' : 'bg-danger-subtle text-danger' }} fs-10">
                                                        Offline
                                                    </span>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- ================= GRID VIEW (MULTI-CAMERA WALL) ================= -->
                <div class="row g-3 g-lg-4">
                    @foreach ($cameras as $cam)
                        <div class="col-xxl-4 col-xl-6 col-md-6">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 camera-card">
                                <!-- Card Header -->
                                <div class="card-header bg-dark text-white px-3 py-2 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2 text-truncate me-2">
                                        <i class="feather-video fs-14 text-white-50"></i>
                                        <span class="fw-bold fs-14 text-truncate" title="{{ $cam['name'] }}">
                                            {{ $cam['name'] }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                        @if ($cam['status'] === 'online')
                                            <span class="badge bg-success-subtle text-success fs-10 px-2 py-1">
                                                <span class="pulse-dot-green"></span> ONLAYN
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger fs-10 px-2 py-1">
                                                OFFLINE
                                            </span>
                                        @endif
                                        @if (!empty($cam['is_ptz']))
                                            <span class="badge bg-warning text-dark fs-10">PTZ</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Card Body: Video Player Stream -->
                                <div class="card-body p-0 bg-black position-relative">
                                    <div class="ratio ratio-16x9">
                                        @if ($cam['status'] === 'online' && !empty($cam['embed_url']))
                                            <iframe src="{{ $cam['embed_url'] }}" 
                                                    id="cam-frame-{{ $cam['id'] }}"
                                                    allow="fullscreen; autoplay" 
                                                    allowfullscreen 
                                                    loading="lazy"
                                                    style="border: 0; width: 100%; height: 100%;">
                                            </iframe>
                                        @else
                                            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center p-3 text-white-50">
                                                <i class="feather-alert-circle fs-36 text-warning mb-2"></i>
                                                <div class="fw-bold text-white fs-14">{{ $cam['name'] }}</div>
                                                <div class="fs-12 text-muted mt-1">
                                                    {{ $cam['last_error_message'] ?? 'Aloqa mavjud emas (RTSP/Kamera oflayn)' }}
                                                </div>
                                                <div class="mt-2">
                                                    <span class="badge bg-secondary fs-10">IP: {{ $cam['ip_address'] }}</span>
                                                </div>
                                                @if (!empty($cam['embed_url']))
                                                    <a href="javascript:void(0);" 
                                                       onclick="forceLoadFrame({{ $cam['id'] }}, '{{ $cam['embed_url'] }}')"
                                                       class="btn btn-outline-light btn-xs mt-3">
                                                        <i class="feather-play me-1"></i> Majburiy ochish
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Overlay hover expand button -->
                                    <button class="btn btn-dark btn-sm position-absolute top-0 end-0 m-2 opacity-75 hover-opacity-100 shadow" 
                                            onclick="openCameraModal({{ $cam['id'] }}, '{{ addslashes($cam['name']) }}', '{{ $cam['embed_url'] ?? '' }}', '{{ $cam['ip_address'] }}')"
                                            title="Kattalashtirib ko'rish"
                                            style="z-index: 5;">
                                        <i class="feather-maximize-2"></i>
                                    </button>
                                </div>

                                <!-- Card Footer: Camera Details -->
                                <div class="card-footer bg-white px-3 py-2 border-top d-flex align-items-center justify-content-between fs-12">
                                    <div class="d-flex align-items-center gap-2 text-muted">
                                        <span title="IP manzil"><i class="feather-globe me-1"></i>{{ $cam['ip_address'] }}</span>
                                        <span>•</span>
                                        <span>{{ $cam['vendor'] }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        @if (!empty($cam['groups']))
                                            @foreach ($cam['groups'] as $grp)
                                                <span class="badge bg-light text-dark border fs-10">{{ $grp['name'] }}</span>
                                            @endforeach
                                        @endif
                                        @if (!empty($cam['is_ptz']) || in_array($cam['id'], [1, 4, 10]))
                                            <a href="{{ route('cctv.ptz', ['camera' => $cam['id']]) }}" 
                                               class="btn btn-warning btn-xs text-dark fw-bold px-2" title="PTZ Burish & Zoom">
                                                <i class="feather-compass me-1"></i>PTZ
                                            </a>
                                        @endif
                                        <a href="{{ route('cctv.index', ['view' => 'single', 'camera' => $cam['id'], 'quality' => $quality]) }}" 
                                           class="btn btn-light btn-xs text-primary" title="Batafsil">
                                            <i class="feather-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        @endif

    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- ================= CAMERA FULLSCREEN MODAL ================= -->
<div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-darker px-4 py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="feather-video text-danger fs-20"></i>
                    <h5 class="modal-title fw-bold text-white mb-0" id="modalCamTitle">Kamera</h5>
                    <span class="badge bg-secondary fs-11" id="modalCamIp">192.168.0.x</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-light btn-sm" onclick="toggleIframeFullscreen('modalCamFrame')">
                        <i class="feather-maximize me-1"></i> To'liq ekran
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0 bg-black">
                <div class="ratio ratio-16x9">
                    <iframe id="modalCamFrame" src="" allow="fullscreen; autoplay" allowfullscreen style="border: 0; width: 100%; height: 100%;"></iframe>
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
        width: 7px;
        height: 7px;
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

    .hover-opacity-100:hover {
        opacity: 1 !important;
    }

    .camera-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .camera-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15) !important;
    }

    .active-cam {
        background-color: #2563eb !important;
        color: white !important;
    }
</style>
@endsection

@push('scripts')
<script>
    // Force load iframe on offline card if user clicks
    function forceLoadFrame(camId, url) {
        const container = document.getElementById('cam-frame-' + camId);
        if (container) {
            container.src = url;
        } else {
            location.reload();
        }
    }

    // Modal popup for single camera stream
    function openCameraModal(id, name, embedUrl, ip) {
        document.getElementById('modalCamTitle').innerText = name;
        document.getElementById('modalCamIp').innerText = 'IP: ' + ip;
        const frame = document.getElementById('modalCamFrame');
        frame.src = embedUrl;

        const myModal = new bootstrap.Modal(document.getElementById('cameraModal'));
        myModal.show();
    }

    document.getElementById('cameraModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('modalCamFrame').src = '';
    });

    // Native Fullscreen toggle helper
    function toggleIframeFullscreen(frameId) {
        const elem = document.getElementById(frameId);
        if (!elem) return;
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }
    }

    // Dynamic camera switcher in Single View (AJAX or direct)
    function switchSingleCamera(cameraId) {
        const quality = "{{ $quality }}";
        
        // Highlight active camera in list
        document.querySelectorAll('.list-group-item').forEach(el => {
            el.classList.remove('active-cam', 'bg-primary', 'text-white');
        });
        const currentItem = document.getElementById('cam-item-' + cameraId);
        if (currentItem) {
            currentItem.classList.add('active-cam', 'bg-primary', 'text-white');
        }

        // Fetch stream details via AJAX
        fetch(`/admin/cctv/stream/${cameraId}?quality=${quality}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.embed_url) {
                    document.getElementById('mainVideoFrame').src = data.embed_url;
                    document.getElementById('mainCamTitle').innerText = data.camera.name;
                    document.getElementById('mainCamIp').innerText = 'IP: ' + data.camera.ip_address;
                    document.getElementById('mainCamVendor').innerText = (data.camera.vendor || '-') + ' (' + (data.camera.model || '-') + ')';
                    document.getElementById('mainCamRes').innerText = (data.camera.resolution_main || '-') + ' @ ' + (data.camera.fps || 25) + 'fps';
                    
                    const groupNames = (data.camera.groups || []).map(g => g.name).join(', ') || '-';
                    document.getElementById('mainCamGroup').innerText = groupNames;

                    const statusElem = document.getElementById('mainCamStatus');
                    if (data.camera.status === 'online') {
                        statusElem.className = 'badge bg-success-subtle text-success fs-11 px-2 py-1';
                        statusElem.innerHTML = '<span class="pulse-dot-green"></span> ONLAYN';
                    } else {
                        statusElem.className = 'badge bg-danger-subtle text-danger fs-11 px-2 py-1';
                        statusElem.innerHTML = 'OFFLINE';
                    }
                }
            })
            .catch(err => {
                console.error('Failed to switch camera:', err);
            });
    }
</script>
@endpush
