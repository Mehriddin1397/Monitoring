@extends('layouts.admin')

@section('title', 'Сабаблилар ва таътиллар — Турникет')

@section('content')
<div class="nxl-content d-flex flex-column h-100">
    <!-- [ Page Header ] start -->
    <div class="page-header py-3 px-3 px-lg-4 shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-text avatar-lg bg-warning text-dark rounded-circle shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                    <i class="feather-check-square fs-24"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="mb-1 text-white fw-bold fs-22">Сабаблилар ва таътиллар</h3>
                        <span class="badge bg-warning text-dark text-uppercase px-2 py-1 fs-11">
                            ЖАМИ: {{ count($leaves) }} ТА
                        </span>
                    </div>
                    <p class="fs-14 text-white-50 mb-0">
                        Кечиккан ёки келмаган ходимларга расмий сабаб (рухсатнома, хизмат сафари, касаллик, таътил) бириктириш
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-primary" onclick="openExcuseModal('', '')">
                    <i class="feather-plus-circle me-1"></i> Янги сабаб киритиш
                </button>
                <a href="{{ route('admin.turniket.dashboard') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-grid me-1"></i> Панел
                </a>
                <a href="{{ route('admin.turniket.attendance') }}" class="btn btn-sm btn-outline-light">
                    <i class="feather-list me-1"></i> Давомат журнали
                </a>
            </div>
        </div>
    </div>
    <!-- [ Page Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content p-3 p-lg-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="feather-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="feather-alert-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.turniket.leaves') }}" method="GET" class="row g-2 align-items-end">
                    <!-- Type -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fs-12 fw-semibold text-muted mb-1">Сабаб тури:</label>
                        <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Барчаси</option>
                            <option value="late_excuse" {{ request('type') === 'late_excuse' ? 'selected' : '' }}>Кечикиш сабаби / рухсатнома</option>
                            <option value="business_trip" {{ request('type') === 'business_trip' ? 'selected' : '' }}>Хизмат сафари (командировка)</option>
                            <option value="vacation" {{ request('type') === 'vacation' ? 'selected' : '' }}>Меҳнат таътили</option>
                            <option value="sick_leave" {{ request('type') === 'sick_leave' ? 'selected' : '' }}>Касаллик варақаси (больничный)</option>
                            <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>Бошқа сабаблар</option>
                        </select>
                    </div>

                    <!-- Date -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fs-12 fw-semibold text-muted mb-1">Сана:</label>
                        <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                    </div>

                    <!-- Search Employee -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label fs-12 fw-semibold text-muted mb-1">Ходим қидириш:</label>
                        <input type="text" name="employee_filter" class="form-control form-control-sm" 
                               placeholder="Ф.И.Ш ёки ID..." value="{{ request('employee_filter') }}">
                    </div>

                    <!-- Submit -->
                    <div class="col-12 col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="feather-search me-1"></i> Филтрлаш
                        </button>
                        <a href="{{ route('admin.turniket.leaves') }}" class="btn btn-sm btn-light border" title="Тозалаш">
                            <i class="feather-x"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Leaves Table Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="fs-12 text-uppercase text-muted">
                                <th style="width: 50px;">#</th>
                                <th>Ходим</th>
                                <th>ID рақами</th>
                                <th>Сабаб тури</th>
                                <th>Муддати</th>
                                <th>Изоҳ / Сабаб матни</th>
                                <th>Ҳужжат рақами</th>
                                <th>Ҳолати</th>
                                <th class="text-end" style="width: 80px;">Амал</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $idx => $leave)
                                <tr>
                                    <td class="text-muted fs-12">{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if(!empty($leave['photo_url']))
                                                <img src="{{ $leave['photo_url'] }}" alt="{{ $leave['employee_name'] ?? '' }}" 
                                                     class="rounded-circle object-fit-cover shadow-sm" style="width: 42px; height: 42px;"
                                                     onerror="this.onerror=null; this.src='/assets/images/avatar/default.png';">
                                            @else
                                                <div class="avatar-text avatar-md bg-soft-primary text-primary rounded-circle">
                                                    <i class="feather-user"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark fs-14">{{ $leave['employee_name'] ?? 'Ходим' }}</div>
                                                <small class="text-muted">{{ $leave['department'] ?? 'Институт' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark font-monospace fs-12">{{ $leave['employee_no'] ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $t = $leave['type'] ?? 'other';
                                            $badgeClass = match($t) {
                                                'late_excuse'   => 'bg-soft-warning text-warning',
                                                'business_trip' => 'bg-soft-primary text-primary',
                                                'vacation'      => 'bg-soft-success text-success',
                                                'sick_leave'    => 'bg-soft-danger text-danger',
                                                default         => 'bg-soft-secondary text-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-2 py-1 fs-12">
                                            {{ $leave['type_label'] ?? $t }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="font-monospace fs-13 text-dark">
                                            {{ $leave['start_date'] ?? '-' }}
                                            @if(!empty($leave['end_date']) && $leave['end_date'] !== ($leave['start_date'] ?? ''))
                                                <span class="text-muted"> дан </span> {{ $leave['end_date'] }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-wrap text-dark fs-13" style="max-width: 320px;">
                                            {{ $leave['reason'] ?? '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if(!empty($leave['document_no']))
                                            <span class="badge bg-light text-muted border font-monospace fs-11">
                                                <i class="feather-file me-1"></i>{{ $leave['document_no'] }}
                                            </span>
                                        @else
                                            <span class="text-muted fs-12">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($leave['is_currently_active']))
                                            <span class="badge bg-success px-2 py-1 fs-11">
                                                <span class="pulse-dot"></span> Фаол
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted fs-11">Тугаган</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.turniket.leaves.destroy', $leave['id']) }}" method="POST" 
                                              onsubmit="return confirm('Ҳақиқатан ҳам ушбу сабабни ўчириб, ходимни сабабсиз ҳолатига қайтармоқчимисиз?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger p-1" title="Ўчириш">
                                                <i class="feather-trash-2 fs-14"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="feather-inbox fs-40 d-block mb-2 text-muted"></i>
                                        Ҳозирча бирорта ҳам сабабли ёки таътил ёзуви топилмади.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
                <i class="feather-check-square me-2 text-warning"></i> Ходимга сабаб / таътил киритиш
            </h5>
            <button type="button" class="btn-close btn-close-white" onclick="closeExcuseModal()"></button>
        </div>
        <form action="{{ route('admin.turniket.leaves.store') }}" method="POST">
            @csrf
            <div class="card-body p-4">
                <!-- Employee select -->
                <div class="mb-3">
                    <label class="form-label fs-13 fw-semibold text-muted">Ходимни танланг <span class="text-danger">*</span></label>
                    <select name="employee_no" id="modal_employee_no" class="form-select" required>
                        <option value="">-- Ходимни танланг --</option>
                        @foreach($allEmployees as $emp)
                            <option value="{{ $emp['employee_no'] }}">
                                {{ $emp['fullname'] }} (ID: {{ $emp['employee_no'] }})
                            </option>
                        @endforeach
                    </select>
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
                    <small class="text-muted fs-11">Максимал 500 белги.</small>
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

<script>
function openExcuseModal(employeeNo, type) {
    const overlay = document.getElementById('excuseCustomOverlay');
    if (!overlay) return;

    if (employeeNo) {
        const empSelect = document.getElementById('modal_employee_no');
        if (empSelect) empSelect.value = employeeNo;
    }
    if (type) {
        const typeSelect = document.getElementById('modal_type');
        if (typeSelect) typeSelect.value = type;
    }

    overlay.style.display = 'flex';
}

function closeExcuseModal() {
    const overlay = document.getElementById('excuseCustomOverlay');
    if (overlay) overlay.style.display = 'none';
}

// Close on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeExcuseModal();
});
</script>
@endsection
