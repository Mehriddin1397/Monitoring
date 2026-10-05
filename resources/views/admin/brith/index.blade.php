@extends('layouts.admin')

@section('title', 'Yangiliklar')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->
        <div class="page-header position-fixed">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Tug'ilgan kun</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item">Tug'ilgan kun</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex d-md-none">
                        <a href="javascript:void(0)" class="page-header-right-close-toggle">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Back</span>
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('brith') }}" target="_blank" class="btn btn-outline-warning">
                            <i class="feather-external-link me-2"></i>
                            <span>Ekranni ko'rish (/brith)</span>
                        </a>
                        <a href="{{ route('employee-works.index') }}" class="btn btn-info text-white">
                            <i class="feather-book-open me-2"></i>
                            <span>Xodimlar ijodi</span>
                        </a>
                        <a href="{{ route('group-photos.index') }}" class="btn btn-secondary">
                            <i class="feather-image me-2"></i>
                            <span>Guruh rasmlari</span>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas"
                           data-bs-target="#tasksDetailsOffcanvas">
                            <i class="feather-plus me-2"></i>
                            <span>Yaratish</span>
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
        <!-- [ page-header ] end -->
        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="proposalList">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Photo</th>
                                        <th>F.I.Sh</th>
                                        <th>Lavozimi</th>
                                        <th>Tug'ilgan kuni</th>
                                        <th>Telefon raqami</th>
                                        <th>Dizayn</th>
                                        <th class="text-end">Tahrirlash</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employees as $index => $employee)
                                        <tr class="single-item">
                                            <th>{{$index +1 }}</th>
                                            <td>
                                                <img src="{{ asset('storage/' . $employee->photo) }}" alt=""
                                                     width="36" height="36" class="rounded-circle object-fit-cover shadow-sm">
                                            </td>
                                            <td>
                                                <strong>{{ $employee->full_name }}</strong>
                                                @if($employee->custom_wish)
                                                    <span class="badge bg-soft-primary text-primary ms-1" title="Maxsus tabrik bor"><i class="feather-message-square"></i></span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $employee->position }}
                                            </td>
                                            <td>
                                                {{$employee->birth_date}}
                                            </td>
                                            <td>
                                                {{$employee->phone}}
                                            </td>
                                            <td>
                                                @php
                                                    $th = $employee->theme ?? 'random';
                                                @endphp
                                                @if($th === 'men_classic')
                                                    <span class="badge bg-warning text-dark">🎖️ Medalyon</span>
                                                @elseif($th === 'men_diplomat')
                                                    <span class="badge bg-primary">🏛️ Diplomatik</span>
                                                @elseif($th === 'men_zafar')
                                                    <span class="badge bg-info text-dark">⭐ Zafarnoma</span>
                                                @elseif($th === 'women_rose')
                                                    <span class="badge bg-danger">🌹 Atirgullar</span>
                                                @elseif($th === 'women_emerald')
                                                    <span class="badge bg-success">🌿 Zumrad</span>
                                                @elseif($th === 'women_pearl')
                                                    <span class="badge text-dark" style="background:#fbcfe8;">💎 Marvarid</span>
                                                @else
                                                    <span class="badge bg-secondary">🎲 Random ({{ $employee->gender == 'female' ? 'Ayol' : 'Erkak' }})</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="hstack gap-2 justify-content-end">
                                                    <a href="javascript:void(0)" data-bs-toggle="offcanvas"
                                                       data-bs-target="#tasksDetailsOffcanvasEdit{{ $employee->id }}"
                                                       class="avatar-text avatar-md">
                                                        <i class="feather feather-edit-3"></i>
                                                    </a>
                                                    <form action="{{ route('employees.destroy', $employee->id) }}"
                                                          method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="avatar-text avatar-md"
                                                                onclick="return confirm('Rostan uchirasizmi?')">
                                                            <i class="feather feather-trash-2"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    @include('components.admin.brith.brith-modal-create')
    @include('components.admin.brith.brith-modal-edit')

@endsection
