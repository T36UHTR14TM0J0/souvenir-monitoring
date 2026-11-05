@extends('layouts.app')

@section('title', 'Stok Bahan Baku')

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
    .table-bahan {
        min-width: 1100px;
    }
    .table-bahan thead {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
    }
    .table-bahan thead th {
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
    .table-bahan tbody td {
        padding: 0.75rem 0.85rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.8125rem;
        background-color: #ffffff;
    }
    .table-bahan tbody tr:hover td {
        background-color: #f8fafc;
    }

    /* FREEZE / STICKY ACTION COLUMN */
    .col-freeze-aksi {
        position: sticky !important;
        right: 0 !important;
        z-index: 5;
        min-width: 140px;
        width: 140px;
        box-shadow: -4px 0 8px -2px rgba(0, 0, 0, 0.08);
    }
    .table-bahan thead th.col-freeze-aksi {
        background: linear-gradient(135deg, #1f3a5c 0%, #1a304d 100%) !important;
        z-index: 6;
    }
    .table-bahan tbody td.col-freeze-aksi {
        background-color: #ffffff;
    }
    .table-bahan tbody tr:hover td.col-freeze-aksi {
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
    .btn-action-view {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff !important;
        border-color: #1d4ed8;
    }
    .btn-action-view:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
    }
    .btn-action-edit {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff !important;
        border-color: #d97706;
    }
    .btn-action-edit:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
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

    /* BADGES PERSIS KATALOG SOUVENIR */
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
        icon="fas fa-layer-group"
        title="Stok Bahan Baku"
        subtitle="Kelola master data bahan mentah, blanko souvenir, perlengkapan packing, dan ketersediaan stok fisik gudang workshop."
    />

    {{-- KPI STATS SUMMARY (4 CARDS PERSIS KATALOG SOUVENIR) --}}
    <div class="row g-3 mb-3">
        {{-- 1. Total Jenis Material --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-indigo">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #eef2ff; color: #4f46e5;">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total Jenis Material</div>
                        <div class="kpi-value text-primary">
                            {{ number_format($totalItemBahan) }}
                            <small class="text-muted fs-6 fw-normal">Jenis</small>
                        </div>
                        <div class="kpi-subtext">{{ number_format($totalStokFisik) }} unit fisik tersedia di gudang</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Nilai Aset Bahan Baku --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-warning">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #fffbeb; color: #d97706;">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Nilai Aset Bahan Baku</div>
                        <div class="kpi-value text-warning">
                            Rp {{ number_format($totalNilaiAset ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="kpi-subtext">Total modal bahan tersimpan di rak</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Total Stok Fisik Gudang --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #059669;">Total Stok Fisik</div>
                        <div class="kpi-value" style="color: #059669;">
                            {{ number_format($totalStokFisik) }}
                            <small class="text-muted fs-6 fw-normal">Unit</small>
                        </div>
                        <div class="kpi-subtext">Siap digunakan tim produksi workshop</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Status Stok & Alert Restock --}}
        <div class="col-12 col-sm-6 col-xl-3">
            @php
                $stokPerluPerhatian = ($totalStokMenipis + $totalStokHabis) > 0;
            @endphp
            <div class="kpi-card-modern {{ $stokPerluPerhatian ? 'kpi-accent-danger' : 'kpi-accent-info' }}">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: {{ $stokPerluPerhatian ? '#fef2f2' : '#f0fdf4' }}; color: {{ $stokPerluPerhatian ? '#dc2626' : '#16a34a' }};">
                        <i class="fas {{ $stokPerluPerhatian ? 'fa-exclamation-triangle' : 'fa-check-circle' }}"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: {{ $stokPerluPerhatian ? '#dc2626' : '#16a34a' }};">
                            Perlu Restock Bahan
                        </div>
                        <div class="kpi-value" style="color: {{ $stokPerluPerhatian ? '#dc2626' : '#16a34a' }};">
                            {{ $totalStokMenipis + $totalStokHabis }}
                            <small class="fs-6 fw-normal">Item</small>
                        </div>
                        <div class="kpi-subtext">
                            {{ $totalStokHabis }} habis &bull; {{ $totalStokMenipis }} menipis
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH TOOLBAR (STRUKTUR PERSIS KATALOG SOUVENIR) --}}
    @php
        $activeFilterCount = 0;
        if (request()->filled('kategori_id')) $activeFilterCount++;
        if (request()->filled('satuan_id')) $activeFilterCount++;
        if (request()->filled('stok_status')) $activeFilterCount++;
        if (request()->filled('sort') && request('sort') !== 'latest') $activeFilterCount++;
    @endphp

    <div class="card card-modern bg-white mb-3">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-stretch align-items-xl-center gap-2.5">
                {{-- Form Pencarian Cepat --}}
                <div class="flex-grow-1">
                    <form method="GET" action="{{ route('bahan-baku.index') }}" class="m-0">
                        @if(request('kategori_id')) <input type="hidden" name="kategori_id" value="{{ request('kategori_id') }}"> @endif
                        @if(request('satuan_id')) <input type="hidden" name="satuan_id" value="{{ request('satuan_id') }}"> @endif
                        @if(request('stok_status')) <input type="hidden" name="stok_status" value="{{ request('stok_status') }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <div class="input-group search-input-group shadow-none">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-3 pe-2">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   class="form-control border-start-0 ps-1"
                                   placeholder="Cari nama bahan baku, kode material..."
                                   value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('bahan-baku.index', request()->except('search')) }}" class="input-group-text bg-white text-muted text-decoration-none border-start-0 pe-3" title="Hapus Pencarian">
                                    <i class="fas fa-times text-secondary"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Action Buttons: Filter, Reset & Tambah Bahan Baku (Sejajar Samping Filter) --}}
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" id="btnBukaFilterBahan" class="btn btn-filter-custom position-relative" data-bs-toggle="modal" data-bs-target="#modalFilterBahan">
                        <i class="fas fa-filter"></i>
                        <span>Filter Data</span>
                        @if($activeFilterCount > 0)
                            <span class="badge bg-white text-dark rounded-pill ms-1" style="font-size: 0.68rem; font-weight: 700; padding: 0.2rem 0.45rem;">{{ $activeFilterCount }}</span>
                        @endif
                    </button>

                    @if(request()->anyFilled(['search', 'kategori_id', 'satuan_id', 'stok_status', 'sort']))
                        <a href="{{ route('bahan-baku.index') }}" class="btn btn-reset-custom" title="Hapus Semua Filter">
                            <i class="fas fa-redo"></i>
                            <span class="d-none d-sm-inline">Reset</span>
                        </a>
                    @endif

                    @can('bahan-baku-create')
                        <a href="{{ route('bahan-baku.create') }}" class="btn btn-primary-custom shadow-sm">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Bahan Baku</span>
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
                            <a href="{{ route('bahan-baku.index', request()->except('search')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('kategori_id'))
                        @php $katSelected = $kategoris->firstWhere('id', request('kategori_id')); @endphp
                        @if($katSelected)
                            <span class="filter-chip">
                                <i class="fas fa-tag me-1 text-primary"></i> Kategori: {{ $katSelected->nama_kategori }}
                                <a href="{{ route('bahan-baku.index', request()->except('kategori_id')) }}"><i class="fas fa-times"></i></a>
                            </span>
                        @endif
                    @endif

                    @if(request('satuan_id'))
                        @php $satSelected = $satuans->firstWhere('id', request('satuan_id')); @endphp
                        @if($satSelected)
                            <span class="filter-chip">
                                <i class="fas fa-ruler me-1 text-primary"></i> Satuan: {{ $satSelected->singkatan }}
                                <a href="{{ route('bahan-baku.index', request()->except('satuan_id')) }}"><i class="fas fa-times"></i></a>
                            </span>
                        @endif
                    @endif

                    @if(request('stok_status'))
                        <span class="filter-chip">
                            <i class="fas fa-boxes me-1"></i> Stok: {{ request('stok_status') === 'menipis' ? 'Menipis' : (request('stok_status') === 'habis' ? 'Habis' : 'Aman') }}
                            <a href="{{ route('bahan-baku.index', request()->except('stok_status')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('sort') && request('sort') !== 'latest')
                        <span class="filter-chip">
                            <i class="fas fa-arrow-down-short-wide me-1"></i> Urutan: {{ request('sort') }}
                            <a href="{{ route('bahan-baku.index', request()->except('sort')) }}"><i class="fas fa-times"></i></a>
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
                <table class="table table-hover table-bahan align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th style="min-width: 280px;">Informasi Bahan Baku</th>
                            <th style="width: 160px;">Kategori</th>
                            <th style="width: 95px;">Satuan</th>
                            <th class="text-end" style="min-width: 170px;">Harga Beli &amp; Valuasi</th>
                            <th class="text-center" style="width: 150px;">Stok Gudang</th>
                            <th class="text-center pe-4 col-freeze-aksi" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bahanBakus as $index => $item)
                        <tr>
                            {{-- 1. No --}}
                            <td class="ps-4 text-muted font-monospace small">
                                {{ $bahanBakus->firstItem() + $index }}
                            </td>

                            {{-- 2. Informasi Bahan Baku --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-icon-souvenir">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="fw-bold text-dark">
                                            <a href="{{ route('bahan-baku.show', $item) }}" class="text-dark text-decoration-none">
                                                {{ $item->nama_produk }}
                                            </a>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                            <span class="badge-kode">{{ $item->kode_produk }}</span>
                                            @if($item->deskripsi)
                                                <small class="text-muted text-truncate d-none d-md-inline" style="max-width: 220px; font-size: 0.72rem;">
                                                    &bull; {{ $item->deskripsi }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- 3. Kategori --}}
                            <td>
                                <span class="badge-kategori-custom">
                                    <i class="fas fa-tag text-primary"></i>
                                    <span>{{ $item->kategori->nama_kategori ?? '-' }}</span>
                                </span>
                            </td>

                            {{-- 4. Satuan --}}
                            <td class="text-center">
                                <span class="badge-satuan">
                                    {{ $item->satuan->singkatan ?? ($item->satuan->nama_satuan ?? 'pcs') }}
                                </span>
                            </td>

                            {{-- 5. Harga Beli & Valuasi --}}
                            <td class="text-end text-nowrap">
                                <div class="fw-bold text-dark font-monospace" style="font-size: 0.875rem;">
                                    Rp {{ number_format($item->harga_beli, 0, ',', '.') }}
                                </div>
                                <div class="text-muted small mt-1 font-monospace" style="font-size: 0.72rem;">
                                    Nilai Stok: Rp {{ number_format($item->stok * $item->harga_beli, 0, ',', '.') }}
                                </div>
                            </td>

                            {{-- 6. Stok Gudang (Persis Stok Etalase Katalog Souvenir) --}}
                            <td class="text-center">
                                @if($item->stok <= 0)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fw-bold" style="font-size: 0.75rem;">
                                        <i class="fas fa-times-circle me-1"></i>Habis (0)
                                    </span>
                                @elseif($item->stok <= $item->stok_minimal)
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 fw-bold" style="font-size: 0.75rem;">
                                        <i class="fas fa-triangle-exclamation me-1"></i>{{ number_format($item->stok) }} {{ $item->satuan->singkatan ?? 'pcs' }}
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fw-semibold" style="font-size: 0.75rem;">
                                        <i class="fas fa-circle-check me-1"></i>{{ number_format($item->stok) }} {{ $item->satuan->singkatan ?? 'pcs' }}
                                    </span>
                                @endif

                                <div class="text-muted mt-1 font-monospace" style="font-size: 0.7rem;">
                                    Min: {{ number_format($item->stok_minimal) }} {{ $item->satuan->singkatan ?? 'pcs' }}
                                </div>
                            </td>

                            {{-- 7. Aksi (Solid gradient persis Katalog Souvenir) --}}
                            <td class="text-center pe-4 col-freeze-aksi">
                                <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                    {{-- Lihat Detail --}}
                                    <a href="{{ route('bahan-baku.show', $item) }}"
                                       class="btn-action-icon btn-action-view"
                                       title="Lihat Detail Bahan Baku">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Edit --}}
                                    @can('bahan-baku-edit')
                                        <a href="{{ route('bahan-baku.edit', $item) }}"
                                           class="btn-action-icon btn-action-edit"
                                           title="Edit Data Bahan Baku">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    @endcan

                                    {{-- Hapus --}}
                                    @can('bahan-baku-delete')
                                        <button type="button"
                                                class="btn-action-icon btn-action-delete btn-delete-bahan"
                                                data-url="{{ route('bahan-baku.destroy', $item) }}"
                                                data-nama="{{ $item->nama_produk }}"
                                                title="Hapus Bahan Baku">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-0">
                                <x-empty-state
                                    icon="fas fa-layer-group"
                                    title="Tidak Ada Bahan Baku Ditemukan"
                                    message="Belum ada data material atau bahan baku yang sesuai dengan kriteria pencarian atau filter yang Anda pilih."
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if($bahanBakus->hasPages())
            <x-pagination :paginator="$bahanBakus" label="bahan baku" />
        @endif
    </div>

