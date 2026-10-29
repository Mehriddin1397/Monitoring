@extends('layouts.admin')

@section('title', 'Давомат ҳисоботлари — Турникет')

@section('content')
<div class="nxl-content d-flex flex-column h-100">
    <!-- [ Page Header ] start -->
    <div class="page-header py-3 px-3 px-lg-4 shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-text avatar-lg bg-warning text-dark rounded-circle shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                    <i class="feather-file-text fs-24"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="mb-1 text-white fw-bold fs-22">Давомат ҳисоботлари</h3>
                        <span class="badge bg-warning text-dark text-uppercase px-2 py-1 fs-11">
                            {{ strtoupper($type) }} ҲИСОБОТ
                        </span>
                    </div>
                    <p class="fs-14 text-white-50 mb-0">
                        Кунлик, ҳафталик ва ойлик иш вақти, кечикишлар ҳамда қатнашиш таҳлили
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
                <button type="button" class="btn btn-sm btn-primary" onclick="window.print();">
                    <i class="feather-printer me-1"></i> Чоп этиш
                </button>
            </div>
        </div>
    </div>
    <!-- [ Page Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content p-3 p-lg-4">
        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.turniket.reports') }}" method="GET" class="row g-2 align-items-end">
                    <!-- Report Type -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fs-12 fw-semibold text-muted mb-1">Ҳисобот тури:</label>
                        <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="daily" {{ $type === 'daily' ? 'selected' : '' }}>Кунлик ҳисобот</option>
                            <option value="weekly" {{ $type === 'weekly' ? 'selected' : '' }}>Ҳафталик ҳисобот</option>
                            <option value="monthly" {{ $type === 'monthly' ? 'selected' : '' }}>Ойлик ҳисобот</option>
                        </select>
                    </div>

                    <!-- Date -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fs-12 fw-semibold text-muted mb-1">Сана:</label>
                        <input type="date" name="date" class="form-control form-control-sm" value="{{ $date }}">
                    </div>

                    <!-- Employee Filter -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label fs-12 fw-semibold text-muted mb-1">Ходим бўйича филтр:</label>
                        <input type="text" name="employee_filter" class="form-control form-control-sm" 
                               placeholder="Ф.И.Ш ёки ID..." value="{{ request('employee_filter') }}">
                    </div>

                    <!-- Submit -->
                    <div class="col-12 col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="feather-filter me-1"></i> Кўрсатиш
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Meta Summary Banner -->
        <div class="alert alert-light border shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 p-3">
            <div class="d-flex align-items-center gap-2">
                <i class="feather-calendar text-primary fs-20"></i>
                <div>
                    <span class="fw-bold text-dark fs-14">Ҳисобот даври:</span>
                    <span class="text-muted ms-1">{{ $meta['start_date'] ?? $date }} дан {{ $meta['end_date'] ?? $date }} гача</span>
                </div>
            </div>
            <div>
                <span class="badge bg-primary fs-12 px-3 py-2">
                    Жами ёзувлар: <strong>{{ count($records) }} та</strong>
                </span>
            </div>
        </div>

        <!-- Reports Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="fs-12 text-uppercase text-muted">
                                <th style="width: 50px;">#</th>
                                <th>Сана</th>
                                <th>Ходим</th>
                                <th>ID рақами</th>
                                <th>Биринчи кириш</th>
                                <th>Охирги чиқиш</th>
                                <th>Кечикиш</th>
                                <th>Ишлаган вақти</th>
                                <th>Ҳолати</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $idx => $row)
                                <tr>
                                    <td class="text-muted fs-12">{{ $idx + 1 }}</td>
                                    <td class="font-monospace fs-13 text-muted">
                                        {{ $row['date'] ?? $date }}
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark fs-14">{{ $row['fullname'] ?? '-' }}</div>
                                        <small class="text-muted">{{ $row['department'] ?? 'Институт' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark font-monospace fs-12">{{ $row['employee_no'] ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="font-monospace fw-semibold text-success fs-13">
                                            {{ $row['first_entry'] ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="font-monospace fw-semibold text-primary fs-13">
                                            {{ $row['last_exit'] ?? '-' }}
                                        </span>
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
                                        @php
                                            $st = strtolower($row['status'] ?? 'unknown');
                                        @endphp
                                        @if($st === 'kelgan' || $st === 'present')
                                            <span class="badge bg-soft-success text-success px-2 py-1">
                                                <i class="feather-check me-1"></i>Келган
                                            </span>
                                        @elseif($st === 'kechikkan' || $st === 'late')
                                            <span class="badge bg-soft-warning text-warning px-2 py-1">
                                                <i class="feather-clock me-1"></i>Кечиккан
                                            </span>
                                        @elseif($st === 'kelmagan' || $st === 'absent')
                                            <span class="badge bg-soft-danger text-danger px-2 py-1">
                                                <i class="feather-x me-1"></i>Келмаган
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted px-2 py-1">{{ $row['status'] ?? '-' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="feather-file-text fs-40 d-block mb-2 text-muted"></i>
                                        Кўрсатилган давр бўйича ҳисобот маълумотлари топилмади.
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
@endsection
