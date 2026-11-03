@extends('layouts.app')

@section('title', 'Tambah Role')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-user-shield"
        title="Tambah Role"
        subtitle="Buat role baru dan atur permission yang dimiliki."
    />

    {{-- FORM CARD --}}
    <div class="card card-modern">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('roles.store') }}" method="POST" id="roleForm" novalidate>
                @csrf

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
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: admin, operator, kasir"
                                   autofocus>
                        </div>
                        <div class="invalid-feedback" id="error_name" @if(!$errors->has('name')) style="display: none !important;" @endif>
                            @error('name'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_name" @if($errors->has('name')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib diisi, gunakan huruf kecil tanpa spasi (contoh: admin)</span>
                        </div>
                    </div>
                </div>

                {{-- ============ SECTION: PERMISSIONS ============ --}}
                <div class="form-section-title mt-4">
                    <i class="fas fa-shield-alt"></i>
                    Permissions
                </div>

                {{-- BAR: PILIH SEMUA --}}
                <div class="perm-select-all-bar">
                    <label class="perm-checkbox-label">
                        <input type="checkbox"
                               class="perm-checkbox"
                               id="selectAllGlobal">
                        <span class="perm-checkbox-custom"></span>
                        <span class="perm-group-title">Pilih Semua Permission</span>
                    </label>
                    <span class="perm-counter" id="permCounter">
                        0 dari {{ $permissions->flatten()->count() }} dipilih
                    </span>
                </div>

                {{-- GRUP PERMISSIONS --}}
                @foreach($permissions as $group => $perms)
                <div class="perm-group-card">
                    <div class="perm-group-header">
                        <label class="perm-checkbox-label">
                            <input type="checkbox"
                                   class="perm-checkbox perm-select-all-group"
                                   data-group="{{ Str::slug($group) }}">
                            <span class="perm-checkbox-custom"></span>
                            <span class="perm-group-title">{{ $group }}</span>
                        </label>
                        <span class="badge badge-modern badge-perm-count group-counter-badge"
                              id="counter-{{ Str::slug($group) }}">
                            0 / {{ $perms->count() }}
                        </span>
                    </div>

                    <div class="perm-items-grid">
                        @foreach($perms as $perm)
                        <label class="perm-item-label-wrap">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $perm->name }}"
                                   class="perm-checkbox perm-checkbox-item group-{{ Str::slug($group) }}"
                                   data-group="{{ Str::slug($group) }}"
                                   {{ in_array($perm->name, old('permissions', [])) ? 'checked' : '' }}>
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
                        <span>Simpan Role</span>
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
    const selectAllGlob  = document.getElementById('selectAllGlobal');
    const groupCheckboxes= document.querySelectorAll('.perm-select-all-group');
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

    /* ---------- UPDATE COUNTER & STATE ---------- */
    function updateCounters() {
        const total   = itemCheckboxes.length;
        const checked = Array.from(itemCheckboxes).filter(cb => cb.checked).length;

        document.getElementById('permCounter').textContent = `${checked} dari ${total} dipilih`;

        if (selectAllGlob) {
            selectAllGlob.checked       = checked === total && total > 0;
            selectAllGlob.indeterminate = checked > 0 && checked < total;
        }

        groupCheckboxes.forEach(gcb => {
            const group   = gcb.dataset.group;
            const items   = document.querySelectorAll(`.perm-checkbox-item.group-${group}`);
            const gTotal  = items.length;
            const gCheck  = Array.from(items).filter(cb => cb.checked).length;
            const badge   = document.getElementById(`counter-${group}`);

            if (badge) badge.textContent = `${gCheck} / ${gTotal}`;

            gcb.checked       = gCheck === gTotal && gTotal > 0;
            gcb.indeterminate = gCheck > 0 && gCheck < gTotal;
        });
    }

    if (selectAllGlob) {
        selectAllGlob.addEventListener('change', function () {
            itemCheckboxes.forEach(cb => cb.checked = this.checked);
            updateCounters();
        });
    }

    groupCheckboxes.forEach(gcb => {
        gcb.addEventListener('change', function () {
            const group = this.dataset.group;
            document.querySelectorAll(`.perm-checkbox-item.group-${group}`)
                .forEach(cb => cb.checked = this.checked);
            updateCounters();
        });
    });

    itemCheckboxes.forEach(cb => cb.addEventListener('change', updateCounters));
    updateCounters();

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
