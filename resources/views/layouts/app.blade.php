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
    <link href="{{ asset('assets/css/components.css') }}?v={{ filemtime(public_path('assets/css/components.css')) }}" rel="stylesheet">
    <link href="{{ asset('assets/css/modal.css') }}?v={{ filemtime(public_path('assets/css/modal.css')) }}" rel="stylesheet">
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

        /* Pastikan icon pada .input-group-modern tidak pernah menimpa teks pada select maupun Select2 */
        .input-group-modern .form-select,
        .input-group-modern .form-select-modern,
        .input-group-modern .select2-container--bootstrap-5 .select2-selection,
        .input-group-modern .select2-container--bootstrap-5 .select2-selection--single {
            padding-left: 2.5rem !important;
        }
        .input-group-modern .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            padding-left: 0 !important;
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

    {{-- Modal Global Pratinjau Bukti Transaksi --}}
    <div class="modal fade" id="modalBuktiTransaksi" tabindex="-1" aria-labelledby="modalBuktiTransaksiTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);">
                <div class="modal-header bg-light py-2 px-3 border-bottom">
                    <h6 class="modal-title font-weight-bold mb-0 text-dark" id="modalBuktiTransaksiTitle">
                        <i class="fas fa-file-invoice text-primary me-2"></i>Bukti Transaksi
                    </h6>
                    <div class="d-flex align-items-center gap-2">
                        <a href="#" target="_blank" id="modalBuktiOpenTab" class="btn btn-sm btn-outline-secondary" title="Buka di tab baru">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-0 text-center bg-dark bg-opacity-10" style="min-height: 280px; display: flex; align-items: center; justify-content: center; position: relative;">
                    <div id="modalBuktiLoading" class="p-4 text-muted">
                        <div class="spinner-border spinner-border-sm text-primary me-1" role="status"></div> Memuat file bukti...
                    </div>
                    <img id="modalBuktiImage" src="" alt="Bukti Transaksi" class="img-fluid d-none" style="max-height: 80vh; max-width: 100%; object-fit: contain; margin: 0 auto; padding: 8px;">
                    <iframe id="modalBuktiPdf" src="" class="w-100 d-none" style="height: 75vh; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showBuktiModal(url, title = 'Bukti Transaksi') {
            const modalEl = document.getElementById('modalBuktiTransaksi');
            if (!modalEl) return;

            document.getElementById('modalBuktiTransaksiTitle').innerHTML = '<i class="fas fa-file-invoice text-primary me-2"></i>' + title;
            document.getElementById('modalBuktiOpenTab').href = url;

            const imgEl = document.getElementById('modalBuktiImage');
            const pdfEl = document.getElementById('modalBuktiPdf');
            const loadingEl = document.getElementById('modalBuktiLoading');

            imgEl.classList.add('d-none');
            pdfEl.classList.add('d-none');
            loadingEl.classList.remove('d-none');
            loadingEl.innerHTML = '<div class="spinner-border spinner-border-sm text-primary me-1" role="status"></div> Memuat file bukti...';

            const cleanUrl = url.split('?')[0].toLowerCase();
            const isPdf = cleanUrl.endsWith('.pdf');

            if (isPdf) {
                pdfEl.src = url;
                loadingEl.classList.add('d-none');
                pdfEl.classList.remove('d-none');
            } else {
                imgEl.onload = function() {
                    loadingEl.classList.add('d-none');
                    imgEl.classList.remove('d-none');
                };
                imgEl.onerror = function() {
                    loadingEl.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i> Gagal memuat gambar bukti</span>';
                };
                imgEl.src = url;
            }

            const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            bsModal.show();
        }
    </script>

    @stack('scripts')
</body>

</html>