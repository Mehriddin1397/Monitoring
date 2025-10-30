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
<script src="/admin/assets/vendors/js/vendors.min.js"></script>
<!-- vendors.min.js {always must need to be top} -->
{{--<script src="/admin/assets/vendors/js/daterangepicker.min.js"></script>--}}
{{--<script src="/admin/assets/vendors/js/apexcharts.min.js"></script>--}}
{{--<script src="/admin/assets/vendors/js/circle-progress.min.js"></script>--}}
<!--! END: Vendors JS !-->
<!--! BEGIN: Apps Init  !-->
{{--<script src="/admin/assets/vendors/js/select2.min.js"></script>--}}
{{--<script src="/admin/assets/vendors/js/select2-active.min.js"></script>--}}

<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>


<script src="/admin/assets/js/common-init.min.js"></script>
<script src="/admin/assets/js/dashboard-init.min.js"></script>


<script src="{{asset('assets/vendors/js/vendors.min.js')}}"></script>
<!-- vendors.min.js {always must need to be top} -->
<script src="{{asset('assets/vendors/js/apexcharts.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/circle-progress.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/jquery.calendar.min.js')}}"></script>
<!--! END: Vendors JS !-->
<!--! BEGIN: Apps Init  !-->
<script src="{{asset('assets/js/common-init.min.js')}}"></script>
<script src="{{asset('assets/js/reports-project-init.min.js')}}"></script>
<!--! END: Apps Init !-->
<!--! BEGIN: Theme Customizer  !-->
<script src="{{asset('assets/js/theme-customizer-init.min.js')}}"></script>

<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor1');
    CKEDITOR.replace('editor2');
    CKEDITOR.replace('editor3');
    CKEDITOR.replace('editor4');
    CKEDITOR.replace('editor5');
    CKEDITOR.replace('editor6');
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.ckeditor').forEach(function (el) {
            // CKEditor ishlashi uchun elementda id bo‘lishi kerak
            if (!el.id) {
                el.id = 'ckeditor-' + Math.random().toString(36).substr(2, 9);
            }

            // Agar allaqachon CKEditor ulangani bo‘lsa, qayta ulamaslik
            if (!CKEDITOR.instances[el.id]) {
                CKEDITOR.replace(el.id);
            }
        });
    });
</script>
<!--! END: Theme Customizer !-->
</body>

</html>
