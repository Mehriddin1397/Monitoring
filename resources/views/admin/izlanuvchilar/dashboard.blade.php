@extends('layouts.admin')

@section('title', 'Мустақил изланувчилар мониторинги')

@section('content')
<div class="nxl-content d-flex flex-column h-100">
    <!-- [ Page Header ] start -->
    <div class="page-header py-3 px-3 px-lg-4 shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-text avatar-lg bg-primary text-white rounded-circle shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                    <i class="feather-award fs-24"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="mb-1 text-white fw-bold fs-22">Мустақил изланувчилар мониторинги</h3>
                        @if($isOnline)
                            <span class="badge bg-success text-uppercase px-2 py-1 fs-11">
                                <span class="spinner-grow spinner-grow-sm me-1" style="width: 8px; height: 8px;" role="status"></span> ОНЛАЙН (192.168.40.104)
                            </span>
                        @else
                            <span class="badge bg-danger text-uppercase px-2 py-1 fs-11">
                                <i class="feather-alert-triangle me-1"></i> ОФФЛАЙН (192.168.40.104)
                            </span>
                        @endif
                    </div>
                    <p class="fs-14 text-white-50 mb-0">
                        «Мустақил изланувчилар ариза мониторинги» интеграциялашган тизими маълумотлари
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('admin.izlanuvchilar.researchers') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-users me-1"></i> Изланувчилар рўйхати
                </a>
                <a href="{{ route('admin.izlanuvchilar.tasks') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-check-circle me-1"></i> Кўриб чиқиш (Вазифалар)
                </a>
                <button type="button" class="btn btn-sm btn-primary" onclick="window.location.reload();">
                    <i class="feather-refresh-cw me-1"></i> Янгилаш
                </button>
            </div>
        </div>
    </div>
    <!-- [ Page Header ] end -->

    <div class="main-content p-3 p-lg-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="feather-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="feather-alert-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(!$isOnline)
            <div class="alert alert-warning d-flex align-items-center gap-3 mb-4" role="alert">
                <i class="feather-alert-circle fs-24 text-warning"></i>
                <div>
                    <strong>Алоқа ўрнатилмади!</strong> <code>http://192.168.40.104/api/v1</code> сервери вақтинча жавоб бермаяпти. Илтимос, тармоқ уланиши ва API сервери ҳолатини текширинг.
                </div>
            </div>
        @endif

        <!-- [ Overview KPI Cards ] start -->
        <div class="row g-3 mb-4">
            <!-- 1. Jami izlanuvchilar -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #2563eb !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted fw-semibold fs-13 text-uppercase">Жами изланувчилар</span>
                            <div class="avatar-text avatar-sm bg-primary-subtle text-primary rounded">
                                <i class="feather-users fs-16"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-2 text-dark">{{ $overview['izlanuvchilar']['jami'] ?? 0 }}</h2>
                        <div class="d-flex align-items-center justify-content-between fs-12 text-muted pt-2 border-top">
                            <span>Фаол: <strong class="text-success">{{ $overview['izlanuvchilar']['faol'] ?? 0 }}</strong></span>
                            <span>Ариза берган: <strong class="text-primary">{{ $overview['izlanuvchilar']['ariza_topshirganlar'] ?? 0 }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Loyihalar / Arizalar -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #10b981 !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted fw-semibold fs-13 text-uppercase">Аризалар / Лойиҳалар</span>
                            <div class="avatar-text avatar-sm bg-success-subtle text-success rounded">
                                <i class="feather-folder fs-16"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-2 text-dark">{{ $overview['loyihalar']['jami'] ?? 0 }}</h2>
                        <div class="d-flex align-items-center justify-content-between fs-12 text-muted pt-2 border-top">
                            <span>Жараёнда: <strong class="text-warning">{{ $overview['loyihalar']['jarayonda'] ?? 0 }}</strong></span>
                            <span>Якунланган: <strong class="text-success">{{ $overview['loyihalar']['yakunlangan'] ?? 0 }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Vazifalar -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #f59e0b !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted fw-semibold fs-13 text-uppercase">Кўриб чиқиш вазифалари</span>
                            <div class="avatar-text avatar-sm bg-warning-subtle text-warning rounded">
                                <i class="feather-check-square fs-16"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-2 text-dark">{{ $overview['vazifalar']['jami'] ?? 0 }}</h2>
                        <div class="d-flex align-items-center justify-content-between fs-12 text-muted pt-2 border-top">
                            <span>Кутилмоқда: <strong class="text-info">{{ $overview['vazifalar']['kutilmoqda'] ?? 0 }}</strong></span>
                            <span>Тасдиқланган: <strong class="text-success">{{ $overview['vazifalar']['tasdiqlandi'] ?? 0 }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. O'rtacha progress -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #8b5cf6 !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted fw-semibold fs-13 text-uppercase">Ўртача ўзлаштириш</span>
                            <div class="avatar-text avatar-sm bg-purple-subtle text-purple rounded">
                                <i class="feather-trending-up fs-16"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-2 text-dark">{{ $overview['loyihalar']['orta_progress_foizi'] ?? 0 }}%</h2>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $overview['loyihalar']['orta_progress_foizi'] ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Overview KPI Cards ] end -->

        <!-- [ Details Section ] start -->
        <div class="row g-4">
            <!-- Ariza turlari -->
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fw-bold fs-16">
                            <i class="feather-layers text-primary me-2"></i> Ариза турлари бўйича лойиҳалар
                        </h5>
                        <span class="badge bg-light text-dark">{{ count($overview['ariza_turlari'] ?? []) }} та тур</span>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($overview['ariza_turlari']))
                            <ul class="list-group list-group-flush">
                                @foreach($overview['ariza_turlari'] as $item)
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3 px-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text avatar-sm bg-light text-primary rounded-circle">
                                                <i class="feather-file-text"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $item['name'] }}</h6>
                                                <small class="text-muted">ID: #{{ $item['id'] }}</small>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="badge bg-primary-subtle text-primary fs-13 px-3 py-2 rounded-pill">
                                                {{ $item['projects_count'] }} та лойиҳа
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="feather-inbox fs-36 d-block mb-2 text-muted"></i>
                                Ҳозирча ариза турлари мавжуд эмас
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mas'ul xodimlar (Kotiblar) ish yuklamasi -->
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fw-bold fs-16">
                            <i class="feather-briefcase text-success me-2"></i> Масъул ходимлар (Котиблар) иш юкламаси
                        </h5>
                        <span class="badge bg-light text-dark">{{ count($workload) }} та ходим</span>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($workload))
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Масъул ходим</th>
                                            <th>Телефон</th>
                                            <th class="text-center">Бириктирилган</th>
                                            <th class="text-center">Кўриб чиқиш</th>
                                            <th class="text-center">Тасдиқланган</th>
                                            <th class="text-center pe-4">Қайтарилган</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($workload as $staff)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="fw-semibold text-dark">{{ $staff['full_name'] }}</div>
                                                    <div class="text-muted fs-11">
                                                        @foreach($staff['roles'] ?? [] as $r)
                                                            <span class="badge bg-light text-secondary border me-1">{{ $r }}</span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="tel:{{ $staff['phone'] }}" class="text-decoration-none text-muted">
                                                        <i class="feather-phone me-1"></i>{{ $staff['phone'] }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1">
                                                        {{ $staff['workload']['jami_biriktirilgan'] ?? 0 }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @php $k = $staff['workload']['ko_rib_chiqish_kutilmoqda'] ?? 0; @endphp
                                                    <span class="badge {{ $k > 0 ? 'bg-danger text-white' : 'bg-light text-muted' }} px-2 py-1">
                                                        {{ $k }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success-subtle text-success px-2 py-1">
                                                        {{ $staff['workload']['tasdiqlangan'] ?? 0 }}
                                                    </span>
                                                </td>
                                                <td class="text-center pe-4">
                                                    <span class="badge bg-danger-subtle text-danger px-2 py-1">
                                                        {{ $staff['workload']['qaytarilgan'] ?? 0 }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="feather-user-x fs-36 d-block mb-2 text-muted"></i>
                                Ходимлар юкламаси бўйича маълумот йўқ
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Details Section ] end -->

        <!-- Tezkor havolalar banner -->
        <div class="card border-0 shadow-sm mt-4" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);">
            <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-text avatar-lg bg-primary text-white rounded-circle">
                        <i class="feather-file-check fs-24"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Ҳужжатларни кўриб чиқишга ўтиш</h5>
                        <p class="text-muted mb-0 fs-13">
                            Талабгорлар томонидан юкланган диссертация, автореферат ва бошқа ҳужжатларни тасдиқлаш ёки изоҳ билан қайтариш.
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.izlanuvchilar.tasks') }}" class="btn btn-primary px-4">
                        <i class="feather-arrow-right me-1"></i> Вазифалар рўйхати
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
