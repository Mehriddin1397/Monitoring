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
                    <h4 class="text-white mb-1 fw-bold">Ходимлар статистикаси, И-индекс ва Лойиҳалар таҳлили</h4>
                    <p class="text-white-50 mb-0 fs-13">Топшириқлар ижро интизоми, илмий мақолалар ҳамда лойиҳалар мониторинги</p>
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

        <!-- Section 1 Title: Topshiriqlar ko'rsatkichlari -->
        <div class="d-flex align-items-center gap-2 mb-3">
            <span class="badge bg-primary text-white p-2 rounded-circle">
                <i class="feather-check-square fs-14"></i>
            </span>
            <h5 class="fw-bold mb-0 text-dark">Топшириқлар ва Ходимлар Ижро Интизоми</h5>
        </div>

        <!-- KPI Summary Cards (Tasks) -->
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

        <!-- Main Charts Section 1: Topshiriqlar diagrammalari -->
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

        <!-- Main Charts Section 2: Dinamika va Kategoriyalar -->
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

            <!-- Categories Distribution Donut Chart -->
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
                        <div id="chartCategories" style="min-height: 300px;"></div>
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

        <!-- ================================================================ -->
        <!-- SECTION 2: I-INDEKS VA LOYIHALAR BO'LIMI STATISTIKASI -->
        <!-- ================================================================ -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 mt-4 pt-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge text-white p-2 rounded-circle" style="background: linear-gradient(135deg, #e17055 0%, #d63031 100%);">
                    <i class="feather-book-open fs-14"></i>
                </span>
                <h5 class="fw-bold mb-0 text-dark">Илмий И-индекс ва Лойиҳалар Таҳлили</h5>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('articles.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    <i class="feather-external-link me-1"></i> И-индекс саҳифаси
                </a>
                <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="feather-briefcase me-1"></i> Лойиҳалар саҳифаси
                </a>
            </div>
        </div>

        <!-- I-Index & Projects KPI Cards -->
        <div class="row g-3 mb-4">
            <!-- Total Articles -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 kpi-card" style="background: linear-gradient(135deg, #e84393 0%, #fd79a8 100%); border-radius: 16px; color: #fff;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fs-12 text-uppercase fw-bold text-white-50">Жами Мақолалар (И-индекс)</span>
                            <div class="kpi-icon-wrap bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center">
                                <i class="feather-file-text fs-16 text-white"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-1 text-white">{{ $articleStats['total'] }} та</h2>
                        <div class="d-flex justify-content-between fs-12 text-white-50">
                            <span><i class="feather-check me-1"></i> {{ $articleStats['checked'] }} та текширилган</span>
                            <span><i class="feather-clock me-1"></i> {{ $articleStats['pending'] }} та кутилмоқда</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Score (I-Index) -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 kpi-card" style="background: linear-gradient(135deg, #6c5ce7 0%, #8e44ad 100%); border-radius: 16px; color: #fff;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fs-12 text-uppercase fw-bold text-white-50">Жами Тўпланган Балл</span>
                            <div class="kpi-icon-wrap bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center">
                                <i class="feather-star fs-16 text-white"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-1 text-white">{{ $articleStats['total_score'] }}</h2>
                        <div class="fs-12 text-white-50">
                            Ўртача балл: <span class="fw-bold text-white">{{ $articleStats['avg_score'] }}</span> балл
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Projects -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 kpi-card" style="background: linear-gradient(135deg, #2d3436 0%, #636e72 100%); border-radius: 16px; color: #fff;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fs-12 text-uppercase fw-bold text-white-50">Жами Лойиҳалар</span>
                            <div class="kpi-icon-wrap bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center">
                                <i class="feather-briefcase fs-16 text-white"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-1 text-white">{{ $projectStats['total'] }} та</h2>
                        <div class="fs-12 text-white-50">
                            Амалга оширилаётган лойиҳалар
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Participants & Docs -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 kpi-card" style="background: linear-gradient(135deg, #0984e3 0%, #00cec9 100%); border-radius: 16px; color: #fff;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fs-12 text-uppercase fw-bold text-white-50">Иштирокчилар ва Ҳужжатлар</span>
                            <div class="kpi-icon-wrap bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center">
                                <i class="feather-users fs-16 text-white"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-1 text-white">{{ $projectStats['participants_count'] }} нафар</h2>
                        <div class="fs-12 text-white-50">
                            {{ $projectStats['documents_count'] }} та бириктирилган ҳужжат
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- I-Index Authors and Projects Grid -->
        <div class="row g-4 mb-4">
            <!-- I-Index Top Authors & Status Donut -->
            <div class="col-xl-7">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2 d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-circle">
                                    <i class="feather-book fs-14"></i>
                                </span>
                                📚 И-индекс: Энг фаол муаллифлар рейтинги
                            </h5>
                            <p class="text-muted fs-13 mb-0">Мақолалари текширилган ва юқори балл тўплаган олимлар</p>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-5 mb-3 mb-md-0">
                                <div id="chartArticlesStatus" style="min-height: 220px;"></div>
                            </div>
                            <div class="col-md-7">
                                @if(count($articleStats['top_authors']) > 0)
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($articleStats['top_authors'] as $idx => $author)
                                            <div class="p-2 px-3 rounded-3 d-flex align-items-center justify-content-between border bg-light">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-dark rounded-circle" style="width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; font-size: 11px;">
                                                        {{ $idx + 1 }}
                                                    </span>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold fs-13 text-dark">{{ $author['full_name'] }}</h6>
                                                        <small class="text-muted fs-11">{{ $author['position'] }} • {{ $author['articles_count'] }} та мақола</small>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-warning text-dark fw-bold px-2 py-1 rounded-pill shadow-sm" style="font-size: 12px;">
                                                        ⭐ {{ $author['total_score'] }} балл
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4 text-muted">Ҳозирча баҳоланган мақолалар мавжуд эмас</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Overview Cards -->
            <div class="col-xl-5">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2 d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                                <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-circle">
                                    <i class="feather-briefcase fs-14"></i>
                                </span>
                                🚀 Лойиҳалар таҳлили
                            </h5>
                            <p class="text-muted fs-13 mb-0">Амалга оширилаётган илмий лойиҳалар</p>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if(count($projectStats['list']) > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($projectStats['list'] as $proj)
                                    <div class="p-3 rounded-4 border bg-white shadow-sm hover-shadow transition-all">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold text-primary mb-0 text-truncate" style="max-width: 250px;" title="{{ $proj['name'] }}">
                                                {{ $proj['name'] ?: 'Лойиҳа номи' }}
                                            </h6>
                                            <span class="badge bg-light text-muted border fs-11">
                                                {{ $proj['created_at'] }}
                                            </span>
                                        </div>
                                        <div class="fs-12 text-muted mb-2">
                                            <i class="feather-user me-1 text-secondary"></i> Раҳбар: <span class="text-dark fw-semibold">{{ $proj['leader'] }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 pt-2 border-top">
                                            <span class="badge bg-primary bg-opacity-10 text-primary fs-11">
                                                <i class="feather-users me-1"></i> {{ $proj['participants_count'] }} иштирокчи
                                            </span>
                                            <span class="badge bg-info bg-opacity-10 text-info fs-11">
                                                <i class="feather-paperclip me-1"></i> {{ $proj['docs_count'] }} ҳужжат
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">Ҳозирча лойиҳалар киритилмаган</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Full Breakdown Interactive Table Card (Tasks) -->
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
    // 4. Toifalar bo'yicha taqsimot (Donut Chart - 100% Reliable)
    // ----------------------------------------------------
    if (categoriesCounts && categoriesCounts.length > 0) {
        let catOptions = {
            series: categoriesCounts,
            labels: categoriesLabels,
            chart: {
                type: 'donut',
                height: 300,
                fontFamily: 'inherit'
            },
            colors: ['#6c5ce7', '#0984e3', '#00b894', '#fdcb6e', '#e17055', '#00cec9', '#a29bfe'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '68%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Тоифалар',
                                fontSize: '14px',
                                fontWeight: 600,
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + ' та';
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
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
    // 5. I-Indeks Maqolalar statusi (Donut Chart)
    // ----------------------------------------------------
    const articlesChecked = {{ $articleStats['checked'] }};
    const articlesPending = {{ $articleStats['pending'] }};
    if (document.querySelector("#chartArticlesStatus") && (articlesChecked > 0 || articlesPending > 0)) {
        let articleChartOptions = {
            series: [articlesChecked, articlesPending],
            labels: ['Текширилган', 'Кутилмоқда'],
            chart: {
                type: 'donut',
                height: 220,
                fontFamily: 'inherit'
            },
            colors: ['#00b894', '#fdcb6e'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '72%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Мақолалар',
                                fontSize: '13px',
                                fontWeight: 600,
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + ' та';
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: { position: 'bottom', fontSize: '12px' },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " та мақола";
                    }
                }
            }
        };

        let articleChart = new ApexCharts(document.querySelector("#chartArticlesStatus"), articleChartOptions);
        articleChart.render();
    }

    // ----------------------------------------------------
    // 6. Jadval bo'yicha tezkor qidiruv (Live Search)
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
