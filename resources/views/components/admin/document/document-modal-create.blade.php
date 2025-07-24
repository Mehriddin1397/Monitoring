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
                <h2 class="fs-14 fw-bold text-truncate-1-line">Файл номи </h2>
                <span class="fs-12 fw-normal text-muted text-truncate-1-line">Яратиш</span>
            </a>
        </div>

    </div>
    <div class="offcanvas-body">
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-">
                    <div class="form-group mb-4">
                        <label for="name_free_count" class="form-label">Хужжат номи:</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="name_free_count" class="form-label">Хужжат файли:</label>
                        <input type="file" class="form-control" name="file" required>
                        <input type="hidden" class="form-control" name="category_id" value="{{$category->id}}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary d-inline-block mt-4">Яратиш</button>
            </div>
        </form>
    </div>



</div>
<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
