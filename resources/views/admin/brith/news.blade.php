@extends('layouts.admin')

@section('title', 'Сўнгги янгиликлар')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->
        <div class="page-header position-fixed">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Сўнгги янгиликлар ва воқеалар</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Туғилган кун</a></li>
                    <li class="breadcrumb-item">Янгиликлар</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('brith') }}" target="_blank" class="btn btn-outline-warning">
                            <i class="feather-external-link me-2"></i>
                            <span>Экранни кўриш (/brith)</span>
                        </a>
                        <a href="{{ route('employee-works.index') }}" class="btn btn-info text-white">
                            <i class="feather-book-open me-2"></i>
                            <span>Ходимлар ижоди</span>
                        </a>
                        <a href="{{ route('group-photos.index') }}" class="btn btn-secondary">
                            <i class="feather-image me-2"></i>
                            <span>Гуруҳ расмлари</span>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas"
                           data-bs-target="#createNewsOffcanvas">
                            <i class="feather-plus me-2"></i>
                            <span>Янгилик қўшиш</span>
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

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Институт ҳаётига оид сўнгги янгиликлар</h5>
                            <p class="text-muted mb-0 fs-12">Бу ерда қўшилган янгиликлар (матн ва бир нечта фотосуратлар билан) асосий экранда (/brith) автоматик намойиш этилади.</p>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Расмлар</th>
                                        <th>Сарлавҳа</th>
                                        <th>Категория</th>
                                        <th>Сана</th>
                                        <th>Ҳолати</th>
                                        <th class="text-end">Амаллар</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($newsList as $index => $item)
                                        <tr class="single-item">
                                            <th>{{ $index + 1 }}</th>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    @if(is_array($item->images) && count($item->images) > 0)
                                                        <div class="position-relative">
                                                            <img src="{{ asset('storage/' . $item->images[0]) }}"
                                                                 alt="{{ $item->title }}"
                                                                 width="54" height="42" class="rounded object-fit-cover shadow-sm border">
                                                            @if(count($item->images) > 1)
                                                                <span class="position-absolute bottom-0 end-0 badge bg-dark text-warning p-1" style="font-size: 10px;">
                                                                    +{{ count($item->images) - 1 }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="avatar-text avatar-md bg-soft-secondary text-secondary rounded">
                                                            <i class="feather-image"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <strong class="d-block" style="max-width: 320px;">{{ $item->title }}</strong>
                                                <small class="text-muted d-block text-truncate" style="max-width: 320px;">{{ Str::limit($item->content, 80) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-soft-primary text-primary">
                                                    {{ $item->category ?: 'Институт янгилиги' }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $item->published_date ? $item->published_date->format('d.m.Y') : '—' }}
                                                </small>
                                            </td>
                                            <td>
                                                <form action="{{ route('news.toggle', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $item->is_active ? 'btn-success' : 'btn-secondary' }}" title="Ҳолатни ўзгартириш">
                                                        {{ $item->is_active ? 'Фаол (экранда)' : 'Ўчирилган' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="hstack gap-2 justify-content-end">
                                                    <a href="javascript:void(0)" data-bs-toggle="offcanvas"
                                                       data-bs-target="#editNewsOffcanvas{{ $item->id }}"
                                                       class="avatar-text avatar-md" title="Таҳрирлаш">
                                                        <i class="feather feather-edit-3"></i>
                                                    </a>
                                                    <form action="{{ route('news.destroy', $item->id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Ҳақиқатан ҳам ушбу янгиликни ўчирмоқчимисиз?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="avatar-text avatar-md text-danger border-0 bg-transparent" title="Ўчириш">
                                                            <i class="feather feather-trash-2"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <i class="feather-file-text fs-1 d-block mb-2 text-secondary"></i>
                                                Ҳозирча янгиликлар қўшилмаган. «Янгилик қўшиш» тугмаси орқали биринчи янгиликни киритинг.
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
        <!-- [ Main Content ] end -->
    </div>

    {{-- CREATE OFFCANVAS MODAL --}}
    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="createNewsOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title">Янги янгилик қўшиш</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Сарлавҳа: <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Янгилик сарлавҳаси" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Категория / Бўлим:</label>
                        <input type="text" name="category" class="form-control" placeholder="Масалан: Институт ҳаёти, Халқаро ҳамкорлик, Анжуман">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Сана:</label>
                        <input type="date" name="published_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Расмлар (Бир нечта танлашингиз мумкин): <span class="text-danger">*</span></label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*" required>
                        <small class="text-muted">Ctrl тугмасини босиб бир вақтнинг ўзида бир нечта фотосуратни танланг.</small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Янгилик матни: <span class="text-danger">*</span></label>
                        <textarea name="content" rows="9" class="form-control" placeholder="Янгилик ҳақида батафсил маълумот..." required></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Кўриниш тартиби:</label>
                        <input type="number" name="order" class="form-control" value="0">
                    </div>

                    <div class="col-md-6 mb-4 d-flex align-items-center pt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="create_news_active" value="1" checked>
                            <label class="form-check-label" for="create_news_active">Экранда фаол намойиш этилсин</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">Сақлаш ва экранга чиқариш</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT OFFCANVAS MODALS --}}
    @foreach($newsList as $item)
        <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="editNewsOffcanvas{{ $item->id }}">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title">Янгиликни таҳрирлаш #{{ $item->id }}</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form action="{{ route('news.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Сарлавҳа: <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ $item->title }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Категория / Бўлим:</label>
                            <input type="text" name="category" class="form-control" value="{{ $item->category }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Сана:</label>
                            <input type="date" name="published_date" class="form-control" value="{{ $item->published_date ? $item->published_date->format('Y-m-d') : '' }}">
                        </div>

                        {{-- Current Photos --}}
                        @if(is_array($item->images) && count($item->images) > 0)
                            <div class="col-md-12 mb-3">
                                <label class="form-label d-block">Мавжуд расмлар (ўчириш учун белгиланг):</label>
                                <div class="d-flex flex-wrap gap-2 p-2 bg-light rounded">
                                    @foreach($item->images as $img)
                                        <div class="position-relative border rounded p-1 bg-white text-center">
                                            <img src="{{ asset('storage/' . $img) }}" width="80" height="60" class="rounded object-fit-cover d-block mb-1">
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="checkbox" name="removed_images[]" value="{{ $img }}" id="rm_{{ md5($img) }}">
                                                <label class="form-check-label text-danger fs-11" for="rm_{{ md5($img) }}">Ўчириш</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Янги расмлар қўшиш (ихтиёрий):</label>
                            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Янгилик матни: <span class="text-danger">*</span></label>
                            <textarea name="content" rows="9" class="form-control" required>{{ $item->content }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Кўриниш тартиби:</label>
                            <input type="number" name="order" class="form-control" value="{{ $item->order }}">
                        </div>

                        <div class="col-md-6 mb-4 d-flex align-items-center pt-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="edit_news_active_{{ $item->id }}" value="1" {{ $item->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="edit_news_active_{{ $item->id }}">Экранда фаол намойиш этилсин</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Ўзгаришларни сақлаш</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

@endsection
