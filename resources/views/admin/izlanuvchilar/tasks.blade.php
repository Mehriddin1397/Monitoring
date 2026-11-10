@extends('layouts.admin')

@section('title', 'Кўриб чиқиш (Вазифалар)')

@section('content')
<div class="nxl-content d-flex flex-column h-100">
    <!-- [ Page Header ] start -->
    <div class="page-header py-3 px-3 px-lg-4 shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-text avatar-lg bg-primary text-white rounded-circle shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                    <i class="feather-check-circle fs-24"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="mb-1 text-white fw-bold fs-22">Кўриб чиқиш (Вазифалар)</h3>
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
                        Талабгорлар юклаган диссертация, автореферат ва бошқа ҳужжатларни текшириш ҳамда қарор қабул қилиш
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('admin.izlanuvchilar.dashboard') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-pie-chart me-1"></i> Бошқарув панели
                </a>
                <a href="{{ route('admin.izlanuvchilar.researchers') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-users me-1"></i> Изланувчилар
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

        <!-- [ Filter Tabs & Search ] start -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <!-- Status Tabs -->
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.izlanuvchilar.tasks', ['search' => $search]) }}"
                           class="btn btn-sm {{ $status === '' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Ҳаммаси
                        </a>
                        <a href="{{ route('admin.izlanuvchilar.tasks', ['status' => 'jarayonda', 'search' => $search]) }}"
                           class="btn btn-sm {{ $status === 'jarayonda' ? 'btn-warning text-dark' : 'btn-outline-secondary' }}">
                            Жараёнда (Кўриб чиқиш)
                        </a>
                        <a href="{{ route('admin.izlanuvchilar.tasks', ['status' => 'kutilmoqda', 'search' => $search]) }}"
                           class="btn btn-sm {{ $status === 'kutilmoqda' ? 'btn-info text-white' : 'btn-outline-secondary' }}">
                            Кутилмоқда
                        </a>
                        <a href="{{ route('admin.izlanuvchilar.tasks', ['status' => 'tasdiqlandi', 'search' => $search]) }}"
                           class="btn btn-sm {{ $status === 'tasdiqlandi' ? 'btn-success' : 'btn-outline-secondary' }}">
                            Тасдиқланди
                        </a>
                        <a href="{{ route('admin.izlanuvchilar.tasks', ['status' => 'qaytarildi', 'search' => $search]) }}"
                           class="btn btn-sm {{ $status === 'qaytarildi' ? 'btn-danger' : 'btn-outline-secondary' }}">
                            Қайтарилди
                        </a>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.izlanuvchilar.tasks') }}" class="d-flex gap-2" style="min-width: 280px;">
                        @if($status !== '')
                            <input type="hidden" name="status" value="{{ $status }}">
                        @endif
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="feather-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Талабгор ёки вазифа номи..." value="{{ $search }}">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">
                            Қидириш
                        </button>
                        @if($search !== '')
                            <a href="{{ route('admin.izlanuvchilar.tasks', ['status' => $status]) }}" class="btn btn-sm btn-outline-secondary" title="Тозалаш">
                                <i class="feather-x"></i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <!-- [ Filter Tabs & Search ] end -->

        <!-- [ Tasks Table ] start -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title mb-0 fw-bold fs-16">
                    Вазифалар рўйхати (Жами: {{ $pagination['total'] ?? count($tasks) }} та)
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
                                <th>Талабгор</th>
                                <th>Вазифа / Ҳужжат тури</th>
                                <th>Босқич</th>
                                <th>Охирги юкланган ҳужжат</th>
                                <th class="text-center">Ҳолати</th>
                                <th class="pe-4 text-end" style="min-width: 180px;">Амаллар</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $t)
                                <tr>
                                    <td class="ps-4 text-muted fw-semibold">
                                        #{{ $t['id'] }}
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $t['project']['applicant_name'] ?? 'Номаълум' }}</div>
                                        <a href="tel:{{ $t['project']['applicant_phone'] ?? '' }}" class="text-decoration-none text-muted fs-12">
                                            <i class="feather-phone me-1 text-primary"></i>{{ $t['project']['applicant_phone'] ?? '—' }}
                                        </a>
                                        @if(!empty($t['project']['application_type']))
                                            <div class="text-muted fs-11 mt-1">
                                                <i class="feather-folder me-1"></i>{{ $t['project']['application_type'] }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $t['name'] }}</div>
                                        @if(!empty($t['description']))
                                            <div class="text-muted fs-12">{{ $t['description'] }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($t['stage']))
                                            <span class="badge bg-light text-dark border">
                                                {{ $t['stage']['order'] ?? 1 }}-босқич: {{ $t['stage']['name'] ?? '' }}
                                            </span>
                                        @else
                                            <span class="text-muted fs-12">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($t['latest_document']))
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-text avatar-xs bg-light text-danger rounded">
                                                    <i class="feather-file fs-13"></i>
                                                </div>
                                                <div>
                                                    <a href="{{ route('admin.izlanuvchilar.documents.download', $t['latest_document']['id']) }}"
                                                       class="fw-semibold text-primary text-decoration-none d-block fs-13"
                                                       title="Юклаб олиш">
                                                        <i class="feather-download me-1"></i>{{ $t['latest_document']['file_name'] }}
                                                    </a>
                                                    <small class="text-muted fs-11">
                                                        v{{ $t['latest_document']['version'] ?? 1 }} • {{ \Carbon\Carbon::parse($t['latest_document']['uploaded_at'])->format('d.m.Y H:i') }}
                                                    </small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted fs-12">
                                                <i class="feather-clock me-1"></i> Ҳали юкланмаган
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $st = $t['status'] ?? 'kutilmoqda';
                                        @endphp
                                        @if($st === 'tasdiqlandi')
                                            <span class="badge bg-success-subtle text-success px-2 py-1">
                                                <i class="feather-check-circle me-1"></i> Тасдиқланди
                                            </span>
                                        @elseif($st === 'jarayonda')
                                            <span class="badge bg-warning-subtle text-warning px-2 py-1">
                                                <i class="feather-loader me-1"></i> Жараёнда
                                            </span>
                                        @elseif($st === 'qaytarildi')
                                            <span class="badge bg-danger-subtle text-danger px-2 py-1">
                                                <i class="feather-x-circle me-1"></i> Қайтарилди
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1">
                                                <i class="feather-clock me-1"></i> Кутилмоқда
                                            </span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.izlanuvchilar.tasks.detail', $t['id']) }}"
                                               class="btn btn-outline-primary"
                                               title="Батафсил кўриш">
                                                <i class="feather-eye"></i>
                                            </a>

                                            @if(!empty($t['latest_document']))
                                                <!-- Approve button -->
                                                <button type="button"
                                                        class="btn btn-outline-success"
                                                        onclick="openApproveModal({{ $t['id'] }}, '{{ addslashes($t['name']) }}', '{{ addslashes($t['project']['applicant_name'] ?? '') }}')"
                                                        title="Тасдиқлаш">
                                                    <i class="feather-check"></i>
                                                </button>

                                                <!-- Reject button -->
                                                <button type="button"
                                                        class="btn btn-outline-danger"
                                                        onclick="openRejectModal({{ $t['id'] }}, '{{ addslashes($t['name']) }}', '{{ addslashes($t['project']['applicant_name'] ?? '') }}')"
                                                        title="Қайтариш / Рад этиш">
                                                    <i class="feather-x"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="feather-check-square fs-40 d-block mb-2 text-muted"></i>
                                        Кўрсатилган шартларга мос вазифалар топилмади.
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
                                <a class="page-link" href="{{ route('admin.izlanuvchilar.tasks', array_merge(request()->query(), ['page' => $pagination['current_page'] - 1])) }}">
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
                                    <a class="page-link" href="{{ route('admin.izlanuvchilar.tasks', array_merge(request()->query(), ['page' => 1])) }}">1</a>
                                </li>
                                @if($startPage > 2)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                            @endif

                            @for($p = $startPage; $p <= $endPage; $p++)
                                <li class="page-item {{ $p == $pagination['current_page'] ? 'active' : '' }}">
                                    <a class="page-link" href="{{ route('admin.izlanuvchilar.tasks', array_merge(request()->query(), ['page' => $p])) }}">{{ $p }}</a>
                                </li>
                            @endfor

                            @if($endPage < $pagination['last_page'])
                                @if($endPage < $pagination['last_page'] - 1)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ route('admin.izlanuvchilar.tasks', array_merge(request()->query(), ['page' => $pagination['last_page']])) }}">{{ $pagination['last_page'] }}</a>
                                </li>
                            @endif

                            {{-- Keyingi --}}
                            <li class="page-item {{ $pagination['current_page'] >= $pagination['last_page'] ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ route('admin.izlanuvchilar.tasks', array_merge(request()->query(), ['page' => $pagination['current_page'] + 1])) }}">
                                    <i class="feather-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif
                <!-- [ Custom Pagination ] end -->
            </div>
        </div>
        <!-- [ Tasks Table ] end -->
    </div>
