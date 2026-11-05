@extends('layouts.app')

@section('title', 'Manajemen Permission')

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
        min-width: 880px;
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
        padding: 0.75rem 0.85rem;
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
        min-width: 110px;
        width: 110px;
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
    .btn-action-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff !important;
        border-color: #dc2626;
    }

    /* UNIFIED TOOLBAR ACTION BUTTONS */
    .btn-primary-custom,
    .btn-secondary-custom,
    .btn-filter-custom,
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
    .btn-secondary-custom {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        border-color: #475569;
        color: #ffffff !important;
    }
    .btn-filter-custom {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        border-color: #3730a3;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(79, 70, 229, 0.25);
    }
    .btn-reset-custom {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-color: #dc2626;
        color: #ffffff !important;
    }

    /* FILTER CHIP PILL */
    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
        padding: 0.25rem 0.6rem;
        border-radius: 2rem;
        font-size: 0.72rem;
        font-weight: 600;
        line-height: 1.2;
    }
    .filter-chip a {
        color: #3b82f6;
        text-decoration: none;
        margin-left: 0.2rem;
    }

    .badge-kode {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.25rem 0.55rem;
        border-radius: 0.375rem;
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .modal-dialog .form-control,
    .modal-dialog .form-select {
        height: 38px;
        font-size: 0.8125rem;
        border-radius: 0.45rem;
        border-color: #cbd5e1;
    }
    .modal-dialog .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.3rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- 1. HEADER (SAMA PERSIS KONSEP KATALOG SOUVENIR - TANPA TOMBOL DI HEADER) --}}
    <x-page-header
        icon="fas fa-key"
        title="Manajemen Permission"
        subtitle="Daftar kode otorisasi per modul fitur dan pemetaan penggunaannya pada Role Pemilik, Bendahara, dan Admin."
    />

    {{-- 2. KPI STATS SUMMARY (4 CARDS - IKON DI KIRI) --}}
    <div class="row g-3 mb-3">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-indigo">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #eef2ff; color: #4f46e5;">
                        <i class="fas fa-key"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total Permission</div>
                        <div class="kpi-value">{{ number_format($totalPermissions ?? 0) }} Akses</div>
                        <div class="kpi-subtext">Kode otorisasi terdaftar</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-info">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Grup Modul Sistem</div>
                        <div class="kpi-value text-primary">{{ number_format($totalGroups ?? 0) }} Modul</div>
                        <div class="kpi-subtext">Kelompok fitur aplikasi</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                        <i class="fas fa-shield-check"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #059669;">Digunakan Role</div>
                        <div class="kpi-value text-success">{{ number_format($totalUsedPermissions ?? 0) }} Aktif</div>
                        <div class="kpi-subtext">Terhubung ke Pemilik/Bendahara/Admin</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-warning">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #fffbeb; color: #d97706;">
                        <i class="fas fa-circle-Exclamation"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #d97706;">Belum Digunakan</div>
                        <div class="kpi-value" style="color: #d97706;">{{ number_format($totalUnusedPermissions ?? 0) }} Kode</div>
                        <div class="kpi-subtext">Permission tanpa role</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. FILTER & SEARCH TOOLBAR (STRUKTUR PERSIS KATALOG SOUVENIR) --}}
    @php
        $activeFilterCount = 0;
        if (request()->filled('group')) $activeFilterCount++;
        if (request()->filled('status')) $activeFilterCount++;
        if (request()->filled('sort') && request('sort') !== 'name_asc') $activeFilterCount++;
    @endphp

    <div class="card card-modern bg-white mb-3">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-stretch align-items-xl-center gap-2.5">
                <div class="flex-grow-1">
                    <form method="GET" action="{{ route('permissions.index') }}" class="m-0">
                        @if(request('group')) <input type="hidden" name="group" value="{{ request('group') }}"> @endif
                        @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <div class="input-group search-input-group shadow-none">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-3 pe-2">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   class="form-control border-start-0 ps-1"
                                   placeholder="Cari kode permission (contoh: penjualan-list, kas-masuk-create, laporan-export)..."
                                   value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('permissions.index', request()->except('search')) }}"
                                   class="input-group-text bg-white text-muted text-decoration-none border-start-0 pe-3"
                                   title="Hapus Pencarian">
                                    <i class="fas fa-times text-secondary"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" id="btnBukaFilterPermission" class="btn btn-filter-custom position-relative" data-bs-toggle="modal" data-bs-target="#modalFilterPermission">
                        <i class="fas fa-filter"></i>
                        <span>Filter Data</span>
                        @if($activeFilterCount > 0)
                            <span class="badge bg-white text-dark rounded-pill ms-1" style="font-size: 0.68rem; font-weight: 700; padding: 0.2rem 0.45rem;">{{ $activeFilterCount }}</span>
                        @endif
                    </button>

                    @if(request()->anyFilled(['search', 'group', 'status', 'sort']))
                        <a href="{{ route('permissions.index') }}" class="btn btn-reset-custom" title="Hapus Semua Filter">
                            <i class="fas fa-redo"></i>
                            <span class="d-none d-sm-inline">Reset</span>
                        </a>
                    @endif

                    @can('permission-create')
                        <button type="button" class="btn btn-primary-custom shadow-sm" onclick="openAddModal()">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Permission</span>
                        </button>
                    @endcan
                </div>
            </div>

            {{-- ACTIVE FILTER CHIPS --}}
            @if($activeFilterCount > 0 || request('search'))
                <div class="d-flex flex-wrap align-items-center gap-2 mt-2.5 pt-2.5 border-top">
                    <span class="small text-muted fw-bold me-1" style="font-size: 0.72rem;">Filter Aktif:</span>

                    @if(request('search'))
                        <span class="filter-chip">
                            <i class="fas fa-search me-1"></i> "{{ request('search') }}"
                            <a href="{{ route('permissions.index', request()->except('search')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('group'))
                        @php $grpLabel = $moduleMeta[request('group')]['label'] ?? strtoupper(request('group')); @endphp
                        <span class="filter-chip">
                            <i class="fas fa-folder me-1 text-primary"></i> Modul: {{ $grpLabel }}
                            <a href="{{ route('permissions.index', request()->except('group')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('status'))
                        <span class="filter-chip">
                            <i class="fas fa-shield-halved me-1 text-primary"></i> Status: {{ request('status') === 'used' ? 'Digunakan Role' : 'Belum Digunakan' }}
                            <a href="{{ route('permissions.index', request()->except('status')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif
                </div>
            @endif
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
                            <th style="min-width: 220px;">Kode Permission</th>
                            <th style="min-width: 240px;">Keterangan Hak Akses</th>
                            <th style="min-width: 220px;">Menu / Modul Aplikasi</th>
                            <th style="min-width: 240px;">Dimiliki Oleh Role</th>
                            <th class="text-center pe-4 col-freeze-aksi" style="width: 110px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $index => $perm)
                        @php
                            $groupKey = \App\Http\Controllers\RoleController::extractModuleGroup($perm->name);
                            $mInfo = $moduleMeta[$groupKey] ?? ['label' => strtoupper($groupKey), 'icon' => 'fas fa-folder', 'section' => 'Modul'];
                            $pDesc = $permLabels[$perm->name] ?? ucwords(str_replace('-', ' ', $perm->name));
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted font-monospace small">
                                {{ $permissions->firstItem() + $index }}
                            </td>

                            <td>
                                <span class="badge-kode">
                                    <i class="fas fa-key"></i>
                                    <span>{{ $perm->name }}</span>
                                </span>
                            </td>

                            <td>
                                <div class="fw-semibold text-dark" style="font-size: 0.8125rem;">{{ $pDesc }}</div>
                            </td>

                            <td>
                                <div class="d-flex flex-column gap-1 align-items-start">
                                    <span class="badge bg-light text-dark border px-2.5 py-1 fw-semibold" style="font-size: 0.74rem;">
                                        <i class="{{ $mInfo['icon'] }} text-primary me-1"></i>{{ $mInfo['label'] }}
                                    </span>
                                    @if(!empty($mInfo['section']))
                                        <span class="text-muted" style="font-size: 0.68rem;">Grup Sidebar: {{ $mInfo['section'] }}</span>
                                    @endif
                                </div>
                            </td>

                            <td>
                                @if($perm->roles->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($perm->roles as $r)
                                            @php
                                                $rm = \App\Http\Controllers\RoleController::ROLE_META[$r->name] ?? [
                                                    'badge_bg' => '#f8fafc',
                                                    'badge_color' => '#334155',
                                                    'badge_border' => '#cbd5e1',
                                                    'icon' => 'fas fa-user-tag',
                                                ];
                                            @endphp
                                            <span class="badge px-2 py-1 fw-semibold"
                                                  style="background: {{ $rm['badge_bg'] }}; color: {{ $rm['badge_color'] }}; border: 1px solid {{ $rm['badge_border'] }}; font-size: 0.72rem;">
                                                <i class="{{ $rm['icon'] }} me-1"></i>{{ $r->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border">Belum Dipakai</span>
                                @endif
                            </td>

                            <td class="text-center pe-4 col-freeze-aksi">
                                <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                    @can('permission-edit')
                                        <button type="button"
                                                class="btn-action-icon btn-action-edit"
                                                onclick="openEditModal('{{ route('permissions.update', $perm) }}', '{{ $perm->name }}')"
                                                title="Edit Permission">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endcan

                                    @can('permission-delete')
                                        <button type="button"
                                                class="btn-action-icon btn-action-delete btn-delete-permission"
                                                data-url="{{ route('permissions.destroy', $perm) }}"
                                                data-name="{{ $perm->name }}"
                                                data-roles="{{ $perm->roles_count }}"
                                                title="Hapus Permission">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-0">
                                <x-empty-state
                                    icon="fas fa-key"
                                    title="Tidak Ada Permission"
                                    message="Tidak ada kode permission yang cocok dengan filter atau pencarian Anda."
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($permissions->hasPages())
            <x-pagination :paginator="$permissions" label="permission" />
        @endif
    </div>
</div>

{{-- MODAL FILTER PERMISSION --}}
<div class="modal fade" id="modalFilterPermission" tabindex="-1" aria-labelledby="modalFilterPermissionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.875rem;">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-filter fa-lg"></i>
                    <h5 class="modal-title fw-bold fs-6 mb-0 text-white" id="modalFilterPermissionLabel">Filter Manajemen Permission</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('permissions.index') }}">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="modal_group">Grup Modul Aplikasi</label>
                            <select name="group" id="modal_group" class="form-select">
                                <option value="">Semua Grup Modul</option>
                                @foreach($groups ?? [] as $grp)
                                    <option value="{{ $grp }}" {{ request('group') === $grp ? 'selected' : '' }}>
                                        {{ $moduleMeta[$grp]['label'] ?? strtoupper($grp) }} ({{ $grp }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_status">Status Penggunaan</label>
                            <select name="status" id="modal_status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Digunakan oleh Role</option>
                                <option value="unused" {{ request('status') === 'unused' ? 'selected' : '' }}>Belum Digunakan</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_sort">Urutkan Data</label>
                            <select name="sort" id="modal_sort" class="form-select">
                                <option value="name_asc" {{ request('sort', 'name_asc') === 'name_asc' ? 'selected' : '' }}>Nama (A – Z)</option>
                                <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Nama (Z – A)</option>
                                <option value="roles_desc" {{ request('sort') === 'roles_desc' ? 'selected' : '' }}>Paling Banyak Role</option>
                                <option value="roles_asc" {{ request('sort') === 'roles_asc' ? 'selected' : '' }}>Paling Sedikit Role</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top py-3 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('permissions.index', request()->only('search')) }}" class="btn btn-reset-custom">
                        <i class="fas fa-redo"></i>
                        <span>Reset Filter</span>
                    </a>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-check"></i>
                            <span>Terapkan Filter</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
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
document.addEventListener('DOMContentLoaded', function () {
    const modalFilterEl = document.getElementById('modalFilterPermission');
    if (modalFilterEl && modalFilterEl.parentElement !== document.body) {
        document.body.appendChild(modalFilterEl);
    }

    const btnBukaFilter = document.getElementById('btnBukaFilterPermission');
    if (btnBukaFilter && modalFilterEl) {
        btnBukaFilter.addEventListener('click', function (e) {
            e.preventDefault();
            if (window.bootstrap && window.bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(modalFilterEl).show();
            } else if (window.jQuery && typeof jQuery(modalFilterEl).modal === 'function') {
                jQuery(modalFilterEl).modal('show');
            } else {
                modalFilterEl.classList.add('show');
                modalFilterEl.style.display = 'block';
            }
        });
    }

    if (modalFilterEl) {
        modalFilterEl.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                if (window.bootstrap && window.bootstrap.Modal) {
                    bootstrap.Modal.getOrCreateInstance(modalFilterEl).hide();
                } else if (window.jQuery && typeof jQuery(modalFilterEl).modal === 'function') {
                    jQuery(modalFilterEl).modal('hide');
                } else {
                    modalFilterEl.classList.remove('show');
                    modalFilterEl.style.display = 'none';
                }
            });
        });
    }

    const SWAL_PRIMARY = '#26476f';
    const SWAL_DANGER  = '#dc2626';
    const SWAL_CANCEL  = '#64748b';

    window.openAddModal = function () {
        Swal.fire({
            title: 'Tambah Permission Baru',
            html: `
                <div style="text-align: left; padding: 0.25rem 0;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 0.4rem;">
                        Kode Permission <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text"
                           id="swalPermissionName"
                           placeholder="contoh: produk-export"
                           autocomplete="off"
                           style="width: 100%; padding: 0.55rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; font-size: 0.85rem;">
                    <div style="font-size: 0.74rem; color: #64748b; margin-top: 0.45rem;">
                        <i class="fas fa-circle-info me-1"></i>Format: <b>modul-aksi</b> (contoh: <code>produk-export</code>)
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-1"></i> Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: SWAL_PRIMARY,
            cancelButtonColor: SWAL_CANCEL,
            reverseButtons: true,
            preConfirm: () => {
                const name = document.getElementById('swalPermissionName').value.trim();
                if (!name) {
                    Swal.showValidationMessage('Nama permission wajib diisi.');
                    return false;
                }
                if (!/^[a-z0-9_-]+$/.test(name) || !name.includes('-')) {
                    Swal.showValidationMessage('Format harus modul-aksi huruf kecil (contoh: produk-export).');
                    return false;
                }
                return name;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('permissions.store') }}';
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="name" value="${result.value}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    };

    window.openEditModal = function (updateUrl, currentName) {
        Swal.fire({
            title: 'Edit Permission',
            html: `
                <div style="text-align: left; padding: 0.25rem 0;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 0.4rem;">
                        Kode Permission <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text"
                           id="swalEditPermissionName"
                           value="${currentName}"
                           autocomplete="off"
                           style="width: 100%; padding: 0.55rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; font-size: 0.85rem;">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-1"></i> Perbarui',
            cancelButtonText: 'Batal',
            confirmButtonColor: SWAL_PRIMARY,
            cancelButtonColor: SWAL_CANCEL,
            reverseButtons: true,
            preConfirm: () => {
                const name = document.getElementById('swalEditPermissionName').value.trim();
                if (!name || !/^[a-z0-9_-]+$/.test(name) || !name.includes('-')) {
                    Swal.showValidationMessage('Format harus modul-aksi huruf kecil (contoh: produk-export).');
                    return false;
                }
                return name;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = updateUrl;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="name" value="${result.value}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    };

    document.querySelectorAll('.btn-delete-permission').forEach(button => {
        button.addEventListener('click', function () {
            const url        = this.dataset.url;
            const name       = this.dataset.name;
            const rolesCount = parseInt(this.dataset.roles) || 0;

            Swal.fire({
                title: 'Hapus Permission?',
                html: `<p style="color: #4b5563; margin-bottom: 0;">Permission <b>"${name}"</b> akan dihapus permanen.</p>`,
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
});
</script>
@endpush
