@extends('layouts.admin')

@section('title', 'Takliflar')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->
        <div class="page-header " style="background-color: #7878a3">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10 ">Takliflar</h5>
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
                <div class="card stretch stretch-full">
                    <hr class="mt-0">
                    <div class="card-body general-info">
                        <form action="{{ route('suggestions.store') }}" method="POST">
                            @csrf

                            <div class="row mb-4 align-items-center">
                                <div class="col-lg-4">
                                    <label for="descriptionInput" class="fw-semibold">Taklif: </label>
                                </div>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="feather-type"></i></div>
                                        <textarea
                                            class="form-control @error('suggestion') is-invalid @enderror"
                                            id="descriptionInput"
                                            name="suggestion"
                                            cols="30"
                                            rows="5"
                                            placeholder="Taklifingizni yozing">{{ old('suggestion') }}</textarea>
                                    </div>
                                    @error('suggestion')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                                <button type="submit" class="btn btn-primary">
                                    <i class="feather-plus me-2"></i>
                                    <span>Taklif qo'shish</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-12">

                    <div class="card-body p-0">
                        <div class="table-responsive table-container">
                            <table class="table table-hover " id="proposalList">
                                <thead style="background-color: #c7c7f0">
                                <tr>
                                    <th style="font-weight: bold; font-size: 13px; color: #333;">№</th>
                                    <th style="font-weight: bold; font-size: 13px; color: #333;">F.I.Sh</th>
                                    <th style="font-weight: bold; font-size: 13px; color: #333;">Taklif matni </th>
                                    <th class="text-end">Tahrirlash</th>
                                </tr>
                                </thead>
                                <tbody style="background-color: #e7e7f3">
                                @if($suggestions)

                                @foreach($suggestions as $suggestion)
                                    <tr class="single-item">
                                        <td>  {{$loop->iteration }}</td>
                                        <td>
                                             {{$suggestion->user->name }}
                                        </td>
                                        <td>
                                            {{$suggestion->suggestion }}
                                        </td>
                                        <td>
                                            <div class="hstack gap-2 justify-content-end">
                                                @if(auth()->user()->role == 'admin')
                                                <form action="{{route('suggestions.destroy', $suggestion->id)}}"
                                                      method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="avatar-text avatar-md"
                                                            onclick="return confirm('Uchirasizmi ?')">
                                                        <i class="feather feather-trash-2"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection
