@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Boshqaruv paneli</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Bosh sahifa</a></li>
                    <li class="breadcrumb-item">Boshqaruv paneli</li>
                </ul>
            </div>

        </div>
        <!-- [ page-header ] end -->
        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row">
                <!-- [Projects Stats] start -->
                <div class="col-xxl-12">
                    <div class="card stratch">
                        <div class="card-header">
                            <h5 class="card-title">Xodimlarga biriktirilgan topshiriqlar - {{$month}}</h5>
                            <div class="card-header-action">
                                <div class="card-header-btn">
                                    <div data-bs-toggle="tooltip" title="Delete">
                                        <a href="javascript:void(0);" class="avatar-text avatar-xs bg-danger"
                                           data-bs-toggle="remove"> </a>
                                    </div>
                                    <div data-bs-toggle="tooltip" title="Refresh">
                                        <a href="javascript:void(0);" class="avatar-text avatar-xs bg-warning"
                                           data-bs-toggle="refresh"> </a>
                                    </div>
                                    <div data-bs-toggle="tooltip" title="Maximize/Minimize">
                                        <a href="javascript:void(0);" class="avatar-text avatar-xs bg-success"
                                           data-bs-toggle="expand"> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <div class="table-responsive project-report-table">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">F.I.Sh</th>
                                        <th scope="col">Topshiriq</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($tasksWithUsers as $user)
                                        @foreach ($user->assignedTasks as $task)
                                            <tr>
                                                <td>
                                                    <div class="hstack gap-4">
                                                        <div class="avatar-image ounded">
                                                            <img src="{{asset('assets/images/logo.svg')}}" alt=""
                                                                 class="img-fluid">
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark">{{$user->name}}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-dark">{{$task->title}}</span></td>
                                                <td>
                                                    @php
                                                        $deadline = \Carbon\Carbon::parse($task->end_date)->toDateString(); // faqat sana
//                                                        dd($deadline);
                                                        $today = \Carbon\Carbon::today()->toDateString();
                                                        $expired = $deadline < $today; // agar muddati o‘tgan bo‘lsa

                                                    @endphp

                                                    @if($task->status == 'bajarildi')
                                                        <div
                                                            class="badge bg-soft-success text-success">{{$task->status}}</div>
                                                    @elseif(($task->status == 'yangi' || $task->status == 'bajarilmoqda') && !$expired)
                                                        @if($task->status == 'yangi')
                                                            <div
                                                                class="badge bg-soft-teal text-teal">{{$task->status}}</div>
                                                        @elseif($task->status == 'bajarilmoqda')
                                                            <div
                                                                class="badge bg-soft-primary text-primary">{{$task->status}}</div>
                                                        @endif
                                                    @elseif(($task->status == 'yangi' || $task->status == 'bajarilmoqda') && $expired)
                                                        <div class="badge bg-soft-danger text-danger">Bajarilmadi</div>
                                                    @endif


                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [Projects Stats] end -->
                <!-- [Project Report] start -->
                <div class="col-xxl-8">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Project Report</h5>
                            <div class="card-header-action">
                                <div class="card-header-btn">
                                    <div data-bs-toggle="tooltip" title="Delete">
                                        <a href="javascript:void(0);" class="avatar-text avatar-xs bg-danger"
                                           data-bs-toggle="remove"> </a>
                                    </div>
                                    <div data-bs-toggle="tooltip" title="Refresh">
                                        <a href="javascript:void(0);" class="avatar-text avatar-xs bg-warning"
                                           data-bs-toggle="refresh"> </a>
                                    </div>
                                    <div data-bs-toggle="tooltip" title="Maximize/Minimize">
                                        <a href="javascript:void(0);" class="avatar-text avatar-xs bg-success"
                                           data-bs-toggle="expand"> </a>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="avatar-text avatar-sm"
                                       data-bs-toggle="dropdown" data-bs-offset="25, 25">
                                        <div data-bs-toggle="tooltip" title="Options">
                                            <i class="feather-more-vertical"></i>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="javascript:void(0);" class="dropdown-item"><i
                                                class="feather-at-sign"></i>New</a>
                                        <a href="javascript:void(0);" class="dropdown-item"><i
                                                class="feather-calendar"></i>Event</a>
                                        <a href="javascript:void(0);" class="dropdown-item"><i class="feather-bell"></i>Snoozed</a>
                                        <a href="javascript:void(0);" class="dropdown-item"><i
                                                class="feather-trash-2"></i>Deleted</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="javascript:void(0);" class="dropdown-item"><i
                                                class="feather-settings"></i>Settings</a>
                                        <a href="javascript:void(0);" class="dropdown-item"><i
                                                class="feather-life-buoy"></i>Tips & Tricks</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body custom-card-action">
                            <div id="project-statistics-chart"></div>
                        </div>
                    </div>
                </div>
                <!-- [Project Report] end -->
                <!-- [Project Calendar] start -->
                <div class="col-xxl-4">
                    <div class="card stretch stretch-full">
                        <div id="project-calendar"></div>
                    </div>
                </div>
                <!-- [Project Calendar] end -->


            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
    <script src="assets/vendors/js/vendors.min.js"></script>
    <!-- vendors.min.js {always must need to be top} -->
    <script src="assets/vendors/js/daterangepicker.min.js"></script>
    <script src="assets/vendors/js/apexcharts.min.js"></script>
    <script src="assets/vendors/js/circle-progress.min.js"></script>
    <!--! END: Vendors JS !-->
    <!--! BEGIN: Apps Init  !-->
    <script src="assets/js/common-init.min.js"></script>
    <script src="assets/js/dashboard-init.min.js"></script>
    <!--! END: Apps Init !-->
    <!--! BEGIN: Theme Customizer  !-->
    <script src="assets/js/theme-customizer-init.min.js"></script>

@endsection
