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
    <!--! BEGIN: Favicon-->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/images/logo.svg')}}" />
    <!--! END: Favicon-->
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
<!--! END: Theme Customizer !-->
    @yield('scripts')
    @stack('scripts')
</body>

</html>
