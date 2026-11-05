@extends('layouts.app')

@section('title', 'Pembelian Bahan Baku')

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
    .table-pembelian {
        min-width: 1100px;
    }
    .table-pembelian thead {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
    }
    .table-pembelian thead th {
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
    .table-pembelian tbody td {
        padding: 0.75rem 0.85rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.8125rem;
        background-color: #ffffff;
    }
    .table-pembelian tbody tr:hover td {
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
    .table-pembelian thead th.col-freeze-aksi {
        background: linear-gradient(135deg, #1f3a5c 0%, #1a304d 100%) !important;
        z-index: 6;
    }
    .table-pembelian tbody td.col-freeze-aksi {
        background-color: #ffffff;
    }
    .table-pembelian tbody tr:hover td.col-freeze-aksi {
        background-color: #f8fafc;
    }

    /* BADGES PLATFORM & STATUS */
    .badge-platform {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 0.2rem 0.55rem;
        border-radius: 0.375rem;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-shopee { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .badge-tokopedia { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .badge-tiktok { background: #f8fafc; color: #0f172a; border: 1px solid #cbd5e1; }
    .badge-lazada { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .badge-offline { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .badge-default { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

    .status-badge-btn {
        border: none;
        background: transparent;
        padding: 0;
        cursor: pointer;
        display: inline-block;
        transition: transform 0.15s ease;
    }
    .status-badge-btn:hover {
        transform: scale(1.04);
    }

    .badge-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.6rem;
        border-radius: 0.375rem;
        font-size: 0.72rem;
        font-weight: 700;
        text-decoration: none;
    }
    .status-pill-dipesan {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }
    .status-pill-dikirim {
        background: #f0f9ff;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }
    .status-pill-diterima {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
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
    .btn-action-status {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        color: #ffffff !important;
        border-color: #3730a3;
    }
    .btn-action-status:hover {
        background: linear-gradient(135deg, #4338ca 0%, #312e81 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(79, 70, 229, 0.3);
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
        icon="fas fa-truck-ramp-box"
        title="Pembelian Bahan Baku Online"
        subtitle="Kelola riwayat pengadaan material mentah, blanko souvenir, dan packaging kargo dari supplier."
    />

    {{-- KPI STATS SUMMARY (4 CARDS) --}}
    <div class="row g-3 mb-3">
        {{-- 1. Total Belanja Bahan --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-info">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total Belanja Bahan</div>
                        <div class="kpi-value text-dark">
                            Rp {{ number_format($totalNominal, 0, ',', '.') }}
                        </div>
                        <div class="kpi-subtext">Dari {{ number_format($totalTransaksi) }} batch kulakan</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Dipesan (Supplier) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-warning">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #fffbeb; color: #d97706;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #d97706;">Dipesan (Supplier)</div>
                        <div class="kpi-value" style="color: #d97706;">
                            {{ number_format($totalDipesan) }}
                            <small class="text-muted fs-6 fw-normal">Order</small>
                        </div>
                        <div class="kpi-subtext">Menunggu proses &amp; kirim seller</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Dikirim (Kargo) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-indigo">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #eef2ff; color: #4f46e5;">
                        <i class="fas fa-truck-fast"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Dikirim (Kargo)</div>
                        <div class="kpi-value text-primary">
                            {{ number_format($totalDikirim) }}
                            <small class="text-muted fs-6 fw-normal">Order</small>
                        </div>
                        <div class="kpi-subtext">Dalam perjalanan ekspedisi</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Diterima di Workshop --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #059669;">Diterima di Workshop</div>
                        <div class="kpi-value text-success">
                            {{ number_format($totalDiterima) }}
                            <small class="text-muted fs-6 fw-normal">Order</small>
                        </div>
                        <div class="kpi-subtext">Stok otomatis bertambah</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH TOOLBAR (STRUKTUR PERSIS KATALOG SOUVENIR) --}}
    @php
        $activeFilterCount = 0;
        if (request()->filled('platform')) $activeFilterCount++;
        if (request()->filled('status_pembelian')) $activeFilterCount++;
        if (request()->filled('dari_tanggal')) $activeFilterCount++;
        if (request()->filled('sampai_tanggal')) $activeFilterCount++;
        if (request()->filled('sort') && request('sort') !== 'latest') $activeFilterCount++;
    @endphp

    <div class="card card-modern bg-white mb-3">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-stretch align-items-xl-center gap-2.5">
                {{-- Form Pencarian Cepat --}}
                <div class="flex-grow-1">
                    <form method="GET" action="{{ route('transaksi-pembelian.index') }}" class="m-0">
                        @if(request('platform')) <input type="hidden" name="platform" value="{{ request('platform') }}"> @endif
                        @if(request('status_pembelian')) <input type="hidden" name="status_pembelian" value="{{ request('status_pembelian') }}"> @endif
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
                                   placeholder="Ketik kode pembelian, supplier, no invoice, atau resi kargo..."
                                   value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('transaksi-pembelian.index', request()->except('search')) }}" class="input-group-text bg-white text-muted text-decoration-none border-start-0 pe-3" title="Hapus Pencarian">
                                    <i class="fas fa-times text-secondary"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Action Buttons: Filter, Reset & Catat Pembelian Baru (Sejajar Samping Filter) --}}
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" id="btnBukaFilterPembelian" class="btn btn-filter-custom position-relative" data-bs-toggle="modal" data-bs-target="#modalFilterPembelian">
                        <i class="fas fa-filter"></i>
                        <span>Filter Data</span>
                        @if($activeFilterCount > 0)
                            <span class="badge bg-white text-dark rounded-pill ms-1" style="font-size: 0.68rem; font-weight: 700; padding: 0.2rem 0.45rem;">{{ $activeFilterCount }}</span>
                        @endif
                    </button>

                    @if(request()->anyFilled(['search', 'platform', 'status_pembelian', 'dari_tanggal', 'sampai_tanggal', 'sort']))
                        <a href="{{ route('transaksi-pembelian.index') }}" class="btn btn-reset-custom" title="Hapus Semua Filter">
                            <i class="fas fa-redo"></i>
                            <span class="d-none d-sm-inline">Reset</span>
                        </a>
                    @endif

                    @can('pembelian-create')
                        <a href="{{ route('transaksi-pembelian.create') }}" class="btn btn-primary-custom shadow-sm">
                            <i class="fas fa-plus"></i>
                            <span>Catat Pembelian Baru</span>
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
                            <a href="{{ route('transaksi-pembelian.index', request()->except('search')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('platform'))
                        <span class="filter-chip">
                            <i class="fas fa-store me-1 text-primary"></i> Platform: {{ App\Models\Pembelian::PLATFORMS[request('platform')] ?? ucfirst(request('platform')) }}
                            <a href="{{ route('transaksi-pembelian.index', request()->except('platform')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('status_pembelian'))
                        <span class="filter-chip">
                            <i class="fas fa-truck-ramp-box me-1 text-primary"></i> Status: {{ App\Models\Pembelian::STATUS_PEMBELIAN[request('status_pembelian')] ?? ucfirst(request('status_pembelian')) }}
                            <a href="{{ route('transaksi-pembelian.index', request()->except('status_pembelian')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('dari_tanggal') || request('sampai_tanggal'))
                        <span class="filter-chip">
                            <i class="fas fa-calendar-alt me-1 text-primary"></i>
                            Tanggal: {{ request('dari_tanggal') ? date('d/m/Y', strtotime(request('dari_tanggal'))) : 'Awal' }}
                            s.d. {{ request('sampai_tanggal') ? date('d/m/Y', strtotime(request('sampai_tanggal'))) : 'Sekarang' }}
                            <a href="{{ route('transaksi-pembelian.index', request()->except(['dari_tanggal', 'sampai_tanggal'])) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('sort') && request('sort') !== 'latest')
                        <span class="filter-chip">
                            <i class="fas fa-arrow-down-short-wide me-1"></i> Urutan: {{ request('sort') }}
                            <a href="{{ route('transaksi-pembelian.index', request()->except('sort')) }}"><i class="fas fa-times"></i></a>
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
                <table class="table table-hover table-pembelian align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th style="min-width: 220px;">Informasi Pembelian</th>
                            <th style="min-width: 200px;">Toko Supplier &amp; Platform</th>
                            <th style="width: 170px;">Kurir &amp; Resi Kargo</th>
                            <th class="text-center" style="width: 165px;">Status Pengadaan</th>
                            <th class="text-center" style="width: 110px;">Item Bahan</th>
                            <th class="text-end" style="width: 165px;">Grand Total</th>
                            <th class="text-center pe-4 col-freeze-aksi" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembelians as $index => $pbl)
                        <tr>
                            {{-- 1. No --}}
                            <td class="ps-4 text-muted font-monospace small">
                                {{ $pembelians->firstItem() + $index }}
                            </td>

                            {{-- 2. Informasi Pembelian (Avatar + Kode + Tanggal persis Katalog Souvenir) --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-icon-souvenir">
                                        <i class="fas fa-truck-ramp-box"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="fw-bold text-dark">
                                            <a href="{{ route('transaksi-pembelian.show', $pbl) }}" class="text-dark text-decoration-none font-monospace">
                                                {{ $pbl->kode_pembelian }}
                                            </a>
                                        </div>
                                        <div class="d-flex align-items-center gap-1.5 mt-1 flex-wrap">
                                            <span class="badge-kode">
                                                <i class="far fa-calendar me-1"></i>{{ $pbl->tanggal->translatedFormat('d M Y') }}
                                            </span>
                                            @if($pbl->no_invoice_online)
                                                <span class="badge-kode text-muted" title="{{ $pbl->no_invoice_online }}">
                                                    {{ Str::limit($pbl->no_invoice_online, 14) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- 3. Toko Supplier & Platform --}}
                            <td>
                                <div class="fw-semibold text-dark">{{ $pbl->supplier ?: 'Toko Supplier' }}</div>
                                <div class="mt-1 d-flex align-items-center gap-1">
                                    @php
                                        $platClass = match($pbl->platform) {
                                            'shopee' => 'badge-shopee',
                                            'tokopedia' => 'badge-tokopedia',
                                            'tiktok' => 'badge-tiktok',
                                            'lazada' => 'badge-lazada',
                                            'offline' => 'badge-offline',
                                            default => 'badge-default'
                                        };
                                    @endphp
                                    <span class="badge-platform {{ $platClass }}">
                                        {{ $pbl->platform_label }}
                                    </span>
                                </div>
                            </td>

                            {{-- 4. Kurir & Resi Kargo --}}
                            <td>
                                @if($pbl->kurir_kargo || $pbl->no_resi_kargo)
                                    <div class="fw-semibold text-dark" style="font-size: 0.8rem;">
                                        <i class="fas fa-truck text-primary me-1"></i>{{ $pbl->kurir_kargo ?: 'Kargo' }}
                                    </div>
                                    @if($pbl->no_resi_kargo)
                                        <div class="mt-0.5">
                                            <span class="badge-kode">{{ $pbl->no_resi_kargo }}</span>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-muted small fst-italic" style="font-size: 0.75rem;">Tanpa resi kargo</span>
                                @endif
                            </td>

                            {{-- 5. Status Pengadaan --}}
                            <td class="text-center">
                                @php
                                    $pillClass = match($pbl->status_pembelian) {
                                        'dipesan' => 'status-pill-dipesan',
                                        'dikirim' => 'status-pill-dikirim',
                                        'diterima' => 'status-pill-diterima',
                                        default => 'status-pill-dipesan',
                                    };
                                    $pillIcon = match($pbl->status_pembelian) {
                                        'dipesan' => 'fas fa-clock',
                                        'dikirim' => 'fas fa-truck-fast',
                                        'diterima' => 'fas fa-check-circle',
                                        default => 'fas fa-box',
                                    };
                                    $pillLabel = match($pbl->status_pembelian) {
                                        'dipesan' => 'Dipesan',
                                        'dikirim' => 'Sedang Dikirim',
                                        'diterima' => 'Diterima di Gudang',
                                        default => ucfirst($pbl->status_pembelian),
                                    };
                                @endphp
                                <button type="button"
                                        class="status-badge-btn btn-trigger-status"
                                        data-url="{{ route('transaksi-pembelian.update-status', $pbl) }}"
                                        data-kode="{{ $pbl->kode_pembelian }}"
                                        data-status="{{ $pbl->status_pembelian }}"
                                        data-kurir="{{ $pbl->kurir_kargo }}"
                                        data-resi="{{ $pbl->no_resi_kargo }}"
                                        title="Klik untuk ubah status pengadaan">
                                    <span class="badge-status-pill {{ $pillClass }}">
                                        <i class="{{ $pillIcon }}"></i>
                                        <span>{{ $pillLabel }}</span>
                                    </span>
                                </button>
                            </td>

                            {{-- 6. Item Bahan --}}
                            <td class="text-center">
                                <span class="badge-satuan font-monospace">
                                    {{ $pbl->details->count() }} Bahan
                                </span>
                            </td>

                            {{-- 7. Grand Total --}}
                            <td class="text-end">
                                <div class="fw-bold text-dark font-monospace">
                                    Rp {{ number_format($pbl->grand_total, 0, ',', '.') }}
                                </div>
                                @if($pbl->ongkir > 0)
                                    <div class="text-muted font-monospace" style="font-size: 0.7rem;">
                                        +Ongkir: Rp {{ number_format($pbl->ongkir, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>

                            {{-- 8. Aksi (Solid gradient persis Katalog Souvenir) --}}
                            <td class="text-center pe-4 col-freeze-aksi">
                                <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                    {{-- Lihat Detail --}}
                                    <a href="{{ route('transaksi-pembelian.show', $pbl) }}"
                                       class="btn-action-icon btn-action-view"
                                       title="Lihat Detail Transaksi">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Quick Update Status --}}
                                    <button type="button"
                                            class="btn-action-icon btn-action-status btn-trigger-status"
                                            data-url="{{ route('transaksi-pembelian.update-status', $pbl) }}"
                                            data-kode="{{ $pbl->kode_pembelian }}"
                                            data-status="{{ $pbl->status_pembelian }}"
                                            data-kurir="{{ $pbl->kurir_kargo }}"
                                            data-resi="{{ $pbl->no_resi_kargo }}"
                                            title="Ubah Status (Dipesan / Dikirim / Diterima)">
                                        <i class="fas fa-truck-fast"></i>
                                    </button>

                                    {{-- Hapus (Hanya hari ini) --}}
                                    @can('pembelian-delete')
                                        @if($pbl->tanggal->isToday())
                                            <button type="button"
                                                    class="btn-action-icon btn-action-delete btn-delete-pbl"
                                                    data-url="{{ route('transaksi-pembelian.destroy', $pbl) }}"
                                                    data-kode="{{ $pbl->kode_pembelian }}"
                                                    title="Hapus Pembelian Hari Ini">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-0">
                                <x-empty-state
                                    icon="fas fa-truck-ramp-box"
                                    title="Tidak Ada Transaksi Pembelian Ditemukan"
                                    message="Belum ada data pengadaan bahan baku yang sesuai dengan kriteria pencarian atau filter yang Anda pilih."
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if($pembelians->hasPages())
            <x-pagination :paginator="$pembelians" label="transaksi pembelian" />
        @endif
    </div>

</div>

{{-- MODAL FILTER TRANSAKSI PEMBELIAN (SAMA PERSIS DESAIN MODAL FILTER KATALOG SOUVENIR) --}}
<div class="modal fade" id="modalFilterPembelian" tabindex="-1" aria-labelledby="modalFilterPembelianLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.875rem;">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-filter fa-lg"></i>
                    <h5 class="modal-title fw-bold fs-6 mb-0 text-white" id="modalFilterPembelianLabel">Filter Data Pembelian Bahan Baku</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('transaksi-pembelian.index') }}">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="modal-body p-4">
                    <div class="row g-3">
                        {{-- 1. Platform Belanja --}}
                        <div class="col-12">
                            <label class="form-label" for="modal_platform">Platform Toko Online</label>
                            <select name="platform" id="modal_platform" class="form-select">
                                <option value="">Semua Platform Belanja</option>
                                @foreach(App\Models\Pembelian::PLATFORMS as $key => $label)
                                    <option value="{{ $key }}" {{ request('platform') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. Status Pengadaan --}}
                        <div class="col-12">
                            <label class="form-label" for="modal_status_pembelian">Status Pengadaan / Pengiriman</label>
                            <select name="status_pembelian" id="modal_status_pembelian" class="form-select">
                                <option value="">Semua Status Pengadaan</option>
                                @foreach(App\Models\Pembelian::STATUS_PEMBELIAN as $key => $label)
                                    <option value="{{ $key }}" {{ request('status_pembelian') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 3. Rentang Tanggal --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_dari_tanggal">Dari Tanggal</label>
                            <input type="date"
                                   name="dari_tanggal"
                                   id="modal_dari_tanggal"
                                   class="form-control"
                                   value="{{ request('dari_tanggal') }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_sampai_tanggal">Sampai Tanggal</label>
                            <input type="date"
                                   name="sampai_tanggal"
                                   id="modal_sampai_tanggal"
                                   class="form-control"
                                   value="{{ request('sampai_tanggal') }}">
                        </div>

                        {{-- 4. Urutkan Data --}}
                        <div class="col-12">
                            <label class="form-label" for="modal_sort">Urutkan Data</label>
                            <select name="sort" id="modal_sort" class="form-select">
                                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Terbaru Dicatat</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama Dicatat</option>
                                <option value="nominal_desc" {{ request('sort') === 'nominal_desc' ? 'selected' : '' }}>Grand Total Tertinggi</option>
                                <option value="nominal_asc" {{ request('sort') === 'nominal_asc' ? 'selected' : '' }}>Grand Total Terendah</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top py-3 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('transaksi-pembelian.index', request()->only('search')) }}" class="btn btn-reset-custom">
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

{{-- MODAL CEPAT UPDATE STATUS PENGADAAN (DIPESAN / DIKIRIM / DITERIMA) --}}
<div class="modal fade" id="modalUpdateStatusPembelian" tabindex="-1" aria-labelledby="modalUpdateStatusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.875rem;">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-truck-ramp-box fa-lg"></i>
                    <h5 class="modal-title fw-bold fs-6 mb-0 text-white" id="modalUpdateStatusLabel">Update Status Pengadaan</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUpdateStatusPembelian" method="POST" action="">
                @csrf
                @method('PATCH')

                <div class="modal-body p-4">
                    <div class="p-3 mb-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Nomor Pembelian</div>
                        <div class="fw-bold font-monospace text-primary fs-6" id="statusModalKode">-</div>
                    </div>

                    <div class="row g-3">
                        {{-- Pilihan Status --}}
                        <div class="col-12">
                            <label for="status_pembelian_select" class="form-label">
                                Pilih Status Pengadaan <span class="text-danger">*</span>
                            </label>
                            <select name="status_pembelian" id="status_pembelian_select" class="form-select" required>
                                <option value="dipesan">Dipesan (Menunggu dikirim supplier online, stok belum bertambah)</option>
                                <option value="dikirim">Dikirim (Paket dalam perjalanan kargo, stok belum bertambah)</option>
                                <option value="diterima">Diterima di Workshop (Barang fisik sampai &amp; dicek, stok otomatis masuk gudang)</option>
                            </select>
                        </div>

                        {{-- Kurir & Resi --}}
                        <div class="col-12 col-md-6">
                            <label for="status_modal_kurir" class="form-label">Kurir / Ekspedisi Kargo</label>
                            <input type="text" name="kurir_kargo" id="status_modal_kurir" class="form-control" placeholder="Misal: J&amp;T Cargo">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="status_modal_resi" class="form-label">Nomor Resi Kargo</label>
                            <input type="text" name="no_resi_kargo" id="status_modal_resi" class="form-control" placeholder="No. Resi Pengiriman">
                        </div>

                        {{-- Info Card Stok Alert --}}
                        <div class="col-12">
                            <div class="p-3 rounded-3 border" id="statusStockNotice" style="background: #eff6ff; border-color: #bfdbfe; font-size: 0.8rem; color: #1e40af;">
                                <i class="fas fa-info-circle me-1"></i>
                                <span>Memilih <strong>Diterima</strong> akan otomatis menambahkan kuantitas bahan baku ke stok fisik workshop.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top py-3 px-4 d-flex justify-content-end align-items-center gap-2">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-save"></i>
                        <span>Simpan Status Baru</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Hidden Delete Form --}}
<form id="deleteFormPbl" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Pindahkan modal ke <body> agar terhindar dari stacking context & overflow:hidden wrapper AdminKit
    const modalFilterEl = document.getElementById('modalFilterPembelian');
    if (modalFilterEl && modalFilterEl.parentElement !== document.body) {
        document.body.appendChild(modalFilterEl);
    }

    const modalStatusEl = document.getElementById('modalUpdateStatusPembelian');
    if (modalStatusEl && modalStatusEl.parentElement !== document.body) {
        document.body.appendChild(modalStatusEl);
    }

    // 2. Event listener pembuka modal filter
    const btnBukaFilter = document.getElementById('btnBukaFilterPembelian');
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

    // 3. Event listener tombol Update Status
    const statusTriggers = document.querySelectorAll('.btn-trigger-status');
    const formUpdateStatus = document.getElementById('formUpdateStatusPembelian');
    const statusModalKode = document.getElementById('statusModalKode');
    const selectStatus = document.getElementById('status_pembelian_select');
    const inputKurir = document.getElementById('status_modal_kurir');
    const inputResi = document.getElementById('status_modal_resi');
    const stockNotice = document.getElementById('statusStockNotice');

    statusTriggers.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            const kode = this.getAttribute('data-kode');
            const status = this.getAttribute('data-status');
            const kurir = this.getAttribute('data-kurir') || '';
            const resi = this.getAttribute('data-resi') || '';

            formUpdateStatus.action = url;
            statusModalKode.textContent = kode;
            selectStatus.value = status;
            inputKurir.value = kurir;
            inputResi.value = resi;

            updateStockNotice(status);

            if (window.bootstrap && window.bootstrap.Modal) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalStatusEl);
                modal.show();
            } else if (window.jQuery && typeof jQuery(modalStatusEl).modal === 'function') {
                jQuery(modalStatusEl).modal('show');
            } else {
                modalStatusEl.classList.add('show');
                modalStatusEl.style.display = 'block';
            }
        });
    });

    if (selectStatus) {
        selectStatus.addEventListener('change', function() {
            updateStockNotice(this.value);
        });
    }

    function updateStockNotice(status) {
        if (!stockNotice) return;
        if (status === 'diterima') {
            stockNotice.className = 'p-3 rounded-3 border bg-success-subtle border-success text-success';
            stockNotice.innerHTML = '<i class="fas fa-check-circle me-1"></i> Status <strong>Diterima</strong>: Bahan baku fisik sudah tiba dan kuantitas akan otomatis bertambah ke stok gudang workshop.';
        } else if (status === 'dikirim') {
            stockNotice.className = 'p-3 rounded-3 border bg-info-subtle border-info text-primary';
            stockNotice.innerHTML = '<i class="fas fa-truck-fast me-1"></i> Status <strong>Dikirim</strong>: Paket kargo dalam perjalanan. Stok bahan baku di gudang belum bertambah.';
        } else {
            stockNotice.className = 'p-3 rounded-3 border bg-warning-subtle border-warning text-warning';
            stockNotice.innerHTML = '<i class="fas fa-clock me-1"></i> Status <strong>Dipesan</strong>: Order baru dikirim ke supplier. Stok bahan baku belum bertambah.';
        }
    }

    // 4. Close handlers for modals
    [modalFilterEl, modalStatusEl].forEach(modalEl => {
        if (!modalEl) return;
        modalEl.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (window.bootstrap && window.bootstrap.Modal) {
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.hide();
                } else if (window.jQuery && typeof jQuery(modalEl).modal === 'function') {
                    jQuery(modalEl).modal('hide');
                } else {
                    modalEl.classList.remove('show');
                    modalEl.style.display = 'none';
                }
            });
        });
    });

    // 5. Delete button confirmation
    const deleteBtns = document.querySelectorAll('.btn-delete-pbl');
    const deleteForm = document.getElementById('deleteFormPbl');

    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const kode = this.getAttribute('data-kode');

            Swal.fire({
                title: 'Hapus Pembelian?',
                html: `<p style="color: #4b5563; margin-bottom: 8px;">Transaksi pembelian <b>"${kode}"</b> akan dihapus. Jika status sudah diterima, stok yang sebelumnya bertambah akan otomatis dikurangi kembali.</p>`,
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
