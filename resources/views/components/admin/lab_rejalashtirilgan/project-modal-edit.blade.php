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
            <form action="{{ route('planned-works.update', $work->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="form-group mb-4">
                        <label class="form-label">Лойиҳа номи:</label>
                        <textarea name="project_name" rows="10"  class="form-control ckeditor">{!!old('project_name',$work->project_name)!!}</textarea>
                    </div>
                    <br>

                    <div class="form-group mb-4">
                        <label class="form-label">Талаб қилинаётган сарф-харажатлар:</label>
                        <textarea name="required_expenses" rows="10"  class="form-control ckeditor">{!!old('required_expenses',$work->required_expenses)!!}</textarea>
                    </div>
                    <br>
                    <div class="form-group mb-4">
                        <label class="form-label">Таёрлаш муддати:</label>
                        <textarea name="preparation_time" rows="10" class="form-control ckeditor">{{old('preparation_time',$work->preparation_time)}}</textarea>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label">Кўрсаткич натижалар:</label>
                        <textarea name="performance_results" rows="10" class="form-control ckeditor">{{old('performance_results',$work->performance_results)}}</textarea>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label">Талаб қилаётган миқдори:</label>
                        <textarea name="required_amount" rows="10" class="form-control ckeditor">{{old('required_amount',$work->required_amount)}}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Yangilash</button>
            </form>
        </div>
    </div>
    </div>
@endforeach

<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
