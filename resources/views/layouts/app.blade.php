<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem Monitoring Keuangan Toko Souvenir Jibranime">
    <meta name="author" content="Toko Souvenir Jibranime">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    {{-- <link rel="shortcut icon" href="{{ asset('assets/img/icons/icon-48x48.png') }}"> --}}

    <title>@yield('title', 'Dashboard') | Toko Souvenir Jibranime</title>

    {{-- Core CSS --}}
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style-custom.css') }}?v={{ filemtime(public_path('assets/css/style-custom.css')) }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vendor CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

    <style>
        /* Pastikan select option bawaan & container Select2 TIDAK PERNAH muncul di SweetAlert2 */
        .swal2-container select,
        .swal2-popup select,
        .swal2-select,
        #swal2-select,
        .swal2-container .select2,
        .swal2-container .select2-container,
        .swal2-popup .select2,
        .swal2-popup .select2-container {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
            width: 0 !important;
            height: 0 !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="wrapper">

        @include('layouts.partials.sidebar')

        <div class="main">

            @include('layouts.partials.navbar')

            <main class="content">
                <div class="container-fluid p-0">
                    @yield('content')
                </div>
            </main>

            @include('layouts.partials.footer')

        </div>
    </div>

    {{-- Core JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/password-strength.js') }}"></script>
    <script src="{{ asset('assets/js/permission-picker.js') }}"></script> 

    {{-- Vendor JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/js/select2-init.js') }}?v={{ filemtime(public_path('assets/js/select2-init.js')) }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function () {
            // Bersihkan dan sembunyikan select / select2 di SweetAlert2 secara real-time
            function cleanSwalSelects() {
                var els = document.querySelectorAll('.swal2-container select, .swal2-popup select, .swal2-select, #swal2-select');
                for (var i = 0; i < els.length; i++) {
                    els[i].style.setProperty('display', 'none', 'important');
                    els[i].setAttribute('hidden', 'hidden');
                }
                var select2Els = document.querySelectorAll('.swal2-container .select2, .swal2-container .select2-container, .swal2-popup .select2-container');
                for (var j = 0; j < select2Els.length; j++) {
                    select2Els[j].remove();
                }
            }
            if (window.MutationObserver) {
                new MutationObserver(cleanSwalSelects).observe(document.documentElement, { childList: true, subtree: true });
            }
            document.addEventListener('DOMContentLoaded', cleanSwalSelects);
        })();
    </script>

    @if(isset($withChart) && $withChart)
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @endif

    {{-- Global Flash Messages --}}
    @include('layouts.partials.flash-messages')

    @stack('scripts')
</body>

</html>