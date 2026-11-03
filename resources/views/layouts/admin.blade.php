<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keyword" content="" />
    <meta name="author" content="flexilecode" />
    <!--! The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags !-->
    <!--! BEGIN: Apps Title-->
    <title>@yield('title') </title>
    <!--! END:  Apps Title-->
    <!--! BEGIN: Favicon & PWA Manifest-->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/images/logo.svg')}}" />
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2563eb">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Smart-Ijro">
    <!--! END: Favicon & PWA Manifest-->
    <!--! BEGIN: Bootstrap CSS-->
    <link rel="stylesheet" type="text/css" href="/admin/assets/css/bootstrap.min.css" />
    <!--! END: Bootstrap CSS-->
    <!--! BEGIN: Vendors CSS-->
    <link rel="stylesheet" type="text/css" href="/admin/assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="/admin/assets/vendors/css/daterangepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="/admin/assets/vendors/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/assets/vendors/css/select2-theme.min.css">
    <!--! END: Vendors CSS-->
    <!--! BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="/admin/assets/css/theme.min.css" />
    <!--! END: Custom CSS-->
    <!--! HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries !-->
    <!--! WARNING: Respond.js doesn"t work if you view the page via file: !-->
    <!--[if lt IE 9]>
    <script src="https:oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https:oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


</head>

<style>
    .nxl-container .nxl-content .main-content {
        overflow-x: inherit !important;
    }
    .table-responsive {
        overflow-x: visible !important;
        -webkit-overflow-scrolling: touch;
    }
</style>

<body>

<style>
    /* Orqa fon */
    .custom-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    /* Modal oynasi */
    .custom-modal-content {
        background: #fff;
        margin: 5% auto;
        padding: 15px;
        border-radius: 10px;
        width: 80%;
        max-width: 1000px;
        position: relative;
    }

    /* Yopish tugmasi */
    .close-btn {
        position: absolute;
        right: 15px;
        top: 10px;
        font-size: 24px;
        font-weight: bold;
        color: #000;
        cursor: pointer;
    }

    .close-btn:hover {
        color: red;
    }
</style>


<script>
    function openModal(pdfUrl) {
        document.getElementById('pdfFrame').src = pdfUrl;
        document.getElementById('pdfModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('pdfModal').style.display = 'none';
        document.getElementById('pdfFrame').src = '';
    }

    // Orqa fonni bosganda yopiladi
    window.onclick = function(event) {
        let modal = document.getElementById('pdfModal');
        if (event.target === modal) {
            closeModal();
        }
    }
</script>
<!--! ================================================================ !-->
<!--! [Start] Navigation Manu !-->
<!--! ================================================================ !-->
<x-admin.sidebar></x-admin.sidebar>
<!--! ================================================================ !-->
<!--! [End]  Navigation Manu !-->
<!--! ================================================================ !-->
<!--! ================================================================ !-->
<!--! [Start] Header !-->
<!--! ================================================================ !-->
<x-admin.header></x-admin.header>
<!--! ================================================================ !-->
<!--! [End] Header !-->
<!--! ================================================================ !-->
<!--! ================================================================ !-->
<!--! [Start] Main Content !-->
<!--! ================================================================ !-->
<main class="nxl-container">
    @yield('content')

    <!-- [ Footer ] start -->
   <x-admin.footer></x-admin.footer>
    <!-- [ Footer ] end -->
</main>

<x-admin.birthday-modal />
<!--! ================================================================ !-->
<!--! [End] Main Content !-->
<!--! ================================================================ !-->
<!--! ================================================================ !-->
<!--! BEGIN: Theme Customizer !-->
<!--! ================================================================ !-->
<!--! ================================================================ !-->
<!--! [End] Theme Customizer !-->
<!--! ================================================================ !-->
<!--! ================================================================ !-->
<!--! Footer Script !-->
<!--! ================================================================ !-->
<!--! BEGIN: Vendors JS !-->
<script src="{{asset('assets/vendors/js/vendors.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/apexcharts.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/circle-progress.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/jquery.calendar.min.js')}}"></script>
<!--! END: Vendors JS !-->
<!--! BEGIN: Apps Init  !-->
<script src="{{asset('assets/js/common-init.min.js')}}"></script>
<script src="{{asset('assets/js/reports-project-init.min.js')}}"></script>
<script src="{{asset('assets/js/theme-customizer-init.min.js')}}"></script>

<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Standart editorlar mavjud bo'lsa
        ['editor1', 'editor2', 'editor3', 'editor4', 'editor5', 'editor6'].forEach(function (id) {
            if (document.getElementById(id) && typeof CKEDITOR !== 'undefined' && !CKEDITOR.instances[id]) {
                CKEDITOR.replace(id);
            }
        });

        // 2. Sahifa qotib qolmasligi uchun faqat ochilgan modal/offcanvas ichidagi CKEditorlarni ulash (Lazy init)
        function initEditorsInContainer(container) {
            if (typeof CKEDITOR === 'undefined') return;
            container.querySelectorAll('.ckeditor').forEach(function (el) {
                if (!el.id) {
                    el.id = 'ckeditor-' + Math.random().toString(36).substr(2, 9);
                }
                if (!CKEDITOR.instances[el.id]) {
                    CKEDITOR.replace(el.id);
                }
            });
        }

        document.addEventListener('shown.bs.offcanvas', function (e) {
            initEditorsInContainer(e.target);
        });
        document.addEventListener('shown.bs.modal', function (e) {
            initEditorsInContainer(e.target);
        });

        // 3. Agar modallardan tashqarida ochiq .ckeditor bo'lsa
        document.querySelectorAll('.ckeditor:not(.offcanvas .ckeditor):not(.modal .ckeditor)').forEach(function (el) {
            if (!el.id) {
                el.id = 'ckeditor-' + Math.random().toString(36).substr(2, 9);
            }
            if (typeof CKEDITOR !== 'undefined' && !CKEDITOR.instances[el.id]) {
                CKEDITOR.replace(el.id);
            }
        });
    });
