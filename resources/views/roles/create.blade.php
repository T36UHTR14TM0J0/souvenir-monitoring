@extends('layouts.app')

@section('title', 'Tambah Role')

 

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-user-tag"
        title="Tambah Role"
        subtitle="Buat role baru dan tentukan permission yang dimilikinya."
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
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="required-note">
                                <i class="fas fa-circle-info"></i>
                                <span>Wajib diisi, gunakan huruf kecil tanpa spasi (contoh: admin)</span>
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- ============ SECTION: PERMISSIONS ============ --}}
                <div class="form-section-title mt-4">
                    <i class="fas fa-shield-alt"></i>
                    Permissions
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
                @endphp

                <div class="perm-header mb-3">
                    <div class="perm-header-title">
                        <span class="perm-header-icon">
                            <i class="fas fa-key"></i>
                        </span>
                        <span>Daftar Permission</span>
                    </div>
                    <span class="perm-header-counter" id="selectedCounter">
                        <i class="fas fa-check"></i>
                        <span><b id="selectedCount">0</b> / {{ $totalPermissions }} dipilih</span>
                    </span>
                </div>

                {{-- PERMISSION GROUPS --}}
                @foreach($permissions as $group => $perms)
                <div class="perm-group" data-group="{{ $group }}">
                    <div class="perm-group-header">
                        <div class="perm-group-left">
                            <input type="checkbox"
                                   class="perm-checkbox check-all"
                                   id="group-{{ $group }}"
                                   data-group="{{ $group }}">
                            <label class="perm-group-name" for="group-{{ $group }}">
                                {{ strtoupper(str_replace('_', ' ', $group)) }}
                            </label>
                        </div>
                        <span class="perm-group-count" data-count-group="{{ $group }}">
                            <i class="fas fa-key"></i>
                            <span class="group-count-value">0</span>
                            <span>/ {{ count($perms) }}</span>
                        </span>
                    </div>

                    <div class="perm-items">
                        @foreach($perms as $perm)
                        <label class="perm-item perm-item-{{ $group }} {{ in_array($perm->name, old('permissions', [])) ? 'is-checked' : '' }}"
                               for="perm-{{ $perm->id }}">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $perm->name }}"
                                   class="perm-checkbox perm-checkbox-item perm-{{ $group }}"
                                   id="perm-{{ $perm->id }}"
                                   data-group="{{ $group }}"
                                   {{ in_array($perm->name, old('permissions', [])) ? 'checked' : '' }}>
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

    const SWAL_PRIMARY = '#26476f';
    const SWAL_CANCEL  = '#a8a29e';

    const form        = document.getElementById('roleForm');
    const submitBtn   = document.getElementById('submitBtn');
    const itemCheckboxes = document.querySelectorAll('.perm-checkbox-item');

    if (!form) return;

    /* ---------- FORM SUBMIT ---------- */
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const nameInput = document.getElementById('name');
        if (!nameInput.value.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Nama Role Kosong',
                text: 'Nama role wajib diisi.',
                confirmButtonText: 'OK',
                confirmButtonColor: SWAL_PRIMARY,
            });
            nameInput.focus();
            return false;
        }

        const total   = itemCheckboxes.length;
        const checked = Array.from(itemCheckboxes).filter(cb => cb.checked).length;
        const permText = checked === 0
            ? '<span style="color: #dc2626;"><i class="fas fa-exclamation-triangle"></i> Tidak ada permission yang dipilih</span>'
            : `<span style="color: #10b981;"><i class="fas fa-check-circle"></i> ${checked} dari ${total} permission dipilih</span>`;

        Swal.fire({
            title: 'Simpan Role?',
            html: `
                <p style="margin-bottom: 0.5rem;">Pastikan data role sudah benar.</p>
                <p style="font-size: 0.8rem; margin: 0.5rem 0 0 0;">
                    ${permText}
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