<!--! ================================================================ !-->
@foreach($employees as $employee )
    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="tasksDetailsOffcanvasEdit{{ $employee->id }}">
        <div class="offcanvas-header border-bottom" style="padding-top: 20px; padding-bottom: 20px">
            <div class="d-flex align-items-center">
                <div class="avatar-text avatar-md items-details-close-trigger" data-bs-dismiss="offcanvas"
                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Details Close">
                    <i class="feather-arrow-left"></i>
                </div>
                <span class="vr text-muted mx-4"></span>
                <a href="javascript:void(0);">
                    <h2 class="fs-14 fw-bold text-truncate-1-line">Tug'ilgan kun</h2>
                    <span class="fs-12 fw-normal text-muted text-truncate-1-line"> O'zgartirish</span>
                </a>
            </div>
        </div>

        <div class="offcanvas-body">
            <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Tuliq F.I.Sh:</label>
                            <input type="text" name="full_name" value="{{old('name_uz',$employee->full_name)}}"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Telefon raqami:</label>
                            <input type="text" name="phone" value="{{old('name_ru',$employee->phone)}}"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Lavozimi:</label>
                            <input type="text" name="position" value="{{old('name_en',$employee->position)}}"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Tug'ilgan kuni:</label>
                            <input type="date" name="birth_date" value="{{old('name_kr',$employee->birth_date)}}"
                                   class="form-control">
                        </div>
                    </div>
                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="Question Image"
                         class="img-fluid mt-2" width="150">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Rasmi:</label>
                            <input type="file" name="photo" class="form-control" >
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary d-inline-block mt-4">Saqlash</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll('.ckeditor').forEach((el) => {
            CKEDITOR.replace(el);
        });
    </script>
    </div>
@endforeach

<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
