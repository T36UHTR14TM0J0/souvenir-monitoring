@extends('layouts.app')

@section('title', 'Master Satuan Produk & Material')

@push('styles')
<style>
    /* MODERN CARD & SHADOW SYSTEM */
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
    .search-input-group .form-control::placeholder {
        color: #94a3b8;
        font-size: 0.8125rem;
    }

    /* THEAD TABLE HEADER NAVY GRADIENT */
    .table-satuan {
        min-width: 920px;
    }
    .table-satuan thead {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
    }
    .table-satuan thead th {
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
    .table-satuan tbody td {
        padding: 0.75rem 0.85rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.8125rem;
        background-color: #ffffff;
    }
    .table-satuan tbody tr:hover td {
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
    .table-satuan thead th.col-freeze-aksi {
        background: linear-gradient(135deg, #1f3a5c 0%, #1a304d 100%) !important;
        z-index: 6;
    }
    .table-satuan tbody td.col-freeze-aksi {
        background-color: #ffffff;
    }
    .table-satuan tbody tr:hover td.col-freeze-aksi {
        background-color: #f8fafc;
    }

    /* ACTION BUTTONS IN ROW PERSIS KATALOG SOUVENIR */
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
        box-shadow: 0 4px 8px rgba(217, 119, 6, 0.3);
    }
    .btn-action-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff !important;
        border-color: #dc2626;
    }
    .btn-action-delete:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
    }

    /* UNIFIED TOOLBAR ACTION BUTTONS */
    .btn-toolbar-action,
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
        box-sizing: border-box;
        cursor: pointer;
        line-height: 1;
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
        box-shadow: 0 4px 10px rgba(38, 71, 111, 0.35);
    }

    .btn-secondary-custom {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        border-color: #475569;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(71, 85, 105, 0.2);
    }
    .btn-secondary-custom:hover {
        background: linear-gradient(135deg, #475569 0%, #334155 100%);
        border-color: #334155;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(71, 85, 105, 0.3);
    }

    .btn-filter-custom {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        border-color: #3730a3;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(79, 70, 229, 0.25);
    }
    .btn-filter-custom:hover {
        background: linear-gradient(135deg, #4338ca 0%, #312e81 100%);
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.35);
    }

    .btn-reset-custom {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-color: #dc2626;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.25);
    }
    .btn-reset-custom:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.35);
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

    /* BADGES & AVATAR PERSIS KATALOG SOUVENIR */
    .badge-kode {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.725rem;
        font-weight: 700;
        padding: 0.2rem 0.5rem;
        border-radius: 0.375rem;
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #e2e8f0;
    }
    .badge-satuan {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        padding: 0.25rem 0.6rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        border: 1px solid #e2e8f0;
    }
    .badge-kategori-custom {
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 600;
        padding: 0.25rem 0.6rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        border: 1px solid #bfdbfe;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .avatar-icon-souvenir {
        width: 36px;
        height: 36px;
        border-radius: 0.5rem;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        color: #4338ca;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
        border: 1px solid #c7d2fe;
    }

    /* MODAL FORM INPUTS */
    .modal-dialog .form-control,
    .modal-dialog .form-select {
        height: 38px;
        font-size: 0.8125rem;
        border-radius: 0.45rem;
        border-color: #cbd5e1;
    }
    .modal-dialog .form-control:focus,
    .modal-dialog .form-select:focus {
        border-color: #26476f;
        box-shadow: 0 0 0 3px rgba(38, 71, 111, 0.12);
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

    {{-- HEADER (SAMA PERSIS KONSEP KATALOG SOUVENIR) --}}
    <x-page-header
        icon="fas fa-ruler-combined"
        title="Satuan Takaran & Kemasan"
        subtitle="Kelola satuan ukuran atau takaran kemasan produk souvenir dan material workshop (pcs, box, lusin, roll, dll)."
    />

    {{-- KPI STATS SUMMARY (4 CARDS) --}}
    <div class="row g-3 mb-3">
        {{-- 1. Total Satuan --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-indigo">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #eef2ff; color: #4f46e5;">
                        <i class="fas fa-ruler-combined"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total Satuan</div>
                        <div class="kpi-value text-dark">
                            {{ number_format($totalSatuan ?? $satuans->total()) }}
                            <small class="text-muted fs-6 fw-normal">Unit</small>
                        </div>
                        <div class="kpi-subtext">Satuan ukur &amp; takaran</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Satuan Aktif --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #059669;">Satuan Aktif</div>
                        <div class="kpi-value text-success">
                            {{ number_format($satuanTerpakai ?? 0) }}
                            <small class="text-muted fs-6 fw-normal">Terpakai</small>
                        </div>
                        <div class="kpi-subtext">Dipakai barang/bahan</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Satuan Kosong --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-warning">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #fffbeb; color: #d97706;">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #d97706;">Satuan Kosong</div>
                        <div class="kpi-value" style="color: #d97706;">
                            {{ number_format($satuanKosong ?? 0) }}
                            <small class="text-muted fs-6 fw-normal">Item</small>
                        </div>
                        <div class="kpi-subtext">Belum ditautkan ke barang</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Total Produk Terdata --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-info">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-cube"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total Produk Terdata</div>
                        <div class="kpi-value text-primary">
                            {{ number_format($totalProduk ?? 0) }}
                            <small class="text-muted fs-6 fw-normal">Unit</small>
                        </div>
                        <div class="kpi-subtext">Memiliki takaran resmi</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH TOOLBAR (STRUKTUR PERSIS KATALOG SOUVENIR) --}}
    @php
        $activeFilterCount = 0;
        if (request()->filled('tipe')) $activeFilterCount++;
        if (request()->filled('status_pakai')) $activeFilterCount++;
        if (request()->filled('sort') && request('sort') !== 'nama_asc') $activeFilterCount++;
    @endphp

    <div class="card card-modern bg-white mb-3">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-stretch align-items-xl-center gap-2.5">
                {{-- Form Pencarian Cepat --}}
                <div class="flex-grow-1">
                    <form method="GET" action="{{ route('satuan.index') }}" class="m-0">
                        @if(request('tipe')) <input type="hidden" name="tipe" value="{{ request('tipe') }}"> @endif
                        @if(request('status_pakai')) <input type="hidden" name="status_pakai" value="{{ request('status_pakai') }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <div class="input-group search-input-group shadow-none">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-3 pe-2">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   class="form-control border-start-0 ps-1"
                                   placeholder="Cari nama satuan, singkatan, atau deskripsi..."
                                   value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('satuan.index', request()->except('search')) }}" class="input-group-text bg-white text-muted text-decoration-none border-start-0 pe-3" title="Hapus Pencarian">
                                    <i class="fas fa-times text-secondary"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Action Buttons: Filter, Reset & Tambah Satuan (Sejajar Samping Filter) --}}
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" id="btnBukaFilterSatuan" class="btn btn-filter-custom position-relative" data-bs-toggle="modal" data-bs-target="#modalFilterSatuan">
                        <i class="fas fa-filter"></i>
                        <span>Filter Data</span>
                        @if($activeFilterCount > 0)
                            <span class="badge bg-white text-dark rounded-pill ms-1" style="font-size: 0.68rem; font-weight: 700; padding: 0.2rem 0.45rem;">{{ $activeFilterCount }}</span>
                        @endif
                    </button>

                    @if(request()->anyFilled(['search', 'tipe', 'status_pakai', 'sort']))
                        <a href="{{ route('satuan.index') }}" class="btn btn-reset-custom" title="Hapus Semua Filter">
                            <i class="fas fa-redo"></i>
                            <span class="d-none d-sm-inline">Reset</span>
                        </a>
                    @endif

                    @can('satuan-create')
                        <a href="{{ route('satuan.create') }}" class="btn btn-primary-custom shadow-sm">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Satuan</span>
                        </a>
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
                            <a href="{{ route('satuan.index', request()->except('search')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('tipe'))
                        <span class="filter-chip">
                            <i class="fas fa-shield-alt me-1 text-primary"></i> Tipe: {{ ucfirst(request('tipe')) }}
                            <a href="{{ route('satuan.index', request()->except('tipe')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('status_pakai'))
                        <span class="filter-chip">
                            <i class="fas fa-box me-1 text-primary"></i> Status: {{ ucfirst(request('status_pakai')) }}
                            <a href="{{ route('satuan.index', request()->except('status_pakai')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('sort') && request('sort') !== 'nama_asc')
                        <span class="filter-chip">
                            <i class="fas fa-arrow-down-short-wide me-1"></i> Urutan: {{ request('sort') }}
                            <a href="{{ route('satuan.index', request()->except('sort')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- DATA TABLE CARD DENGAN HEADER NAVY & ROW AKSI FREEZE --}}
    <div class="card card-modern bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-satuan align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 55px;">No</th>
                            <th style="min-width: 220px;">Informasi Satuan</th>
                            <th class="text-center" style="width: 150px;">Singkatan / Simbol</th>
                            <th style="min-width: 260px;">Deskripsi Takaran</th>
                            <th class="text-center" style="width: 160px;">Jumlah Produk</th>
                            <th class="text-center" style="width: 130px;">Tipe Sistem</th>
                            <th class="text-center pe-4 col-freeze-aksi" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($satuans as $index => $satuan)
                        <tr>
                            {{-- 1. No --}}
                            <td class="ps-4 text-muted font-monospace small">
                                {{ $satuans->firstItem() + $index }}
                            </td>

                            {{-- 2. Informasi Satuan (Avatar + Nama persis Katalog Souvenir) --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-icon-souvenir">
                                        <i class="fas fa-ruler-combined"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 220px;">
                                            {{ $satuan->nama_satuan }}
                                        </div>
                                        <div class="mt-0.5">
                                            <span class="badge-kode">ID #{{ $satuan->id }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- 3. Singkatan / Simbol --}}
                            <td class="text-center">
                                <span class="badge-kode font-monospace">
                                    {{ $satuan->singkatan }}
                                </span>
                            </td>

                            {{-- 4. Deskripsi Takaran --}}
                            <td>
                                <span class="text-muted text-truncate d-inline-block" style="max-width: 340px; font-size: 0.8125rem;" title="{{ $satuan->deskripsi }}">
                                    {{ $satuan->deskripsi ?: '-' }}
                                </span>
                            </td>

                            {{-- 5. Jumlah Produk --}}
                            <td class="text-center">
                                <span class="badge-kategori-custom font-monospace">
                                    <i class="fas fa-box text-primary"></i>
                                    <span>{{ number_format($satuan->produks_count) }} produk</span>
                                </span>
                            </td>

                            {{-- 6. Tipe Sistem --}}
                            <td class="text-center">
                                @if(in_array($satuan->nama_satuan, ['Pieces', 'Pcs']))
                                    <span class="badge-satuan">
                                        <i class="fas fa-shield-alt me-1 text-secondary"></i>Default
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fw-semibold" style="font-size: 0.72rem;">
                                        <i class="fas fa-check-circle me-1"></i>Kustom
                                    </span>
                                @endif
                            </td>

                            {{-- 7. Aksi (Solid gradient persis Katalog Souvenir) --}}
                            <td class="text-center pe-4 col-freeze-aksi">
                                <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                    @can('satuan-edit')
                                        <a href="{{ route('satuan.edit', $satuan) }}"
                                           class="btn-action-icon btn-action-edit"
                                           title="Edit Satuan">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @can('satuan-delete')
                                        @if(!in_array($satuan->nama_satuan, ['Pieces', 'Pcs']))
                                            <button type="button"
                                                    class="btn-action-icon btn-action-delete btn-delete-satuan"
                                                    data-url="{{ route('satuan.destroy', $satuan) }}"
                                                    data-name="{{ $satuan->nama_satuan }} ({{ $satuan->singkatan }})"
                                                    title="Hapus Satuan">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @else
                                            <span class="btn-action-icon bg-light text-muted border" title="Satuan Default Sistem Tidak Dapat Dihapus" style="cursor: not-allowed; opacity: 0.6;">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-0">
                                <x-empty-state
                                    icon="fas fa-ruler-combined"
                                    title="Tidak Ada Satuan Ditemukan"
                                    message="Belum ada data satuan ukuran atau takaran yang sesuai dengan kriteria pencarian atau filter yang Anda pilih."
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if($satuans->hasPages())
            <x-pagination :paginator="$satuans" label="satuan" />
        @endif
    </div>

</div>

{{-- MODAL FILTER SATUAN (SAMA PERSIS DESAIN MODAL FILTER KATALOG SOUVENIR) --}}
<div class="modal fade" id="modalFilterSatuan" tabindex="-1" aria-labelledby="modalFilterSatuanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.875rem;">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-filter fa-lg"></i>
                    <h5 class="modal-title fw-bold fs-6 mb-0 text-white" id="modalFilterSatuanLabel">Filter Data Satuan</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('satuan.index') }}">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="modal-body p-4">
                    <div class="row g-3">
                        {{-- 1. Tipe Satuan --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_tipe">Tipe Sistem</label>
                            <select name="tipe" id="modal_tipe" class="form-select">
                                <option value="">Semua Tipe</option>
                                <option value="kustom" {{ request('tipe') === 'kustom' ? 'selected' : '' }}>Satuan Kustom</option>
                                <option value="default" {{ request('tipe') === 'default' ? 'selected' : '' }}>Satuan Default Sistem</option>
                            </select>
                        </div>

                        {{-- 2. Status Penggunaan --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_status_pakai">Status Penggunaan</label>
                            <select name="status_pakai" id="modal_status_pakai" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="terpakai" {{ request('status_pakai') === 'terpakai' ? 'selected' : '' }}>Aktif Terpakai</option>
                                <option value="kosong" {{ request('status_pakai') === 'kosong' ? 'selected' : '' }}>Satuan Kosong (0 Produk)</option>
                            </select>
                        </div>

                        {{-- 3. Urutkan Berdasarkan --}}
                        <div class="col-12">
                            <label class="form-label" for="modal_sort">Urutkan Data</label>
                            <select name="sort" id="modal_sort" class="form-select">
                                <option value="nama_asc" {{ request('sort', 'nama_asc') === 'nama_asc' ? 'selected' : '' }}>Nama Satuan (A - Z)</option>
                                <option value="nama_desc" {{ request('sort') === 'nama_desc' ? 'selected' : '' }}>Nama Satuan (Z - A)</option>
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru Ditambahkan</option>
                                <option value="produk_desc" {{ request('sort') === 'produk_desc' ? 'selected' : '' }}>Jumlah Produk Terbanyak</option>
                                <option value="produk_asc" {{ request('sort') === 'produk_asc' ? 'selected' : '' }}>Jumlah Produk Paling Sedikit</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top py-3 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('satuan.index', request()->only('search')) }}" class="btn btn-reset-custom">
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
<form id="deleteSatuanForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalFilterEl = document.getElementById('modalFilterSatuan');
    if (modalFilterEl && modalFilterEl.parentElement !== document.body) {
        document.body.appendChild(modalFilterEl);
    }

    const btnBukaFilter = document.getElementById('btnBukaFilterSatuan');
    if (btnBukaFilter && modalFilterEl) {
        btnBukaFilter.addEventListener('click', function(e) {
            e.preventDefault();
            if (window.bootstrap && window.bootstrap.Modal) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalFilterEl);
                modal.show();
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
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (window.bootstrap && window.bootstrap.Modal) {
                    const modal = bootstrap.Modal.getOrCreateInstance(modalFilterEl);
                    modal.hide();
                } else if (window.jQuery && typeof jQuery(modalFilterEl).modal === 'function') {
                    jQuery(modalFilterEl).modal('hide');
                } else {
                    modalFilterEl.classList.remove('show');
                    modalFilterEl.style.display = 'none';
                }
            });
        });
    }

    const deleteButtons = document.querySelectorAll('.btn-delete-satuan');
    const deleteForm = document.getElementById('deleteSatuanForm');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const url  = this.getAttribute('data-url');
            const name = this.getAttribute('data-name');

            Swal.fire({
                title: 'Hapus Satuan?',
                html: `<p style="color: #4b5563; margin-bottom: 8px;">Satuan <b>"${name}"</b> akan dihapus permanen. Satuan yang sudah digunakan pada produk tidak dapat dihapus.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#a8a29e',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteForm.action = url;
                    deleteForm.submit();
                }
            });
        });
    });
});
</script>
@endpush
