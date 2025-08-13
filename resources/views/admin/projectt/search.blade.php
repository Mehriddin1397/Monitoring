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
                            <table class="table table-hover" id="proposalList">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Loyiha</th>
                                    <th>Buyruq</th>
                                    <th>Qo'shimcha buyruq</th>
                                    <th>Guruh tarkibi</th>
                                    <th>Guruh tarkibi jamoatchilik asosida</th>
                                    <th>F.I.Sh</th>
                                    <th>Tel_number</th>
                                    <th>Ish joyi</th>
                                    <th class="text-end">Tahrirlash</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($projects as $index => $project)
                                    <tr class="single-item">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $project->name }}
                                        </td>
                                        @php
                                            $pulParticipants = $project->participants->where('type', 'pul');
                                            $freeParticipants = $project->participants->where('type', 'free');
                                        @endphp
                                        <td>
                                            <a href="#">
                                                Buyruq fayli
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#">
                                                Qo'shimcha buyruq
                                            </a>
                                        </td>
                                        <td>
                                            @foreach($pulParticipants as $pul)
                                                {{$pul->name}} <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($freeParticipants as $pul)
                                                {{$pul->name}} <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{$project->pro_bos_name}}
                                        </td>
                                        <td>
                                            {{$project->tel_number}}
                                        </td>
                                        <td>
                                            {{$project->job}}
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
