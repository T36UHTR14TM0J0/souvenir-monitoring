@extends('layouts.app')

@section('title', 'Pesanan Marketplace')

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

    /* MARKETPLACE BREAKDOWN STRIP */
    .market-bar-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.65rem 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    }
    .market-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.3rem 0.65rem;
        border-radius: 0.45rem;
        font-size: 0.78rem;
        font-weight: 600;
        line-height: 1.2;
        font-variant-numeric: tabular-nums;
    }
    .market-pill-shopee {
        background: #fff7ed;
        color: #c2410c;
        border: 1px solid #fed7aa;
    }
    .market-pill-lazada {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }
    .market-pill-tiktok {
        background: #f8fafc;
        color: #0f172a;
        border: 1px solid #e2e8f0;
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
    .table-pesanan {
        min-width: 1240px;
    }
    .table-pesanan thead {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
    }
    .table-pesanan thead th {
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
    .table-pesanan tbody td {
        padding: 0.75rem 0.85rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.8125rem;
        background-color: #ffffff;
    }
    .table-pesanan tbody tr:hover td {
        background-color: #f8fafc;
    }

    /* FREEZE / STICKY ACTION COLUMN */
    .col-freeze-aksi {
        position: sticky !important;
        right: 0 !important;
        z-index: 5;
        min-width: 155px;
        width: 155px;
        box-shadow: -4px 0 8px -2px rgba(0, 0, 0, 0.08);
    }
    .table-pesanan thead th.col-freeze-aksi {
        background: linear-gradient(135deg, #1f3a5c 0%, #1a304d 100%) !important;
        z-index: 6;
    }
    .table-pesanan tbody td.col-freeze-aksi {
        background-color: #ffffff;
    }
    .table-pesanan tbody tr:hover td.col-freeze-aksi {
        background-color: #f8fafc;
    }

    /* ACTION BUTTONS IN ROW PERSIS MODUL HASIL PRODUKSI MASUK */
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
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff !important;
        border-color: #059669;
    }
    .btn-action-status:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
    }
    .btn-action-print {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        color: #ffffff !important;
        border-color: #475569;
    }
    .btn-action-print:hover {
        background: linear-gradient(135deg, #475569 0%, #334155 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(71, 85, 105, 0.3);
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

    /* UNIFIED TOOLBAR & HEADER ACTION BUTTONS */
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

    /* BADGE STATUS */
    .badge-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.6rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.15s ease;
        line-height: 1.2;
    }
    .badge-status-pill:hover {
        transform: scale(1.04);
    }
    .badge-status-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        display: inline-block;
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

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-boxes-packing"
        title="Pesanan Marketplace"
        subtitle="Monitoring pesanan dari Shopee, Lazada, dan TikTok Shop dengan tracking status pengiriman real-time."
    />

    {{-- KPI STATS SUMMARY (4 CARDS) --}}
    <div class="row g-3 mb-3">
        {{-- Total Omzet Penjualan --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-indigo">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #eef2ff; color: #4f46e5;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Omzet Penjualan ({{ $periodeLabel }})</div>
                        <div class="kpi-value" style="color: #4f46e5;">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</div>
                        <div class="kpi-subtext">Hari ini: Rp {{ number_format($totalHariIni, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Potongan Marketplace --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-danger">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #fef2f2; color: #ef4444;">
                        <i class="fas fa-percent"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Potongan Marketplace</div>
                        <div class="kpi-value text-danger">Rp {{ number_format($totalAdminMarketplaceBulanIni, 0, ',', '.') }}</div>
                        <div class="kpi-subtext">Biaya admin, komisi &amp; promo</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dana Bersih Masuk Kas --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #059669;">Dana Bersih Masuk</div>
                        <div class="kpi-value" style="color: #059669;">Rp {{ number_format($totalBersihBulanIni, 0, ',', '.') }}</div>
                        <div class="kpi-subtext">Saldo riil yang diterima toko</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Transaksi Pesanan --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-info">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #f0f9ff; color: #0284c7;">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total Pesanan ({{ $periodeLabel }})</div>
                        <div class="kpi-value">{{ number_format($totalTransaksi) }} <small class="text-muted fw-normal" style="font-size: 0.8rem;">Order</small></div>
                        <div class="kpi-subtext">Shopee, Lazada &amp; TikTok</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MARKETPLACE BREAKDOWN STRIP --}}
    <div class="market-bar-modern">
        <span class="text-muted fw-bold small text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">
            <i class="fas fa-store me-1 text-primary"></i> Omzet per Marketplace ({{ $periodeLabel }}):
        </span>
        <div class="market-pill market-pill-shopee">
            <i class="fas fa-bag-shopping"></i> Shopee: <span class="fw-bold">Rp {{ number_format($omzetShopee, 0, ',', '.') }}</span>
        </div>
        <div class="market-pill market-pill-lazada">
            <i class="fas fa-cart-shopping"></i> Lazada: <span class="fw-bold">Rp {{ number_format($omzetLazada, 0, ',', '.') }}</span>
        </div>
        <div class="market-pill market-pill-tiktok">
            <i class="fas fa-store"></i> TikTok Shop: <span class="fw-bold">Rp {{ number_format($omzetTiktok, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- FILTER & SEARCH TOOLBAR (STRUKTUR PERSIS HASIL PRODUKSI MASUK) --}}
    @php
        $activeTrxFilterCount = 0;
        if (request()->filled('channel')) $activeTrxFilterCount++;
        if (request()->filled('status_pesanan')) $activeTrxFilterCount++;
        if (request()->filled('dari_tanggal')) $activeTrxFilterCount++;
        if (request()->filled('sampai_tanggal')) $activeTrxFilterCount++;
    @endphp

    <div class="card card-modern bg-white mb-3">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-stretch align-items-xl-center gap-2.5">
                {{-- Form Pencarian Cepat --}}
                <div class="flex-grow-1">
                    <form method="GET" action="{{ route('transaksi-penjualan.index') }}" class="m-0">
                        @if(request('channel'))
                            <input type="hidden" name="channel" value="{{ request('channel') }}">
                        @endif
                        @if(request('status_pesanan'))
                            <input type="hidden" name="status_pesanan" value="{{ request('status_pesanan') }}">
                        @endif
                        @if(request('dari_tanggal'))
                            <input type="hidden" name="dari_tanggal" value="{{ request('dari_tanggal') }}">
                        @endif
                        @if(request('sampai_tanggal'))
                            <input type="hidden" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}">
                        @endif

                        <div class="input-group search-input-group shadow-none">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-3 pe-2">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   class="form-control border-start-0 ps-1"
                                   placeholder="Cari No Pesanan, Resi, Pembeli, Kurir..."
                                   value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('transaksi-penjualan.index', request()->except('search')) }}" class="input-group-text bg-white text-muted text-decoration-none border-start-0 pe-3" title="Hapus pencarian">
                                    <i class="fas fa-times text-secondary"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Action Buttons: Filter, Reset, Import & Catat Pesanan --}}
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-filter-custom position-relative" data-bs-toggle="modal" data-bs-target="#modalFilterPesanan">
                        <i class="fas fa-filter"></i>
                        <span>Filter Data</span>
                        @if($activeTrxFilterCount > 0)
                            <span class="badge bg-white text-dark rounded-pill ms-1" style="font-size: 0.68rem; font-weight: 700; padding: 0.2rem 0.45rem;">{{ $activeTrxFilterCount }}</span>
                        @endif
                    </button>

                    @if(request()->anyFilled(['search', 'channel', 'status_pesanan', 'dari_tanggal', 'sampai_tanggal']))
                        <a href="{{ route('transaksi-penjualan.index') }}" class="btn btn-reset-custom" title="Hapus Semua Filter">
                            <i class="fas fa-redo"></i>
                            <span class="d-none d-sm-inline">Reset</span>
                        </a>
                    @endif

                    @can('penjualan-create')
                        <button type="button" class="btn btn-secondary-custom shadow-sm" data-bs-toggle="modal" data-bs-target="#modalImportMarketplace">
                            <i class="fas fa-file-excel text-success"></i>
                            <span>Import Laporan</span>
                        </button>
                        <a href="{{ route('transaksi-penjualan.create') }}" class="btn btn-primary-custom shadow-sm">
                            <i class="fas fa-plus"></i>
                            <span>Catat Pesanan Baru</span>
                        </a>
                    @endcan
                </div>
            </div>

            {{-- ACTIVE FILTER CHIPS --}}
            @if(request()->hasAny(['channel', 'status_pesanan', 'dari_tanggal', 'sampai_tanggal']))
            <div class="d-flex align-items-center gap-2 flex-wrap pt-2.5 mt-2.5 border-top">
                <span class="text-muted small fw-semibold" style="font-size: 0.72rem;">Filter Aktif:</span>

                @if(request('channel'))
                    <span class="filter-chip">
                        Marketplace: <b>{{ ucfirst(request('channel')) }}</b>
                        <a href="{{ route('transaksi-penjualan.index', request()->except('channel')) }}"><i class="fas fa-times"></i></a>
                    </span>
                @endif

                @if(request('status_pesanan'))
                    <span class="filter-chip">
                        Status: <b>{{ App\Models\Transaksi::STATUS_PESANAN[request('status_pesanan')] ?? request('status_pesanan') }}</b>
                        <a href="{{ route('transaksi-penjualan.index', request()->except('status_pesanan')) }}"><i class="fas fa-times"></i></a>
                    </span>
                @endif

                @if(request('dari_tanggal'))
                    <span class="filter-chip">
                        Dari: <b>{{ \Carbon\Carbon::parse(request('dari_tanggal'))->format('d/m/Y') }}</b>
                        <a href="{{ route('transaksi-penjualan.index', request()->except('dari_tanggal')) }}"><i class="fas fa-times"></i></a>
                    </span>
                @endif

                @if(request('sampai_tanggal'))
                    <span class="filter-chip">
                        Sampai: <b>{{ \Carbon\Carbon::parse(request('sampai_tanggal'))->format('d/m/Y') }}</b>
                        <a href="{{ route('transaksi-penjualan.index', request()->except('sampai_tanggal')) }}"><i class="fas fa-times"></i></a>
                    </span>
                @endif
            </div>
            @endif
        </div>
    </div>
        </div>
    </div>

    {{-- TABEL DATA TRANSAKSI PENJUALAN --}}
    <div class="card card-modern bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-pesanan align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th style="width: 170px;">Tanggal &amp; Kode TRX</th>
                            <th style="width: 250px;">Marketplace &amp; Resi</th>
                            <th style="width: 170px;">Pembeli</th>
                            <th style="width: 120px;">Item Produk</th>
                            <th class="text-end" style="width: 180px;">Total &amp; Bersih</th>
                            <th class="text-center" style="width: 150px;">Status Pesanan</th>
                            <th class="text-center col-freeze-aksi pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $index => $trx)
                        <tr>
                            <td class="ps-4 text-muted font-monospace small">
                                {{ $transaksis->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $trx->tanggal->translatedFormat('d M Y') }}</div>
                                <div class="small font-monospace text-primary">
                                    {{ $trx->kode_transaksi }}
                                </div>
                            </td>
                            <td>
                                @php
                                    $chBadge = $trx->channel_badge_config;
                                @endphp
                                <div class="mb-1">
                                    <span class="badge" style="background: {{ $chBadge['bg'] }}; color: {{ $chBadge['color'] }}; border: 1px solid {{ $chBadge['border'] }}; font-size: 0.75rem;">
                                        <i class="fas {{ $chBadge['icon'] }} me-1"></i>{{ $chBadge['label'] }}
                                    </span>
                                </div>
                                @if($trx->no_pesanan_marketplace)
                                    <div class="font-monospace small text-dark fw-medium" style="font-size: 0.78rem;">
                                        {{ $trx->no_pesanan_marketplace }}
                                    </div>
                                @endif
                                @if($trx->no_resi)
                                    <div class="text-muted small" style="font-size: 0.73rem;">
                                        <i class="fas fa-truck text-secondary me-1"></i>{{ $trx->kurir ? $trx->kurir . ' - ' : '' }}<span class="font-monospace text-dark fw-medium user-select-all">{{ $trx->no_resi }}</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $trx->nama_pelanggan ?: 'Pembeli Online' }}</div>
                                <div class="small text-muted font-monospace">
                                    <i class="fas fa-wallet text-muted me-1"></i>{{ $trx->metode_bayar_label }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="fas fa-box text-muted me-1"></i>{{ $trx->details->count() }} item
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="fw-bold text-dark" style="font-variant-numeric: tabular-nums;">
                                    Rp {{ number_format($trx->grand_total, 0, ',', '.') }}
                                </div>
                                @if($trx->biaya_admin_marketplace > 0)
                                    <div class="text-danger" style="font-size: 0.72rem;">
                                        Admin: -Rp {{ number_format($trx->biaya_admin_marketplace, 0, ',', '.') }}
                                    </div>
                                    <div class="text-success fw-semibold" style="font-size: 0.78rem;">
                                        Bersih: Rp {{ number_format($trx->penghasilan_bersih, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $statusBadge = $trx->status_pesanan_badge_config;
                                @endphp
                                <span class="badge-status-pill btn-trigger-status"
                                      data-id="{{ $trx->id }}"
                                      data-kode="{{ $trx->kode_transaksi }}"
                                      data-status="{{ $trx->status_pesanan }}"
                                      data-kurir="{{ $trx->kurir }}"
                                      data-resi="{{ $trx->no_resi }}"
                                      data-url="{{ route('transaksi-penjualan.update-status', $trx) }}"
                                      title="Klik untuk ubah status pesanan"
                                      style="background: {{ $statusBadge['bg'] }}; color: {{ $statusBadge['color'] }}; border: 1px solid {{ $statusBadge['border'] }};">
                                    <span class="badge-status-dot" style="background: {{ $statusBadge['dot'] }};"></span>
                                    <span>{{ $statusBadge['label'] }}</span>
                                    <i class="fas fa-pencil ms-1" style="font-size: 0.65rem; opacity: 0.6;"></i>
                                </span>
                            </td>
                            <td class="text-center col-freeze-aksi pe-4">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <a href="{{ route('transaksi-penjualan.show', $trx) }}" class="btn-action-icon btn-action-view" title="Lihat Detail Pesanan">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn-action-icon btn-action-status btn-trigger-status"
                                            data-id="{{ $trx->id }}"
                                            data-kode="{{ $trx->kode_transaksi }}"
                                            data-status="{{ $trx->status_pesanan }}"
                                            data-kurir="{{ $trx->kurir }}"
                                            data-resi="{{ $trx->no_resi }}"
                                            data-url="{{ route('transaksi-penjualan.update-status', $trx) }}"
                                            title="Update Status Pesanan">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <a href="{{ route('transaksi-penjualan.cetak-struk', $trx) }}" target="_blank" class="btn-action-icon btn-action-print" title="Cetak Packing Slip & Surat Jalan">
                                        <i class="fas fa-clipboard-check"></i>
                                    </a>
                                    @can('penjualan-delete')
                                        @if($trx->tanggal->isToday())
                                        <button type="button"
                                                class="btn-action-icon btn-action-delete btn-delete-trx"
                                                data-url="{{ route('transaksi-penjualan.destroy', $trx) }}"
                                                data-kode="{{ $trx->kode_transaksi }}"
                                                title="Hapus Pesanan">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="mb-3 text-muted" style="font-size: 2.2rem;">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <div class="fw-semibold text-dark mb-1">Tidak Ada Pesanan Ditemukan</div>
                                <div class="text-muted small mb-3">Tidak ada data pesanan marketplace yang cocok dengan filter.</div>
                                @can('penjualan-create')
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-secondary-custom shadow-sm" data-bs-toggle="modal" data-bs-target="#modalImportMarketplace">
                                        <i class="fas fa-file-excel text-success"></i>
                                        <span>Import Laporan</span>
                                    </button>
                                    <a href="{{ route('transaksi-penjualan.create') }}" class="btn btn-primary-custom shadow-sm">
                                        <i class="fas fa-plus"></i>
                                        <span>Catat Pesanan Baru</span>
                                    </a>
                                </div>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION RESMI SISTEM MODERN & BERWARNA --}}
        @if($transaksis->hasPages())
            <x-pagination :paginator="$transaksis" label="pesanan" />
        @endif
    </div>

</div>

{{-- MODAL QUICK UPDATE STATUS --}}
<div class="modal fade" id="modalUpdateStatus" tabindex="-1" aria-labelledby="modalUpdateStatusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.875rem;">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-sync-alt fa-lg"></i>
                    <h5 class="modal-title fw-bold fs-6 mb-0 text-white" id="modalUpdateStatusLabel">Update Status Pesanan</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" method="POST" id="formUpdateStatus">
                @csrf
                @method('PATCH')
                <div class="modal-body p-4">
                    <div class="p-3 rounded-3 mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Pesanan:</div>
                        <div class="fw-bold font-monospace text-primary fs-6" id="modalUpdateKode">-</div>
                    </div>

                    <div class="mb-3">
                        <label for="modal_status_pesanan" class="form-label fw-semibold text-dark small">
                            Status Pesanan <span class="text-danger">*</span>
                        </label>
                        <select name="status_pesanan" id="modal_status_pesanan" class="form-select" required>
                            @foreach(App\Models\Transaksi::STATUS_PESANAN as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted" style="font-size: 0.75rem;">
                            Pilih status proses saat ini untuk pesanan marketplace tersebut.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="modal_kurir" class="form-label fw-semibold text-dark small">Ekspedisi / Jasa Kirim</label>
                        <input type="text" name="kurir" id="modal_kurir" class="form-control" placeholder="Contoh: SPX Standard, J&T Express, JNE Regular">
                    </div>

                    <div class="mb-2">
                        <label for="modal_no_resi" class="form-label fw-semibold text-dark small">Nomor Resi Pengiriman</label>
                        <input type="text" name="no_resi" id="modal_no_resi" class="form-control font-monospace" placeholder="Nomor Resi / AWB">
                    </div>
                </div>

                <div class="modal-footer border-top py-2 px-4">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom" id="btnSubmitUpdateStatus">
                        <i class="fas fa-save me-1"></i>
                        <span>Simpan Status</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL FILTER PESANAN --}}
<div class="modal fade" id="modalFilterPesanan" tabindex="-1" aria-labelledby="modalFilterPesananLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.875rem;">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-sliders fa-lg"></i>
                    <h5 class="modal-title fw-bold fs-6 mb-0 text-white" id="modalFilterPesananLabel">Filter Lanjutan Pesanan</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="GET" action="{{ route('transaksi-penjualan.index') }}">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Marketplace</label>
                        <select name="channel" class="form-select">
                            <option value="">Semua Marketplace</option>
                            <option value="shopee" {{ request('channel') === 'shopee' ? 'selected' : '' }}>Shopee</option>
                            <option value="lazada" {{ request('channel') === 'lazada' ? 'selected' : '' }}>Lazada</option>
                            <option value="tiktok" {{ request('channel') === 'tiktok' ? 'selected' : '' }}>TikTok Shop</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Status Pesanan</label>
                        <select name="status_pesanan" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach(App\Models\Transaksi::STATUS_PESANAN as $key => $label)
                                <option value="{{ $key }}" {{ request('status_pesanan') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark small">Dari Tanggal</label>
                            <input type="date" name="dari_tanggal" class="form-control" value="{{ request('dari_tanggal') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark small">Sampai Tanggal</label>
                            <input type="date" name="sampai_tanggal" class="form-control" value="{{ request('sampai_tanggal') }}">
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top py-2 px-4">
                    <a href="{{ route('transaksi-penjualan.index') }}" class="btn btn-reset-custom">Reset Filter</a>
                    <button type="submit" class="btn btn-filter-custom">
                        <i class="fas fa-check me-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL IMPORT MARKETPLACE EXCEL --}}
<div class="modal fade" id="modalImportMarketplace" tabindex="-1" aria-labelledby="modalImportMarketplaceLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.875rem;">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-file-excel fa-lg text-success"></i>
                    <h5 class="modal-title fw-bold fs-6 mb-0 text-white" id="modalImportMarketplaceLabel">Import Laporan Pesanan Marketplace</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('transaksi-penjualan.import-shopee') }}" method="POST" enctype="multipart/form-data" id="formImportMarketplace">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="channel_import" class="form-label fw-semibold text-dark small">Pilih Marketplace</label>
                        <select name="channel" id="channel_import" class="form-select">
                            <option value="shopee">Shopee</option>
                            <option value="lazada">Lazada</option>
                            <option value="tiktok">TikTok Shop</option>
                        </select>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-3 border" style="font-size: 0.8rem;">
                        <div class="fw-bold text-dark mb-1">
                            <i class="fas fa-info-circle text-primary me-1"></i> Cara Unduh Laporan dari Seller Center:
                        </div>
                        <ul class="text-muted ps-3 mb-0" style="line-height: 1.6;">
                            <li><b>Shopee:</b> Seller Centre &gt; Pesanan Saya &gt; Export Pesanan.</li>
                            <li><b>Lazada:</b> Seller Center &gt; Orders &gt; Export Orders.</li>
                            <li><b>TikTok Shop:</b> Seller Center &gt; Orders &gt; Manage Orders &gt; Export.</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <label for="file_marketplace" class="form-label fw-semibold text-dark small">
                            Pilih File Excel / CSV (.xlsx, .xls, .csv) <span class="text-danger">*</span>
                        </label>
                        <input type="file" name="file_marketplace" id="file_marketplace" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <div class="text-muted mt-1" style="font-size: 0.75rem;">
                            Sistem akan otomatis mengenali nomor pesanan, SKU souvenir jadi, dan memotong stok gudang.
                        </div>
                    </div>

                    <div class="p-2 px-3 rounded-3 bg-light border" style="font-size: 0.78rem; color: #475569;">
                        <i class="fas fa-shield-halved text-success me-1"></i>
                        <span>Pesanan yang sudah pernah diimport sebelumnya akan otomatis dilewati untuk mencegah duplikasi.</span>
                    </div>
                </div>

                <div class="modal-footer border-top py-2 px-4">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom" id="btnSubmitImport">
                        <i class="fas fa-upload me-1"></i>
                        <span>Mulai Import</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Hidden form delete --}}
