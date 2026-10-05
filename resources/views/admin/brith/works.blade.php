@extends('layouts.admin')

@section('title', 'Ходимлар ижоди')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->
        <div class="page-header position-fixed">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Ходимлар ижоди (Шеър, ҳикоя, илмий таърифлар)</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Туғилган кун</a></li>
                    <li class="breadcrumb-item">Ижодий ишлар</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('brith') }}" target="_blank" class="btn btn-outline-warning">
                            <i class="feather-external-link me-2"></i>
                            <span>Экранни кўриш (/brith)</span>
                        </a>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                            <i class="feather-users me-2"></i>
                            <span>Ходимлар рўйхати</span>
                        </a>
                        <a href="{{ route('group-photos.index') }}" class="btn btn-info text-white">
                            <i class="feather-image me-2"></i>
                            <span>Гуруҳ расмлари</span>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas"
                           data-bs-target="#createWorkOffcanvas">
                            <i class="feather-plus me-2"></i>
                            <span>Янги ижод қўшиш</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Институт ходимларининг ижодий ишлари ва илмий фикрлари</h5>
                            <p class="text-muted mb-0 fs-12">Ушбу ижодий намуналар туғилган кун бўлмаган кунларда асосий мониторда турли дизайн ва анимациялар билан намойиш этилади.</p>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Муаллиф</th>
                                        <th>Ижод тури</th>
                                        <th>Сарлавҳа</th>
                                        <th>Дизайн услуби</th>
                                        <th>Ҳолати</th>
                                        <th class="text-end">Амаллар</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($works as $index => $work)
                                        <tr class="single-item">
                                            <th>{{ $index + 1 }}</th>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($work->employee && $work->employee->photo)
                                                        <img src="{{ asset('storage/' . $work->employee->photo) }}"
                                                             alt="{{ $work->employee->full_name }}"
                                                             width="38" height="38" class="rounded-circle object-fit-cover shadow-sm">
                                                    @else
                                                        <div class="avatar-text avatar-sm bg-soft-primary text-primary rounded-circle">
                                                            {{ mb_substr($work->employee->full_name ?? 'X', 0, 1) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong class="d-block text-truncate" style="max-width: 180px;">{{ $work->employee->full_name ?? '—' }}</strong>
                                                        <small class="text-muted text-truncate d-block" style="max-width: 180px;">{{ $work->employee->position ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($work->type === 'poem')
                                                    <span class="badge bg-soft-info text-info"><i class="feather-feather me-1"></i> Шеър</span>
                                                @elseif($work->type === 'story')
                                                    <span class="badge bg-soft-warning text-warning"><i class="feather-book me-1"></i> Ҳикоя / Бадиа</span>
                                                @elseif($work->type === 'scientific')
                                                    <span class="badge bg-soft-success text-success"><i class="feather-award me-1"></i> Илмий таъриф</span>
                                                @else
                                                    <span class="badge bg-soft-secondary text-secondary"><i class="feather-message-circle me-1"></i> Ҳикмат</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $work->title }}</strong>
                                                @if($work->excerpt)
                                                    <small class="text-muted d-block text-truncate" style="max-width: 250px;">{{ $work->excerpt }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($work->layout_theme === 'lyric')
                                                    <span class="badge bg-gradient text-white" style="background-color: #6366f1;">📜 Лирик шеърият</span>
                                                @elseif($work->layout_theme === 'book')
                                                    <span class="badge bg-gradient text-white" style="background-color: #8b5cf6;">📖 Китобий / Насрий</span>
                                                @elseif($work->layout_theme === 'academic')
                                                    <span class="badge bg-gradient text-white" style="background-color: #0ea5e9;">🔬 Илмий академик</span>
                                                @else
                                                    <span class="badge bg-gradient text-white" style="background-color: #ec4899;">🎴 Замонавий карта</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($work->is_active)
                                                    <span class="badge bg-success">Фаол (экранда)</span>
                                                @else
                                                    <span class="badge bg-secondary">Ўчирилган</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="hstack gap-2 justify-content-end">
                                                    <a href="javascript:void(0)" data-bs-toggle="offcanvas"
                                                       data-bs-target="#editWorkOffcanvas{{ $work->id }}"
                                                       class="avatar-text avatar-md" title="Таҳрирлаш">
                                                        <i class="feather feather-edit-3"></i>
                                                    </a>
                                                    <form action="{{ route('employee-works.destroy', $work->id) }}"
                                                          method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="avatar-text avatar-md text-danger"
                                                                onclick="return confirm('Ижодий ишни ўчиришни тасдиқлайсизми?')">
                                                            <i class="feather feather-trash-2"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                Ҳозирча бирорта ижодий иш қўшилмаган. «Янги ижод қўшиш» тугмасини босинг.
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
    </div>

    {{-- CREATE OFFCANVAS MODAL --}}
    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="createWorkOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title">Янги ижодий иш қўшиш</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('employee-works.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Муаллиф (ходим): <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">— Ходимни танланг —</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->full_name }} ({{ $emp->position }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ижод тури: <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="poem" selected>📜 Шеър</option>
                            <option value="story">📖 Ҳикоя / Бадиа</option>
                            <option value="scientific">🔬 Илмий таъриф / Мақола</option>
                            <option value="quote">💬 Ҳикматли сўз / Иқтибос</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Сарлавҳа: <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Асар номи ёки мавзуси" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Экранда кўриниш дизайни (шаблон): <span class="text-danger">*</span></label>
                        <select name="layout_theme" class="form-select" required>
                            <option value="lyric" selected>📜 Лирик шеърият (каллиграфия, марказлашган)</option>
                            <option value="book">📖 Китобий / Насрий (нафис икки устунли)</option>
                            <option value="academic">🔬 Илмий академик (замонавий инфо-карта)</option>
                            <option value="card">🎴 Замонавий журнал карточкаси</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Қўшимча муқова расми (ихтиёрий):</label>
                        <input type="file" name="cover_image" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Қисқача изоҳ ёки эпиграф (ихтиёрий):</label>
                        <input type="text" name="excerpt" class="form-control" placeholder="Масалан: «Ватан мадҳи» туркумидан ёки «Криминалистика асослари» монографиясидан">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Матн / Шеър сатрлари: <span class="text-danger">*</span></label>
                        <textarea name="content" rows="8" class="form-control font-monospace" placeholder="Шеър ёки илмий фикр матнини сатрларга ажратиб ёзинг..." required></textarea>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="create_is_active" value="1" checked>
                            <label class="form-check-label" for="create_is_active">Экранда фаол намойиш этилсин</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Сақлаш ва экранга чиқариш</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT OFFCANVAS MODALS --}}
    @foreach($works as $work)
        <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="editWorkOffcanvas{{ $work->id }}">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title">Ижодий ишни таҳрирлаш #{{ $work->id }}</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form action="{{ route('employee-works.update', $work->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Муаллиф (ходим): <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select" required>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ $work->employee_id == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->full_name }} ({{ $emp->position }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ижод тури: <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="poem" {{ $work->type == 'poem' ? 'selected' : '' }}>📜 Шеър</option>
                                <option value="story" {{ $work->type == 'story' ? 'selected' : '' }}>📖 Ҳикоя / Бадиа</option>
                                <option value="scientific" {{ $work->type == 'scientific' ? 'selected' : '' }}>🔬 Илмий таъриф / Мақола</option>
                                <option value="quote" {{ $work->type == 'quote' ? 'selected' : '' }}>💬 Ҳикматли сўз / Иқтибос</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Сарлавҳа: <span class="text-danger">*</span></label>
                            <input type="text" name="title" value="{{ $work->title }}" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Экранда кўриниш дизайни (шаблон): <span class="text-danger">*</span></label>
                            <select name="layout_theme" class="form-select" required>
                                <option value="lyric" {{ $work->layout_theme == 'lyric' ? 'selected' : '' }}>📜 Лирик шеърият (каллиграфия, марказлашган)</option>
                                <option value="book" {{ $work->layout_theme == 'book' ? 'selected' : '' }}>📖 Китобий / Насрий (нафис икки устунли)</option>
                                <option value="academic" {{ $work->layout_theme == 'academic' ? 'selected' : '' }}>🔬 Илмий академик (замонавий инфо-карта)</option>
                                <option value="card" {{ $work->layout_theme == 'card' ? 'selected' : '' }}>🎴 Замонавий журнал карточкаси</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Қўшимча муқова расми:</label>
                            <input type="file" name="cover_image" class="form-control">
                            @if($work->cover_image)
                                <small class="text-success d-block mt-1">Жорий расм юкланган</small>
                            @endif
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Қисқача изоҳ ёки эпиграф:</label>
                            <input type="text" name="excerpt" value="{{ $work->excerpt }}" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Матн / Шеър сатрлари: <span class="text-danger">*</span></label>
                            <textarea name="content" rows="8" class="form-control font-monospace" required>{{ $work->content }}</textarea>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active{{ $work->id }}" value="1" {{ $work->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="edit_is_active{{ $work->id }}">Экранда фаол намойиш этилсин</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Ўзгаришларни сақлаш</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

@endsection
