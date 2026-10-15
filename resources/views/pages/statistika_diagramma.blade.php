@extends('layouts.admin')

@section('title', 'Ходимлар статистикаси ва Диаграммалар')

@section('content')
<div class="nxl-content d-flex flex-column h-100">
    <!-- [ page-header ] start -->
    <div class="page-header" style="background: linear-gradient(135deg, #1e1e38 0%, #30336b 100%); padding: 22px 25px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 8px 24px rgba(0,0,0,0.12);">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 w-100">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-text avatar-lg bg-white text-primary rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; font-size: 24px;">
                    <i class="feather-pie-chart" style="color: #4834d4;"></i>
                </div>
                <div>
                    <h4 class="text-white mb-1 fw-bold">Ходимлар статистикаси ва Креатив Диаграммалар</h4>
                    <p class="text-white-50 mb-0 fs-13">Топшириқлар ижро интизоми, ходимлар самарадорлиги ва интерактив аналитика</p>
                </div>
            </div>

            <!-- View switcher and action buttons -->
            <div class="d-flex align-items-center flex-wrap gap-2">
                <div class="btn-group p-1 bg-white bg-opacity-10 rounded-pill backdrop-blur">
                    <a href="{{ route('monitoring.diagramma') }}" class="btn btn-sm btn-light rounded-pill px-3 fw-semibold shadow-sm text-primary">
                        <i class="feather-pie-chart me-1"></i> Диаграммалар
                    </a>
                    <a href="{{ route('monitoring.umumiy') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 border-0 text-white">
                        <i class="feather-table me-1"></i> Жадвал кўриниши
                    </a>
                </div>

                <button type="button" class="btn btn-sm btn-warning rounded-pill px-3 fw-semibold" onclick="window.print()">
                    <i class="feather-printer me-1"></i> Чоп этиш
                </button>
            </div>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-3 p-md-4">
                <form action="{{ route('monitoring.diagramma') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-12 col-md-auto">
                        <label class="form-label fs-12 text-uppercase fw-bold text-muted mb-1">Тезкор давр</label>
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('monitoring.diagramma', ['period' => 'all']) }}" 
                               class="btn btn-outline-primary btn-sm {{ $period === 'all' ? 'active' : '' }}">Барчаси</a>
                            <a href="{{ route('monitoring.diagramma', ['period' => 'today']) }}" 
                               class="btn btn-outline-primary btn-sm {{ $period === 'today' ? 'active' : '' }}">Бугун</a>
                            <a href="{{ route('monitoring.diagramma', ['period' => 'this_month']) }}" 
                               class="btn btn-outline-primary btn-sm {{ $period === 'this_month' ? 'active' : '' }}">Шу ой</a>
                            <a href="{{ route('monitoring.diagramma', ['period' => 'this_year']) }}" 
                               class="btn btn-outline-primary btn-sm {{ $period === 'this_year' ? 'active' : '' }}">Шу йил</a>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fs-12 text-uppercase fw-bold text-muted mb-1">Бошланиш санаси</label>
                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fs-12 text-uppercase fw-bold text-muted mb-1">Тугаш санаси</label>
                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
                    </div>

                    @if(isset($categories) && count($categories) > 0)
                    <div class="col-12 col-sm-6 col-md-2">
                        <label class="form-label fs-12 text-uppercase fw-bold text-muted mb-1">Тоифа (Категория)</label>
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">Барчаси</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-12 col-sm-6 col-md-auto d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm px-3 w-100">
                            <i class="feather-filter me-1"></i> Филтрлаш
                        </button>
                        <a href="{{ route('monitoring.diagramma') }}" class="btn btn-light btn-sm px-3" title="Тозалаш">
                            <i class="feather-rotate-ccw"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- KPI Summary Cards -->
        <div class="row g-3 mb-4">
            <!-- Total Tasks -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 kpi-card" style="background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%); border-radius: 16px; color: #fff;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fs-12 text-uppercase fw-bold text-white-50">Жами Топшириқлар</span>
                            <div class="kpi-icon-wrap bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center">
                                <i class="feather-layers fs-16 text-white"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-1 text-white">{{ $summary['total'] }}</h2>
                        <div class="fs-12 text-white-50">
                            <i class="feather-users me-1"></i> {{ $summary['active_users_count'] }} нафар ходим
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Execution Score -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 kpi-card" style="background: linear-gradient(135deg, #0984e3 0%, #74b9ff 100%); border-radius: 16px; color: #fff;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fs-12 text-uppercase fw-bold text-white-50">Ижро Самарадорлиги</span>
                            <div class="kpi-icon-wrap bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center">
                                <i class="feather-award fs-16 text-white"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-1 text-white">{{ $summary['completion_rate'] }}%</h2>
                        <div class="progress bg-white bg-opacity-25" style="height: 6px; border-radius: 10px;">
                            <div class="progress-bar bg-white" role="progressbar" style="width: {{ $summary['completion_rate'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 kpi-card" style="background: linear-gradient(135deg, #00b894 0%, #55efc4 100%); border-radius: 16px; color: #fff;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fs-12 text-uppercase fw-bold text-white-50">Бажарилган</span>
                            <div class="kpi-icon-wrap bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center">
                                <i class="feather-check-circle fs-16 text-white"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-1 text-white">{{ $summary['completed'] }}</h2>
                        <div class="fs-12 text-white-50">
                            @if($summary['total'] > 0)
                                {{ round(($summary['completed'] / $summary['total']) * 100, 1) }}% улуш
                            @else
                                0%
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- In Process -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 kpi-card" style="background: linear-gradient(135deg, #00cec9 0%, #81ecec 100%); border-radius: 16px; color: #fff;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fs-12 text-uppercase fw-bold text-white-50">Жараёнда</span>
                            <div class="kpi-icon-wrap bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center">
                                <i class="feather-clock fs-16 text-white"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-1 text-white">{{ $summary['in_process'] }}</h2>
                        <div class="fs-12 text-white-50">Муддати етмаган фаоллар</div>
                    </div>
                </div>
            </div>

            <!-- Extended -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 kpi-card" style="background: linear-gradient(135deg, #fdcb6e 0%, #ffeaa7 100%); border-radius: 16px; color: #2d3436;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fs-12 text-uppercase fw-bold text-dark opacity-75">Ўзайтирилган</span>
                            <div class="kpi-icon-wrap bg-dark bg-opacity-10 rounded-circle p-2 d-flex align-items-center justify-content-center">
                                <i class="feather-alert-circle fs-16 text-dark"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-1 text-dark">{{ $summary['extended'] }}</h2>
                        <div class="fs-12 text-dark opacity-75">Қайта муддат берилган</div>
                    </div>
                </div>
            </div>

            <!-- Not Completed / Overdue -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 kpi-card" style="background: linear-gradient(135deg, #d63031 0%, #ff7675 100%); border-radius: 16px; color: #fff;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fs-12 text-uppercase fw-bold text-white-50">Бажарилмаган</span>
                            <div class="kpi-icon-wrap bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center">
                                <i class="feather-x-circle fs-16 text-white"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-1 text-white">{{ $summary['not_completed'] }}</h2>
                        <div class="fs-12 text-white-50">Муддати ўтган / кечиккан</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Charts Section 1 -->
        <div class="row g-4 mb-4">
            <!-- Big Employee Comparison Chart -->
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-circle">
                                    <i class="feather-bar-chart-2 fs-14"></i>
                                </span>
                                Ходимлар кесимида топшириқлар таҳлили
                            </h5>
                            <p class="text-muted fs-13 mb-0">Ҳар бир ходим бўйича бажарилган, жараёндаги, узайтирилган ва бажарилмаган ишлар</p>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary active" id="btnChartStacked" onclick="toggleStacked(true)">
                                Тўплам (Stacked)
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="btnChartGrouped" onclick="toggleStacked(false)">
                                Ёнма-ён (Grouped)
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div id="chartEmployeesComparison" style="min-height: 380px;"></div>
                    </div>
                </div>
            </div>

            <!-- Overall Status Donut Chart -->
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                            <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-circle">
                                <i class="feather-pie-chart fs-14"></i>
                            </span>
                            Ижро ҳолати мувозанати
                        </h5>
                        <p class="text-muted fs-13 mb-0">Топшириқларнинг умумий тақсимоти</p>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-center">
                        <div id="chartStatusDonut" style="min-height: 280px;"></div>
                        <div class="row g-2 text-center mt-3 pt-3 border-top">
                            <div class="col-6">
                                <span class="fs-12 text-muted d-block">Бажарилиш даражаси</span>
                                <span class="fw-bold fs-16 text-success">{{ $summary['completion_rate'] }}%</span>
                            </div>
                            <div class="col-6">
                                <span class="fs-12 text-muted d-block">Қарздорлик улуши</span>
                                <span class="fw-bold fs-16 text-danger">
                                    {{ $summary['total'] > 0 ? round(($summary['not_completed'] / $summary['total']) * 100, 1) : 0 }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Charts Section 2 -->
        <div class="row g-4 mb-4">
            <!-- Monthly Trends Spline Area -->
            <div class="col-xl-7">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                            <span class="badge bg-info bg-opacity-10 text-info p-2 rounded-circle">
                                <i class="feather-trending-up fs-14"></i>
                            </span>
                            Ойлик ижро динамикаси (Тренди)
                        </h5>
                        <p class="text-muted fs-13 mb-0">Охирги 6 ой давомида бириктирилган ва якунланган топшириқлар</p>
                    </div>
                    <div class="card-body p-4">
                        <div id="chartMonthlyTrends" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Categories Distribution or Polar Chart -->
            <div class="col-xl-5">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                            <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-circle">
                                <i class="feather-tag fs-14"></i>
                            </span>
                            Тоифалар (Категориялар) бўйича тақсимот
                        </h5>
                        <p class="text-muted fs-13 mb-0">Топшириқлар йўналишлари ва соҳалари</p>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-center">
                        @if(count($categoryData) > 0)
                            <div id="chartCategories" style="min-height: 300px;"></div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="feather-inbox fs-36 mb-2 d-block opacity-50"></i>
                                <span>Тоифалар бўйича маълумот мавжуд эмас</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Leaderboard & Risk Watch Section -->
        <div class="row g-4 mb-4">
            <!-- Top Performers -->
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2 d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                                <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-circle">
                                    <i class="feather-star fs-14"></i>
                                </span>
                                🏆 Энг намунали ходимлар (Топ-5 пешқадамлар)
                            </h5>
                            <p class="text-muted fs-13 mb-0">Энг юқори самарадорлик ва ижро кўрсаткичларига эга ходимлар</p>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if(count($topPerformers) > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($topPerformers as $idx => $p)
                                    @php
                                        $medalClass = match($idx) {
                                            0 => 'background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); color: #fff;',
                                            1 => 'background: linear-gradient(135deg, #e0e0e0 0%, #b8b8b8 100%); color: #fff;',
                                            2 => 'background: linear-gradient(135deg, #d49a6a 0%, #a76d3f 100%); color: #fff;',
                                            default => 'background: #f1f2f6; color: #2f3542;'
                                        };
                                        $medalBadge = match($idx) {
                                            0 => '🥇',
                                            1 => '🥈',
                                            2 => '🥉',
                                            default => '#' . ($idx + 1)
                                        };
                                    @endphp
                                    <div class="p-3 rounded-4 d-flex align-items-center justify-content-between border transition-all hover-shadow" style="background-color: #fcfcfd; border-radius: 14px;">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 44px; height: 44px; font-size: 16px; {{ $medalClass }}">
                                                {{ $medalBadge }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark">{{ $p['full_name'] }}</h6>
                                                <small class="text-muted">{{ $p['position'] }} • Жами: {{ $p['total'] }} та</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge text-white fs-13 fw-bold px-3 py-1 rounded-pill shadow-sm" style="background-color: #00b894 !important; color: #ffffff !important;">
                                                <i class="feather-check-circle me-1"></i> {{ $p['completed'] }} та ({{ $p['completion_rate'] }}%)
                                            </span>
                                            <div class="progress mt-2" style="width: 110px; height: 6px; border-radius: 6px; background-color: #e2e8f0;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $p['completion_rate'] }}%; background-color: #00b894 !important;"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">Маълумот йўқ</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Risk / Attention Watch -->
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2 d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="fw-bold mb-1 text-danger d-flex align-items-center gap-2">
                                <span class="badge bg-danger bg-opacity-10 text-danger p-2 rounded-circle">
                                    <i class="feather-alert-triangle fs-14"></i>
                                </span>
                                ⚠️ Назорат талаб қилувчи ходимлар
                            </h5>
                            <p class="text-muted fs-13 mb-0">Кечиккан ёки бажарилмаган топшириқлари мавжуд ходимлар</p>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if(count($attentionUsers) > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($attentionUsers as $u)
                                    <div class="p-3 rounded-4 d-flex align-items-center justify-content-between border border-danger border-opacity-25 transition-all hover-shadow" style="background-color: #fff9f9; border-radius: 14px;">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text avatar-md bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;">
                                                <i class="feather-user-x fs-18"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark">{{ $u['full_name'] }}</h6>
                                                <small class="text-muted">{{ $u['position'] }} • Жами: {{ $u['total'] }} та топшириқ</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-danger text-white fs-13 fw-bold px-3 py-1 rounded-pill shadow-sm">
                                                {{ $u['not_completed'] }} та бажарилмаган
                                            </span>
                                            <div class="fs-12 text-muted mt-1">
                                                Жараёнда: {{ $u['in_process'] }} та
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-success">
                                <i class="feather-check-circle fs-36 d-block mb-2 text-success"></i>
                                <span class="fw-bold">Ажойиб! Ҳозирда қарздорлик ёки муддати ўтган топшириғи бор ходимлар йўқ!</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Full Breakdown Interactive Table Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2 d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h5 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-circle">
                            <i class="feather-list fs-14"></i>
                        </span>
                        Ходимлар кесимида батафсил кўрсаткичлар
                    </h5>
                    <p class="text-muted fs-13 mb-0">Тезкор қидирув ва самарадорлик фоизлари билан</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" id="employeeSearchInput" class="form-control form-control-sm rounded-pill px-3" placeholder="🔍 Ходимни қидириш..." style="max-width: 240px;">
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="detailedEmployeeTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 text-uppercase fs-11 fw-bold text-muted" style="width: 50px;">№</th>
                                <th class="text-uppercase fs-11 fw-bold text-muted">Ходим Ф.И.Ш</th>
                                <th class="text-uppercase fs-11 fw-bold text-muted text-center">Жами</th>
                                <th class="text-uppercase fs-11 fw-bold text-muted text-center text-success">Бажарилган</th>
                                <th class="text-uppercase fs-11 fw-bold text-muted text-center text-primary">Жараёнда</th>
                                <th class="text-uppercase fs-11 fw-bold text-muted text-center text-warning">Ўзайтирилган</th>
                                <th class="text-uppercase fs-11 fw-bold text-muted text-center text-danger">Бажарилмаган</th>
                                <th class="text-uppercase fs-11 fw-bold text-muted text-center pe-4" style="width: 180px;">Самарадорлик (KPI)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($xodimlarStats as $index => $item)
                                <tr class="employee-row">
                                    <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-text avatar-sm bg-light text-primary rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 13px;">
                                                {{ mb_substr($item['name'], 0, 1) }}
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark d-block emp-name">{{ $item['name'] }}</span>
                                                <small class="text-muted">{{ $item['position'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold fs-14">{{ $item['total'] }}</td>
                                    <td class="text-center fw-bold fs-14 text-success">{{ $item['completed'] }}</td>
                                    <td class="text-center fw-bold fs-14 text-primary">{{ $item['in_process'] }}</td>
                                    <td class="text-center fw-bold fs-14 text-warning">{{ $item['extended'] }}</td>
                                    <td class="text-center fw-bold fs-14 text-danger">{{ $item['not_completed'] }}</td>
                                    <td class="pe-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 7px; border-radius: 8px;">
                                                @php
                                                    $barColor = $item['completion_rate'] >= 80 ? 'bg-success' : ($item['completion_rate'] >= 50 ? 'bg-primary' : ($item['completion_rate'] > 0 ? 'bg-warning' : 'bg-danger'));
                                                @endphp
                                                <div class="progress-bar {{ $barColor }}" role="progressbar" style="width: {{ $item['completion_rate'] }}%"></div>
                                            </div>
                                            <span class="fw-bold fs-12 text-dark" style="min-width: 42px;">{{ $item['completion_rate'] }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <!-- Total Row -->
                            <tr class="bg-light fw-bold">
                                <td class="ps-4"></td>
                                <td class="text-dark">ЖАМИ:</td>
                                <td class="text-center fs-15 text-dark">{{ $summary['total'] }}</td>
                                <td class="text-center fs-15 text-success">{{ $summary['completed'] }}</td>
                                <td class="text-center fs-15 text-primary">{{ $summary['in_process'] }}</td>
                                <td class="text-center fs-15 text-warning">{{ $summary['extended'] }}</td>
                                <td class="text-center fs-15 text-danger">{{ $summary['not_completed'] }}</td>
                                <td class="pe-4 text-center text-primary fs-14">{{ $summary['completion_rate'] }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<!-- Fallback ApexCharts CDN if not already initialized -->
<script>
    if (typeof ApexCharts === 'undefined') {
        document.write('<script src="https://cdn.jsdelivr.net/npm/apexcharts"><\/script>');
    }
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // ----------------------------------------------------
    // Ma'lumotlarni controller'dan olish
    // ----------------------------------------------------
    const employeeNames = @json($chartEmployees['names']);
    const employeeCompleted = @json($chartEmployees['completed']);
    const employeeInProcess = @json($chartEmployees['in_process']);
    const employeeExtended = @json($chartEmployees['extended']);
    const employeeNotCompleted = @json($chartEmployees['not_completed']);

    const summaryCompleted = {{ $summary['completed'] }};
    const summaryInProcess = {{ $summary['in_process'] }};
    const summaryExtended = {{ $summary['extended'] }};
    const summaryNotCompleted = {{ $summary['not_completed'] }};

    const monthlyMonths = @json($monthlyTrends['months']);
    const monthlyTotal = @json($monthlyTrends['total']);
    const monthlyCompleted = @json($monthlyTrends['completed']);

    const categoriesLabels = @json(array_column($categoryData, 'name'));
    const categoriesCounts = @json(array_column($categoryData, 'count'));

    // ----------------------------------------------------
    // 1. Xodimlar kesimida taqqoslash (Comparison Chart)
    // ----------------------------------------------------
    let empChartOptions = {
        series: [
            { name: 'Бажарилган', data: employeeCompleted },
            { name: 'Жараёнда', data: employeeInProcess },
            { name: 'Ўзайтирилган', data: employeeExtended },
            { name: 'Бажарилмаган', data: employeeNotCompleted }
        ],
        chart: {
            type: 'bar',
            height: 380,
            stacked: true,
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: false,
                    zoom: false,
                    zoomin: false,
                    zoomout: false,
                    pan: false,
                    reset: false
                }
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            },
            fontFamily: 'inherit'
        },
        colors: ['#00b894', '#0984e3', '#fdcb6e', '#d63031'],
        plotOptions: {
            bar: {
                horizontal: false,
                borderRadius: 6,
                columnWidth: '45%'
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: employeeNames,
            labels: {
                rotate: -25,
                style: {
                    fontSize: '12px',
                    fontWeight: 500
                }
            }
        },
        yaxis: {
            title: {
                text: 'Топшириқлар сони'
            }
        },
        fill: {
            opacity: 1
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " та топшириқ";
                }
            }
        },
        grid: {
            borderColor: '#f1f2f6',
            strokeDashArray: 4
        }
    };

    let empChart = new ApexCharts(document.querySelector("#chartEmployeesComparison"), empChartOptions);
    empChart.render();

    // Toggle Stacked / Grouped
    window.toggleStacked = function (isStacked) {
        empChart.updateOptions({
            chart: {
                stacked: isStacked
            },
            plotOptions: {
                bar: {
                    columnWidth: isStacked ? '45%' : '65%'
                }
            }
        });
        document.getElementById('btnChartStacked').classList.toggle('active', isStacked);
        document.getElementById('btnChartGrouped').classList.toggle('active', !isStacked);
    };

    // ----------------------------------------------------
    // 2. Ijro holati balansi (Donut Chart)
    // ----------------------------------------------------
    let donutOptions = {
        series: [summaryCompleted, summaryInProcess, summaryExtended, summaryNotCompleted],
        labels: ['Бажарилган', 'Жараёнда', 'Ўзайтирилган', 'Бажарилмаган'],
        chart: {
            type: 'donut',
            height: 280,
            fontFamily: 'inherit'
        },
        colors: ['#00b894', '#0984e3', '#fdcb6e', '#d63031'],
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Жами',
                            fontSize: '15px',
                            fontWeight: 600,
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + ' та';
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            position: 'bottom',
            fontSize: '13px'
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " та";
                }
            }
        }
    };

    let donutChart = new ApexCharts(document.querySelector("#chartStatusDonut"), donutOptions);
    donutChart.render();

    // ----------------------------------------------------
    // 3. Oylik dinamika (Spline Area Chart)
    // ----------------------------------------------------
    let trendsOptions = {
        series: [
            { name: 'Бириктирилган', data: monthlyTotal },
            { name: 'Бажарилган', data: monthlyCompleted }
        ],
        chart: {
            type: 'area',
            height: 300,
            toolbar: { show: false },
            fontFamily: 'inherit'
        },
        colors: ['#6c5ce7', '#00b894'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [0, 95, 100]
            }
        },
        xaxis: {
            categories: monthlyMonths,
            labels: {
                style: { fontSize: '12px' }
            }
        },
        yaxis: {
            title: { text: 'Сони' }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        },
        grid: {
            borderColor: '#f1f2f6',
            strokeDashArray: 4
        }
    };

    let trendsChart = new ApexCharts(document.querySelector("#chartMonthlyTrends"), trendsOptions);
    trendsChart.render();

    // ----------------------------------------------------
    // 4. Toifalar bo'yicha taqsimot (Categories Chart)
    // ----------------------------------------------------
    if (categoriesCounts && categoriesCounts.length > 0) {
        let catOptions = {
            series: categoriesCounts,
            labels: categoriesLabels,
            chart: {
                type: 'polarArea',
                height: 300,
                fontFamily: 'inherit'
            },
            colors: ['#6c5ce7', '#0984e3', '#00b894', '#fdcb6e', '#e17055', '#a29bfe'],
            fill: { opacity: 0.9 },
            stroke: { width: 1, colors: ['#fff'] },
            legend: { position: 'bottom', fontSize: '12px' },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " та топшириқ";
                    }
                }
            }
        };

        let catChart = new ApexCharts(document.querySelector("#chartCategories"), catOptions);
        catChart.render();
    }

    // ----------------------------------------------------
    // 5. Jadval bo'yicha tezkor qidiruv (Live Search)
    // ----------------------------------------------------
    const searchInput = document.getElementById('employeeSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.employee-row');

            rows.forEach(function (row) {
                const nameEl = row.querySelector('.emp-name');
                if (nameEl) {
                    const name = nameEl.textContent.toLowerCase();
                    row.style.display = name.includes(query) ? '' : 'none';
                }
            });
        });
    }
});
</script>

<style>
/* Hover and Transition animations */
.kpi-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
}

.hover-shadow {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-shadow:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
}

.backdrop-blur {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Print Styles */
@media print {
    .page-header, .btn, form, #employeeSearchInput, .nxl-navigation, .header-wrapper, .nxl-header {
        display: none !important;
    }
    .card {
        border: 1px solid #ccc !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }
    .nxl-content {
        padding: 0 !important;
    }
}
</style>
@endsection