<form id="deleteTrxForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Alert Messages
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: @json(session('success')),
            confirmButtonColor: '#26476f',
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: @json(session('error')),
            confirmButtonColor: '#26476f'
        });
    @endif

    // 2. Loading state for Import Submit
    const formImport = document.getElementById('formImportMarketplace');
    const btnSubmit = document.getElementById('btnSubmitImport');
    if (formImport && btnSubmit) {
        formImport.addEventListener('submit', function() {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';
        });
    }

    // 3. Quick Update Status Modal
    const modalUpdateStatusEl = document.getElementById('modalUpdateStatus');
    const formUpdateStatus = document.getElementById('formUpdateStatus');
    const modalUpdateKode = document.getElementById('modalUpdateKode');
    const modalStatusPesanan = document.getElementById('modal_status_pesanan');
    const modalKurir = document.getElementById('modal_kurir');
    const modalNoResi = document.getElementById('modal_no_resi');

    document.querySelectorAll('.btn-trigger-status').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const kode = this.dataset.kode;
            const status = this.dataset.status;
            const kurir = this.dataset.kurir || '';
            const resi = this.dataset.resi || '';
            const url = this.dataset.url;

            if (formUpdateStatus) {
                formUpdateStatus.action = url;
            }
            if (modalUpdateKode) {
                modalUpdateKode.textContent = kode;
            }
            if (modalStatusPesanan) {
                modalStatusPesanan.value = status;
            }
            if (modalKurir) {
                modalKurir.value = kurir;
            }
            if (modalNoResi) {
                modalNoResi.value = resi;
            }

            const modal = new bootstrap.Modal(modalUpdateStatusEl);
            modal.show();
        });
    });

    // 4. Delete Confirmation
    document.querySelectorAll('.btn-delete-trx').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.dataset.url;
            const kode = this.dataset.kode;

            Swal.fire({
                title: 'Hapus Pesanan?',
                text: `Pesanan "${kode}" akan dihapus dan kuantitas stok souvenir jadi akan dikembalikan ke etalase toko.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteTrxForm');
                    form.action = url;
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
