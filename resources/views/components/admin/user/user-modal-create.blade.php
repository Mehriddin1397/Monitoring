<!--! [Start] Tasks Details Offcanvas !-->
<!--! ================================================================ !-->
<div class="offcanvas offcanvas-end w-50" tabindex="-1" id="tasksDetailsOffcanvas" xmlns="http://www.w3.org/1999/html">
    <div class="offcanvas-header border-bottom" style="padding-top: 20px; padding-bottom: 20px">
        <div class="d-flex align-items-center">
            <div class="avatar-text avatar-md items-details-close-trigger" data-bs-dismiss="offcanvas"
                 data-bs-toggle="tooltip" data-bs-trigger="hover" title="Details Close"><i
                    class="feather-arrow-left"></i></div>
            <span class="vr text-muted mx-4"></span>
            <a href="javascript:void(0);">
                <h2 class="fs-14 fw-bold text-truncate-1-line">Slide </h2>
                <span class="fs-12 fw-normal text-muted text-truncate-1-line">Loyiha yaratish</span>
            </a>
        </div>

    </div>
    <div class="offcanvas-body">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label">Ism va familiya:</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group mb-4">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group mb-4">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option> </option>
                            @foreach(['boshliq', 'xodim'] as $role)
                                <option >{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        @if(!isset($user))
                            <div class="mb-3">
                                <label>Parol</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        @else
                            <div class="mb-3">
                                <label>Yangi parol (ixtiyoriy)</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                        @endif
                    </div>
                    <div class="form-group mb-4">
                            <div class="mb-3">
                                <label>Maxsus kod</label>
                                <input type="text" name="auth_code" class="form-control">
                            </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary d-inline-block mt-4">Loyihani qo'shish</button>
            </div>
        </form>
    </div>

</div>
<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
