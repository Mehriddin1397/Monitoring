<!--! ================================================================ !-->
@foreach($works as $work )
    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="tasksDetailsOffcanvasEdit{{ $work->id }}">
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
            <form action="{{ route('ongoing-works.update', $work->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="form-group mb-4">
                        <label class="form-label">Лойиҳа номи:</label>
                        <textarea name="project_name" rows="10" class="form-control ckeditor">{!!old('project_name',$work->project_name)!!}</textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"> Жараёндаги муаммолар:</label>
                        <textarea name="problems" rows="10" class="form-control ckeditor">{!!old('problems',$work->problems)!!}</textarea>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"> Иш жараёни:</label>
                        <textarea name="process" rows="10" class="form-control ckeditor">{!!old('process',$work->process)!!}</textarea>
                        <select name="process_color" class="form-select form-control">
                            @foreach(['#9ad6a4', '#d6cc9a','#cf8787'] as $role)
                                <option
                                    value="{{ $role }}" {{ (old('role', $work->process_color ?? '') == $role) ? 'selected' : '' }} style="background-color: {{$role}} ; color: white;">{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"> Топшириш муддати:</label>
                        <input type="date" class="form-control" name="deadline" value="{{old('deadline',$work->deadline)}}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Сақлаш</button>
            </form>
        </div>
    </div>
    </div>
@endforeach

<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
