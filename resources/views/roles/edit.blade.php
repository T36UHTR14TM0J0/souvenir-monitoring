@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-user-edit"
        title="Edit Role"
        subtitle="Perbarui role <strong>{{ $role->name }}</strong> dan permission-nya."
        iconVariant="warning"
    />

    {{-- FORM CARD --}}
    <div class="card card-modern">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('roles.update', $role) }}" method="POST" id="roleForm" novalidate>
                @csrf
                @method('PUT')

                {{-- ============ SECTION: INFORMASI ROLE ============ --}}
                <div class="form-section-title">
                    <i class="fas fa-tag"></i>
                    Informasi Role
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label-custom" for="name">
                            Nama Role <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-user-tag input-icon"></i>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control form-control-modern @error('name') is-invalid @enderror"
                                   value="{{ old('name', $role->name) }}"
                                   placeholder="Contoh: admin, operator, kasir"
                                   autofocus>
                        </div>
                        <div class="invalid-feedback" id="error_name" @if(!$errors->has('name')) style="display: none !important;" @endif>
                            @error('name'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_name" @if($errors->has('name')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib diisi, gunakan huruf kecil tanpa spasi</span>
                        </div>
                    </div>
                </div>

                {{-- ============ SECTION: PERMISSIONS ============ --}}
                <div class="form-section-title mt-4">
                    <i class="fas fa-shield-alt"></i>
                    Permissions
                </div>

                {{-- Info Box --}}
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Tips:</strong> Anda bisa menambah atau mengurangi permission sesuai kebutuhan role ini.
                        Perubahan akan langsung berlaku setelah disimpan.
                    </div>
                </div>

                <div class="perm-actions">
                    <button type="button" class="perm-action-btn" onclick="selectAllPerms()">
                        <i class="fas fa-check-double"></i>
                        <span>Pilih Semua</span>
                    </button>
                    <button type="button" class="perm-action-btn" onclick="clearAllPerms()">
                        <i class="fas fa-times-circle"></i>
                        <span>Hapus Semua</span>
                    </button>
                </div>

                @php
                    $totalPermissions = collect($permissions)->flatten()->count();
                    $currentPerms     = old('permissions', $role->permissions->pluck('name')->toArray());
                    $initialCount     = count($currentPerms);
                @endphp

                {{-- PERM HEADER BAR --}}
                <div class="perm-header">
                    <div class="perm-header-title">
                        <span class="perm-header-icon">
                            <i class="fas fa-key"></i>
                        </span>
                        <span>Daftar Permission</span>
                    </div>
                    <span class="perm-header-counter" id="selectedCounter">
                        <i class="fas fa-check"></i>
                        <span><b id="selectedCount">{{ $initialCount }}</b> / {{ $totalPermissions }} dipilih</span>
                    </span>
                </div>

                {{-- GRUP PERMISSIONS --}}
                @foreach($permissions as $group => $perms)
                <div class="perm-group-card">
                    <div class="perm-group-header">
                        <div class="perm-group-title">{{ $group }}</div>
                        <span class="badge badge-modern badge-perm-count">
                            {{ $perms->count() }} permission
                        </span>
                    </div>

                    <div class="perm-items-grid">
                        @foreach($perms as $perm)
                        <label class="perm-item-label-wrap">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $perm->name }}"
                                   class="perm-checkbox perm-checkbox-item"
                                   {{ in_array($perm->name, $currentPerms) ? 'checked' : '' }}>
                            <span class="perm-checkbox-custom"></span>
                            <span class="perm-item-label">{{ $perm->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                @error('permissions')
                    <div class="invalid-feedback d-block mt-2">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror

                <hr class="form-divider">

                {{-- ============ ACTIONS ============ --}}
                <div class="form-actions">
                    <a href="{{ route('roles.index') }}" class="btn btn-danger-custom">
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

    const form           = document.getElementById('roleForm');
    const submitBtn      = document.getElementById('submitBtn');
    const nameInput      = document.getElementById('name');
    const errorName      = document.getElementById('error_name');
    const noteName       = document.getElementById('note_name');
    const itemCheckboxes = document.querySelectorAll('.perm-checkbox-item');

    if (!form) return;

    function setFieldError(message) {
        if (nameInput) {
            nameInput.classList.add('is-invalid');
        }
        if (errorName) {
            errorName.textContent = message;
            errorName.style.setProperty('display', 'block', 'important');
        }
        if (noteName) {
            noteName.style.setProperty('display', 'none', 'important');
        }
    }

    function clearFieldError() {
        if (nameInput) {
            nameInput.classList.remove('is-invalid');
        }
        if (errorName) {
            errorName.textContent = '';
            errorName.style.setProperty('display', 'none', 'important');
        }
        if (noteName) {
            noteName.style.removeProperty('display');
        }
    }

    if (nameInput) {
        nameInput.addEventListener('input', clearFieldError);
    }

    function updateCounter() {
        const checked = Array.from(itemCheckboxes).filter(cb => cb.checked).length;
        document.getElementById('selectedCount').textContent = checked;
    }

    window.selectAllPerms = function () {
        itemCheckboxes.forEach(cb => cb.checked = true);
        updateCounter();
    };

    window.clearAllPerms = function () {
        itemCheckboxes.forEach(cb => cb.checked = false);
        updateCounter();
    };

    itemCheckboxes.forEach(cb => cb.addEventListener('change', updateCounter));
    updateCounter();

    /* ---------- FORM SUBMIT TANPA SWEETALERT ---------- */
    form.addEventListener('submit', function (e) {
        clearFieldError();

        const nameVal = nameInput ? nameInput.value.trim() : '';
        if (!nameVal) {
            e.preventDefault();
            setFieldError('Nama role wajib diisi.');
            nameInput.focus();
            nameInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }

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
</script>
@endpush
