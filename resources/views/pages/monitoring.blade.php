@extends('layouts.admin')

@section('title', 'Ходимлар умумий ҳисоботи ва досьеси')

@section('content')
<div class="nxl-content d-flex flex-column h-100">
    <!-- [ Page Header ] start -->
    <div class="page-header py-3 px-4" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border-bottom: 3px solid #3b82f6;">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h4 class="text-white fw-bold mb-1 d-flex align-items-center gap-2">
                    <i class="feather-users text-primary"></i> Ходимлар ягона мониторинги ва умумий статистикаси
                </h4>
                <p class="text-white-50 mb-0 small">
                    Топшириқлар, И-индекс, Турникет давомати, Лойиҳалар, Ижодий ишлар ва шахсий досьелар ягона платформада
                </p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button type="button" id="btnSyncTurniket" class="btn btn-outline-light btn-sm rounded-pill px-3 shadow-sm" onclick="syncTurniketPhotos()">
                    <i class="feather-refresh-cw me-1"></i> 🔄 Турникет расмларини янгилаш
                </button>
                <a href="{{ route('monitoring.diagramma') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm fw-semibold">
                    <i class="feather-pie-chart me-1"></i> 📊 Диаграммалар
                </a>
                <button onclick="window.print()" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm">
                    <i class="feather-printer me-1"></i> 🖨️ Чоп этиш
                </button>
            </div>
        </div>
    </div>
    <!-- [ Page Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content p-3 p-md-4">

        <!-- Top KPI Cards Row -->
        <div class="row g-3 mb-4">
            <!-- 1. Total Employees -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-3" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-left: 4px solid #3b82f6 !important;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-semibold text-uppercase">Жами ходимлар</span>
                                <h3 class="fw-bold mb-0 text-dark mt-1">{{ $summary['total_employees'] ?? count($xodimlar) }} <small class="text-muted fs-6 fw-normal">нафар</small></h3>
                                <div class="mt-2 small text-muted">
                                    <span class="badge bg-light text-dark border">🏢 {{ count($summary['departments'] ?? []) }} та бўлим</span>
                                </div>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(59, 130, 246, 0.12);">
                                <i class="feather-users fs-3 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Attendance Today -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-3" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-left: 4px solid #10b981 !important;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-semibold text-uppercase">Бугунги давомат</span>
                                <div class="d-flex align-items-baseline gap-2 mt-1">
                                    <h3 class="fw-bold mb-0 text-success">{{ $summary['inside_count'] ?? 0 }}</h3>
                                    <span class="text-muted small">бинода ҳозир</span>
                                </div>
                                <div class="d-flex flex-wrap gap-1 mt-2">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" title="Келганлар">
                                        🔵 {{ $summary['arrived_count'] ?? 0 }} келган
                                    </span>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle" title="Кечикканлар">
                                        🟠 {{ $summary['late_count'] ?? 0 }} кечиккан
                                    </span>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" title="Келмаганлар">
                                        🔴 {{ $summary['absent_count'] ?? 0 }} келмаган
                                    </span>
                                </div>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(16, 185, 129, 0.12);">
                                <i class="feather-check-circle fs-3 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Tasks KPI -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-3" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-left: 4px solid #8b5cf6 !important;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-semibold text-uppercase">Топшириқлар ижроси</span>
                                <div class="d-flex align-items-baseline gap-2 mt-1">
                                    <h3 class="fw-bold mb-0 text-purple">{{ $summary['total'] ?? 0 }}</h3>
                                    <span class="badge bg-purple-subtle text-purple border">{{ $summary['percent'] ?? 0 }}% бажарилди</span>
                                </div>
                                <div class="d-flex flex-wrap gap-1 mt-2">
                                    <span class="badge bg-success-subtle text-success">✅ {{ $summary['completed'] ?? 0 }}</span>
                                    <span class="badge bg-info-subtle text-info">⏳ {{ $summary['in_process'] ?? 0 }}</span>
                                    <span class="badge bg-danger-subtle text-danger">⚠️ {{ $summary['not_completed'] ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(139, 92, 246, 0.12);">
                                <i class="feather-check-square fs-3 text-purple"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. I-index & Projects -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-3" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-left: 4px solid #f59e0b !important;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-semibold text-uppercase">И-индекс ва Лойиҳалар</span>
                                <div class="d-flex align-items-baseline gap-2 mt-1">
                                    <h3 class="fw-bold mb-0 text-warning">{{ number_format($summary['total_score'] ?? 0, 1) }}</h3>
                                    <span class="text-muted small">умумий балл</span>
                                </div>
                                <div class="d-flex flex-wrap gap-1 mt-2">
                                    <span class="badge bg-warning-subtle text-dark">📄 {{ $summary['total_articles'] ?? 0 }} мақола</span>
                                    <span class="badge bg-primary-subtle text-primary">🚀 {{ $summary['total_projects'] ?? 0 }} лойиҳа</span>
                                    <span class="badge bg-info-subtle text-info">✍️ {{ $summary['total_works'] ?? 0 }} ижод</span>
                                </div>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(245, 158, 11, 0.12);">
                                <i class="feather-award fs-3 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-3">
                <div class="row g-2 align-items-center">
                    <div class="col-lg-4 col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="feather-search text-muted"></i>
                            </span>
                            <input type="text" id="employeeSearchInput" class="form-control border-start-0 bg-light" placeholder="Ф.И.Ш, лавозим ёки ID бўйича қидириш...">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <select id="departmentFilter" class="form-select bg-light">
                            <option value="">🏢 Барча бўлимлар</option>
                            @foreach($summary['departments'] ?? [] as $dep)
                                @if(!empty($dep))
                                    <option value="{{ $dep }}">{{ $dep }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <select id="attendanceFilter" class="form-select bg-light">
                            <option value="">⏱️ Барча давомат ҳолатлари</option>
                            <option value="inside">🟢 Бинода (Ҳозир)</option>
                            <option value="arrived">🔵 Келган (Бугун)</option>
                            <option value="late">🟠 Кечиккан</option>
                            <option value="excused">🟡 Сабабли / Таътил</option>
                            <option value="absent">🔴 Келмаган</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 text-lg-end">
                        <span class="text-muted small">Кўрсатилмоқда: <strong id="visibleCount" class="text-dark">{{ count($xodimlar) }}</strong> нафар</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Master Unified Table Card -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 800px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" id="unifiedEmployeeTable">
                        <thead class="sticky-top" style="background-color: #1e293b; color: #ffffff; z-index: 5;">
                            <tr>
                                <th style="width: 50px;" class="text-center">№</th>
                                <th style="min-width: 280px;">Ходим (Ф.И.Ш / Лавозим)</th>
                                <th style="min-width: 170px;" class="text-center">Бугунги давомат</th>
                                <th style="min-width: 220px;">Топшириқлар</th>
                                <th style="min-width: 130px;" class="text-center">И-индекс</th>
                                <th style="min-width: 110px;" class="text-center">Лойиҳа / Ижод</th>
                                <th style="min-width: 150px;" class="text-center">Туғилган кун</th>
                                <th style="min-width: 130px;" class="text-center">Ҳаракат</th>
                            </tr>
                        </thead>
                        <tbody id="employeeTableBody">
                            @forelse($xodimlar as $index => $item)
                                @php
                                    $emp = $item['employee'] ?? null;
                                    $empId = $item['id'] ?? ($emp ? $emp->id : null);
                                    $att = $item['attendance'] ?? [];
                                    $tasks = $item['tasks'] ?? [];
                                    $iIndex = $item['i_index'] ?? [];
                                    $bday = $item['birthday'] ?? null;
                                @endphp
                                <tr class="employee-row" 
                                    data-name="{{ mb_strtolower($item['name']) }}"
                                    data-department="{{ $item['department'] }}"
                                    data-attendance="{{ $att['status'] ?? 'unknown' }}"
                                    data-turniket-id="{{ $item['turniket_id'] ?? '' }}">
                                    
                                    <!-- 1. Index -->
                                    <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>

                                    <!-- 2. Employee Info + Turniket Photo -->
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="position-relative">
                                                <img src="{{ $item['avatar'] }}" 
                                                     alt="{{ $item['name'] }}" 
                                                     class="rounded-circle shadow-sm border border-2 {{ ($att['is_inside'] ?? false) ? 'border-success' : (($att['is_late'] ?? false) ? 'border-warning' : 'border-light') }}"
                                                     style="width: 48px; height: 48px; object-fit: cover;"
                                                     onerror="this.src='{{ asset('assets/images/avatar/default.png') }}'">
                                                @if($att['is_inside'] ?? false)
                                                    <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 12px; height: 12px;" title="Бинода"></span>
                                                @elseif($att['is_arrived'] ?? false)
                                                    <span class="position-absolute bottom-0 end-0 bg-info border border-white rounded-circle" style="width: 12px; height: 12px;" title="Келган"></span>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ $empId ? route('employees.show', $empId) : '#' }}" class="fw-bold text-dark text-decoration-none hover-primary d-block">
                                                    {{ $item['name'] }}
                                                </a>
                                                <div class="d-flex flex-wrap align-items-center gap-1 small text-muted">
                                                    @if(!empty($item['rank']))
                                                        <span class="badge bg-light text-dark border">{{ $item['rank'] }}</span>
                                                    @endif
                                                    <span>{{ $item['position'] }}</span>
                                                    <span>•</span>
                                                    <span class="text-secondary">{{ $item['department'] }}</span>
                                                    @if(!empty($item['turniket_id']))
                                                        <span class="badge bg-secondary-subtle text-secondary py-0 px-1 font-monospace" style="font-size: 10px;">ID: {{ $item['turniket_id'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- 3. Attendance Today -->
                                    <td class="text-center">
                                        @if(($att['has_excuse'] ?? false))
                                            <span class="badge bg-primary px-2 py-1 rounded-pill" title="{{ $att['excuse_reason'] ?? 'Сабабли' }}">
                                                🟡 Сабабли
                                            </span>
                                            @if(!empty($att['excuse_reason']))
                                                <div class="small text-muted mt-1 text-truncate" style="max-width: 150px; margin: 0 auto;" title="{{ $att['excuse_reason'] }}">
                                                    {{ $att['excuse_reason'] }}
                                                </div>
                                            @endif
                                        @elseif(($att['is_inside'] ?? false))
                                            <span class="badge bg-success px-2 py-1 rounded-pill">
                                                🟢 Бинода
                                            </span>
                                            @if(!empty($att['entry_time']))
                                                <div class="small text-muted mt-1">
                                                    {{ substr($att['entry_time'], 11, 5) }}
                                                </div>
                                            @endif
                                        @elseif(($att['is_late'] ?? false))
                                            <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">
                                                🟠 Кечиккан (+{{ $att['late_minutes'] ?? 0 }} дақ)
                                            </span>
                                            @if(!empty($att['entry_time']))
                                                <div class="small text-muted mt-1">
                                                    {{ substr($att['entry_time'], 11, 5) }}
                                                </div>
                                            @endif
                                        @elseif(($att['is_arrived'] ?? false))
                                            <span class="badge bg-info px-2 py-1 rounded-pill">
                                                🔵 Келган
                                            </span>
                                            @if(!empty($att['entry_time']))
                                                <div class="small text-muted mt-1">
                                                    {{ substr($att['entry_time'], 11, 5) }}
                                                </div>
                                            @endif
                                        @elseif(($att['is_absent'] ?? false))
                                            <span class="badge bg-danger px-2 py-1 rounded-pill">
                                                🔴 Келмаган
                                            </span>
                                        @else
                                            <span class="badge bg-secondary px-2 py-1 rounded-pill">
                                                —
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 4. Tasks -->
                                    <td>
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="small fw-bold text-dark">Жами: {{ $tasks['total'] ?? 0 }}</span>
                                            <span class="small fw-semibold text-muted">{{ $tasks['percent'] ?? 0 }}%</span>
                                        </div>
                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $tasks['percent'] ?? 0 }}%"></div>
                                        </div>
                                        <div class="d-flex flex-wrap gap-1" style="font-size: 11px;">
                                            <span class="badge bg-success-subtle text-success py-0 px-1" title="Бажарилди">✅ {{ $tasks['completed'] ?? 0 }}</span>
                                            <span class="badge bg-primary-subtle text-primary py-0 px-1" title="Жараёнда">⏳ {{ $tasks['in_process'] ?? 0 }}</span>
                                            <span class="badge bg-warning-subtle text-warning py-0 px-1" title="Ўзайтирилди">🔄 {{ $tasks['extended'] ?? 0 }}</span>
                                            <span class="badge bg-danger-subtle text-danger py-0 px-1" title="Бажарилмаган">⚠️ {{ $tasks['not_completed'] ?? 0 }}</span>
                                        </div>
                                    </td>

                                    <!-- 5. I-index -->
                                    <td class="text-center">
                                        <div class="fw-bold text-warning fs-6">
                                            ⭐ {{ number_format($iIndex['score'] ?? 0, 1) }}
                                        </div>
                                        <div class="small text-muted">
                                            {{ $iIndex['articles_count'] ?? 0 }} та мақола
                                        </div>
                                    </td>

                                    <!-- 6. Projects & Creative Works -->
                                    <td class="text-center">
                                        <div class="small mb-1">
                                            <span class="badge bg-primary-subtle text-primary" title="Лойиҳалар">
                                                🚀 {{ $item['projects_count'] ?? 0 }} лойиҳа
                                            </span>
                                        </div>
                                        <div class="small">
                                            <span class="badge bg-info-subtle text-info" title="Ижодий ишлар">
                                                ✍️ {{ $item['works_count'] ?? 0 }} ижод
                                            </span>
                                        </div>
                                    </td>

                                    <!-- 7. Birthday -->
                                    <td class="text-center">
                                        @if(!empty($bday))
                                            <div class="fw-semibold small text-dark">{{ $bday['formatted'] ?? '' }}</div>
                                            <div class="small text-muted mb-1">{{ $bday['age'] ?? '' }} ёш</div>
                                            @if(($bday['is_today'] ?? false))
                                                <span class="badge bg-warning text-dark animate-pulse">🎉 Бугун!</span>
                                            @elseif(($bday['days_until'] ?? 999) <= 30)
                                                <span class="badge bg-light text-primary border">{{ $bday['days_until'] }} кун қолди</span>
                                            @endif
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>

                                    <!-- 8. Actions -->
                                    <td class="text-center">
                                        @if($empId)
                                            <a href="{{ route('employees.show', $empId) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-1">
                                                <i class="feather-user"></i> Досье
                                            </a>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="feather-alert-circle fs-2 d-block mb-2"></i>
                                        Ходимлар топилмади.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- SweetAlert2 (if not already included) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Search & Filter Logic
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('employeeSearchInput');
        const depFilter = document.getElementById('departmentFilter');
        const attFilter = document.getElementById('attendanceFilter');
        const rows = document.querySelectorAll('.employee-row');
        const visibleCountEl = document.getElementById('visibleCount');

        function filterTable() {
            const query = searchInput.value.toLowerCase().trim();
            const selectedDep = depFilter.value;
            const selectedAtt = attFilter.value;
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const dep = row.getAttribute('data-department') || '';
                const att = row.getAttribute('data-attendance') || '';
                const tId = row.getAttribute('data-turniket-id') || '';

                const matchesQuery = !query || name.includes(query) || dep.toLowerCase().includes(query) || tId.includes(query);
                const matchesDep = !selectedDep || dep === selectedDep;
                const matchesAtt = !selectedAtt || att === selectedAtt;

                if (matchesQuery && matchesDep && matchesAtt) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (visibleCountEl) {
                visibleCountEl.innerText = visibleCount;
            }
        }

        if (searchInput) searchInput.addEventListener('input', filterTable);
        if (depFilter) depFilter.addEventListener('change', filterTable);
        if (attFilter) attFilter.addEventListener('change', filterTable);
    });

    // Sync Turniket Photos
    function syncTurniketPhotos() {
        const btn = document.getElementById('btnSyncTurniket');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Синхронланмоқда...';
        }

        fetch("{{ route('employees.sync_turniket') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Муваффақиятли!',
                    text: data.message,
                    confirmButtonText: 'Аъло'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Хатолик',
                    text: data.message || 'Синхронлашда хатолик юз берди.',
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Хатолик',
                text: 'Сервер билан боғланишда хатолик: ' + err.message,
            });
        })
        .finally(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="feather-refresh-cw me-1"></i> 🔄 Турникет расмларини янгилаш';
            }
        });
    }
</script>

<style>
    .hover-primary:hover {
        color: #3b82f6 !important;
    }
    .text-purple {
        color: #8b5cf6 !important;
    }
    .bg-purple-subtle {
        background-color: rgba(139, 92, 246, 0.15) !important;
    }
    .animate-pulse {
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.05); }
    }
    @media print {
        .page-header, #btnSyncTurniket, .btn, #employeeSearchInput, #departmentFilter, #attendanceFilter, .no-print {
            display: none !important;
        }
        .table-responsive {
            overflow: visible !important;
            max-height: none !important;
        }
        table thead {
            background-color: #333 !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>
@endsection
