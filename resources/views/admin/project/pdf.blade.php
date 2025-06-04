@extends('layouts.admin')

@section('title', 'Buyruqlar')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->

        <div class="main-content">
            <div class="card mb-0">
                <div class="card-body" style="min-height: 80vh">
                    <!-- Order Information -->
                    <div class="mb-5">

                        <iframe src="{{ asset('storage/'.$filePath) }}" width="100%" height="600px"></iframe>
                    </div>

                    <div class="mb-2">
                        <a href="{{route('projects.index')}}" class="btn btn-primary mt-4">Ortga</a>
                    </div>

                </div>
            </div>
        </div>
@endsection
