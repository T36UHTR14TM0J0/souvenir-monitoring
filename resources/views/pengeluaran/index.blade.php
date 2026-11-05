@extends('layouts.app')

@section('title', 'Biaya Operasional & Ads')

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
    .table-pengeluaran {
        min-width: 1020px;
    }
    .table-pengeluaran thead {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
    }
    .table-pengeluaran thead th {
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
    .table-pengeluaran tbody td {
        padding: 0.75rem 0.85rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.8125rem;
        background-color: #ffffff;
    }
    .table-pengeluaran tbody tr:hover td {
        background-color: #f8fafc;
    }

    /* FREEZE / STICKY ACTION COLUMN */
    .col-freeze-aksi {
        position: sticky !important;
        right: 0 !important;
        z-index: 5;
        min-width: 145px;
        width: 145px;
        box-shadow: -4px 0 8px -2px rgba(0, 0, 0, 0.08);
    }
    .table-pengeluaran thead th.col-freeze-aksi {
        background: linear-gradient(135deg, #1f3a5c 0%, #1a304d 100%) !important;
        z-index: 6;
    }
    .table-pengeluaran tbody td.col-freeze-aksi {
        background-color: #ffffff;
    }
    .table-pengeluaran tbody tr:hover td.col-freeze-aksi {
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
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: #ffffff !important;
        border-color: #0284c7;
    }
    .btn-action-view:hover {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(2, 132, 199, 0.3);
    }
    .btn-action-bukti {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #ffffff !important;
        border-color: #4f46e5;
    }
    .btn-action-bukti:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(79, 70, 229, 0.3);
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
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
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
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        color: #dc2626;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
        border: 1px solid #fecaca;
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
        icon="fas fa-credit-card"
        title="Biaya Operasional & Ads"
        subtitle="Kelola pengeluaran operasional workshop, biaya iklan marketplace (Ads), prive pemilik, dan kasbon tim."
    />

    {{-- KPI STATS SUMMARY (4 CARDS SESUAI BUKU KEUANGAN) --}}
    <div class="row g-3 mb-3">
        {{-- 1. Modal Bahan Baku & Produksi (Bulan Ini) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-indigo">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #eef2ff; color: #4f46e5;">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Modal Bahan &amp; Material</div>
                        <div class="kpi-value text-dark">
                            Rp {{ number_format($totalModalBahanBulanIni ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="kpi-subtext">Akrilik, kain, silikon, cetak luar</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Operasional & Ads Toko (Bulan Ini) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #10b981;">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #047857;">Operasional &amp; Iklan Toko</div>
                        <div class="kpi-value" style="color: #047857;">
                            Rp {{ number_format($totalOperasionalBulanIni ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="kpi-subtext">Gaji, ads, packing, makan, bensin</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Kasbon & Prive (Bulan Ini) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-warning">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #fffbeb; color: #d97706;">
                        <i class="fas fa-hand-holding-dollar"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #d97706;">Kasbon &amp; Prive Owner</div>
                        <div class="kpi-value" style="color: #d97706;">
                            Rp {{ number_format(($totalKasbonBulanIni ?? 0) + ($totalPribadiBulanIni ?? 0), 0, ',', '.') }}
                        </div>
                        <div class="kpi-subtext">Kasbon: Rp {{ number_format($totalKasbonBulanIni ?? 0, 0, ',', '.') }} | Prive: Rp {{ number_format($totalPribadiBulanIni ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Total Semua Pengeluaran --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-info">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total Pengeluaran Bulan Ini</div>
                        <div class="kpi-value text-primary">
                            Rp {{ number_format($totalBulanIni ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="kpi-subtext">{{ number_format($totalTransaksi ?? $pengeluarans->total()) }} transaksi tercatat</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH TOOLBAR (STRUKTUR PERSIS KATALOG SOUVENIR) --}}
    @php
        $activeFilterCount = 0;
        if (request()->filled('tipe_alokasi')) $activeFilterCount++;
        if (request()->filled('kategori')) $activeFilterCount++;
        if (request()->filled('status_kasbon')) $activeFilterCount++;
        if (request()->filled('dari_tanggal')) $activeFilterCount++;
        if (request()->filled('sampai_tanggal')) $activeFilterCount++;
        if (request()->filled('sort') && request('sort') !== 'latest') $activeFilterCount++;
    @endphp

    <div class="card card-modern bg-white mb-3">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-stretch align-items-xl-center gap-2.5">
                {{-- Form Pencarian Cepat --}}
                <div class="flex-grow-1">
                    <form method="GET" action="{{ route('pengeluaran.index') }}" class="m-0">
                        @if(request('tipe_alokasi')) <input type="hidden" name="tipe_alokasi" value="{{ request('tipe_alokasi') }}"> @endif
                        @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
                        @if(request('status_kasbon')) <input type="hidden" name="status_kasbon" value="{{ request('status_kasbon') }}"> @endif
                        @if(request('dari_tanggal')) <input type="hidden" name="dari_tanggal" value="{{ request('dari_tanggal') }}"> @endif
                        @if(request('sampai_tanggal')) <input type="hidden" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <div class="input-group search-input-group shadow-none">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-3 pe-2">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   class="form-control border-start-0 ps-1"
                                   placeholder="Cari kode pengeluaran, keterangan biaya, atau nama karyawan..."
                                   value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('pengeluaran.index', request()->except('search')) }}" class="input-group-text bg-white text-muted text-decoration-none border-start-0 pe-3" title="Hapus Pencarian">
                                    <i class="fas fa-times text-secondary"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Action Buttons: Filter, Reset & Tambah Pengeluaran (Sejajar Samping Filter) --}}
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" id="btnBukaFilterPengeluaran" class="btn btn-filter-custom position-relative" data-bs-toggle="modal" data-bs-target="#modalFilterPengeluaran">
                        <i class="fas fa-filter"></i>
                        <span>Filter Data</span>
                        @if($activeFilterCount > 0)
                            <span class="badge bg-white text-dark rounded-pill ms-1" style="font-size: 0.68rem; font-weight: 700; padding: 0.2rem 0.45rem;">{{ $activeFilterCount }}</span>
                        @endif
                    </button>

                    @if(request()->anyFilled(['search', 'tipe_alokasi', 'kategori', 'status_kasbon', 'dari_tanggal', 'sampai_tanggal', 'sort']))
                        <a href="{{ route('pengeluaran.index') }}" class="btn btn-reset-custom" title="Hapus Semua Filter">
                            <i class="fas fa-redo"></i>
                            <span class="d-none d-sm-inline">Reset</span>
                        </a>
                    @endif

                    @can('pengeluaran-create')
                        <a href="{{ route('pengeluaran.create') }}" class="btn btn-primary-custom shadow-sm">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Pengeluaran</span>
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
                            <a href="{{ route('pengeluaran.index', request()->except('search')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('tipe_alokasi'))
                        <span class="filter-chip">
                            <i class="fas fa-layer-group me-1 text-primary"></i> Alokasi: {{ $tipeAlokasis[request('tipe_alokasi')] ?? ucfirst(request('tipe_alokasi')) }}
                            <a href="{{ route('pengeluaran.index', request()->except('tipe_alokasi')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('kategori'))
                        <span class="filter-chip">
                            <i class="fas fa-tag me-1 text-primary"></i> Kategori: {{ $kategoris[request('kategori')] ?? request('kategori') }}
                            <a href="{{ route('pengeluaran.index', request()->except('kategori')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('status_kasbon'))
                        <span class="filter-chip">
                            <i class="fas fa-check-circle me-1 text-primary"></i> Kasbon: {{ request('status_kasbon') === 'lunas' ? 'Sudah Lunas' : 'Belum Lunas' }}
                            <a href="{{ route('pengeluaran.index', request()->except('status_kasbon')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('dari_tanggal') || request('sampai_tanggal'))
                        <span class="filter-chip">
                            <i class="fas fa-calendar me-1 text-primary"></i>
                            {{ request('dari_tanggal') ?: '...' }} s/d {{ request('sampai_tanggal') ?: '...' }}
                            <a href="{{ route('pengeluaran.index', request()->except(['dari_tanggal', 'sampai_tanggal'])) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('sort') && request('sort') !== 'latest')
                        <span class="filter-chip">
                            <i class="fas fa-arrow-down-short-wide me-1"></i> Urutan: {{ request('sort') }}
                            <a href="{{ route('pengeluaran.index', request()->except('sort')) }}"><i class="fas fa-times"></i></a>
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
                <table class="table table-hover table-pengeluaran align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th style="min-width: 210px;">Kode &amp; Tanggal</th>
                            <th style="min-width: 155px;">Alokasi</th>
                            <th style="min-width: 190px;">Kategori Biaya</th>
                            <th style="min-width: 250px;">Keterangan</th>
                            <th class="text-end" style="width: 165px;">Nominal Keluar</th>
                            <th class="text-center pe-4 col-freeze-aksi" style="width: 145px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengeluarans as $index => $pgl)
                        <tr>
                            {{-- 1. No --}}
                            <td class="ps-4 text-muted font-monospace small">
                                {{ $pengeluarans->firstItem() + $index }}
                            </td>

                            {{-- 2. Kode & Tanggal --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-icon-souvenir">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                            <a href="{{ route('pengeluaran.show', $pgl) }}" class="text-decoration-none">
                                                <span class="badge-kode">{{ $pgl->kode_pengeluaran }}</span>
                                            </a>
                                            @if($pgl->bukti_transaksi)
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle"
                                                      style="cursor: pointer; font-size: 0.65rem;"
                                                      title="Klik untuk lihat bukti struk"
                                                      onclick="showBuktiModal('{{ $pgl->bukti_transaksi_url }}', 'Bukti {{ $pgl->kode_pengeluaran }}')">
                                                    <i class="fas fa-paperclip me-1"></i>Bukti
                                                </span>
                                            @endif
                                        </div>
                                        <div class="small text-muted mt-1">
                                            <i class="far fa-calendar-alt me-1"></i>{{ $pgl->tanggal->translatedFormat('d M Y') }}
                                            <span class="ms-1 text-secondary opacity-75">&bull; {{ $pgl->created_at->format('H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- 3. Alokasi --}}
                            <td>
                                @if($pgl->tipe_alokasi === 'bisnis')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fw-semibold" style="font-size: 0.72rem;">
                                        <i class="fas fa-store me-1"></i>Bisnis Toko
                                    </span>
                                @elseif($pgl->tipe_alokasi === 'pribadi')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 fw-semibold" style="font-size: 0.72rem;">
                                        <i class="fas fa-user-tie me-1"></i>Pribadi / Prive
                                    </span>
                                @else
                                    <div class="d-flex flex-column align-items-start gap-1">
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1 fw-semibold" style="font-size: 0.72rem;">
                                            <i class="fas fa-hand-holding-dollar me-1"></i>Kasbon Tim
                                        </span>
                                        @if($pgl->status_kasbon === 'lunas')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.65rem;">
                                                <i class="fas fa-check-circle me-1"></i>Lunas
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">
                                                <i class="fas fa-clock me-1"></i>Belum Lunas
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            {{-- 4. Kategori Biaya --}}
                            <td>
                                <span class="badge-kategori-custom">
                                    <i class="fas fa-tag"></i>
                                    <span>{{ $pgl->kategori_label }}</span>
                                </span>
                            </td>

                            {{-- 5. Keterangan --}}
                            <td>
                                <div class="fw-semibold text-dark text-truncate" style="max-width: 280px;" title="{{ $pgl->keterangan }}">
                                    @if($pgl->tipe_alokasi === 'kasbon' && $pgl->karyawan_nama)
                                        <span class="text-primary fw-bold">[{{ $pgl->karyawan_nama }}]</span>
                                    @endif
                                    {{ $pgl->keterangan }}
                                </div>
                                @if($pgl->catatan)
                                    <div class="small text-muted text-truncate" style="max-width: 280px;" title="{{ $pgl->catatan }}">
                                        {{ $pgl->catatan }}
                                    </div>
                                @endif
                            </td>

                            {{-- 6. Nominal Keluar --}}
                            <td class="text-end text-nowrap font-monospace fw-bold text-danger" style="font-size: 0.9rem;">
                                - Rp {{ number_format($pgl->jumlah, 0, ',', '.') }}
                            </td>

                            {{-- 7. Aksi (Solid gradient persis Katalog Souvenir) --}}
                            <td class="text-center pe-4 col-freeze-aksi">
                                <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                    <a href="{{ route('pengeluaran.show', $pgl) }}"
                                       class="btn-action-icon btn-action-view"
                                       title="Lihat Detail Pengeluaran">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($pgl->bukti_transaksi)
                                        <button type="button"
                                                class="btn-action-icon btn-action-bukti"
                                                title="Lihat Bukti Struk"
                                                onclick="showBuktiModal('{{ $pgl->bukti_transaksi_url }}', 'Bukti {{ $pgl->kode_pengeluaran }}')">
                                            <i class="fas fa-paperclip"></i>
                                        </button>
                                    @endif

                                    @can('pengeluaran-edit')
                                        <a href="{{ route('pengeluaran.edit', $pgl) }}"
                                           class="btn-action-icon btn-action-edit"
                                           title="Edit Pengeluaran">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @can('pengeluaran-delete')
                                        <button type="button"
                                                class="btn-action-icon btn-action-delete btn-delete-pgl"
                                                data-url="{{ route('pengeluaran.destroy', $pgl) }}"
                                                data-kode="{{ $pgl->kode_pengeluaran }}"
                                                title="Hapus Pengeluaran">
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
                                    icon="fas fa-credit-card"
                                    title="Belum Ada Data Pengeluaran"
                                    message="Belum ada biaya operasional, iklan marketplace, atau pengeluaran lain yang sesuai dengan pencarian atau filter yang Anda pilih."
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if($pengeluarans->hasPages())
            <x-pagination :paginator="$pengeluarans" label="pengeluaran" />
        @endif
    </div>

</div>

{{-- MODAL FILTER PENGELUARAN (SAMA PERSIS DESAIN MODAL FILTER KATALOG SOUVENIR) --}}
<div class="modal fade" id="modalFilterPengeluaran" tabindex="-1" aria-labelledby="modalFilterPengeluaranLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.875rem;">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-filter fa-lg"></i>
                    <h5 class="modal-title fw-bold fs-6 mb-0 text-white" id="modalFilterPengeluaranLabel">Filter Biaya Operasional &amp; Ads</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('pengeluaran.index') }}">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="modal-body p-4">
                    <div class="row g-3">
                        {{-- 1. Alokasi Pengeluaran --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_tipe_alokasi">Alokasi Keuangan</label>
                            <select name="tipe_alokasi" id="modal_tipe_alokasi" class="form-select">
                                <option value="">Semua Alokasi</option>
                                @foreach($tipeAlokasis as $tKey => $tLabel)
                                    <option value="{{ $tKey }}" {{ request('tipe_alokasi') == $tKey ? 'selected' : '' }}>
                                        {{ $tLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. Kategori Biaya --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_kategori">Kategori Biaya</label>
                            <select name="kategori" id="modal_kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $key => $label)
                                    <option value="{{ $key }}" {{ request('kategori') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 3. Rentang Tanggal --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_dari_tanggal">Dari Tanggal</label>
                            <input type="date" name="dari_tanggal" id="modal_dari_tanggal" class="form-control" value="{{ request('dari_tanggal') }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_sampai_tanggal">Sampai Tanggal</label>
                            <input type="date" name="sampai_tanggal" id="modal_sampai_tanggal" class="form-control" value="{{ request('sampai_tanggal') }}">
                        </div>

                        {{-- 4. Status Kasbon --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_status_kasbon">Status Kasbon</label>
                            <select name="status_kasbon" id="modal_status_kasbon" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="belum_lunas" {{ request('status_kasbon') === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas (Piutang Aktif)</option>
                                <option value="lunas" {{ request('status_kasbon') === 'lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                            </select>
                        </div>

                        {{-- 5. Urutkan Berdasarkan --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_sort">Urutkan Data</label>
                            <select name="sort" id="modal_sort" class="form-select">
                                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Tanggal Terbaru</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Tanggal Terlama</option>
                                <option value="jumlah_desc" {{ request('sort') === 'jumlah_desc' ? 'selected' : '' }}>Nominal Terbesar</option>
                                <option value="jumlah_asc" {{ request('sort') === 'jumlah_asc' ? 'selected' : '' }}>Nominal Terkecil</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top py-3 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('pengeluaran.index', request()->only('search')) }}" class="btn btn-reset-custom">
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
<form id="deletePglForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalFilterEl = document.getElementById('modalFilterPengeluaran');
    if (modalFilterEl && modalFilterEl.parentElement !== document.body) {
        document.body.appendChild(modalFilterEl);
    }

    const btnBukaFilter = document.getElementById('btnBukaFilterPengeluaran');
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

    document.querySelectorAll('.btn-delete-pgl').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.dataset.url;
            const kode = this.dataset.kode;

            Swal.fire({
                title: 'Hapus Pengeluaran?',
                html: `<p style="color: #4b5563; margin-bottom: 8px;">Data pengeluaran <b>"${kode}"</b> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</p>`,
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
                    const form = document.getElementById('deletePglForm');
                    form.action = url;
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
