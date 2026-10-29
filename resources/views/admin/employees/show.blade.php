@extends('layouts.admin')

@section('title', $employee->full_name . ' — Ходимнинг 360° досьеси')

@section('content')
<div class="nxl-content d-flex flex-column h-100">
    <!-- [ Page Header / Profile Hero ] start -->
    <div class="page-header py-4 px-3 px-lg-4 text-white shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
            
            <!-- Avatar & Main Info -->
            <div class="d-flex align-items-center gap-4 flex-wrap">
                <div class="position-relative">
                    <img src="{{ $employee->avatar_url }}" alt="{{ $employee->full_name }}" 
                         class="rounded-circle border border-3 border-white shadow-lg object-fit-cover" 
                         style="width: 100px; height: 100px; background-color: #f1f5f9;"
                         onerror="this.onerror=null; this.src='/assets/images/avatar/default.png';">
                    
                    @php
                        $stToday = $turniketProfile['status_today'] ?? null;
                        $isInside = $stToday['is_inside'] ?? false;
                        $hasArrived = $stToday['has_arrived'] ?? false;
                    @endphp

                    @if($isInside)
                        <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success p-2 border border-2 border-white" title="Ҳозир бино ичида">
                            <span class="visually-hidden">Online</span>
                        </span>
                    @elseif($hasArrived)
                        <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-info p-2 border border-2 border-white" title="Бугун келган (ҳозир ташқарида)">
                            <span class="visually-hidden">Outside</span>
                        </span>
                    @else
                        <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-secondary p-2 border border-2 border-white" title="Бинода қайд этилмаган">
                            <span class="visually-hidden">Offline</span>
                        </span>
                    @endif
                </div>

                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <h3 class="mb-0 text-white fw-bold fs-24">{{ $employee->full_name }}</h3>
                        @if($employee->turniket_id)
                            <span class="badge bg-light text-dark font-monospace fs-12 px-2 py-1">
                                <i class="feather-shield text-primary me-1"></i>ID: {{ $employee->turniket_id }}
                            </span>
                        @endif

                        @if(!empty($stToday['is_on_leave']))
                            <span class="badge bg-warning text-dark px-2 py-1 fs-12">
                                <i class="feather-calendar me-1"></i>Таътилда / Сабабли
                            </span>
                        @elseif($isInside)
                            <span class="badge bg-success px-2 py-1 fs-12">
                                <span class="pulse-dot"></span> Бино ичида
                            </span>
                        @elseif($hasArrived)
                            <span class="badge bg-info px-2 py-1 fs-12">
                                <i class="feather-check me-1"></i>Бугун келган
                            </span>
                        @else
                            <span class="badge bg-danger px-2 py-1 fs-12">
                                <i class="feather-x me-1"></i>Бугун келмаган
                            </span>
                        @endif
                    </div>

                    <p class="fs-14 text-white-50 mb-1">
                        <i class="feather-briefcase me-1"></i> {{ $employee->position ?? 'Лавозим кўрсатилмаган' }}
                        @if($user && $user->degree)
                            <span class="ms-2">| <i class="feather-award me-1"></i>{{ $user->degree }}</span>
                        @endif
                        @if($user && $user->is_scientific)
                            <span class="badge bg-soft-info text-info ms-2">Илмий ходим</span>
                        @endif
                    </p>

                    <div class="d-flex align-items-center gap-3 fs-13 text-white-50 flex-wrap">
                        @if($employee->phone)
                            <span><i class="feather-phone me-1"></i>{{ $employee->phone }}</span>
                        @endif
                        @if($user && $user->email)
                            <span><i class="feather-mail me-1"></i>{{ $user->email }}</span>
                        @endif
                        @if(!empty($birthdayInfo))
                            <span><i class="feather-gift text-warning me-1"></i>{{ $birthdayInfo['formatted'] }} ({{ $birthdayInfo['age'] }} ёш)</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                @if($employee->turniket_id)
                    <button type="button" class="btn btn-sm btn-warning text-dark fw-semibold" 
                            onclick="openExcuseModal('{{ $employee->turniket_id }}', 'late_excuse')">
                        <i class="feather-check-square me-1"></i> Сабаб / Таътил киритиш
                    </button>
                @endif
                <a href="{{ route('monitoring.umumiy') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-arrow-left me-1"></i> Умумий рўйхат
                </a>
            </div>

        </div>
    </div>
    <!-- [ Page Header / Profile Hero ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content p-3 p-lg-4">
        
        <!-- KPI Cards Row -->
        <div class="row g-3 mb-4">
            <!-- 1. Tasks Card -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted fs-11 text-uppercase fw-semibold">Топшириқлар</span>
                            <i class="feather-check-square text-primary fs-16"></i>
                        </div>
                        <h4 class="fw-bold mb-1 fs-20 text-dark">
                            {{ $taskStats['completed'] }} <span class="fs-13 text-muted fw-normal">/ {{ $taskStats['total'] }}</span>
                        </h4>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $taskStats['pct'] }}%"></div>
                        </div>
                        <span class="fs-11 text-muted mt-1 d-block">{{ $taskStats['pct'] }}% бажарилди</span>
                    </div>
                </div>
            </div>

            <!-- 2. I-index Card -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted fs-11 text-uppercase fw-semibold">И-индекс бали</span>
                            <i class="feather-trending-up text-success fs-16"></i>
                        </div>
                        <h4 class="fw-bold mb-1 fs-20 text-success">
                            {{ $articleStats['total_score'] }} <span class="fs-12 text-muted fw-normal">балл</span>
                        </h4>
                        <span class="fs-12 text-muted">
                            <i class="feather-book-open me-1"></i>{{ $articleStats['total'] }} та мақола
                        </span>
                    </div>
                </div>
            </div>

            <!-- 3. Attendance Today -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 border-start border-info border-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted fs-11 text-uppercase fw-semibold">Давомат (Бугун)</span>
                            <i class="feather-user-check text-info fs-16"></i>
                        </div>
                        @php
                            $fEntry = !empty($stToday['first_entry']) ? \Carbon\Carbon::parse($stToday['first_entry'])->timezone('Asia/Tashkent')->format('H:i') : null;
                        @endphp
                        @if($fEntry)
                            <h4 class="fw-bold mb-1 fs-20 text-info font-monospace">{{ $fEntry }}</h4>
                            <span class="fs-12 {{ !empty($stToday['is_late']) ? 'text-warning' : 'text-success' }}">
                                {{ !empty($stToday['is_late']) ? '+' . ($stToday['late_minutes'] ?? 0) . ' дақиқа кечиккан' : 'Ўз вақтида келган' }}
                            </span>
                        @else
                            <h4 class="fw-bold mb-1 fs-20 text-muted font-monospace">-</h4>
                            <span class="fs-12 text-muted">Қайд этилмаган</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 4. Projects -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 border-start border-indigo border-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted fs-11 text-uppercase fw-semibold">Лойиҳалар</span>
                            <i class="feather-folder text-indigo fs-16"></i>
                        </div>
                        <h4 class="fw-bold mb-1 fs-20 text-dark">{{ $projects->count() }}</h4>
                        <span class="fs-12 text-muted">Илмий ва амалий</span>
                    </div>
                </div>
            </div>

            <!-- 5. Creative Works -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted fs-11 text-uppercase fw-semibold">Ижод</span>
                            <i class="feather-feather text-warning fs-16"></i>
                        </div>
                        <h4 class="fw-bold mb-1 fs-20 text-warning">{{ $works->count() }}</h4>
                        <span class="fs-12 text-muted">Шеър ва мақолалар</span>
                    </div>
                </div>
            </div>

            <!-- 6. Birthday Info -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 border-start border-danger border-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted fs-11 text-uppercase fw-semibold">Туғилган кун</span>
                            <i class="feather-gift text-danger fs-16"></i>
                        </div>
                        @if($birthdayInfo)
                            <h4 class="fw-bold mb-1 fs-20 text-dark">{{ $birthdayInfo['age'] }} <span class="fs-13 text-muted">ёш</span></h4>
                            <span class="fs-12 text-muted">
                                @if($birthdayInfo['is_today'])
                                    <strong class="text-danger">🎉 Бугун туғилган куни!</strong>
                                @else
                                    {{ $birthdayInfo['days_left'] }} кун қолди
                                @endif
                            </span>
                        @else
                            <h4 class="fw-bold mb-1 fs-20 text-muted">-</h4>
                            <span class="fs-12 text-muted">Киритилмаган</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Details Tabs -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-3">
                <ul class="nav nav-pills card-header-pills gap-2" id="employeeTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active px-3 py-2 fw-semibold" id="tab-tasks-btn" data-bs-toggle="tab" data-bs-target="#tab-tasks" type="button" role="tab">
                            <i class="feather-check-square me-1"></i> Топшириқлар
                            <span class="badge bg-primary ms-1">{{ $tasks->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link px-3 py-2 fw-semibold" id="tab-iindex-btn" data-bs-toggle="tab" data-bs-target="#tab-iindex" type="button" role="tab">
                            <i class="feather-trending-up me-1 text-success"></i> И-индекс
                            <span class="badge bg-success ms-1">{{ $articles->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link px-3 py-2 fw-semibold" id="tab-attendance-btn" data-bs-toggle="tab" data-bs-target="#tab-attendance" type="button" role="tab">
                            <i class="feather-shield me-1 text-info"></i> Давомат (Турникет)
                            <span class="badge bg-info ms-1">{{ count($turniketLogs) }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link px-3 py-2 fw-semibold" id="tab-projects-btn" data-bs-toggle="tab" data-bs-target="#tab-projects" type="button" role="tab">
                            <i class="feather-folder me-1 text-indigo"></i> Лойиҳалар
                            <span class="badge bg-indigo ms-1">{{ $projects->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link px-3 py-2 fw-semibold" id="tab-works-btn" data-bs-toggle="tab" data-bs-target="#tab-works" type="button" role="tab">
                            <i class="feather-feather me-1 text-warning"></i> Ижод
                            <span class="badge bg-warning ms-1">{{ $works->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link px-3 py-2 fw-semibold" id="tab-info-btn" data-bs-toggle="tab" data-bs-target="#tab-info" type="button" role="tab">
                            <i class="feather-user me-1 text-secondary"></i> Шахсий маълумотлар
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-0">
                <div class="tab-content" id="employeeTabsContent">

                    <!-- TAB 1: TASKS -->
                    <div class="tab-pane fade show active p-3" id="tab-tasks" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="fs-12 text-uppercase text-muted">
                                        <th style="width: 50px;">#</th>
                                        <th>Топшириқ номи</th>
                                        <th>Муддати</th>
                                        <th>Ҳолати</th>
                                        <th>Ҳужжат</th>
                                        <th>Бириктирилган файллар</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tasks as $idx => $task)
                                        <tr>
                                            <td class="text-muted fs-12">{{ $idx + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark fs-14">{{ $task->title }}</div>
                                                <small class="text-muted">Тур: {{ $task->task_type ?? 'Умумий' }}</small>
                                            </td>
                                            <td>
                                                <div class="font-monospace fs-13">
                                                    {{ $task->start_date }} — <strong class="text-danger">{{ $task->end_date }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $st = $task->status;
                                                    $stBadge = match($st) {
                                                        'bajarildi'    => 'bg-success',
                                                        'bajarilmoqda' => 'bg-info',
                                                        'yangi'        => 'bg-primary',
                                                        'uzaytirildi'  => 'bg-warning text-dark',
                                                        'bajarilmadi'  => 'bg-danger',
                                                        default        => 'bg-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $stBadge }} px-2 py-1 fs-11">
                                                    {{ ucfirst($st) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($task->document)
                                                    <a href="{{ asset($task->document) }}" target="_blank" class="btn btn-sm btn-light border py-1 px-2 fs-12">
                                                        <i class="feather-file-text me-1"></i> Ҳужжат
                                                    </a>
                                                @else
                                                    <span class="text-muted fs-12">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $taskFiles = $task->files->where('user_id', $user->id ?? 0);
                                                @endphp
                                                @if($taskFiles->count() > 0)
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="feather-paperclip me-1"></i>{{ $taskFiles->count() }} та файл
                                                    </span>
                                                @else
                                                    <span class="text-muted fs-12">Юкланмаган</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="feather-inbox fs-32 d-block mb-2 text-muted"></i>
                                                Ушбу ходимга бириктирилган топшириқлар мавжуд эмас.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 2: I-INDEX -->
                    <div class="tab-pane fade p-3" id="tab-iindex" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="fs-12 text-uppercase text-muted">
                                        <th style="width: 50px;">#</th>
                                        <th>Мақола номи</th>
                                        <th>Нашр этилган жой</th>
                                        <th>Баҳо (Таъриф / Тасниф / Таклиф)</th>
                                        <th>Умумий балл</th>
                                        <th>Ҳолати</th>
                                        <th>Файл</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($articles as $idx => $art)
                                        <tr>
                                            <td class="text-muted fs-12">{{ $idx + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark fs-14">{!! strip_tags($art->title) !!}</div>
                                                <small class="text-muted">{{ $art->created_at->format('d.m.Y') }}</small>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-13">{{ $art->publish_place ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @if($art->articleScore)
                                                    <span class="font-monospace fs-12 text-dark">
                                                        {{ $art->articleScore->definitions ?? 0 }} / 
                                                        {{ $art->articleScore->classifications ?? 0 }} / 
                                                        {{ $art->articleScore->suggestions ?? 0 }}
                                                    </span>
                                                @else
                                                    <span class="text-muted fs-12">Баҳоланмаган</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($art->articleScore && $art->articleScore->total_score > 0)
                                                    <span class="badge bg-success fs-13 px-2 py-1 font-monospace">
                                                        ⭐ {{ $art->articleScore->total_score }} балл
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-muted">0 балл</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($art->status === 'tasdiqlandi')
                                                    <span class="badge bg-soft-success text-success px-2 py-1">Тасдиқланган</span>
                                                @elseif($art->status === 'rad_etildi')
                                                    <span class="badge bg-soft-danger text-danger px-2 py-1">Рад этилган</span>
                                                @else
                                                    <span class="badge bg-soft-warning text-warning px-2 py-1">Кутилмоқда</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($art->article_pdf)
                                                    <a href="{{ asset($art->article_pdf) }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2 fs-12">
                                                        <i class="feather-file me-1"></i> PDF
                                                    </a>
                                                @else
                                                    <span class="text-muted fs-12">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="feather-book-open fs-32 d-block mb-2 text-muted"></i>
                                                Ушбу ходим томонидан ҳали мақолалар юкланмаган.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 3: ATTENDANCE -->
                    <div class="tab-pane fade p-3" id="tab-attendance" role="tabpanel">
                        <!-- Today breakdown -->
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <div class="bg-light p-3 rounded">
                                    <div class="text-muted fs-12">Биринчи кириш:</div>
                                    <div class="fw-bold font-monospace fs-16 text-success">
                                        {{ !empty($stToday['first_entry']) ? \Carbon\Carbon::parse($stToday['first_entry'])->timezone('Asia/Tashkent')->format('H:i:s (d.m.Y)') : 'Қайд этилмаган' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="bg-light p-3 rounded">
                                    <div class="text-muted fs-12">Охирги чиқиш:</div>
                                    <div class="fw-bold font-monospace fs-16 text-primary">
                                        {{ !empty($stToday['last_exit']) ? \Carbon\Carbon::parse($stToday['last_exit'])->timezone('Asia/Tashkent')->format('H:i:s (d.m.Y)') : 'Қайд этилмаган' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="bg-light p-3 rounded">
                                    <div class="text-muted fs-12">Ҳолати:</div>
                                    <div class="fw-bold fs-16">
                                        @if($isInside)
                                            <span class="text-success"><span class="pulse-dot"></span> Бино ичида</span>
                                        @elseif($hasArrived)
                                            <span class="text-info"><i class="feather-check me-1"></i>Бинодан ташқарида</span>
                                        @else
                                            <span class="text-danger"><i class="feather-x me-1"></i>Келмаган</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Turniket Logs Table -->
                        <h6 class="fw-bold text-dark fs-14 mb-2">Сўнгги ўтишлар тарихи:</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="fs-12 text-uppercase text-muted">
                                        <th style="width: 50px;">#</th>
                                        <th>Ўтиш вақти</th>
                                        <th>Йўналиш</th>
                                        <th>Турникет</th>
                                        <th>Усул</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($turniketLogs as $idx => $tLog)
                                        <tr>
                                            <td class="text-muted fs-12">{{ $idx + 1 }}</td>
                                            <td class="font-monospace fs-13 fw-semibold text-dark">
                                                {{ !empty($tLog['event_time']) ? \Carbon\Carbon::parse($tLog['event_time'])->timezone('Asia/Tashkent')->format('H:i:s (d.m.Y)') : '-' }}
                                            </td>
                                            <td>
                                                @if(($tLog['direction'] ?? '') === 'entry')
                                                    <span class="badge bg-success px-2 py-1 fs-11">
                                                        <i class="feather-arrow-down-right me-1"></i>Кириш
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark px-2 py-1 fs-11">
                                                        <i class="feather-arrow-up-right me-1"></i>Чиқиш
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-muted fs-13">{{ $tLog['device_name'] ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-muted border font-monospace fs-11">
                                                    {{ strtoupper($tLog['verify_mode'] ?? 'FACE') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                Турникетдан ўтиш тарихи топилмади.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 4: PROJECTS -->
                    <div class="tab-pane fade p-3" id="tab-projects" role="tabpanel">
                        <div class="row g-3">
                            @forelse($projects as $proj)
                                <div class="col-12 col-md-6">
                                    <div class="card border shadow-none p-3 h-100">
                                        <h6 class="fw-bold text-dark fs-15 mb-2">{!! strip_tags($proj->name) !!}</h6>
                                        <div class="fs-13 text-muted mb-2">
                                            <strong>Манба:</strong> {{ $proj->manba ?? '-' }}
                                        </div>
                                        <div class="fs-13 text-muted mb-3">
                                            <strong>Изоҳ:</strong> {{ $proj->izoh ?? '-' }}
                                        </div>
                                        <div class="d-flex gap-2">
                                            @if($proj->file_buyruq)
                                                <a href="{{ asset($proj->file_buyruq) }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2 fs-12">
                                                    <i class="feather-file-text me-1"></i> Буйруқ файли
                                                </a>
                                            @endif
                                            @if($proj->file_qushimcha)
                                                <a href="{{ asset($proj->file_qushimcha) }}" target="_blank" class="btn btn-sm btn-outline-secondary py-1 px-2 fs-12">
                                                    <i class="feather-paperclip me-1"></i> Қўшимча файл
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4 text-muted">
                                    <i class="feather-folder fs-32 d-block mb-2 text-muted"></i>
                                    Ходимга бириктирилган лойиҳалар мавжуд эмас.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- TAB 5: WORKS -->
                    <div class="tab-pane fade p-3" id="tab-works" role="tabpanel">
                        <div class="row g-3">
                            @forelse($works as $work)
                                <div class="col-12 col-md-6">
                                    <div class="card border shadow-none p-3 h-100">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-soft-warning text-warning text-uppercase fs-11">
                                                {{ $work->type ?? 'Ижод' }}
                                            </span>
                                            <small class="text-muted">{{ $work->created_at->format('d.m.Y') }}</small>
                                        </div>
                                        <h5 class="fw-bold text-dark fs-16 mb-2">{{ $work->title }}</h5>
                                        <p class="fs-13 text-muted mb-0">{!! nl2br(e($work->excerpt ?? \Illuminate\Support\Str::limit($work->content, 180))) !!}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4 text-muted">
                                    <i class="feather-feather fs-32 d-block mb-2 text-muted"></i>
                                    Ушбу ходимнинг ижодий ишлари киритилмаган.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- TAB 6: PERSONAL INFO -->
                    <div class="tab-pane fade p-4" id="tab-info" role="tabpanel">
                        <div class="row g-3" style="max-width: 700px;">
                            <div class="col-6 text-muted fs-14">Тўлиқ Ф.И.Ш:</div>
                            <div class="col-6 fw-bold text-dark fs-14">{{ $employee->full_name }}</div>

                            <div class="col-6 text-muted fs-14">Лавозими:</div>
                            <div class="col-6 text-dark fs-14">{{ $employee->position ?? '-' }}</div>

                            <div class="col-6 text-muted fs-14">Илмий даражаси / Унвони:</div>
                            <div class="col-6 text-dark fs-14">{{ $user->degree ?? '-' }}</div>

                            <div class="col-6 text-muted fs-14">Телефон рақами:</div>
                            <div class="col-6 font-monospace text-dark fs-14">{{ $employee->phone ?? '-' }}</div>

                            <div class="col-6 text-muted fs-14">Электрон почта:</div>
                            <div class="col-6 text-dark fs-14">{{ $user->email ?? '-' }}</div>

                            <div class="col-6 text-muted fs-14">Туғилган санаси:</div>
                            <div class="col-6 font-monospace text-dark fs-14">
                                {{ $birthdayInfo['formatted'] ?? 'Киритилмаган' }}
                            </div>

                            <div class="col-6 text-muted fs-14">Жинси:</div>
                            <div class="col-6 text-dark fs-14">
                                {{ $employee->gender === 'female' ? 'Аёл' : 'Эркак' }}
                            </div>

                            <div class="col-6 text-muted fs-14">Турникет ID рақами:</div>
                            <div class="col-6 font-monospace text-dark fs-14">
                                {{ $employee->turniket_id ?? 'Боғланмаган' }}
                            </div>

                            @if($employee->custom_wish)
                                <div class="col-12 mt-3">
                                    <div class="alert alert-light border p-3">
                                        <div class="fw-bold text-dark mb-1">🎉 Табрик тилаги:</div>
                                        <div class="fs-13 text-muted">{!! nl2br(e($employee->custom_wish)) !!}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- ======================================================== -->
<!-- MODAL: Сабаб / Таътил киритиш                            -->
<!-- ======================================================== -->
<div id="excuseCustomOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999999; align-items:center; justify-content:center;">
    <div class="card border-0 shadow-lg" style="width: 540px; max-width: 95%; max-height: 90vh; overflow-y: auto;">
        <div class="card-header bg-dark text-white p-3 d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0 fs-16 text-white fw-bold">
                <i class="feather-check-square me-2 text-warning"></i> Сабаб / Таътил киритиш
            </h5>
            <button type="button" class="btn-close btn-close-white" onclick="closeExcuseModal()"></button>
        </div>
        <form action="{{ route('admin.turniket.leaves.store') }}" method="POST">
            @csrf
            <div class="card-body p-4">
                <input type="hidden" name="employee_no" id="modal_employee_no" value="{{ $employee->turniket_id }}">

                <div class="mb-3">
                    <label class="form-label fs-13 fw-semibold text-muted">Ходим:</label>
                    <div class="fw-bold text-dark fs-15">{{ $employee->full_name }} (ID: {{ $employee->turniket_id }})</div>
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

<style>
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

<script>
function openExcuseModal(employeeNo, type) {
    const overlay = document.getElementById('excuseCustomOverlay');
    if (!overlay) return;
    overlay.style.display = 'flex';
}
function closeExcuseModal() {
    const overlay = document.getElementById('excuseCustomOverlay');
    if (overlay) overlay.style.display = 'none';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeExcuseModal();
});
</script>
@endsection
