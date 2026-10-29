<!--! [Start] Document Create Offcanvas !-->
<div class="offcanvas offcanvas-end w-50" tabindex="-1" id="tasksDetailsOffcanvas">
    <div class="offcanvas-header border-bottom py-3">
        <div class="d-flex align-items-center">
            <div class="avatar-text avatar-md items-details-close-trigger" data-bs-dismiss="offcanvas" title="Ёпиш">
                <i class="feather-arrow-left"></i>
            </div>
            <span class="vr text-muted mx-3"></span>
            <div>
                <h2 class="fs-14 fw-bold mb-0">Янги ҳужжат қўшиш</h2>
                <span class="fs-12 text-muted">Меъёрий-ҳуқуқий ҳужжатлар базаси</span>
            </div>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Ҳужжат бўлими (Категория):</label>
                <select class="form-select" name="category_id" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ isset($category) && $category && $category->id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Ҳужжат номи / сарлавҳаси:</label>
                <input type="text" class="form-control" name="name" placeholder="Масалан: 2026 йилги чораклик иш режаси" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Ҳужжат файли (PDF, Word, Excel, PPTX):</label>
                <input type="file" class="form-control" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.ppt,.pptx" required>
                <small class="text-muted">Қўллаб-қувватланади: PDF, DOC, DOCX, XLS, XLSX, ZIP</small>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Бекор қилиш</button>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="feather-upload me-1"></i> Сақлаш
                </button>
            </div>
        </form>
    </div>
</div>
<!--! [End] Document Create Offcanvas !-->
