@extends('layouts.admin')

@section('title', 'Loyiha')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->
        <div class="page-header " style="background-color: #7878a3">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10 ">Loyihalar</h5>
                </div>
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
                        @if (auth()->user()->role !== 'xodim')
                            <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                                <a href="javascript:void(0);" class="btn btn-primary " data-bs-toggle="offcanvas"
                                   data-bs-target="#tasksDetailsOffcanvas">
                                    <i class="feather-plus me-2"></i>
                                    <span>Loyiha yaratish</span>
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
                                <table class="table table-hover " id="proposalList">
                                    <thead style="background-color: #c7c7f0">
                                    <tr>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">№</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Berilgan
                                            topshiriq
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Topshiriq bergan
                                            rahbar
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Topshiriq fayli
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333; text-align: center"> Berilgan sanasi </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Topshirish sanasi </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Topshiriq muddati
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Topshiriq holati
                                        </th>
                                        <th class="text-end">Tahrirlash</th>
                                    </tr>
                                    </thead>
                                    <tbody style="background-color: #e7e7f3">
{{--                                    @foreach($projects as $project)--}}
{{--                                        @php--}}
{{--                                            $deadline = \Carbon\Carbon::parse($project->deadline);--}}
{{--                                            $daysLeft = \Carbon\Carbon::today()->diffInDays($deadline, false);--}}
{{--                                            $color = 'text-success';--}}

{{--                                            if ($daysLeft <= 5) $color = 'text-danger';--}}
{{--                                            elseif ($daysLeft <= 16) $color = 'text-warning';--}}
{{--                                        @endphp--}}
{{--                                        <tr class="single-item">--}}
{{--                                            <td> {{ $loop->iteration }}</td>--}}
{{--                                            <td>--}}
{{--                                                {{ $project->name }}--}}
{{--                                            </td>--}}
{{--                                            @php--}}
{{--                                                $pulParticipants = $project->participants->where('type', 'pul');--}}
{{--                                                $freeParticipants = $project->participants->where('type', 'free');--}}
{{--                                            @endphp--}}
{{--                                            <td>--}}
{{--                                                <a href="{{ route('projects.file', ['id' => $project->id, 'type' => 'buyruq']) }}">--}}
{{--                                                    Hujjatini ochish--}}
{{--                                                </a>--}}


{{--                                            </td>--}}
{{--                                            <td>--}}
{{--                                                {{$project->pro_bos_name}}--}}
{{--                                            </td>--}}
{{--                                            <td>--}}
{{--                                                @foreach($pulParticipants as $pul)--}}
{{--                                                    {{$pul->name}} <br>--}}
{{--                                                @endforeach--}}
{{--                                            </td>--}}

{{--                                            <td>--}}
{{--                                                {{$project->pro_moliya}}--}}
{{--                                            </td>--}}
{{--                                            <td>--}}
{{--                                                {{$project->start_date}}--}}
{{--                                            </td>--}}
{{--                                            <td>--}}
{{--                                                <p class="{{ $color }}">--}}
{{--                                                    {{$project->deadline}}--}}
{{--                                                </p>--}}
{{--                                            </td>--}}

{{--                                            <td>--}}
{{--                                                <div class="hstack gap-2 justify-content-end">--}}
{{--                                                    <a href="javascript:void(0)" data-bs-toggle="offcanvas"--}}
{{--                                                       data-bs-target="#tasksDetailsOffcanvasEdit{{ $project->id }}"--}}
{{--                                                       class="avatar-text avatar-md">--}}
{{--                                                        <i class="feather feather-edit-3"></i>--}}
{{--                                                    </a>--}}
{{--                                                    <form action="{{ route('projects.destroy', $project->id) }}"--}}
{{--                                                          method="POST">--}}
{{--                                                        @csrf--}}
{{--                                                        @method('DELETE')--}}
{{--                                                        <button type="submit" class="avatar-text avatar-md"--}}
{{--                                                                onclick="return confirm('Are you sure?')">--}}
{{--                                                            <i class="feather feather-trash-2"></i>--}}
{{--                                                        </button>--}}
{{--                                                    </form>--}}
{{--                                                </div>--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}
                                    </tbody>
                                    <style>
                                        .text-fonds {
                                            font-weight: bold;
                                            font-size: 20px;
                                            color: #333;
                                        "
                                        }
                                    </style>
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

{{--        @include('components.admin.project.project-modal-create')--}}
{{--        @include('components.admin.project.project-modal-edit', ['projects' => $projects])--}}

@endsection
