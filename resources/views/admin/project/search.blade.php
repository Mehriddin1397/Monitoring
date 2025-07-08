@extends('layouts.admin')

@section('title', 'Search')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->

        <div class="main-content">
            <div class="card mb-0">
                <div class="card-body" style="min-height: 80vh">
                    <!-- Order Information -->
                    <div class="mb-5">
                        <h2>Қидирув натижалари: " {{ $search }} "</h2>
                        @if($tasks->isEmpty())
                            <div class="alert alert-warning">Неч қандай натижа топилмади.</div>
                        @else
                            <table class="table table-hover " id="proposalList">
                                <thead class="sticky-top " style="background-color: #c7c7f0; ">
                                <tr>
                                    <th style="font-weight: bold; font-size: 13px; color: #333;">№</th>
                                    <th style="font-weight: bold; font-size: 13px; color: #333;">Берилган топшириқ
                                    </th>
                                    {{--                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Nazorat uchun--}}
                                    {{--                                        </th>--}}
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
                                    <th style="font-weight: bold; font-size: 13px; color: #333;">Топшириқ ижроси
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
                                                @if($task->status == 'yangi')
                                                    Янги
                                                @elseif($task->status == 'bajarilmoqda')
                                                    Бажарилмоқда
                                                @else
                                                    Бажарилди
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


                                                <!-- Modalni ochuvchi tugma -->
                                            @if($task->assignedUsers->contains(Auth::user()->id))

                                            @endif

                                            @if($task->assignedUsers->contains(Auth::user()->id))
                                                @if($task->document)
                                                    Yuklangan
                                                @else
                                                    <form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                        <input type="file" name="document" onchange="this.form.submit()">
                                                    </form>
                                                @endif
                                            @else
                                                @if($task->document)
                                                    <a href="{{ asset('storage/' . $task->document) }}" class="btn btn-success" download>
                                                        Юклаш
                                                    </a>
                                                @else
                                                    <p>Файл йуқ</p>
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
                        @endif
                    </div>

                    <div class="mb-2">
                        <a href="{{route('tasks.index')}}" class="btn btn-primary mt-4">Ортга</a>
                    </div>

                </div>
            </div>
        </div>
    @include('components.admin.project.project-modal-edit', ['tasks' => $tasks])
@endsection
