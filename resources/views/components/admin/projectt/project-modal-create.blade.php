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
                <h2 class="fs-14 fw-bold text-truncate-1-line">Slide </h2>
                <span class="fs-12 fw-normal text-muted text-truncate-1-line">Лойиҳа яратиш</span>
            </a>
        </div>

    </div>
    <div class="offcanvas-body">
        <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label">Лойиҳа номи:</label>
                        <textarea name="name" rows="10" id="editor1" class="form-control"></textarea>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label">Лойиҳа буйруғи(pdf):</label>
                        <input type="file" name="file_buyruq" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label">Лойиҳа қўшимча буйруғи(pdf):</label>
                        <input type="file" name="file_qushimcha" class="form-control">
                    </div>
                    <div class="form-group mb-4">
                        <label for="name_pul_count" class="form-label">Гурух сони:</label>
                        <input type="number" class="form-control" id="name_pul_count" min="1" required>
                    </div>
                    <div id="name_pul"></div>
                    <script>
                        document.getElementById('name_pul_count').addEventListener('input', function() {
                            let count = parseInt(this.value);
                            let container = document.getElementById('name_pul');
                            container.innerHTML = ''; // Avvalgi inputlarni tozalash

                            if (count > 0) {
                                for (let i = 0; i < count; i++) {
                                    let div = document.createElement('div');
                                    div.classList.add('mb-3');

                                    let label = document.createElement('label');
                                    label.innerText = `Қатнашувчи ${i + 1} Ф.И.Ш:`;
                                    div.appendChild(label);

                                    let input = document.createElement('input');
                                    input.type = 'text';
                                    input.name = `name_pul[]`;
                                    input.classList.add('form-control');
                                    input.required = true;
                                    div.appendChild(input);

                                    container.appendChild(div);
                                }
                            }
                        });
                    </script>

                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="name_free_count" class="form-label">Маъсул ижрочи Ф.И.Ш, тел рақ:</label>
                        <textarea name="pro_bos_name" rows="10" id="editor2"  class="form-control "></textarea>
                    </div>
                    <div class="form-group mb-4">
                        <label for="name_free_count" class="form-label">Молиялаштириш манбаси ва суммаси:</label>
                        <textarea name="manba" rows="10"  id="editor3" class="form-control"></textarea>
                    </div>
                    <div class="form-group mb-4">
                        <label for="name_free_count" class="form-label">Лойиха рахбарининг Иш жойи ва лавозими:</label>
                        <textarea name="job" rows="10" id="editor4" class="form-control "></textarea>
                    </div>
                    <div class="form-group mb-4">
                        <label for="name_free_count" class="form-label">Изох:</label>
                        <textarea name="izoh" rows="10" id="editor5"  class="form-control "></textarea>
                    </div>

                </div>
                <button type="submit" class="btn btn-primary d-inline-block mt-4">Loyihani qo'shish</button>
            </div>
        </form>
    </div>

</div>
<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
