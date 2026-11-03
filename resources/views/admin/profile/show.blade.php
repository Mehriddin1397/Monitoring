@extends('layouts.admin')

@section('title', 'Шахсий профиль — ' . ($user->name ?? 'Фойдаланувчи'))

@section('content')
<div class="nxl-content">

    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Шахсий профиль</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Бош саҳифа</a></li>
                <li class="breadcrumb-item">Шахсий профиль</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto">
            <div class="page-header-right-items">
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-danger d-flex align-items-center gap-2"
                            onclick="event.preventDefault(); document.getElementById('profile-logout-form').submit();">
                        <i class="feather-log-out"></i>
                        <span>Тизимдан чиқиш</span>
                    </button>
                    <form id="profile-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">

        <!-- [ Profile Top Banner Card ] start -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card stretch stretch-full border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
                            
                            <!-- Left: Avatar + Details -->
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <div class="position-relative">
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                         class="rounded-circle object-fit-cover shadow border border-3 border-light"
                                         style="width: 88px; height: 88px; background-color: #f8fafc;"
                                         onerror="this.onerror=null; this.src='{{ asset('assets/images/8214212.png') }}';">
                                    <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success p-2 border border-2 border-white" title="Тизимда фаол">
                                        <span class="visually-hidden">Фаол</span>
                                    </span>
                                </div>

                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                        <h3 class="mb-0 text-white fw-bold fs-22">{{ $user->name }}</h3>
                                        <span class="badge bg-primary text-uppercase px-2.5 py-1 fs-12 fw-semibold">
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
                                        <span><i class="feather-shield text-info me-1"></i>Шахсий профиль</span>
                                        @if(!empty($employee?->department))
                                            <span>• {{ $employee->department }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Quick actions -->
                            <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                                    <i class="feather-home me-1"></i> Бошқарув
                                </a>
                                <button type="button" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm d-flex align-items-center gap-2 fw-semibold"
                                        onclick="event.preventDefault(); document.getElementById('profile-logout-form').submit();">
                                    <i class="feather-log-out"></i> Тизимдан чиқиш
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Profile Top Banner Card ] end -->

        <!-- [ 4 KPI Stat Cards ] start -->
        <div class="row g-3 mb-4">
            
            <!-- 1. Tug'ilgan kuni -->
            <div class="col-12 col-sm-6 col-xxl-3">
                <div class="card stretch stretch-full border-0 shadow-sm border-start border-warning border-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted fs-12 text-uppercase fw-semibold">Туғилган куни</span>
                            <div class="avatar-text avatar-sm bg-soft-warning text-warning rounded-circle">
                                <i class="feather-gift fs-16"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-dark mb-1 fs-18">
                            {{ $birthdayInfo['formatted'] ?? ($employee->birth_date ?? 'Кўрсатилмаган') }}
                        </h4>
                        <div class="fs-12 text-muted">
                            @if(!empty($birthdayInfo))
                                <span class="badge bg-warning text-dark px-2 py-0.5 me-1">{{ $birthdayInfo['age'] }} ёш</span>
                                @if(!empty($birthdayInfo['is_today']))
                                    <span class="text-danger fw-bold">🎉 Бугун таваллуд куни!</span>
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
            <div class="col-12 col-sm-6 col-xxl-3">
                <div class="card stretch stretch-full border-0 shadow-sm border-start border-success border-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted fs-12 text-uppercase fw-semibold">И-индекс баҳоси</span>
                            <div class="avatar-text avatar-sm bg-soft-success text-success rounded-circle">
                                <i class="feather-trending-up fs-16"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-success mb-1 fs-18">
                            {{ $iIndexTotalScore }} <span class="fs-13 fw-normal text-muted">балл</span>
                        </h4>
                        <div class="fs-12 text-muted">
                            Жами мақолалар: <strong class="text-dark">{{ $articles->count() }} та</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Topshiriqlar -->
            <div class="col-12 col-sm-6 col-xxl-3">
                <div class="card stretch stretch-full border-0 shadow-sm border-start border-primary border-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted fs-12 text-uppercase fw-semibold">Топшириқлар</span>
                            <div class="avatar-text avatar-sm bg-soft-primary text-primary rounded-circle">
                                <i class="feather-check-square fs-16"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-primary mb-1 fs-18">
                            {{ $completedTasksCount }} / {{ $assignedTasksCount }}
                        </h4>
                        <div class="fs-12 text-muted">
                            Бажарилган топшириқлар
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Turniket ID -->
            <div class="col-12 col-sm-6 col-xxl-3">
                <div class="card stretch stretch-full border-0 shadow-sm border-start border-info border-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted fs-12 text-uppercase fw-semibold">Турникет ID</span>
                            <div class="avatar-text avatar-sm bg-soft-info text-info rounded-circle">
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
        <!-- [ 4 KPI Stat Cards ] end -->

        <!-- [ Main Two Columns Section ] start -->
        <div class="row g-4">

            <!-- Left Column: Shaxsiy ma'lumotlar -->
            <div class="col-xxl-4 col-xl-5 col-lg-12">
                <div class="card stretch stretch-full border-0 shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="feather-user text-primary me-2"></i>Шахсий маълумотлар
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-borderless table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted py-2" style="width: 40%;"><i class="feather-user me-1 text-muted"></i> Тўлиқ исми:</td>
                                    <td class="fw-semibold text-dark text-end py-2">{{ $user->name }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="text-muted py-2"><i class="feather-mail me-1 text-muted"></i> Электрон почта:</td>
                                    <td class="fw-semibold text-dark text-end py-2">{{ $user->email }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="text-muted py-2"><i class="feather-shield me-1 text-muted"></i> Тизимдаги роли:</td>
                                    <td class="text-end py-2"><span class="badge bg-primary text-uppercase">{{ $user->role }}</span></td>
                                </tr>
                                <tr class="border-top">
                                    <td class="text-muted py-2"><i class="feather-gift me-1 text-warning"></i> Туғилган куни:</td>
                                    <td class="fw-bold text-dark text-end py-2">{{ $user->formatted_birth_date }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="text-muted py-2"><i class="feather-trending-up me-1 text-success"></i> И-индекс баҳоси:</td>
                                    <td class="text-end py-2">
                                        <span class="badge bg-soft-success text-success fw-bold fs-13 px-2 py-1">{{ $iIndexTotalScore }} балл</span>
                                    </td>
                                </tr>
                                @if(!empty($employee?->phone))
                                <tr class="border-top">
                                    <td class="text-muted py-2"><i class="feather-phone me-1 text-muted"></i> Телефон:</td>
                                    <td class="fw-semibold text-dark text-end py-2">{{ $employee->phone }}</td>
                                </tr>
                                @endif
                                @if(!empty($employee?->position))
                                <tr class="border-top">
                                    <td class="text-muted py-2"><i class="feather-briefcase me-1 text-muted"></i> Лавозими:</td>
                                    <td class="fw-semibold text-dark text-end py-2">{{ $employee->position }}</td>
                                </tr>
                                @endif
                                @if(!empty($employee?->department))
                                <tr class="border-top">
                                    <td class="text-muted py-2"><i class="feather-layers me-1 text-muted"></i> Бўлим:</td>
                                    <td class="fw-semibold text-dark text-end py-2">{{ $employee->department }}</td>
                                </tr>
                                @endif
                                @if(!empty($employee?->turniket_id))
                                <tr class="border-top">
                                    <td class="text-muted py-2"><i class="feather-cpu me-1 text-muted"></i> Турникет ID:</td>
                                    <td class="fw-bold text-dark font-monospace text-end py-2">{{ $employee->turniket_id }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

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

            <!-- Right Column: Articles and I-Index Scores Table -->
            <div class="col-xxl-8 col-xl-7 col-lg-12">
                <div class="card stretch stretch-full border-0 shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="feather-award text-success me-2"></i>И-индекс мақолалари ва тўпланган баҳолар
                        </h5>
                        <span class="badge bg-soft-success text-success fw-bold fs-12 px-3 py-1.5">
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
        <!-- [ Main Two Columns Section ] end -->

    </div>
    <!-- [ Main Content ] end -->

</div>
@endsection
