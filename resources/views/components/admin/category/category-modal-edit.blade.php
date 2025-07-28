<!--! ================================================================ !-->
@foreach($categories as $category )
    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="tasksDetailsOffcanvasEdit{{ $category->id }}">
        <div class="offcanvas-header border-bottom" style="padding-top: 20px; padding-bottom: 20px">
            <div class="d-flex align-items-center">
                <div class="avatar-text avatar-md items-details-close-trigger" data-bs-dismiss="offcanvas"
                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Details Close">
                    <i class="feather-arrow-left"></i>
                </div>
                <span class="vr text-muted mx-4"></span>
                <a href="javascript:void(0);">
                    <h2 class="fs-14 fw-bold text-truncate-1-line">Kategoriya</h2>
                    <span class="fs-12 fw-normal text-muted text-truncate-1-line">Kategoriya o'zgartirish</span>
                </a>
            </div>
        </div>

        <div class="offcanvas-body">
            <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Kategoriya nomi(uz):</label>
                    <input type="text" class="form-control" name="name" value="{{ $category->name }}" required>
                </div>
                <div class="form-group mb-4">
                    <label for="object_type">Obyekt turi:</label>
                    <select name="object_type" required class="form-select form-control">
                        <option value="tasks" {{ old('object_type', $category->object_type ?? '') == 'tasks' ? 'selected' : '' }}>
                            Topshiriqlar
                        </option>
                        <option value="baza" {{ old('object_type', $category->object_type ?? '') == 'baza' ? 'selected' : '' }}>
                            Elektron baza
                        </option>
                    </select>
                </div>


                <button type="submit" class="btn btn-primary">Yangilash</button>
            </form>
        </div>
        </div>
    </div>
@endforeach

<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
