@extends('layouts.admin')

@section('title', 'Loyiha')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->
        <div class="page-header " style="background-color: #7878a3">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10 ">Topshiriqlar</h5>
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
                                    <span>Yaratish</span>
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
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Nazorat uchun
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Topshiriq fayli
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333; text-align: center">
                                            Biriktirilgan xodimlar
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333; text-align: center">
                                            Berilgan sanasi
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Topshirish sanasi
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Topshiriq muddati
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Topshiriq holati
                                        </th>
                                        <th class="text-end">Tahrirlash</th>
                                    </tr>
                                    </thead>
                                    <tbody style="background-color: #e7e7f3">
                                    @foreach($tasks as $task)
                                        @php
                                            $deadline = \Carbon\Carbon::parse($task->end_date);
                                            $daysLeft = \Carbon\Carbon::today()->diffInDays($deadline, false);
                                            $color = 'text-success';

                                            if ($daysLeft <= 5) $color = 'text-danger';
                                            elseif ($daysLeft <= 16) $color = 'text-warning';
                                        @endphp
                                        <tr class="single-item">
                                            <td> {{ $loop->iteration }}</td>
                                            <td>
                                                {{ $task->title }}
                                            </td>
                                            <td>
                                                {{$task->creator->name ?? '-' }}
                                            </td>
                                            <td>
                                                <a href="{{ route('projects.file', ['id' => $task->id, 'type' => 'buyruq']) }}">
                                                    Hujjatini ochish
                                                </a>


                                            </td>
                                            <td>
                                                @foreach($task->assignedUsers as $user)
                                                    <span class="badge bg-primary">{{ $user->name }}</span> <br>
                                                @endforeach
                                            </td>

                                            <td>
                                                {{$task->start_date}}
                                            </td>
                                            <td>

                                                {{$task->end_date}}
                                            </td>
                                            <td>
                                                @if($task->end_date < now() && $task->status !== 'bajarildi')
                                                    <p class="{{ $color }}">
                                                        Bajarilmadi
                                                @elseif($task->status == 'bajarildi')
                                                    <p class="text-success">
                                                        Yakunlandi
                                                @else
                                                    <p class="{{ $color }}">
                                                        {{$daysLeft}} - kun
                                                @endif
                                            </td>
                                            <td>@if($task->end_date < now() && $task->status !== 'bajarildi')
                                                    <p class="$color">
                                                        Bajarilmadi
                                                @elseif(auth()->user()->id == $task->created_by )
                                                    <form action="{{ route('updateStatus', $task->id) }}" method="POST">
                                                        @csrf
                                                        @method('POST')

                                                        <select name="status" class="form-control" required
                                                                onchange="this.form.submit()">
                                                            @foreach(['yangi', 'bajarilmoqda', 'bajarildi'] as $status)
                                                                <option
                                                                    value="{{ $status }}" {{ $task->status === $status ? 'selected' : '' }}>
                                                                    {{ $status }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                @else
                                                    {{$task->status}}

                                                @endif

                                            </td>
                                            <td>

                                                <div class="hstack gap-2 justify-content-end">
                                                    @if(auth()->user()->role == 'xodim' || $task->end_date < now())

                                                    @elseif(auth()->user()->id == $task->created_by ?? auth()->user()->role == 'admin')
                                                        <a href="javascript:void(0)" data-bs-toggle="offcanvas"
                                                           data-bs-target="#tasksDetailsOffcanvasEdit{{ $task->id }}"
                                                           class="avatar-text avatar-md">
                                                            <i class="feather feather-edit-3"></i>
                                                        </a>
                                                    @endif
                                                    @if(auth()->user()->role == 'admin')
                                                        <form action="{{ route('tasks.destroy', $task->id) }}"
                                                              method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="avatar-text avatar-md"
                                                                    onclick="return confirm('Are you sure?')">
                                                                <i class="feather feather-trash-2"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
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


    @include('components.admin.project.project-modal-create',['users' => $users])
    @include('components.admin.project.project-modal-edit', ['tasks' => $tasks])

@endsection
