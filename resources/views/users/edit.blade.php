@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-user-edit"
        title="Edit User"
        subtitle="Perbarui data akun pengguna dan hak aksesnya."
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
                        <div class="invalid-feedback" id="error_name" @if(!$errors->has('name')) style="display: none !important;" @endif>
                            @error('name'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_name" @if($errors->has('name')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib diisi</span>
                        </div>
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
                        <div class="invalid-feedback" id="error_email" @if(!$errors->has('email')) style="display: none !important;" @endif>
                            @error('email'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_email" @if($errors->has('email')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib diisi dengan email valid</span>
                        </div>
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
                        <div class="invalid-feedback" id="error_role" @if(!$errors->has('role')) style="display: none !important;" @endif>
                            @error('role'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_role" @if($errors->has('role')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib dipilih</span>
                        </div>
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
                            <div class="invalid-feedback" id="error_password" @if(!$errors->has('password')) style="display: none !important;" @endif>
                                @error('password'){{ $message }}@enderror
                            </div>
                            <div class="required-note" id="note_password" @if($errors->has('password')) style="display: none !important;" @endif>
                                <i class="fas fa-circle-info"></i>
                                <span>Minimal 8 karakter (opsional)</span>
                            </div>
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
                            <div class="invalid-feedback" id="error_password_confirmation" style="display: none !important;"></div>
                            <div class="required-note" id="note_password_confirmation">
                                <i class="fas fa-circle-info"></i>
                                <span>Harus sama dengan password baru jika diisi</span>
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

    const form          = document.getElementById('userForm');
    const submitBtn     = document.getElementById('submitBtn');
    const passwordInput = document.getElementById('password');
    const confirmInput  = document.getElementById('password_confirmation');

    if (!form) return;

    const fields = ['name', 'email', 'role', 'password', 'password_confirmation'];

    function setFieldError(fieldId, message) {
        const input   = document.getElementById(fieldId);
        const errorEl = document.getElementById('error_' + fieldId);
        const noteEl  = document.getElementById('note_' + fieldId);

        if (input) {
            input.classList.add('is-invalid');
            const select2 = input.nextElementSibling;
            if (select2 && select2.classList.contains('select2-container')) {
                select2.classList.add('is-invalid');
            }
        }
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.style.setProperty('display', 'block', 'important');
        }
        if (noteEl) {
            noteEl.style.setProperty('display', 'none', 'important');
        }
    }

    function clearFieldError(fieldId) {
        const input   = document.getElementById(fieldId);
        const errorEl = document.getElementById('error_' + fieldId);
        const noteEl  = document.getElementById('note_' + fieldId);

        if (input) {
            input.classList.remove('is-invalid');
            const select2 = input.nextElementSibling;
            if (select2 && select2.classList.contains('select2-container')) {
                select2.classList.remove('is-invalid');
            }
        }
        if (errorEl) {
            errorEl.textContent = '';
            errorEl.style.setProperty('display', 'none', 'important');
        }
        if (noteEl) {
            noteEl.style.removeProperty('display');
        }
    }

    fields.forEach(function (fieldId) {
        const el = document.getElementById(fieldId);
        if (!el) return;
        const evt = el.tagName === 'SELECT' ? 'change' : 'input';
        el.addEventListener(evt, function () {
            clearFieldError(fieldId);
        });
    });

    if (window.jQuery) {
        $('#role').on('change', function () {
            clearFieldError('role');
        });
    }

    /* ---------- PASSWORD STRENGTH ---------- */
    passwordInput.addEventListener('input', function () {
        const val = this.value;
        const bars = document.querySelectorAll('.password-strength-bar');
        const text = document.getElementById('passwordStrengthText');

        if (!val.length) {
            bars.forEach(b => b.className = 'password-strength-bar');
            text.textContent = '';
            return;
        }

        let strength = 0;
        if (val.length >= 8) strength++;
        if (/[A-Z]/.test(val)) strength++;
        if (/[0-9]/.test(val)) strength++;
        if (/[^A-Za-z0-9]/.test(val)) strength++;

        bars.forEach((bar, idx) => {
            bar.className = 'password-strength-bar';
            if (idx < strength) {
                if (strength <= 1) bar.classList.add('weak');
                else if (strength <= 2) bar.classList.add('medium');
                else bar.classList.add('strong');
            }
        });

        const labels = ['', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];
        text.textContent = `Kekuatan: ${labels[strength]}`;
        text.className = 'password-strength-text ' + (strength <= 1 ? 'text-danger' : strength <= 2 ? 'text-warning' : 'text-success');

        checkPasswordMatch();
    });

    confirmInput.addEventListener('input', checkPasswordMatch);

    function checkPasswordMatch() {
        const matchText = document.getElementById('passwordMatchText');
        if (!confirmInput.value.length) {
            matchText.textContent = '';
            return;
        }
        if (passwordInput.value === confirmInput.value) {
            matchText.textContent = '✓ Password cocok';
            matchText.className = 'password-strength-text text-success';
            clearFieldError('password_confirmation');
        } else {
            matchText.textContent = '✗ Password tidak cocok';
            matchText.className = 'password-strength-text text-danger';
        }
    }

    /* ---------- FORM SUBMIT TANPA SWEETALERT ---------- */
    form.addEventListener('submit', function (e) {
        let isValid = true;
        let firstInvalid = null;

        fields.forEach(clearFieldError);

        const nameEl = document.getElementById('name');
        const emailEl = document.getElementById('email');
        const roleEl = document.getElementById('role');

        const nameVal = nameEl ? nameEl.value.trim() : '';
        if (!nameVal) {
            setFieldError('name', 'Nama lengkap wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = nameEl;
        }

        const emailVal = emailEl ? emailEl.value.trim() : '';
        if (!emailVal) {
            setFieldError('email', 'Email wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = emailEl;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
            setFieldError('email', 'Format email tidak valid.');
            isValid = false;
            if (!firstInvalid) firstInvalid = emailEl;
        }

        if (!roleEl || !roleEl.value) {
            setFieldError('role', 'Role pengguna wajib dipilih.');
            isValid = false;
            if (!firstInvalid) firstInvalid = roleEl;
        }

        const passVal = passwordInput ? passwordInput.value : '';
        const confirmVal = confirmInput ? confirmInput.value : '';

        // Pada Edit: password opsional, jika salah satu diisi, maka keduanya harus valid
        if (passVal.length > 0 || confirmVal.length > 0) {
            if (passVal.length < 8) {
                setFieldError('password', 'Password baru minimal harus 8 karakter.');
                isValid = false;
                if (!firstInvalid) firstInvalid = passwordInput;
            }

            if (passVal !== confirmVal) {
                setFieldError('password_confirmation', 'Konfirmasi password tidak cocok dengan password baru.');
                isValid = false;
                if (!firstInvalid) firstInvalid = confirmInput;
            }
        }

        if (!isValid) {
            e.preventDefault();
            if (firstInvalid) {
                if (window.jQuery && $(firstInvalid).data('select2')) {
                    $(firstInvalid).select2('open');
                } else {
                    firstInvalid.focus();
                }
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
        }

        // Form valid
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> <span>Menyimpan...</span>';
        }
    });

    const initialInvalid = document.querySelector('.is-invalid');
    if (initialInvalid) {
        initialInvalid.focus();
        initialInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
})();

function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
