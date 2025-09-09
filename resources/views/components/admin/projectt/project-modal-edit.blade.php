<!--! ================================================================ !-->
@foreach($projects as $project )
    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="tasksDetailsOffcanvasEdit{{ $project->id }}">
        <div class="offcanvas-header border-bottom" style="padding-top: 20px; padding-bottom: 20px">
            <div class="d-flex align-items-center">
                <div class="avatar-text avatar-md items-details-close-trigger" data-bs-dismiss="offcanvas"
                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Details Close">
                    <i class="feather-arrow-left"></i>
                </div>
                <span class="vr text-muted mx-4"></span>
                <a href="javascript:void(0);">
                    <h2 class="fs-14 fw-bold text-truncate-1-line">Лойиҳа</h2>
                    <span class="fs-12 fw-normal text-muted text-truncate-1-line">Лойиҳани ўзгартириш</span>
                </a>
            </div>
        </div>

        <div class="offcanvas-body">
            <form action="{{ route('projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Лойиҳа номи:</label>
                    <textarea name="name"  class="form-control ckeditor">{{old('name',$project->name)}}</textarea>
                </div>

                <div class="mb-3">
                    <label for="pro_bos_name" class="form-label">Маъсул ижрочи Ф.И.Ш, тел рақ:</label>
                    <textarea name="pro_bos_name"  class="form-control ckeditor">{{old('pro_bos_name',$project->pro_bos_name)}}</textarea>
                </div>

                <div class="form-group mb-4">
                    <label for="name_free_count" class="form-label">Молиялаштириш манбаси ва суммаси:</label>
                    <textarea name="manba"  class="form-control ckeditor">{{old('manba',$project->manba)}}</textarea>
                </div>

                <div class="mb-3">
                    <label for="job" class="form-label">Лойиха рахбарининг Иш жойи ва лавозими:</label>
                    <textarea name="job"  class="form-control ckeditor">{{old('job',$project->job)}}</textarea>
                </div>

                <div class="mb-3">
                    <label for="file_buyruq" class="form-label">Лойиҳа буйруғи(pdf)</label>
                    <input type="file" class="form-control" name="file_buyruq">
                </div>

                <div class="mb-3">
                    <label for="file_qushimcha" class="form-label">Лойиҳа қўшимча буйруғи(pdf)</label>
                    <input type="file" class="form-control" name="file_qushimcha">
                </div>

                <div class="mb-3">
                    <label class="form-label">Гурух таркиби</label>
                    <div id="pul-participants">
                        @foreach($project->participants->where('type', 'pul') as $participant)
                            <input type="text" class="form-control mb-2" name="name_pul[]" value="{{ $participant->name }}" >
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="addParticipant('pul')">+ Қўшиш</button>
                </div>
                <div class="form-group mb-4">
                    <label for="name_free_count" class="form-label">Изох:</label>
                    <textarea name="izoh"  class="form-control ckeditor">{{old('izoh',$project->izoh)}}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Yangilash</button>
            </form>
        </div>

        <script>
            function addParticipant(type) {
                let container = type === 'pul' ? document.getElementById('pul-participants') : document.getElementById('free-participants');
                let input = document.createElement('input');
                input.type = 'text';
                input.className = 'form-control mb-2';
                input.name = type === 'pul' ? 'name_pul[]' : 'name_free[]';
                input.required = true;
                container.appendChild(input);
            }
        </script>
        </div>
    </div>
@endforeach

<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
