@extends('layouts.admin')

@section('title', 'Маълумотлар банки')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->
        <div class="page-header " style="background-color: #7878a3">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title ">
                    <h5 class="m-b-10 ">Маълумотлар банки</h5>
                </div>
            </div>
            <div class="nxl-lavel-mega-menu-wrapper d-flex gap-3 " style="margin-left: 20px">

                <input
                    type="text"
                    id="search"
                    class="search-box"
                    placeholder="Hujjat nomi yoki muallif ismi"
                >



                <style>
                    .search-box {
                        outline: none;
                        padding: 7px;
                        padding-left: 10px;
                        padding-right: 40px;
                        height: 35px;
                        width: 100%;
                        border-radius: 30px;
                        font-size: 15px;
                        border: 1px solid #444; /* Chegara qo'shildi */
                        background-color: #f0f0f0; /* Biroz qoraytirilgan fon */
                        color: #333; /* Matn rangi */
                        font-weight: bold; /* Matnni sal qalin qilish */
                        box-shadow: 0px 0px 5px;
                    }
                </style>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex d-md-none">
                        <a href="javascript:void(0)" class="page-header-right-close-toggle">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Back</span>
                        </a>
                    </div>
                    @auth
                        @if (auth()->user()->role !== 'xodim' || auth()->user()->id == 59 || auth()->user()->id == 40)
                            <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                                <a href="javascript:void(0);" class="btn btn-primary " data-bs-toggle="offcanvas"
                                   data-bs-target="#tasksDetailsOffcanvas">
                                    <i class="feather-plus me-2"></i>
                                    <span>Яратиш</span>
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
                <div class="d-md-none d-flex align-items-center">
                    <a href="javascript:void(0)" class="page-header-right-open-toggle">
                        <i class="feather-align-right fs-20"></i>
                    </a>
                </div>
            </div>
        </div>
        <div id="collapseOne" class="accordion-collapse collapse page-header-collapse">
            <div class="accordion-body pb-2">
                <div class="row">
                    <div class="col-xxl-3 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="javascript:void(0);" class="fw-bold d-block">
                                        <span class="d-block">Paid</span>
                                        <span class="fs-20 fw-bold d-block">78/100</span>
                                    </a>
                                    <div class="progress-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="javascript:void(0);" class="fw-bold d-block">
                                        <span class="d-block">Unpaid</span>
                                        <span class="fs-20 fw-bold d-block">38/50</span>
                                    </a>
                                    <div class="progress-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="javascript:void(0);" class="fw-bold d-block">
                                        <span class="d-block">Overdue</span>
                                        <span class="fs-20 fw-bold d-block">15/30</span>
                                    </a>
                                    <div class="progress-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="javascript:void(0);" class="fw-bold d-block">
                                        <span class="d-block">Draft</span>
                                        <span class="fs-20 fw-bold d-block">3/10</span>
                                    </a>
                                    <div class="progress-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->
        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-body p-0">
                            <div class="table-responsive table-container">
                                <table class="table table-hover" id="proposalList">
                                    <thead style="background-color: #c7c7f0">
                                    <tr>

                                        <th style="font-weight: bold; font-size: 13px; color: #333;">№</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Хужжат номи</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Муаллифи</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333; text-align: center">
                                            Кўриш
                                        </th>
                                        <th class="text-end">Tahrirlash</th>
                                    </tr>
                                    </thead>

                                    <tbody id="result" style="background-color: #e7e7f3">
                                    @foreach($libraries as $document)
                                        <tr class="single-item">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="wrap-text">
                                                {{ $document->name }}
                                            </td>


                                            <td>{{ $document->author }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-primary btn-sm"
                                                        onclick="openModal('{{ asset('storage/' . $document->pdf_path) }}')">
                                                    Кўриш
                                                </button>
                                            </td>
                                            <!-- Modal oynasi -->
                                            <div id="pdfModal" class="custom-modal">
                                                <div class="custom-modal-content">
                                                    <span class="close-btn" onclick="closeModal()">&times;</span>
                                                    <iframe id="pdfFrame" src="" width="100%" height="600px" style="border:none;"></iframe>
                                                </div>
                                            </div>
                                            <td class="text-end">
                                                @auth
                                                    @if (auth()->user()->role !== 'xodim' || auth()->user()->id == 59)
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <a href="javascript:void(0)" data-bs-toggle="offcanvas"
                                                               data-bs-target="#tasksDetailsOffcanvasEdit{{ $document->id }}"
                                                               class="avatar-text avatar-md">
                                                                <i class="feather feather-edit-3"></i>
                                                            </a>
                                                            <form action="{{ route('library.destroy', $document->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="avatar-text avatar-md"
                                                                        onclick="return confirm('Uchirasizmi ?')">
                                                                    <i class="feather feather-trash-2"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                @endauth
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const searchInput = document.getElementById('search');
                                        const resultBody = document.getElementById('result');
                                        const searchUrl = "{{ route('library.search') }}";

                                        if (!searchInput || !resultBody) {
                                            console.error('Search input yoki result tbody topilmadi');
                                            return;
                                        }

                                        searchInput.addEventListener('keyup', function () {
                                            let q = this.value.trim();

                                            fetch(searchUrl + '?q=' + q)
                                                .then(res => res.json())
                                                .then(data => {
                                                    let html = '';

                                                    if (data.length === 0) {
                                                        html = `
                        <tr>
                            <td colspan="5" class="text-center text-danger">
                                Natija topilmadi
                            </td>
                        </tr>`;
                                                    } else {
                                                        data.forEach((item, index) => {
                                                            html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.name}</td>
                            <td>${item.author}</td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm"
                                    onclick="openModal('/storage/${item.pdf_path}')">
                                    Кўриш
                                </button>
                            </td>
                            <td class="text-end">
                                                @auth
                                                            @if (auth()->user()->role !== 'xodim' || auth()->user()->id == 59)
                                                            <div class="hstack gap-2 justify-content-end">
                                                                <a href="javascript:void(0)" data-bs-toggle="offcanvas"
                                                           data-bs-target="#tasksDetailsOffcanvasEdit${item.id}"
                                                           class="avatar-text avatar-md">
                                                            <i class="feather feather-edit-3"></i>
                                                        </a>
                                                                <form action="/admin/library/${item.id}" method="POST">
                                                                @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="avatar-text avatar-md"
                                                                    onclick="return confirm('Uchirasizmi ?')">
                                                                <i class="feather feather-trash-2"></i>
                                                            </button>
                                                        </form>
                                                    </div>
@endif
                                                            @endauth
                                                            </td>
                                        </tr>`;
                                                        });
                                                    }

                                                    resultBody.innerHTML = html;
                                                })
                                                .catch(err => console.error(err));
                                        });
                                    });
                                </script>








                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <style>
        .table-container {
            overflow-x: auto !important; /* Gorizontal skroll qo‘shadi */
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }


        .wrap-text {
            white-space: normal !important;
            word-break: break-word !important;
            overflow-wrap: break-word !important;
        }


    </style>

    @include('components.admin.library.document-modal-create')
    @include('components.admin.library.document-modal-edit')

@endsection
