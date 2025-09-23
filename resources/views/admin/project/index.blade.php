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
                    @auth
                        @if (auth()->user()->role !== 'xodim' || auth()->user()->id == 26)
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
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    @php
                                        $currentStatus = request()->route('status');
                                    @endphp
                                    <!-- Chap tarafdagi 4ta status tugma -->
                                    <div class="d-flex gap-1 mb-3">
                                        <a href="{{ route('tasks.status', 'bajarilmoqda') }}"
                                           class="custom-btn btn btn-primary {{ request()->route('status') === 'bajarilmoqda' ? 'active' : '' }}">
                                            Бажарилмоқда
                                        </a>
                                        <a href="{{ route('tasks.status', 'uzaytirildi') }}"
                                           class="custom-btn btn btn-warning text-dark {{ request()->route('status') === 'uzaytirildi' ? 'active' : '' }}">
                                            Узайтирилган
                                        </a>
                                        <a href="{{ route('tasks.status', 'bajarildi') }}"
                                           class="custom-btn btn btn-success {{ request()->route('status') === 'bajarildi' ? 'active' : '' }}">
                                            Бажарилган
                                        </a>
                                        <a href="{{ route('tasks.failed') }}"
                                           class="custom-btn btn btn-danger {{ request()->routeIs('tasks.failed') ? 'active' : '' }}">
                                            Бажарилмаган
                                        </a>

                                    </div>

                                    <!-- O'ng tarafdagi print tugma -->
{{--                                    <div class="mt-2">--}}
{{--                                        <button onclick="printTable()" class="btn btn-primary">--}}
{{--                                            🖨️ Чиқариш--}}
{{--                                        </button>--}}
{{--                                    </div>--}}

                                    <div class="mt-2">
                                        <button onclick="downloadAsWord()" class="btn btn-success">
                                            📝 Word файл сифатида сақлаш
                                        </button>
                                    </div>

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
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Топшириқ мазмуни
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333; text-align: center">
                                            Ижрочилар
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333; text-align: center">
                                            Топшириқни берган
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333; text-align: center">
                                            Берилган санаси
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Бажариш санаси
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Топшириқ муддати
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Топшириқ ҳолати
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Топшириқ ижроси
                                        </th>
                                        <th class="text-end">Таҳрирлаш</th>
                                    </tr>
                                    </thead>
                                    <tbody style="

                                    background-color: #e7e7f3

                                    ">
                                    @foreach($tasks as $task)
                                        @php
                                            $deadline = \Carbon\Carbon::parse($task->end_date);
                                            $daysLeft = \Carbon\Carbon::today()->diffInDays($deadline, false);
                                            $color = 'text-success';
                                            $showAlert = in_array($daysLeft, [0, 1]); // Faqat 1 kun qolganida
                                            $now = \Carbon\Carbon::now();
                                            $today = $now->startOfDay();
                                            $endDate = \Carbon\Carbon::parse($task->end_date)->startOfDay();
                                            $daysLeft1 = $today->diffInDays($endDate, false); // false -> manfiy ham qaytaradi

                                            if ($daysLeft <= 3) $color = 'text-danger';
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
                                                    setInterval(showRepeatingAlert, 30 * 60 * 1000);
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

                                            <td>
                                                @foreach($task->assignedUsers as $user)
                                                    <span class="badge bg-primary">{{ $user->name }}</span> <br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($task->categories as $category)
                                                    {{$category->name}}
                                                @endforeach
                                            </td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($task->start_date)->format('d-m-Y') }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($task->end_date)->format('d-m-Y') }}
                                            </td>

                                            <td>

                                                @if ($task->status === 'bajarildi')
                                                    <p class="text-success">Якунланди</p>

                                                @elseif ($now->greaterThan($endDate->copy()->endOfDay()) && $task->status !== 'bajarildi')
                                                    <p class="{{ $color }}">Бажарилмади</p>

                                                @elseif ($endDate->isToday() && $task->status !== 'bajarildi')
                                                    <p class="{{ $color }}">Топшириш куни</p>

                                                @else
                                                    <p class="{{ $color }}">{{ $daysLeft }} - кун</p>
                                                @endif
                                            </td>
                                            <td>
                                                @if($now->greaterThan($endDate->copy()->endOfDay()) && $task->status !== 'bajarildi')
                                                    <p class="{{ $color }}">
                                                        Бажарилмади
                                                @elseif(auth()->user()->id == $task->created_by )
                                                    <form action="{{ route('updateStatus', $task->id) }}" method="POST" id="status-form-{{ $task->id }}">
                                                        @csrf
                                                        @method('POST')

                                                        <select name="status" class="form-control" required onchange="handleStatusChange(this, {{ $task->id }})">
                                                            @foreach(['yangi', 'bajarilmoqda', 'uzaytirildi', 'bajarildi'] as $status)
                                                                <option value="{{ $status }}" {{ $task->status === $status ? 'selected' : '' }}>
                                                                    @if($status == 'yangi' )
                                                                        Янги
                                                                    @elseif( $status == 'bajarilmoqda')
                                                                        Жараёнда
                                                                    @elseif( $status == 'uzaytirildi')
                                                                        Узайтирилди
                                                                    @elseif( $status == 'bajarildi' &&  $task->document)
                                                                        Бажарилди
                                                                    @endif
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        {{-- Sana inputi faqat "uzaytirildi" holatda ko‘rsatiladi --}}
                                                        <div id="date-container-{{ $task->id }}" style="display: none; margin-top: 10px;">
                                                            <label>Янги муддат:</label>
                                                            <input type="date" name="end_date" class="form-control"
                                                                   onchange="document.getElementById('status-form-{{ $task->id }}').submit();">
                                                        </div>
                                                    </form>


                                                    <script>
                                                        function handleStatusChange(select, taskId) {
                                                            const dateContainer = document.getElementById('date-container-' + taskId);
                                                            const form = document.getElementById('status-form-' + taskId);

                                                            if (select.value === 'uzaytirildi') {
                                                                dateContainer.style.display = 'block';
                                                            } else {
                                                                dateContainer.style.display = 'none';
                                                                form.submit();
                                                            }
                                                        }

                                                        // Sahifa yuklanganda tekshir: agar status = 'uzaytirildi' bo‘lsa, sana ko‘rsatilsin
                                                        document.addEventListener('DOMContentLoaded', function () {
                                                            const select = document.querySelector('#status-form-{{ $task->id }} select');
                                                            if (select && select.value === 'uzaytirildi') {
                                                                document.getElementById('date-container-{{ $task->id }}').style.display = 'block';
                                                            }
                                                        });
                                                    </script>


                                                @else
                                                    @if($task->status == 'yangi')
                                                        Янги
                                                    @elseif($task->status == 'bajarilmoqda')
                                                        Бажарилмоқда
                                                    @elseif($task->status == 'bajarildi')
                                                        Бажарилди
                                                    @else
                                                        Узайтирилди
                                                    @endif
                                                @endif


                                            </td>
                                            <td>
                                                @php
                                                    $currentUser = \Illuminate\Support\Facades\Auth::user();
                                                    $isAssigned = $task->assignedUsers->contains(function ($user) use ($currentUser) {
                                                        return $user->id === $currentUser->id;
                                                    });
                                                @endphp


                                                @if($task->assignedUsers->contains(Auth::user()->id))
                                                    @if($task->document)
                                                        <form action="{{ route('file.upload') }}" method="POST"
                                                              enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                            <input type="file" name="document"
                                                                   onchange="this.form.submit()">
                                                        </form>
                                                        <button class="btn btn-primary btn-sm" onclick="openModal('{{ asset('storage/' . $task->document) }}')">
                                                            Кўриш
                                                        </button>
                                                    @else
                                                        <form action="{{ route('file.upload') }}" method="POST"
                                                              enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                            <input type="file" name="document"
                                                                   onchange="this.form.submit()">
                                                        </form>
                                                    @endif
                                                @else
                                                    @if($task->document)
                                                        <button class="btn btn-primary btn-sm" onclick="openModal('{{ asset('storage/' . $task->document) }}')">
                                                            Кўриш
                                                        </button>
                                                    @else
                                                        <p>Файл йуқ</p>
                                                    @endif
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
                                                <div class="hstack gap-2 justify-content-end">
                                                    @if(auth()->user()->role == 'xodim'  )

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
    <script>
        function printTable() {
            // Jadvalni clone qilib olamiz.
            let originalTable = document.querySelector('#proposalList');
            let clonedTable = originalTable.cloneNode(true);

            // "Топшириқ файли" va "Таҳрирлаш" ustunlarini topamiz (9- va 10-ustun)
            const removeColumns = [7, 8]; // 0-indeksdan boshlab

            // Theaddagi ustunlarni o‘chirish
            let theadRow = clonedTable.querySelector('thead tr');
            removeColumns.slice().reverse().forEach(index => {
                theadRow.deleteCell(index);
            });

            // Tbodydagi har bir qatordagi shu ustunlarni o‘chirish
            clonedTable.querySelectorAll('tbody tr').forEach(row => {
                removeColumns.slice().reverse().forEach(index => {
                    row.deleteCell(index);
                });
            });

            // CSS style
            let style = `
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 12px;
                }
                th, td {
                    border: 1px solid #000;
                    padding: 5px;
                    text-align: left;
                }
                th {
                    background-color: #f8f8f8;
                }
            </style>
        `;

            // Yangi oynada chiqarish..
            let printWindow = window.open('', '', 'height=800,width=1000');
            printWindow.document.write('<html><head><title>Топшириқлар рўйхати</title>');
            printWindow.document.write(style);
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h3>Топшириқлар рўйхати</h3>');
            printWindow.document.write(clonedTable.outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }
    </script>

    <script>
        function downloadAsWord() {
            let originalTable = document.querySelector('#proposalList');
            let clonedTable = originalTable.cloneNode(true);

            const removeColumns = [7, 8]; // 0-indeksdan boshlab
            let theadRow = clonedTable.querySelector('thead tr');
            removeColumns.slice().reverse().forEach(index => theadRow.deleteCell(index));

            clonedTable.querySelectorAll('tbody tr').forEach(row => {
                removeColumns.slice().reverse().forEach(index => row.deleteCell(index));
            });

            // Word fayli uchun style
            let style = `
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 12px;
                }
                th, td {
                    border: 1px solid #000;
                    padding: 5px;
                    text-align: left;
                }
                th {
                    background-color: #f0f0f0;
                }
            </style>
        `;

            let html = `
            <html xmlns:o='urn:schemas-microsoft-com:office:office'
                  xmlns:w='urn:schemas-microsoft-com:office:word'
                  xmlns='http://www.w3.org/TR/REC-html40'>
            <head><meta charset='utf-8'>${style}</head>
            <body>
                <h3>Топшириқлар рўйхати</h3>
                ${clonedTable.outerHTML}
            </body>
            </html>
        `;

            let blob = new Blob(['\ufeff', html], {
                type: 'application/msword'
            });

            let url = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(html);
            let link = document.createElement('a');
            link.href = url;
            link.download = 'topshiriqlar.doc';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
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










    @include('components.admin.project.project-modal-create',['users' => $users])
    @include('components.admin.project.project-modal-edit', ['tasks' => $tasks])

@endsection
