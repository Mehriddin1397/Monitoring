@extends('layouts.admin')

@section('title', 'Рақамли Мониторинг ва Ситуацион Бошқарув Маркази')

@section('content')
<div class="nxl-content d-flex flex-column h-100">

    <!-- ==================== [ CREATIVE EXECUTIVE HEADER ] ==================== -->
    <div class="monitoring-header p-4 position-relative overflow-hidden" 
         style="background: linear-gradient(135deg, #0b132b 0%, #1c2541 45%, #2a3458 100%); border-bottom: 3px solid #4f46e5; box-shadow: 0 10px 30px rgba(0,0,0,0.25);">
        
        <!-- Subtle background glow -->
        <div class="position-absolute" style="top: -60px; right: 10%; width: 280px; height: 280px; background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, transparent 70%); pointer-events: none;"></div>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative" style="z-index: 2;">
            
            <!-- Left: Creative Logo & Branding -->
            <div class="d-flex align-items-center gap-3">
                <div class="creative-logo-box d-flex align-items-center justify-content-center rounded-4 shadow-lg"
                     style="width: 58px; height: 58px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border: 2px solid rgba(255,255,255,0.2);">
                    <!-- Creative Monitoring SVG Logo -->
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L3 7v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-9-5z"/>
                        <circle cx="12" cy="11" r="3"/>
                        <path d="M12 14v3"/>
                        <path d="M9 17h6"/>
                    </svg>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h4 class="text-white fw-bold mb-0 letter-spacing-1">
                            ЯГОНА МОНИТОРИНГ ВА СИТУАЦИОН МАРКАЗ
                        </h4>
                        <span class="badge rounded-pill px-2.5 py-1 small fw-bold d-inline-flex align-items-center"
                              style="background: rgba(34, 197, 94, 0.25); color: #4ade80 !important; border: 1.5px solid #22c55e; letter-spacing: 0.5px; box-shadow: 0 0 10px rgba(74, 222, 128, 0.35);">
                            <span class="live-dot me-1.5" style="background-color: #4ade80; box-shadow: 0 0 8px #4ade80;"></span> ОНЛАЙН
                        </span>
                    </div>
                    <p class="mb-0 small" style="color: #cbd5e1;">
                        Турникет давомати, Топшириқлар ижроси, И-индекс, Лойиҳалар ва ходимларнинг шахсий 360° досьелари
                    </p>
                </div>
            </div>

            <!-- Right: Real-time clock & Action buttons -->
            <div class="d-flex flex-wrap align-items-center gap-2">
                <!-- Live Tashkent Clock -->
                <div class="px-3 py-2 rounded-pill d-flex align-items-center gap-2 shadow-sm"
                     style="background: rgba(15, 23, 42, 0.7); border: 1.5px solid rgba(56, 189, 248, 0.45); box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                    <i class="feather-clock" style="color: #38bdf8; font-size: 15px;"></i>
                    <span id="liveClock" class="fw-black font-monospace fs-14" style="color: #ffffff !important; letter-spacing: 1px;">--:--:--</span>
                    <span class="small fw-semibold" style="color: #7dd3fc !important;">| Asia/Tashkent</span>
                </div>

                <!-- Toggle Charts Button -->
                <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3 shadow-sm d-flex align-items-center gap-1" onclick="toggleChartsSection()">
                    <i class="feather-pie-chart text-warning"></i>
                    <span id="toggleChartText">📊 Диаграммалар</span>
                </button>

                <!-- Sync Turniket Photos -->
                <button type="button" id="btnSyncTurniket" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm d-flex align-items-center gap-1 fw-semibold" onclick="syncTurniketPhotos()">
                    <i class="feather-refresh-cw"></i> 🔄 Турникет расмларини янгилаш
                </button>

                <!-- Print -->
                <button type="button" onclick="window.print()" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm" title="Чоп этиш">
                    <i class="feather-printer"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- ==================== [ MAIN BODY ] ==================== -->
    <div class="main-content p-3 p-md-4">

        <!-- ==================== [ 4 CREATIVE KPI SUMMARY CARDS ] ==================== -->
        <div class="row g-3 mb-4">
            <!-- 1. Total Employees -->
            <div class="col-xl-3 col-sm-6">
                <div class="card stat-card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden" 
                     style="background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%); border-top: 4px solid #4f46e5 !important;">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="kpi-label d-block mb-1">Жами ходимлар</span>
                                <h2 class="fw-black text-dark mb-1 mt-1">{{ $summary['total_employees'] ?? count($xodimlar) }} <span class="fs-6 fw-normal text-muted">нафар</span></h2>
                                <span class="badge bg-indigo-subtle text-indigo rounded-pill px-2 py-1 small fw-semibold">
                                    🏢 {{ count($summary['departments'] ?? []) }} та бўлим бўйича
                                </span>
                            </div>
                            <div class="stat-icon-wrap rounded-4 d-flex align-items-center justify-content-center"
                                 style="width: 56px; height: 56px; background: rgba(79, 70, 229, 0.12); color: #4f46e5;">
                                <i class="feather-users fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Attendance Today -->
            <div class="col-xl-3 col-sm-6">
                <div class="card stat-card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden" 
                     style="background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%); border-top: 4px solid #10b981 !important;">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="kpi-label d-block mb-1">Бугунги давомат</span>
                                <div class="d-flex align-items-baseline gap-2 mt-1 mb-1">
                                    <h2 class="fw-black text-success mb-0">{{ $summary['inside_count'] ?? 0 }}</h2>
                                    <span class="text-secondary small fw-semibold">бинода ҳозир</span>
                                </div>
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge status-badge-inside" title="Бинода ҳозир">
                                        🟢 {{ $summary['inside_count'] ?? 0 }} бинода
                                    </span>
                                    <span class="badge status-badge-late" title="Кечикканлар">
                                        🟠 {{ $summary['late_count'] ?? 0 }} кечиккан
                                    </span>
                                    <span class="badge status-badge-absent" title="Келмаганлар">
                                        🔴 {{ $summary['absent_count'] ?? 0 }} келмаган
                                    </span>
                                </div>
                            </div>
                            <div class="stat-icon-wrap rounded-4 d-flex align-items-center justify-content-center"
                                 style="width: 56px; height: 56px; background: rgba(16, 185, 129, 0.12); color: #10b981;">
                                <i class="feather-check-circle fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Tasks Execution -->
            <div class="col-xl-3 col-sm-6">
                <div class="card stat-card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden" 
                     style="background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%); border-top: 4px solid #8b5cf6 !important;">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="kpi-label d-block mb-1">Топшириқлар ижроси</span>
                                <div class="d-flex align-items-baseline gap-2 mt-1 mb-1">
                                    <h2 class="fw-black text-purple mb-0">{{ $summary['total'] ?? 0 }}</h2>
                                    <span class="badge bg-purple-subtle text-purple border border-purple-subtle fw-bold">{{ $summary['percent'] ?? 0 }}% бажарилди</span>
                                </div>
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge status-badge-inside">✅ {{ $summary['completed'] ?? 0 }} бажарилди</span>
                                    <span class="badge badge-blue-crisp">⏳ {{ $summary['in_process'] ?? 0 }} жараёнда</span>
                                    <span class="badge status-badge-absent">⚠️ {{ $summary['not_completed'] ?? 0 }} ўтган</span>
                                </div>
                            </div>
                            <div class="stat-icon-wrap rounded-4 d-flex align-items-center justify-content-center"
                                 style="width: 56px; height: 56px; background: rgba(139, 92, 246, 0.12); color: #8b5cf6;">
                                <i class="feather-check-square fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. I-index & Scientific Potential -->
            <div class="col-xl-3 col-sm-6">
                <div class="card stat-card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden" 
                     style="background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%); border-top: 4px solid #f59e0b !important;">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="kpi-label d-block mb-1">И-индекс ва Лойиҳалар</span>
                                <div class="d-flex align-items-baseline gap-2 mt-1 mb-1">
                                    <h2 class="fw-black text-amber-dark mb-0">{{ number_format($summary['total_score'] ?? 0, 1) }}</h2>
                                    <span class="text-secondary small fw-semibold">жами балл</span>
                                </div>
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge badge-amber-crisp">📄 {{ $summary['total_articles'] ?? 0 }} мақола</span>
                                    <span class="badge badge-blue-crisp">🚀 {{ $summary['total_projects'] ?? 0 }} лойиҳа</span>
                                    <span class="badge badge-sky-crisp">✍️ {{ $summary['total_works'] ?? 0 }} ижод</span>
                                </div>
                            </div>
                            <div class="stat-icon-wrap rounded-4 d-flex align-items-center justify-content-center"
                                 style="width: 56px; height: 56px; background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                                <i class="feather-award fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== [ CREATIVE INTERACTIVE CHARTS SECTION ] ==================== -->
        <div id="creativeChartsSection" class="mb-4" style="display: block;">
            <div class="row g-3">
                <!-- Chart 1: Attendance Donut -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
                            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="feather-pie-chart text-success"></i> Бугунги давомат таҳлили
                            </h6>
                            <span class="badge bg-light text-muted border">Турникет</span>
                        </div>
                        <div class="card-body p-3">
                            <div id="attendanceDonutChart" style="min-height: 250px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Chart 2: Tasks Radial Progress -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
                            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="feather-activity text-purple"></i> Топшириқлар ижро кўрсаткичи
                            </h6>
                            <span class="badge bg-light text-muted border">Интизом</span>
                        </div>
                        <div class="card-body p-3">
                            <div id="tasksRadialChart" style="min-height: 250px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Chart 3: Top Departments Bar -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
                            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="feather-bar-chart-2 text-primary"></i> Бўлимлар бўйича топшириқлар
                            </h6>
                            <span class="badge bg-light text-muted border">Топ бўлимлар</span>
                        </div>
                        <div class="card-body p-3">
                            <div id="departmentsBarChart" style="min-height: 250px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== [ SMART CONTROL & FILTER BAR ] ==================== -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    
                    <!-- Search Input -->
                    <div class="flex-grow-1" style="min-width: 250px; max-width: 400px;">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-pill text-muted ps-3">
                                <i class="feather-search"></i>
                            </span>
                            <input type="text" id="employeeSearchInput" class="form-control border-start-0 bg-light rounded-end-pill py-2" placeholder="Ф.И.Ш, лавозим ёки ID бўйича қидириш...">
                        </div>
                    </div>

                    <!-- Department Select -->
                    <div style="min-width: 200px;">
                        <select id="departmentFilter" class="form-select bg-light rounded-pill py-2">
                            <option value="">🏢 Барча бўлимлар</option>
                            @foreach($summary['departments'] ?? [] as $dep)
                                @if(!empty($dep))
                                    <option value="{{ $dep }}">{{ $dep }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- View Switcher (Cards vs Table) -->
                    <div class="btn-group p-1 bg-light rounded-pill shadow-sm" role="group">
                        <button type="button" id="btnViewCards" class="btn btn-sm rounded-pill px-3 active fw-semibold" onclick="switchView('cards')">
                            <i class="feather-grid me-1"></i> Карточкалар
                        </button>
                        <button type="button" id="btnViewTable" class="btn btn-sm rounded-pill px-3 text-muted fw-semibold" onclick="switchView('table')">
                            <i class="feather-list me-1"></i> Жадвал
                        </button>
                    </div>

                    <!-- Counter -->
                    <div class="text-muted small">
                        Кўрсатилмоқда: <strong id="visibleCount" class="text-dark fs-6">{{ count($xodimlar) }}</strong> нафар
                    </div>
                </div>

                <!-- Quick Filter Chips -->
                <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-3 border-top">
                    <span class="text-muted small fw-bold me-1">Тезкор фильтр:</span>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill filter-chip active" data-filter="" onclick="setQuickFilter('')">
                        Барчаси ({{ count($xodimlar) }})
                    </button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill filter-chip" data-filter="inside" onclick="setQuickFilter('inside')">
                        🟢 Бинода ҳозир ({{ $summary['inside_count'] ?? 0 }})
                    </button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill filter-chip" data-filter="late" onclick="setQuickFilter('late')">
                        🟠 Кечикканлар ({{ $summary['late_count'] ?? 0 }})
                    </button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill filter-chip" data-filter="excused" onclick="setQuickFilter('excused')">
                        🟡 Сабабли / Таътил ({{ $summary['excused_count'] ?? 0 }})
                    </button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill filter-chip" data-filter="absent" onclick="setQuickFilter('absent')">
                        🔴 Келмаганлар ({{ $summary['absent_count'] ?? 0 }})
                    </button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill filter-chip" data-filter="birthday" onclick="setQuickFilter('birthday')">
                        🎂 Яқин туғилган кунлар
                    </button>
                </div>
            </div>
        </div>

        <!-- ==================== [ VIEW 1: CREATIVE PROFILE CARDS (GRID VIEW) ] ==================== -->
        <div id="viewModeCards">
            <div class="row g-3 row-cols-1 row-cols-md-2 row-cols-xl-3 row-cols-xxl-4" id="cardsGridContainer">
                @forelse($xodimlar as $index => $item)
                    @php
                        $empId = $item['id'] ?? null;
                        $att = $item['attendance'] ?? [];
                        $tasks = $item['tasks'] ?? [];
                        $iIndex = $item['i_index'] ?? [];
                        $bday = $item['birthday'] ?? null;
                        $isInside = $att['is_inside'] ?? false;
                        $isLate = $att['is_late'] ?? false;
                        $isArrived = $att['is_arrived'] ?? false;
                        $hasExcuse = $att['has_excuse'] ?? false;
                        $isAbsent = $att['is_absent'] ?? false;
                    @endphp
                    <div class="col employee-card-wrapper"
                         data-name="{{ mb_strtolower($item['name']) }}"
                         data-department="{{ $item['department'] }}"
                         data-attendance="{{ $att['status'] ?? 'unknown' }}"
                         data-has-birthday="{{ (!empty($bday) && ($bday['days_until'] ?? 999) <= 30) ? '1' : '0' }}"
                         data-turniket-id="{{ $item['turniket_id'] ?? '' }}">
                        
                        <div class="card employee-card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden">
                            <!-- Card Header Banner -->
                            <div class="card-banner px-3 py-2 d-flex align-items-center justify-content-between"
                                 style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <span class="badge font-monospace px-2.5 py-1 rounded-pill shadow-sm" 
                                      style="font-size: 12px; font-weight: 800; background-color: #ffffff !important; color: #0f172a !important; border: 1px solid #ffffff; letter-spacing: 0.5px;">
                                    #{{ $index + 1 }} {{ !empty($item['turniket_id']) ? '• ID: ' . $item['turniket_id'] : '' }}
                                </span>
                                <span class="small text-truncate fw-semibold" style="max-width: 145px; color: #ffffff !important; text-shadow: 0 1px 2px rgba(0,0,0,0.5);" title="{{ $item['department'] }}">
                                    {{ $item['department'] }}
                                </span>
                            </div>

                            <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                                <!-- Avatar & Status -->
                                <div>
                                    <div class="position-relative d-inline-block mt-1 mb-2">
                                        <img src="{{ $item['avatar'] }}" 
                                             alt="{{ $item['name'] }}" 
                                             class="rounded-circle shadow border border-3 {{ $isInside ? 'border-success' : ($isLate ? 'border-warning' : ($isArrived ? 'border-info' : 'border-light')) }}"
                                             style="width: 72px; height: 72px; object-fit: cover;"
                                             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 128 128\'%3E%3Cdefs%3E%3ClinearGradient id=\'g\' x1=\'0%25\' y1=\'0%25\' x2=\'100%25\' y2=\'100%25\'%3E%3Cstop offset=\'0%25\' stop-color=\'%234f46e5\'/%3E%3Cstop offset=\'100%25\' stop-color=\'%237c3aed\'/%3E%3C/defs%3E%3Crect width=\'128\' height=\'128\' rx=\'64\' fill=\'url(%23g)\'/%3E%3Cpath d=\'M64 62a22 22 0 1 0 0-44 22 22 0 0 0 0 44zm0 12c-23 0-42 15-42 34v6h84v-6c0-19-19-34-42-34z\' fill=\'%23ffffff\' opacity=\'0.9\'/%3E%3C/svg%3E';">
                                        
                                        <!-- Glowing live attendance indicator -->
                                        @if($isInside)
                                            <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle" style="width: 16px; height: 16px;" title="Бинода ҳозир"></span>
                                        @elseif($isArrived)
                                            <span class="position-absolute bottom-0 end-0 bg-info border border-2 border-white rounded-circle" style="width: 16px; height: 16px;" title="Келган"></span>
                                        @endif
                                    </div>

                                    <!-- Full Name -->
                                    <h6 class="fw-bold text-dark mb-1">
                                        <a href="{{ $empId ? route('employees.show', $empId) : '#' }}" class="text-dark text-decoration-none hover-primary">
                                            {{ $item['name'] }}
                                        </a>
                                    </h6>

                                    <!-- Position & Rank -->
                                    <p class="text-secondary small mb-2 text-truncate" title="{{ $item['position'] }}">
                                        @if(!empty($item['rank']))
                                            <span class="badge bg-light text-secondary border border-secondary-subtle py-0 px-1 me-1" style="font-size: 10px; font-weight: 600;">{{ $item['rank'] }}</span>
                                        @endif
                                        {{ $item['position'] }}
                                    </p>

                                    <!-- Attendance Badge -->
                                    <div class="mb-3">
                                        @if($hasExcuse)
                                            <span class="badge status-badge-excused rounded-pill px-3 py-1 shadow-sm" title="{{ $att['excuse_reason'] ?? 'Сабабли' }}">
                                                🟡 Сабабли
                                            </span>
                                        @elseif($isInside)
                                            <span class="badge status-badge-inside rounded-pill px-3 py-1 shadow-sm">
                                                🟢 Бинода {{ !empty($att['entry_time']) ? '(' . substr($att['entry_time'], 11, 5) . ')' : '' }}
                                            </span>
                                        @elseif($isLate)
                                            <span class="badge status-badge-late rounded-pill px-3 py-1 shadow-sm">
                                                🟠 Кечиккан (+{{ $att['late_minutes'] ?? 0 }} дақ)
                                            </span>
                                        @elseif($isArrived)
                                            <span class="badge status-badge-arrived rounded-pill px-3 py-1 shadow-sm">
                                                🔵 Келган {{ !empty($att['entry_time']) ? '(' . substr($att['entry_time'], 11, 5) . ')' : '' }}
                                            </span>
                                        @elseif($isAbsent)
                                            <span class="badge status-badge-absent rounded-pill px-3 py-1 shadow-sm">
                                                🔴 Келмаган
                                            </span>
                                        @else
                                            <span class="badge status-badge-unknown rounded-pill px-3 py-1 shadow-sm">
                                                —
                                            </span>
                                        @endif
                                    </div>

                                    <!-- 3 Micro Stats Grid -->
                                    <div class="p-2 bg-light rounded-3 mb-3 text-start">
                                        <!-- Tasks -->
                                        <div class="d-flex align-items-center justify-content-between mb-1 small">
                                            <span class="text-secondary fw-semibold"><i class="feather-check-square me-1 text-purple"></i> Топшириқ:</span>
                                            <span class="fw-bold text-dark">{{ $tasks['completed'] ?? 0 }} / {{ $tasks['total'] ?? 0 }} <small class="text-muted">({{ $tasks['percent'] ?? 0 }}%)</small></span>
                                        </div>
                                        <div class="progress mb-2" style="height: 5px;">
                                            <div class="progress-bar bg-purple" role="progressbar" style="width: {{ $tasks['percent'] ?? 0 }}%"></div>
                                        </div>

                                        <!-- I-index & Projects -->
                                        <div class="d-flex align-items-center justify-content-between small">
                                            <span class="text-secondary fw-semibold"><i class="feather-award me-1 text-amber-dark"></i> И-индекс:</span>
                                            <span class="fw-bold text-amber-dark">⭐ {{ number_format($iIndex['score'] ?? 0, 1) }} <small class="text-muted">({{ $iIndex['articles_count'] ?? 0 }} мақола)</small></span>
                                        </div>

                                        <!-- Birthday -->
                                        @if(!empty($bday))
                                            <div class="d-flex align-items-center justify-content-between small mt-1 pt-1 border-top">
                                                <span class="text-secondary fw-semibold"><i class="feather-gift me-1 text-danger"></i> Туғилган кун:</span>
                                                <span class="badge badge-rose-crisp">{{ $bday['badge'] ?? $bday['formatted'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Button: Open 360 Dossier -->
                                <div>
                                    <a href="{{ $empId ? route('employees.show', $empId) : '#' }}" 
                                       class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-semibold d-flex align-items-center justify-content-center gap-1 shadow-sm">
                                        <i class="feather-user"></i> 👤 360° Тўлиқ Досье
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted fs-5">Ходимлар топилмади.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ==================== [ VIEW 2: STREAMLINED TABLE (TABLE VIEW) ] ==================== -->
        <div id="viewModeTable" style="display: none;">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive" style="max-height: 800px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" id="unifiedEmployeeTable">
                        <thead class="sticky-top" style="background-color: #1e293b; color: #ffffff; z-index: 5;">
                            <tr>
                                <th style="width: 50px;" class="text-center">№</th>
                                <th style="min-width: 260px;">Ходим</th>
                                <th style="min-width: 170px;" class="text-center">Бугунги давомат</th>
                                <th style="min-width: 200px;">Топшириқлар ижроси</th>
                                <th style="min-width: 130px;" class="text-center">И-индекс</th>
                                <th style="min-width: 120px;" class="text-center">Лойиҳа / Ижод</th>
                                <th style="min-width: 140px;" class="text-center">Туғилган кун</th>
                                <th style="min-width: 130px;" class="text-center">Ҳаракат</th>
                            </tr>
                        </thead>
                        <tbody id="employeeTableBody">
                            @forelse($xodimlar as $index => $item)
                                @php
                                    $empId = $item['id'] ?? null;
                                    $att = $item['attendance'] ?? [];
                                    $tasks = $item['tasks'] ?? [];
                                    $iIndex = $item['i_index'] ?? [];
                                    $bday = $item['birthday'] ?? null;
                                    $isInside = $att['is_inside'] ?? false;
                                    $isLate = $att['is_late'] ?? false;
                                    $isArrived = $att['is_arrived'] ?? false;
                                    $hasExcuse = $att['has_excuse'] ?? false;
                                    $isAbsent = $att['is_absent'] ?? false;
                                @endphp
                                <tr class="employee-table-row" 
                                    data-name="{{ mb_strtolower($item['name']) }}"
                                    data-department="{{ $item['department'] }}"
                                    data-attendance="{{ $att['status'] ?? 'unknown' }}"
                                    data-has-birthday="{{ (!empty($bday) && ($bday['days_until'] ?? 999) <= 30) ? '1' : '0' }}"
                                    data-turniket-id="{{ $item['turniket_id'] ?? '' }}">
                                    
                                    <td class="text-center text-dark fw-bold font-monospace fs-13">#{{ $index + 1 }}</td>

                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $item['avatar'] }}" 
                                                 alt="{{ $item['name'] }}" 
                                                 class="rounded-circle shadow-sm border border-2 {{ $isInside ? 'border-success' : ($isLate ? 'border-warning' : 'border-light') }}"
                                                 style="width: 44px; height: 44px; object-fit: cover;"
                                                 onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 128 128\'%3E%3Cdefs%3E%3ClinearGradient id=\'g\' x1=\'0%25\' y1=\'0%25\' x2=\'100%25\' y2=\'100%25\'%3E%3Cstop offset=\'0%25\' stop-color=\'%234f46e5\'/%3E%3Cstop offset=\'100%25\' stop-color=\'%237c3aed\'/%3E%3C/defs%3E%3Crect width=\'128\' height=\'128\' rx=\'64\' fill=\'url(%23g)\'/%3E%3Cpath d=\'M64 62a22 22 0 1 0 0-44 22 22 0 0 0 0 44zm0 12c-23 0-42 15-42 34v6h84v-6c0-19-19-34-42-34z\' fill=\'%23ffffff\' opacity=\'0.9\'/%3E%3C/svg%3E';">
                                            <div>
                                                <a href="{{ $empId ? route('employees.show', $empId) : '#' }}" class="fw-bold text-dark text-decoration-none hover-primary d-block">
                                                    {{ $item['name'] }}
                                                </a>
                                                <div class="small text-muted text-truncate" style="max-width: 250px;">
                                                    {{ $item['position'] }} • <span class="text-secondary">{{ $item['department'] }}</span>
                                                    @if(!empty($item['turniket_id']))
                                                        <span class="badge bg-dark text-white rounded-pill ms-1 font-monospace px-2 py-0.5" style="font-size: 10px;">ID: {{ $item['turniket_id'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        @if($hasExcuse)
                                            <span class="badge status-badge-excused rounded-pill px-2 py-1" title="{{ $att['excuse_reason'] ?? 'Сабабли' }}">
                                                🟡 Сабабли
                                            </span>
                                        @elseif($isInside)
                                            <span class="badge status-badge-inside rounded-pill px-2 py-1">
                                                🟢 Бинода {{ !empty($att['entry_time']) ? '(' . substr($att['entry_time'], 11, 5) . ')' : '' }}
                                            </span>
                                        @elseif($isLate)
                                            <span class="badge status-badge-late rounded-pill px-2 py-1">
                                                🟠 Кечиккан (+{{ $att['late_minutes'] ?? 0 }}м)
                                            </span>
                                        @elseif($isArrived)
                                            <span class="badge status-badge-arrived rounded-pill px-2 py-1">
                                                🔵 Келган {{ !empty($att['entry_time']) ? '(' . substr($att['entry_time'], 11, 5) . ')' : '' }}
                                            </span>
                                        @elseif($isAbsent)
                                            <span class="badge status-badge-absent rounded-pill px-2 py-1">
                                                🔴 Келмаган
                                            </span>
                                        @else
                                            <span class="badge status-badge-unknown rounded-pill px-2 py-1">
                                                —
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center justify-content-between mb-1 small">
                                            <span class="fw-bold text-dark">{{ $tasks['completed'] ?? 0 }} / {{ $tasks['total'] ?? 0 }}</span>
                                            <span class="text-muted fw-semibold">{{ $tasks['percent'] ?? 0 }}%</span>
                                        </div>
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar bg-purple" style="width: {{ $tasks['percent'] ?? 0 }}%"></div>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <div class="fw-bold text-amber-dark fs-6">
                                            ⭐ {{ number_format($iIndex['score'] ?? 0, 1) }}
                                        </div>
                                        <div class="small text-muted fw-semibold">{{ $iIndex['articles_count'] ?? 0 }} мақола</div>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge badge-blue-crisp">🚀 {{ $item['projects_count'] ?? 0 }}</span>
                                        <span class="badge badge-sky-crisp">✍️ {{ $item['works_count'] ?? 0 }}</span>
                                    </td>

                                    <td class="text-center">
                                        @if(!empty($bday))
                                            <div class="small fw-semibold text-dark">{{ $bday['formatted'] ?? '' }}</div>
                                            <span class="badge badge-rose-crisp">{{ $bday['badge'] ?? '' }}</span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ $empId ? route('employees.show', $empId) : '#' }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
                                            Досье
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">Ходимлар топилмади.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ==================== [ SCRIPTS & CHARTS INITIALIZATION ] ==================== -->
<script src="{{ asset('assets/vendors/js/sweetalert2.min.js') }}"></script>

<script>
    // Live Clock Asia/Tashkent
    function updateLiveClock() {
        const el = document.getElementById('liveClock');
        if (!el) return;
        const now = new Date();
        const opts = { timeZone: 'Asia/Tashkent', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        el.innerText = new Intl.DateTimeFormat('uz-UZ', opts).format(now);
    }
    setInterval(updateLiveClock, 1000);
    updateLiveClock();

    // Toggle Charts Section
    function toggleChartsSection() {
        const sec = document.getElementById('creativeChartsSection');
        const txt = document.getElementById('toggleChartText');
        if (!sec) return;
        if (sec.style.display === 'none') {
            sec.style.display = 'block';
            if (txt) txt.innerText = '📊 Диаграммаларни яшириш';
        } else {
            sec.style.display = 'none';
            if (txt) txt.innerText = '📊 Диаграммалар';
        }
    }

    // Switch between Cards Grid and Table
    function switchView(mode) {
        const viewCards = document.getElementById('viewModeCards');
        const viewTable = document.getElementById('viewModeTable');
        const btnCards = document.getElementById('btnViewCards');
        const btnTable = document.getElementById('btnViewTable');

        if (mode === 'cards') {
            viewCards.style.display = 'block';
            viewTable.style.display = 'none';
            btnCards.classList.add('active');
            btnCards.classList.remove('text-muted');
            btnTable.classList.remove('active');
            btnTable.classList.add('text-muted');
            localStorage.setItem('monitoring_view_mode', 'cards');
        } else {
            viewCards.style.display = 'none';
            viewTable.style.display = 'block';
            btnTable.classList.add('active');
            btnTable.classList.remove('text-muted');
            btnCards.classList.remove('active');
            btnCards.classList.add('text-muted');
            localStorage.setItem('monitoring_view_mode', 'table');
        }
    }

    // Restore saved view mode
    document.addEventListener('DOMContentLoaded', function() {
        const savedMode = localStorage.getItem('monitoring_view_mode') || 'cards';
        switchView(savedMode);
    });

    // Real-time Search & Filter System
    let currentQuickFilter = '';

    function setQuickFilter(filter) {
        currentQuickFilter = filter;
        document.querySelectorAll('.filter-chip').forEach(chip => {
            if (chip.getAttribute('data-filter') === filter) {
                chip.classList.add('active', 'btn-primary', 'text-white');
                chip.classList.remove('btn-light', 'border');
            } else {
                chip.classList.remove('active', 'btn-primary', 'text-white');
                chip.classList.add('btn-light', 'border');
            }
        });
        applyAllFilters();
    }

    function applyAllFilters() {
        const query = (document.getElementById('employeeSearchInput')?.value || '').toLowerCase().trim();
        const selectedDep = document.getElementById('departmentFilter')?.value || '';
        const visibleCountEl = document.getElementById('visibleCount');

        const cardWrappers = document.querySelectorAll('.employee-card-wrapper');
        const tableRows = document.querySelectorAll('.employee-table-row');

        let visibleCount = 0;

        function matchItem(name, dep, att, turniketId, hasBday) {
            const matchesQuery = !query || name.includes(query) || dep.toLowerCase().includes(query) || turniketId.includes(query);
            const matchesDep = !selectedDep || dep === selectedDep;
            let matchesFilter = true;

            if (currentQuickFilter === 'inside') {
                matchesFilter = (att === 'inside');
            } else if (currentQuickFilter === 'late') {
                matchesFilter = (att === 'late');
            } else if (currentQuickFilter === 'excused') {
                matchesFilter = (att === 'excused');
            } else if (currentQuickFilter === 'absent') {
                matchesFilter = (att === 'absent');
            } else if (currentQuickFilter === 'birthday') {
                matchesFilter = (hasBday === '1');
            }

            return matchesQuery && matchesDep && matchesFilter;
        }

        cardWrappers.forEach(el => {
            const name = el.getAttribute('data-name') || '';
            const dep = el.getAttribute('data-department') || '';
            const att = el.getAttribute('data-attendance') || '';
            const tId = el.getAttribute('data-turniket-id') || '';
            const hasBday = el.getAttribute('data-has-birthday') || '0';

            if (matchItem(name, dep, att, tId, hasBday)) {
                el.style.display = '';
                visibleCount++;
            } else {
                el.style.display = 'none';
            }
        });

        tableRows.forEach(el => {
            const name = el.getAttribute('data-name') || '';
            const dep = el.getAttribute('data-department') || '';
            const att = el.getAttribute('data-attendance') || '';
            const tId = el.getAttribute('data-turniket-id') || '';
            const hasBday = el.getAttribute('data-has-birthday') || '0';

            if (matchItem(name, dep, att, tId, hasBday)) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });

        if (visibleCountEl) {
            visibleCountEl.innerText = visibleCount;
        }
    }

    document.getElementById('employeeSearchInput')?.addEventListener('input', applyAllFilters);
    document.getElementById('departmentFilter')?.addEventListener('change', applyAllFilters);

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
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Муваффақиятли!',
                        text: data.message,
                        confirmButtonText: 'Аъло'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    alert(data.message);
                    window.location.reload();
                }
            } else {
                alert(data.message || 'Хатолик юз берди.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Сервер билан боғланишда хатолик: ' + err.message);
        })
        .finally(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="feather-refresh-cw"></i> 🔄 Турникет расмларини янгилаш';
            }
        });
    }

    // ==================== [ APEXCHARTS INITIALIZATION ] ====================
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof ApexCharts === 'undefined') return;

        // Chart 1: Attendance Donut
        const insideCount = {{ $summary['inside_count'] ?? 0 }};
        const arrivedCount = {{ $summary['arrived_count'] ?? 0 }};
        const lateCount = {{ $summary['late_count'] ?? 0 }};
        const excusedCount = {{ $summary['excused_count'] ?? 0 }};
        const absentCount = {{ $summary['absent_count'] ?? 0 }};

        const donutOptions = {
            series: [insideCount, arrivedCount, lateCount, excusedCount, absentCount],
            labels: ['Бинода', 'Келган', 'Кечиккан', 'Сабабли', 'Келмаган'],
            colors: ['#10b981', '#06b6d4', '#f59e0b', '#3b82f6', '#ef4444'],
            chart: {
                type: 'donut',
                height: 250,
                fontFamily: 'inherit'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '72%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Жами ходим',
                                formatter: () => '{{ $summary['total_employees'] ?? count($xodimlar) }}'
                            }
                        }
                    }
                }
            },
            legend: {
                position: 'bottom',
                fontSize: '12px'
            },
            dataLabels: {
                enabled: false
            }
        };
        new ApexCharts(document.querySelector("#attendanceDonutChart"), donutOptions).render();

        // Chart 2: Tasks Radial Progress
        const percentCompleted = {{ $summary['percent'] ?? 0 }};
        const radialOptions = {
            series: [percentCompleted],
            chart: {
                height: 250,
                type: 'radialBar',
                fontFamily: 'inherit'
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '68%',
                    },
                    dataLabels: {
                        name: {
                            show: true,
                            fontSize: '14px',
                            color: '#64748b',
                            offsetY: -5
                        },
                        value: {
                            show: true,
                            fontSize: '24px',
                            fontWeight: 'bold',
                            color: '#8b5cf6',
                            offsetY: 5,
                            formatter: (val) => val + '%'
                        }
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#3b82f6'],
                    stops: [0, 100]
                }
            },
            colors: ['#8b5cf6'],
            labels: ['Бажарилди']
        };
        new ApexCharts(document.querySelector("#tasksRadialChart"), radialOptions).render();

        // Chart 3: Top Departments Bar
        @php
            $topDepts = $summary['dept_stats'] ?? [];
            $deptNames = array_map(fn($d) => mb_substr($d['name'], 0, 16), $topDepts);
            $deptTasks = array_map(fn($d) => $d['total_tasks'], $topDepts);
            $deptCompleted = array_map(fn($d) => $d['completed_tasks'], $topDepts);
        @endphp

        const barOptions = {
            series: [{
                name: 'Жами топшириқ',
                data: {!! json_encode($deptTasks) !!}
            }, {
                name: 'Бажарилди',
                data: {!! json_encode($deptCompleted) !!}
            }],
            chart: {
                type: 'bar',
                height: 250,
                stacked: false,
                fontFamily: 'inherit',
                toolbar: { show: false }
            },
            colors: ['#3b82f6', '#10b981'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4
                },
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: {!! json_encode($deptNames) !!},
                labels: {
                    style: { fontSize: '10px' },
                    rotate: -25
                }
            },
            legend: {
                position: 'top',
                fontSize: '11px'
            }
        };
        new ApexCharts(document.querySelector("#departmentsBarChart"), barOptions).render();
    });
