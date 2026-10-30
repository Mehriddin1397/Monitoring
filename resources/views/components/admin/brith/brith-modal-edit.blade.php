<!--! ================================================================ !-->
<!--! [Start] Employee Edit Offcanvas (Dynamic Single Instance)        !-->
<!--! ================================================================ !-->
<div class="offcanvas offcanvas-end w-50" tabindex="-1" id="tasksDetailsOffcanvasEdit">
    <div class="offcanvas-header border-bottom" style="padding-top: 20px; padding-bottom: 20px">
        <div class="d-flex align-items-center">
            <div class="avatar-text avatar-md items-details-close-trigger" data-bs-dismiss="offcanvas"
                 data-bs-toggle="tooltip" data-bs-trigger="hover" title="Yopish" style="cursor: pointer;">
                <i class="feather-arrow-left"></i>
            </div>
            <span class="vr text-muted mx-4"></span>
            <div>
                <h2 class="fs-14 fw-bold text-truncate-1-line mb-0" id="edit_modal_title">Xodimni tahrirlash</h2>
                <span class="fs-12 fw-normal text-muted text-truncate-1-line">Tug'ilgan kun va shaxsiy ma'lumotlar</span>
            </div>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        <form id="editEmployeeForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- F.I.Sh -->
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label fw-semibold">To'liq F.I.Sh: <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" id="edit_full_name" class="form-control" required>
                    </div>
                </div>

                <!-- Telefon -->
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label fw-semibold">Telefon raqami:</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control" placeholder="+998 90 123 45 67">
                    </div>
                </div>

                <!-- Lavozimi -->
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label fw-semibold">Lavozimi:</label>
                        <input type="text" name="position" id="edit_position" class="form-control">
                    </div>
                </div>

                <!-- Tug'ilgan kuni -->
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label fw-semibold">Tug'ilgan kuni:</label>
                        <input type="date" name="birth_date" id="edit_birth_date" class="form-control">
                    </div>
                </div>

                <!-- Jinsi -->
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label fw-semibold">Jinsi:</label>
                        <select name="gender" id="edit_gender" class="form-select">
                            <option value="male">👨 Erkak</option>
                            <option value="female">👩 Ayol</option>
                        </select>
                    </div>
                </div>

                <!-- Tabriknoma shabloni -->
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label fw-semibold">Tabriknoma shabloni:</label>
                        <select name="theme" id="edit_theme" class="form-select">
                            <option value="random">🎲 Jinsiga mos avtomatik (Random)</option>
                            <optgroup label="👨 Erkaklar uchun salobatli shablonlar:">
                                <option value="men_classic">🎖️ Salobatli Medalyon (Oltin geometrik)</option>
                                <option value="men_diplomat">🏛️ Diplomatik / Sharaf lavhasi (Mahobatli)</option>
                                <option value="men_zafar">⭐ Zafarnoma (Yulduzli orden va shon-sharaf)</option>
                            </optgroup>
                            <optgroup label="👩 Ayollar uchun nafis shablonlar:">
                                <option value="women_rose">🌹 Nafis Bahor va Atirgullar (Gulli hoshiya)</option>
                                <option value="women_emerald">🌿 Zumrad Nafislik (Zarhal gulchambar)</option>
                                <option value="women_pearl">💎 Marvarid va Ipak (Nafis marvaridli ramka)</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <!-- Yangi rasm -->
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label fw-semibold">Yangi rasm yuklash:</label>
                        <input type="file" name="photo" id="edit_photo" class="form-control" accept="image/*">
                        <small class="text-muted">Bo'sh qoldirilsa, amaldagi rasm saqlanadi</small>
                    </div>
                </div>

                <!-- Joriy rasm ko'rinishi -->
                <div class="col-md-6 text-center d-flex align-items-center justify-content-center">
                    <div id="edit_photo_preview" class="p-2 border rounded bg-light" style="min-width: 110px; min-height: 90px;">
                        <span class="text-muted small">Rasm mavjud emas</span>
                    </div>
                </div>

                <!-- Maxsus tabrik -->
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label class="form-label d-flex justify-content-between fw-semibold">
                            <span>Maxsus tabrik so'zi (ixtiyoriy):</span>
                            <small class="text-muted">Bo'sh bo'lsa, standart tilak chiqadi</small>
                        </label>
                        <textarea name="custom_wish" id="edit_custom_wish" rows="3" class="form-control" 
                                  placeholder="Сизга узоқ умр, мустаҳкам соғлик, оилавий бахт ва масъулиятли касбий фаолиятингизда улкан зафарлар тилаймиз!"></textarea>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <small class="text-muted w-100">Tayyor tabriklardan tanlash:</small>
                        <button type="button" class="btn btn-xs btn-outline-secondary" onclick="document.getElementById('edit_custom_wish').value = 'Сизга узоқ умр, сиҳат-саломатлик, оилавий хотиржамлик ва илмий-ижодий фаолиятингизда улкан ютуқлар тилаймиз!'">1-variant</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary" onclick="document.getElementById('edit_custom_wish').value = 'Институтимиз ривожига қўшаётган беқиёс ҳиссангиз учун миннатдорлик билдирамиз. Бахт ва муваффақият ҳамиша ҳамроҳингиз бўлсин!'">2-variant</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary" onclick="document.getElementById('edit_custom_wish').value = 'Келажакдаги барча эзгу мақсад ва режаларингиз рўёбга чиқсин, юзингиздан табассум аримасин!'">3-variant</button>
                    </div>
                </div>

                <!-- Saqlash tugmasi -->
                <div class="col-12 pt-3 border-top d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Bekor qilish</button>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">💾 Saqlash</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditEmployeeModal(emp) {
        if (!emp) return;

        // 1. Set form action
        const form = document.getElementById('editEmployeeForm');
        if (form) {
            form.action = "{{ url('admin/employees') }}/" + emp.id;
        }

        // 2. Set modal title
        const titleEl = document.getElementById('edit_modal_title');
        if (titleEl) {
            titleEl.textContent = (emp.full_name || 'Xodim') + " — tahrirlash";
        }

        // 3. Fill input fields
        document.getElementById('edit_full_name').value = emp.full_name || '';
        document.getElementById('edit_phone').value = emp.phone || '';
        document.getElementById('edit_position').value = emp.position || '';
        document.getElementById('edit_birth_date').value = emp.birth_date ? emp.birth_date.substring(0, 10) : '';
        document.getElementById('edit_gender').value = emp.gender || 'male';
        document.getElementById('edit_theme').value = emp.theme || 'random';
        document.getElementById('edit_custom_wish').value = emp.custom_wish || '';
        document.getElementById('edit_photo').value = '';

        // 4. Photo preview
        const photoPrev = document.getElementById('edit_photo_preview');
        if (photoPrev) {
            if (emp.photo) {
                photoPrev.innerHTML = '<img src="' + "{{ asset('storage') }}/" + emp.photo + '" alt="Photo" class="img-fluid rounded shadow-sm" style="max-height: 90px; object-fit: cover;">';
            } else if (emp.turniket_photo) {
                photoPrev.innerHTML = '<img src="' + emp.turniket_photo + '" alt="Turniket Photo" class="img-fluid rounded shadow-sm" style="max-height: 90px; object-fit: cover;">';
            } else {
                photoPrev.innerHTML = '<span class="text-muted small">Rasm yuklanmagan</span>';
            }
        }

        // 5. Trigger Bootstrap offcanvas show
        const el = document.getElementById('tasksDetailsOffcanvasEdit');
        if (el) {
            if (typeof bootstrap !== 'undefined' && bootstrap.Offcanvas) {
                const inst = bootstrap.Offcanvas.getOrCreateInstance(el);
                inst.show();
            } else if (typeof $ !== 'undefined') {
                $(el).offcanvas('show');
            }
        }
    }
</script>
<!--! ================================================================ !-->
<!--! [End] Employee Edit Offcanvas                                    !-->
<!--! ================================================================ !-->
