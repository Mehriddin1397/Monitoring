@extends('layouts.admin')

@section('title', 'Мустақил изланувчилар рўйхати')

@section('content')
<div class="nxl-content d-flex flex-column h-100">
    <!-- [ Page Header ] start -->
    <div class="page-header py-3 px-3 px-lg-4 shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-text avatar-lg bg-primary text-white rounded-circle shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                    <i class="feather-users fs-24"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="mb-1 text-white fw-bold fs-22">Мустақил изланувчилар рўйхати</h3>
                        @if($isOnline)
                            <span class="badge bg-success text-uppercase px-2 py-1 fs-11">
                                <span class="spinner-grow spinner-grow-sm me-1" style="width: 8px; height: 8px;" role="status"></span> ОНЛАЙН
                            </span>
                        @else
                            <span class="badge bg-danger text-uppercase px-2 py-1 fs-11">
                                <i class="feather-alert-triangle me-1"></i> ОФФЛАЙН
                            </span>
                        @endif
                    </div>
                    <p class="fs-14 text-white-50 mb-0">
                        Тизимдаги барча талабгорлар, уларнинг аризалари ва ўзлаштириш кўрсаткичлари
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('admin.izlanuvchilar.dashboard') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-pie-chart me-1"></i> Бошқарув панели
                </a>
                <a href="{{ route('admin.izlanuvchilar.tasks') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-check-circle me-1"></i> Вазифалар
                </a>
            </div>
        </div>
    </div>
    <!-- [ Page Header ] end -->

    <div class="main-content p-3 p-lg-4">
        <!-- [ Filter Bar ] start -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('admin.izlanuvchilar.researchers') }}" class="row g-2 align-items-center">
                    <div class="col-12 col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="feather-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Ф.И.Ш. ёки телефон рақами бўйича қидириш..." value="{{ $search }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <select name="has_project" class="form-select">
                            <option value="">Барча изланувчилар (Ҳаммаси)</option>
                            <option value="1" {{ $hasProject === '1' ? 'selected' : '' }}>Ариза / Лойиҳа топширганлар</option>
                            <option value="0" {{ $hasProject === '0' ? 'selected' : '' }}>Ҳали ариза топширмаганлар</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="feather-filter me-1"></i> Филтрлаш
                        </button>
                        @if($search !== '' || $hasProject !== '')
                            <a href="{{ route('admin.izlanuvchilar.researchers') }}" class="btn btn-outline-secondary" title="Тозалаш">
                                <i class="feather-x"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        <!-- [ Filter Bar ] end -->

        <!-- [ Researchers Table ] start -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title mb-0 fw-bold fs-16">
                    Изланувчилар рўйхати (Жами: {{ $pagination['total'] ?? count($researchers) }} та)
                </h5>
                <span class="text-muted fs-13">
                    Саҳифа {{ $pagination['current_page'] ?? 1 }} / {{ $pagination['last_page'] ?? 1 }}
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 60px;"># ID</th>
                                <th>Талабгор (Ф.И.Ш.)</th>
                                <th>Телефон</th>
                                <th>Ариза / Лойиҳа тури</th>
                                <th>Жорий босқич</th>
                                <th class="text-center">Вазифалар</th>
                                <th style="min-width: 140px;">Ўзлаштириш</th>
                                <th>Ҳолати</th>
                                <th class="pe-4 text-end">Рўйхатдан ўтган</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($researchers as $item)
                                <tr>
                                    <td class="ps-4 text-muted fw-semibold">
                                        #{{ $item['id'] }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-text avatar-sm bg-primary-subtle text-primary rounded-circle fw-bold">
                                                {{ mb_substr($item['full_name'] ?? 'U', 0, 1) }}
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark d-block">{{ $item['full_name'] }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="tel:{{ $item['phone'] }}" class="text-decoration-none text-muted">
                                            <i class="feather-phone me-1 text-primary"></i>{{ $item['phone'] }}
                                        </a>
                                    </td>
                                    <td>
                                        @if(!empty($item['has_project']) && !empty($item['project']))
                                            <span class="badge bg-primary-subtle text-primary px-2 py-1">
                                                {{ $item['project']['application_type'] ?? 'Лойиҳа' }}
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted border">Ариза топширилмаган</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($item['project']['current_stage']))
                                            <span class="badge bg-info-subtle text-info px-2 py-1">
                                                {{ $item['project']['current_stage']['order'] ?? 1 }}-босқич: {{ $item['project']['current_stage']['name'] ?? '' }}
                                            </span>
                                        @else
                                            <span class="text-muted fs-12">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(!empty($item['project']['tasks_summary']))
                                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1">
                                                {{ $item['project']['tasks_summary']['approved'] ?? 0 }} / {{ $item['project']['tasks_summary']['total'] ?? 0 }}
                                            </span>
                                        @else
                                            <span class="text-muted fs-12">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $percent = $item['project']['progress_percent'] ?? 0;
                                        @endphp
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px;">
                                                <div class="progress-bar {{ $percent >= 100 ? 'bg-success' : ($percent > 0 ? 'bg-primary' : 'bg-secondary') }}"
                                                     role="progressbar"
                                                     style="width: {{ $percent }}%">
                                                </div>
                                            </div>
                                            <span class="fs-12 fw-bold text-dark" style="min-width: 35px;">{{ $percent }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item['is_active'])
                                            <span class="badge bg-success-subtle text-success">Фаол</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Нофаол</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end text-muted fs-12">
                                        {{ \Carbon\Carbon::parse($item['created_at'])->format('d.m.Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="feather-users fs-40 d-block mb-2 text-muted"></i>
                                        Кўрсатилган шартларга мос изланувчилар топилмади.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- [ Custom Pagination ] start -->
                @if(($pagination['last_page'] ?? 1) > 1)
                    <div class="card-footer bg-white border-top py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="text-muted fs-13">
                            Жами: {{ $pagination['total'] ?? 0 }} тадан {{ (($pagination['current_page'] - 1) * $pagination['per_page']) + 1 }} - {{ min($pagination['current_page'] * $pagination['per_page'], $pagination['total']) }} кўрсатилмоқда
                        </div>
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Oldingi --}}
                            <li class="page-item {{ $pagination['current_page'] <= 1 ? 'disabled' : '' }}">
                                <a class="page-item page-link" href="{{ route('admin.izlanuvchilar.researchers', array_merge(request()->query(), ['page' => $pagination['current_page'] - 1])) }}">
                                    <i class="feather-chevron-left"></i>
                                </a>
                            </li>

                            {{-- Sahifalar --}}
                            @php
                                $startPage = max(1, $pagination['current_page'] - 2);
                                $endPage = min($pagination['last_page'], $pagination['current_page'] + 2);
                            @endphp

                            @if($startPage > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ route('admin.izlanuvchilar.researchers', array_merge(request()->query(), ['page' => 1])) }}">1</a>
                                </li>
                                @if($startPage > 2)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                            @endif

                            @for($p = $startPage; $p <= $endPage; $p++)
                                <li class="page-item {{ $p == $pagination['current_page'] ? 'active' : '' }}">
                                    <a class="page-link" href="{{ route('admin.izlanuvchilar.researchers', array_merge(request()->query(), ['page' => $p])) }}">{{ $p }}</a>
                                </li>
                            @endfor

                            @if($endPage < $pagination['last_page'])
                                @if($endPage < $pagination['last_page'] - 1)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ route('admin.izlanuvchilar.researchers', array_merge(request()->query(), ['page' => $pagination['last_page']])) }}">{{ $pagination['last_page'] }}</a>
                                </li>
                            @endif

                            {{-- Keyingi --}}
                            <li class="page-item {{ $pagination['current_page'] >= $pagination['last_page'] ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ route('admin.izlanuvchilar.researchers', array_merge(request()->query(), ['page' => $pagination['current_page'] + 1])) }}">
                                    <i class="feather-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif
                <!-- [ Custom Pagination ] end -->
            </div>
        </div>
        <!-- [ Researchers Table ] end -->
    </div>
</div>
@endsection
