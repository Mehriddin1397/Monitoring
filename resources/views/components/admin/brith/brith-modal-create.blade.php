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
        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label">Tuliq F.I.Sh:</label>
                        <input type="text" name="full_name" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label">Telefon raqami:</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label">Lavozimi:</label>
                        <input type="text" name="position" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label">Tug'ilgan kuni:</label>
                        <input type="date" name="birth_date" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label">Rasmi:</label>
                        <input type="file" name="photo" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label">Jinsi:</label>
                        <select name="gender" id="create_employee_gender" class="form-select">
                            <option value="male" selected>👨 Erkak</option>
                            <option value="female">👩 Ayol</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label">Tabriknoma shabloni:</label>
                        <select name="theme" id="create_employee_theme" class="form-select">
                            <option value="random" selected>🎲 Jinsiga mos avtomatik (Tavsiya etiladi)</option>
                            <optgroup label="👨 Erkaklar uchun salobatli shablonlar:">
                                <option value="men_classic">🎖️ Salobatli Medalyon (Oltin geometrik)</option>
                                <option value="men_diplomat">🏛️ Diplomatik / Sharaf lavhasi (Mahobatli to'g'ri to'rtburchak)</option>
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
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label class="form-label d-flex justify-content-between">
                            <span>Maxsus tabrik so'zi (ixtiyoriy):</span>
                            <small class="text-muted">Bo'sh qoldirilsa, standart tilak chiqadi</small>
                        </label>
                        <textarea name="custom_wish" id="create_custom_wish" rows="3" class="form-control" placeholder="Сизга узоқ умр, мустаҳкам соғлик, оилавий бахт ва масъuliyatли касбий фаолиятингизда улкан зафарлар тилаймиз!"></textarea>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <small class="text-muted w-100">Tayyor tabriklardan tanlash:</small>
                        <button type="button" class="btn btn-xs btn-outline-secondary" onclick="document.getElementById('create_custom_wish').value = 'Сизга узоқ умр, сиҳат-саломатлик, оилавий хотиржамлик ва илмий-ижодий фаолиятингизда улкан ютуқлар тилаймиз!'">1-variant</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary" onclick="document.getElementById('create_custom_wish').value = 'Институтимиз ривожига қўшаётган беқиёс ҳиссангиз учун миннатдорлик билдирамиз. Бахт ва муваффақият ҳамиша ҳамроҳингиз бўлсин!'">2-variant</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary" onclick="document.getElementById('create_custom_wish').value = 'Келажакдаги барча эзгу мақсад ва режаларингиз рўёбга чиқсин, юзингиздан табассум аримасин!'">3-variant</button>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary d-inline-block mt-2">Qo'shish</button>
                </div>
        </form>
    </div>



</div>
<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
