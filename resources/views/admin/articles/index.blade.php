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
        <div class="page-header py-3 px-4 shadow-sm" style="background: linear-gradient(135deg, #2b354f 0%, #3e4a68 100%);">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-text avatar-lg bg-primary-subtle text-primary rounded-circle">
                        <i class="feather-award fs-20"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 text-white fw-bold">И-индекс — Илмий мақолалар</h4>
                        <p class="fs-12 text-white-50 mb-0">Мақолаларни қабул қилиш, илмий мезонлар бўйича текшириш ва баҳолаш</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="https://uzkti.uz/" target="_blank" class="btn btn-outline-light btn-sm d-flex align-items-center gap-2 rounded-pill px-3">
                        <i class="feather-external-link"></i>
                        <span>I-Index портали</span>
                    </a>
                    <button class="btn btn-primary btn-sm d-flex align-items-center gap-2 rounded-pill px-3 shadow"
                            data-bs-toggle="offcanvas" data-bs-target="#tasksDetailsOffcanvas">
                        <i class="feather-plus-circle"></i>
                        <span>Янги мақола юклаш</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- [ Page Header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content p-4">
            <!-- [ Live Stat Cards ] start -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 stat-card stat-total">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted text-uppercase fw-semibold">Жами мақолалар</span>
                                <h3 class="fw-bold mb-0 text-dark mt-1">{{ $totalCount }}</h3>
                            </div>
                            <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                                <i class="feather-book-open fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 stat-card stat-checked">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted text-uppercase fw-semibold">Текширилган</span>
                                <h3 class="fw-bold mb-0 text-success mt-1">{{ $checkedCount }}</h3>
                            </div>
                            <div class="stat-icon-wrapper bg-success-subtle text-success">
                                <i class="feather-check-circle fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 stat-card stat-pending">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted text-uppercase fw-semibold">Кутилаётган</span>
                                <h3 class="fw-bold mb-0 text-warning mt-1">{{ $pendingCount }}</h3>
                            </div>
                            <div class="stat-icon-wrapper bg-warning-subtle text-warning">
                                <i class="feather-clock fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 stat-card stat-score">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted text-uppercase fw-semibold">Ўртача балл</span>
                                <h3 class="fw-bold mb-0 text-info mt-1">{{ $avgScore }} <small class="fs-13 text-muted">балл</small></h3>
                            </div>
                            <div class="stat-icon-wrapper bg-info-subtle text-info">
                                <i class="feather-star fs-22"></i>
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
                        <span class="fs-13 fw-semibold text-muted me-2">Филтр:</span>
                        <a href="{{ route('articles.index') }}"
                           class="btn btn-sm rounded-pill px-3 {{ $year === null ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Барчаси <span class="badge bg-white text-dark ms-1 rounded-pill">{{ $totalCount }}</span>
                        </a>
                        <a href="{{ route('articles.index', ['year' => 2026]) }}"
                           class="btn btn-sm rounded-pill px-3 {{ $year == 2026 ? 'btn-success' : 'btn-outline-secondary' }}">
                            2026 йил
                        </a>
                        <a href="{{ route('articles.index', ['year' => 2025]) }}"
                           class="btn btn-sm rounded-pill px-3 {{ $year == 2025 ? 'btn-secondary' : 'btn-outline-secondary' }}">
                            2025 йил
                        </a>
                    </div>

                    <div class="text-muted fs-12">
                        Кўрсатилмоқда: <strong>{{ $articles->count() }}</strong> та мақола
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0 custom-article-table" id="proposalList">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">№</th>
                                    <th>Мақола номи</th>
                                    <th>Муаллиф</th>
                                    <th class="text-center" style="width: 130px;">Ҳолати</th>
                                    <th class="text-center" style="width: 110px;">Умумий балл</th>
                                    <th class="text-center" style="width: 100px;">Мақола PDF</th>
                                    <th class="text-center" style="width: 100px;">Хулоса PDF</th>
                                    <th class="text-end" style="width: 180px;">Амаллар</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($articles as $article)
                                    <tr>
                                        <td class="text-muted fw-bold">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="article-title-cell text-dark fw-medium" title="{{ strip_tags($article->title) }}">
                                                {!! $article->title !!}
                                            </div>
                                            @if($article->publish_place)
                                                <div class="fs-12 text-muted mt-1">
                                                    <i class="feather-map-pin me-1 text-primary"></i> {{ $article->publish_place }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="author-avatar">
                                                    {{ mb_substr($article->user?->name ?? 'U', 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark fs-13">{{ $article->user?->name ?? '—' }}</div>
                                                    <div class="fs-11 text-muted">{{ $article->created_at ? $article->created_at->format('d.m.Y') : '' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($article->status === 'checked')
                                                <span class="badge badge-status badge-checked">
                                                    <i class="feather-check-circle me-1"></i> Текширилган
                                                </span>
                                            @else
                                                <span class="badge badge-status badge-pending">
                                                    <i class="feather-clock me-1"></i> Кутилмоқда
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($article->articleScore)
                                                <span class="badge badge-score">
                                                    {{ $article->articleScore->total_score }} <small>балл</small>
                                                </span>
                                            @else
                                                <span class="text-muted fs-12">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($article->article_pdf)
                                                <button class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1 action-pdf-btn"
                                                        onclick="openModal('{{ asset('storage/' . $article->article_pdf) }}')"
                                                        title="Мақолани кўриш">
                                                    <i class="feather-file-text me-1"></i> Кўриш
                                                </button>
                                            @else
                                                <span class="text-muted fs-12">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($article->conclusion_pdf)
                                                <button class="btn btn-sm btn-outline-success rounded-pill px-2 py-1 action-pdf-btn"
                                                        onclick="openModal('{{ asset('storage/' . $article->conclusion_pdf) }}')"
                                                        title="Хулосани кўриш">
                                                    <i class="feather-check-square me-1"></i> Хулоса
                                                </button>
                                            @else
                                                <span class="text-muted fs-12">—</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex align-items-center justify-content-end gap-1">
                                                @if($article->status === 'pending' && (in_array($user->id, [59, 2]) || $user->role === 'admin'))
                                                    <button class="btn btn-sm btn-success rounded-pill px-3 py-1 shadow-sm d-flex align-items-center gap-1 check-trigger-btn"
                                                            onclick="openCheckModal({{ $article->id }})"
                                                            title="Илмий баҳолаш ва текшириш">
                                                        <i class="feather-award"></i>
                                                        <span>Текшириш</span>
                                                    </button>
                                                @endif

                                                @if(($article->user_id == auth()->user()->id) || (auth()->user()->role == 'admin') || auth()->user()->id == 59)
                                                    <a href="javascript:void(0)"
                                                       data-bs-toggle="offcanvas"
                                                       data-bs-target="#tasksDetailsOffcanvasEdit{{ $article->id }}"
                                                       class="btn btn-sm btn-light text-primary rounded-circle action-icon-btn"
                                                       title="Таҳрирлаш">
                                                        <i class="feather-edit-2"></i>
                                                    </a>
                                                    <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-light text-danger rounded-circle action-icon-btn"
                                                                onclick="return confirm('Ҳақиқатан ҳам ушбу мақолани ўчирмоқчимисиз?')"
                                                                title="Ўчириш">
                                                            <i class="feather-trash-2"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <div class="empty-state">
                                                <i class="feather-inbox fs-40 text-muted opacity-50 mb-2"></i>
                                                <h6 class="text-muted fw-normal">Ҳеч қандай мақола топилмади</h6>
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
                <iframe id="pdfFrame" src="" width="100%" height="650px" style="border:none; border-radius: 8px;"></iframe>
            </div>
        </div>

        <!-- ================= [ Creative Check Modal ] ================= -->
        <div id="checkModal" class="custom-check-modal">
            <div class="custom-check-modal-content">
                <span class="close-check-btn" onclick="closeCheckModal()">&times;</span>

                <div class="d-flex align-items-center gap-3 mb-3 border-bottom pb-3">
                    <div class="avatar-text avatar-md bg-success-subtle text-success rounded-circle">
                        <i class="feather-award fs-18"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 text-dark fw-bold">Мақолани текшириш ва баҳолаш</h5>
                        <span class="fs-12 text-muted">Илмий мезонлар кўрсаткичларини киритинг</span>
                    </div>
                </div>

                <!-- Live Score Calculator Preview -->
                <div class="live-score-preview mb-3 p-3 rounded-3">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="fs-12 text-muted fw-semibold">Ҳисобланган балл:</span>
                        <div class="fs-18 fw-bold text-success" id="liveScoreDisplay">0.0 <small class="fs-12">балл</small></div>
                    </div>
                    <div class="fs-11 text-muted text-truncate-1-line">
                        Формула: (Таъриф × 0.6) + (Тасниф × 0.4) + (Таклиф × 0.2)
                    </div>
                </div>

                <form id="checkForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="article_id" id="articleId">

                    <div class="mb-3">
                        <label for="definitions" class="form-label d-flex align-items-center justify-content-between fs-13 fw-semibold">
                            <span><i class="feather-book me-1 text-primary"></i> Илмий таърифлар сони:</span>
                            <span class="badge bg-primary-subtle text-primary fs-11">× 0.6 балл</span>
                        </label>
                        <input type="number" id="definitions" name="definitions" min="0" value="0"
                               class="form-control score-input" required placeholder="0">
                    </div>

                    <div class="mb-3">
                        <label for="classifications" class="form-label d-flex align-items-center justify-content-between fs-13 fw-semibold">
                            <span><i class="feather-layers me-1 text-info"></i> Илмий таснифлар сони:</span>
                            <span class="badge bg-info-subtle text-info fs-11">× 0.4 балл</span>
                        </label>
                        <input type="number" id="classifications" name="classifications" min="0" value="0"
                               class="form-control score-input" required placeholder="0">
                    </div>

                    <div class="mb-3">
                        <label for="suggestions" class="form-label d-flex align-items-center justify-content-between fs-13 fw-semibold">
                            <span><i class="feather-bulb me-1 text-warning"></i> Илмий таклифлар сони:</span>
                            <span class="badge bg-warning-subtle text-warning fs-11">× 0.2 балл</span>
                        </label>
                        <input type="number" id="suggestions" name="suggestions" min="0" value="0"
                               class="form-control score-input" required placeholder="0">
                    </div>

                    <div class="mb-4">
                        <label for="conclusion_pdf" class="form-label fs-13 fw-semibold">
                            <i class="feather-file-text me-1 text-danger"></i> Хулоса PDF файлини юклаш:
                        </label>
                        <input type="file" id="conclusion_pdf" name="conclusion_pdf" accept="application/pdf"
                               class="form-control" required>
                        <small class="text-muted fs-11">Фақат PDF форматдаги расмий хулоса файли (макс: 20MB)</small>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top">
                        <button type="button" class="btn btn-light rounded-pill px-4" onclick="closeCheckModal()">
                            Бекор қилиш
                        </button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 submit-btn shadow-sm">
                            <i class="feather-check me-1"></i> Сақлаш ва тасдиқлаш
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- [ Custom Styles ] -->
    <style>
        .stat-card {
            transition: all 0.25s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
        }
        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .custom-article-table thead th {
            background-color: #2b354f;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            padding: 12px 14px;
            border-bottom: none;
            letter-spacing: 0.3px;
        }
        .custom-article-table tbody td {
            padding: 12px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .custom-article-table tbody tr:hover {
            background-color: #f8fafc;
        }
        .article-title-cell {
            max-width: 420px;
            line-height: 1.45;
            word-break: break-word;
        }
        .author-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 13px;
            flex-shrink: 0;
            text-transform: uppercase;
        }
        .badge-status {
            padding: 6px 10px;
            border-radius: 50rem;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
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
            padding: 6px 12px;
            border-radius: 50rem;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);
        }
        .action-pdf-btn {
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .action-icon-btn {
            width: 32px;
            height: 32px;
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
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .check-trigger-btn:hover {
            transform: translateY(-1px);
        }

        /* Check Modal Styles */
        .custom-check-modal {
            display: none;
            position: fixed;
            z-index: 99999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow-y: auto;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
        }
        .custom-check-modal-content {
            background: #fff;
            margin: 5% auto;
            padding: 28px;
            border-radius: 16px;
            width: 92%;
            max-width: 520px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            animation: modalFadeIn .25s ease-out;
            position: relative;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .close-check-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 26px;
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
            border: 1px dashed #cbd5e1;
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
            liveScoreDisplay.innerHTML = total.toFixed(1) + ' <small class="fs-12">балл</small>';
        }

        [defInput, classInput, sugInput].forEach(inp => {
            inp.addEventListener('input', updateLiveScore);
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
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Сақланмоқда...';

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
