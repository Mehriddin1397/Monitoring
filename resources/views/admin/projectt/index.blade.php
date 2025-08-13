@extends('layouts.admin')

@section('title', 'Loyiha')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Loyihalar</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item">Loyihalar</li>
                </ul>
            </div>
            <div class="nxl-lavel-mega-menu-wrapper d-flex gap-3 "style="margin-left: 20px">
                <form action="{{ route('projects.search') }}" method="GET" class="d-flex">
                    <input type="text" name="query" class="form-control me-2" placeholder="Ism yoki loyiha boshqaruvchisi...">
                    <button type="submit" class="btn btn-primary">Qidirish</button>
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
                            <span>Loyiha yaratish</span>
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
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Loyiha</th>
                                        <th>Buyruq</th>
                                        <th>Qo'shimcha buyruq</th>
                                        <th>Guruh tarkibi</th>
                                        <th>Guruh jamoatchilik asosida</th>
                                        <th>F.I.Sh</th>
                                        <th>Tel_number</th>
                                        <th>Ish joyi</th>
                                        <th class="text-end">Tahrirlash</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($projects as $project)
                                        <tr class="single-item">
                                            <td> {{ $loop->iteration }}</td>
                                            <td>
                                                {{ $project->name }}
                                            </td>
                                            @php
                                                $pulParticipants = $project->participants->where('type', 'pul');
                                                $freeParticipants = $project->participants->where('type', 'free');
                                            @endphp
                                            <td>
                                                <a href="{{ route('projects_file', ['id' => $project->id, 'type' => 'buyruq']) }}" >
                                                    Hujjatini ochish
                                                </a>


                                            </td>
                                            <td>
                                                <a href="{{ route('projects_file', ['id' => $project->id, 'type' => 'qushimcha']) }}" >
                                                    Hujjatni ochish
                                                </a>
                                            </td>
                                            <td>
                                                @foreach($pulParticipants as $pul)
                                                    {{$pul->name}} <br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($freeParticipants as $pul)
                                                    {{$pul->name}} <br>
                                                @endforeach
                                            </td>
                                            <td>
                                                {{$project->pro_bos_name}}
                                            </td>
                                            <td>
                                                {{$project->tel_number}}
                                            </td>
                                            <td>
                                                {{$project->job}}
                                            </td>

                                            <td>
                                                <div class="hstack gap-2 justify-content-end">
                                                    <a href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#tasksDetailsOffcanvasEdit{{ $project->id }}" class="avatar-text avatar-md">
                                                        <i class="feather feather-edit-3"></i>
                                                    </a>
                                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="avatar-text avatar-md" onclick="return confirm('Are you sure?')">
                                                            <i class="feather feather-trash-2"></i>
                                                        </button>
                                                    </form>
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
