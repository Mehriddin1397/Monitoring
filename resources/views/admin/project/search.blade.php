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
                            <h2>Qidiruv natijalari: "{{ $query }}"</h2>
                        @if($projects->isEmpty())
                            <div class="alert alert-warning">Hech qanday loyiha topilmadi.</div>
                        @else
                            <table class="table table-hover " id="proposalList">
                                <thead style="background-color: #c7c7f0">
                                <tr>
                                    <th style="font-weight: bold; font-size: 13px; color: #333;">№</th>
                                    <th style="font-weight: bold; font-size: 13px; color: #333;">Loyiha nomi</th>
                                    <th style="font-weight: bold; font-size: 13px; color: #333;">Loyiha hujjati(pdf)</th>
                                    <th style="font-weight: bold; font-size: 13px; color: #333; text-align: center">Loyiha rahbari</th>
                                    <th style="font-weight: bold; font-size: 13px; color: #333;">ilmiy Guruh tarkibi</th>
                                    <th style="font-weight: bold; font-size: 13px; color: #333;">Loyihani moliyalashtirish manbasi</th>
                                    <th style="font-weight: bold; font-size: 13px; color: #333;">loyihani boshlangan vaqti</th>
                                    <th style="font-weight: bold; font-size: 13px; color: #333;">loyihani Tugash vaqti</th>
                                    <th class="text-end">Tahrirlash</th>
                                </tr>
                                </thead>
                                <tbody style="background-color: #e7e7f3">
                                @foreach($projects as $project)
                                    @php
                                        $deadline = \Carbon\Carbon::parse($project->deadline);
                                        $daysLeft = \Carbon\Carbon::today()->diffInDays($deadline, false);
                                        $color = 'text-success';

                                        if ($daysLeft <= 5) $color = 'text-danger';
                                        elseif ($daysLeft <= 16) $color = 'text-warning';
                                    @endphp
                                    <tr class="single-item">
                                        <td> {{ $loop->iteration }}</td>
                                        <td>
                                            {{ $project->name }}
                                        </td>
                                        @php
                                            $pulParticipants = $project->participants->where('type', 'pul');
                                            $freeParticipants = $project->participants->where('type', 'free');
                                        @endphp
                                        <td>
                                            <a href="{{ route('projects.file', ['id' => $project->id, 'type' => 'buyruq']) }}" >
                                                Hujjatini ochish
                                            </a>


                                        </td>
                                        <td>
                                            {{$project->pro_bos_name}}
                                        </td>
                                        <td>
                                            @foreach($pulParticipants as $pul)
                                                {{$pul->name}} <br>
                                            @endforeach
                                        </td>

                                        <td>
                                            {{$project->pro_moliya}}
                                        </td>
                                        <td>
                                            {{$project->start_date}}
                                        </td>
                                        <td>
                                            <p class="{{ $color }}">
                                                {{$project->deadline}}
                                            </p>
                                        </td>

                                        <td>
                                            <div class="hstack gap-2 justify-content-end">
                                                <a href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#tasksDetailsOffcanvasEdit{{ $project->id }}" class="avatar-text avatar-md">
                                                    <i class="feather feather-edit-3"></i>
                                                </a>
                                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="avatar-text avatar-md" onclick="return confirm('Are you sure?')">
                                                        <i class="feather feather-trash-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <style>
                                    .text-fonds{
                                        font-weight: bold;
                                        font-size: 20px;
                                        color: #333;"
                                    }
                                </style>
                            </table>
                        @endif
                    </div>

                    <div class="mb-2">
                        <a href="{{route('projects.index')}}" class="btn btn-primary mt-4">Ortga</a>
                    </div>

                </div>
            </div>
        </div>
@endsection