</div>

{{-- MODAL FILTER BAHAN BAKU (SAMA PERSIS DESAIN MODAL FILTER KATALOG SOUVENIR) --}}
<div class="modal fade" id="modalFilterBahan" tabindex="-1" aria-labelledby="modalFilterBahanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.875rem;">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-filter fa-lg"></i>
                    <h5 class="modal-title fw-bold fs-6 mb-0 text-white" id="modalFilterBahanLabel">Filter Data Stok Bahan Baku</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('bahan-baku.index') }}">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="modal-body p-4">
                    <div class="row g-3">
                        {{-- 1. Kategori --}}
                        <div class="col-12">
                            <label class="form-label" for="modal_kategori_id">Kategori Material</label>
                            <select name="kategori_id" id="modal_kategori_id" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. Satuan --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_satuan_id">Satuan Ukuran</label>
                            <select name="satuan_id" id="modal_satuan_id" class="form-select">
                                <option value="">Semua Satuan</option>
                                @foreach($satuans as $sat)
                                    <option value="{{ $sat->id }}" {{ request('satuan_id') == $sat->id ? 'selected' : '' }}>
                                        {{ $sat->nama_satuan }} ({{ $sat->singkatan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 3. Status Stok --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_stok_status">Status Ketersediaan</label>
                            <select name="stok_status" id="modal_stok_status" class="form-select">
                                <option value="">Semua Status Stok</option>
                                <option value="aman" {{ request('stok_status') === 'aman' ? 'selected' : '' }}>Stok Aman</option>
                                <option value="menipis" {{ request('stok_status') === 'menipis' ? 'selected' : '' }}>Stok Menipis</option>
                                <option value="habis" {{ request('stok_status') === 'habis' ? 'selected' : '' }}>Stok Habis</option>
                            </select>
                        </div>

                        {{-- 4. Urutkan Berdasarkan --}}
                        <div class="col-12">
                            <label class="form-label" for="modal_sort">Urutkan Data</label>
                            <select name="sort" id="modal_sort" class="form-select">
                                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Terbaru Ditambahkan</option>
                                <option value="nama_asc" {{ request('sort') === 'nama_asc' ? 'selected' : '' }}>Nama Bahan (A &rarr; Z)</option>
                                <option value="nama_desc" {{ request('sort') === 'nama_desc' ? 'selected' : '' }}>Nama Bahan (Z &rarr; A)</option>
                                <option value="stok_desc" {{ request('sort') === 'stok_desc' ? 'selected' : '' }}>Stok Terbanyak</option>
                                <option value="stok_asc" {{ request('sort') === 'stok_asc' ? 'selected' : '' }}>Stok Tersedikit</option>
                                <option value="harga_desc" {{ request('sort') === 'harga_desc' ? 'selected' : '' }}>Harga Beli Tertinggi</option>
                                <option value="harga_asc" {{ request('sort') === 'harga_asc' ? 'selected' : '' }}>Harga Beli Terendah</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top py-2 px-4">
                    <a href="{{ route('bahan-baku.index') }}" class="btn btn-reset-custom">
                        <i class="fas fa-redo"></i> Reset Filter
                    </a>
                    <button type="submit" class="btn btn-filter-custom">
                        <i class="fas fa-check me-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Hidden form untuk delete --}}
<form id="deleteBahanForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ============================================
       DELETE BAHAN BAKU CONFIRMATION
       ============================================ */
    document.querySelectorAll('.btn-delete-bahan').forEach(button => {
        button.addEventListener('click', function () {
            const url  = this.dataset.url;
            const nama = this.dataset.nama;

            Swal.fire({
                title: 'Hapus Bahan Baku?',
                html: `
                    <p style="color: #4b5563; margin-bottom: 0;">
                        Bahan baku <b>"${nama}"</b> akan dihapus dari daftar gudang workshop.
                    </p>
                    <p style="font-size: 0.8rem; color: #dc2626; margin-top: 0.5rem;">
                        <i class="fas fa-exclamation-triangle me-1"></i> Bahan yang sudah memiliki riwayat pembelian atau ditautkan ke resep tidak dapat dihapus.
                    </p>
                `,
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
                    const form = document.getElementById('deleteBahanForm');
                    form.action = url;
                    form.submit();
                }
            });
        });
    });

})();
</script>
@endpush