</script>

<!-- ==================== [ MODERN HIGH-TECH STYLES ] ==================== -->
<style>
    .letter-spacing-1 { letter-spacing: 0.5px; }
    .fw-black { font-weight: 800; }
    
    .live-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        box-shadow: 0 0 8px #10b981;
    }

    .stat-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08) !important;
    }

    .employee-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        background: #ffffff;
    }
    .employee-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.1) !important;
    }

    .hover-primary:hover {
        color: #4f46e5 !important;
    }

    .bg-indigo-subtle { background-color: rgba(79, 70, 229, 0.12) !important; }
    .text-indigo { color: #4f46e5 !important; }

    .bg-purple { background-color: #8b5cf6 !important; }
    .bg-purple-subtle { background-color: rgba(139, 92, 246, 0.12) !important; }
    .text-purple { color: #8b5cf6 !important; }

    .text-amber { color: #d97706 !important; }
    .text-amber-dark { color: #b45309 !important; }
    .bg-amber-subtle { background-color: #fef3c7 !important; color: #92400e !important; }

    /* Crisp High-Contrast Badges */
    .status-badge-inside {
        background-color: #dcfce7 !important;
        color: #166534 !important;
        border: 1px solid #86efac !important;
        font-weight: 600 !important;
    }
    .status-badge-arrived {
        background-color: #e0f2fe !important;
        color: #0369a1 !important;
        border: 1px solid #7dd3fc !important;
        font-weight: 600 !important;
    }
    .status-badge-late {
        background-color: #ffedd5 !important;
        color: #9a3412 !important;
        border: 1px solid #fed7aa !important;
        font-weight: 600 !important;
    }
    .status-badge-excused {
        background-color: #fef9c3 !important;
        color: #854d0e !important;
        border: 1px solid #fde047 !important;
        font-weight: 600 !important;
    }
    .status-badge-absent {
        background-color: #fee2e2 !important;
        color: #991b1b !important;
        border: 1px solid #fca5a5 !important;
        font-weight: 600 !important;
    }
    .status-badge-unknown {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
        border: 1px solid #cbd5e1 !important;
        font-weight: 600 !important;
    }

    .badge-blue-crisp {
        background-color: #dbeafe !important;
        color: #1d4ed8 !important;
        border: 1px solid #bfdbfe !important;
        font-weight: 600 !important;
    }
    .badge-sky-crisp {
        background-color: #e0f2fe !important;
        color: #0369a1 !important;
        border: 1px solid #bae6fd !important;
        font-weight: 600 !important;
    }
    .badge-amber-crisp {
        background-color: #fef3c7 !important;
        color: #92400e !important;
        border: 1px solid #fde68a !important;
        font-weight: 600 !important;
    }
    .badge-rose-crisp {
        background-color: #ffe4e6 !important;
        color: #9f1239 !important;
        border: 1px solid #fecdd3 !important;
        font-weight: 600 !important;
    }
    .kpi-label {
        color: #475569 !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
    }

    .filter-chip {
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .filter-chip.active {
        background-color: #4f46e5 !important;
        border-color: #4f46e5 !important;
        color: #ffffff !important;
    }

    @media print {
        .monitoring-header, #btnSyncTurniket, .btn, #employeeSearchInput, #departmentFilter, .filter-chip, .btn-group {
            display: none !important;
        }
        #viewModeCards { display: none !important; }
        #viewModeTable { display: block !important; }
        .table-responsive { overflow: visible !important; max-height: none !important; }
    }
</style>
@endsection
