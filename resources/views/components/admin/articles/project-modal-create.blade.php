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
                <h2 class="fs-14 fw-bold text-truncate-1-line">Мақола</h2>
                <span class="fs-12 fw-normal text-muted text-truncate-1-line">Яратиш</span>
            </a>
        </div>

    </div>
    <div class="offcanvas-body">
        <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-">
                    <div class="form-group mb-4">
                        <label class="form-label">Мақола номи:</label>
                        <textarea name="title" class="form-control ckeditor" rows="10"></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label">Мақола эгаси:</label>
                        <select name="participants[]"  size="17" class="form-control" required multiple>
                            @foreach($participants as $p)
                                    <option value="{{$p->id}}">{{ $p->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label" for="categories">Нашр этилган жой:</label>
                        <input type="text" name="publish_place" placeholder="Nashr etilgan joy">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label" for="categories">Мақола пдфи:</label>
                        <input type="file" name="article_pdf">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label" for="categories">Хулоса пдфи:</label>
                        <input type="file" name="conclusion_pdf">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label" for="categories">Таърифлар сони:</label>
                        <input type="number" name="definitions" placeholder="Ta'riflar soni">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label" for="categories">Таснифлар сони:</label>
                        <input type="number" name="classifications" placeholder="Tasniflar soni">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label" for="categories">Таклифлар сони:</label>
                        <input type="number" name="suggestions" placeholder="Takliflar soni">
                    </div>
                </div>




                <button type="submit" class="btn btn-primary d-inline-block mt-4">Топшириқни қўшиш</button>
            </div>
        </form>
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


</div>
<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
