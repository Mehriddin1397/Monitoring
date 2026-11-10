@extends('layouts.admin')

@section('title', 'Yangiliklar')

@section('content')

    <div class="nxl-content d-flex flex-column h-100">
        <!-- [ page-header ] start -->
        <div class="page-header position-fixed">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Tug'ilgan kun</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item">Tug'ilgan kun</li>
                </ul>
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
                        <a href="{{ route('brith') }}" target="_blank" class="btn btn-outline-warning">
                            <i class="feather-external-link me-2"></i>
                            <span>Ekranni ko'rish (/brith)</span>
                        </a>
                        <a href="{{ route('news.index') }}" class="btn btn-warning text-dark">
                            <i class="feather-bell me-2"></i>
                            <span>Сўнгги янгиликлар</span>
                        </a>
                        <a href="{{ route('employee-works.index') }}" class="btn btn-info text-white">
                            <i class="feather-book-open me-2"></i>
                            <span>Xodimlar ijodi</span>
                        </a>
                        <a href="{{ route('group-photos.index') }}" class="btn btn-secondary">
                            <i class="feather-image me-2"></i>
                            <span>Guruh rasmlari</span>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas"
                           data-bs-target="#tasksDetailsOffcanvas">
                            <i class="feather-plus me-2"></i>
                            <span>Yaratish</span>
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
        <!-- [ page-header ] end -->
        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-body p-0">
                            <!-- [ Real-time Live Search Bar ] -->
                            <div class="p-3 border-bottom bg-light bg-opacity-50">
                                <div class="row align-items-center g-2">
                                    <div class="col-md-6 col-lg-5">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted">
                                                <i class="feather-search"></i>
                                            </span>
                                            <input type="text" id="employeeSearchInput" class="form-control border-start-0 ps-0" 
                                                   placeholder="F.I.Sh, lavozim, sana yoki telefon bo'yicha tezkor qidirish..." 
                                                   autocomplete="off">
                                            <button type="button" class="btn btn-outline-secondary border-start-0" id="btnClearSearch" style="display: none;" title="Tozalash">
                                                <i class="feather-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-7 text-md-end text-muted small">
                                        <span>Jami: <strong class="text-dark">{{ count($employees) }}</strong> nafar</span>
                                        <span class="mx-2">•</span>
                                        <span>Ko'rsatilmoqda: <strong id="visibleEmployeeCount" class="text-primary">{{ count($employees) }}</strong> nafar</span>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover" id="proposalList">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Photo</th>
                                        <th>F.I.Sh</th>
                                        <th>Qolgan vaqt</th>
                                        <th>Tug'ilgan kuni</th>
                                        <th>Telefon raqami</th>
                                        <th>Dizayn</th>
                                        <th class="text-end">Tahrirlash</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employees as $index => $employee)
                                        <tr class="single-item">
                                            <th>{{$index +1 }}</th>
                                            <td>
                                                @if($employee->photo)
                                                    <img src="{{ asset('storage/' . $employee->photo) }}" alt=""
                                                         width="36" height="36" class="rounded-circle object-fit-cover shadow-sm">
                                                @else
                                                    <div class="avatar-text avatar-sm bg-soft-primary text-primary rounded-circle fw-bold">
                                                        {{ mb_substr($employee->full_name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $employee->full_name }}</strong>
                                                @if($employee->position)
                                                    <div class="small text-muted fs-12">{{ $employee->position }}</div>
                                                @endif
                                                @if($employee->custom_wish)
                                                    <span class="badge bg-soft-primary text-primary mt-1" title="Maxsus tabrik bor"><i class="feather-message-square me-1"></i>Maxsus tabrik</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php $days = $employee->days_until_birthday; @endphp
                                                @if($days === 0)
                                                    <span class="badge bg-danger text-white py-1 px-2.5 shadow-sm fw-bold">
                                                        <i class="feather-gift me-1"></i>Бугун! 🎉
                                                    </span>
                                                @elseif($days === 1)
                                                    <span class="badge bg-warning text-dark py-1 px-2.5 shadow-sm fw-bold">
                                                        <i class="feather-clock me-1"></i>Эртага (1 кун қолди) ⏳
                                                    </span>
                                                @elseif($days !== null && $days <= 7)
                                                    <span class="badge bg-soft-warning text-warning border border-warning-subtle py-1 px-2 fw-semibold">
                                                        <i class="feather-clock me-1"></i>{{ $days }} кун қолди
                                                    </span>
                                                @elseif($days !== null)
                                                    <span class="badge bg-soft-info text-info py-1 px-2">
                                                        <i class="feather-calendar me-1"></i>{{ $days }} кун қолди
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-muted border py-1 px-2">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($employee->birth_date)
                                                    <span class="badge bg-soft-success text-success border border-success-subtle">
                                                        <i class="feather-calendar me-1"></i>{{ \Carbon\Carbon::parse($employee->birth_date)->format('d.m.Y') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-muted border">
                                                        <i class="feather-alert-circle me-1 text-warning"></i>Kiritilmagan
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $employee->phone ?: '—' }}
                                            </td>
                                            <td>
                                                @php
                                                    $th = $employee->theme ?? 'random';
                                                @endphp
                                                @if($th === 'men_classic')
                                                    <span class="badge bg-warning text-dark">🎖️ Medalyon</span>
                                                @elseif($th === 'men_diplomat')
                                                    <span class="badge bg-primary">🏛️ Diplomatik</span>
                                                @elseif($th === 'men_zafar')
                                                    <span class="badge bg-info text-dark">⭐ Zafarnoma</span>
                                                @elseif($th === 'women_rose')
                                                    <span class="badge bg-danger">🌹 Atirgullar</span>
                                                @elseif($th === 'women_emerald')
                                                    <span class="badge bg-success">🌿 Zumrad</span>
                                                @elseif($th === 'women_pearl')
                                                    <span class="badge text-dark" style="background:#fbcfe8;">💎 Marvarid</span>
                                                @else
                                                    <span class="badge bg-secondary">🎲 Random ({{ $employee->gender == 'female' ? 'Ayol' : 'Erkak' }})</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="hstack gap-2 justify-content-end">
                                                    @if($employee->phone)
                                                        <form action="{{ route('employees.send_sms', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ $employee->full_name }} ga tabrik SMS xabari yuborilsinmi?');">
                                                            @csrf
                                                            <button type="submit" class="avatar-text avatar-md text-success border-0 bg-transparent p-0" title="Tabrik SMS yuborish">
                                                                <i class="feather-send"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <button type="button" 
                                                            class="avatar-text avatar-md text-info border-0 bg-transparent p-0 btn-monitor-preview"
                                                            data-id="{{ $employee->id }}"
                                                            data-name="{{ $employee->full_name }}"
                                                            data-url="{{ route('employees.preview', $employee->id) }}"
                                                            title="Katta monitorda (/brith) qanday ko'rinishini tekshirish">
                                                        <i class="feather-tv"></i>
                                                    </button>
                                                    <a href="javascript:void(0)" 
                                                       class="avatar-text avatar-md text-primary btn-edit-employee"
                                                       data-id="{{ $employee->id }}"
                                                       data-name="{{ $employee->full_name }}"
                                                       data-phone="{{ $employee->phone }}"
                                                       data-position="{{ $employee->position }}"
                                                       data-birth="{{ $employee->birth_date ? \Carbon\Carbon::parse($employee->birth_date)->format('Y-m-d') : '' }}"
                                                       data-gender="{{ $employee->gender }}"
                                                       data-theme="{{ $employee->theme }}"
                                                       data-wish="{{ $employee->custom_wish }}"
                                                       data-photo="{{ $employee->photo ? asset('storage/' . $employee->photo) : '' }}"
                                                       data-bg-image="{{ $employee->bg_image ? asset('storage/' . $employee->bg_image) : '' }}"
                                                       data-turniket-photo="{{ $employee->turniket_photo }}"
                                                       title="Tahrirlash">
                                                        <i class="feather feather-edit-3"></i>
                                                    </a>
                                                    <form action="{{ route('employees.destroy', $employee->id) }}"
                                                          method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="avatar-text avatar-md"
                                                                onclick="return confirm('Rostan uchirasizmi?')">
                                                            <i class="feather feather-trash-2"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                        <tr id="noResultsRow" style="display: none;">
                                            <td colspan="8" class="text-center py-5 text-muted">
                                                <i class="feather-search fs-24 d-block mb-2 text-muted"></i>
                                                <span>Qidiruv bo'yicha xodim topilmadi</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('components.admin.brith.brith-modal-create')
    @include('components.admin.brith.brith-modal-edit')

    <!-- [ Monitor Preview Modal Overlay ] start -->
    <style>
        .custom-monitor-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 100060;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        .custom-monitor-modal-overlay.active {
            opacity: 1;
            visibility: visible;
            display: flex !important;
        }
        .custom-monitor-modal-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.78);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            z-index: 1;
            cursor: pointer;
        }
        .custom-monitor-modal-dialog {
            position: relative;
            z-index: 2;
            width: 95vw;
            max-width: 1300px;
            max-height: 94vh;
            display: flex;
            flex-direction: column;
            transform: scale(0.96);
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .custom-monitor-modal-overlay.active .custom-monitor-modal-dialog {
            transform: scale(1);
        }
        .custom-monitor-modal-content {
            background: #0c101c;
            border-radius: 18px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(255, 255, 255, 0.12);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
    </style>

    <div id="monitorPreviewModalOverlay" class="custom-monitor-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="monitorPreviewTitle">
        <div class="custom-monitor-modal-backdrop" onclick="closeMonitorPreview()"></div>
        <div class="custom-monitor-modal-dialog">
            <div class="custom-monitor-modal-content">
                <!-- Header -->
                <div class="modal-header py-3 px-4 border-0 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-bottom: 1px solid rgba(255,255,255,0.08);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning text-dark px-2.5 py-1 fw-bold fs-12">
                            <i class="feather-tv me-1"></i> MONITOR PREVIEW
                        </span>
                        <h5 class="modal-title text-white fw-bold fs-16 mb-0" id="monitorPreviewTitle">
                            Xodim monitorida ko'rinishi
                        </h5>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="#" id="btnOpenNewTabPreview" target="_blank" class="btn btn-sm btn-outline-warning">
                            <i class="feather-maximize me-1"></i> To'liq ekranda ochish
                        </a>
                        <button type="button" class="btn btn-sm btn-light rounded-circle p-2 text-dark" onclick="closeMonitorPreview()" aria-label="Close" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="feather-x"></i>
                        </button>
                    </div>
                </div>

                <!-- Body (Iframe) -->
                <div class="modal-body p-0 position-relative" style="background: #000000; height: 75vh; min-height: 520px;">
                    <div id="previewLoader" class="position-absolute top-50 start-50 translate-middle text-center text-white" style="z-index: 1;">
                        <div class="spinner-border text-warning" role="status" style="width: 3rem; height: 3rem;"></div>
                        <p class="mt-2 text-warning fs-13 mb-0">Monitor ko'rinishi yuklanmoqda...</p>
                    </div>
                    <iframe id="monitorPreviewFrame" src="" class="w-100 h-100 border-0 position-relative" style="z-index: 2; opacity: 0; transition: opacity 0.3s ease;"></iframe>
                </div>

                <!-- Footer -->
                <div class="modal-footer py-2 px-4 border-0 d-flex justify-content-between align-items-center" style="background: #0f172a; border-top: 1px solid rgba(255,255,255,0.08);">
                    <small class="text-white-50 fs-12">
                        <i class="feather-info me-1 text-warning"></i> Ushbu oyna katta monitorda (/brith) xodimning ramkasi, portreti va tabriknomasi qanday ko'rinishini aniq aks ettiradi.
                    </small>
                    <button type="button" class="btn btn-sm btn-secondary px-3" onclick="closeMonitorPreview()">Yopish</button>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Monitor Preview Modal Overlay ] end -->

    <script>
        // Global preview modal open/close functions
        function openMonitorPreview(empName, previewUrl) {
            const overlay = document.getElementById('monitorPreviewModalOverlay');
            const previewFrame = document.getElementById('monitorPreviewFrame');
            const previewTitle = document.getElementById('monitorPreviewTitle');
            const btnOpenNewTab = document.getElementById('btnOpenNewTabPreview');
            const previewLoader = document.getElementById('previewLoader');

            if (previewTitle) {
                previewTitle.textContent = empName + " — Monitorda ko'rinishi (/brith)";
            }
            if (btnOpenNewTab) {
                btnOpenNewTab.href = previewUrl;
            }
            if (previewLoader) {
                previewLoader.style.display = 'block';
            }
            if (previewFrame) {
                previewFrame.style.opacity = '0';
                previewFrame.onload = function() {
                    if (previewLoader) previewLoader.style.display = 'none';
                    previewFrame.style.opacity = '1';
                };
                previewFrame.src = previewUrl;

                // Fallback: Agar onload kechiksa, 1.2s da baribir ko'rsatish
                setTimeout(function() {
                    if (previewLoader) previewLoader.style.display = 'none';
                    if (previewFrame) previewFrame.style.opacity = '1';
                }, 1200);
            }

            if (overlay) {
                overlay.style.display = 'flex';
                // Trigger reflow for smooth transition
                void overlay.offsetHeight;
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeMonitorPreview() {
            const overlay = document.getElementById('monitorPreviewModalOverlay');
            const previewFrame = document.getElementById('monitorPreviewFrame');
            if (overlay) {
                overlay.classList.remove('active');
                setTimeout(function() {
                    overlay.style.display = 'none';
                    document.body.style.overflow = '';
                }, 250);
            }
            if (previewFrame) {
                previewFrame.src = '';
                previewFrame.style.opacity = '0';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Modal elementini body ga ko'chirish (har qanday stacking context trapdan xalos bo'lish uchun)
            const overlay = document.getElementById('monitorPreviewModalOverlay');
            if (overlay && overlay.parentNode !== document.body) {
                document.body.appendChild(overlay);
            }

            // Monitor Preview button click listener
            document.querySelectorAll('.btn-monitor-preview').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const empName = this.getAttribute('data-name');
                    const previewUrl = this.getAttribute('data-url');
                    openMonitorPreview(empName, previewUrl);
                });
            });

            // ESC bosilganda modalni yopish
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const ov = document.getElementById('monitorPreviewModalOverlay');
                    if (ov && ov.classList.contains('active')) {
                        closeMonitorPreview();
                    }
                }
            });

            const searchInput = document.getElementById('employeeSearchInput');
            const clearBtn = document.getElementById('btnClearSearch');
            const countEl = document.getElementById('visibleEmployeeCount');
            const rows = document.querySelectorAll('#proposalList tbody tr.single-item');
            const noResultsRow = document.getElementById('noResultsRow');

            if (!searchInput) return;

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                let visible = 0;

                if (clearBtn) {
                    clearBtn.style.display = query ? 'block' : 'none';
                }

                rows.forEach(function(row) {
                    const text = row.innerText.toLowerCase();
                    if (!query || text.includes(query)) {
                        row.style.display = '';
                        visible++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (countEl) countEl.innerText = visible;
                if (noResultsRow) noResultsRow.style.display = (visible === 0) ? '' : 'none';
            });

            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    searchInput.dispatchEvent(new Event('input'));
                    searchInput.focus();
                });
            }
        });
    </script>
@endsection
