@extends('layouts.admin')

@section('title', 'Электрон кутубхона')

@section('content')
<div class="nxl-content d-flex flex-column h-100">
    <!-- [ page-header ] start -->
    <div class="page-header py-3 px-4 bg-white border-bottom shadow-sm">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 w-100">
            <div class="page-header-left d-flex align-items-center gap-3">
                <div class="avatar-text avatar-md bg-soft-success text-success rounded-3">
                    <i class="feather-book-open fs-18"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">Электрон кутубхона (Маълумотлар банки)</h5>
                    <span class="fs-12 text-muted">
                        @if($category)
                            Жорий бўлим: <strong class="text-success">{{ $category->name }}</strong>
                        @else
                            Барча адабиётлар
                        @endif
                    </span>
                </div>
            </div>

            <div class="page-header-right d-flex align-items-center gap-2">
                @auth
                    <a href="javascript:void(0);" class="btn btn-success d-flex align-items-center gap-2" data-bs-toggle="offcanvas" data-bs-target="#tasksDetailsOffcanvas">
                        <i class="feather-plus"></i>
                        <span>Янги адабиёт қўшиш</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content p-4">
        {{-- 1. КАТЕГОРИЯЛАР ТАБЛАРИ (PILLS) --}}
        @if($categories && $categories->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <span class="fs-12 fw-bold text-uppercase text-muted">
                            <i class="feather-book me-1"></i> Кутубхона йўналишлари:
                        </span>
                        <span class="fs-12 text-muted">Жами бўлимлар: {{ $categories->count() }} та</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($categories as $cat)
                            @php
                                $isActive = isset($category) && $category && $category->id == $cat->id;
                            @endphp
                            <a href="{{ route('libraries.byCategory', $cat->id) }}" 
                               class="btn btn-sm rounded-pill px-3 py-2 d-inline-flex align-items-center gap-2 {{ $isActive ? 'btn-success text-white shadow-sm' : 'btn-outline-secondary bg-white text-dark' }}">
                                <i class="feather-bookmark fs-13"></i>
                                <span>{{ $cat->name }}</span>
                                <span class="badge {{ $isActive ? 'bg-white text-success' : 'bg-light text-dark border' }} rounded-pill fs-11">
                                    {{ $cat->libraries_count ?? 0 }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- 2. ҚИДИРУВ ВА ҲОЛАТ ПАНЕЛИ --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="{{ isset($category) && $category ? route('libraries.byCategory', $category->id) : route('library.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="feather-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" 
                                   placeholder="Китоб номи ёки муаллиф бўйича қидириш..." 
                                   value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ isset($category) && $category ? route('libraries.byCategory', $category->id) : route('library.index') }}" 
                                   class="btn btn-outline-secondary border-start-0 border-end-0" title="Тозалаш">
                                    <i class="feather-x text-danger"></i>
                                </a>
                            @endif
                            <button type="submit" class="btn btn-success px-3">Қидириш</button>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-7 text-md-end text-muted fs-13">
                        @if(request('search'))
                            Қидирув натижаси: <strong>"{{ request('search') }}"</strong> бўйича <strong>{{ $libraries->total() }}</strong> та адабиёт топилди.
                        @else
                            Ушбу бўлимда: <strong>{{ $libraries->total() }}</strong> та адабиёт мавжуд
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- 3. АДАБИЁТЛАР ЖАДВАЛИ --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted fs-12 text-uppercase">
                            <tr>
                                <th class="text-center" style="width: 50px;">№</th>
                                <th>Китоб / Адабиёт номи</th>
                                <th>Муаллиф(лар)</th>
                                <th style="width: 140px;">Ҳажм</th>
                                <th style="width: 130px;">Сана</th>
                                <th class="text-end" style="width: 180px;">Амаллар</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($libraries as $item)
                                <tr>
                                    <td class="text-center text-muted fw-semibold fs-13">
                                        {{ ($libraries->currentPage() - 1) * $libraries->perPage() + $loop->iteration }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-danger text-uppercase fs-10 px-2 py-1 rounded">
                                                PDF
                                            </span>
                                            <span class="fw-semibold text-dark fs-14">
                                                {{ $item->name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="feather-user text-muted fs-13"></i>
                                            <span class="fs-13 text-secondary">{{ $item->author }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-muted border fs-11">
                                            <i class="feather-hard-drive me-1"></i>{{ $item->file_size_formatted }}
                                        </span>
                                    </td>
                                    <td class="text-muted fs-12">
                                        <i class="feather-calendar me-1"></i>{{ $item->created_at ? $item->created_at->format('d.m.Y') : '-' }}
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1"
                                                    onclick="openPdfModal('{{ asset('storage/' . $item->pdf_path) }}', '{{ addslashes($item->name) }}')">
                                                <i class="feather-book-open"></i>
                                                <span>Ўқиш</span>
                                            </button>

                                            <a href="{{ asset('storage/' . $item->pdf_path) }}" 
                                               download="{{ $item->name }}.pdf" 
                                               class="btn btn-sm btn-outline-secondary" 
                                               title="Юклаб олиш">
                                                <i class="feather-download"></i>
                                            </a>

                                            @auth
                                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                                        data-bs-toggle="offcanvas" 
                                                        data-bs-target="#tasksDetailsOffcanvasEdit{{ $item->id }}" 
                                                        title="Таҳрирлаш">
                                                    <i class="feather-edit-2"></i>
                                                </button>

                                                <form action="{{ route('library.destroy', $item->id) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Ҳақиқатан ҳам ушбу адабиётни ўчирмоқчимисиз?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Ўчириш">
                                                        <i class="feather-trash-2"></i>
                                                    </button>
                                                </form>
                                            @endauth
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="feather-book-open fs-40 d-block mb-2 text-secondary opacity-50"></i>
                                            <h6 class="fw-bold mb-1">Ушбу бўлимда адабиётлар топилмади</h6>
                                            <p class="fs-13 text-muted mb-3">Қидирув сўзини ўзгартириб кўринг ёки янги китоб қўшинг.</p>
                                            @auth
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="offcanvas" data-bs-target="#tasksDetailsOffcanvas">
                                                    <i class="feather-plus me-1"></i> Адабиёт қўшиш
                                                </button>
                                            @endauth
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Пагинация --}}
                @if($libraries->hasPages())
                    <div class="card-footer bg-white border-top py-3 d-flex justify-content-end">
                        {{ $libraries->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- Битта умумий PDF кўриш модали -->
<div class="modal fade" id="universalPdfModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 90vw; height: 90vh;">
        <div class="modal-content h-100 border-0 shadow-lg">
            <div class="modal-header bg-dark text-white py-2 px-3">
                <h6 class="modal-title fs-14 fw-bold text-truncate" id="pdfModalTitle">Китобни мутолаа қилиш</h6>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" id="pdfDownloadBtn" download class="btn btn-sm btn-outline-light d-flex align-items-center gap-1">
                        <i class="feather-download"></i>
                        <span>Юклаб олиш</span>
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0 h-100 bg-secondary">
                <iframe id="pdfModalFrame" src="" width="100%" height="100%" style="border:none; min-height: 80vh;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    function openPdfModal(fileUrl, title) {
        document.getElementById('pdfModalTitle').innerText = title || 'Китобни мутолаа қилиш';
        document.getElementById('pdfModalFrame').src = fileUrl;
        document.getElementById('pdfDownloadBtn').href = fileUrl;
        var myModal = new bootstrap.Modal(document.getElementById('universalPdfModal'));
        myModal.show();
    }

    document.getElementById('universalPdfModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('pdfModalFrame').src = '';
    });
</script>

@include('components.admin.library.document-modal-create')
@include('components.admin.library.document-modal-edit', ['libraries' => $libraries])

@endsection
