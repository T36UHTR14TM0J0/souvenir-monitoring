@extends('layouts.app')

@section('title', 'Manajemen Role & Hak Akses')

@push('styles')
<style>
    /* MODERN CARD & SHADOW SYSTEM (KONSISTEN KATALOG SOUVENIR) */
    .card-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    /* KPI STATS CARDS */
    .kpi-card-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 1.1rem 1.25rem;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .kpi-card-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
    }
    .kpi-accent-indigo { border-left: 3.5px solid #4f46e5; }
    .kpi-accent-danger { border-left: 3.5px solid #ef4444; }
    .kpi-accent-success { border-left: 3.5px solid #10b981; }
    .kpi-accent-info { border-left: 3.5px solid #0284c7; }
    .kpi-accent-warning { border-left: 3.5px solid #f59e0b; }

    .kpi-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }
    .kpi-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 0.2rem;
        white-space: normal;
        line-height: 1.35;
    }
    .kpi-value {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.25;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }
    .kpi-subtext {
        font-size: 0.72rem;
        color: #94a3b8;
        margin-top: 0.2rem;
        font-weight: 500;
        white-space: normal;
        line-height: 1.35;
    }

    /* SEARCH TOOLBAR INPUT GROUP */
    .search-input-group {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .search-input-group .input-group-text {
        height: 38px;
        background-color: #ffffff;
        border-color: #cbd5e1;
        color: #64748b;
        font-size: 0.8125rem;
    }
    .search-input-group .form-control {
        height: 38px;
        background-color: #ffffff;
        border-color: #cbd5e1;
        font-size: 0.8125rem;
        color: #1e293b;
    }
    .search-input-group .form-control:focus {
        background-color: #ffffff;
        border-color: #26476f;
        box-shadow: 0 0 0 3px rgba(38, 71, 111, 0.12);
    }

    /* THEAD TABLE HEADER NAVY GRADIENT */
    .table-produk {
        width: 100%;
        min-width: 960px;
        margin-bottom: 0;
        border-collapse: collapse;
    }
    .table-produk thead {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
    }
    .table-produk thead th {
        background-color: transparent !important;
        color: #ffffff !important;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: none !important;
        padding: 0.75rem 0.85rem;
        white-space: nowrap;
    }
    .table-produk tbody td {
        padding: 0.85rem 0.85rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.8125rem;
        background-color: #ffffff;
        color: #334155;
    }
    .table-produk tbody tr:hover td {
        background-color: #f8fafc;
    }

    /* FREEZE / STICKY ACTION COLUMN */
    .col-freeze-aksi {
        position: sticky !important;
        right: 0 !important;
        z-index: 5;
        min-width: 120px;
        width: 120px;
        box-shadow: -4px 0 8px -2px rgba(0, 0, 0, 0.08);
    }
    .table-produk thead th.col-freeze-aksi {
        background: linear-gradient(135deg, #1f3a5c 0%, #1a304d 100%) !important;
        z-index: 6;
    }
    .table-produk tbody td.col-freeze-aksi {
        background-color: #ffffff;
    }
    .table-produk tbody tr:hover td.col-freeze-aksi {
        background-color: #f8fafc;
    }

    /* ACTION BUTTONS IN ROW */
    .btn-action-icon {
        width: 30px;
        height: 30px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.45rem;
        border: 1px solid transparent;
        font-size: 0.78rem;
        transition: all 0.15s ease;
        text-decoration: none;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
    }
    .btn-action-edit {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff !important;
        border-color: #d97706;
    }
    .btn-action-edit:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        transform: translateY(-1px);
    }
    .btn-action-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff !important;
        border-color: #dc2626;
    }
    .btn-action-delete:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-1px);
    }

    /* UNIFIED TOOLBAR ACTION BUTTONS */
    .btn-primary-custom,
    .btn-reset-custom {
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        font-size: 0.8125rem;
        font-weight: 600;
        padding: 0 1rem;
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
        color: #ffffff !important;
        transform: translateY(-1px);
    }
    .btn-reset-custom {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-color: #dc2626;
        color: #ffffff !important;
    }

    .perm-module-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: #f8fafc;
        color: #334155;
        border: 1px solid #cbd5e1;
        padding: 0.2rem 0.55rem;
        border-radius: 0.375rem;
        font-size: 0.72rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- 1. HEADER (SAMA PERSIS KONSEP KATALOG SOUVENIR - TANPA TOMBOL DI HEADER) --}}
    <x-page-header
        icon="fas fa-user-shield"
        title="Manajemen Role & Hak Akses"
        subtitle="Pengaturan mapping otoritas akses aplikasi untuk Pemilik, Bendahara, dan Admin."
    />

    {{-- 2. KPI STATS SUMMARY (4 CARDS - IKON DI KIRI) --}}
    <div class="row g-3 mb-3">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-indigo">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #eef2ff; color: #4f46e5;">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total Role Sistem</div>
                        <div class="kpi-value">{{ number_format($totalRoles ?? $roles->total()) }} Role</div>
                        <div class="kpi-subtext">Kelompok hak akses terdaftar</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #059669;">Role Utama Terlindungi</div>
                        <div class="kpi-value text-success">{{ number_format($totalProtectedRoles ?? 3) }} Role Inti</div>
                        <div class="kpi-subtext">Pemilik, Bendahara &amp; Admin</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-info">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-key"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total Permission</div>
                        <div class="kpi-value text-primary">{{ number_format($totalPermissions ?? 0) }} Akses</div>
                        <div class="kpi-subtext">Tersebar di {{ count($moduleMeta ?? []) }} modul aplikasi</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-warning">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #fffbeb; color: #d97706;">
                        <i class="fas fa-users-gear"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #d97706;">User Terhubung Role</div>
                        <div class="kpi-value" style="color: #d97706;">{{ number_format($totalAssignedUsers ?? 0) }} User</div>
                        <div class="kpi-subtext">Memiliki otoritas aktif</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. FILTER & SEARCH TOOLBAR (STRUKTUR PERSIS KATALOG SOUVENIR) --}}
    <div class="card card-modern bg-white mb-3">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-stretch align-items-xl-center gap-2.5">
                <div class="flex-grow-1">
                    <form method="GET" action="{{ route('roles.index') }}" class="m-0">
                        <div class="input-group search-input-group shadow-none">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-3 pe-2">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   class="form-control border-start-0 ps-1"
                                   placeholder="Cari nama role (Pemilik, Bendahara, Admin)..."
                                   value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('roles.index') }}"
                                   class="input-group-text bg-white text-muted text-decoration-none border-start-0 pe-3"
                                   title="Hapus Pencarian">
                                    <i class="fas fa-times text-secondary"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @if(request()->filled('search'))
                        <a href="{{ route('roles.index') }}" class="btn btn-reset-custom" title="Reset Pencarian">
                            <i class="fas fa-redo"></i>
                            <span>Reset</span>
                        </a>
                    @endif

                    @can('role-create')
                        <a href="{{ route('roles.create') }}" class="btn btn-primary-custom shadow-sm">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Role</span>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    {{-- 4. TABLE CARD --}}
    <div class="card card-modern bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-produk align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 55px;">No</th>
                            <th style="min-width: 240px;">Role &amp; Tanggung Jawab</th>
                            <th style="width: 150px;">Statistik Akses</th>
                            <th style="min-width: 380px;">Cakupan Modul &amp; Permission</th>
                            <th class="text-center pe-4 col-freeze-aksi" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $index => $role)
                        @php
                            $meta = $roleMeta[$role->name] ?? [
                                'badge_bg' => '#f8fafc',
                                'badge_color' => '#334155',
                                'badge_border' => '#cbd5e1',
                                'icon' => 'fas fa-user-tag',
                                'label' => 'Role Tambahan',
                                'desc' => 'Role kustom yang diatur sesuai kebutuhan operasional.',
                            ];
                            $isProtected = in_array($role->name, \App\Http\Controllers\RoleController::PROTECTED_ROLES, true);
                            $groupedPerms = $role->permissions->groupBy(fn ($p) => \App\Http\Controllers\RoleController::extractModuleGroup($p->name));
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted font-monospace small">
                                {{ $roles->firstItem() + $index }}
                            </td>

                            <td>
                                <div class="d-flex align-items-start gap-3">
                                    <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0 mt-0.5"
                                         style="width: 40px; height: 40px; background: {{ $meta['badge_bg'] }}; color: {{ $meta['badge_color'] }}; border: 1px solid {{ $meta['badge_border'] }}; font-size: 1rem;">
                                        <i class="{{ $meta['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                            <span class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $role->name }}</span>
                                            @if($isProtected)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.65rem;">
                                                    <i class="fas fa-lock me-1"></i>Default Sistem
                                                </span>
                                            @endif
                                        </div>
                                        <div class="fw-semibold mt-0.5" style="font-size: 0.74rem; color: {{ $meta['badge_color'] }};">
                                            {{ $meta['label'] }}
                                        </div>
                                        <div class="text-muted mt-1" style="font-size: 0.74rem; max-width: 340px; line-height: 1.4;">
                                            {{ $meta['desc'] }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column gap-1.5">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle align-self-start px-2.5 py-1">
                                        <i class="fas fa-users me-1"></i>{{ $role->users_count }} User
                                    </span>
                                    <span class="badge bg-indigo-subtle text-dark border align-self-start px-2.5 py-1" style="background: #eef2ff; color: #4f46e5; border-color: #c7d2fe !important;">
                                        <i class="fas fa-key me-1"></i>{{ $role->permissions->count() }} Permission
                                    </span>
                                </div>
                            </td>

                            <td>
                                @if($groupedPerms->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-1.5">
                                        @foreach($groupedPerms as $modKey => $modPerms)
                                            @php
                                                $mInfo = $moduleMeta[$modKey] ?? ['label' => ucfirst($modKey), 'icon' => 'fas fa-folder'];
                                            @endphp
                                            <span class="perm-module-pill" title="{{ $modPerms->pluck('name')->join(', ') }}">
                                                <i class="{{ $mInfo['icon'] }} text-primary"></i>
                                                <span>{{ $mInfo['label'] }}</span>
                                                <span class="badge bg-primary text-white rounded-pill" style="font-size: 0.63rem;">{{ $modPerms->count() }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted fst-italic small">Belum ada permission yang ditetapkan</span>
                                @endif
                            </td>

                            <td class="text-center pe-4 col-freeze-aksi">
                                <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                    @can('role-edit')
                                        <a href="{{ route('roles.edit', $role) }}"
                                           class="btn-action-icon btn-action-edit"
                                           title="Atur Permission Role {{ $role->name }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @can('role-delete')
                                        @if(!$isProtected)
                                            <button type="button"
                                                    class="btn-action-icon btn-action-delete btn-delete-role"
                                                    data-url="{{ route('roles.destroy', $role) }}"
                                                    data-name="{{ $role->name }}"
                                                    data-users="{{ $role->users_count }}"
                                                    title="Hapus Role">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-0">
                                <x-empty-state
                                    icon="fas fa-user-shield"
                                    title="Belum Ada Role"
                                    message="Belum ada role yang sesuai dengan pencarian Anda."
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($roles->hasPages())
            <x-pagination :paginator="$roles" label="role" />
        @endif
    </div>
</div>

{{-- Hidden form untuk delete --}}
<form id="deleteRoleForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    document.querySelectorAll('.btn-delete-role').forEach(button => {
        button.addEventListener('click', function () {
            const url        = this.dataset.url;
            const name       = this.dataset.name;
            const usersCount = parseInt(this.dataset.users) || 0;

            let warningHtml = '';
            if (usersCount > 0) {
                warningHtml = `
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 0.75rem; margin-top: 0.75rem; text-align: left;">
                        <div style="display: flex; align-items: flex-start; gap: 0.5rem; color: #991b1b; font-size: 0.8rem;">
                            <i class="fas fa-exclamation-triangle" style="margin-top: 1px;"></i>
                            <div>
                                <strong>Perhatian!</strong><br>
                                Role ini masih digunakan oleh <b>${usersCount} user</b>.
                            </div>
                        </div>
                    </div>
                `;
            }

            Swal.fire({
                title: 'Hapus Role?',
                html: `
                    <p style="color: #4b5563; margin-bottom: 0;">
                        Role <b>"${name}"</b> akan dihapus secara permanen.
                    </p>
                    ${warningHtml}
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
                focusCancel: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteRoleForm');
                    form.action = url;
                    form.submit();
                }
            });
        });
    });
})();
</script>
@endpush
