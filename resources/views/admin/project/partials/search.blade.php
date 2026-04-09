@extends('layouts.admin')

@section('title', 'Filtr')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">Топшириқларни филтрлаш</h5>
            </div>

            <div class="card-body">
                <form id="filterForm" class="row g-3">
                    <div class="col-md-3">
                        <label>Ижрочи</label>
                        <select name="ijrochi" class="form-select filter-input">
                            <option value="">Барчаси</option>
                            @foreach($users as $user)
                                <option value="{{ $user->name }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Топшириқни берган</label>
                        <select name="bergan" class="form-select filter-input">
                            <option value="">Барчаси</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>Бошланғич сана</label>
                        <input type="date" name="start_date" class="form-control filter-input">
                    </div>

                    <div class="col-md-2">
                        <label>Тугаш санаси</label>
                        <input type="date" name="end_date" class="form-control filter-input">
                    </div>

                    <div class="col-md-2">
                        <label>Ҳолати</label>
                        <select name="status" class="form-select filter-input">
                            <option value="">Барчаси</option>
                            <option value="yangi">Янги</option>
                            <option value="bajarilmoqda">Бажарилмоқда</option>
                            <option value="bajarildi">Бажарилди</option>
                            <option value="uzaytirildi">Узайтирилди</option>
                            <option value="bajarilmadi">Бажарилмади</option>
                        </select>
                    </div>
                </form>
            </div>
            <button onclick="exportToWord('exportTable', 'Topshiriqlar_hisoboti')" class="btn btn-success mb-3">
                Word qilib yuklab olish
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="exportTable">
                <thead class="sticky-top" style="background-color: #c7c7f0;">
                <tr>
                    <th>№</th>
                    <th>Топшириқ мазмуни</th>
                    <th>Ижрочилар</th>
                    <th>Топшириқни берган</th>
                    <th>Берилган санаси</th>
                    <th>Бажариш санаси</th>
                    <th>Топшириқ муддати</th>
                    <th>Топшириқ ҳолати</th>
                </tr>
                </thead>
                <tbody id="resultsContainer" style="background-color: #e7e7f3;">
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.filter-input');
            const resultsContainer = document.getElementById('resultsContainer');

            // Har qanday select yoki date o'zgarganda to'g'ridan-to'g'ri so'rov ketadi
            inputs.forEach(input => {
                input.addEventListener('change', function () {
                    fetchResults();
                });
            });

            function fetchResults() {
                // Yuklanmoqda animasiyasi
                resultsContainer.innerHTML = '<tr><td colspan="8" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div> Юкланмоқда...</td></tr>';

                const formData = new FormData(document.getElementById('filterForm'));
                const params = new URLSearchParams(formData).toString();

                fetch(`{{ route('tasks.search') }}?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        resultsContainer.innerHTML = data.html;
                    })
                    .catch(error => {
                        console.error('Xato yuz berdi:', error);
                        resultsContainer.innerHTML = '<tr><td colspan="8" class="text-center text-danger py-4">Хаттолик юз берди. Саҳифани янгиланг.</td></tr>';
                    });
            }

            // Sahifa birinchi marta ochilganda barcha ma'lumotlar chiqib turishi uchun
            fetchResults();
        });


        function exportToWord(tableID, filename = ''){
            var downloadLink;
            var dataType = 'application/vnd.ms-word';
            var tableSelect = document.getElementById(tableID);

            // O'zbek harflari (lotin/kirill) Word'da buzilib ketmasligi uchun UTF-8 formatini qo'shamiz
            var preHtml = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'><title>Export HTML To Doc</title></head><body>";
            var postHtml = "</body></html>";

            // HTML ni birlashtiramiz
            var html = preHtml + tableSelect.outerHTML + postHtml;

            // Fayl nomini belgilaymiz
            filename = filename?filename+'.doc':'document.doc';

            // Brauzer orqali yuklab olish jarayoni
            var blob = new Blob(['\ufeff', html], { type: dataType });

            downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);

            if(navigator.msSaveOrOpenBlob){
                navigator.msSaveOrOpenBlob(blob, filename);
            }else{
                downloadLink.href = URL.createObjectURL(blob);
                downloadLink.download = filename;
                downloadLink.click();
            }
            document.body.removeChild(downloadLink);
        }
    </script>
@endsection




