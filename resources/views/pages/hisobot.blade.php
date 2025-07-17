@extends('layouts.admin')

@section('title', 'Loyiha')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->
        <div class="page-header " style="background-color: #7878a3">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10  text-white">Хисобот</h5>
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
                            <div class="table-responsive table-container table-wrapper p-3" style="overflow-y: auto;">

                                <div class="d-flex justify-content-between align-items-end flex-wrap mb-4">

                                    <!-- Chap tarafdagi forma -->
                                    <form method="GET" action="{{ route('monitoring.hisobot') }}" class="form-inline d-flex flex-wrap align-items-end">
                                        <div class="form-group me-3 mb-2">
                                            <label class="me-2 fw-bold">Дан:</label>
                                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control shadow-sm" required>
                                        </div>

                                        <div class="form-group me-3 mb-2">
                                            <label class="me-2 fw-bold">Гача:</label>
                                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control shadow-sm" required>
                                        </div>

                                        <button type="submit" class="btn btn-success mb-2 custom-btn">
                                            📊 Ҳисоботни кўриш
                                        </button>
                                    </form>

                                    <!-- O‘ng tarafdagi print tugmasi -->
                                    <button onclick="printTable()" class="btn btn-primary custom-btn mb-2">
                                        🖨️ Чиқариш
                                    </button>

                                </div>

                            </div>

                            <style>
                                .custom-btn {
                                    min-width: 160px;
                                    transition: all 0.2s ease-in-out;
                                }

                                .custom-btn:hover {
                                    transform: translateY(-2px);
                                    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
                                }

                                .form-control {
                                    min-width: 180px;
                                }

                                @media (max-width: 768px) {
                                    .form-inline {
                                        flex-direction: column;
                                        align-items: flex-start !important;
                                    }

                                    .form-group {
                                        width: 100%;
                                    }

                                    .custom-btn {
                                        width: 100%;
                                        margin-top: 10px;
                                    }
                                }
                            </style>



                            <table class="table table-hover " id="proposalList">
                                <thead class="sticky-top " style="background-color: #c7c7f0; ">
                                <tr>
                                    <th style="font-weight: bold; font-size: 13px; color: #333;">№</th>

                                    <th style="font-weight: bold; font-size: 13px; color: #333;">
                                        Келиб тушган топшириқлар сони
                                    </th>

                                    <th style="font-weight: bold; font-size: 13px; color: #333;">
                                        Топширилган (муддатида)
                                    </th>

                                    <th style="font-weight: bold; font-size: 13px; color: #333;">Топширилган (муддати ўзайтирилган)
                                    </th>

                                    <th style="font-weight: bold; font-size: 13px; color: #333; "> Ўзайтирилган
                                    </th>

                                    <th> Жараёнда
                                    </th>

                                    <th style="font-weight: bold; font-size: 13px; color: #333;">Бажарилмаган
                                    </th>

                                </tr>
                                </thead>
                                <tbody style=" background-color: #e7e7f3  ">
                                @if(isset($summary))
                                <tr class="single-item" style="background-color: #e7e7f3">
                                    <td></td>
                                    <td style="color: black; font-weight: bold">{{ $summary['total'] }}</td>
                                    <td style="color: #00e000; font-weight: bold ">{{$summary['completed'] }}</td>
                                    <td> {{$summary['extended_then_completed']}}</td>
                                    <td style="color: #d3aa13;font-weight: bold">{{$summary['extended'] }}</td>
                                    <td style="color: #2305dd; font-weight: bold">{{ $summary['in_process'] }}</td>
                                    <td style="color: red;font-weight: bold ">{{ $summary['not_completed'] }}</td>
                                </tr>
                                @endif
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
                <title>Криминалогия институти Смарт бошқаруви Хисоботи</title>
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
                <h2 style="text-align: center"> Криминалогия институти Смарт бошқаруви Хисоботи <br> {{request('from_date')}} дан {{ request('to_date') }} гача </h2>
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
