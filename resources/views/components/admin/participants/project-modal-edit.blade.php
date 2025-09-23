<!--! ================================================================ !-->
@foreach($participants as $user )
    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="tasksDetailsOffcanvasEdit{{ $user->id }}">
        <div class="offcanvas-header border-bottom" style="padding-top: 20px; padding-bottom: 20px">
            <div class="d-flex align-items-center">
                <div class="avatar-text avatar-md items-details-close-trigger" data-bs-dismiss="offcanvas"
                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Details Close">
                    <i class="feather-arrow-left"></i>
                </div>
                <span class="vr text-muted mx-4"></span>
                <a href="javascript:void(0);">
                    <h2 class="fs-14 fw-bold text-truncate-1-line">Xodimlar</h2>
                    <span class="fs-12 fw-normal text-muted text-truncate-1-line">O'zgartirish</span>
                </a>
            </div>
        </div>

        <div class="offcanvas-body">
            <form action="{{ route('participants.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label>F.I.Sh</label>
                            <input type="text" name="full_name" class="form-control"
                                   value="{{ old('full_name', $user->full_name ?? '') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label>Lavozimi</label>
                            <input type="text" name="position" class="form-control"
                                   value="{{ old('position', $user->position ?? '') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label>Ilmiy darajasi</label>
                            <input type="text" name="degree" class="form-control"
                                   value="{{ old('degree', $user->degree ?? '') }}" required>
                        </div>
                    </div>

                </div>
                <button type="submit" class="btn btn-primary d-inline-block mt-4">Yangilash</button>
            </form>
        </div>
    </div>
    </div>
@endforeach

<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
