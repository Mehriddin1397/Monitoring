@extends('layouts.admin')

@section('title', 'Вазифа тафсилоти: ' . ($task['name'] ?? ''))

@section('content')
<div class="nxl-content d-flex flex-column h-100">
    <!-- [ Page Header ] start -->
    <div class="page-header py-3 px-3 px-lg-4 shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.izlanuvchilar.tasks') }}" class="btn btn-sm btn-outline-light rounded-circle p-2" title="Орқага қайтиш">
                    <i class="feather-arrow-left fs-16"></i>
                </a>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="mb-1 text-white fw-bold fs-20">Вазифа тафсилоти: {{ $task['name'] ?? '' }}</h3>
                        @php
                            $st = $task['status'] ?? 'kutilmoqda';
                        @endphp
                        @if($st === 'tasdiqlandi')
                            <span class="badge bg-success text-uppercase px-2 py-1 fs-11">Тасдиқланган</span>
                        @elseif($st === 'jarayonda')
                            <span class="badge bg-warning text-uppercase px-2 py-1 fs-11 text-dark">Жараёнда</span>
                        @elseif($st === 'qaytarildi')
                            <span class="badge bg-danger text-uppercase px-2 py-1 fs-11">Қайтарилган</span>
                        @else
                            <span class="badge bg-secondary text-uppercase px-2 py-1 fs-11">Кутилмоқда</span>
                        @endif
                    </div>
                    <p class="fs-13 text-white-50 mb-0">
                        Талабгор: <strong class="text-white">{{ $task['project']['applicant_name'] ?? 'Номаълум' }}</strong> | ID: #{{ $task['id'] ?? '' }}
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('admin.izlanuvchilar.tasks') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-list me-1"></i> Вазифалар рўйхати
                </a>
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

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Left Column: Task info & Documents History -->
            <div class="col-12 col-lg-8">
                <!-- Task Overview Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold fs-16">
                            <i class="feather-info text-primary me-2"></i> Вазифа ҳақида маълумот
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="text-muted fs-12 d-block">Вазифа номи</label>
                                <span class="fw-bold text-dark fs-15">{{ $task['name'] ?? '' }}</span>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted fs-12 d-block">Босқич (Stage)</label>
                                <span class="badge bg-light text-dark border fs-13">
                                    {{ $task['stage']['order'] ?? 1 }}-босқич: {{ $task['stage']['name'] ?? '—' }}
                                </span>
                            </div>
                            @if(!empty($task['description']))
                                <div class="col-12">
                                    <label class="text-muted fs-12 d-block">Тавсиф / Талаблар</label>
                                    <div class="p-2.5 bg-light rounded text-dark fs-14">
                                        {{ $task['description'] }}
                                    </div>
                                </div>
                            @endif
                            @if(!empty($task['approved_at']))
                                <div class="col-sm-6">
                                    <label class="text-muted fs-12 d-block">Тасдиқланган вақт</label>
                                    <span class="text-success fw-bold">
                                        <i class="feather-check-circle me-1"></i> {{ \Carbon\Carbon::parse($task['approved_at'])->format('d.m.Y H:i') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Documents History Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fw-bold fs-16">
                            <i class="feather-file-text text-success me-2"></i> Юкланган ҳужжатлар ва версиялар
                        </h5>
                        <span class="badge bg-light text-dark">{{ count($task['documents_history'] ?? []) }} та версия</span>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($task['documents_history']) && count($task['documents_history']) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Версия</th>
                                            <th>Файл номи</th>
                                            <th>Юкланган вақти</th>
                                            <th>Ҳолати</th>
                                            <th class="pe-4 text-end">Юклаб олиш</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($task['documents_history'] as $doc)
                                            <tr>
                                                <td class="ps-4">
                                                    <span class="badge bg-primary-subtle text-primary fw-bold">
                                                        v{{ $doc['version'] ?? 1 }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="feather-file text-muted fs-16"></i>
                                                        <span class="fw-semibold text-dark">{{ $doc['file_name'] ?? 'Fayl' }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-muted fs-12">
                                                    {{ !empty($doc['uploaded_at']) ? \Carbon\Carbon::parse($doc['uploaded_at'])->format('d.m.Y H:i') : '—' }}
                                                </td>
                                                <td>
                                                    @php $docSt = $doc['status'] ?? 'kutilmoqda'; @endphp
                                                    @if($docSt === 'tasdiqlandi')
                                                        <span class="badge bg-success-subtle text-success">Тасдиқланган</span>
                                                    @elseif($docSt === 'qaytarildi')
                                                        <span class="badge bg-danger-subtle text-danger">Қайтарилган</span>
                                                    @else
                                                        <span class="badge bg-secondary-subtle text-secondary">Кутилмоқда</span>
                                                    @endif
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <a href="{{ route('admin.izlanuvchilar.documents.download', $doc['id']) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="feather-download me-1"></i> Юклаб олиш
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="feather-file-minus fs-40 d-block mb-2 text-muted"></i>
                                Талабгор ҳали ушбу вазифа бўйича биронта ҳам ҳужжат юкламаган.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Comments / Feedback History Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fw-bold fs-16">
                            <i class="feather-message-square text-warning me-2"></i> Изоҳлар ва қайтариш сабаблари
                        </h5>
                        <span class="badge bg-light text-dark">{{ count($task['comments_history'] ?? []) }} та</span>
                    </div>
                    <div class="card-body">
                        @if(!empty($task['comments_history']) && count($task['comments_history']) > 0)
                            <div class="timeline-container">
                                @foreach($task['comments_history'] as $c)
                                    <div class="p-3 mb-3 bg-light rounded border-start border-3 border-danger">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <strong class="text-dark fs-13">
                                                <i class="feather-user me-1 text-primary"></i> {{ $c['author'] ?? 'Масъул ходим / Котиб' }}
                                            </strong>
                                            <small class="text-muted fs-11">
                                                {{ !empty($c['created_at']) ? \Carbon\Carbon::parse($c['created_at'])->format('d.m.Y H:i') : '' }}
                                            </small>
                                        </div>
                                        <p class="mb-0 text-muted fs-13">
                                            {{ $c['comment'] ?? '' }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0 text-center py-3">
                                <i class="feather-message-circle me-1"></i> Ҳозирча ҳеч қандай эътироз ёки изоҳ қолдирилмаган.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Applicant details & Action forms -->
            <div class="col-12 col-lg-4">
                <!-- Applicant Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold fs-16">
                            <i class="feather-user text-primary me-2"></i> Талабгор маълумотлари
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center pb-3 border-bottom">
                            <div class="avatar-text avatar-xl bg-primary text-white rounded-circle mx-auto mb-2 fs-20 fw-bold" style="width: 58px; height: 58px; display: flex; align-items: center; justify-content: center;">
                                {{ mb_substr($task['project']['applicant_name'] ?? 'U', 0, 1) }}
                            </div>
                            <h6 class="fw-bold text-dark mb-1">{{ $task['project']['applicant_name'] ?? 'Номаълум' }}</h6>
                            <a href="tel:{{ $task['project']['applicant_phone'] ?? '' }}" class="text-decoration-none text-muted fs-13">
                                <i class="feather-phone me-1 text-primary"></i>{{ $task['project']['applicant_phone'] ?? '—' }}
                            </a>
                        </div>
                        <div class="pt-3">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <span class="text-muted fs-13">Ариза тури:</span>
                                <span class="fw-semibold text-dark fs-13">{{ $task['project']['application_type'] ?? '—' }}</span>
                            </div>
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <span class="text-muted fs-13">Лойиҳа ҳолати:</span>
                                <span class="badge bg-warning-subtle text-warning">{{ $task['project']['status'] ?? 'жараёнда' }}</span>
                            </div>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-1 fs-12">
                                    <span class="text-muted">Умумий ўзлаштириш:</span>
                                    <span class="fw-bold text-dark">{{ $task['project']['progress_percent'] ?? 0 }}%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $task['project']['progress_percent'] ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Decision / Action Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold fs-16">
                            <i class="feather-check-circle text-success me-2"></i> Қарор қабул қилиш
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                            $hasDocs = !empty($task['documents_history']) && count($task['documents_history']) > 0;
                        @endphp

                        @if(!$hasDocs)
                            <div class="alert alert-warning fs-12 mb-0">
                                <i class="feather-alert-triangle me-1"></i> Ҳужжат юкланмаганлиги сабабли қарор қабул қилиш имконсиз.
                            </div>
                        @else
                            <!-- Approve button & form -->
                            <form method="POST" action="{{ route('admin.izlanuvchilar.tasks.approve', $task['id']) }}" class="mb-3">
                                @csrf
                                <div class="p-3 bg-light rounded mb-3 border">
                                    <div class="fw-semibold text-dark mb-1">
                                        <i class="feather-check-circle text-success me-1"></i> Ҳужжатни тасдиқлаш
                                    </div>
                                    <p class="text-muted fs-12 mb-2">
                                        Ҳужжат талабларга тўлиқ жавоб берса, уни тасдиқланг. Талабгорга SMS хабар боради ва прогресс ошади.
                                    </p>
                                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Ҳақиқатан ҳам ушбу вазифани тасдиқлайсизми?');">
                                        <i class="feather-check me-1"></i> Тасдиқлаш (Approve)
                                    </button>
                                </div>
                            </form>

                            <!-- Reject button & form -->
                            <form method="POST" action="{{ route('admin.izlanuvchilar.tasks.reject', $task['id']) }}">
                                @csrf
                                <div class="p-3 bg-light rounded border">
                                    <div class="fw-semibold text-dark mb-1">
                                        <i class="feather-x-circle text-danger me-1"></i> Қайтариш / Рад этиш
                                    </div>
                                    <p class="text-muted fs-12 mb-2">
                                        Камчилик бўлса, изоҳ ёзиб қайтаринг. Талабгор янгитдан тўғрилаб юклайди.
                                    </p>
                                    <div class="mb-2">
                                        <textarea name="comment" class="form-control form-control-sm" rows="3" placeholder="Қайтариш сабабини аниқ ёзинг..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="feather-send me-1"></i> Қайтариш (Reject)
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
