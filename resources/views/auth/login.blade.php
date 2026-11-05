<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem Monitoring Keuangan Toko Souvenir Jibranime">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="{{ asset('assets/img/icons/icon-48x48.png') }}" />

    <title>Sign In | Toko Souvenir Jibranime</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600;1,700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --blue-50: #f0f5fb;
            --blue-100: #dde9f5;
            --blue-200: #bcd3eb;
            --blue-300: #8fb4d9;
            --blue-400: #5f8fc2;
            --blue-500: #3e6fa8;
            --blue-600: #2f5889;
            --blue-700: #26476f;
            --blue-800: #1f3a5c;
            --blue-900: #1a304d;
            --blue-950: #0f1f33;

            --cream-50: #fbfaf7;
            --cream-100: #f7f4ee;
            --cream-200: #efe9dd;

            --stone-50: #fafaf9;
            --stone-100: #f5f5f4;
            --stone-200: #e7e5e4;
            --stone-300: #d6d3d1;
            --stone-400: #a8a29e;
            --stone-500: #78716c;
            --stone-600: #57534e;
            --stone-700: #44403c;
            --stone-800: #292524;
            --stone-900: #1c1917;
        }

        html, body {
            height: 100%;
            overflow: hidden;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--cream-100);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .bg-art {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: linear-gradient(135deg, #f0f5fb 0%, #dde9f5 100%);
            overflow: hidden;
        }

        .bg-art::before {
            content: '';
            position: absolute;
            top: -15%;
            right: -10%;
            width: 650px;
            height: 650px;
            background: radial-gradient(circle, rgba(62, 111, 168, 0.1) 0%, transparent 65%);
            border-radius: 50%;
            animation: float1 24s ease-in-out infinite;
        }

        .bg-art::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(95, 143, 194, 0.08) 0%, transparent 65%);
            border-radius: 50%;
            animation: float2 28s ease-in-out infinite;
        }

        @keyframes float1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-50px, 40px) scale(1.08); }
        }

        @keyframes float2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(50px, -40px) scale(1.05); }
        }

        .login-page {
            height: 100vh;
            height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            width: 100%;
            max-width: 1040px;
            height: 100%;
            max-height: 620px;
            background: #ffffff;
            border-radius: 32px;
            box-shadow:
                0 40px 100px -20px rgba(26, 48, 77, 0.15),
                0 20px 40px -10px rgba(26, 48, 77, 0.08),
                0 0 0 1px rgba(26, 48, 77, 0.04);
            overflow: hidden;
            display: grid;
            grid-template-columns: 48% 52%;
            animation: scaleIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.96) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .login-brand {
            background: linear-gradient(165deg, #3e6fa8 0%, #2f5889 45%, #26476f 100%);
            padding: 44px 40px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .login-brand::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 25% 20%, rgba(255, 255, 255, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 75% 85%, rgba(255, 255, 255, 0.05) 0%, transparent 40%);
            pointer-events: none;
        }

        .login-brand::after {
            content: '';
            position: absolute;
            top: 55%;
            right: -140px;
            width: 380px;
            height: 380px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 50%;
            transform: translateY(-50%);
        }

        .brand-content {
            position: relative;
            z-index: 2;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 36px;
            padding: 7px 16px 7px 7px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
        }

        .brand-logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #fbfaf7 0%, #efe9dd 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #26476f;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .brand-logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.3px;
            color: #ffffff;
        }

        .brand-title {
            font-family: 'Playfair Display', serif;
            font-size: 34px;
            font-weight: 700;
            line-height: 1.18;
            margin-bottom: 14px;
            color: #ffffff;
            letter-spacing: -0.3px;
        }

        .brand-title-accent {
            font-style: italic;
            font-weight: 600;
            color: #bcd3eb;
            display: inline-block;
            margin-top: 2px;
        }

        .brand-subtitle {
            font-size: 13.5px;
            line-height: 1.65;
            opacity: 0.82;
            margin-bottom: 28px;
            color: #dde9f5;
            max-width: 95%;
            font-weight: 400;
        }

        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 11px;
            margin-top: 8px;
        }

        .brand-feature {
            display: flex;
            align-items: center;
            gap: 11px;
            font-size: 12.5px;
            color: #dde9f5;
            opacity: 0.92;
            font-weight: 400;
        }

        .brand-feature-icon {
            width: 26px;
            height: 26px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #fbfaf7;
        }

        .brand-footer {
            position: relative;
            z-index: 2;
            font-size: 10.5px;
            opacity: 0.6;
            color: #dde9f5;
            display: flex;
            align-items: center;
            gap: 7px;
            font-weight: 400;
            letter-spacing: 0.2px;
        }

        .brand-footer-dot {
            width: 5px;
            height: 5px;
            background: #bcd3eb;
            border-radius: 50%;
            box-shadow: 0 0 6px rgba(188, 211, 235, 0.8);
            animation: pulse 2.4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }

        .login-form-side {
            padding: 48px 56px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
            background: #ffffff;
            position: relative;
        }

        .login-form-side::-webkit-scrollbar {
            width: 4px;
        }
        .login-form-side::-webkit-scrollbar-thumb {
            background: var(--stone-200);
            border-radius: 2px;
        }

        .form-header {
            margin-bottom: 32px;
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--blue-950);
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }

        .form-subtitle {
            font-size: 13px;
            color: var(--stone-500);
            line-height: 1.5;
            font-weight: 400;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--blue-900);
            margin-bottom: 7px;
            display: block;
            letter-spacing: 0.1px;
        }

        .form-control {
            width: 100%;
            height: 46px;
            border-radius: 11px;
            border: 1.5px solid var(--stone-200);
            font-size: 13.5px;
            padding: 0 15px;
            transition: all 0.2s ease;
            background: var(--stone-50);
            font-family: inherit;
            color: var(--stone-900);
        }

        .form-control::placeholder {
            color: var(--stone-400);
            font-weight: 400;
        }

        .form-control:hover {
            border-color: var(--stone-300);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--blue-500);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(62, 111, 168, 0.08);
        }

        .form-control.is-invalid {
            border-color: #dc2626;
            background: #fef2f2;
        }

        .input-group {
            display: flex;
            align-items: stretch;
            position: relative;
        }

        .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-right: 0;
        }

        .input-group .btn-toggle {
            border: 1.5px solid var(--stone-200);
            border-left: 0;
            border-radius: 0 11px 11px 0;
            background: var(--stone-50);
            color: var(--stone-400);
            padding: 0 15px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .input-group .btn-toggle:hover {
            color: var(--blue-600);
        }

        .input-group .form-control:focus ~ .btn-toggle {
            border-color: var(--blue-500);
            background: #fff;
            color: var(--blue-600);
        }

        .btn-login {
            width: 100%;
            height: 46px;
            border-radius: 11px;
            background: linear-gradient(135deg, #2f5889 0%, #26476f 100%);
            border: none;
            color: #ffffff;
            font-weight: 600;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 14px rgba(38, 71, 111, 0.25);
            font-family: inherit;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.2px;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transition: left 0.6s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-1.5px);
            box-shadow: 0 8px 22px rgba(38, 71, 111, 0.35);
            background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .mb-3 { margin-bottom: 15px; }
        .mb-4 { margin-bottom: 22px; }

        .swal2-popup {
            font-family: 'Inter', sans-serif !important;
            border-radius: 18px !important;
            padding: 28px 24px !important;
        }

        .swal2-title {
            font-family: 'Playfair Display', serif !important;
            font-size: 22px !important;
            font-weight: 700 !important;
            color: #0f1f33 !important;
        }

        .swal2-html-container {
            font-size: 13.5px !important;
            color: #57534e !important;
            margin-top: 8px !important;
        }

        .swal2-confirm {
            background: linear-gradient(135deg, #2f5889 0%, #26476f 100%) !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
            font-size: 13.5px !important;
            padding: 10px 24px !important;
            box-shadow: 0 4px 14px rgba(38, 71, 111, 0.25) !important;
            font-family: 'Inter', sans-serif !important;
        }

        .swal2-confirm:hover {
            background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%) !important;
            box-shadow: 0 8px 22px rgba(38, 71, 111, 0.35) !important;
        }

        .swal2-cancel {
            background: #f5f5f4 !important;
            color: #57534e !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
            font-size: 13.5px !important;
            padding: 10px 24px !important;
            font-family: 'Inter', sans-serif !important;
        }

        .swal2-cancel:hover {
            background: #e7e5e4 !important;
        }

        .swal2-icon {
            border-width: 3px !important;
            margin: 0 auto 16px !important;
        }

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

        @media (max-width: 960px) {
            .login-card { grid-template-columns: 44% 56%; max-height: 600px; }
            .login-brand { padding: 36px 30px; }
            .brand-title { font-size: 28px; }
            .login-form-side { padding: 36px 40px; }
            .form-title { font-size: 24px; }
        }

        @media (max-width: 768px) {
            html, body { overflow: auto; }
            .login-page { padding: 0; height: auto; min-height: 100vh; align-items: flex-start; }
            .login-card {
                grid-template-columns: 1fr;
                max-height: none;
                height: auto;
                min-height: 100vh;
                border-radius: 0;
            }
            .login-brand { padding: 36px 28px 32px; }
            .brand-title { font-size: 26px; }
            .brand-features { display: none; }
            .brand-subtitle { margin-bottom: 0; }
            .login-form-side { padding: 32px 28px; }
            .form-title { font-size: 22px; }
        }

        @media (max-height: 720px) {
            .login-card { max-height: 94vh; }
            .login-brand { padding: 28px 32px; }
            .brand-logo { margin-bottom: 22px; }
            .brand-title { font-size: 26px; margin-bottom: 10px; }
            .brand-subtitle { margin-bottom: 20px; font-size: 12.5px; }
            .login-form-side { padding: 30px 42px; }
            .form-header { margin-bottom: 24px; }
            .form-title { font-size: 24px; }
        }

        @media (max-height: 560px) and (orientation: landscape) {
            html, body { overflow: auto; }
            .login-page { height: auto; min-height: 100vh; align-items: flex-start; padding: 16px; }
            .login-card { max-height: none; height: auto; grid-template-columns: 40% 60%; }
            .brand-features { display: none; }
            .login-brand { padding: 22px 26px; }
            .brand-title { font-size: 20px; margin-bottom: 8px; }
            .login-form-side { padding: 22px 26px; }
        }
    </style>
</head>

<body>
    <div class="bg-art"></div>

    <div class="login-page">
        <div class="login-card">

            <div class="login-brand">
                <div class="brand-content">
                    <div class="brand-logo">
                        <div class="brand-logo-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                                <path d="M3 6h18"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </div>
                        <div class="brand-logo-text">Toko Souvenir Jibranime</div>
                    </div>

                    <h1 class="brand-title">
                        Kelola Keuangan<br>
                        <span class="brand-title-accent">Toko Souvenir Jibranime</span>
                    </h1>
                    <p class="brand-subtitle">
                        Pantau pemasukan, pengeluaran, dan laba dalam satu dashboard yang elegan.
                    </p>

                    <div class="brand-features">
                        <div class="brand-feature">
                            <div class="brand-feature-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 3v18h18"/>
                                    <path d="m19 9-5 5-4-4-3 3"/>
                                </svg>
                            </div>
                            <span>Dashboard real-time</span>
                        </div>
                        <div class="brand-feature">
                            <div class="brand-feature-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                </svg>
                            </div>
                            <span>Laporan laba/rugi otomatis</span>
                        </div>
                        <div class="brand-feature">
                            <div class="brand-feature-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                                </svg>
                            </div>
                            <span>Multi-user & hak akses</span>
                        </div>
                    </div>
                </div>

                <div class="brand-footer">
                    <span class="brand-footer-dot"></span>
                    <span>&copy; {{ date('Y') }} Toko Souvenir Jibranime</span>
                </div>
            </div>

            <div class="login-form-side">
                <div class="form-header">
                    <h2 class="form-title">Selamat Datang</h2>
                    <p class="form-subtitle">Masuk untuk melanjutkan ke dashboard Anda</p>
                </div>

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    @if($errors->any())
                        <div class="alert alert-danger py-2 px-3 mb-3" style="font-size: 0.85rem; border-radius: 8px;">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="nama@gmail.com"
                               required
                               autofocus />
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input class="form-control @error('password') is-invalid @enderror"
                                   type="password"
                                   name="password"
                                   id="password"
                                   placeholder="••••••••"
                                   required />
                            <button class="btn-toggle" type="button" id="togglePassword" aria-label="Toggle password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="eyeIcon">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4"></div>

                    <button type="submit" class="btn-login" id="btnLogin">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" x2="3" y1="12" y2="12"/>
                        </svg>
                        Sign In
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function () {
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

    <script>
        document.getElementById('togglePassword')?.addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                    <line x1="2" x2="22" y1="2" y2="22"/>
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                    <circle cx="12" cy="12" r="3"/>
                `;
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            @if(session('swal'))
                Swal.fire({
                    icon: @json(session('swal.type')),
                    title: @json(session('swal.title')),
                    text: @json(session('swal.text')),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2f5889',
                    background: '#ffffff',
                    timer: @json(session('swal.type')) === 'success' ? 2500 : undefined,
                    timerProgressBar: @json(session('swal.type')) === 'success',
                    showConfirmButton: @json(session('swal.type')) !== 'success',
                });
            @endif

            @if(session('success') && !session('swal'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2f5889',
                    background: '#ffffff',
                    timer: 2500,
                    timerProgressBar: true,
                    showConfirmButton: false,
                });
            @endif

            @if(session('error') && !session('swal'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: @json(session('error')),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2f5889',
                    background: '#ffffff',
                });
            @endif
        });

        document.getElementById('loginForm')?.addEventListener('submit', function () {
            const btn = document.getElementById('btnLogin');
            btn.disabled = true;
            btn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 1s linear infinite;">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                </svg>
                Memproses...
            `;
        });
    </script>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</body>

</html>