@extends('layouts.admin')

@section('title', 'Loyiha')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->
        <div class="page-header " style="background-color: #7878a3">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10  text-white" >Топшириқлар</h5>
                </div>
            </div>
            <div style="
    background-color: #ff0000;
    color: #fff700;
    font-weight: bold;
    font-size: 20px;
    padding: 15px;
    border: 3px dashed yellow;
    text-align: center;
    animation: blink 1s infinite;
    margin-right: 5px;
">
                ⚠️ Белгиланган топшириқларни бажарилмаганлиги юзасидан ташкилий ишлар бўлими томонидан хизмат текшируви
                ўтказилиб, институт кенгашида муҳокамага қўйилади!
            </div>

            <style>
                @keyframes blink {
                    0% {
                        opacity: 1;
                    }
                    50% {
                        opacity: 0.4;
                    }
                    100% {
                        opacity: 1;
                    }
                }
            </style>

            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex d-md-none">
                        <a href="javascript:void(0)" class="page-header-right-close-toggle">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Back</span>
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
                            <div class="table-responsive table-container table-wrapper" style=" overflow-y: auto;">
                                <h2>📊 Ходимлар статистикаси</h2>
{{--                                <div class="d-flex justify-content-between align-items-center mb-3">--}}
{{--                                    @php--}}
{{--                                        $currentStatus = request()->route('status');--}}
{{--                                    @endphp--}}
{{--                                        <!-- Chap tarafdagi 4ta status tugma -->--}}
{{--                                    <div class="d-flex gap-1 mb-3">--}}
{{--                                        <a href="{{ route('tasks.status', 'bajarilmoqda') }}"--}}
{{--                                           class="custom-btn btn btn-primary {{ request()->route('status') === 'bajarilmoqda' ? 'active' : '' }}">--}}
{{--                                            Бажарилмоқда--}}
{{--                                        </a>--}}
{{--                                        <a href="{{ route('tasks.status', 'uzaytirildi') }}"--}}
{{--                                           class="custom-btn btn btn-warning text-dark {{ request()->route('status') === 'uzaytirildi' ? 'active' : '' }}">--}}
{{--                                            Узайтирилган--}}
{{--                                        </a>--}}
{{--                                        <a href="{{ route('tasks.status', 'bajarildi') }}"--}}
{{--                                           class="custom-btn btn btn-success {{ request()->route('status') === 'bajarildi' ? 'active' : '' }}">--}}
{{--                                            Бажарилган--}}
{{--                                        </a>--}}
{{--                                        <a href="{{ route('tasks.failed') }}"--}}
{{--                                           class="custom-btn btn btn-danger {{ request()->routeIs('tasks.failed') ? 'active' : '' }}">--}}
{{--                                            Бажарилмаган--}}
{{--                                        </a>--}}

{{--                                    </div>--}}

                                    <!-- O'ng tarafdagi print tugma -->
                                <button onclick="printTable()" class="btn btn-primary">
                                    🖨️ Чиқариш
                                </button>

                            </div>


                                <style>
                                    .custom-btn {
                                        min-width: 150px;
                                        margin-left: 10px;
                                        margin-top: 10px;
                                        text-align: center;
                                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                                    }

                                    .custom-btn:hover {
                                        transform: translateY(-3px);
                                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
                                        text-decoration: none;
                                    }
                                </style>


                                <table class="table table-hover " id="proposalList">
                                    <thead class="sticky-top " style="background-color: #c7c7f0; ">
                                    <tr>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">№</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Ходимлар Ф.И.Ш
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;" >
                                            Умумий топшириқ
                                        </th>
                                        <th >
                                            Жараёнда
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333; "> Ўзайтирилган
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Топширилган
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Бажарилмаган
                                        </th>

                                    </tr>
                                    </thead>
                                    <tbody style=" background-color: #e7e7f3  ">
                                    @foreach($xodimlar as $index => $item)
                                        <tr class="single-item">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item['user']->name }}</td>
                                            <td style="color: black; font-weight: bold">{{ $item['total'] }}</td>
                                            <td style="color: #2305dd; font-weight: bold">{{ $item['in_process'] }}</td>
                                            <td style="color: #d3aa13;font-weight: bold">{{ $item['extended'] }}</td>
                                            <td style="color: #00e000; font-weight: bold ">{{ $item['completed'] }}</td>
                                            <td style="color: red;font-weight: bold ">{{ $item['not_completed'] }}</td>
                                        </tr >
                                    @endforeach
                                        <tr class="single-item" style="background-color: #d3d3f6">
                                            <td></td>
                                            <td style="color: black; font-weight: bold">Jami:</td>
                                            <td style="color: black; font-weight: bold">{{ $summary['total'] }}</td>
                                            <td style="color: #2305dd; font-weight: bold">{{ $summary['in_process'] }}</td>
                                            <td style="color: #d3aa13;font-weight: bold">{{$summary['extended'] }}</td>
                                            <td style="color: #00e000; font-weight: bold ">{{$summary['completed'] }}</td>
                                            <td style="color: red;font-weight: bold ">{{ $summary['not_completed'] }}</td>
                                        </tr>
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
    <script>
        function printTable() {
            let divToPrint = document.getElementById('proposalList');
            let newWin = window.open('');
            newWin.document.write(`
            <html>
            <head>
                <title>📊 Ходимлар статистикаси</title>
                <style>
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    table, th, td {
                        border: 1px solid black;
                        padding: 8px;
                        text-align: center;
                    }
                    th {
                        background-color: #c7c7f0;
                    }
                </style>
            </head>
            <body>
                <h2>📊 Ходимлар статистикаси</h2>
                ${divToPrint.outerHTML}
            </body>
            </html>
        `);
            newWin.document.close();
            newWin.focus();
            newWin.print();
            newWin.close();
        }
    </script>



    <style>
        .table-wrapper {
            max-height: 800px; /* scroll bo'lishi uchun balandlik */
            overflow-y: auto;
            position: relative;
            border: 1px solid #ddd;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background-color: #c7c7ed; /* orqa fon (bootstrap light) */
            z-index: 2;
            border-bottom: 2px solid #dee2e6;
        }
    </style>












@endsection
