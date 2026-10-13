<!--! ================================================================ !-->
@foreach($articles as $article)
    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="tasksDetailsOffcanvasEdit{{ $article->id }}">
        <div class="offcanvas-header border-bottom py-3 px-4 bg-light">
            <div class="d-flex align-items-center">
                <div class="avatar-text avatar-md items-details-close-trigger cursor-pointer" data-bs-dismiss="offcanvas"
                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Ёпиш">
                    <i class="feather-arrow-left"></i>
                </div>
                <span class="vr text-muted mx-3"></span>
                <div>
                    <h6 class="fs-14 fw-bold text-dark mb-0">Мақолани таҳрирлаш</h6>
                    <span class="fs-12 text-muted">ID #{{ $article->id }} маълумотларини янгилаш</span>
                </div>
            </div>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-4">
            <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">Мақола номи: <span class="text-danger">*</span></label>
                            <textarea name="title" class="form-control ckeditor" rows="6" required>{{ old('title', $article->title) }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold" for="publish_place_{{ $article->id }}">Нашр этилган жой: <span class="text-danger">*</span></label>
                            <input type="text" id="publish_place_{{ $article->id }}" name="publish_place" class="form-control"
                                   placeholder="Nashr etilgan joy"
                                   value="{{ old('publish_place', $article->publish_place) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">Янги мақола PDF (ихтиёрий):</label>
                            <input type="file" name="article_pdf" accept="application/pdf" class="form-control">
                            @if($article->article_pdf)
                                <small class="text-success fs-11 mt-1 d-block">
                                    <i class="feather-check me-1"></i> Ҳозирги файл мавжуд
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 pt-3 border-top d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="offcanvas">Бекор қилиш</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="feather-save me-1"></i> Сақлаш
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach
<!--! ================================================================ !-->
