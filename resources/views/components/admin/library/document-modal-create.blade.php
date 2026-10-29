<!--! [Start] Library Create Offcanvas !-->
<div class="offcanvas offcanvas-end w-50" tabindex="-1" id="tasksDetailsOffcanvas">
    <div class="offcanvas-header border-bottom py-3">
        <div class="d-flex align-items-center">
            <div class="avatar-text avatar-md items-details-close-trigger" data-bs-dismiss="offcanvas" title="Ёпиш">
                <i class="feather-arrow-left"></i>
            </div>
            <span class="vr text-muted mx-3"></span>
            <div>
                <h2 class="fs-14 fw-bold mb-0">Янги адабиёт қўшиш</h2>
                <span class="fs-12 text-muted">Электрон кутубхона фонди</span>
            </div>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('library.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Бўлим (Категория):</label>
                <select class="form-select" name="category_id" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ isset($category) && $category && $category->id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Китоб / Адабиёт номи:</label>
                <input type="text" class="form-control" name="name" placeholder="Масалан: Иқтисодиёт асослари" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Муаллиф(лар):</label>
                <input type="text" class="form-control" name="author" placeholder="Масалан: Абдуллаев А.А." required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Китоб файли (PDF):</label>
                <input type="file" class="form-control" name="pdf" accept=".pdf" required>
                <small class="text-muted">Формат: Фақат PDF файли</small>
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
<!--! [End] Library Create Offcanvas !-->
