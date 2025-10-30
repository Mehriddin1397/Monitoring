<!--! ================================================================ !-->
@foreach($articles as $article )
    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="tasksDetailsOffcanvasEdit{{ $article->id }}">
        <div class="offcanvas-header border-bottom" style="padding-top: 20px; padding-bottom: 20px">
            <div class="d-flex align-items-center">
                <div class="avatar-text avatar-md items-details-close-trigger" data-bs-dismiss="offcanvas"
                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Details Close">
                    <i class="feather-arrow-left"></i>
                </div>
                <span class="vr text-muted mx-4"></span>
                <a href="javascript:void(0);">
                    <h2 class="fs-14 fw-bold text-truncate-1-line">Топшириқлар</h2>
                    <span class="fs-12 fw-normal text-muted text-truncate-1-line">Ўзгартириш</span>
                </a>
            </div>
        </div>

        <div class="offcanvas-body">
            <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-">
                        <div class="form-group mb-4">
                            <label class="form-label">Мақола номи:</label>
                            <textarea name="title" class="form-control ckeditor" rows="10">{{old('title',$article->title)}}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="categories">Нашр этилган жой:</label>
                            <input type="text" name="publish_place" placeholder="Nashr etilgan joy" value="{{ old('publish_place', isset($article) ? $article->publish_place : '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="categories">Мақола пдфи:</label>
                            <input type="file" name="article_pdf">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary d-inline-block mt-4">Сақлаш</button>
                </div>
            </form>
        </div>

    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.ckeditor').forEach(function (el) {
                // CKEditor ishlashi uchun elementda id bo‘lishi kerak
                if (!el.id) {
                    el.id = 'ckeditor-' + Math.random().toString(36).substr(2, 9);
                }

                // Agar allaqachon CKEditor ulangani bo‘lsa, qayta ulamaslik
                if (!CKEDITOR.instances[el.id]) {
                    CKEDITOR.replace(el.id);
                }
            });
        });
    </script>

@endforeach

<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
