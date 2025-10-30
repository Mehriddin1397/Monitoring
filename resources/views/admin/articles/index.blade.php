@extends('layouts.admin')

@section('title', 'И-индекс')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->
        <div class="page-header " style="background-color: #7878a3">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10 ">И-индекс</h5>
                </div>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex d-md-none">
                        <a href="javascript:void(0)" class="page-header-right-close-toggle">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Back</span>
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="javascript:void(0);" class="btn btn-primary " data-bs-toggle="offcanvas"
                           data-bs-target="#tasksDetailsOffcanvas">
                            <i class="feather-plus me-2"></i>
                            <span>Яратиш</span>
                        </a>
                    </div>
                </div>
                <div class="d-md-none d-flex align-items-center">
                    <a href="javascript:void(0)" class="page-header-right-open-toggle">
                        <i class="feather-align-right fs-20"></i>
                    </a>
                </div>
            </div>
        </div>
        <div id="collapseOne" class="accordion-collapse collapse page-header-collapse">
            <div class="accordion-body pb-2">
                <div class="row">
                    <div class="col-xxl-3 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="javascript:void(0);" class="fw-bold d-block">
                                        <span class="d-block">Paid</span>
                                        <span class="fs-20 fw-bold d-block">78/100</span>
                                    </a>
                                    <div class="progress-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="javascript:void(0);" class="fw-bold d-block">
                                        <span class="d-block">Unpaid</span>
                                        <span class="fs-20 fw-bold d-block">38/50</span>
                                    </a>
                                    <div class="progress-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="javascript:void(0);" class="fw-bold d-block">
                                        <span class="d-block">Overdue</span>
                                        <span class="fs-20 fw-bold d-block">15/30</span>
                                    </a>
                                    <div class="progress-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="javascript:void(0);" class="fw-bold d-block">
                                        <span class="d-block">Draft</span>
                                        <span class="fs-20 fw-bold d-block">3/10</span>
                                    </a>
                                    <div class="progress-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->
        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-body p-0">
                            <div class="table-responsive table-container">
                                <table class="table table-hover " id="proposalList">
                                    <thead style="background-color: #c7c7f0">
                                    <tr>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">№</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Номи</th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333;">Муаллиф
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333; text-align: center">
                                            Статус
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333; text-align: center">
                                            Умумий <br> балл
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333; text-align: center">
                                            Мақола пдф
                                        </th>
                                        <th style="font-weight: bold; font-size: 13px; color: #333; text-align: center">
                                            Хулоса пдф
                                        </th>
                                        <th class="text-end">Тахрирлаш</th>
                                    </tr>
                                    </thead>
                                    <tbody style="background-color: #e7e7f3">

                                    @foreach($articles as $article)
                                        <tr class="single-item">
                                            <td> {{ $loop->iteration }}</td>
                                            <td>
                                                {!!  $article->title !!}
                                            </td>
                                            <td>
                                                {{$article->user->name }}
                                            </td>
                                            <td>
                                                @if($article->status == 'pending')
                                                    <p style="color: red">⏳ Текширилмаган</p>
                                                    @if (in_array($user->id, [1, 2]) || $user->role === 'admin')
                                                        <button class="btn btn-sm btn-success"
                                                                onclick="openCheckModal({{ $article->id }}, '{{ $article->title }}')">
                                                            Текшириш
                                                        </button>
                                                    @endif
                                                @else
                                                    <p style="color: blue">✅ Текширилган</p>

                                                @endif
                                            </td>


                                            <td>
                                            @foreach($article->articleScores as $score)

                                                        {{$score->total_score }}



                                                    @endforeach
                                                </td>

                                            <td>
                                                @if($article->article_pdf)
                                                    <button class="btn btn-primary btn-sm"
                                                            onclick="openModal('{{ asset('storage/' . $article->article_pdf) }}')">
                                                        Кўриш
                                                    </button>
                                                @endif


                                            </td>
                                            <td>
                                                @if($article->conclusion_pdf)
                                                    <button class="btn btn-primary btn-sm"
                                                            onclick="openModal('{{ asset('storage/' . $article->conclusion_pdf) }}')">
                                                        Кўриш
                                                    </button>
                                                @endif
                                            </td>

                                            <td>
                                                @if(($article->user_id == auth()->user()->id) || (auth()->user()->role == 'admin'))
                                                <div class="hstack gap-2 justify-content-end">
                                                    <a href="javascript:void(0)" data-bs-toggle="offcanvas"
                                                       data-bs-target="#tasksDetailsOffcanvasEdit{{ $article->id }}"
                                                       class="avatar-text avatar-md">
                                                        <i class="feather feather-edit-3"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('articles.destroy', $article->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            type="submit"
                                                            class="avatar-text avatar-md"
                                                            onclick="return confirm('Учирасизми ?')">
                                                            <i class="feather feather-trash-2"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>


                                <!-- Modal oynasi -->
                                <div id="pdfModal" class="custom-modal">
                                    <div class="custom-modal-content">
                                        <span class="close-btn" onclick="closeModal()">&times;</span>
                                        <iframe id="pdfFrame" src="" width="100%" height="600px"
                                                style="border:none;"></iframe>
                                    </div>
                                </div>

                                <!-- ================= MODAL ================= -->
                                <div id="checkModal" class="modal">
                                    <div class="modal-content">
                                        <span class="close" onclick="closeCheckModal()">&times;</span>
                                        <h4 id="modalTitle">Мақолани текшириш</h4>

                                        <form id="checkForm" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="article_id" id="articleId">

                                            <label for="definitions">Илмий таърифлар сони:</label>
                                            <input type="number" id="definitions" name="definitions" min="0" required>

                                            <label for="classifications">Илмий таснифлар сони:</label>
                                            <input type="number" id="classifications" name="classifications" min="0" required>

                                            <label for="suggestions">Илмий таклифлар сони:</label>
                                            <input type="number" id="suggestions" name="suggestions" min="0" required>

                                            <label for="conclusion_pdf">Хулоса ПДФ файлини юклаш:</label>
                                            <input type="file" id="conclusion_pdf" name="conclusion_pdf" accept="application/pdf" required>

                                            <button type="submit" class="submit-btn">Сақлаш</button>
                                        </form>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <style>
        .table-container {
            overflow-x: auto !important; /* Gorizontal skroll qo‘shadi */
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>

    <style>
        .modal { display:none; position:fixed; z-index:999; left:0; top:0; width:100%; height:100%;
            background:rgba(0,0,0,0.6); }
        .modal-content { background:#fff; margin:10% auto; padding:20px; border-radius:10px;
            width:90%; max-width:450px; animation:fadeIn .3s; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(-10px);} to{opacity:1;transform:translateY(0);} }
        .close { float:right; font-size:24px; cursor:pointer; color:#555; }
        label { display:block; margin-top:10px; font-weight:600; }
        input { width:100%; padding:7px; border:1px solid #ccc; border-radius:6px; }
        .submit-btn { margin-top:15px; width:100%; background:#198754; color:#fff; border:none;
            padding:10px; border-radius:6px; font-weight:600; cursor:pointer; }
    </style>

    <script>
        const modal = document.getElementById('checkModal');
        const form = document.getElementById('checkForm');
        const articleIdInput = document.getElementById('articleId');
        const modalTitle = document.getElementById('modalTitle');

        function openCheckModal(id, title) {
            articleIdInput.value = id;
            modal.style.display = "block";
        }

        function closeCheckModal() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target === modal) closeCheckModal();
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            const response = await fetch("{{ route('articles.check') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const result = await response.json();
            if (result.success) {
                alert(result.message);
                closeCheckModal();
                window.location.reload();
            } else {
                alert("Xatolik: Maqola saqlanmadi!");
            }
        });
    </script>

    @include('components.admin.articles.project-modal-create')
    @include('components.admin.articles.project-modal-edit', ['articles' => $articles])

@endsection
