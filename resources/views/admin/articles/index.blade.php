@extends('layouts.admin')

@section('title', 'И-индекс — Илмий мақолалар')

@section('content')
    @php
        $totalCount = $articles->count();
        $checkedCount = $articles->where('status', 'checked')->count();
        $pendingCount = $articles->where('status', 'pending')->count();
        $avgScore = $checkedCount > 0 ? round($articles->where('status', 'checked')->avg(fn($a) => $a->articleScore?->total_score ?? 0), 1) : 0;
    @endphp

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ Page Header ] start -->
        <div class="page-header py-3 px-3 px-lg-4 shadow-sm" style="background: linear-gradient(135deg, #242c42 0%, #3a4765 100%);">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-text avatar-lg bg-primary text-white rounded-circle shadow-sm" style="width: 52px; height: 52px;">
                        <i class="feather-award fs-24"></i>
                    </div>
                    <div>
                        <h3 class="mb-1 text-white fw-bold fs-22">И-индекс — Илмий мақолалар</h3>
                        <p class="fs-14 text-white-50 mb-0">Мақолаларни қабул қилиш, илмий мезонлар бўйича текшириш ва баҳолаш тизими</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="https://uzkti.uz/" target="_blank" class="btn btn-outline-light btn-md d-flex align-items-center gap-2 rounded-pill px-4 fs-14 fw-semibold">
                        <i class="feather-external-link"></i>
                        <span>I-Index портали</span>
                    </a>
                    <button class="btn btn-primary btn-md d-flex align-items-center gap-2 rounded-pill px-4 shadow fs-14 fw-semibold"
                            data-bs-toggle="offcanvas" data-bs-target="#tasksDetailsOffcanvas">
                        <i class="feather-plus-circle fs-16"></i>
                        <span>Янги мақола юклаш</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- [ Page Header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content p-3 p-lg-4">
            <!-- [ Live Stat Cards ] start -->
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 stat-card stat-total">
                        <div class="card-body p-3 p-lg-4 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-13 text-muted text-uppercase fw-bold letter-spacing-1">Жами мақолалар</span>
                                <h2 class="fw-bolder mb-0 text-dark mt-2 fs-32">{{ $totalCount }}</h2>
                            </div>
                            <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                                <i class="feather-book-open fs-26"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 stat-card stat-checked">
                        <div class="card-body p-3 p-lg-4 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-13 text-muted text-uppercase fw-bold letter-spacing-1">Текширилган</span>
                                <h2 class="fw-bolder mb-0 text-success mt-2 fs-32">{{ $checkedCount }}</h2>
                            </div>
                            <div class="stat-icon-wrapper bg-success-subtle text-success">
                                <i class="feather-check-circle fs-26"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 stat-card stat-pending">
                        <div class="card-body p-3 p-lg-4 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-13 text-muted text-uppercase fw-bold letter-spacing-1">Кутилаётган</span>
                                <h2 class="fw-bolder mb-0 text-warning mt-2 fs-32">{{ $pendingCount }}</h2>
                            </div>
                            <div class="stat-icon-wrapper bg-warning-subtle text-warning">
                                <i class="feather-clock fs-26"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 stat-card stat-score">
                        <div class="card-body p-3 p-lg-4 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-13 text-muted text-uppercase fw-bold letter-spacing-1">Ўртача балл</span>
                                <h2 class="fw-bolder mb-0 text-info mt-2 fs-32">{{ $avgScore }} <small class="fs-15 text-muted fw-normal">балл</small></h2>
                            </div>
                            <div class="stat-icon-wrapper bg-info-subtle text-info">
                                <i class="feather-star fs-26"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Live Stat Cards ] end -->

            <!-- [ Table Card ] start -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="fs-14 fw-bold text-dark me-2">Йил бўйича:</span>
                        <a href="{{ route('articles.index') }}"
                           class="btn rounded-pill px-4 py-2 fs-14 fw-semibold {{ $year === null ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Барчаси <span class="badge bg-white text-dark ms-1 rounded-pill fs-12">{{ $totalCount }}</span>
                        </a>
                        <a href="{{ route('articles.index', ['year' => 2026]) }}"
                           class="btn rounded-pill px-4 py-2 fs-14 fw-semibold {{ $year == 2026 ? 'btn-success' : 'btn-outline-secondary' }}">
                            2026 йил
                        </a>
                        <a href="{{ route('articles.index', ['year' => 2025]) }}"
                           class="btn rounded-pill px-4 py-2 fs-14 fw-semibold {{ $year == 2025 ? 'btn-secondary' : 'btn-outline-secondary' }}">
                            2025 йил
                        </a>
                    </div>

                    <div class="text-muted fs-14">
                        Жами: <strong class="text-dark fs-15">{{ $articles->count() }}</strong> та мақола рўйхатда
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0 custom-article-table w-100" id="proposalList">
                            <thead>
                                <tr>
                                    <th style="width: 55px;" class="text-center">№</th>
                                    <th style="min-width: 320px;">Мақола номи ва нашриёт</th>
                                    <th style="min-width: 200px;">Муаллиф</th>
                                    <th class="text-center" style="width: 140px;">Ҳолати</th>
                                    <th class="text-center" style="width: 130px;">Умумий балл</th>
                                    <th class="text-center" style="width: 120px;">Мақола PDF</th>
                                    <th class="text-center" style="width: 120px;">Хулоса PDF</th>
                                    <th class="text-end" style="min-width: 180px;">Амаллар</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($articles as $article)
                                    <tr>
                                        <td class="text-center text-muted fw-bold fs-15">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="article-title-cell text-dark" title="{{ strip_tags($article->title) }}">
                                                {!! $article->title !!}
                                            </div>
                                            @if($article->publish_place)
                                                <div class="fs-13 text-muted mt-1 d-flex align-items-center">
                                                    <i class="feather-map-pin me-1 text-primary fs-13"></i>
                                                    <span class="fw-medium">{{ $article->publish_place }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="author-avatar">
                                                    {{ mb_substr($article->user?->name ?? 'U', 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark fs-15">{{ $article->user?->name ?? '—' }}</div>
                                                    <div class="fs-12 text-muted mt-1">
                                                        <i class="feather-calendar me-1"></i>{{ $article->created_at ? $article->created_at->format('d.m.Y H:i') : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($article->status === 'checked')
                                                <span class="badge badge-status badge-checked">
                                                    <i class="feather-check-circle me-1 fs-14"></i> Текширилган
                                                </span>
                                            @else
                                                <span class="badge badge-status badge-pending">
                                                    <i class="feather-clock me-1 fs-14"></i> Кутилмоқда
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($article->articleScore)
                                                <span class="badge badge-score">
                                                    {{ $article->articleScore->total_score }} <small class="fs-11 fw-normal">балл</small>
                                                </span>
                                            @else
                                                <span class="text-muted fs-15 fw-bold">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($article->article_pdf)
                                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2 action-pdf-btn"
                                                        onclick="openModal('{{ asset('storage/' . $article->article_pdf) }}')"
                                                        title="Мақола файлини очиш">
                                                    <i class="feather-file-text me-1 fs-14"></i> Кўриш
                                                </button>
                                            @else
                                                <span class="text-muted fs-14">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($article->conclusion_pdf)
                                                <button class="btn btn-sm btn-outline-success rounded-pill px-3 py-2 action-pdf-btn"
                                                        onclick="openModal('{{ asset('storage/' . $article->conclusion_pdf) }}')"
                                                        title="Расмий хулоса файлини очиш">
                                                    <i class="feather-check-square me-1 fs-14"></i> Хулоса
                                                </button>
                                            @else
                                                <span class="text-muted fs-14">—</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                                @if($article->status === 'pending' && (in_array($user->id, [59, 2]) || $user->role === 'admin'))
                                                    <button class="btn btn-sm btn-success rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-1 check-trigger-btn"
                                                            onclick="openCheckModal({{ $article->id }})"
                                                            title="Илмий баҳолаш ва текшириш">
                                                        <i class="feather-award fs-14"></i>
                                                        <span>Текшириш</span>
                                                    </button>
                                                @endif

                                                @if(($article->user_id == auth()->user()->id) || (auth()->user()->role == 'admin') || auth()->user()->id == 59)
                                                    <a href="javascript:void(0)"
                                                       data-bs-toggle="offcanvas"
                                                       data-bs-target="#tasksDetailsOffcanvasEdit{{ $article->id }}"
                                                       class="btn btn-light text-primary rounded-circle action-icon-btn"
                                                       title="Таҳрирлаш">
                                                        <i class="feather-edit-2 fs-15"></i>
                                                    </a>
                                                    <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-light text-danger rounded-circle action-icon-btn"
                                                                onclick="return confirm('Ҳақиқатан ҳам ушбу мақолани ўчирмоқчимисиз?')"
                                                                title="Ўчириш">
                                                            <i class="feather-trash-2 fs-15"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <div class="empty-state py-4">
                                                <i class="feather-inbox fs-48 text-muted opacity-50 mb-3 d-block"></i>
                                                <h5 class="text-muted fw-semibold">Ҳеч қандай мақола топилмади</h5>
                                                <p class="fs-14 text-muted mb-0">Филтрни ўзгартириб кўринг ёки янги мақола юкланг</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- [ Table Card ] end -->
        </div>
        <!-- [ Main Content ] end -->

        <!-- ================= [ PDF Modal ] ================= -->
        <div id="pdfModal" class="custom-modal">
            <div class="custom-modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <iframe id="pdfFrame" src="" width="100%" height="700px" style="border:none; border-radius: 10px;"></iframe>
            </div>
        </div>

        <!-- ================= [ Creative Check Modal ] ================= -->
        <div id="checkModal" class="custom-check-modal">
            <div class="custom-check-modal-content">
                <span class="close-check-btn" onclick="closeCheckModal()">&times;</span>

                <div class="d-flex align-items-center gap-3 mb-3 border-bottom pb-3">
                    <div class="avatar-text avatar-md bg-success-subtle text-success rounded-circle" style="width: 48px; height: 48px;">
                        <i class="feather-award fs-22"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 text-dark fw-bold fs-18">Мақолани текшириш ва баҳолаш</h4>
                        <span class="fs-13 text-muted">Илмий мезонлар кўрсаткичларини киритинг</span>
                    </div>
                </div>

                <!-- Live Score Calculator Preview -->
                <div class="live-score-preview mb-4 p-3 rounded-3">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="fs-14 text-dark fw-bold">Ҳисобланган балл:</span>
                        <div class="fs-22 fw-bolder text-success" id="liveScoreDisplay">0.0 <small class="fs-14 fw-normal">балл</small></div>
                    </div>
                    <div class="fs-12 text-muted">
                        Формула: (Таърифлар × 0.6) + (Таснифлар × 0.4) + (Таклифлар × 0.2)
                    </div>
                </div>

                <form id="checkForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="article_id" id="articleId">

                    <div class="mb-3">
                        <label for="definitions" class="form-label d-flex align-items-center justify-content-between fs-14 fw-bold text-dark">
                            <span><i class="feather-book me-1 text-primary"></i> Илмий таърифлар сони:</span>
                            <span class="badge bg-primary-subtle text-primary fs-12 px-2 py-1">× 0.6 балл</span>
                        </label>
                        <input type="number" id="definitions" name="definitions" min="0" value="0"
                               class="form-control form-control-lg fs-16 fw-semibold score-input" required placeholder="0">
                    </div>

                    <div class="mb-3">
                        <label for="classifications" class="form-label d-flex align-items-center justify-content-between fs-14 fw-bold text-dark">
                            <span><i class="feather-layers me-1 text-info"></i> Илмий таснифлар сони:</span>
                            <span class="badge bg-info-subtle text-info fs-12 px-2 py-1">× 0.4 балл</span>
                        </label>
                        <input type="number" id="classifications" name="classifications" min="0" value="0"
                               class="form-control form-control-lg fs-16 fw-semibold score-input" required placeholder="0">
                    </div>

                    <div class="mb-3">
                        <label for="suggestions" class="form-label d-flex align-items-center justify-content-between fs-14 fw-bold text-dark">
                            <span><i class="feather-bulb me-1 text-warning"></i> Илмий таклифлар сони:</span>
                            <span class="badge bg-warning-subtle text-warning fs-12 px-2 py-1">× 0.2 балл</span>
                        </label>
                        <input type="number" id="suggestions" name="suggestions" min="0" value="0"
                               class="form-control form-control-lg fs-16 fw-semibold score-input" required placeholder="0">
                    </div>

                    <div class="mb-4">
                        <label for="conclusion_pdf" class="form-label fs-14 fw-bold text-dark">
                            <i class="feather-file-text me-1 text-danger"></i> Хулоса PDF файлини юклаш:
                        </label>
                        <input type="file" id="conclusion_pdf" name="conclusion_pdf" accept="application/pdf"
                               class="form-control form-control-lg fs-14" required>
                        <small class="text-muted fs-12 mt-1 d-block">Фақат расмий PDF форматдаги хулоса файли (макс: 20MB)</small>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                        <button type="button" class="btn btn-light btn-lg rounded-pill px-4 fs-14 fw-semibold" onclick="closeCheckModal()">
                            Бекор қилиш
                        </button>
                        <button type="submit" class="btn btn-success btn-lg rounded-pill px-4 submit-btn shadow-sm fs-14 fw-bold">
                            <i class="feather-check-circle me-1"></i> Сақлаш ва тасдиқлаш
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- [ Custom Enhanced Styles ] -->
    <style>
        .letter-spacing-1 {
            letter-spacing: 0.5px;
        }
        .fs-32 {
            font-size: 32px !important;
        }
        .stat-card {
            transition: all 0.25s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
        }
        .stat-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Table Sizing & Typography */
        .custom-article-table thead th {
            background-color: #242c42;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            padding: 16px 18px;
            border-bottom: none;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }
        .custom-article-table tbody td {
            padding: 16px 18px;
            vertical-align: middle;
            border-bottom: 1px solid #eef2f6;
        }
        .custom-article-table tbody tr:hover {
            background-color: #f8fafc;
        }
        .article-title-cell {
            font-size: 16px;
            font-weight: 600;
            line-height: 1.55;
            word-break: break-word;
        }
        .author-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
            text-transform: uppercase;
            box-shadow: 0 3px 8px rgba(79, 70, 229, 0.25);
        }
        .badge-status {
            padding: 8px 16px;
            border-radius: 50rem;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }
        .badge-checked {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .badge-score {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #fff;
            padding: 8px 16px;
            border-radius: 50rem;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
            display: inline-block;
            white-space: nowrap;
        }
        .action-pdf-btn {
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .action-icon-btn {
            width: 38px;
            height: 38px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .action-icon-btn:hover {
            transform: scale(1.1);
        }
        .check-trigger-btn {
            font-size: 14px;
            font-weight: 700;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .check-trigger-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3) !important;
        }

        /* Check Modal Responsive & Sizing */
        .custom-check-modal {
            display: none;
            position: fixed;
            z-index: 99999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow-y: auto;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(5px);
        }
        .custom-check-modal-content {
            background: #fff;
            margin: 4% auto;
            padding: 32px;
            border-radius: 18px;
            width: 94%;
            max-width: 580px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            animation: modalFadeIn .25s ease-out;
            position: relative;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-25px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .close-check-btn {
            position: absolute;
            right: 22px;
            top: 22px;
            font-size: 28px;
            font-weight: 400;
            cursor: pointer;
            color: #94a3b8;
            line-height: 1;
            transition: color 0.2s;
        }
        .close-check-btn:hover {
            color: #ef4444;
        }
        .live-score-preview {
            background: #f8fafc;
            border: 1.5px dashed #cbd5e1;
        }
    </style>

    <!-- [ Custom Scripts ] -->
    <script>
        const checkModal = document.getElementById('checkModal');
        const checkForm = document.getElementById('checkForm');
        const articleIdInput = document.getElementById('articleId');
        const liveScoreDisplay = document.getElementById('liveScoreDisplay');

        const defInput = document.getElementById('definitions');
        const classInput = document.getElementById('classifications');
        const sugInput = document.getElementById('suggestions');

        function updateLiveScore() {
            const defVal = parseFloat(defInput.value) || 0;
            const classVal = parseFloat(classInput.value) || 0;
            const sugVal = parseFloat(sugInput.value) || 0;

            const total = (defVal * 0.6) + (classVal * 0.4) + (sugVal * 0.2);
            liveScoreDisplay.innerHTML = total.toFixed(1) + ' <small class="fs-14 fw-normal">балл</small>';
        }

        [defInput, classInput, sugInput].forEach(inp => {
            inp.addEventListener('input', updateLiveScore);
            inp.addEventListener('change', updateLiveScore);
        });

        function openCheckModal(id) {
            if (!id) return;
            checkForm.reset();
            articleIdInput.value = id;
            updateLiveScore();
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
            const originalText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Сақланмоқда...';

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
                    alert(result.message || 'Мақола муваффақиятли баҳоланди!');
                    closeCheckModal();
                    window.location.reload();
                } else {
                    let errMsg = result.message || "Хатолик юз берди!";
                    if (result.errors) {
                        errMsg += "\n" + Object.values(result.errors).flat().join("\n");
                    }
                    alert(errMsg);
                }
            } catch (err) {
                alert("Хатолик юз берди: " + err.message);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    </script>

    @include('components.admin.articles.project-modal-create')
    @include('components.admin.articles.project-modal-edit', ['articles' => $articles])

@endsection
