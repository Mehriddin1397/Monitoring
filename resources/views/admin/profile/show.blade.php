@extends('layouts.admin')

@section('title', 'Шахсий профиль — ' . ($user->name ?? 'Фойдаланувчи'))

@section('content')
<div class="nxl-content d-flex flex-column h-100">

    <!-- [ Page Header / Profile Hero ] start -->
    <div class="page-header py-4 px-3 px-lg-4 shadow-sm position-relative overflow-hidden" 
         style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #1e293b 100%); border-bottom: 3px solid #4f46e5;">
        
        <div class="position-absolute" style="top: -60px; right: 5%; width: 260px; height: 260px; background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, transparent 70%); pointer-events: none;"></div>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative" style="z-index: 2;">
            
            <!-- User Info Left -->
            <div class="d-flex align-items-center gap-3">
                <div class="position-relative">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                         class="rounded-circle object-fit-cover shadow-lg border border-3 border-white" 
                         style="width: 84px; height: 84px;"
                         onerror="this.onerror=null; this.src='{{ asset('assets/images/8214212.png') }}';">
                    <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success p-2 border border-2 border-white" title="Тизимда фаол">
                        <span class="visually-hidden">Фаол</span>
                    </span>
                </div>

                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <h3 class="mb-0 text-white fw-bold fs-22">{{ $user->name }}</h3>
                        <span class="badge bg-primary text-uppercase px-2.5 py-1 fs-11 fw-semibold">
                            {{ $user->role }}
                        </span>
                    </div>

                    <p class="fs-13 text-white-50 mb-1">
                        <i class="feather-mail me-1"></i> {{ $user->email }}
                        @if(!empty($employee?->phone))
                            <span class="mx-2">•</span>
                            <i class="feather-phone me-1"></i> {{ $employee->phone }}
                        @endif
                        @if(!empty($employee?->position))
                            <span class="mx-2">•</span>
                            <i class="feather-briefcase me-1"></i> {{ $employee->position }}
                        @endif
                    </p>

                    <div class="d-flex align-items-center gap-2 text-white-50 fs-12">
                        <span><i class="feather-shield text-info me-1"></i>Шахсий бошқарув профили</span>
                        @if(!empty($employee?->department))
                            <span>• {{ $employee->department }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Header Action: Logout Button -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="feather-arrow-left me-1"></i> Бош саҳифа
                </a>

                <button type="button" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm d-flex align-items-center gap-1.5 fw-semibold" 
                        onclick="event.preventDefault(); document.getElementById('profile-logout-form').submit();">
                    <i class="feather-log-out"></i> Тизимдан чиқиш
                </button>

                <form id="profile-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>

        </div>
    </div>
    <!-- [ Page Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content p-3 p-lg-4">

        <!-- 4 KPI Metrics Row -->
        <div class="row g-3 mb-4">
            
            <!-- 1. Tug'ilgan kuni -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden" 
                     style="background: linear-gradient(135deg, #ffffff 0%, #fefce8 100%); border-left: 4px solid #eab308 !important;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small fw-semibold text-uppercase">Туғилган куни</span>
                            <div class="avatar-text avatar-sm bg-soft-warning text-warning rounded-circle">
                                <i class="feather-gift fs-16"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">
                            {{ $birthdayInfo['formatted'] ?? ($employee->birth_date ?? 'Кўрсатилмаган') }}
                        </h4>
                        <div class="fs-12 text-muted">
                            @if(!empty($birthdayInfo))
                                <span class="badge bg-warning text-dark px-2 py-0.5 me-1">{{ $birthdayInfo['age'] }} ёш</span>
                                @if(!empty($birthdayInfo['is_today']))
                                    <span class="text-danger fw-bold">🎉 Бугун туғилган куни!</span>
                                @elseif(isset($birthdayInfo['days_remaining']))
                                    <span>Яна {{ $birthdayInfo['days_remaining'] }} кун қолди</span>
                                @endif
                            @else
                                <span>Маълумот киритилмаган</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. I-indexdagi bahosi -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden" 
                     style="background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%); border-left: 4px solid #22c55e !important;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small fw-semibold text-uppercase">И-индекс баҳоси</span>
                            <div class="avatar-text avatar-sm bg-soft-success text-success rounded-circle">
                                <i class="feather-trending-up fs-16"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-success mb-1">
                            {{ $iIndexTotalScore }} <span class="fs-14 fw-normal text-muted">балл</span>
                        </h4>
                        <div class="fs-12 text-muted">
                            Жами мақолалар: <strong class="text-dark">{{ $articles->count() }} та</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Topshiriqlar ijrosi -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden" 
                     style="background: linear-gradient(135deg, #ffffff 0%, #eff6ff 100%); border-left: 4px solid #3b82f6 !important;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small fw-semibold text-uppercase">Топшириқлар</span>
                            <div class="avatar-text avatar-sm bg-soft-primary text-primary rounded-circle">
                                <i class="feather-check-square fs-16"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-primary mb-1">
                            {{ $completedTasksCount }} / {{ $assignedTasksCount }}
                        </h4>
                        <div class="fs-12 text-muted">
                            Бажарилган топшириқлар
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Turniket / Tizim holati -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden" 
                     style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-left: 4px solid #6366f1 !important;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small fw-semibold text-uppercase">Турникет ID</span>
                            <div class="avatar-text avatar-sm bg-soft-indigo text-indigo rounded-circle">
                                <i class="feather-user-check fs-16"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-dark mb-1 font-monospace fs-18">
                            {{ $employee->turniket_id ?? 'Боғланмаган' }}
                        </h4>
                        <div class="fs-12 text-muted">
                            Ҳолат: <span class="badge bg-soft-success text-success px-2 py-0.5">Тизимда фаол</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row g-4">
            
            <!-- Left: Profile details card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-dark text-white p-3 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 text-white fw-bold fs-14">
                            <i class="feather-user me-2 text-primary"></i> Шахсий маълумотлар
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                                <span class="text-muted fs-13"><i class="feather-user me-1 text-muted"></i> Тўлиқ исми:</span>
                                <span class="fw-semibold text-dark fs-13 text-end">{{ $user->name }}</span>
                            </li>
                            <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                                <span class="text-muted fs-13"><i class="feather-mail me-1 text-muted"></i> Электрон почта:</span>
                                <span class="fw-semibold text-dark fs-13 text-end">{{ $user->email }}</span>
                            </li>
                            <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                                <span class="text-muted fs-13"><i class="feather-shield me-1 text-muted"></i> Тизимдаги роли:</span>
                                <span class="badge bg-primary text-uppercase">{{ $user->role }}</span>
                            </li>
                            <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                                <span class="text-muted fs-13"><i class="feather-gift me-1 text-warning"></i> Туғилган куни:</span>
                                <span class="fw-bold text-dark fs-13">{{ $user->formatted_birth_date }}</span>
                            </li>
                            <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                                <span class="text-muted fs-13"><i class="feather-trending-up me-1 text-success"></i> И-индекс баҳоси:</span>
                                <span class="badge bg-soft-success text-success fw-bold fs-13 px-2 py-1">{{ $iIndexTotalScore }} балл</span>
                            </li>
                            @if(!empty($employee?->phone))
                            <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                                <span class="text-muted fs-13"><i class="feather-phone me-1 text-muted"></i> Телефон:</span>
                                <span class="fw-semibold text-dark fs-13">{{ $employee->phone }}</span>
                            </li>
                            @endif
                            @if(!empty($employee?->position))
                            <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                                <span class="text-muted fs-13"><i class="feather-briefcase me-1 text-muted"></i> Лавозими:</span>
                                <span class="fw-semibold text-dark fs-13 text-end">{{ $employee->position }}</span>
                            </li>
                            @endif
                        </ul>

                        <!-- Big Logout Button -->
                        <div class="mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-danger w-100 py-2.5 rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-2 fw-semibold" 
                                    onclick="event.preventDefault(); document.getElementById('profile-logout-form').submit();">
                                <i class="feather-log-out"></i> Тизимдан чиқиш (Logout)
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Articles & I-Index Scores Table -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white p-3 d-flex align-items-center justify-content-between border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-text avatar-sm bg-soft-success text-success rounded-circle">
                                <i class="feather-book-open fs-16"></i>
                            </div>
                            <h6 class="mb-0 fw-bold fs-15 text-dark">
                                И-индекс мақолалари ва тўпланган баҳолар
                            </h6>
                        </div>
                        <span class="badge bg-soft-success text-success fw-bold fs-12 px-2.5 py-1">
                            ЖАМИ БАҲО: {{ $iIndexTotalScore }} БАЛЛ
                        </span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="fs-12 text-uppercase text-muted">
                                        <th style="width: 50px;">#</th>
                                        <th>Мақола мавзуси</th>
                                        <th>Тоифаси</th>
                                        <th>Ҳолати</th>
                                        <th class="text-end" style="width: 140px;">Тўпланган балл</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($articles as $idx => $art)
                                        <tr>
                                            <td class="text-muted fs-12">{{ $idx + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark fs-14">{{ $art->title ?? 'Номсиз мақола' }}</div>
                                                <small class="text-muted">{{ $art->created_at ? $art->created_at->format('d.m.Y') : '-' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border fs-11">
                                                    {{ $art->category->name ?? 'Умумий' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($art->status === 'checked' || $art->status === 'tasdiqlandi')
                                                    <span class="badge bg-soft-success text-success px-2 py-1 fs-11">
                                                        <i class="feather-check-circle me-1"></i>Баҳоланган
                                                    </span>
                                                @else
                                                    <span class="badge bg-soft-warning text-warning px-2 py-1 fs-11">
                                                        <i class="feather-clock me-1"></i>Кутилмоқда
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @php
                                                    $score = $art->articleScore ? (float)$art->articleScore->total_score : 0;
                                                @endphp
                                                <span class="fw-bold text-success font-monospace fs-14">
                                                    +{{ number_format($score, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="feather-inbox fs-40 d-block mb-2 text-muted"></i>
                                                Ҳозирча илмий мақолалар киритилмаган.
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
@endsection
