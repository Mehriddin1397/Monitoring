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
                <h2 class="fs-14 fw-bold text-truncate-1-line">Топшириқ </h2>
                <span class="fs-12 fw-normal text-muted text-truncate-1-line">Яратиш</span>
            </a>
        </div>

    </div>
    <div class="offcanvas-body">
        <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-">
                    <div class="form-group mb-4">
                        <label class="form-label">Топшириқ номи:</label>
                        <textarea name="title" class="form-control ckeditor" rows="10"></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label">Ходимлар:</label>
                        <select name="assigned_users[]" size="17" class="form-control" required multiple>
                            @foreach($users as $user)
                                @if($user->role == 'xodim')
                                    <option value="{{$user->id}}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="name_free_count" class="form-label">Топшириқ берилган сана:</label>
                        <input type="date" class="form-control" name="start_date" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="name_free_count" class="form-label">Топшириқ тугаш муддати(сана):</label>
                        <input type="date" class="form-control" name="end_date" required>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label" for="categories">Топшириқни ким берган:</label>
                        <select name="categories[]" class="form-select form-control">
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary d-inline-block mt-4">Топшириқни қўшиш</button>
            </div>
        </form>
    </div>


        <script>

        </script>


</div>
<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
