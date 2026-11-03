@extends('layouts.app')

@section('title', 'Manajemen Permission')

 

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-key"
        title="Manajemen Permission"
        subtitle="Kelola daftar permission yang tersedia di sistem."
    >
        @can('permission-create')
            <button type="button" class="btn btn-primary-custom" onclick="openAddModal()">
                <i class="fas fa-plus"></i>
                <span>Tambah Permission</span>
            </button>
        @endcan
    </x-page-header>

    {{-- FILTER BAR --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('permissions.index') }}" class="row g-2 g-md-3 align-items-center">
            <div class="col-12 col-md-5">
                <div class="form-search-wrapper">
                    <i class="fas fa-search form-search-icon"></i>
                    <input type="text"
                           name="search"
                           class="form-control-modern"
                           placeholder="Cari permission... (contoh: user-create)"
                           value="{{ request('search') }}"
                           autocomplete="off">
                </div>
            </div>

            <div class="col-6 col-md-3">
                <select name="group" class="filter-select">
                    <option value="">Semua Grup</option>
                    @foreach($groups ?? [] as $grp)
                        <option value="{{ $grp }}" {{ request('group') == $grp ? 'selected' : '' }}>
                            {{ strtoupper($grp) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-6 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom flex-fill justify-content-center">
                    <i class="fas fa-filter"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('permissions.index') }}" class="btn-reset" title="Reset">
                    <i class="fas fa-redo"></i>
                </a>
            </div>

            {{-- Stats --}}
            <div class="col-12">
                <div class="filter-stats mt-2">
                    <span class="filter-stat-item">
                        <i class="fas fa-key"></i>
                        Total: <b>{{ $totalPermissions }}</b>
                    </span>
                    <span class="filter-stat-item">
                        <i class="fas fa-folder"></i>
                        Grup: <b>{{ $totalGroups }}</b>
                    </span>
                    @if(request('search') || request('group'))
                        <span class="filter-stat-item is-active">
                            <i class="fas fa-filter"></i>
                            <span>Filter aktif: <b>{{ $permissions->total() }}</b> hasil</span>
                        </span>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- TABLE CARD --}}
    <div class="card card-modern">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 60px;">No</th>
                            <th>Permission</th>
                            <th>Grup</th>
                            <th>Digunakan</th>
                            <th class="text-center col-aksi" style="width: 70px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $index => $perm)
                        @php
                            $groupName = explode('-', $perm->name)[0];
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <span class="cell-muted fw-medium">
                                    {{ $permissions->firstItem() + $index }}
                                </span>
                            </td>
                            <td>
                                <span class="perm-code">
                                    <i class="fas fa-key"></i>
                                    {{ $perm->name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-group">
                                    <i class="fas fa-folder"></i>
                                    {{ strtoupper($groupName) }}
                                </span>
                            </td>
                            <td>
                                @if($perm->roles_count > 0)
                                    <span class="badge-role-count is-used">
                                        <i class="fas fa-shield-alt"></i>
                                        {{ $perm->roles_count }} role
                                    </span>
                                @else
                                    <span class="badge-role-count is-unused">
                                        <i class="fas fa-minus-circle"></i>
                                        Belum dipakai
                                    </span>
                                @endif
                            </td>
                            <td class="text-center col-aksi">
                                @can('permission-delete')
                                    <button type="button"
                                            class="btn-icon btn-icon-danger btn-delete-permission"
                                            data-url="{{ route('permissions.destroy', $perm) }}"
                                            data-name="{{ $perm->name }}"
                                            data-roles="{{ $perm->roles_count }}"
                                            title="Hapus Permission">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <x-empty-state
                                    icon="fas fa-key"
                                    title="Belum Ada Permission"
                                    :message="request('search') || request('group')
                                        ? 'Tidak ada permission yang cocok dengan filter.'
                                        : 'Belum ada permission yang terdaftar di sistem.'"
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if($permissions->hasPages())
            <x-pagination :paginator="$permissions" label="permission" />
        @endif
    </div>
</div>

{{-- Hidden form untuk delete --}}
<form id="deletePermissionForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const SWAL_PRIMARY = '#26476f';
    const SWAL_DANGER  = '#dc2626';
    const SWAL_CANCEL  = '#a8a29e';

    /* ============================================
       OPEN ADD MODAL
       ============================================ */
    window.openAddModal = function () {
        Swal.fire({
            title: 'Tambah Permission Baru',
            html: `
                <div style="text-align: left; padding: 0.25rem 0;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Nama Permission <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text"
                           id="swalPermissionName"
                           placeholder="contoh: produk-export"
                           autocomplete="off"
                           style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.875rem; font-family: 'Inter', sans-serif; box-sizing: border-box; transition: all 0.15s ease;">
                    <div style="display: flex; align-items: center; gap: 0.375rem; font-size: 0.75rem; color: #6b7280; margin-top: 0.5rem;">
                        <i class="fas fa-info-circle" style="font-size: 0.7rem;"></i>
                        <span>Format: <b>modul-aksi</b> (contoh: <code style="background: #f3f4f6; padding: 1px 5px; border-radius: 3px; font-size: 0.7rem;">produk-export</code>)</span>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-1"></i> Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: SWAL_PRIMARY,
            cancelButtonColor: SWAL_CANCEL,
            reverseButtons: true,
            focusConfirm: false,
            didOpen: () => {
                const input = document.getElementById('swalPermissionName');
                if (input) {
                    input.focus();

                    input.addEventListener('focus', () => {
                        input.style.borderColor = '#26476f';
                        input.style.boxShadow = '0 0 0 3px rgba(38, 71, 111, 0.1)';
                    });
                    input.addEventListener('blur', () => {
                        input.style.borderColor = '#e5e7eb';
                        input.style.boxShadow = 'none';
                    });

                    input.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            Swal.clickConfirm();
                        }
                    });
                }
            },
            preConfirm: () => {
                const name = document.getElementById('swalPermissionName').value.trim();

                if (!name) {
                    Swal.showValidationMessage('Nama permission wajib diisi.');
                    return false;
                }

                if (!/^[a-z0-9_-]+$/.test(name)) {
                    Swal.showValidationMessage('Hanya boleh huruf kecil, angka, tanda hubung (-), dan garis bawah (_).');
                    return false;
                }

                if (!name.includes('-')) {
                    Swal.showValidationMessage('Format harus modul-aksi (contoh: produk-export).');
                    return false;
                }

                return name;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('permissions.store') }}';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';

                const nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.name = 'name';
                nameInput.value = result.value;

                form.appendChild(csrfInput);
                form.appendChild(nameInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    };

    /* ============================================
       DELETE PERMISSION
       ============================================ */
    document.querySelectorAll('.btn-delete-permission').forEach(button => {
        button.addEventListener('click', function () {
            const url        = this.dataset.url;
            const name       = this.dataset.name;
            const rolesCount = parseInt(this.dataset.roles) || 0;

            let warningHtml = '';
            if (rolesCount > 0) {
                warningHtml = `
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 0.75rem; margin-top: 0.75rem; text-align: left;">
                        <div style="display: flex; align-items: flex-start; gap: 0.5rem; color: #991b1b; font-size: 0.8rem;">
                            <i class="fas fa-exclamation-triangle" style="margin-top: 1px;"></i>
                            <div>
                                <strong>Perhatian!</strong><br>
                                Permission ini masih digunakan oleh <b>${rolesCount} role</b>.
                                Menghapus permission ini akan mencabut aksesnya dari role terkait.
                            </div>
                        </div>
                    </div>
                `;
            }

            Swal.fire({
                title: 'Hapus Permission?',
                html: `
                    <p style="color: #4b5563; margin-bottom: 0;">
                        Permission <b>"${name}"</b> akan dihapus secara permanen.
                    </p>
                    <p style="font-size: 0.8rem; color: #6b7280; margin-top: 0.5rem;">
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                    ${warningHtml}
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: SWAL_DANGER,
                cancelButtonColor: SWAL_CANCEL,
                reverseButtons: true,
                focusCancel: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deletePermissionForm');
                    form.action = url;
                    form.submit();
                }
            });
        });
    });

})();
</script>
@endpush