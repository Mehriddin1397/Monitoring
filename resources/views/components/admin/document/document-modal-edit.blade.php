<!--! [Start] Document Edit Offcanvas !-->
@foreach($documents as $document)
    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="tasksDetailsOffcanvasEdit{{ $document->id }}">
        <div class="offcanvas-header border-bottom py-3">
            <div class="d-flex align-items-center">
                <div class="avatar-text avatar-md items-details-close-trigger" data-bs-dismiss="offcanvas" title="Ёпиш">
                    <i class="feather-arrow-left"></i>
                </div>
                <span class="vr text-muted mx-3"></span>
                <div>
                    <h2 class="fs-14 fw-bold mb-0">Ҳужжатни таҳрирлаш</h2>
                    <span class="fs-12 text-muted">ID: #{{ $document->id }}</span>
                </div>
            </div>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ҳужжат бўлими (Категория):</label>
                    <select class="form-select" name="category_id" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $document->categories->contains('id', $cat->id) ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ҳужжат номи:</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $document->name) }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Файлни янгилаш (Ихтиёрий):</label>
                    <input type="file" class="form-control" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.ppt,.pptx">
                    <small class="text-muted d-block mt-1">
                        Жорий файл: <span class="badge bg-light text-dark border">{{ $document->extension ? strtoupper($document->extension) : 'Файл' }}</span>
                        ({{ $document->file_size_formatted }})
                    </small>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Бекор қилиш</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="feather-check me-1"></i> Янгилаш
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach
<!--! [End] Document Edit Offcanvas !-->
