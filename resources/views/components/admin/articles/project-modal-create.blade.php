<!--! [Start] Tasks Details Offcanvas !-->
<!--! ================================================================ !-->
<div class="offcanvas offcanvas-end w-50" tabindex="-1" id="tasksDetailsOffcanvas">
    <div class="offcanvas-header border-bottom py-3 px-4 bg-light">
        <div class="d-flex align-items-center">
            <div class="avatar-text avatar-md items-details-close-trigger cursor-pointer" data-bs-dismiss="offcanvas"
                 data-bs-toggle="tooltip" data-bs-trigger="hover" title="Ёпиш">
                <i class="feather-arrow-left"></i>
            </div>
            <span class="vr text-muted mx-3"></span>
            <div>
                <h6 class="fs-14 fw-bold text-dark mb-0">Янги мақола қўшиш</h6>
                <span class="fs-12 text-muted">И-индекс тизимига илмий мақола юклаш</span>
            </div>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-4">
        <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold">Мақола номи: <span class="text-danger">*</span></label>
                        <textarea name="title" class="form-control ckeditor" rows="6" placeholder="Мақоланинг тўлиқ номи ва маълумотлари..." required></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold" for="publish_place">Нашр этилган жой / журнал: <span class="text-danger">*</span></label>
                        <input type="text" id="publish_place" name="publish_place" class="form-control" placeholder="Масалан: Илмий ахборотнома, №4" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold" for="article_pdf">Мақола PDF файли: <span class="text-danger">*</span></label>
                        <input type="file" id="article_pdf" name="article_pdf" accept="application/pdf" class="form-control" required>
                        <small class="text-muted fs-11">Фақат PDF формат (макс: 20MB)</small>
                    </div>
                </div>

                <div class="col-12 pt-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="offcanvas">Бекор қилиш</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="feather-upload me-1"></i> Сақлаш ва юклаш
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
