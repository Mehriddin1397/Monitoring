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
                <h2 class="fs-14 fw-bold text-truncate-1-line">Yaratish</h2>
                <span class="fs-12 fw-normal text-muted text-truncate-1-line">Yaratish</span>
            </a>
        </div>

    </div>
    <div class="offcanvas-body">
        <form action="{{ route('group-photos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold">Guruh rasmlari (bir nechta tanlash mumkin):</label>
                        <input type="file" name="photos[]" class="form-control" multiple accept="image/*" required>
                        <small class="text-muted d-block mt-2">
                            <i class="feather-info me-1"></i> Bir vaqtning o'zida bir nechta rasmni tanlab yuklashingiz mumkin.
                            Formatlar: JPG, PNG, WEBP, JFIF, GIF. Maksimal hajm: 25MB gacha.
                        </small>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="feather-upload me-2"></i> Rasmlarni yuklash
                    </button>
                </div>
            </div>
        </form>
    </div>



</div>
<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
