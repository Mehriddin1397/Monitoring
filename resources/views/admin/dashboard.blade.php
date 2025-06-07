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
                <div class="col-lg-4">
                    <div class="card mb-4 stretch stretch-full">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex gap-3 align-items-center">
                                <div class="avatar-text">
                                    <i class="feather feather-star"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">Tasks Completed</div>
                                    <div class="fs-12 text-muted">22/35 completed</div>
                                </div>
                            </div>
                            <div class="fs-4 fw-bold text-dark">22/35</div>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-between gap-4">
                            <div id="task-completed-area-chart"></div>
                            <div class="fs-12 text-muted text-nowrap">
                                <span class="fw-semibold text-primary">28% more</span><br />
                                <span>from last week</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-4 stretch stretch-full">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex gap-3 align-items-center">
                                <div class="avatar-text">
                                    <i class="feather feather-file-text"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">New Tasks</div>
                                    <div class="fs-12 text-muted">0/20 tasks</div>
                                </div>
                            </div>
                            <div class="fs-4 fw-bold text-dark">5/20</div>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-between gap-4">
                            <div id="new-tasks-area-chart"></div>
                            <div class="fs-12 text-muted text-nowrap">
                                <span class="fw-semibold text-success">34% more</span><br />
                                <span>from last week</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-4 stretch stretch-full">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex gap-3 align-items-center">
                                <div class="avatar-text">
                                    <i class="feather feather-airplay"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">Project Done</div>
                                    <div class="fs-12 text-muted">20/30 project</div>
                                </div>
                            </div>
                            <div class="fs-4 fw-bold text-dark">20/30</div>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-between gap-4">
                            <div id="project-done-area-chart"></div>
                            <div class="fs-12 text-muted text-nowrap">
                                <span class="fw-semibold text-danger">42% more</span><br />
                                <span>from last week</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- [Latest Leads] start -->
                <div class="col-xxl-8">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Latest Leads</h5>
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
                        <div class="card-body custom-card-action p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                    <tr class="border-b">
                                        <th scope="row">Users</th>
                                        <th>Proposal</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-image">
                                                    <img src="assets/images/avatar/2.png" alt="" class="img-fluid"/>
                                                </div>
                                                <a href="javascript:void(0);">
                                                    <span class="d-block">Archie Cantones</span>
                                                    <span class="fs-12 d-block fw-normal text-muted">arcie.tones@gmail.com</span>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-gray-200 text-dark">Sent</span>
                                        </td>
                                        <td>11/06/2023 10:53</td>
                                        <td>
                                            <span class="badge bg-soft-success text-success">Completed</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="javascript:void(0);"><i class="feather-more-vertical"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-image">
                                                    <img src="assets/images/avatar/3.png" alt="" class="img-fluid"/>
                                                </div>
                                                <a href="javascript:void(0);">
                                                    <span class="d-block">Holmes Cherryman</span>
                                                    <span class="fs-12 d-block fw-normal text-muted">golms.chan@gmail.com</span>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-gray-200 text-dark">New</span>
                                        </td>
                                        <td>11/06/2023 10:53</td>
                                        <td>
                                            <span class="badge bg-soft-primary text-primary">In Progress </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="javascript:void(0);"><i class="feather-more-vertical"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-image">
                                                    <img src="assets/images/avatar/4.png" alt="" class="img-fluid"/>
                                                </div>
                                                <a href="javascript:void(0);">
                                                    <span class="d-block">Malanie Hanvey</span>
                                                    <span class="fs-12 d-block fw-normal text-muted">lanie.nveyn@gmail.com</span>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-gray-200 text-dark">Sent</span>
                                        </td>
                                        <td>11/06/2023 10:53</td>
                                        <td>
                                            <span class="badge bg-soft-success text-success">Completed</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="javascript:void(0);"><i class="feather-more-vertical"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-image">
                                                    <img src="assets/images/avatar/5.png" alt="" class="img-fluid"/>
                                                </div>
                                                <a href="javascript:void(0);">
                                                    <span class="d-block">Kenneth Hune</span>
                                                    <span
                                                        class="fs-12 d-block fw-normal text-muted">nneth.une@gmail.com</span>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-gray-200 text-dark">Returning</span>
                                        </td>
                                        <td>11/06/2023 10:53</td>
                                        <td>
                                            <span class="badge bg-soft-warning text-warning">Not Interested</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="javascript:void(0);"><i class="feather-more-vertical"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-image">
                                                    <img src="assets/images/avatar/6.png" alt="" class="img-fluid"/>
                                                </div>
                                                <a href="javascript:void(0);">
                                                    <span class="d-block">Valentine Maton</span>
                                                    <span class="fs-12 d-block fw-normal text-muted">alenine.aton@gmail.com</span>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-gray-200 text-dark">Sent</span>
                                        </td>
                                        <td>11/06/2023 10:53</td>
                                        <td>
                                            <span class="badge bg-soft-success text-success">Completed</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="javascript:void(0);"><i class="feather-more-vertical"></i></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <ul class="list-unstyled d-flex align-items-center gap-2 mb-0 pagination-common-style">
                                <li>
                                    <a href="javascript:void(0);"><i class="bi bi-arrow-left"></i></a>
                                </li>
                                <li><a href="javascript:void(0);" class="active">1</a></li>
                                <li><a href="javascript:void(0);">2</a></li>
                                <li>
                                    <a href="javascript:void(0);"><i class="bi bi-dot"></i></a>
                                </li>
                                <li><a href="javascript:void(0);">8</a></li>
                                <li><a href="javascript:void(0);">9</a></li>
                                <li>
                                    <a href="javascript:void(0);"><i class="bi bi-arrow-right"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- [Latest Leads] end -->

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
