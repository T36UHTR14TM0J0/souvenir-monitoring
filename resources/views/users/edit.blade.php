@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER (icon warning/amber untuk edit) --}}
    <x-page-header
        icon="fas fa-user-edit"
        title="Edit User"
        subtitle="Perbarui informasi akun <strong>{{ $user->name }}</strong>."
        iconVariant="warning"
    />

    {{-- FORM CARD --}}
    <div class="card card-modern">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('users.update', $user) }}" method="POST" id="userForm" novalidate>
                @csrf
                @method('PUT')

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
                                   value="{{ old('name', $user->name) }}"
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
                                   value="{{ old('email', $user->email) }}"
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
                                <option value="{{ $role->name }}"
                                    {{ old('role', $user->getRoleNames()->first()) == $role->name ? 'selected' : '' }}>
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

                    {{-- Info Box --}}
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Password bersifat opsional.</strong>
                            Kosongkan kedua field password jika tidak ingin mengubah password user ini.
                        </div>
                    </div>

                    <div class="row g-3">
                        {{-- Password Baru --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom" for="password">
                                Password Baru
                            </label>
                            <div class="input-group-modern">
                                <i class="fas fa-key input-icon"></i>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control form-control-modern @error('password') is-invalid @enderror"
                                       placeholder="Kosongkan jika tidak diubah">
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
                                    <span>Minimal 8 karakter (opsional)</span>
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
                                Konfirmasi Password Baru
                            </label>
                            <div class="input-group-modern">
                                <i class="fas fa-key input-icon"></i>
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       class="form-control form-control-modern"
                                       placeholder="Ulangi password baru">
                                <button type="button"
                                        class="password-toggle"
                                        onclick="togglePassword('password_confirmation', this)"
                                        tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="required-note">
                                <i class="fas fa-circle-info"></i>
                                <span>Harus sama dengan password baru</span>
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
                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label form-check-label-modern" for="is_active">
                        Aktifkan Akun
                        <small>User dapat login ke sistem. Jika tidak dicentang, user tidak bisa login.</small>
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
                        <span>Simpan Perubahan</span>
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

        const passwordFilled = passwordInput.value.length > 0;
        const confirmFilled  = confirmInput.value.length > 0;

        // KHUSUS EDIT: Password boleh kosong (tidak diubah)
        if (passwordFilled || confirmFilled) {

            if (passwordFilled && !confirmFilled) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Konfirmasi Password Kosong',
                    text: 'Anda mengisi password baru. Mohon isi konfirmasi password juga.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: SWAL_PRIMARY,
                });
                confirmInput.focus();
                return false;
            }

            if (!passwordFilled && confirmFilled) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Password Baru Kosong',
                    text: 'Anda mengisi konfirmasi password. Mohon isi password baru juga.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: SWAL_PRIMARY,
                });
                passwordInput.focus();
                return false;
            }

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
                    text: 'Pastikan password baru dan konfirmasi password sama.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: SWAL_PRIMARY,
                });
                confirmInput.focus();
                return false;
            }
        }

        // Konfirmasi simpan
        const userChangedPassword = passwordFilled;
        const confirmText = userChangedPassword
            ? 'Password user akan diubah. Pastikan Anda sudah yakin.'
            : 'Password user tidak akan diubah.';

        Swal.fire({
            title: 'Simpan Perubahan?',
            html: `
                <p style="margin-bottom: 0.5rem;">Pastikan semua data yang diisi sudah benar.</p>
                <p style="font-size: 0.8rem; color: #6b7280; margin: 0;">
                    <i class="fas fa-info-circle"></i> ${confirmText}
                </p>
            `,
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