@extends('layouts.admin')

@section('title', 'Давомат ҳисоботлари — Турникет')

@section('content')
<div class="nxl-content d-flex flex-column h-100">

    <!-- [ Print Official Header (Faqat chop etilganda ko'rinadi) ] -->
    <div class="print-header d-none d-print-block text-center mb-4">
        <h4 class="fw-bold mb-2 text-uppercase text-dark" style="letter-spacing: 0.5px;">Ўзбекистон Республикаси Криминология тадқиқот институти</h4>
        <div class="border-top border-bottom border-dark py-2 my-2">
            <h5 class="mb-0 fw-bold">
                @if($singleEmployeeData)
                    ХОДИМНИНГ ДАВОМАТ ВА ТУРНИКЕТДАН КИРИБ-ЧИҚИШ ҲИСОБОТИ
                @else
                    ТУРНИКЕТ ДАВОМАТ ВА ИШ ВАҚТИ МОНИТОРИНГИ ҲИСОБОТИ
                    @if($statusFilter === 'late')
                        — КЕЧИККАН ХОДИМЛАР РЎЙХАТИ
                    @elseif($statusFilter === 'absent')
                        — КЕЛМАГАН ХОДИМЛАР РЎЙХАТИ
                    @elseif($statusFilter === 'on_time')
                        — ЎЗ ВАҚТИДА КЕЛГАН ХОДИМЛАР РЎЙХАТИ
                    @endif
                @endif
            </h5>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-2 fs-12 text-muted px-2">
            <span><strong>Ҳисобот даври:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} дан {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }} гача</span>
            @if($singleEmployeeData)
                <span><strong>Ходим:</strong> {{ $singleEmployeeData['employee']['fullname'] ?? '-' }} (ID: {{ $singleEmployeeData['employee_no'] }})</span>
            @endif
            <span><strong>Чоп этилган сана:</strong> {{ now()->format('d.m.Y H:i') }}</span>
        </div>
    </div>

    <!-- [ Page Header (Ekranda ko'rinadi) ] start -->
    <div class="page-header py-3 px-3 px-lg-4 shadow-sm d-print-none" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-text avatar-lg bg-warning text-dark rounded-circle shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                    <i class="feather-file-text fs-24"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="mb-1 text-white fw-bold fs-22">Давомат ҳисоботлари</h3>
                        <span class="badge bg-warning text-dark text-uppercase px-2 py-1 fs-11">
                            {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}
                        </span>
                        @if($isOnline)
                            <span class="badge bg-success-subtle text-success border border-success-subtle fs-11 px-2 py-1">
                                <i class="feather-wifi me-1"></i> Онлайн
                            </span>
                        @else
                            <span class="badge bg-danger text-white fs-11 px-2 py-1">
                                <i class="feather-wifi-off me-1"></i> Офлайн
                            </span>
                        @endif
                    </div>
                    <p class="fs-14 text-white-50 mb-0">
                        Турникет орқали кириш-чиқишлар, иш вақти, кечикишлар ва шахсий ҳисоботлар таҳлили
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
                <button type="button" class="btn btn-sm btn-primary shadow-sm" onclick="window.print();">
                    <i class="feather-printer me-1"></i> Чоп этиш
                </button>
            </div>
        </div>
    </div>
    <!-- [ Page Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content p-3 p-lg-4">

        <!-- [ Filter Card ] start -->
        <div class="card border-0 shadow-sm mb-4 filter-card d-print-none">
            <div class="card-body p-3 p-lg-4">
                <form action="{{ route('admin.turniket.reports') }}" method="GET" id="reportFilterForm">
                    <div class="row g-2 align-items-end">
                        <!-- Start Date -->
                        <div class="col-12 col-sm-6 col-lg-2">
                            <label class="form-label fs-12 fw-semibold text-muted mb-1 d-flex align-items-center gap-1">
                                <i class="feather-calendar text-primary"></i> Бошланиш санаси:
                            </label>
                            <input type="date" name="start_date" id="startDateInput" class="form-control form-control-sm" 
                                   value="{{ $startDate }}" required>
                        </div>

                        <!-- End Date -->
                        <div class="col-12 col-sm-6 col-lg-2">
                            <label class="form-label fs-12 fw-semibold text-muted mb-1 d-flex align-items-center gap-1">
                                <i class="feather-calendar text-primary"></i> Тугаш санаси:
                            </label>
                            <input type="date" name="end_date" id="endDateInput" class="form-control form-control-sm" 
                                   value="{{ $endDate }}" required>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label fs-12 fw-semibold text-muted mb-1 d-flex align-items-center gap-1">
                                <i class="feather-check-circle text-primary"></i> Ҳолати бўйича филтр:
                            </label>
                            <select name="status" id="statusFilterSelect" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">-- Барча ҳолатлар --</option>
                                <option value="on_time" {{ $statusFilter === 'on_time' ? 'selected' : '' }}>🟢 Ўз вақтида келганлар</option>
                                <option value="late" {{ $statusFilter === 'late' ? 'selected' : '' }}>🟡 Кечикканлар (Кеч қолганлар)</option>
                                <option value="absent" {{ $statusFilter === 'absent' ? 'selected' : '' }}>🔴 Келмаганлар</option>
                            </select>
                        </div>

                        <!-- Employee Select Filter -->
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label fs-12 fw-semibold text-muted mb-1 d-flex align-items-center gap-1">
                                <i class="feather-user text-primary"></i> Ходим бўйича филтр:
                            </label>
                            <select name="employee_filter" id="employeeFilterSelect" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">-- Барча ходимлар --</option>
                                @foreach($mainEmployees as $emp)
                                    <option value="{{ $emp['employee_no'] }}" {{ (string)$selectedEmployee === (string)$emp['employee_no'] ? 'selected' : '' }}>
                                        {{ $emp['fullname'] }} (ID: {{ $emp['employee_no'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12 col-lg-2 d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-primary flex-fill d-flex align-items-center justify-content-center gap-1">
                                <i class="feather-filter"></i> Кўрсатиш
                            </button>
                            <a href="{{ route('admin.turniket.reports') }}" class="btn btn-sm btn-outline-secondary" title="Филтрларни тозалаш">
                                <i class="feather-rotate-ccw"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Date & Status Presets Row -->
                    <div class="d-flex align-items-center justify-content-between gap-3 mt-3 pt-2 border-top flex-wrap">
                        <!-- Quick Date Presets -->
                        <div class="d-flex align-items-center gap-1 flex-wrap">
                            <span class="fs-12 text-muted fw-semibold me-1"><i class="feather-clock me-1"></i>Тезкор сана:</span>
                            <button type="button" class="btn btn-xs btn-light border" onclick="setQuickDate('today')">Бугун</button>
                            <button type="button" class="btn btn-xs btn-light border" onclick="setQuickDate('yesterday')">Кеча</button>
                            <button type="button" class="btn btn-xs btn-light border" onclick="setQuickDate('week')">Охирги 7 кун</button>
                            <button type="button" class="btn btn-xs btn-light border" onclick="setQuickDate('month')">Шу ой</button>
                        </div>

                        <!-- Quick Status Presets -->
                        <div class="d-flex align-items-center gap-1 flex-wrap">
                            <span class="fs-12 text-muted fw-semibold me-1"><i class="feather-activity me-1"></i>Ҳолати:</span>
                            <button type="button" class="btn btn-xs {{ empty($statusFilter) ? 'btn-dark' : 'btn-light border' }}" onclick="setQuickStatus('')">
                                Барчаси <span class="badge bg-secondary ms-1">{{ $statusCounts['all'] ?? count($mainRecords) }}</span>
                            </button>
                            <button type="button" class="btn btn-xs {{ $statusFilter === 'on_time' ? 'btn-success text-white' : 'btn-light border text-success' }}" onclick="setQuickStatus('on_time')">
                                🟢 Ўз вақтида <span class="badge {{ $statusFilter === 'on_time' ? 'bg-light text-success' : 'bg-success' }} ms-1">{{ $statusCounts['on_time'] ?? 0 }}</span>
                            </button>
                            <button type="button" class="btn btn-xs {{ $statusFilter === 'late' ? 'btn-warning text-dark' : 'btn-light border text-warning-emphasis' }}" onclick="setQuickStatus('late')">
                                🟡 Кечикканлар <span class="badge {{ $statusFilter === 'late' ? 'bg-dark text-warning' : 'bg-warning text-dark' }} ms-1">{{ $statusCounts['late'] ?? 0 }}</span>
                            </button>
                            <button type="button" class="btn btn-xs {{ $statusFilter === 'absent' ? 'btn-danger text-white' : 'btn-light border text-danger' }}" onclick="setQuickStatus('absent')">
                                🔴 Келмаганлар <span class="badge {{ $statusFilter === 'absent' ? 'bg-light text-danger' : 'bg-danger' }} ms-1">{{ $statusCounts['absent'] ?? 0 }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- [ Filter Card ] end -->

        @if($singleEmployeeData)
            <!-- ============================================================== -->
            <!-- [ ALOHIDA YAKKA XODIM HISOBOTI ] start                         -->
            <!-- ============================================================== -->
            <div class="employee-report-wrapper">
                
                <!-- Employee Summary Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-3 p-lg-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                @if(!empty($singleEmployeeData['employee']['photo_url']))
                                    <img src="{{ $singleEmployeeData['employee']['photo_url'] }}" 
                                         alt="{{ $singleEmployeeData['employee']['fullname'] }}" 
                                         class="rounded-circle border shadow-sm" 
                                         style="width: 64px; height: 64px; object-fit: cover;">
                                @else
                                    <div class="avatar-text avatar-lg rounded-circle text-white shadow-sm fw-bold fs-20"
                                         style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; background: {{ $singleEmployeeData['is_guard'] ? 'linear-gradient(135deg, #d97706, #b45309)' : 'linear-gradient(135deg, #2563eb, #1d4ed8)' }};">
                                        {{ mb_substr($singleEmployeeData['employee']['fullname'] ?? 'X', 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                        <h4 class="mb-0 fw-bold text-dark">{{ $singleEmployeeData['employee']['fullname'] ?? 'Ходим' }}</h4>
                                        @if($singleEmployeeData['is_guard'])
                                            <span class="badge bg-warning text-dark px-2 py-1 fs-12 fw-semibold">
                                                <i class="feather-shield me-1"></i> Қўриқлаш хизмати ходими
                                            </span>
                                        @else
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-12 fw-semibold">
                                                <i class="feather-user me-1"></i> Асосий ходим
                                            </span>
                                        @endif
                                        <span class="badge bg-light text-dark font-monospace fs-12 border">
                                            ID: {{ $singleEmployeeData['employee_no'] }}
                                        </span>
                                    </div>
                                    <p class="fs-13 text-muted mb-0">
                                        <i class="feather-briefcase me-1"></i>{{ $singleEmployeeData['employee']['department'] ?? 'Институт' }}
                                        <span class="mx-2">•</span>
                                        <i class="feather-calendar me-1"></i>Ҳисобот даври: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }}</strong> дан <strong>{{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}</strong> гача
                                    </p>
                                </div>
                            </div>

                            <div class="d-print-none d-flex gap-2">
                                <a href="{{ route('admin.turniket.reports', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="feather-arrow-left me-1"></i> Барча ходимларга қайтиш
                                </a>
                                <button type="button" class="btn btn-sm btn-primary" onclick="window.print();">
                                    <i class="feather-printer me-1"></i> Ушбу ҳисоботни чоп этиш
                                </button>
                            </div>
                        </div>

                        <!-- 4 Stat Counters for Single Employee -->
                        <div class="row g-3 mt-3 pt-3 border-top">
                            <div class="col-6 col-md-3">
                                <div class="p-3 rounded-3 bg-light border text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center p-2 rounded-circle bg-success-subtle text-success mb-2" style="width: 40px; height: 40px;">
                                        <i class="feather-log-in fs-18"></i>
                                    </div>
                                    <div class="fs-12 text-muted fw-semibold">Жами киришлар</div>
                                    <div class="fs-22 fw-bold text-success font-monospace">{{ $singleEmployeeData['total_entries'] }} <span class="fs-13 fw-normal text-muted">марта</span></div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 rounded-3 bg-light border text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center p-2 rounded-circle bg-primary-subtle text-primary mb-2" style="width: 40px; height: 40px;">
                                        <i class="feather-log-out fs-18"></i>
                                    </div>
                                    <div class="fs-12 text-muted fw-semibold">Жами чиқишлар</div>
                                    <div class="fs-22 fw-bold text-primary font-monospace">{{ $singleEmployeeData['total_exits'] }} <span class="fs-13 fw-normal text-muted">марта</span></div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 rounded-3 bg-light border text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center p-2 rounded-circle bg-info-subtle text-info mb-2" style="width: 40px; height: 40px;">
                                        <i class="feather-repeat fs-18"></i>
                                    </div>
                                    <div class="fs-12 text-muted fw-semibold">Умумий ҳаракатлар</div>
                                    <div class="fs-22 fw-bold text-info font-monospace">{{ $singleEmployeeData['total_events'] }} <span class="fs-13 fw-normal text-muted">та</span></div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 rounded-3 bg-light border text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center p-2 rounded-circle bg-warning-subtle text-warning mb-2" style="width: 40px; height: 40px;">
                                        <i class="feather-clock fs-18"></i>
                                    </div>
                                    <div class="fs-12 text-muted fw-semibold">Жами кечикиш</div>
                                    <div class="fs-22 fw-bold text-warning font-monospace">{{ $singleEmployeeData['total_late_minutes'] }} <span class="fs-13 fw-normal text-muted">дақиқа</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Tabs for Single Employee -->
                <ul class="nav nav-pills p-1 bg-light rounded-3 mb-3 d-print-none" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-semibold fs-13" id="tab-single-daily-btn" data-bs-toggle="pill" data-bs-target="#tab-single-daily">
                            <i class="feather-calendar me-1"></i> 📅 Кунлик жамланган хулоса ({{ count($singleEmployeeData['daily_records']) }} кун)
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-semibold fs-13" id="tab-single-events-btn" data-bs-toggle="pill" data-bs-target="#tab-single-events">
                            <i class="feather-list me-1"></i> 📋 Барча кириб-чиқиш вақтлари журнали ({{ $singleEmployeeData['total_events'] }} та)
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Tab 1: Daily Summaries -->
                    <div class="tab-pane fade show active" id="tab-single-daily">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                                <h6 class="card-title mb-0 fw-bold">
                                    <i class="feather-calendar text-primary me-2"></i>Кунлик давомат ва иш вақти кўрсаткичлари
                                </h6>
                                <span class="badge bg-light text-muted border fs-12">Жами: {{ count($singleEmployeeData['daily_records']) }} кун</span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr class="fs-12 text-uppercase text-muted">
                                                <th style="width: 45px;">#</th>
                                                <th>Сана</th>
                                                <th>Биринчи кириш</th>
                                                <th>Охирги чиқиш</th>
                                                <th>Кунлик ҳаракатлар</th>
                                                <th>Кечикиш</th>
                                                <th>Ишлаган вақти</th>
                                                <th>Ҳолати</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($singleEmployeeData['daily_records'] as $idx => $dr)
                                                @php
                                                    $dayDate = $dr['date'] ?? '';
                                                    $dayEvents = $singleEmployeeData['events_by_date'][$dayDate] ?? [];
                                                    $dayEntries = count(array_filter($dayEvents, fn($e) => ($e['direction'] ?? '') === 'entry'));
                                                    $dayExits   = count(array_filter($dayEvents, fn($e) => ($e['direction'] ?? '') === 'exit'));
                                                @endphp
                                                <tr>
                                                    <td class="text-muted fs-12">{{ $idx + 1 }}</td>
                                                    <td>
                                                        <span class="fw-bold text-dark font-monospace fs-13">
                                                            {{ \Carbon\Carbon::parse($dr['date'])->format('d.m.Y') }}
                                                        </span>
                                                        <small class="text-muted d-block">
                                                            {{ \Carbon\Carbon::parse($dr['date'])->translatedFormat('l') }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        @if(!empty($dr['first_entry']) && $dr['first_entry'] !== '-')
                                                            <span class="badge bg-success-subtle text-success font-monospace fs-12 px-2 py-1">
                                                                <i class="feather-log-in me-1"></i>{{ $dr['first_entry'] }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!empty($dr['last_exit']) && $dr['last_exit'] !== '-')
                                                            <span class="badge bg-primary-subtle text-primary font-monospace fs-12 px-2 py-1">
                                                                <i class="feather-log-out me-1"></i>{{ $dr['last_exit'] }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border font-monospace fs-12">
                                                            <span class="text-success fw-bold">{{ $dayEntries }} кириш</span> / 
                                                            <span class="text-primary fw-bold">{{ $dayExits }} чиқиш</span>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if(($dr['late_minutes'] ?? 0) > 0)
                                                            <span class="badge bg-warning text-dark px-2 py-1 fs-12">
                                                                +{{ $dr['late_minutes'] }} дақиқа
                                                            </span>
                                                        @else
                                                            <span class="text-muted fs-12">Кечикмаган</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border font-monospace fs-12">
                                                            {{ $dr['worked_duration'] ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @php $st = strtolower($dr['status'] ?? ''); @endphp
                                                        @if(str_contains($st, 'vaqtida') || $st === 'kelgan' || $st === 'present')
                                                            <span class="badge bg-soft-success text-success px-2 py-1">
                                                                <i class="feather-check me-1"></i>Ўз вақтида
                                                            </span>
                                                        @elseif(str_contains($st, 'kechik') || $st === 'late')
                                                            <span class="badge bg-soft-warning text-warning px-2 py-1">
                                                                <i class="feather-clock me-1"></i>Кечиккан
                                                            </span>
                                                        @elseif(str_contains($st, 'kelma') || $st === 'absent')
                                                            <span class="badge bg-soft-danger text-danger px-2 py-1">
                                                                <i class="feather-x me-1"></i>Келмаган
                                                            </span>
                                                        @else
                                                            <span class="badge bg-light text-muted px-2 py-1">{{ $dr['status'] ?? '-' }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center py-5 text-muted">
                                                        <i class="feather-file-text fs-36 d-block mb-2 text-muted"></i>
                                                        Ушбу давр бўйича кунлик ҳисобот маълумотлари топилмади.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Detailed Events Log (All entry/exit timestamps) -->
                    <div class="tab-pane fade" id="tab-single-events">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <h6 class="card-title mb-0 fw-bold">
                                        <i class="feather-list text-primary me-2"></i>Барча турникетдан кириш ва чиқиш вақтлари
                                    </h6>
                                    <small class="text-muted">Ҳар бир ҳаракатнинг аниқ вақти ва қайси турникетдан амалга оширилганлиги</small>
                                </div>
                                <span class="badge bg-primary fs-12 px-2 py-1">
                                    Жами ҳаракатлар: {{ $singleEmployeeData['total_events'] }} та
                                </span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped align-middle mb-0">
                                        <thead class="table-light">
                                            <tr class="fs-12 text-uppercase text-muted">
                                                <th style="width: 45px;">#</th>
                                                <th>Сана ва вақт</th>
                                                <th>Ҳаракат йўналиши</th>
                                                <th>Турникет / Қурилма</th>
                                                <th>Қурилма IP</th>
                                                <th>Тасдиқлаш усули</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($singleEmployeeData['events'] as $idx => $ev)
                                                @php
                                                    $dir = strtolower($ev['direction'] ?? '');
                                                    $isEntry = ($dir === 'entry' || $dir === 'in');
                                                @endphp
                                                <tr>
                                                    <td class="text-muted fs-12">{{ $idx + 1 }}</td>
                                                    <td>
                                                        <span class="fw-bold font-monospace fs-13 text-dark">
                                                            {{ \Carbon\Carbon::parse($ev['event_time'])->format('d.m.Y H:i:s') }}
                                                        </span>
                                                        <small class="text-muted d-block">
                                                            {{ \Carbon\Carbon::parse($ev['event_time'])->translatedFormat('l') }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        @if($isEntry)
                                                            <span class="badge bg-success px-2 py-1 fs-12 text-uppercase">
                                                                <i class="feather-log-in me-1"></i> КИРИШ
                                                            </span>
                                                        @else
                                                            <span class="badge bg-primary px-2 py-1 fs-12 text-uppercase">
                                                                <i class="feather-log-out me-1"></i> ЧИҚИШ
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="fw-semibold text-dark fs-13">
                                                            <i class="feather-cpu me-1 text-muted"></i>{{ $ev['device_name'] ?? 'Турникет' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="font-monospace text-muted fs-12">{{ $ev['device_ip'] ?? '-' }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border fs-11">
                                                            <i class="feather-check-circle me-1 text-success"></i>{{ $ev['verify_mode'] ?? 'Face/Card' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-5 text-muted">
                                                        <i class="feather-clock fs-36 d-block mb-2 text-muted"></i>
                                                        Ушбу давр бўйича кириш-чиқиш ҳаракатлари топилмади.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- ============================================================== -->
            <!-- [ ALOHIDA YAKKA XODIM HISOBOTI ] end                           -->
            <!-- ============================================================== -->

        @else

            <!-- ============================================================== -->
            <!-- [ UMUMIY HISOBOT: ASOSIY VA QO'RIQLASH XODIMLARI ] start       -->
            <!-- ============================================================== -->
            <div class="all-reports-wrapper">

                <!-- Meta Summary Banner (d-print-none) -->
                <div class="alert alert-light border shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 p-3 d-print-none">
                    <div class="d-flex align-items-center gap-2">
                        <i class="feather-calendar text-primary fs-20"></i>
                        <div>
                            <span class="fw-bold text-dark fs-14">Ҳисобот даври:</span>
                            <span class="text-muted ms-1">
                                {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} дан {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }} гача
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary fs-12 px-3 py-2">
                            🏢 Асосий ходимлар: <strong>{{ count($mainRecords) }} та</strong>
                        </span>
                        <span class="badge bg-warning text-dark fs-12 px-3 py-2">
                            🛡️ Қўриқлаш хизмати: <strong>{{ count($guardRecords) }} та</strong>
                        </span>
                    </div>
                </div>

                <!-- Main Navigation Tabs for Category Separation -->
                <ul class="nav nav-pills p-1 bg-light rounded-3 mb-3 d-print-none" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold fs-14" id="tab-main-records-btn" data-bs-toggle="pill" data-bs-target="#tab-main-records">
                            <i class="feather-users me-1 text-primary"></i> 🏢 Асосий ходимлар давомати 
                            <span class="badge bg-primary ms-1">{{ count($mainRecords) }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold fs-14 text-warning-emphasis" id="tab-guard-records-btn" data-bs-toggle="pill" data-bs-target="#tab-guard-records">
                            <i class="feather-shield me-1 text-warning"></i> 🛡️ Қўриқлаш хизмати ходимлари 
                            <span class="badge bg-warning text-dark ms-1">{{ count($guardRecords) }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content">

                    <!-- ============================================================== -->
                    <!-- TAB 1: ASOSIY XODIMLAR JADVALI (Qo'riqlash xodimlari yo'q)      -->
                    <!-- ============================================================== -->
                    <div class="tab-pane fade show active" id="tab-main-records">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <h6 class="card-title mb-0 fw-bold text-dark">
                                        <i class="feather-users text-primary me-2"></i>Асосий ходимлар давомат ҳисоботи
                                    </h6>
                                    <small class="text-muted">Ҳар бир ходим бўйича батафсил маълумот ва ҳаракатларни кўриш учун қатордаги тугмани босинг</small>
                                </div>
                                <span class="badge bg-light text-muted border fs-12">
                                    Ёзувлар сони: {{ count($mainRecords) }} та
                                </span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr class="fs-12 text-uppercase text-muted">
                                                <th style="width: 45px;">#</th>
                                                <th>Сана</th>
                                                <th>Ходим</th>
                                                <th>ID рақами</th>
                                                <th>Биринчи кириш</th>
                                                <th>Охирги чиқиш</th>
                                                <th>Кечикиш</th>
                                                <th>Ишлаган вақти</th>
                                                <th>Ҳолати</th>
                                                <th class="d-print-none text-end">Амал</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($mainRecords as $idx => $row)
                                                <tr>
                                                    <td class="text-muted fs-12">{{ $idx + 1 }}</td>
                                                    <td class="font-monospace fs-13 text-muted">
                                                        {{ \Carbon\Carbon::parse($row['date'])->format('d.m.Y') }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.turniket.reports', ['start_date' => $startDate, 'end_date' => $endDate, 'employee_filter' => $row['employee_no']]) }}" 
                                                           class="fw-bold text-dark fs-14 text-decoration-none hover-primary">
                                                            {{ $row['fullname'] ?? '-' }}
                                                        </a>
                                                        <small class="text-muted d-block">{{ $row['department'] ?? 'Институт' }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark font-monospace fs-12 border">
                                                            {{ $row['employee_no'] ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if(!empty($row['first_entry']) && $row['first_entry'] !== '-')
                                                            <span class="font-monospace fw-semibold text-success fs-13">
                                                                <i class="feather-log-in me-1"></i>{{ $row['first_entry'] }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!empty($row['last_exit']) && $row['last_exit'] !== '-')
                                                            <span class="font-monospace fw-semibold text-primary fs-13">
                                                                <i class="feather-log-out me-1"></i>{{ $row['last_exit'] }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(($row['late_minutes'] ?? 0) > 0)
                                                            <span class="badge bg-warning text-dark px-2 py-1 fs-12">
                                                                +{{ $row['late_minutes'] }} дақиқа
                                                            </span>
                                                        @else
                                                            <span class="text-muted fs-12">Кечикмаган</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border font-monospace fs-12">
                                                            {{ $row['worked_duration'] ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @php $st = strtolower($row['status'] ?? 'unknown'); @endphp
                                                        @if(str_contains($st, 'vaqtida') || $st === 'kelgan' || $st === 'present')
                                                            <span class="badge bg-soft-success text-success px-2 py-1">
                                                                <i class="feather-check me-1"></i>Ўз вақтида
                                                            </span>
                                                        @elseif(str_contains($st, 'kechik') || $st === 'late')
                                                            <span class="badge bg-soft-warning text-warning px-2 py-1">
                                                                <i class="feather-clock me-1"></i>Кечиккан
                                                            </span>
                                                        @elseif(str_contains($st, 'kelma') || $st === 'absent')
                                                            <span class="badge bg-soft-danger text-danger px-2 py-1">
                                                                <i class="feather-x me-1"></i>Келмаган
                                                            </span>
                                                        @else
                                                            <span class="badge bg-light text-muted px-2 py-1">{{ $row['status'] ?? '-' }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="d-print-none text-end">
                                                        <a href="{{ route('admin.turniket.reports', ['start_date' => $startDate, 'end_date' => $endDate, 'employee_filter' => $row['employee_no']]) }}" 
                                                           class="btn btn-xs btn-outline-primary" title="Ходим бўйича алоҳида ҳисобот">
                                                            <i class="feather-eye me-1"></i>Ҳисобот
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center py-5 text-muted">
                                                        <i class="feather-users fs-36 d-block mb-2 text-muted"></i>
                                                        Кўрсатилган давр бўйича асосий ходимлар ҳисобот маълумотлари топилмади.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================== -->
                    <!-- TAB 2: QO'RIQLASH XIZMATI XODIMLARI (ALOHIDA AJRATILGAN)        -->
                    <!-- ============================================================== -->
                    <div class="tab-pane fade" id="tab-guard-records">
                        
                        <!-- Special Guard Notice -->
                        <div class="alert alert-warning border border-warning-subtle shadow-sm mb-3 p-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="feather-shield fs-24 text-warning"></i>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Қўриқлаш хизмати ходимлари мониторинги</h6>
                                    <p class="fs-13 text-muted mb-0">
                                        Ушбу бўлимда фақат Қўриқлаш хизмати ходимларининг (<strong>ID: 00100, 0090, 0091, 00101</strong>) давомат кўрсаткичлари акс эттирилган бўлиб, улар асосий ходимлар ҳисоботидан тўлиқ алоҳида кўрсатилмоқда.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <h6 class="card-title mb-0 fw-bold text-dark">
                                        <i class="feather-shield text-warning me-2"></i>Қўриқлаш хизмати ходимлари давомат ҳисоботи
                                    </h6>
                                    <small class="text-muted">Навбатчилик ва кириш-чиқиш ҳаракатлари кўрсаткичлари</small>
                                </div>
                                <span class="badge bg-warning text-dark border fs-12">
                                    Қўриқчилар ёзувлари: {{ count($guardRecords) }} та
                                </span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr class="fs-12 text-uppercase text-muted">
                                                <th style="width: 45px;">#</th>
                                                <th>Сана</th>
                                                <th>Қўриқчи ходим</th>
                                                <th>ID рақами</th>
                                                <th>Биринчи кириш</th>
                                                <th>Охирги чиқиш</th>
                                                <th>Кечикиш</th>
                                                <th>Ишлаган вақти</th>
                                                <th>Ҳолати</th>
                                                <th class="d-print-none text-end">Амал</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($guardRecords as $idx => $row)
                                                <tr>
                                                    <td class="text-muted fs-12">{{ $idx + 1 }}</td>
                                                    <td class="font-monospace fs-13 text-muted">
                                                        {{ \Carbon\Carbon::parse($row['date'])->format('d.m.Y') }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.turniket.reports', ['start_date' => $startDate, 'end_date' => $endDate, 'employee_filter' => $row['employee_no']]) }}" 
                                                           class="fw-bold text-dark fs-14 text-decoration-none hover-warning">
                                                            <i class="feather-shield text-warning me-1"></i>{{ $row['fullname'] ?? '-' }}
                                                        </a>
                                                        <small class="text-muted d-block">{{ $row['department'] ?? 'Қўриқлаш хизмати' }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning-subtle text-dark border border-warning font-monospace fs-12">
                                                            {{ $row['employee_no'] ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if(!empty($row['first_entry']) && $row['first_entry'] !== '-')
                                                            <span class="font-monospace fw-semibold text-success fs-13">
                                                                <i class="feather-log-in me-1"></i>{{ $row['first_entry'] }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!empty($row['last_exit']) && $row['last_exit'] !== '-')
                                                            <span class="font-monospace fw-semibold text-primary fs-13">
                                                                <i class="feather-log-out me-1"></i>{{ $row['last_exit'] }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(($row['late_minutes'] ?? 0) > 0)
                                                            <span class="badge bg-warning text-dark px-2 py-1 fs-12">
                                                                +{{ $row['late_minutes'] }} дақиқа
                                                            </span>
                                                        @else
                                                            <span class="text-muted fs-12">Кечикмаган</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border font-monospace fs-12">
                                                            {{ $row['worked_duration'] ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @php $st = strtolower($row['status'] ?? 'unknown'); @endphp
                                                        @if(str_contains($st, 'vaqtida') || $st === 'kelgan' || $st === 'present')
                                                            <span class="badge bg-soft-success text-success px-2 py-1">
                                                                <i class="feather-check me-1"></i>Ўз вақтида
                                                            </span>
                                                        @elseif(str_contains($st, 'kechik') || $st === 'late')
                                                            <span class="badge bg-soft-warning text-warning px-2 py-1">
                                                                <i class="feather-clock me-1"></i>Кечиккан
                                                            </span>
                                                        @elseif(str_contains($st, 'kelma') || $st === 'absent')
                                                            <span class="badge bg-soft-danger text-danger px-2 py-1">
                                                                <i class="feather-x me-1"></i>Келмаган
                                                            </span>
                                                        @else
                                                            <span class="badge bg-light text-muted px-2 py-1">{{ $row['status'] ?? '-' }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="d-print-none text-end">
                                                        <a href="{{ route('admin.turniket.reports', ['start_date' => $startDate, 'end_date' => $endDate, 'employee_filter' => $row['employee_no']]) }}" 
                                                           class="btn btn-xs btn-outline-warning" title="Қўриқчи ҳаракатларини алоҳида кўриш">
                                                            <i class="feather-eye me-1"></i>Ҳисобот
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center py-5 text-muted">
                                                        <i class="feather-shield fs-36 d-block mb-2 text-warning"></i>
                                                        Кўрсатилган давр бўйича қўриқлаш хизмати ходимлари маълумотлари топилмади.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- ============================================================== -->
            <!-- [ UMUMIY HISOBOT ] end                                         -->
            <!-- ============================================================== -->

        @endif

        <!-- [ Print Official Footer (Faqat chop etilganda ko'rinadi) ] -->
        <div class="print-footer d-none d-print-block mt-5 pt-4 border-top">
            <div class="row">
                <div class="col-6">
                    <p class="mb-1"><strong>Маълумот шакллантирилган сана:</strong> {{ now()->format('d.m.Y H:i:s') }}</p>
                    <p class="mb-0 text-muted fs-11">Тизим: Smart Institut Турникет ва Давомат Мониторинги</p>
                </div>
                <div class="col-6 text-end">
                    <p class="mb-4"><strong>Кадрлар бўлими масъул ходими:</strong></p>
                    <p class="mb-0">__________________________ (имзо ва Ф.И.Ш.)</p>
                </div>
            </div>
        </div>

    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- ============================================================== -->
<!-- [ CUSTOM STYLES & PRINT CSS ]                                  -->
<!-- ============================================================== -->
<style>
.hover-primary:hover {
    color: #2563eb !important;
}
.hover-warning:hover {
    color: #d97706 !important;
}
.btn-xs {
    padding: 0.2rem 0.5rem;
    font-size: 0.75rem;
    line-height: 1.3;
    border-radius: 0.25rem;
}

/* Chop etish (Print) uchun maxsus CSS */
@media print {
    /* Ortiqcha navigatsiya elementlarini yashirish */
    .nxl-sidebar, 
    .nxl-header, 
    .page-header, 
    .filter-card, 
    .nav-pills, 
    .d-print-none, 
    .btn, 
    footer,
    .nxl-footer {
        display: none !important;
    }

    body {
        background: #ffffff !important;
        color: #000000 !important;
        font-family: 'Times New Roman', Times, serif, sans-serif !important;
        font-size: 11pt !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .nxl-container, .nxl-content, .main-content {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
        margin-bottom: 10px !important;
    }

    .table {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: 10pt !important;
    }

    .table th, .table td {
        border: 1px solid #333 !important;
        padding: 5px 7px !important;
        color: #000 !important;
    }

    .table th {
        background-color: #f2f2f2 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .badge {
        border: 1px solid #555 !important;
        color: #000 !important;
        background: transparent !important;
        font-weight: normal !important;
    }

    .badge.bg-success, .badge.bg-primary, .badge.bg-warning {
        font-weight: bold !important;
    }

    .print-header {
        display: block !important;
    }

    .print-footer {
        display: block !important;
    }
}
</style>

<!-- ============================================================== -->
<!-- [ JAVASCRIPT: QUICK DATE PRESETS ]                             -->
<!-- ============================================================== -->
<script>
function setQuickDate(range) {
    const startInput = document.getElementById('startDateInput');
    const endInput = document.getElementById('endDateInput');
    const form = document.getElementById('reportFilterForm');

    const now = new Date();
    const formatDate = (d) => {
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    if (range === 'today') {
        const todayStr = formatDate(now);
        startInput.value = todayStr;
        endInput.value = todayStr;
    } else if (range === 'yesterday') {
        const y = new Date(now);
        y.setDate(y.getDate() - 1);
        const yStr = formatDate(y);
        startInput.value = yStr;
        endInput.value = yStr;
    } else if (range === 'week') {
        const past7 = new Date(now);
        past7.setDate(past7.getDate() - 6);
        startInput.value = formatDate(past7);
        endInput.value = formatDate(now);
    } else if (range === 'month') {
        const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
        startInput.value = formatDate(firstDay);
        endInput.value = formatDate(now);
    }

    if (form) {
        form.submit();
    }
}

function setQuickStatus(status) {
    const statusSelect = document.getElementById('statusFilterSelect');
    const form = document.getElementById('reportFilterForm');
    if (statusSelect && form) {
        statusSelect.value = status;
        form.submit();
    }
}
</script>
@endsection
