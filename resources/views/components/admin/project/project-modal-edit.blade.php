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
                    <h2 class="fs-14 fw-bold text-truncate-1-line">Loyiha</h2>
                    <span class="fs-12 fw-normal text-muted text-truncate-1-line">Loyihani o'zgartirish</span>
                </a>
            </div>
        </div>

        <div class="offcanvas-body">
            <form action="{{ route('projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Loyiha nomi</label>
                    <input type="text" class="form-control" name="name" value="{{ $project->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="pro_bos_name" class="form-label">Loyiha boshlig'i</label>
                    <input type="text" class="form-control" name="pro_bos_name" value="{{ $project->pro_bos_name }}" required>
                </div>

                <div class="mb-3">
                    <label for="job" class="form-label">Loyiha boshlanish muddati(sana):</label>
                    <input type="date" class="form-control" name="start_date" value="{{ $project->start_date }}" required>
                </div>
                <div class="mb-3">
                    <label for="job" class="form-label">Loyiha tugash muddati(sana):</label>
                    <input type="date" class="form-control" name="deadline" value="{{ $project->deadline }}" required>
                </div>
                <div class="form-group mb-4">
                    <label for="name_free_count" class="form-label">Loyiha moliyalashtirish manbasi:</label>
                    <input type="text"  name="pro_moliya" class="form-control" value="{{ $project->pro_moliya }}"  required>
                </div>
                <div class="mb-3">
                    <label for="file_buyruq" class="form-label">Buyruq fayli</label>
                    <input type="file" class="form-control" name="file_buyruq">
                </div>

                <div class="mb-3">
                    <label class="form-label">Guruh tarkibi</label>
                    <div id="pul-participants">
                        @foreach($project->participants->where('type', 'pul') as $participant)
                            <input type="text" class="form-control mb-2" name="name_pul[]" value="{{ $participant->name }}" required>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="addParticipant('pul')">+ Qo‘shish</button>
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
