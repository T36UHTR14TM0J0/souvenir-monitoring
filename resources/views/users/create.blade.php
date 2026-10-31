@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-user-plus"
        title="Tambah User"
        subtitle="Buat akun pengguna baru dan tentukan hak aksesnya."
    />

    {{-- FORM CARD --}}
    <div class="card card-modern">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('users.store') }}" method="POST" id="userForm" novalidate>
                @csrf

                {{-- ============ SECTION: INFORMASI AKUN ============ --}}
                <div class="form-section-title">
                    <i class="fas fa-user-circle"></i>
                    Informasi Akun
                </div>

                <div class="row g-3">
                    {{-- Nama --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="name">
                            Nama <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control form-control-modern @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Masukkan nama lengkap"
                                   autofocus>
                        </div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="required-note">
                                <i class="fas fa-circle-info"></i>
                                <span>Wajib diisi</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="email">
                            Email <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control form-control-modern @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="required-note">
                                <i class="fas fa-circle-info"></i>
                                <span>Wajib diisi</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div class="col-12">
                        <label class="form-label-custom" for="role">
                            Role <span class="required">*</span>
                        </label>
                        <select name="role"
                                id="role"
                                class="form-select form-select-modern @error('role') is-invalid @enderror">
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="required-note">
                                <i class="fas fa-circle-info"></i>
                                <span>Wajib diisi</span>
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- ============ SECTION: KEAMANAN AKUN ============ --}}
                <div class="form-section mt-4">
                    <div class="form-section-title">
                        <i class="fas fa-lock"></i>
                        Keamanan Akun
                    </div>

                    <div class="row g-3">
                        {{-- Password --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom" for="password">
                                Password <span class="required">*</span>
                            </label>
                            <div class="input-group-modern">
                                <i class="fas fa-key input-icon"></i>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control form-control-modern @error('password') is-invalid @enderror"
                                       placeholder="Minimal 8 karakter">
                                <button type="button"
                                        class="password-toggle"
                                        onclick="togglePassword('password', this)"
                                        tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="required-note">
                                    <i class="fas fa-circle-info"></i>
                                    <span>Wajib diisi, minimal 8 karakter</span>
                                </div>
                            @enderror
                            <div class="password-strength" id="passwordStrength">
                                <div class="password-strength-bar"></div>
                                <div class="password-strength-bar"></div>
                                <div class="password-strength-bar"></div>
                                <div class="password-strength-bar"></div>
                            </div>
                            <div class="password-strength-text" id="passwordStrengthText"></div>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom" for="password_confirmation">
                                Konfirmasi Password <span class="required">*</span>
                            </label>
                            <div class="input-group-modern">
                                <i class="fas fa-key input-icon"></i>
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       class="form-control form-control-modern"
                                       placeholder="Ulangi password">
                                <button type="button"
                                        class="password-toggle"
                                        onclick="togglePassword('password_confirmation', this)"
                                        tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="required-note">
                                <i class="fas fa-circle-info"></i>
                                <span>Wajib diisi, harus sama dengan password</span>
                            </div>
                            <div class="password-strength-text" id="passwordMatchText"></div>
                        </div>
                    </div>
                </div>

                {{-- ============ SECTION: STATUS ============ --}}
                <div class="form-check-modern">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="form-check-input form-check-input-modern"
                           id="is_active"
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label form-check-label-modern" for="is_active">
                        Aktifkan Akun
                        <small>User dapat langsung login ke sistem setelah akun dibuat.</small>
                    </label>
                </div>

                <hr class="form-divider">

                {{-- ============ ACTIONS ============ --}}
                <div class="form-actions">
                    <a href="{{ route('users.index') }}" class="btn btn-danger-custom">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <button type="submit" class="btn btn-primary-custom" id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span>Simpan User</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const SWAL_PRIMARY = '#26476f';
    const SWAL_CANCEL  = '#a8a29e';

    const form          = document.getElementById('userForm');
    const submitBtn     = document.getElementById('submitBtn');
    const passwordInput = document.getElementById('password');
    const confirmInput  = document.getElementById('password_confirmation');

    if (!form) return;

    /* ---------- FORM SUBMIT ---------- */
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (passwordInput.value.length < 8) {
            Swal.fire({
                icon: 'warning',
                title: 'Password Terlalu Pendek',
                text: 'Password minimal harus 8 karakter.',
                confirmButtonText: 'OK',
                confirmButtonColor: SWAL_PRIMARY,
            });
            passwordInput.focus();
            return false;
        }

        if (passwordInput.value !== confirmInput.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Password Tidak Cocok',
                text: 'Pastikan password dan konfirmasi password sama.',
                confirmButtonText: 'OK',
                confirmButtonColor: SWAL_PRIMARY,
            });
            confirmInput.focus();
            return false;
        }

        Swal.fire({
            title: 'Simpan User?',
            text: 'Pastikan semua data yang diisi sudah benar.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-1"></i> Ya, Simpan!',
            cancelButtonText: 'Batal',
            confirmButtonColor: SWAL_PRIMARY,
            cancelButtonColor: SWAL_CANCEL,
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Menyimpan...</span>';
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    });

})();
</script>
@endpush