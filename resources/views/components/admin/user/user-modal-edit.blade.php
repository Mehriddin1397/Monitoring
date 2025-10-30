<!--! ================================================================ !-->
@foreach($users as $user )
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
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label>F.I.Sh</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $user->name ?? '') }}" required>
                        </div>

                        <div class="form-group mb-4">
                            <label>F.I.Sh tuliq</label>
                            <input type="text" name="full_name" class="form-control"
                                   value="{{ old('full_name', $user->full_name ?? '') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label>Lavozimi:</label>
                            <input type="text" name="position" class="form-control"
                                   value="{{ old('position', $user->position ?? '') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label>Ilmiy unvoni:</label>
                            <input type="text" name="degree" class="form-control"
                                   value="{{ old('degree', $user->degree ?? '') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label>Tel_raqami:</label>
                            <input type="text" name="tel_number" class="form-control"
                                   value="{{ old('tel_number', $user->tel_number ?? '') }}" required>
                        </div>

                        <div class="form-group mb-4">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $user->email ?? '') }}" required>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label>Role</label>
                        <select name="role" class="form-control" >
                            <option> </option>
                            @foreach(['boshliq', 'xodim'] as $role)
                                <option
                                    value="{{ $role }}" {{ (old('role', $user->role ?? '') == $role) ? 'selected' : '' }}>{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label>Role</label>
                        <select name="is_scientific" class="form-control" >
                            <option> </option>
                                <option
                                    value="1" {{ (old('is_scientific', $user->is_scientific ?? '') == 1) ? 'selected' : '' }}>Ilmiy xodim
                                </option>
                            <option
                                    value="0" {{ (old('is_scientific', $user->is_scientific ?? '') == 0) ? 'selected' : '' }}>Oddiy xodim
                                </option>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <div class="mb-3">
                            <label>Maxsus kod</label>
                            <input type="text" value="{{old('auth_code',$user->auth_code ?? '')}}" name="auth_code" class="form-control">
                        </div>
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
                </div>
                <button type="submit" class="btn btn-primary d-inline-block mt-4">Yangilash</button>
            </form>
        </div>
    </div>
    </div>
@endforeach

<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
