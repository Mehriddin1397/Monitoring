@extends('layouts.admin')

@section('title', 'Турникет қурилмалари мониторинги')

@section('content')
<div class="nxl-content d-flex flex-column h-100">
    <!-- [ Page Header ] start -->
    <div class="page-header py-3 px-3 px-lg-4 shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-text avatar-lg bg-info text-white rounded-circle shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                    <i class="feather-cpu fs-24"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="mb-1 text-white fw-bold fs-22">Турникет қурилмалари (Hikvision)</h3>
                        <span class="badge bg-success text-uppercase px-2 py-1 fs-11">
                            {{ $onlineCount }} / {{ count($devices) }} ОНЛАЙН
                        </span>
                    </div>
                    <p class="fs-14 text-white-50 mb-0">
                        Институт биносига ўрнатилган 4 та Hikvision DS-K1T671MF биометрик турникетларининг техник ҳолати
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('admin.turniket.dashboard') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-grid me-1"></i> Панел
                </a>
                <a href="{{ route('admin.turniket.attendance') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-list me-1"></i> Давомат журнали
                </a>
                <button type="button" class="btn btn-sm btn-primary" onclick="window.location.reload();">
                    <i class="feather-refresh-cw me-1"></i> Текшириш
                </button>
            </div>
        </div>
    </div>
    <!-- [ Page Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content p-3 p-lg-4">
        <div class="row g-4">
            @forelse($devices as $dev)
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100 device-card">
                        <div class="card-header bg-white border-bottom p-3 d-flex align-items-center justify-content-between">
                            <span class="badge {{ ($dev['type'] ?? '') === 'entry' ? 'bg-success' : 'bg-primary' }} text-uppercase px-2 py-1 fs-11">
                                <i class="feather-{{ ($dev['type'] ?? '') === 'entry' ? 'log-in' : 'log-out' }} me-1"></i>
                                {{ ($dev['type'] ?? '') === 'entry' ? 'Кириш' : 'Чиқиш' }}
                            </span>
                            @if(($dev['status'] ?? '') === 'online')
                                <span class="badge bg-soft-success text-success px-2 py-1 fs-12">
                                    <span class="pulse-dot"></span> Онлайн
                                </span>
                            @else
                                <span class="badge bg-soft-danger text-danger px-2 py-1 fs-12">
                                    <i class="feather-alert-triangle me-1"></i> Оффлайн
                                </span>
                            @endif
                        </div>

                        <div class="card-body p-4 text-center">
                            <div class="avatar-text avatar-xl rounded-circle mx-auto mb-3 shadow-sm {{ ($dev['type'] ?? '') === 'entry' ? 'bg-soft-success text-success' : 'bg-soft-primary text-primary' }}" style="width: 72px; height: 72px;">
                                <i class="feather-shield fs-32"></i>
                            </div>

                            <h5 class="fw-bold mb-1 text-dark fs-18">{{ $dev['name'] ?? 'Турникет' }}</h5>
                            <p class="text-muted fs-13 mb-3">Hikvision DS-K1T671MF</p>

                            <div class="bg-light p-3 rounded mb-3 text-start">
                                <div class="d-flex justify-content-between mb-1 fs-13">
                                    <span class="text-muted">IP Манзил:</span>
                                    <span class="fw-bold font-monospace text-dark">{{ $dev['ip'] ?? '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1 fs-13">
                                    <span class="text-muted">Порт:</span>
                                    <span class="fw-bold font-monospace text-dark">80 (ISAPI)</span>
                                </div>
                                <div class="d-flex justify-content-between fs-13">
                                    <span class="text-muted">Бугунги ўтишлар:</span>
                                    <span class="fw-bold text-primary">{{ $dev['today_logs_count'] ?? 0 }} та</span>
                                </div>
                            </div>

                            <a href="{{ route('admin.turniket.attendance', ['device_id' => $dev['id']]) }}" class="btn btn-sm btn-outline-primary w-100">
                                <i class="feather-list me-1"></i> Ушбу турникет журнали
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning p-4 text-center">
                        <i class="feather-alert-triangle fs-32 d-block mb-2 text-warning"></i>
                        Турникет қурилмалари маълумоти топилмади ёки сервер билан боғланиш мавжуд эмас.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

<style>
.device-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.device-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.1) !important;
}
.pulse-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #22c55e;
    margin-right: 4px;
    animation: pulse 1.5s infinite;
}
@keyframes pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
}
</style>
@endsection
