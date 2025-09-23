@extends('layouts.admin')

@section('title', 'Loyiha')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Лойиҳалар</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item">Лойиҳалар</li>
                </ul>
            </div>
            <div class="nxl-lavel-mega-menu-wrapper d-flex gap-3 "style="margin-left: 20px">
                <form action="{{ route('projects.search') }}" method="GET" class="d-flex">
                    <input type="text" name="query" class="form-control me-2" placeholder="Исм ёки лойиҳа бошқарувчиси...">
                    <button type="submit" class="btn btn-primary">Қидириш</button>
                </form>
                {{--                    <form id="searchForm" action="{{ route('projects.search') }}" method="get" >--}}
                {{--                        <input type="text" class="search-box" name="query" placeholder="Izlash..." required>--}}
                {{--                    </form>--}}
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
                        box-shadow: 0px 0px 5px ;
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
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="javascript:void(0);" class="btn btn-primary "  data-bs-toggle="offcanvas" data-bs-target="#tasksDetailsOffcanvas">
                            <i class="feather-plus me-2"></i>
                            <span>Лойиҳа яратиш</span>
                        </a>
                    </div>
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
                                <table class="table table-hover " id="proposalList">

                                    <thead class="sticky-top " style="background-color: #c7c7f0; ">
                                    <tr>
                                        <th>#</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Лойиҳа номи</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Буйруқ</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Қўшимча буйруқ</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Гурух таркиби</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Лойиҳа молиялаштириш манбаси ва суммаси</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Лойиҳанинг маъсул ижрочиси Ф.И.Ш, тел рақам</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Лойиҳа рахбарининг иш жойи ва лавозими</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Лойиҳа хисоботлари</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Изох</th>
                                        <th class="text-end">Тахрирлаш</th>
                                    </tr>
                                    </thead>
                                    <tbody style="background-color: #e7e7f3">
                                    @foreach($projects as $project)
                                        <tr class="single-item">
                                            <td> {{ $loop->iteration }}</td>
                                            <td style="width: 150px;">
                                                {!! $project->name !!}
                                            </td>
                                            @php
                                                $pulParticipants = $project->participants->where('type', 'pul');
                                            @endphp
                                            <td>
                                                <button class="btn btn-primary btn-sm" onclick="openModal('{{ asset('storage/' . $project->file_buyruq) }}')">
                                                    Кўриш
                                                </button>
                                            </td>
                                            <td>
                                                @if($project->file_qushimcha)
                                                    <button class="btn btn-primary btn-sm" onclick="openModal('{{ asset('storage/' . $project->file_qushimcha) }}')">
                                                        Кўриш
                                                    </button>
                                                @endif
                                            </td>
                                            <td>
                                                @foreach($pulParticipants as $pul)
                                                    {{$pul->name}} <br>
                                                @endforeach
                                            </td>
                                            <td>
                                               {!! $project->manba !!}
                                            </td>
                                            <td>
                                                {!! $project->pro_bos_name !!}
                                            </td>
                                            <td>
                                                {!! $project->job !!}
                                            </td>
                                            <td>
                                                {{-- Hujjat yuklash formasi --}}
                                                <form action="{{ route('pro_document.store', $project->id) }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 5px;">
                                                    @csrf
                                                    <input type="file" name="file" required>
                                                    <button type="submit">Юклаш</button>
                                                </form>

                                                {{-- Yuklangan hujjatlar --}}
                                                @if($project->pro_documents->count() > 0)
                                                    @foreach($project->pro_documents as $doc)
                                                        <button class="btn btn-primary btn-sm" onclick="openModal('{{ asset('storage/' . $doc->file_path) }}')">
                                                            Кўриш
                                                        </button>
                                                    @endforeach
                                                @else
                                                    <span>Хужжат йўқ</span>
                                                @endif
                                            </td>
                                            <!-- Modal oynasi -->
                                            <div id="pdfModal" class="custom-modal">
                                                <div class="custom-modal-content">
                                                    <span class="close-btn" onclick="closeModal()">&times;</span>
                                                    <iframe id="pdfFrame" src="" width="100%" height="600px" style="border:none;"></iframe>
                                                </div>
                                            </div>
                                            <td>
                                                {!! $project->izoh !!}
                                            </td>

                                            <td>
                                                <div class="hstack gap-2 justify-content-end">
                                                    <a href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#tasksDetailsOffcanvasEdit{{ $project->id }}" class="avatar-text avatar-md">
                                                        <i class="feather feather-edit-3"></i>
                                                    </a>
                                                    @if(auth()->user()->role == 'admin')
                                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="avatar-text avatar-md" onclick="return confirm('Are you sure?')">
                                                            <i class="feather feather-trash-2"></i>
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <style>
        #proposalList th {
            text-align: center;  /* Matnni markazga qo‘yadi */
            vertical-align: middle; /* Vertikal markazga qo‘yadi */
        }

        .table-container {
            overflow-x: auto !important; /* Gorizontal skroll qo‘shadi */
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;

        }
    </style>

    @include('components.admin.projectt.project-modal-create')
    @include('components.admin.projectt.project-modal-edit', ['projects' => $projects])

@endsection
