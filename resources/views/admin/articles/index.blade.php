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
                                <div class="mb-3 d-flex gap-2">

                                    <a href="{{ route('articles.index', ['year' => 2025]) }}"
                                       class="btn {{ $year == 2025 ? 'btn-primary' : 'btn-outline-primary' }}">
                                        2025 йил юкланган мақолалар
                                    </a>

                                    <a href="{{ route('articles.index', ['year' => 2026]) }}"
                                       class="btn {{ $year == 2026 ? 'btn-success' : 'btn-outline-success' }}">
                                        2026 йил юкланган мақолалар
                                    </a>

                                    <a href="{{ route('articles.index') }}"
                                       class="btn {{ $year == null ? 'btn-secondary' : 'btn-outline-secondary' }} ">
                                        Барчаси
                                    </a>

                                    <a href="https://uzkti.uz/" target="_blank"
                                       class="btn btn-danger">
                                        I-index saytiga o'tish
                                    </a>

                                </div>
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
                                                {{ $article->user?->name ?? '—' }}
                                            </td>
                                            <td>
                                                @if($article->status == 'pending')
                                                    <p style="color: red">⏳ Текширилмаган</p>
                                                    @if (in_array($user->id, [59, 2]) || $user->role === 'admin')
                                                        <button class="btn btn-sm btn-success"
                                                                onclick="openCheckModal({{ $article->id }})">
                                                            Текшириш
                                                        </button>
                                                    @endif
                                                @else
                                                    <p style="color: blue">✅ Текширилган</p>

                                                @endif
                                            </td>


                                            <td>

                                                        {{$article->articleScore->total_score ?? 0 }}

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
                                                @if(($article->user_id == auth()->user()->id) || (auth()->user()->role == 'admin') || auth()->user()->id == 59 )
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
                                <div id="checkModal" class="custom-check-modal">
                                    <div class="custom-check-modal-content">
                                        <span class="close-check-btn" onclick="closeCheckModal()">&times;</span>
                                        <h4 id="modalTitle" class="mb-3 text-dark">Мақолани текшириш</h4>

                                        <form id="checkForm" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="article_id" id="articleId">

                                            <div class="form-group mb-2">
                                                <label for="definitions">Илмий таърифлар сони:</label>
                                                <input type="number" id="definitions" name="definitions" min="0" value="0" class="form-control" required>
                                            </div>

                                            <div class="form-group mb-2">
                                                <label for="classifications">Илмий таснифлар сони:</label>
                                                <input type="number" id="classifications" name="classifications" min="0" value="0" class="form-control" required>
                                            </div>

                                            <div class="form-group mb-2">
                                                <label for="suggestions">Илмий таклифлар сони:</label>
                                                <input type="number" id="suggestions" name="suggestions" min="0" value="0" class="form-control" required>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="conclusion_pdf">Хулоса ПДФ файлини юклаш:</label>
                                                <input type="file" id="conclusion_pdf" name="conclusion_pdf" accept="application/pdf" class="form-control" required>
                                            </div>

                                            <button type="submit" class="submit-btn btn btn-success w-100">Сақлаш</button>
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
            overflow-x: auto !important;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-check-modal {
            display: none;
            position: fixed;
            z-index: 99999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow-y: auto;
            background: rgba(0, 0, 0, 0.65);
        }

        .custom-check-modal-content {
            background: #fff;
            margin: 6% auto;
            padding: 25px;
            border-radius: 12px;
            width: 90%;
            max-width: 480px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            animation: fadeIn .25s ease-out;
            position: relative;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .close-check-btn {
            position: absolute;
            right: 18px;
            top: 15px;
            font-size: 26px;
            font-weight: bold;
            cursor: pointer;
            color: #888;
            line-height: 1;
        }

        .close-check-btn:hover {
            color: #dc3545;
        }

        .custom-check-modal label {
            display: block;
            margin-bottom: 4px;
            font-weight: 600;
            font-size: 13px;
        }
    </style>

    <script>
        const checkModal = document.getElementById('checkModal');
        const checkForm = document.getElementById('checkForm');
        const articleIdInput = document.getElementById('articleId');

        function openCheckModal(id) {
            if (!id) return;
            checkForm.reset();
            articleIdInput.value = id;
            checkModal.style.display = "block";
        }

        function closeCheckModal() {
            checkModal.style.display = "none";
        }

        window.addEventListener('click', function(event) {
            if (event.target === checkModal) {
                closeCheckModal();
            }
        });

        checkForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitBtn = checkForm.querySelector('.submit-btn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerText = "Сақланмоқда...";
            }

            try {
                const formData = new FormData(checkForm);

                const response = await fetch("{{ route('articles.check') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();
                if (response.ok && result.success) {
                    alert(result.message || 'Мақола муваффақиятли текширилди!');
                    closeCheckModal();
                    window.location.reload();
                } else {
                    let errMsg = result.message || "Хатолик: Мақола сақланмади!";
                    if (result.errors) {
                        errMsg += "\n" + Object.values(result.errors).flat().join("\n");
                    }
                    alert(errMsg);
                }
            } catch (err) {
                alert("Хатолик юз берди: " + err.message);
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = "Сақлаш";
                }
            }
        });
    </script>

    @include('components.admin.articles.project-modal-create')
    @include('components.admin.articles.project-modal-edit', ['articles' => $articles])

@endsection
