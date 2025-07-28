<!--! ================================================================ !-->
@foreach($tasks as $task )
    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="tasksDetailsOffcanvasEdit{{ $task->id }}">
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
            <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="form-group mb-6">
                        <label class="form-label">Топшириқ номи :</label>
                        <textarea name="title" class="form-control ckeditor" >{{old('title',$task->title)}}</textarea>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Ходимлар:</label>
                            <select name="assigned_users[]" size="17" class="form-control" multiple>
                                @foreach($users as $user)
                                    @if($user->role == 'xodim')
                                        <option value="{{ $user->id }}"
                                                @if(collect(old('assigned_users', isset($task) ? $task->assignedUsers->pluck('id')->toArray() : []))->contains($user->id))
                                                    selected
                                            @endif
                                        >{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="start_date" class="form-label">Топшириқ берилган сана:</label>
                            <input type="date" class="form-control" name="start_date"
                                   value="{{ old('start_date', isset($task) ? $task->start_date : '') }}">
                        </div>

                        <div class="form-group mb-4">
                            <label for="end_date" class="form-label">Топшириқ тугаш муддати(сана):</label>
                            <input type="date" class="form-control" name="end_date"
                                   value="{{ old('end_date', isset($task) ? $task->end_date : '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label" for="categories">Топшириқни ким берган:</label>
                            <select name="categories[]" class="form-select form-control">
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}"
                                            @if($task->categories->contains($category->id)) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary d-inline-block mt-4">Сақлаш</button>

                </div>
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



            CKEDITOR.replace('editor45');
            document.querySelectorAll('.ckeditor').forEach((el) => {
                CKEDITOR.replace(el);
            });
        </script>
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
