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
    <link href="{{ asset('assets/css/style-custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vendor CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/password-strength.js') }}"></script>
    <script src="{{ asset('assets/js/permission-picker.js') }}"></script> 

    {{-- Vendor JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(isset($withChart) && $withChart)
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @endif

    {{-- Global Flash Messages --}}
    @include('layouts.partials.flash-messages')

    @stack('scripts')
</body>

</html>