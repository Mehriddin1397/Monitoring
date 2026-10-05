<!--! ================================================================ !-->
@foreach($employees as $employee )
    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="tasksDetailsOffcanvasEdit{{ $employee->id }}">
        <div class="offcanvas-header border-bottom" style="padding-top: 20px; padding-bottom: 20px">
            <div class="d-flex align-items-center">
                <div class="avatar-text avatar-md items-details-close-trigger" data-bs-dismiss="offcanvas"
                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Details Close">
                    <i class="feather-arrow-left"></i>
                </div>
                <span class="vr text-muted mx-4"></span>
                <a href="javascript:void(0);">
                    <h2 class="fs-14 fw-bold text-truncate-1-line">Tug'ilgan kun</h2>
                    <span class="fs-12 fw-normal text-muted text-truncate-1-line"> O'zgartirish</span>
                </a>
            </div>
        </div>

        <div class="offcanvas-body">
            <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Tuliq F.I.Sh:</label>
                            <input type="text" name="full_name" value="{{old('name_uz',$employee->full_name)}}"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Telefon raqami:</label>
                            <input type="text" name="phone" value="{{old('name_ru',$employee->phone)}}"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Lavozimi:</label>
                            <input type="text" name="position" value="{{old('name_en',$employee->position)}}"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Tug'ilgan kuni:</label>
                            <input type="date" name="birth_date" value="{{old('name_kr',$employee->birth_date)}}"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Jinsi:</label>
                            <select name="gender" class="form-select">
                                <option value="male" {{ old('gender', $employee->gender ?? 'male') == 'male' ? 'selected' : '' }}>👨 Erkak</option>
                                <option value="female" {{ old('gender', $employee->gender ?? '') == 'female' ? 'selected' : '' }}>👩 Ayol</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Tabriknoma shabloni:</label>
                            <select name="theme" class="form-select">
                                <option value="random" {{ old('theme', $employee->theme ?? 'random') == 'random' ? 'selected' : '' }}>🎲 Jinsiga mos avtomatik (Random)</option>
                                <optgroup label="👨 Erkaklar uchun salobatli shablonlar:">
                                    <option value="men_classic" {{ old('theme', $employee->theme) == 'men_classic' ? 'selected' : '' }}>🎖️ Salobatli Medalyon (Oltin geometrik)</option>
                                    <option value="men_diplomat" {{ old('theme', $employee->theme) == 'men_diplomat' ? 'selected' : '' }}>🏛️ Diplomatik / Sharaf lavhasi (Mahobatli to'g'ri to'rtburchak)</option>
                                    <option value="men_zafar" {{ old('theme', $employee->theme) == 'men_zafar' ? 'selected' : '' }}>⭐ Zafarnoma (Yulduzli orden va shon-sharaf)</option>
                                </optgroup>
                                <optgroup label="👩 Ayollar uchun nafis shablonlar:">
                                    <option value="women_rose" {{ old('theme', $employee->theme) == 'women_rose' ? 'selected' : '' }}>🌹 Nafis Bahor va Atirgullar (Gulli hoshiya)</option>
                                    <option value="women_emerald" {{ old('theme', $employee->theme) == 'women_emerald' ? 'selected' : '' }}>🌿 Zumrad Nafislik (Zarhal gulchambar)</option>
                                    <option value="women_pearl" {{ old('theme', $employee->theme) == 'women_pearl' ? 'selected' : '' }}>💎 Marvarid va Ipak (Nafis marvaridli ramka)</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">Rasmi:</label>
                            <input type="file" name="photo" class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        @if($employee->photo)
                            <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->full_name }}" class="img-fluid rounded shadow-sm" style="max-height: 100px;">
                        @endif
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label d-flex justify-content-between">
                                <span>Maxsus tabrik so'zi (ixtiyoriy):</span>
                                <small class="text-muted">Bo'sh bo'lsa, standart tilak chiqadi</small>
                            </label>
                            <textarea name="custom_wish" id="edit_custom_wish_{{ $employee->id }}" rows="3" class="form-control" placeholder="Сизга узоқ умр, мустаҳкам соғлик, оилавий бахт ва масъuliyatли касбий фаолиятингизда улкан зафарлар тилаймиз!">{{ old('custom_wish', $employee->custom_wish) }}</textarea>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <small class="text-muted w-100">Tayyor tabriklardan tanlash:</small>
                            <button type="button" class="btn btn-xs btn-outline-secondary" onclick="document.getElementById('edit_custom_wish_{{ $employee->id }}').value = 'Сизга узоқ умр, сиҳат-саломатлик, оилавий хотиржамлик ва илмий-ижодий фаолиятингизда улкан ютуқлар тилаймиз!'">1-variant</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary" onclick="document.getElementById('edit_custom_wish_{{ $employee->id }}').value = 'Институтимиз ривожига қўшаётган беқиёс ҳиссангиз учун миннатдорлик билдирамиз. Бахт ва муваффақият ҳамиша ҳамроҳингиз бўлсин!'">2-variant</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary" onclick="document.getElementById('edit_custom_wish_{{ $employee->id }}').value = 'Келажакдаги барча эзгу мақсад ва режаларингиз рўёбга чиқсин, юзингиздан табассум аримасин!'">3-variant</button>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary d-inline-block mt-2">Saqlash</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll('.ckeditor').forEach((el) => {
            CKEDITOR.replace(el);
        });
    </script>
    </div>
@endforeach

<!--! ================================================================ !-->
<!--! [End] Tasks Details Offcanvas !-->