</script>

@auth
<script>
    (function() {
        let lastKnownUnread = null;

        function playGlobalChatSound() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                const now = ctx.currentTime;

                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(587.33, now);
                gain1.gain.setValueAtTime(0.18, now);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.22);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(now);
                osc1.stop(now + 0.22);

                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(880.00, now + 0.08);
                gain2.gain.setValueAtTime(0.24, now + 0.08);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.48);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(now + 0.08);
                osc2.stop(now + 0.48);
            } catch (e) {}
        }

        // Service Worker registratsiyasi
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(reg) {
                    window.swRegistration = reg;
                }).catch(function(err) {
                    console.warn('SW registration failed:', err);
                });
            });
        }

        // Tizim bildirishnoma ruxsatini olish
        window.requestChatNotificationPermission = async function() {
            if (!("Notification" in window)) return false;
            if (Notification.permission === "granted") return true;
            if (Notification.permission !== "denied") {
                const res = await Notification.requestPermission();
                return res === "granted";
            }
            return false;
        };

        // Desktop Windows Toast bildirishnomasi
        window.showDesktopNotification = function(title, body, url) {
            if (!("Notification" in window) || Notification.permission !== "granted") return;
            const options = {
                body: body || 'Sizga yangi xabar keldi',
                icon: '{{ asset("assets/images/logo.svg") }}',
                badge: '{{ asset("assets/images/logo.svg") }}',
                data: { url: url || '{{ route("chat.index") }}' }
            };

            if (navigator.serviceWorker && navigator.serviceWorker.controller) {
                navigator.serviceWorker.ready.then(function(reg) {
                    reg.showNotification(title || 'Smart-Ijro — Muloqot', options);
                });
            } else {
                try {
                    const notif = new Notification(title || 'Smart-Ijro — Muloqot', options);
                    notif.onclick = function() {
                        window.focus();
                        window.location.href = options.data.url;
                    };
                } catch (e) {}
            }
        };

        async function checkGlobalChatUnread() {
            try {
                if (window.location.pathname.includes('/chat')) return;

                const res = await fetch('{{ route("chat.poll") }}', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                const total = data.total_unread || 0;

                // Yangi xabar kelgan bo'lsa ovozli signal berish va bildirishnoma chiqarish
                if (lastKnownUnread !== null && total > lastKnownUnread) {
                    playGlobalChatSound();
                    window.showDesktopNotification('Smart-Ijro — Muloqot', 'Sizda yangi o\'qilmagan xabarlar mavjud!', '{{ route("chat.index") }}');
                }
                lastKnownUnread = total;

                const headerBadge = document.getElementById('headerChatBadge');
                if (headerBadge) {
                    headerBadge.innerText = total;
                    if (total > 0) {
                        headerBadge.classList.remove('d-none');
                    } else {
                        headerBadge.classList.add('d-none');
                    }
                }

                const sidebarBadge = document.getElementById('sidebarChatBadge');
                if (sidebarBadge) {
                    sidebarBadge.innerText = total;
                    sidebarBadge.style.display = total > 0 ? 'inline-block' : 'none';
                }
            } catch (e) {}
        }

        document.addEventListener('DOMContentLoaded', function() {
            checkGlobalChatUnread();
            setInterval(checkGlobalChatUnread, 15000);
        });
    })();
</script>
@endauth
<!--! END: Theme Customizer !-->
    @yield('scripts')
    @stack('scripts')
</body>

</html>
