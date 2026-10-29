@extends('layouts.admin')

@section('title', 'Давомат журнали — Турникет')

@section('content')
<div class="nxl-content d-flex flex-column h-100">
    <!-- [ Page Header ] start -->
    <div class="page-header py-3 px-3 px-lg-4 shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-text avatar-lg bg-success text-white rounded-circle shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                    <i class="feather-list fs-24"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="mb-1 text-white fw-bold fs-22">Давомат журнали</h3>
                        <span class="badge bg-primary text-uppercase px-2 py-1 fs-11">
                            ЖАМИ: {{ $pagination['total'] ?? count($logs) }} ТА ЁЗУВ
                        </span>
                    </div>
                    <p class="fs-14 text-white-50 mb-0">
                        Турникетлардан ўтган барча ходимларнинг тўлиқ реал-вақтдаги тарихи ва ҳаракат журнали
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('admin.turniket.dashboard') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-grid me-1"></i> Панел (Dashboard)
                </a>
                <a href="{{ route('admin.turniket.reports') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-file-text me-1"></i> Ҳисоботлар
                </a>
            </div>
        </div>
    </div>
    <!-- [ Page Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content p-3 p-lg-4">
        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.turniket.attendance') }}" method="GET" class="row g-2 align-items-end">
                    <!-- Date -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fs-12 fw-semibold text-muted mb-1">Сана:</label>
                        <input type="date" name="date" class="form-control form-control-sm" 
                               value="{{ request('date', date('Y-m-d')) }}">
                    </div>

                    <!-- Direction -->
                    <div class="col-12 col-sm-6 col-md-2">
                        <label class="form-label fs-12 fw-semibold text-muted mb-1">Йўналиш:</label>
                        <select name="direction" class="form-select form-select-sm">
                            <option value="">Барчаси</option>
                            <option value="entry" {{ request('direction') === 'entry' ? 'selected' : '' }}>Кириш</option>
                            <option value="exit" {{ request('direction') === 'exit' ? 'selected' : '' }}>Чиқиш</option>
                        </select>
                    </div>

                    <!-- Device -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fs-12 fw-semibold text-muted mb-1">Турникет қурилмаси:</label>
                        <select name="device_id" class="form-select form-select-sm">
                            <option value="">Барча турникетлар</option>
                            @foreach($devices as $dev)
                                <option value="{{ $dev['id'] }}" {{ request('device_id') == $dev['id'] ? 'selected' : '' }}>
                                    {{ $dev['name'] }} ({{ $dev['ip'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search Employee -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fs-12 fw-semibold text-muted mb-1">Ходим қидириш:</label>
                        <input type="text" name="search" class="form-control form-control-sm" 
                               placeholder="Ф.И.Ш ёки ID..." value="{{ request('search') }}">
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 col-md-1 d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary w-100" title="Филтрлаш">
                            <i class="feather-search"></i>
                        </button>
                        <a href="{{ route('admin.turniket.attendance') }}" class="btn btn-sm btn-light border" title="Тозалаш">
                            <i class="feather-x"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Attendance Table Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="fs-12 text-uppercase text-muted">
                                <th style="width: 50px;">#</th>
                                <th>Ходим</th>
                                <th>ID рақами</th>
                                <th>Ўтиш вақти</th>
                                <th>Йўналиш</th>
                                <th>Турникет</th>
                                <th>Тасдиқ усули</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $idx => $log)
                                <tr>
                                    <td class="text-muted fs-12">
                                        {{ (($pagination['current_page'] ?? 1) - 1) * ($pagination['per_page'] ?? 50) + $idx + 1 }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if(!empty($log['photo_url']))
                                                <img src="{{ $log['photo_url'] }}" alt="{{ $log['employee_name'] ?? '' }}" 
                                                     class="rounded-circle object-fit-cover shadow-sm" style="width: 44px; height: 44px;"
                                                     onerror="this.onerror=null; this.src='/assets/images/avatar/default.png';">
                                            @else
                                                <div class="avatar-text avatar-md bg-soft-primary text-primary rounded-circle">
                                                    <i class="feather-user"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark fs-14">{{ $log['employee_name'] ?? '-' }}</div>
                                                <small class="text-muted">{{ $log['department'] ?? 'Институт ходими' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark font-monospace fs-12">{{ $log['employee_no'] ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $evTime = !empty($log['event_time']) ? \Carbon\Carbon::parse($log['event_time'])->timezone('Asia/Tashkent')->format('H:i:s') : '-';
                                            $evDate = !empty($log['event_time']) ? \Carbon\Carbon::parse($log['event_time'])->timezone('Asia/Tashkent')->format('d.m.Y') : '';
                                        @endphp
                                        <div class="fw-bold font-monospace text-dark fs-14">{{ $evTime }}</div>
                                        <small class="text-muted fs-11">{{ $evDate }}</small>
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
                                        <div class="fw-semibold text-dark fs-13">{{ $log['device_name'] ?? '-' }}</div>
                                        <small class="text-muted font-monospace">{{ $log['device_ip'] ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-muted border font-monospace fs-11">
                                            <i class="feather-shield me-1"></i>{{ strtoupper($log['verify_mode'] ?? 'FACE') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="feather-inbox fs-40 d-block mb-2 text-muted"></i>
                                        Кўрсатилган филтр бўйича бирорта ҳам ўтиш ёзуви топилмади.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(($pagination['last_page'] ?? 1) > 1)
                    <div class="card-footer bg-white border-top p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="text-muted fs-13">
                            Саҳифа: <strong>{{ $pagination['current_page'] }}</strong> / {{ $pagination['last_page'] }} 
                            (Жами {{ $pagination['total'] }} та ёзув)
                        </div>
                        <ul class="pagination pagination-sm mb-0">
                            @if(($pagination['current_page'] ?? 1) > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1]) }}">
                                        <i class="feather-chevron-left"></i> Олдинги
                                    </a>
                                </li>
                            @endif

                            @for($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['last_page'], $pagination['current_page'] + 2); $i++)
                                <li class="page-item {{ $i == $pagination['current_page'] ? 'active' : '' }}">
                                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if(($pagination['current_page'] ?? 1) < ($pagination['last_page'] ?? 1))
                                <li class="page-item">
                                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] + 1]) }}">
                                        Кейинги <i class="feather-chevron-right"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection
