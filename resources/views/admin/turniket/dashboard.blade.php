@extends('layouts.admin')

@section('title', 'Турникет ва давомат мониторинги')

@section('content')
<div class="nxl-content d-flex flex-column h-100">
    <!-- [ Page Header ] start -->
    <div class="page-header py-3 px-3 px-lg-4 shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-text avatar-lg bg-primary text-white rounded-circle shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                    <i class="feather-user-check fs-24"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="mb-1 text-white fw-bold fs-22">Турникет ва давомат мониторинги</h3>
                        @if($isOnline)
                            <span class="badge bg-success text-uppercase px-2 py-1 fs-11" id="server-status-badge">
                                <span class="pulse-dot"></span> ОНЛАЙН (192.168.40.95)
                            </span>
                        @else
                            <span class="badge bg-danger text-uppercase px-2 py-1 fs-11" id="server-status-badge">
                                <i class="feather-alert-triangle me-1"></i> ОФФЛАЙН
                            </span>
                        @endif
                    </div>
                    <p class="fs-14 text-white-50 mb-0">
                        Hikvision DS-K1T671MF турникетлари орқали ходимларнинг реал-вақтдаги давомати ва ҳаракати
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('admin.turniket.attendance') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-list me-1"></i> Давомат журнали
                </a>
                <a href="{{ route('admin.turniket.leaves') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-check-square me-1"></i> Сабаблилар / Таътиллар
                </a>
                <a href="{{ route('admin.turniket.reports') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-file-text me-1"></i> Ҳисоботлар
                </a>
                <a href="{{ route('admin.turniket.devices') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-cpu me-1"></i> Турникетлар
                </a>
                <button type="button" class="btn btn-sm btn-primary" onclick="window.location.reload();">
                    <i class="feather-refresh-cw me-1"></i> Янгилаш
                </button>
            </div>
        </div>
    </div>
    <!-- [ Page Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content p-3 p-lg-4">
        <!-- 1. KPI Cards Row -->
        <div class="row g-3 mb-4">
            <!-- Total Employees -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 kpi-card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted fs-12 fw-semibold text-uppercase">Жами ходимлар</span>
                            <span class="avatar-text avatar-sm bg-soft-primary text-primary rounded">
                                <i class="feather-users fs-16"></i>
                            </span>
                        </div>
                        <h3 class="fw-bold mb-1 fs-24" id="kpi-total">{{ $metrics['total_employees'] ?? 0 }}</h3>
                        <div class="fs-12 text-muted">Рўйхатга олинганлар</div>
                    </div>
                </div>
            </div>

            <!-- Arrived Today -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 kpi-card border-start border-success border-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-success fs-12 fw-semibold text-uppercase">Бугун келганлар</span>
                            <span class="avatar-text avatar-sm bg-soft-success text-success rounded">
                                <i class="feather-check-circle fs-16"></i>
                            </span>
                        </div>
                        <h3 class="fw-bold mb-1 fs-24 text-success" id="kpi-arrived">{{ $metrics['arrived_count'] ?? 0 }}</h3>
                        <div class="fs-12 text-muted">
                            @php
                                $total = $metrics['total_employees'] ?? 1;
                                $pct = $total > 0 ? round((($metrics['arrived_count'] ?? 0) / $total) * 100) : 0;
                            @endphp
                            <span>{{ $pct }}% қатнашиш</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Currently Inside -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 kpi-card border-start border-info border-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-info fs-12 fw-semibold text-uppercase">Ҳозир бинода</span>
                            <span class="avatar-text avatar-sm bg-soft-info text-info rounded">
                                <i class="feather-home fs-16"></i>
                            </span>
                        </div>
                        <h3 class="fw-bold mb-1 fs-24 text-info" id="kpi-inside">{{ $metrics['inside_count'] ?? 0 }}</h3>
                        <div class="fs-12 text-muted">Ичкаридаги ходимлар</div>
                    </div>
                </div>
            </div>

            <!-- Late Today -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 kpi-card border-start border-warning border-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-warning fs-12 fw-semibold text-uppercase">Кечикканлар</span>
                            <span class="avatar-text avatar-sm bg-soft-warning text-warning rounded">
                                <i class="feather-clock fs-16"></i>
                            </span>
                        </div>
                        <h3 class="fw-bold mb-1 fs-24 text-warning" id="kpi-late">{{ $metrics['late_count'] ?? 0 }}</h3>
                        <div class="fs-12 text-muted">09:00 дан кейин</div>
                    </div>
                </div>
            </div>

            <!-- Absent Today -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 kpi-card border-start border-danger border-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-danger fs-12 fw-semibold text-uppercase">Келмаганлар</span>
                            <span class="avatar-text avatar-sm bg-soft-danger text-danger rounded">
                                <i class="feather-user-x fs-16"></i>
                            </span>
                        </div>
                        <h3 class="fw-bold mb-1 fs-24 text-danger" id="kpi-absent">{{ $metrics['absent_count'] ?? 0 }}</h3>
                        <div class="fs-12 text-muted">Бугун қайд этилмаган</div>
                    </div>
                </div>
            </div>

            <!-- Devices Status -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 kpi-card border-start border-primary border-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-primary fs-12 fw-semibold text-uppercase">Турникетлар</span>
                            <span class="avatar-text avatar-sm bg-soft-primary text-primary rounded">
                                <i class="feather-shield fs-16"></i>
                            </span>
                        </div>
                        <h3 class="fw-bold mb-1 fs-24 text-primary" id="kpi-devices">
                            {{ $metrics['devices_online'] ?? 0 }} / {{ $metrics['devices_total'] ?? 0 }}
                        </h3>
                        <div class="fs-12 text-success">
                            <i class="feather-activity me-1"></i>Барчаси онлайн
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Devices Mini Cards -->
        <div class="row g-3 mb-4">
            @forelse($devices as $dev)
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-text avatar-md rounded-circle {{ ($dev['type'] ?? '') === 'entry' ? 'bg-soft-success text-success' : 'bg-soft-primary text-primary' }}">
                                    <i class="feather-{{ ($dev['type'] ?? '') === 'entry' ? 'log-in' : 'log-out' }} fs-18"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold fs-14">{{ $dev['name'] ?? 'Турникет' }}</h6>
                                    <span class="text-muted fs-12">{{ $dev['ip'] ?? '' }}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ ($dev['status'] ?? '') === 'online' ? 'bg-success' : 'bg-danger' }} fs-11 px-2 py-1 mb-1">
                                    {{ strtoupper($dev['status'] ?? 'UNKNOWN') }}
                                </span>
                                <div class="fs-12 text-muted fw-semibold">{{ $dev['today_logs_count'] ?? 0 }} та ўтиш</div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning py-2 mb-0">
                        <i class="feather-info me-2"></i> Турникет қурилмалари маълумоти олинмади.
                    </div>
                </div>
            @endforelse
        </div>

        <!-- 3. Main Data Tabs -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-3">
                <ul class="nav nav-pills card-header-pills gap-2" id="turniketTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-3 py-2 fw-semibold" id="tab-arrived-btn" data-bs-toggle="tab" data-bs-target="#tab-arrived" type="button" role="tab">
                            <i class="feather-check-circle me-1"></i> Келганлар
                            <span class="badge bg-success ms-1">{{ count($arrived) }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2 fw-semibold" id="tab-late-btn" data-bs-toggle="tab" data-bs-target="#tab-late" type="button" role="tab">
                            <i class="feather-clock me-1 text-warning"></i> Кечикканлар
                            <span class="badge bg-warning ms-1">{{ count($late) }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2 fw-semibold" id="tab-inside-btn" data-bs-toggle="tab" data-bs-target="#tab-inside" type="button" role="tab">
                            <i class="feather-home me-1 text-info"></i> Ҳозир бинода
                            <span class="badge bg-info ms-1">{{ count($inside) }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2 fw-semibold" id="tab-absent-btn" data-bs-toggle="tab" data-bs-target="#tab-absent" type="button" role="tab">
                            <i class="feather-user-x me-1 text-danger"></i> Келмаганлар
                            <span class="badge bg-danger ms-1">{{ count($absent) }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2 fw-semibold" id="tab-recent-btn" data-bs-toggle="tab" data-bs-target="#tab-recent" type="button" role="tab">
                            <i class="feather-activity me-1 text-primary"></i> Сўнгги ўтишлар
                            <span class="badge bg-primary ms-1">{{ count($recentAttendance) }}</span>
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-0">
                <div class="tab-content" id="turniketTabsContent">
                    
                    <!-- TAB 1: ARRIVED -->
                    <div class="tab-pane fade show active" id="tab-arrived" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="fs-12 text-uppercase text-muted">
                                        <th style="width: 50px;">#</th>
                                        <th>Ходим</th>
                                        <th>ID рақами</th>
                                        <th>Биринчи кириш</th>
                                        <th>Қурилма</th>
                                        <th>Ҳолати</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($arrived as $idx => $item)
                                        <tr>
                                            <td class="text-muted fs-12">{{ $idx + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    @if(!empty($item['photo_url']))
                                                        <img src="{{ $item['photo_url'] }}" alt="{{ $item['fullname'] ?? '' }}" 
                                                             class="rounded-circle object-fit-cover shadow-sm" style="width: 42px; height: 42px;"
                                                             onerror="this.onerror=null; this.src='/assets/images/avatar/default.png';">
                                                    @else
                                                        <div class="avatar-text avatar-md bg-soft-primary text-primary rounded-circle">
                                                            <i class="feather-user"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold text-dark fs-14">{{ $item['fullname'] ?? 'Noma\'lum xodim' }}</div>
                                                        <small class="text-muted">{{ $item['department'] ?? 'Институт' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark font-monospace fs-12">{{ $item['employee_no'] ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $entryTime = !empty($item['first_entry']) ? \Carbon\Carbon::parse($item['first_entry'])->timezone('Asia/Tashkent')->format('H:i:s') : '-';
                                                @endphp
                                                <span class="fw-bold text-dark font-monospace fs-14">
                                                    <i class="feather-clock text-success me-1"></i>{{ $entryTime }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-13">
                                                    <i class="feather-shield text-muted me-1"></i>{{ $item['device_name'] ?? 'Кириш турникети' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if(!empty($item['is_late']))
                                                    <span class="badge bg-soft-warning text-warning px-2 py-1">
                                                        <i class="feather-alert-circle me-1"></i>Кечиккан
                                                    </span>
                                                @else
                                                    <span class="badge bg-soft-success text-success px-2 py-1">
                                                        <i class="feather-check me-1"></i>Ўз вақтида
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="feather-inbox fs-32 d-block mb-2 text-muted"></i>
                                                Бугун ҳали бирорта ходим қайд этилмаган.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 2: LATE -->
                    <div class="tab-pane fade" id="tab-late" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="fs-12 text-uppercase text-muted">
                                        <th style="width: 50px;">#</th>
                                        <th>Ходим</th>
                                        <th>ID рақами</th>
                                        <th>Келган вақти</th>
                                        <th>Кечикиш дақиқаси</th>
                                        <th>Қурилма</th>
                                        <th>Ҳолати</th>
                                        <th class="text-end" style="width: 140px;">Амал</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($late as $idx => $item)
                                        <tr>
                                            <td class="text-muted fs-12">{{ $idx + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    @if(!empty($item['photo_url']))
                                                        <img src="{{ $item['photo_url'] }}" alt="{{ $item['fullname'] ?? '' }}" 
                                                             class="rounded-circle object-fit-cover shadow-sm" style="width: 42px; height: 42px;"
                                                             onerror="this.onerror=null; this.src='/assets/images/avatar/default.png';">
                                                    @else
                                                        <div class="avatar-text avatar-md bg-soft-warning text-warning rounded-circle">
                                                            <i class="feather-user"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold text-dark fs-14">{{ $item['fullname'] ?? '-' }}</div>
                                                        <small class="text-muted">{{ $item['department'] ?? 'Институт' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark font-monospace fs-12">{{ $item['employee_no'] ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $entryTime = !empty($item['first_entry']) ? \Carbon\Carbon::parse($item['first_entry'])->timezone('Asia/Tashkent')->format('H:i:s') : '-';
                                                @endphp
                                                <span class="fw-bold text-warning font-monospace fs-14">
                                                    <i class="feather-clock me-1"></i>{{ $entryTime }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark px-2 py-1 fs-12">
                                                    +{{ $item['late_minutes'] ?? 0 }} дақиқа
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-13">{{ $item['device_name'] ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @if(!empty($item['has_excuse']))
                                                    <span class="badge bg-soft-info text-info">
                                                        <i class="feather-check-circle me-1"></i>Сабабли
                                                    </span>
                                                @else
                                                    <span class="badge bg-soft-warning text-warning">
                                                        <i class="feather-alert-circle me-1"></i>Сабабсиз
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-outline-warning py-1 px-2 fs-12" 
                                                        onclick="openExcuseModal('{{ $item['employee_no'] }}', 'late_excuse')">
                                                    <i class="feather-edit-2 me-1"></i> Сабаб ёзиш
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="feather-smile fs-32 d-block mb-2 text-success"></i>
                                                Бугун кечиккан ходимлар мавжуд эмас!
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 3: INSIDE NOW -->
                    <div class="tab-pane fade" id="tab-inside" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="fs-12 text-uppercase text-muted">
                                        <th style="width: 50px;">#</th>
                                        <th>Ходим</th>
                                        <th>ID рақами</th>
                                        <th>Охирги кириш вақти</th>
                                        <th>Қурилма</th>
                                        <th>Ҳолати</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inside as $idx => $item)
                                        <tr>
                                            <td class="text-muted fs-12">{{ $idx + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    @if(!empty($item['photo_url']))
                                                        <img src="{{ $item['photo_url'] }}" alt="{{ $item['fullname'] ?? '' }}" 
                                                             class="rounded-circle object-fit-cover shadow-sm" style="width: 42px; height: 42px;"
                                                             onerror="this.onerror=null; this.src='/assets/images/avatar/default.png';">
                                                    @else
                                                        <div class="avatar-text avatar-md bg-soft-info text-info rounded-circle">
                                                            <i class="feather-user"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold text-dark fs-14">{{ $item['fullname'] ?? '-' }}</div>
                                                        <small class="text-muted">{{ $item['department'] ?? 'Институт' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark font-monospace fs-12">{{ $item['employee_no'] ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $lastTime = !empty($item['last_time']) ? \Carbon\Carbon::parse($item['last_time'])->timezone('Asia/Tashkent')->format('H:i:s') : '-';
                                                @endphp
                                                <span class="fw-bold text-info font-monospace fs-14">
                                                    <i class="feather-log-in me-1"></i>{{ $lastTime }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-13">{{ $item['device_name'] ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-soft-info text-info px-2 py-1">
                                                    <span class="pulse-dot bg-info"></span> Бино ичида
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="feather-inbox fs-32 d-block mb-2 text-muted"></i>
                                                Ҳозирда бино ичида ҳеч ким қайд этилмаган.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 4: ABSENT -->
                    <div class="tab-pane fade" id="tab-absent" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="fs-12 text-uppercase text-muted">
                                        <th style="width: 50px;">#</th>
                                        <th>Ходим</th>
                                        <th>ID рақами</th>
                                        <th>Бўлим</th>
                                        <th>Сабаби</th>
                                        <th>Ҳолати</th>
                                        <th class="text-end" style="width: 140px;">Амал</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($absent as $idx => $item)
                                        <tr>
                                            <td class="text-muted fs-12">{{ $idx + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    @if(!empty($item['photo_url']))
                                                        <img src="{{ $item['photo_url'] }}" alt="{{ $item['fullname'] ?? '' }}" 
                                                             class="rounded-circle object-fit-cover shadow-sm" style="width: 42px; height: 42px;"
                                                             onerror="this.onerror=null; this.src='/assets/images/avatar/default.png';">
                                                    @else
                                                        <div class="avatar-text avatar-md bg-soft-danger text-danger rounded-circle">
                                                            <i class="feather-user"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold text-dark fs-14">{{ $item['fullname'] ?? '-' }}</div>
                                                        <small class="text-muted">{{ $item['position'] ?? 'Ходим' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark font-monospace fs-12">{{ $item['employee_no'] ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-13">{{ $item['department'] ?? 'Институт' }}</span>
                                            </td>
                                            <td>
                                                @if(!empty($item['is_excused']))
                                                    <span class="badge bg-soft-info text-info">
                                                        {{ $item['leave_type'] ?? 'Сабабли' }} ({{ $item['excuse_reason'] ?? '-' }})
                                                    </span>
                                                @else
                                                    <span class="text-muted fs-13">Сабаб кўрсатилмаган</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-soft-danger text-danger px-2 py-1">
                                                    <i class="feather-x me-1"></i>Келмаган
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2 fs-12" 
                                                        onclick="openExcuseModal('{{ $item['employee_no'] }}', 'business_trip')">
                                                    <i class="feather-plus-circle me-1"></i> Сабаб ёзиш
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="feather-check-circle fs-32 d-block mb-2 text-success"></i>
                                                Барча ходимлар ишга келган!
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 5: RECENT ATTENDANCE -->
                    <div class="tab-pane fade" id="tab-recent" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="fs-12 text-uppercase text-muted">
                                        <th style="width: 50px;">#</th>
                                        <th>Ходим</th>
                                        <th>Вақти</th>
                                        <th>Йўналиш</th>
                                        <th>Турникет</th>
                                        <th>Усул</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentAttendance as $idx => $log)
                                        <tr>
                                            <td class="text-muted fs-12">{{ $idx + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    @if(!empty($log['photo_url']))
                                                        <img src="{{ $log['photo_url'] }}" alt="{{ $log['employee_name'] ?? '' }}" 
                                                             class="rounded-circle object-fit-cover shadow-sm" style="width: 40px; height: 40px;"
                                                             onerror="this.onerror=null; this.src='/assets/images/avatar/default.png';">
                                                    @else
                                                        <div class="avatar-text avatar-md bg-soft-primary text-primary rounded-circle">
                                                            <i class="feather-user"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold text-dark fs-14">{{ $log['employee_name'] ?? 'Ходим' }}</div>
                                                        <small class="text-muted font-monospace">ID: {{ $log['employee_no'] ?? '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $evTime = !empty($log['event_time']) ? \Carbon\Carbon::parse($log['event_time'])->timezone('Asia/Tashkent')->format('H:i:s (d.m.Y)') : '-';
                                                @endphp
                                                <span class="fw-bold text-dark font-monospace fs-13">{{ $evTime }}</span>
                                            </td>
                                            <td>
                                                @if(($log['direction'] ?? '') === 'entry')
                                                    <span class="badge bg-success px-2 py-1 fs-12">
                                                        <i class="feather-arrow-down-right me-1"></i>КИРИШ
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark px-2 py-1 fs-12">
                                                        <i class="feather-arrow-up-right me-1"></i>ЧИҚИШ
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-muted fs-13">{{ $log['device_name'] ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-muted border font-monospace fs-11">
                                                    {{ strtoupper($log['verify_mode'] ?? 'FACE') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                Ҳозирча ўтишлар мавжуд эмас.
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
    <!-- [ Main Content ] end -->
</div>

<style>
.kpi-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.08) !important;
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

<!-- ======================================================== -->
<!-- MODAL: Сабаб / Таътил киритиш                            -->
<!-- ======================================================== -->
<div id="excuseCustomOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999999; align-items:center; justify-content:center;">
    <div class="card border-0 shadow-lg" style="width: 540px; max-width: 95%; max-height: 90vh; overflow-y: auto;">
        <div class="card-header bg-dark text-white p-3 d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0 fs-16 text-white fw-bold">
                <i class="feather-check-square me-2 text-warning"></i> Ходимга сабаб / таътил киритиш
            </h5>
            <button type="button" class="btn-close btn-close-white" onclick="closeExcuseModal()"></button>
        </div>
        <form action="{{ route('admin.turniket.leaves.store') }}" method="POST">
            @csrf
            <div class="card-body p-4">
                <!-- Employee select -->
                <div class="mb-3">
                    <label class="form-label fs-13 fw-semibold text-muted">Ходимни танланг <span class="text-danger">*</span></label>
                    <select name="employee_no" id="modal_employee_no" class="form-select" required>
                        <option value="">-- Ходимни танланг --</option>
                        @foreach($allEmployees as $emp)
                            <option value="{{ $emp['employee_no'] }}">
                                {{ $emp['fullname'] }} (ID: {{ $emp['employee_no'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Type -->
                <div class="mb-3">
                    <label class="form-label fs-13 fw-semibold text-muted">Сабаб тури <span class="text-danger">*</span></label>
                    <select name="type" id="modal_type" class="form-select" required>
                        <option value="late_excuse">Кечикиш сабаби / рухсатнома</option>
                        <option value="business_trip">Хизмат сафари (командировка)</option>
                        <option value="vacation">Меҳнат таътили</option>
                        <option value="sick_leave">Касаллик варақаси (больничный)</option>
                        <option value="other">Бошқа сабаблар</option>
                    </select>
                </div>

                <!-- Dates -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fs-13 fw-semibold text-muted">Бошланиш санаси</label>
                        <input type="date" name="start_date" id="modal_start_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label fs-13 fw-semibold text-muted">Тугаш санаси</label>
                        <input type="date" name="end_date" id="modal_end_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <!-- Reason text -->
                <div class="mb-3">
                    <label class="form-label fs-13 fw-semibold text-muted">Сабаб ёки изоҳ матни <span class="text-danger">*</span></label>
                    <textarea name="reason" id="modal_reason" rows="3" class="form-control" 
                              placeholder="Масалан: Тирбандлик сабабли 15 дақиқа кечикди, раҳбариятдан рухсат олган..." required maxlength="500"></textarea>
                    <small class="text-muted fs-11">Максимал 500 белги.</small>
                </div>

                <!-- Document no -->
                <div class="mb-2">
                    <label class="form-label fs-13 fw-semibold text-muted">Ҳужжат / Буйруқ рақами (ихтиёрий)</label>
                    <input type="text" name="document_no" id="modal_document_no" class="form-control" placeholder="Масалан: Буйруқ № 142-К">
                </div>
            </div>

            <div class="card-footer bg-light p-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-sm btn-light border" onclick="closeExcuseModal()">Бекор қилиш</button>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="feather-check me-1"></i> Сақлаш ва тасдиқлаш
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openExcuseModal(employeeNo, type) {
    const overlay = document.getElementById('excuseCustomOverlay');
    if (!overlay) return;

    if (employeeNo) {
        const empSelect = document.getElementById('modal_employee_no');
        if (empSelect) empSelect.value = employeeNo;
    }
    if (type) {
        const typeSelect = document.getElementById('modal_type');
        if (typeSelect) typeSelect.value = type;
    }

    overlay.style.display = 'flex';
}

function closeExcuseModal() {
    const overlay = document.getElementById('excuseCustomOverlay');
    if (overlay) overlay.style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeExcuseModal();
});

// Auto-poll metrics every 15 seconds to keep dashboard fresh
document.addEventListener('DOMContentLoaded', function() {
    setInterval(function() {
        fetch('{{ route("admin.turniket.live_stats") }}')
            .then(res => res.json())
            .then(data => {
                if (data.success && data.metrics) {
                    const m = data.metrics;
                    const elTotal = document.getElementById('kpi-total');
                    const elArrived = document.getElementById('kpi-arrived');
                    const elInside = document.getElementById('kpi-inside');
                    const elLate = document.getElementById('kpi-late');
                    const elAbsent = document.getElementById('kpi-absent');
                    const elDevices = document.getElementById('kpi-devices');

                    if (elTotal) elTotal.innerText = m.total_employees || 0;
                    if (elArrived) elArrived.innerText = m.arrived_count || 0;
                    if (elInside) elInside.innerText = m.inside_count || 0;
                    if (elLate) elLate.innerText = m.late_count || 0;
                    if (elAbsent) elAbsent.innerText = m.absent_count || 0;
                    if (elDevices) elDevices.innerText = (m.devices_online || 0) + ' / ' + (m.devices_total || 0);
                }
            })
            .catch(() => {});
    }, 15000);
});
</script>
@endsection