</div>

<!-- Modal: Approve Confirmation -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="approveForm" method="POST" action="">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="feather-check-circle me-1"></i> Ҳужжатни тасдиқлаш
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-2">Ҳақиқатан ҳам ушбу вазифа бўйича тақдим этилган ҳужжатни тасдиқлайсизми?</p>
                    <div class="bg-light p-3 rounded mb-3">
                        <div class="fs-13 text-muted mb-1">Талабгор: <strong class="text-dark" id="approveApplicantName"></strong></div>
                        <div class="fs-13 text-muted">Вазифа: <strong class="text-dark" id="approveTaskName"></strong></div>
                    </div>
                    <div class="alert alert-info fs-12 mb-0">
                        <i class="feather-info me-1"></i> Тасдиқлангандан сўнг талабгорга SMS-хабарнома юборилади ва лойиҳа ўзлаштириш кўрсаткичи автоматик қайта ҳисобланади.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Бекор қилиш</button>
                    <button type="submit" class="btn btn-success">
                        <i class="feather-check me-1"></i> Тасдиқлаш
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Reject with Comment -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="rejectForm" method="POST" action="">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="feather-x-circle me-1"></i> Ҳужжатни қайтариш (Рад этиш)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="bg-light p-3 rounded mb-3">
                        <div class="fs-13 text-muted mb-1">Талабгор: <strong class="text-dark" id="rejectApplicantName"></strong></div>
                        <div class="fs-13 text-muted">Вазифа: <strong class="text-dark" id="rejectTaskName"></strong></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Қайтариш сабаби (Изоҳ) <span class="text-danger">*</span></label>
                        <textarea name="comment" class="form-control" rows="4" placeholder="Талабгор камчиликларни тўғрилаши учун аниқ изоҳ қолдиринг (масалан: муҳр ёки имзо қўйилмаган)..." required></textarea>
                        <small class="text-muted fs-11">Ушбу изоҳ талабгорнинг шахсий кабинетида кўринади ва унга SMS орқали хабар берилади.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Бекор қилиш</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="feather-send me-1"></i> Қайтариш ва SMS юбориш
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openApproveModal(id, taskName, applicantName) {
    var form = document.getElementById('approveForm');
    form.action = '{{ url("admin/izlanuvchilar/tasks") }}/' + id + '/approve';
    document.getElementById('approveTaskName').textContent = taskName;
    document.getElementById('approveApplicantName').textContent = applicantName;
    var modal = new bootstrap.Modal(document.getElementById('approveModal'));
    modal.show();
}

function openRejectModal(id, taskName, applicantName) {
    var form = document.getElementById('rejectForm');
    form.action = '{{ url("admin/izlanuvchilar/tasks") }}/' + id + '/reject';
    document.getElementById('rejectTaskName').textContent = taskName;
    document.getElementById('rejectApplicantName').textContent = applicantName;
    var modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>
@endsection
