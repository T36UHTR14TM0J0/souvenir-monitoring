@extends('layouts.app')

@section('title', 'Edit User')

@push('styles')
<style>
    .card-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }
    .card-header-gradient {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
        color: #ffffff;
        padding: 1rem 1.25rem;
    }
    .form-label-custom {
        font-size: 0.76rem;
        font-weight: 700;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 0.35rem;
        display: block;
    }
    .form-label-custom .required {
        color: #ef4444;
    }
    .input-group-custom .input-group-text {
        background-color: #f8fafc;
        border-color: #cbd5e1;
        color: #64748b;
        font-size: 0.85rem;
        width: 40px;
        justify-content: center;
    }
    .input-group-custom .form-control,
    .input-group-custom .form-select {
        height: 40px;
        border-color: #cbd5e1;
        font-size: 0.825rem;
        color: #0f172a;
    }
    .input-group-custom .form-control:focus,
    .input-group-custom .form-select:focus {
        border-color: #26476f;
        box-shadow: 0 0 0 3px rgba(38, 71, 111, 0.12);
    }
    .btn-primary-custom,
    .btn-secondary-custom {
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        font-size: 0.8125rem;
        font-weight: 600;
        padding: 0 1.15rem;
        border-radius: 0.5rem;
        white-space: nowrap;
        text-decoration: none;
        transition: all 0.15s ease;
        border: 1px solid transparent;
    }
    .btn-primary-custom {
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%);
        border-color: #1a304d;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(38, 71, 111, 0.25);
    }
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #1f3a5c 0%, #14243b 100%);
        transform: translateY(-1px);
    }
    .btn-secondary-custom {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        border-color: #475569;
        color: #ffffff !important;
    }
    .btn-secondary-custom:hover {
        background: linear-gradient(135deg, #475569 0%, #334155 100%);
        transform: translateY(-1px);
    }
    .role-guide-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.65rem;
        padding: 0.85rem 1rem;
        background: #f8fafc;
        transition: all 0.15s ease;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-user-edit"
        title="Edit User: {{ $user->name }}"
        subtitle="Perbarui informasi akun pengguna, password, dan hak akses role."
    />

    <div class="row g-3">
        {{-- FORM INPUT KIRI --}}
        <div class="col-12 col-lg-8">
            <div class="card card-modern">
                <div class="card-header-gradient d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-user-pen"></i>
                        <h6 class="mb-0 fw-bold text-white" style="font-size: 0.9rem;">Perbarui Data &amp; Role Pengguna</h6>
                    </div>
                    <span class="badge bg-white text-dark fw-semibold" style="font-size: 0.7rem;">ID #{{ $user->id }}</span>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('users.update', $user) }}" method="POST" id="userForm" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            {{-- Nama --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom" for="name">
                                    Nama Lengkap <span class="required">*</span>
                                </label>
                                <div class="input-group input-group-custom">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name) }}"
                                           placeholder="Masukkan nama lengkap">
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-1"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom" for="email">
                                    Alamat Email <span class="required">*</span>
                                </label>
                                <div class="input-group input-group-custom">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email) }}"
                                           placeholder="nama@email.com">
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Role --}}
                            <div class="col-12">
                                <label class="form-label-custom" for="role">
                                    Peranan / Role Pengguna <span class="required">*</span>
                                </label>
                                <select name="role"
                                        id="role"
                                        class="form-select @error('role') is-invalid @enderror">
                                    <option value="">-- Pilih Role Pengguna --</option>
                                    @foreach($roles as $role)
                                        @php
                                            $meta = \App\Http\Controllers\RoleController::ROLE_META[$role->name] ?? null;
                                        @endphp
                                        <option value="{{ $role->name }}"
                                            {{ old('role', $user->getRoleNames()->first()) === $role->name ? 'selected' : '' }}>
                                            {{ $role->name }}{{ $meta ? ' — ' . $meta['label'] : '' }} ({{ $role->permissions->count() }} Permission)
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="text-danger small mt-1"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Info Password Opsional --}}
                            <div class="col-12 mt-3">
                                <div class="p-2.5 px-3 rounded-3" style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; font-size: 0.78rem;">
                                    <i class="fas fa-circle-info me-1"></i>
                                    <strong>Password bersifat opsional:</strong> Kosongkan kedua kolom password di bawah ini jika Anda tidak ingin mengubah password pengguna.
                                </div>
                            </div>

                            {{-- Password Baru --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom" for="password">
                                    Password Baru (Opsional)
                                </label>
                                <div class="input-group input-group-custom">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Kosongkan jika tidak diubah">
                                    <button type="button" class="btn btn-outline-secondary border-start-0" style="border-color: #cbd5e1;" onclick="togglePassword('password', this)" tabindex="-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Konfirmasi Password Baru --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom" for="password_confirmation">
                                    Konfirmasi Password Baru
                                </label>
                                <div class="input-group input-group-custom">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password"
                                           name="password_confirmation"
                                           id="password_confirmation"
                                           class="form-control"
                                           placeholder="Ulangi password baru">
                                    <button type="button" class="btn btn-outline-secondary border-start-0" style="border-color: #cbd5e1;" onclick="togglePassword('password_confirmation', this)" tabindex="-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Status Aktif --}}
                            <div class="col-12 mt-3">
                                <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">Status Aktif Akun</div>
                                        <div class="small text-muted">Jika dinonaktifkan, pengguna tidak dapat login ke dalam aplikasi.</div>
                                    </div>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} style="width: 2.6rem; height: 1.35rem; cursor: pointer;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary-custom">
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

        {{-- PANDUAN MAPPING ROLE KANAN --}}
        <div class="col-12 col-lg-4">
            <div class="card card-modern">
                <div class="card-header bg-white py-3 px-3 border-bottom d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 30px; height: 30px; background: #eef2ff; color: #26476f;">
                        <i class="fas fa-shield-halved"></i>
                    </span>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.88rem;">Panduan Mapping Hak Akses Role</h6>
                </div>
                <div class="card-body p-3 d-flex flex-column gap-2.5">
                    @foreach(\App\Http\Controllers\RoleController::ROLE_META as $roleName => $meta)
                        <div class="role-guide-card">
                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                <span class="badge px-2.5 py-1 fw-bold" style="background: {{ $meta['badge_bg'] }}; color: {{ $meta['badge_color'] }}; border: 1px solid {{ $meta['badge_border'] }};">
                                    <i class="{{ $meta['icon'] }} me-1"></i>{{ $roleName }}
                                </span>
                                <small class="fw-bold text-secondary" style="font-size: 0.7rem;">{{ $meta['label'] }}</small>
                            </div>
                            <p class="mb-0 text-muted" style="font-size: 0.76rem; line-height: 1.45;">
                                {{ $meta['desc'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
