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
                        <h2>Қидирув натижалари: "{{ $query }}"</h2>
                        @if($projects->isEmpty())
                            <div class="alert alert-warning">Ҳеч қандай лойиҳа топилмади.</div>
                        @else
                            <table class="table table-hover" id="proposalList">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Лойиҳа номи</th>
                                    <th>Буйруқ</th>
                                    <th>Қўшимча буйруқ</th>
                                    <th>Гурух таркиби</th>
                                    <th>Лойиҳа молиялаштириш манбаси ва суммаси</th>
                                    <th>Лойиҳанинг маъсул ижрочиси Ф.И.Ш, тел рақам</th>
                                    <th>Лойиха рахбарининг Иш жойи ва лавозими</th>
                                    <th>Лойиҳа хисоботлари</th>
                                    <th>Изох</th>
                                    <th class="text-end">Тахрирлаш</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($projects as $project)
                                    <tr class="single-item">
                                        <td> {{ $loop->iteration }}</td>
                                        <td>
                                            {{ $project->name }}
                                        </td>
                                        @php
                                            $pulParticipants = $project->participants->where('type', 'pul');
                                        @endphp
                                        <td>
                                            <a href="{{ route('projects_file', ['id' => $project->id, 'type' => 'buyruq']) }}">
                                                Хужжатни очиш
                                            </a>


                                        </td>
                                        <td>
                                            @if($project->	file_qushimcha)
                                                <a href="{{ route('projects_file', ['id' => $project->id, 'type' => 'qushimcha']) }}">
                                                    Хужжатни очиш
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach($pulParticipants as $pul)
                                                {{$pul->name}} <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{$project->manba}}
                                        </td>
                                        <td>
                                            {{$project->pro_bos_name}}
                                        </td>
                                        <td>
                                            {{$project->job}}
                                        </td>
                                        <td>
                                            {{-- Hujjat yuklash formasi --}}
                                            <form action="{{ route('pro_document.store', $project->id) }}" method="POST"
                                                  enctype="multipart/form-data" style="margin-bottom: 5px;">
                                                @csrf
                                                <input type="file" name="file" required>
                                                <button type="submit">Yuklash</button>
                                            </form>

                                            {{-- Yuklangan hujjatlar --}}
                                            @if($project->pro_documents->count() > 0)
                                                @foreach($project->pro_documents as $doc)
                                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank">
                                                        <button class="button mt-2" type="button">Hujjatni ochish
                                                        </button>
                                                        <br>
                                                    </a>
                                                @endforeach
                                            @else
                                                <span>Hujjat yo‘q</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{$project->izoh}}
                                        </td>

                                        <td>
                                            <div class="hstack gap-2 justify-content-end">
                                                @if($project->user_id == auth()->user()->id)
                                                    <a href="javascript:void(0)" data-bs-toggle="offcanvas"
                                                       data-bs-target="#tasksDetailsOffcanvasEdit{{ $project->id }}"
                                                       class="avatar-text avatar-md">
                                                        <i class="feather feather-edit-3"></i>
                                                    </a>
                                                @endif
                                                @if(auth()->user()->role == 'admin')
                                                    <form action="{{ route('projects.destroy', $project->id) }}"
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
                            </table>
                        @endif
                    </div>

                    <div class="mb-2">
                        <a href="{{route('projects.index')}}" class="btn btn-primary mt-4">Ortga</a>
                    </div>

                </div>
            </div>
        </div>
    @include('components.admin.projectt.project-modal-edit', ['projects' => $projects])
@endsection
