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
                        <h2>Qidiruv natijalari: " {{ $search }} "</h2>
                        @if($tasks->isEmpty())
                            <div class="alert alert-warning">Hech qanday topshiriq topilmadi.</div>
                        @else
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
                                            {!! $task->title !!}
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
                                            @if($task->status == 'bajarildi')
                                                <p class="text-success">
                                                    Bajarildi
                                            @else
                                                <p class="{{ $color }}">
                                                    {{$daysLeft}} - kun
                                            @endif
                                        </td>
                                        <td>
                                            @if(auth()->user()->id == $task->created_by )
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
                                                @if(auth()->user()->role == 'xodim')

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
                        <a href="{{route('tasks.index')}}" class="btn btn-primary mt-4">Ortga</a>
                    </div>

                </div>
            </div>
        </div>
@endsection
