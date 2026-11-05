@extends('layouts.app')

@section('title', 'Dashboard Monitoring & Forecasting')

@push('styles')
<style>
    /* =========================================================================
       1. EXECUTIVE CARD & KPI SYSTEM (KONSISTEN DENGAN KATALOG & LAPORAN)
       ========================================================================= */
    .card-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        overflow: hidden;
        margin-bottom: 0;
    }

    /* Pastikan card di dalam grid row tidak terkena margin-bottom bawaan AdminKit */
    .row > [class*="col-"] > .card-modern,
    .row > [class*="col-"] > .kpi-card-modern {
        margin-bottom: 0 !important;
    }

    .kpi-card-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 16px 18px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    .kpi-card-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.07);
    }

    .kpi-accent-indigo  { border-left: 4px solid #4f46e5; }
    .kpi-accent-danger  { border-left: 4px solid #ef4444; }
    .kpi-accent-success { border-left: 4px solid #10b981; }
    .kpi-accent-info    { border-left: 4px solid #0284c7; }
    .kpi-accent-warning { border-left: 4px solid #f59e0b; }
    .kpi-accent-purple  { border-left: 4px solid #7c3aed; }

    .kpi-top-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
    }

    .kpi-icon-wrap {
        width: 42px;
        height: 42px;
        border-radius: 0.55rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .kpi-card-modern .kpi-label {
        font-size: 0.7rem !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 4px !important;
        line-height: 1.3;
    }

    .kpi-card-modern .kpi-value {
        font-size: 1.2rem !important;
        font-weight: 800 !important;
        color: #0f172a;
        line-height: 1.2;
        margin-bottom: 0 !important;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.02em;
    }

    .kpi-footer-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding-top: 10px;
        margin-top: 10px;
        border-top: 1px dashed #e2e8f0;
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 500;
    }

    .kpi-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 999px;
        font-size: 0.68rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .kpi-pill-success { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .kpi-pill-danger  { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .kpi-pill-info    { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }
    .kpi-pill-purple  { background: #f3e8ff; color: #7c3aed; border: 1px solid #ddd6fe; }

    /* =========================================================================
       2. UNIFIED TABS & ACTION BUTTONS (UKURAN & JARAK 100% SERAGAM 38PX)
       ========================================================================= */
    .dash-toolbar-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .dash-tabs-group {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
    }

    .dash-actions-group {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
    }

    .dash-tab-btn,
    .btn-primary-custom,
    .btn-secondary-custom,
    .btn-filter-custom,
    .btn-reset-custom {
        height: 38px !important;
        min-height: 38px !important;
        padding: 0 16px !important;
        font-size: 0.8125rem !important;
        font-weight: 600 !important;
        border-radius: 0.5rem !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        white-space: nowrap !important;
        text-decoration: none !important;
        line-height: 1 !important;
        box-sizing: border-box !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
    }

    .dash-tab-btn {
        background: #f8fafc !important;
        color: #475569 !important;
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03) !important;
    }
    .dash-tab-btn:hover {
        background: #f1f5f9 !important;
        color: #0f172a !important;
        border-color: #94a3b8 !important;
        transform: translateY(-1px);
    }
    .dash-tab-btn.active {
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%) !important;
        color: #ffffff !important;
        border-color: #1a304d !important;
        box-shadow: 0 2px 6px rgba(38, 71, 111, 0.28) !important;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%) !important;
        border: 1px solid #1a304d !important;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(38, 71, 111, 0.25) !important;
    }
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #1f3a5c 0%, #14243b 100%) !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    .btn-secondary-custom {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%) !important;
        border: 1px solid #475569 !important;
        color: #ffffff !important;
    }
    .btn-secondary-custom:hover {
        background: linear-gradient(135deg, #475569 0%, #334155 100%) !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    .btn-filter-custom {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
        border: 1px solid #3730a3 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(79, 70, 229, 0.25) !important;
    }
    .btn-filter-custom:hover {
        background: linear-gradient(135deg, #4338ca 0%, #312e81 100%) !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    .btn-reset-custom {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        border: 1px solid #dc2626 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.25) !important;
    }
    .btn-reset-custom:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    /* Segmented Mode Buttons di Header Grafik */
    .chart-mode-group {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 0.55rem;
        border: 1px solid #e2e8f0;
    }
    .chart-mode-btn {
        height: 32px !important;
        padding: 0 12px !important;
        font-size: 0.76rem !important;
        font-weight: 600 !important;
        border-radius: 0.4rem !important;
        border: 1px solid transparent !important;
        background: transparent !important;
        color: #475569 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        white-space: nowrap !important;
    }
    .chart-mode-btn:hover {
        color: #0f172a !important;
        background: rgba(255, 255, 255, 0.6) !important;
    }
    .chart-mode-btn.active {
        background: #ffffff !important;
        color: #26476f !important;
        border-color: #cbd5e1 !important;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08) !important;
    }

    /* =========================================================================
       3. CARD HEADER, METRIC STRIP & FILTER CHIPS
       ========================================================================= */
    .dash-chips-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid #e2e8f0;
    }

    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
        padding: 5px 12px;
        border-radius: 2rem;
        font-size: 0.73rem;
        font-weight: 600;
        line-height: 1.2;
    }

    .card-header-clean {
        background: #ffffff;
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .card-header-title-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .header-icon-badge {
        width: 38px;
        height: 38px;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    /* Sub-header Ringkasan Metrik di Atas Canvas Grafik */
    .chart-summary-strip {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .chart-summary-item {
        padding: 12px 20px;
        border-right: 1px solid #e2e8f0;
    }
    .chart-summary-item:last-child {
        border-right: none;
    }
    .chart-summary-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 3px;
    }
    .chart-summary-value {
        font-size: 0.95rem;
        font-weight: 800;
        color: #0f172a;
        font-variant-numeric: tabular-nums;
    }
    .chart-summary-sub {
        font-size: 0.68rem;
        color: #64748b;
        margin-top: 2px;
    }
    @media (max-width: 991.98px) {
        .chart-summary-strip {
            grid-template-columns: repeat(2, 1fr);
        }
        .chart-summary-item:nth-child(2) {
            border-right: none;
        }
        .chart-summary-item:nth-child(1),
        .chart-summary-item:nth-child(2) {
            border-bottom: 1px solid #e2e8f0;
        }
    }
    @media (max-width: 575.98px) {
        .chart-summary-strip {
            grid-template-columns: 1fr;
        }
        .chart-summary-item {
            border-right: none !important;
            border-bottom: 1px solid #e2e8f0;
        }
        .chart-summary-item:last-child {
            border-bottom: none;
        }
    }

    /* Marketplace Card Metrics Grid */
    .mp-metrics-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 10px 12px;
        margin-top: 12px;
    }
    .mp-metric-box {
        min-width: 0;
    }
    .mp-metric-label {
        font-size: 0.66rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 2px;
    }
    .mp-metric-val {
        font-size: 0.8rem;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* =========================================================================
       4. TABLE NAVY GRADIENT & FREEZE AKSI (PERSIS KATALOG SOUVENIR)
       ========================================================================= */
    .table-produk {
        width: 100%;
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
        letter-spacing: 0.05em;
        border-bottom: none !important;
        padding: 12px 16px;
        white-space: nowrap;
    }
    .table-produk tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.8125rem;
        background-color: #ffffff;
        color: #334155;
    }
    .table-produk tbody tr:hover td {
        background-color: #f8fafc;
    }

    .col-freeze-aksi {
        position: sticky !important;
        right: 0 !important;
        z-index: 5;
        min-width: 90px;
        box-shadow: -4px 0 8px -2px rgba(0, 0, 0, 0.06);
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

    .btn-action-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.45rem;
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: #ffffff !important;
        border: 1px solid #0284c7;
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .btn-action-icon:hover {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        transform: translateY(-1px);
    }

    .badge-kode {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.725rem;
        font-weight: 700;
        padding: 0.2rem 0.5rem;
        border-radius: 0.375rem;
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        display: inline-block;
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

    {{-- 1. HEADER STANDAR KATALOG SOUVENIR --}}
    <x-page-header
        icon="fas fa-chart-pie"
        title="Dashboard Monitoring & Predictive Forecasting"
        subtitle="Ringkasan eksekutif performa 3 Marketplace (Shopee, TikTok Shop, Lazada), QC Produksi Gudang, Arus Kas, dan Proyeksi Bisnis Berbasis Time-Series."
    />

    {{-- 2. EMPAT KPI UTAMA (DESAIN EKSEKUTIF, RAPIH, TIDAK TERPOTONG) --}}
    <div class="row g-3 mb-3">
        {{-- KPI 1: Penghasilan Bersih Penjualan MP --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-success">
                <div class="kpi-top-row">
                    <div class="min-w-0">
                        <div class="kpi-label" style="color: #059669;">Penghasilan Bersih MP</div>
                        <div class="kpi-value text-success">Rp {{ number_format($pendapatanEfektif, 0, ',', '.') }}</div>
                    </div>
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                        <i class="fas fa-sack-dollar"></i>
                    </div>
                </div>
                <div class="kpi-footer-row">
                    <span>Omzet: <strong>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</strong></span>
                    <span class="kpi-pill kpi-pill-success">{{ $jumlahTransaksi }} Pesanan</span>
                </div>
            </div>
        </div>

        {{-- KPI 2: Total Belanja Bahan (HPP) & Biaya Bisnis --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-info">
                <div class="kpi-top-row">
                    <div class="min-w-0">
                        <div class="kpi-label" style="color: #0284c7;">Belanja Bahan &amp; Biaya</div>
                        <div class="kpi-value text-primary">Rp {{ number_format($totalPembelian + $totalPengeluaran, 0, ',', '.') }}</div>
                    </div>
                    <div class="kpi-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-truck-ramp-box"></i>
                    </div>
                </div>
                <div class="kpi-footer-row">
                    <span>HPP: <strong>Rp {{ number_format($totalPembelian, 0, ',', '.') }}</strong></span>
                    <span class="kpi-pill kpi-pill-info">Ads/Ops: Rp {{ number_format($totalPengeluaran / 1000, 0, ',', '.') }}rb</span>
                </div>
            </div>
        </div>

        {{-- KPI 3: Laba Bersih Bisnis --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern {{ $labaBersih >= 0 ? 'kpi-accent-indigo' : 'kpi-accent-danger' }}">
                <div class="kpi-top-row">
                    <div class="min-w-0">
                        <div class="kpi-label">Laba Bersih ({{ $namaBulanIni }})</div>
                        <div class="kpi-value {{ $labaBersih >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($labaBersih, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="kpi-icon-wrap" style="background: {{ $labaBersih >= 0 ? '#eef2ff' : '#fef2f2' }}; color: {{ $labaBersih >= 0 ? '#4f46e5' : '#dc2626' }};">
                        <i class="fas fa-{{ $labaBersih >= 0 ? 'chart-line' : 'arrow-trend-down' }}"></i>
                    </div>
                </div>
                <div class="kpi-footer-row">
                    <span>Margin Bersih: <strong>{{ $marginBersih }}%</strong></span>
                    <span class="kpi-pill {{ $persenLaba >= 0 ? 'kpi-pill-success' : 'kpi-pill-danger' }}">
                        <i class="fas fa-caret-{{ $persenLaba >= 0 ? 'up' : 'down' }}"></i>
                        {{ $persenLaba >= 0 ? '+' : '' }}{{ number_format($persenLaba, 1) }}% MoM
                    </span>
                </div>
            </div>
        </div>

        {{-- KPI 4: Proyeksi Bulan Depan (Forecasting) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-purple">
                <div class="kpi-top-row">
                    <div class="min-w-0">
                        <div class="kpi-label" style="color: #7c3aed;">Forecast {{ $forecastSummary['next_month_label'] }}</div>
                        <div class="kpi-value" style="color: #6d28d9;">Rp {{ number_format($forecastSummary['next_omzet'], 0, ',', '.') }}</div>
                    </div>
                    <div class="kpi-icon-wrap" style="background: #f3e8ff; color: #7c3aed;">
                        <i class="fas fa-wand-magic-sparkles"></i>
                    </div>
                </div>
                <div class="kpi-footer-row">
                    <span>Est. Laba: <strong>Rp {{ number_format($forecastSummary['next_laba'], 0, ',', '.') }}</strong></span>
                    <span class="kpi-pill kpi-pill-purple">Akurasi {{ number_format($forecastSummary['accuracy'], 1) }}%</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. UNIFIED TOOLBAR & TABS (SEMUA BUTTON DAN TAB TINGGI 38PX & JARAK 8PX PRESISI) --}}
    @php
        $activeFilterCount = 0;
        if (request()->filled('bulan')) $activeFilterCount++;
        if (request()->filled('tahun')) $activeFilterCount++;
        if (request()->filled('horizon') && (int) request('horizon') !== 3) $activeFilterCount++;
    @endphp

    <div class="card card-modern bg-white mb-3">
        <div class="card-body" style="padding: 16px 20px;">
            <div class="dash-toolbar-row">
                {{-- Kiri: 4 Tab Button Seragam 38px dengan Jarak 8px --}}
                <div class="dash-tabs-group" role="tablist">
                    <button type="button" class="dash-tab-btn active" data-dash-target="pane-forecast">
                        <i class="fas fa-wand-magic-sparkles"></i>
                        <span>Tren &amp; Forecasting</span>
                    </button>
                    <button type="button" class="dash-tab-btn" data-dash-target="pane-marketplace">
                        <i class="fas fa-store"></i>
                        <span>3 Marketplace &amp; Produk</span>
                    </button>
                    <button type="button" class="dash-tab-btn" data-dash-target="pane-operasional">
                        <i class="fas fa-industry"></i>
                        <span>Produksi, Gudang &amp; Kas</span>
                    </button>
                    <button type="button" class="dash-tab-btn" data-dash-target="pane-aktivitas">
                        <i class="fas fa-clock-rotate-left"></i>
                        <span>Transaksi Terbaru</span>
                    </button>
                </div>

                {{-- Kanan: Action Buttons Seragam 38px dengan Jarak 8px --}}
                <div class="dash-actions-group">
                    <button type="button" id="btnBukaFilterDashboard" class="btn-filter-custom" data-bs-toggle="modal" data-bs-target="#modalFilterDashboard">
                        <i class="fas fa-filter"></i>
                        <span>Filter Data</span>
                        @if($activeFilterCount > 0)
                            <span class="badge bg-white text-dark rounded-pill" style="font-size: 0.68rem; font-weight: 700; padding: 0.2rem 0.45rem;">{{ $activeFilterCount }}</span>
                        @endif
                    </button>

                    @if($activeFilterCount > 0)
                        <a href="{{ route('dashboard') }}" class="btn-reset-custom" title="Hapus Semua Filter">
                            <i class="fas fa-redo"></i>
                            <span>Reset</span>
                        </a>
                    @endif

                    @canany(['laporan-view', 'laporan-laba-rugi', 'laporan-penjualan'])
                        <a href="{{ route('laporan.keuangan') }}" class="btn-primary-custom">
                            <i class="fas fa-file-chart-column"></i>
                            <span>Laporan Lengkap</span>
                        </a>
                    @endcanany
                </div>
            </div>

            {{-- Baris Info & Filter Aktif dengan Jarak Atas 14px dan Gap 8px --}}
            <div class="dash-chips-row">
                <span class="small text-muted fw-bold" style="font-size: 0.73rem; margin-right: 4px;">Periode &amp; Parameter Aktif:</span>

                <span class="filter-chip" style="background: #f8fafc; color: #334155; border-color: #cbd5e1;">
                    <i class="fas fa-calendar-check text-primary"></i>
                    <span>Bulan Evaluasi: <strong>{{ $namaBulanIni }}</strong></span>
                </span>

                <span class="filter-chip">
                    <i class="fas fa-wand-magic-sparkles"></i>
                    <span>Horizon Forecasting: <strong>+{{ $forecastHorizon }} Bulan ke Depan</strong></span>
                </span>

                <span class="filter-chip" style="background: #ecfdf5; color: #047857; border-color: #a7f3d0;">
                    <i class="fas fa-bullseye"></i>
                    <span>Akurasi Model: <strong>{{ number_format($forecastSummary['accuracy'], 1) }}%</strong> (MAPE {{ number_format($forecastSummary['mape'], 1) }}%)</span>
                </span>

                <span class="filter-chip" style="background: #f8fafc; color: #334155; border-color: #cbd5e1;">
                    <i class="fas fa-industry text-success"></i>
                    <span>QC Produksi: <strong>+{{ number_format($totalProduksiBagus) }} Lolos ({{ $tingkatLolosQc }}%)</strong></span>
                </span>
            </div>
        </div>
    </div>

    {{-- =====================================================================
         TAB PANE 1: TREN KEUANGAN 12 BULAN & TEKNOLOGI FORECASTING (DEFAULT)
         ===================================================================== --}}
    <div id="pane-forecast" class="dash-tab-pane">
        {{-- Card Grafik Utama dengan Summary Strip --}}
        <div class="card card-modern bg-white mb-3">
            <div class="card-header-clean">
                <div class="card-header-title-wrap">
                    <span class="header-icon-badge" style="background: #eef2ff; color: #26476f;">
                        <i class="fas fa-chart-line"></i>
                    </span>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.92rem;">
                            Grafik Tren Historis (12 Bulan) &amp; Proyeksi Time-Series Forecasting (+{{ $forecastHorizon }} Bulan)
                        </h6>
                        <div class="text-muted" style="font-size: 0.74rem; margin-top: 2px;">
                            Garis utuh = Aktual Historis &bull; Area berarsir kanan = Zona Proyeksi Holt’s Smoothing &amp; Confidence Interval 90%
                        </div>
                    </div>
                </div>

                {{-- Segmented Control Mode Grafik --}}
                <div class="chart-mode-group">
                    <button type="button" class="chart-mode-btn active" data-chart-mode="utama">
                        <i class="fas fa-layer-group"></i>
                        <span>Omzet &amp; Laba</span>
                    </button>
                    <button type="button" class="chart-mode-btn" data-chart-mode="omzet">
                        <i class="fas fa-bullseye"></i>
                        <span>Fokus Omzet (90% CI)</span>
                    </button>
                    <button type="button" class="chart-mode-btn" data-chart-mode="lengkap">
                        <i class="fas fa-sliders"></i>
                        <span>Semua Metrik</span>
                    </button>
                </div>
            </div>

            {{-- Executive Summary Strip di Bawah Header Grafik --}}
            <div class="chart-summary-strip">
                <div class="chart-summary-item">
                    <div class="chart-summary-label">Metode Forecasting</div>
                    <div class="chart-summary-value" style="font-size: 0.85rem; color: #26476f;">Holt’s Double Exponential</div>
                    <div class="chart-summary-sub">Parameter &alpha;=0.45, &beta;=0.25, &phi;=0.90</div>
                </div>
                <div class="chart-summary-item">
                    <div class="chart-summary-label">Target Omzet ({{ $forecastSummary['next_month_label'] }})</div>
                    <div class="chart-summary-value text-success">Rp {{ number_format($forecastSummary['next_omzet'], 0, ',', '.') }}</div>
                    <div class="chart-summary-sub">Bersih: Rp {{ number_format($forecastSummary['next_bersih'], 0, ',', '.') }}</div>
                </div>
                <div class="chart-summary-item">
                    <div class="chart-summary-label">Proyeksi Laba ({{ $forecastSummary['next_month_label'] }})</div>
                    <div class="chart-summary-value" style="color: #4f46e5;">Rp {{ number_format($forecastSummary['next_laba'], 0, ',', '.') }}</div>
                    <div class="chart-summary-sub">Momentum: {{ ucfirst($forecastSummary['trend_direction']) }} ({{ $forecastSummary['growth_rate'] >= 0 ? '+' : '' }}{{ $forecastSummary['growth_rate'] }}%/bln)</div>
                </div>
                <div class="chart-summary-item">
                    <div class="chart-summary-label">Rentang Kepercayaan 90% CI</div>
                    <div class="chart-summary-value" style="font-size: 0.85rem; color: #0f172a;">
                        Rp {{ number_format(($forecastTable[0]['omzet_min'] ?? 0) / 1000000, 1, ',', '.') }}jt &ndash; Rp {{ number_format(($forecastTable[0]['omzet_max'] ?? 0) / 1000000, 1, ',', '.') }}jt
                    </div>
                    <div class="chart-summary-sub">Akurasi Model Historis: <strong>{{ number_format($forecastSummary['accuracy'], 1) }}%</strong></div>
                </div>
            </div>

            <div class="card-body" style="padding: 20px 20px 16px;">
                <div style="position: relative; height: 340px; width: 100%;">
                    <canvas id="chartTrenForecast"></canvas>
                </div>
            </div>
        </div>

        {{-- Card Tabel Matriks Hasil Forecasting --}}
        <div class="card card-modern bg-white">
            <div class="card-header-clean">
                <div class="card-header-title-wrap">
                    <span class="header-icon-badge" style="background: #f3e8ff; color: #7c3aed;">
                        <i class="fas fa-table"></i>
                    </span>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.92rem;">
                            Tabel Matriks Proyeksi Keuangan {{ $forecastHorizon }} Bulan ke Depan
                        </h6>
                        <div class="text-muted" style="font-size: 0.74rem; margin-top: 2px;">
                            Rincian proyeksi Omzet Kotor, Penghasilan Bersih Seller, Belanja Bahan (HPP), Biaya Bisnis &amp; Estimasi Laba Bersih
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-produk align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Bulan Proyeksi</th>
                                <th class="text-end">Prediksi Omzet Kotor</th>
                                <th class="text-center">Rentang Kepercayaan (90% CI)</th>
                                <th class="text-end">Prediksi Penghasilan Bersih</th>
                                <th class="text-end">Est. Belanja Bahan (HPP)</th>
                                <th class="text-end">Est. Biaya Bisnis &amp; Ads</th>
                                <th class="text-end">Prediksi Laba Bersih</th>
                                <th class="text-center pe-4">Est. Margin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($forecastTable as $fcRow)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">
                                        <i class="fas fa-calendar-plus text-primary me-2"></i>{{ $fcRow['periode'] }}
                                    </td>
                                    <td class="text-end fw-bold font-monospace text-dark">
                                        Rp {{ number_format($fcRow['omzet'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.72rem; padding: 5px 10px;">
                                            Rp {{ number_format($fcRow['omzet_min'], 0, ',', '.') }} &ndash; Rp {{ number_format($fcRow['omzet_max'], 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold font-monospace text-success">
                                        Rp {{ number_format($fcRow['bersih'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-end font-monospace" style="color: #0284c7;">
                                        Rp {{ number_format($fcRow['pembelian'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-end font-monospace" style="color: #d97706;">
                                        Rp {{ number_format($fcRow['pengeluaran'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-bold font-monospace {{ $fcRow['laba'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($fcRow['laba'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-4">
                                        <span class="badge {{ $fcRow['margin'] >= 15 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' }}" style="padding: 5px 10px;">
                                            {{ $fcRow['margin'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- =====================================================================
         TAB PANE 2: ANALITIK 3 MARKETPLACE & DEMAND FORECASTING PRODUK
         ===================================================================== --}}
    <div id="pane-marketplace" class="dash-tab-pane d-none">
        {{-- Baris Ringkasan 3 Channel Marketplace (Grid 3 Kolom Proporsional) --}}
        <div class="row g-3 mb-3">
            @foreach($channelStats as $chKey => $ch)
                @php
                    $accentClass = $chKey === 'shopee' ? 'kpi-accent-warning' : ($chKey === 'tiktok' ? 'kpi-accent-indigo' : 'kpi-accent-info');
                    $iconBg      = $chKey === 'shopee' ? '#fff7ed' : ($chKey === 'tiktok' ? '#f1f5f9' : '#eff6ff');
                    $iconColor   = $chKey === 'shopee' ? '#ea580c' : ($chKey === 'tiktok' ? '#0f172a' : '#2563eb');
                    $barColor    = $chKey === 'shopee' ? '#f97316' : ($chKey === 'tiktok' ? '#334155' : '#2563eb');
                @endphp
                <div class="col-12 col-md-4">
                    <div class="kpi-card-modern {{ $accentClass }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center" style="gap: 12px;">
                                <div class="kpi-icon-wrap" style="background: {{ $iconBg }}; color: {{ $iconColor }}; width: 40px; height: 40px; font-size: 1rem;">
                                    <i class="fas fa-bag-shopping"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 0.92rem;">{{ $ch['label'] }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">{{ $ch['transaksi'] }} pesanan aktif / selesai</div>
                                </div>
                            </div>
                            <span class="kpi-pill kpi-pill-info">{{ $ch['share'] }}% Pangsa</span>
                        </div>

                        {{-- Progress Bar Pangsa Omzet --}}
                        <div class="progress mt-3" style="height: 5px; background: #f1f5f9; border-radius: 999px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ max(4, $ch['share']) }}%; background: {{ $barColor }}; border-radius: 999px;"></div>
                        </div>

                        {{-- 3-Column Mini Metrics Box (Anti Bertumpuk) --}}
                        <div class="mp-metrics-grid">
                            <div class="mp-metric-box">
                                <div class="mp-metric-label">Omzet Kotor</div>
                                <div class="mp-metric-val text-dark">Rp {{ number_format($ch['omzet'], 0, ',', '.') }}</div>
                            </div>
                            <div class="mp-metric-box">
                                <div class="mp-metric-label">Dana Bersih</div>
                                <div class="mp-metric-val text-success">Rp {{ number_format($ch['bersih'], 0, ',', '.') }}</div>
                            </div>
                            <div class="mp-metric-box">
                                <div class="mp-metric-label">Forecast Depan</div>
                                <div class="mp-metric-val" style="color: #6d28d9;">Rp {{ number_format($ch['forecast_next'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row g-3">
            {{-- Grafik Komparasi 3 Marketplace --}}
            <div class="col-12 col-xl-5">
                <div class="card card-modern bg-white h-100">
                    <div class="card-header-clean">
                        <div class="card-header-title-wrap">
                            <span class="header-icon-badge" style="background: #fff7ed; color: #ea580c;">
                                <i class="fas fa-store"></i>
                            </span>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Komparasi &amp; Prediksi 3 Marketplace</h6>
                                <div class="text-muted" style="font-size: 0.73rem; margin-top: 2px;">Omzet Kotor vs Penghasilan Bersih vs Forecast</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <div style="position: relative; height: 290px; width: 100%;">
                            <canvas id="chartMarketplace"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Top 5 Produk Terlaris & Demand Forecasting --}}
            <div class="col-12 col-xl-7">
                <div class="card card-modern bg-white h-100">
                    <div class="card-header-clean">
                        <div class="card-header-title-wrap">
                            <span class="header-icon-badge" style="background: #fef9c3; color: #ca8a04;">
                                <i class="fas fa-trophy"></i>
                            </span>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Top 5 Souvenir Terlaris &amp; Demand Forecasting</h6>
                                <div class="text-muted" style="font-size: 0.73rem; margin-top: 2px;">Prediksi permintaan &amp; saran jumlah produksi bulan depan</div>
                            </div>
                        </div>
                        @can('produk-list')
                            <a href="{{ route('produk.index') }}" class="btn-primary-custom">
                                <i class="fas fa-gift"></i>
                                <span>Katalog Souvenir</span>
                            </a>
                        @endcan
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-produk align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3" style="width: 45px;">#</th>
                                        <th>Produk Souvenir</th>
                                        <th class="text-center">Terjual</th>
                                        <th class="text-end">Omzet</th>
                                        <th class="text-center">Stok</th>
                                        <th class="text-center">Forecast Qty</th>
                                        <th class="text-center pe-3">Saran Produksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProduk as $idx => $tp)
                                        <tr>
                                            <td class="ps-3 fw-bold text-muted">{{ $idx + 1 }}</td>
                                            <td class="fw-semibold text-dark">{{ $tp->nama_produk }}</td>
                                            <td class="text-center font-monospace fw-bold">
                                                {{ number_format($tp->total_qty, 0, ',', '.') }} {{ $tp->satuan }}
                                            </td>
                                            <td class="text-end font-monospace text-success fw-semibold">
                                                Rp {{ number_format($tp->total_omzet, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center font-monospace">
                                                {{ number_format($tp->stok_saat_ini, 0, ',', '.') }} {{ $tp->satuan }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace" style="padding: 5px 10px;">
                                                    {{ number_format($tp->forecast_qty, 0, ',', '.') }} {{ $tp->satuan }}
                                                </span>
                                            </td>
                                            <td class="text-center pe-3">
                                                @if($tp->rekomendasi_produksi > 0)
                                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle fw-bold font-monospace" style="padding: 5px 10px;">
                                                        +{{ number_format($tp->rekomendasi_produksi, 0, ',', '.') }} {{ $tp->satuan }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="padding: 5px 10px;">
                                                        Stok Cukup
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data penjualan pada periode ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =====================================================================
         TAB PANE 3: PRODUKSI QC, GUDANG BAHAN BAKU & REKONSILIASI KAS
         ===================================================================== --}}
    <div id="pane-operasional" class="dash-tab-pane d-none">
        {{-- Baris 1: Grafik Produksi 12 Bulan (7 Kolom) & Struktur Arus Kas/Modal (5 Kolom) --}}
        <div class="row g-3 mb-3">
            <div class="col-12 col-xl-7">
                <div class="card card-modern bg-white h-100">
                    <div class="card-header-clean">
                        <div class="card-header-title-wrap">
                            <span class="header-icon-badge" style="background: #ecfdf5; color: #059669;">
                                <i class="fas fa-industry"></i>
                            </span>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Tren Produksi Souvenir (Lolos QC vs Reject) &amp; Pemakaian Bahan</h6>
                                <div class="text-muted" style="font-size: 0.73rem; margin-top: 2px;">Pass Rate QC: <strong>{{ $tingkatLolosQc }}%</strong> (+{{ number_format($totalProduksiBagus) }} Lolos / {{ $totalProduksiReject }} Reject)</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <div style="position: relative; height: 280px; width: 100%;">
                            <canvas id="chartProduksiBahan"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">
                <div class="card card-modern bg-white h-100">
                    <div class="card-header-clean">
                        <div class="card-header-title-wrap">
                            <span class="header-icon-badge" style="background: #e0f2fe; color: #0284c7;">
                                <i class="fas fa-scale-balanced"></i>
                            </span>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Struktur Arus Kas, Modal Owner, Prive &amp; Kasbon</h6>
                                <div class="text-muted" style="font-size: 0.73rem; margin-top: 2px;">Modal Disetor: Rp {{ number_format($totalModalDisetor, 0, ',', '.') }} &bull; Kasbon Aktif: Rp {{ number_format($totalKasbonAktif, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <div style="position: relative; height: 280px; width: 100%;">
                            <canvas id="chartArusKasModal"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Baris 2: Distribusi Biaya Bisnis (6 Kolom) & Distribusi Kategori Katalog (6 Kolom) --}}
        <div class="row g-3 mb-3">
            <div class="col-12 col-xl-6">
                <div class="card card-modern bg-white h-100">
                    <div class="card-header-clean">
                        <div class="card-header-title-wrap">
                            <span class="header-icon-badge" style="background: #fffbeb; color: #d97706;">
                                <i class="fas fa-credit-card"></i>
                            </span>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Distribusi Biaya Operasional &amp; Ads per Kategori</h6>
                                <div class="text-muted" style="font-size: 0.73rem; margin-top: 2px;">Komposisi pengeluaran bisnis pada bulan {{ $namaBulanIni }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <div style="position: relative; height: 250px; width: 100%;">
                            <canvas id="chartBiayaKategori"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="card card-modern bg-white h-100">
                    <div class="card-header-clean">
                        <div class="card-header-title-wrap">
                            <span class="header-icon-badge" style="background: #eef2ff; color: #4f46e5;">
                                <i class="fas fa-tags"></i>
                            </span>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Distribusi Kategori Item Gudang ({{ $totalProduk }} SKU)</h6>
                                <div class="text-muted" style="font-size: 0.73rem; margin-top: 2px;">{{ $totalSouvenirJadi }} Produk Souvenir Jadi &amp; {{ $totalBahanBaku }} Item Bahan Baku</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <div style="position: relative; height: 250px; width: 100%;">
                            <canvas id="chartKategori"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Baris 3: Tabel Peringatan Stok Menipis Full Width --}}
        <div class="card card-modern bg-white">
            <div class="card-header-clean">
                <div class="card-header-title-wrap">
                    <span class="header-icon-badge" style="background: #fef2f2; color: #dc2626;">
                        <i class="fas fa-triangle-exclamation"></i>
                    </span>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Peringatan Stok Menipis (Souvenir &amp; Bahan Baku)</h6>
                        <div class="text-muted" style="font-size: 0.73rem; margin-top: 2px;">Daftar SKU yang mencapai atau berada di bawah ambang stok minimal gudang</div>
                    </div>
                </div>
                @can('bahan-baku-list')
                    <a href="{{ route('bahan-baku.index', ['stok_status' => 'menipis']) }}" class="btn-primary-custom">
                        <i class="fas fa-layer-group"></i>
                        <span>Cek Stok Bahan Baku</span>
                    </a>
                @endcan
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-produk align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Kode SKU</th>
                                <th>Nama Item</th>
                                <th>Kategori</th>
                                <th class="text-center">Tipe Item</th>
                                <th class="text-center">Sisa Stok</th>
                                <th class="text-center pe-4">Batas Minimal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produkStokMenipis as $psm)
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge-kode">{{ $psm->kode_produk }}</span>
                                    </td>
                                    <td class="fw-semibold text-dark">{{ $psm->nama_produk }}</td>
                                    <td class="text-muted">{{ $psm->kategori->nama_kategori ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($psm->jenis === 'bahan_baku')
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle" style="padding: 5px 10px;">Bahan Baku</span>
                                        @else
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="padding: 5px 10px;">Souvenir Jadi</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($psm->stok <= 0)
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-bold" style="padding: 5px 10px;">Habis (0)</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle fw-bold font-monospace" style="padding: 5px 10px;">
                                                {{ number_format($psm->stok) }} {{ $psm->satuan->singkatan ?? 'pcs' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-4 font-monospace text-muted">{{ number_format($psm->stok_minimal) }} {{ $psm->satuan->singkatan ?? 'pcs' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-success fw-semibold">
                                        <i class="fas fa-circle-check me-1"></i> Seluruh stok Souvenir &amp; Bahan Baku berada di level aman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- =====================================================================
         TAB PANE 4: AKTIVITAS TRANSAKSI TERBARU (TABEL LEBAR & RAPIH)
         ===================================================================== --}}
    <div id="pane-aktivitas" class="dash-tab-pane d-none">
        <div class="row g-3">
            {{-- Pesanan Marketplace Terbaru --}}
            <div class="col-12 col-xl-6">
                <div class="card card-modern bg-white h-100">
                    <div class="card-header-clean">
                        <div class="card-header-title-wrap">
                            <span class="header-icon-badge" style="background: #eef2ff; color: #26476f;">
                                <i class="fas fa-boxes-packing"></i>
                            </span>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">5 Pesanan Marketplace Terbaru</h6>
                                <div class="text-muted" style="font-size: 0.73rem; margin-top: 2px;">Transaksi penjualan Shopee, TikTok Shop &amp; Lazada</div>
                            </div>
                        </div>
                        @can('penjualan-list')
                            <a href="{{ route('transaksi-penjualan.index') }}" class="btn-primary-custom">
                                <i class="fas fa-arrow-right"></i>
                                <span>Semua Pesanan</span>
                            </a>
                        @endcan
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-produk align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Kode Transaksi</th>
                                        <th>Channel &amp; Pelanggan</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-center pe-3 col-freeze-aksi">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transaksiTerbaru as $trx)
                                        <tr>
                                            <td class="ps-3">
                                                <span class="badge-kode">{{ $trx->kode_transaksi }}</span>
                                                <div class="text-muted mt-1" style="font-size: 0.7rem;">{{ $trx->tanggal->translatedFormat('d M Y') }}</div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ strtoupper($trx->channel ?? 'MP') }} &bull; {{ $trx->nama_pelanggan ?: 'Pembeli MP' }}</div>
                                                <div class="text-muted" style="font-size: 0.7rem;">{{ $trx->details->count() }} macam produk</div>
                                            </td>
                                            <td class="text-end fw-bold font-monospace text-success">
                                                Rp {{ number_format($trx->grand_total, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center pe-3 col-freeze-aksi">
                                                @can('penjualan-list')
                                                    <a href="{{ route('transaksi-penjualan.show', $trx) }}" class="btn-action-icon" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada transaksi penjualan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pembelian Bahan Baku Terbaru --}}
            <div class="col-12 col-xl-6">
                <div class="card card-modern bg-white h-100">
                    <div class="card-header-clean">
                        <div class="card-header-title-wrap">
                            <span class="header-icon-badge" style="background: #e0f2fe; color: #0284c7;">
                                <i class="fas fa-truck-ramp-box"></i>
                            </span>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">5 Pembelian Bahan Baku Terbaru</h6>
                                <div class="text-muted" style="font-size: 0.73rem; margin-top: 2px;">Pengadaan stok material &amp; packaging supplier</div>
                            </div>
                        </div>
                        @can('pembelian-list')
                            <a href="{{ route('transaksi-pembelian.index') }}" class="btn-primary-custom">
                                <i class="fas fa-arrow-right"></i>
                                <span>Semua Pembelian</span>
                            </a>
                        @endcan
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-produk align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Kode PO</th>
                                        <th>Supplier &amp; Status</th>
                                        <th class="text-end">Grand Total</th>
                                        <th class="text-center pe-3 col-freeze-aksi">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pembelianTerbaru as $pbl)
                                        <tr>
                                            <td class="ps-3">
                                                <span class="badge-kode">{{ $pbl->kode_pembelian }}</span>
                                                <div class="text-muted mt-1" style="font-size: 0.7rem;">{{ $pbl->tanggal->translatedFormat('d M Y') }}</div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $pbl->supplier ?: 'Supplier Online' }}</div>
                                                <div class="text-muted" style="font-size: 0.7rem;">Status: {{ ucfirst($pbl->status_pembelian ?? 'diterima') }}</div>
                                            </td>
                                            <td class="text-end fw-bold font-monospace text-primary">
                                                Rp {{ number_format($pbl->grand_total, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center pe-3 col-freeze-aksi">
                                                @can('pembelian-list')
                                                    <a href="{{ route('transaksi-pembelian.show', $pbl) }}" class="btn-action-icon" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada transaksi pembelian.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- MODAL FILTER PERIODE & FORECASTING DASHBOARD --}}
<div class="modal fade" id="modalFilterDashboard" tabindex="-1" aria-labelledby="modalFilterDashboardLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.875rem;">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%); color: #ffffff;">
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <i class="fas fa-filter fa-lg"></i>
                    <h5 class="modal-title fw-bold fs-6 mb-0 text-white" id="modalFilterDashboardLabel">Filter Periode &amp; Horizon Forecasting</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('dashboard') }}">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_bulan">Bulan Evaluasi KPI</label>
                            <select name="bulan" id="modal_bulan" class="form-select">
                                @foreach($bulanList as $bNum => $bNama)
                                    <option value="{{ $bNum }}" {{ (int) $bulanIni === (int) $bNum ? 'selected' : '' }}>
                                        {{ $bNama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_tahun">Tahun Evaluasi</label>
                            <select name="tahun" id="modal_tahun" class="form-select">
                                @foreach($tahunList as $th)
                                    <option value="{{ $th }}" {{ (int) $tahunIni === (int) $th ? 'selected' : '' }}>
                                        Tahun {{ $th }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="modal_horizon">Horizon Proyeksi Time-Series Forecasting</label>
                            <select name="horizon" id="modal_horizon" class="form-select">
                                <option value="3" {{ (int) $forecastHorizon === 3 ? 'selected' : '' }}>Proyeksi 3 Bulan ke Depan (Short-Term)</option>
                                <option value="6" {{ (int) $forecastHorizon === 6 ? 'selected' : '' }}>Proyeksi 6 Bulan ke Depan (Mid-Term)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-3 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('dashboard') }}" class="btn-reset-custom">
                        <i class="fas fa-redo"></i>
                        <span>Reset Filter</span>
                    </a>
                    <div class="d-flex" style="gap: 8px;">
                        <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-primary-custom">
                            <i class="fas fa-check"></i>
                            <span>Terapkan Filter</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Pastikan modal berada langsung di bawah body agar z-index backdrop presisi
    const modalEl = document.getElementById('modalFilterDashboard');
    if (modalEl && modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }

    const btnBukaFilter = document.getElementById('btnBukaFilterDashboard');
    if (btnBukaFilter && modalEl) {
        btnBukaFilter.addEventListener('click', function (e) {
            e.preventDefault();
            if (window.bootstrap && window.bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            } else if (window.jQuery && typeof jQuery(modalEl).modal === 'function') {
                jQuery(modalEl).modal('show');
            }
        });
    }

    // Global Defaults untuk Chart.js agar tipografi & warna seragam profesional
    if (window.Chart) {
        Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
        Chart.defaults.color = '#64748b';
    }

    const FC_DATA         = @json($chartForecastData);
    const HIST_LABELS     = @json($labels);
    const PROD_BAGUS      = @json($dataProduksiBagus);
    const PROD_REJECT     = @json($dataProduksiReject);
    const PAKAI_BAHAN     = @json($dataPemakaianBahan);
    const CHANNEL_STATS   = @json(array_values($channelStats));
    const KATEGORI_LABELS = @json($kategoriLabels);
    const KATEGORI_COUNTS = @json($kategoriCounts);
    const BIAYA_LABELS    = @json($pengeluaranPerKategori->pluck('kategori_label')->values());
    const BIAYA_VALUES    = @json($pengeluaranPerKategori->pluck('total_jumlah')->values());

    function formatRupiah(angka) {
        if (angka === null || angka === undefined) return '-';
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(angka));
    }

    function shortRupiah(value) {
        const abs = Math.abs(value);
        if (abs >= 1000000) {
            return (value < 0 ? '-Rp ' : 'Rp ') + (abs / 1000000).toFixed(1).replace('.', ',') + ' jt';
        }
        if (abs >= 1000) {
            return (value < 0 ? '-Rp ' : 'Rp ') + (abs / 1000).toFixed(0) + ' rb';
        }
        return 'Rp ' + value;
    }

    const commonTooltip = {
        backgroundColor: '#0f172a',
        titleColor: '#f8fafc',
        bodyColor: '#e2e8f0',
        borderColor: 'rgba(255, 255, 255, 0.1)',
        borderWidth: 1,
        padding: 12,
        cornerRadius: 8,
        boxPadding: 6,
        usePointStyle: true,
        titleFont: { size: 12, weight: '700' },
        bodyFont: { size: 11.5, weight: '500' },
    };

    const chartInstances = {};

    // =========================================================================
    // PLUGIN KHUSUS: ZONA SHADING FORECASTING & GARIS PEMBATAS AKTUAL VS PREDIKSI
    // =========================================================================
    const forecastZonePlugin = {
        id: 'forecastZonePlugin',
        beforeDraw(chart) {
            const { ctx, chartArea, scales } = chart;
            if (!chartArea || !scales.x || !FC_DATA) return;

            // Index 11 adalah bulan historis ke-12 (titik sambung ke forecast)
            const splitIndex = 11;
            const xSplit = scales.x.getPixelForValue(splitIndex);
            if (!xSplit || xSplit >= chartArea.right) return;

            ctx.save();
            // Area latar belakang lembut untuk Zona Forecast
            const grad = ctx.createLinearGradient(xSplit, chartArea.top, chartArea.right, chartArea.top);
            grad.addColorStop(0, 'rgba(99, 102, 241, 0.04)');
            grad.addColorStop(1, 'rgba(16, 185, 129, 0.07)');
            ctx.fillStyle = grad;
            ctx.fillRect(xSplit, chartArea.top, chartArea.right - xSplit, chartArea.bottom - chartArea.top);

            // Garis vertikal putus-putus pembatas Aktual vs Forecast
            ctx.beginPath();
            ctx.setLineDash([4, 4]);
            ctx.lineWidth = 1.5;
            ctx.strokeStyle = 'rgba(79, 70, 229, 0.45)';
            ctx.moveTo(xSplit, chartArea.top);
            ctx.lineTo(xSplit, chartArea.bottom);
            ctx.stroke();

            // Label badge di sudut atas zona forecast
            const labelText = 'ZONA PROYEKSI AI';
            ctx.font = "700 10px 'Inter', sans-serif";
            const textWidth = ctx.measureText(labelText).width;
            const badgeX = Math.min(xSplit + 10, chartArea.right - textWidth - 18);
            const badgeY = chartArea.top + 8;

            ctx.fillStyle = 'rgba(79, 70, 229, 0.12)';
            ctx.beginPath();
            ctx.roundRect(badgeX, badgeY, textWidth + 14, 20, 4);
            ctx.fill();

            ctx.fillStyle = '#4338ca';
            ctx.fillText(labelText, badgeX + 7, badgeY + 13.5);
            ctx.restore();
        }
    };

    // =========================================================================
    // 1. CHART UTAMA: TREN KEUANGAN 12 BULAN + FORECASTING (HOLT'S SMOOTHING)
    // =========================================================================
    const elForecast = document.getElementById('chartTrenForecast');
    if (elForecast && FC_DATA) {
        const ctxFc = elForecast.getContext('2d');
        const omzetGrad = ctxFc.createLinearGradient(0, 0, 0, 320);
        omzetGrad.addColorStop(0, 'rgba(16, 185, 129, 0.20)');
        omzetGrad.addColorStop(1, 'rgba(16, 185, 129, 0.00)');

        chartInstances.forecast = new Chart(ctxFc, {
            type: 'line',
            plugins: [forecastZonePlugin],
            data: {
                labels: FC_DATA.labels,
                datasets: [
                    {
                        label: 'Omzet Penjualan MP (Aktual)',
                        data: FC_DATA.actual_omzet,
                        borderColor: '#10b981',
                        backgroundColor: omzetGrad,
                        borderWidth: 2.75,
                        tension: 0.35,
                        fill: true,
                        pointRadius: 3.5,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                    },
                    {
                        label: 'Forecast Omzet MP (Prediksi)',
                        data: FC_DATA.pred_omzet,
                        borderColor: '#059669',
                        backgroundColor: '#059669',
                        borderDash: [6, 4],
                        borderWidth: 2.75,
                        tension: 0.35,
                        fill: false,
                        pointRadius: 4.5,
                        pointHoverRadius: 6.5,
                        pointStyle: 'rectRot',
                        pointBackgroundColor: '#059669',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 1.5,
                    },
                    {
                        label: 'Rentang Optimis (90% CI)',
                        data: FC_DATA.pred_omzet_upper,
                        borderColor: 'rgba(16, 185, 129, 0.30)',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        borderDash: [2, 2],
                        borderWidth: 1,
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        fill: '+1',
                        tension: 0.35,
                    },
                    {
                        label: 'Rentang Konservatif (90% CI)',
                        data: FC_DATA.pred_omzet_lower,
                        borderColor: 'rgba(16, 185, 129, 0.30)',
                        borderDash: [2, 2],
                        borderWidth: 1,
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        fill: false,
                        tension: 0.35,
                    },
                    {
                        label: 'Laba Bersih Bisnis (Aktual)',
                        data: FC_DATA.actual_laba,
                        borderColor: '#4f46e5',
                        backgroundColor: '#4f46e5',
                        borderWidth: 2.5,
                        tension: 0.35,
                        fill: false,
                        pointRadius: 3.5,
                        pointHoverRadius: 5.5,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                    },
                    {
                        label: 'Forecast Laba Bersih (Prediksi)',
                        data: FC_DATA.pred_laba,
                        borderColor: '#4338ca',
                        backgroundColor: '#4338ca',
                        borderDash: [6, 4],
                        borderWidth: 2.5,
                        tension: 0.35,
                        fill: false,
                        pointRadius: 4.5,
                        pointHoverRadius: 6.5,
                        pointStyle: 'rectRot',
                        pointBackgroundColor: '#4338ca',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 1.5,
                    },
                    {
                        label: 'Belanja Bahan HPP (Aktual)',
                        data: FC_DATA.actual_pembelian,
                        borderColor: '#0284c7',
                        backgroundColor: '#0284c7',
                        borderWidth: 2,
                        tension: 0.35,
                        fill: false,
                        pointRadius: 3,
                        hidden: true,
                    },
                    {
                        label: 'Biaya Operasional & Ads (Aktual)',
                        data: FC_DATA.actual_pengeluaran,
                        borderColor: '#f59e0b',
                        backgroundColor: '#f59e0b',
                        borderWidth: 2,
                        tension: 0.35,
                        fill: false,
                        pointRadius: 3,
                        hidden: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            boxWidth: 9,
                            boxHeight: 9,
                            padding: 16,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 11, weight: '600' },
                            color: '#334155',
                            // Sembunyikan batas bawah CI dari legend agar tidak menduplikasi label
                            filter: function (item) {
                                return item.datasetIndex !== 3;
                            }
                        },
                    },
                    tooltip: {
                        ...commonTooltip,
                        callbacks: {
                            label: function (ctx) {
                                if (ctx.parsed.y === null) return null;
                                return ctx.dataset.label + ': ' + formatRupiah(ctx.parsed.y);
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10.5, weight: '500' }, color: '#64748b', maxRotation: 0 },
                    },
                    y: {
                        grid: { color: 'rgba(15, 23, 42, 0.05)', drawBorder: false },
                        ticks: { font: { size: 10.5 }, color: '#64748b', maxTicksLimit: 7, callback: shortRupiah },
                    },
                },
            },
        });

        document.querySelectorAll('[data-chart-mode]').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('[data-chart-mode]').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const mode = this.getAttribute('data-chart-mode');
                const chart = chartInstances.forecast;
                if (!chart) return;

                if (mode === 'omzet') {
                    [0, 1, 2, 3].forEach(i => chart.setDatasetVisibility(i, true));
                    [4, 5, 6, 7].forEach(i => chart.setDatasetVisibility(i, false));
                } else if (mode === 'lengkap') {
                    [0, 1, 2, 3, 4, 5, 6, 7].forEach(i => chart.setDatasetVisibility(i, true));
                } else {
                    [0, 1, 2, 3, 4, 5].forEach(i => chart.setDatasetVisibility(i, true));
                    [6, 7].forEach(i => chart.setDatasetVisibility(i, false));
                }
                chart.update();
            });
        });
    }

    // =========================================================================
    // 2. CHART 3 MARKETPLACE: OMZET AKTUAL, PENGHASILAN BERSIH & FORECAST
    // =========================================================================
    const elMp = document.getElementById('chartMarketplace');
    if (elMp && CHANNEL_STATS.length) {
        chartInstances.mp = new Chart(elMp.getContext('2d'), {
            type: 'bar',
            data: {
                labels: CHANNEL_STATS.map(c => c.label),
                datasets: [
                    {
                        label: 'Omzet Kotor',
                        data: CHANNEL_STATS.map(c => c.omzet),
                        backgroundColor: '#26476f',
                        borderRadius: 6,
                        maxBarThickness: 28,
                    },
                    {
                        label: 'Penghasilan Bersih',
                        data: CHANNEL_STATS.map(c => c.bersih),
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        maxBarThickness: 28,
                    },
                    {
                        label: 'Forecast Bulan Depan',
                        data: CHANNEL_STATS.map(c => c.forecast_next),
                        backgroundColor: '#7c3aed',
                        borderRadius: 6,
                        maxBarThickness: 28,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: { boxWidth: 8, boxHeight: 8, usePointStyle: true, pointStyle: 'circle', font: { size: 11, weight: '600' }, color: '#334155' },
                    },
                    tooltip: {
                        ...commonTooltip,
                        callbacks: { label: ctx => ctx.dataset.label + ': ' + formatRupiah(ctx.parsed.y) },
                    },
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' }, color: '#334155' } },
                    y: { beginAtZero: true, grid: { color: 'rgba(15, 23, 42, 0.05)' }, ticks: { font: { size: 10 }, callback: shortRupiah } },
                },
            },
        });
    }

    // =========================================================================
    // 3. CHART PRODUKSI WORKSHOP, QC & PEMAKAIAN BAHAN BAKU
    // =========================================================================
    const elProd = document.getElementById('chartProduksiBahan');
    if (elProd) {
        chartInstances.prod = new Chart(elProd.getContext('2d'), {
            type: 'bar',
            data: {
                labels: HIST_LABELS,
                datasets: [
                    {
                        label: 'Souvenir Lolos QC (Pcs)',
                        data: PROD_BAGUS,
                        backgroundColor: '#10b981',
                        borderRadius: 4,
                        maxBarThickness: 22,
                    },
                    {
                        label: 'Reject Produksi (Pcs)',
                        data: PROD_REJECT,
                        backgroundColor: '#ef4444',
                        borderRadius: 4,
                        maxBarThickness: 22,
                    },
                    {
                        type: 'line',
                        label: 'Pemakaian Bahan Baku (Unit)',
                        data: PAKAI_BAHAN,
                        borderColor: '#0284c7',
                        backgroundColor: 'rgba(2, 132, 199, 0.12)',
                        borderWidth: 2.5,
                        tension: 0.35,
                        pointRadius: 3.5,
                        pointBackgroundColor: '#0284c7',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 1.5,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: { boxWidth: 8, boxHeight: 8, usePointStyle: true, pointStyle: 'circle', font: { size: 11, weight: '600' }, color: '#334155' },
                    },
                    tooltip: commonTooltip,
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 }, maxRotation: 0 } },
                    y: { beginAtZero: true, grid: { color: 'rgba(15, 23, 42, 0.05)' }, ticks: { font: { size: 10 } } },
                },
            },
        });
    }

    // =========================================================================
    // 4. CHART KOMPOSISI ARUS KAS, MODAL PEMILIK, PRIVE & KASBON
    // =========================================================================
    const elKas = document.getElementById('chartArusKasModal');
    if (elKas) {
        chartInstances.kas = new Chart(elKas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Penghasilan MP', 'Belanja Bahan', 'Biaya & Ads', 'Modal Owner', 'Prive Owner', 'Kasbon Aktif'],
                datasets: [{
                    label: 'Nominal (Rp)',
                    data: [
                        {{ (float) $pendapatanEfektif }},
                        {{ (float) $totalPembelian }},
                        {{ (float) $totalPengeluaran }},
                        {{ (float) $totalModalDisetor }},
                        {{ (float) $totalPriveBulanIni }},
                        {{ (float) $totalKasbonAktif }},
                    ],
                    backgroundColor: ['#10b981', '#0284c7', '#f59e0b', '#4f46e5', '#ec4899', '#ef4444'],
                    borderRadius: 6,
                    maxBarThickness: 24,
                }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        ...commonTooltip,
                        callbacks: { label: ctx => formatRupiah(ctx.parsed.x) },
                    },
                },
                scales: {
                    x: { beginAtZero: true, grid: { color: 'rgba(15, 23, 42, 0.05)' }, ticks: { font: { size: 10 }, callback: shortRupiah } },
                    y: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' }, color: '#334155' } },
                },
            },
        });
    }

    // =========================================================================
    // 5. CHART DISTRIBUSI BIAYA OPERASIONAL & ADS PER KATEGORI (LEGEND SAMPING)
    // =========================================================================
    const elBiaya = document.getElementById('chartBiayaKategori');
    if (elBiaya) {
        chartInstances.biaya = new Chart(elBiaya.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: BIAYA_LABELS.length ? BIAYA_LABELS : ['Belum Ada Biaya'],
                datasets: [{
                    data: BIAYA_VALUES.length ? BIAYA_VALUES : [1],
                    backgroundColor: ['#f59e0b', '#26476f', '#10b981', '#ef4444', '#7c3aed', '#0284c7', '#ec4899'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '64%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { boxWidth: 10, boxHeight: 10, padding: 14, usePointStyle: true, pointStyle: 'circle', font: { size: 11, weight: '600' }, color: '#334155' },
                    },
                    tooltip: {
                        ...commonTooltip,
                        callbacks: { label: ctx => (ctx.label || '') + ': ' + formatRupiah(ctx.parsed) },
                    },
                },
            },
        });
    }

    // =========================================================================
    // 6. CHART DISTRIBUSI KATEGORI ITEM (LEGEND SAMPING PROPORSIONAL)
    // =========================================================================
    const elKat = document.getElementById('chartKategori');
    if (elKat) {
        chartInstances.kat = new Chart(elKat.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: KATEGORI_LABELS,
                datasets: [{
                    data: KATEGORI_COUNTS,
                    backgroundColor: ['#26476f', '#10b981', '#f59e0b', '#dc2626', '#7c3aed', '#0284c7', '#ec4899', '#06b6d4'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '64%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { boxWidth: 10, boxHeight: 10, padding: 14, usePointStyle: true, pointStyle: 'circle', font: { size: 11, weight: '600' }, color: '#334155' },
                    },
                    tooltip: {
                        ...commonTooltip,
                        callbacks: { label: ctx => (ctx.label || '') + ': ' + ctx.parsed + ' SKU' },
                    },
                },
            },
        });
    }

    // =========================================================================
    // INTERACTIVE TAB SWITCHER (TANPA RELOAD & OTOMATIS RESIZE CHART)
    // =========================================================================
    document.querySelectorAll('[data-dash-target]').forEach(tabBtn => {
        tabBtn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-dash-target');

            document.querySelectorAll('[data-dash-target]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            document.querySelectorAll('.dash-tab-pane').forEach(pane => {
                if (pane.id === targetId) {
                    pane.classList.remove('d-none');
                } else {
                    pane.classList.add('d-none');
                }
            });

            setTimeout(() => {
                Object.values(chartInstances).forEach(ch => {
                    if (ch && typeof ch.resize === 'function') {
                        ch.resize();
                    }
                });
            }, 30);
        });
    });
});
</script>
@endpush
