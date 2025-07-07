@extends('layouts.admin')

@section('title', 'Loyiha')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->
        <div class="page-header " style="background-color: #7878a3">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10 ">Топшириқлар</h5>
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
                            <div class="table-responsive table-container table-wrapper" style=" overflow-y: auto;">
                                <table class="table table-hover " id="proposalList">
                                    <thead class="sticky-top " style="background-color: #c7c7f0; ">
                                    <tr>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">№</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Берилган топшириқ
                                        </th>
                                        {{--                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Nazorat uchun--}}
                                        {{--                                        </th>--}}
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Топшириқ файли
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333; text-align: center">
                                            Ижрочилар
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333; text-align: center">
                                            Берилган санаси
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Бажариш санаси
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Топшириқ муддати
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Топшириқ холати
                                        </th>
                                        <th class="text-end">Таҳрирлаш</th>
                                    </tr>
                                    </thead>
                                    <tbody style="background-color: #e7e7f3">
                                    @foreach($tasks as $task)
                                        @php
                                            $deadline = \Carbon\Carbon::parse($task->end_date);
                                            $daysLeft = \Carbon\Carbon::today()->diffInDays($deadline, false);
                                            $color = 'text-success';
                                            $showAlert = $daysLeft == 1; // Faqat 1 kun qolganida

                                            if ($daysLeft <= 5) $color = 'text-danger';
                                            elseif ($daysLeft <= 16) $color = 'text-warning';
                                        @endphp

                                        @if(auth()->user()->role == 'xodim' && $task->status !== 'bajarildi' )
                                            @if($showAlert)
                                                <script>
                                                    let alertAudio;

                                                    function showRepeatingAlert() {
                                                        // Ovoz faylini yuklab, takrorlansin
                                                        alertAudio = new Audio("{{ asset('sounds/alert.mp3') }}");
                                                        alertAudio.loop = true;
                                                        alertAudio.play();

                                                        // Modal oynani ko‘rsatish (oddiy HTML element yordamida)
                                                        const alertBox = document.createElement('div');
                                                        alertBox.innerHTML = ` <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                                                    background: rgba(0,0,0,0.6); display: flex; align-items: center;
                                                    justify-content: center; z-index: 9999;">
                                                                                <div style="background: white; padding: 30px; border-radius: 10px;
                                                                                     text-align: center; font-size: 20px; max-width: 400px;">
                                                                                        <p><strong>⏰ DIQQAT!</strong><br>Топшириқ тугашига 1 кун қолди!</p>
                                                                                            <button id="stopAlertBtn"
                                                                                               style="padding: 10px 20px; margin-top: 20px; background-color: red; color: white; border: none; border-radius: 5px;">
                                                                                            Тушунарли
                                                                                     </button>
                                                                                     </div>
                                                                                 </div>
                                                                             `;

                                                        document.body.appendChild(alertBox);

                                                        // Tugmani bosganda — modalni va ovozni to‘xtatish
                                                        document.getElementById('stopAlertBtn').addEventListener('click', () => {
                                                            alertAudio.pause();
                                                            alertAudio.currentTime = 0;
                                                            alertBox.remove();
                                                        });
                                                    }

                                                    // Dastlab ochilishi
                                                    showRepeatingAlert();

                                                    // Keyingi har 20 daqiqada signal
                                                    setInterval(showRepeatingAlert, 20 * 60 * 1000);
                                                </script>
                                            @endif
                                        @endif

                                        <tr class="single-item">
                                            <td> {{ $loop->iteration }}</td>
                                            <td>
                                                <h6 class="text-dark mb-0 text-break"
                                                    style="white-space: normal; word-break: break-word; font-weight: normal;">
                                                    {!! $task->title !!}
                                                </h6>
                                            </td>
                                            {{--                                            <td>--}}
                                            {{--                                                {{$task->creator->name ?? '-' }}--}}
                                            {{--                                            </td>--}}
                                            <td>
                                                <a href="{{ route('projects.file', ['id' => $task->id, 'type' => 'buyruq']) }}">
                                                    Хужжатни очиш
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
                                                        Бажарилмади
                                                @elseif($task->status == 'bajarildi')
                                                    <p class="text-success">
                                                        Якунланди
                                                @else
                                                    <p class="{{ $color }}">
                                                        {{$daysLeft}} - кун
                                                @endif
                                            </td>
                                            <td>
                                                @if($task->end_date < now() && $task->status !== 'bajarildi')
                                                    <p class="$color">
                                                        Бажарилмади
                                                @elseif(auth()->user()->id == $task->created_by )
                                                    <form action="{{ route('updateStatus', $task->id) }}" method="POST">
                                                        @csrf
                                                        @method('POST')

                                                        <select name="status" class="form-control" required
                                                                onchange="this.form.submit()">
                                                            @foreach(['yangi', 'bajarilmoqda', 'bajarildi'] as $status)
                                                                <option
                                                                    value="{{ $status }}" {{ $task->status === $status ? 'selected' : '' }}>
                                                                    @if($status == 'yangi')
                                                                        Янги
                                                                    @elseif($status == 'bajarilmoqda')
                                                                        Бажарилмоқда
                                                                    @else
                                                                        Бажарилди
                                                                    @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                @else
                                                    @if($status == 'yangi')
                                                        Янги
                                                    @elseif($status == 'bajarilmoqda')
                                                        Бажарилмоқда
                                                    @else
                                                        Бажарилди
                                                    @endif

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




    @include('components.admin.project.project-modal-create',['users' => $users])
    @include('components.admin.project.project-modal-edit', ['tasks' => $tasks])

@endsection
