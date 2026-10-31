@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="page-title">
                <span class="page-title-icon">
                    <i class="fas fa-chart-pie"></i>
                </span>
                Dashboard
            </h1>
            <p class="page-subtitle">Ringkasan performa toko souvenir.</p>
        </div>
        <div class="cell-muted" style="font-size: 0.8rem;">
            <i class="far fa-calendar-alt me-1"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    {{-- ============================================
         KPI HARI INI
         ============================================ --}}
    <div class="section-title">
        <i class="fas fa-calendar-day"></i> Hari Ini
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-green">
                    <i class="fas fa-cash-register"></i>
                </div>
                <div class="kpi-body">
                    <div class="kpi-label">Penjualan</div>
                    <div class="kpi-value lg">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</div>
                    <div class="kpi-sub">{{ $transaksiHariIni }} transaksi</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-blue">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="kpi-body">
                    <div class="kpi-label">Pembelian</div>
                    <div class="kpi-value lg">Rp {{ number_format($pembelianHariIni, 0, ',', '.') }}</div>
                    <div class="kpi-sub">Restock barang</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-red">
                    <i class="fas fa-money-bill-transfer"></i>
                </div>
                <div class="kpi-body">
                    <div class="kpi-label">Pengeluaran</div>
                    <div class="kpi-value lg">Rp {{ number_format($pengeluaranHariIni, 0, ',', '.') }}</div>
                    <div class="kpi-sub">Biaya operasional</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-navy">
                    <i class="fas fa-box"></i>
                </div>
                <div class="kpi-body">
                    <div class="kpi-label">Total Produk</div>
                    <div class="kpi-value lg">{{ number_format($totalProduk, 0, ',', '.') }}</div>
                    <div class="kpi-sub">{{ $totalKategori }} kategori</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================
         KPI BULAN INI
         ============================================ --}}
    <div class="section-title">
        <i class="fas fa-calendar-alt"></i> Bulan Ini
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-green">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="kpi-body">
                    <div class="kpi-label">Penjualan</div>
                    <div class="kpi-value lg">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                    <div class="kpi-sub">{{ $jumlahTransaksi }} transaksi</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-amber">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="kpi-body">
                    <div class="kpi-label">Pembelian</div>
                    <div class="kpi-value lg">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</div>
                    <div class="kpi-sub">Modal / HPP</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-red">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="kpi-body">
                    <div class="kpi-label">Pengeluaran</div>
                    <div class="kpi-value lg">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                    <div class="kpi-sub">Biaya</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-icon {{ $labaBersih >= 0 ? 'kpi-icon-green' : 'kpi-icon-red' }}">
                    <i class="fas fa-{{ $labaBersih >= 0 ? 'arrow-trend-up' : 'arrow-trend-down' }}"></i>
                </div>
                <div class="kpi-body">
                    <div class="kpi-label">Laba Bersih</div>
                    <div class="kpi-value lg" style="color: {{ $labaBersih >= 0 ? '#059669' : '#dc2626' }};">
                        Rp {{ number_format($labaBersih, 0, ',', '.') }}
                    </div>
                    <div class="kpi-sub">Laba kotor: Rp {{ number_format($labaKotor, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================
         PERFORMA VS BULAN LALU
         ============================================ --}}
    <div class="section-title">
        <i class="fas fa-chart-line"></i> Performa vs Bulan Lalu
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-navy">
                    <i class="fas fa-arrow-trend-up"></i>
                </div>
                <div class="kpi-body">
                    <div class="kpi-label">Penjualan</div>
                    <div class="kpi-value lg">
                        Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
                        <span class="trend-badge {{ $persenPenjualan > 0 ? 'trend-up' : ($persenPenjualan < 0 ? 'trend-down' : 'trend-flat') }}">
                            <i class="fas fa-{{ $persenPenjualan > 0 ? 'arrow-up' : ($persenPenjualan < 0 ? 'arrow-down' : 'minus') }}"></i>
                            {{ number_format(abs($persenPenjualan), 1) }}%
                        </span>
                    </div>
                    <div class="kpi-sub">
                        Bulan lalu: Rp {{ number_format($penjualanBulanLalu, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-purple">
                    <i class="fas fa-sack-dollar"></i>
                </div>
                <div class="kpi-body">
                    <div class="kpi-label">Laba Bersih</div>
                    <div class="kpi-value lg">
                        Rp {{ number_format($labaBersih, 0, ',', '.') }}
                        <span class="trend-badge {{ $persenLaba > 0 ? 'trend-up' : ($persenLaba < 0 ? 'trend-down' : 'trend-flat') }}">
                            <i class="fas fa-{{ $persenLaba > 0 ? 'arrow-up' : ($persenLaba < 0 ? 'arrow-down' : 'minus') }}"></i>
                            {{ number_format(abs($persenLaba), 1) }}%
                        </span>
                    </div>
                    <div class="kpi-sub">
                        Bulan lalu: Rp {{ number_format($labaBulanLalu, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-amber">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="kpi-body">
                    <div class="kpi-label">Jumlah Transaksi</div>
                    <div class="kpi-value lg">
                        {{ number_format($jumlahTransaksi, 0, ',', '.') }}x
                        <span class="trend-badge {{ $persenTransaksi > 0 ? 'trend-up' : ($persenTransaksi < 0 ? 'trend-down' : 'trend-flat') }}">
                            <i class="fas fa-{{ $persenTransaksi > 0 ? 'arrow-up' : ($persenTransaksi < 0 ? 'arrow-down' : 'minus') }}"></i>
                            {{ number_format(abs($persenTransaksi), 1) }}%
                        </span>
                    </div>
                    <div class="kpi-sub">
                        Bulan lalu: {{ $transaksiBulanLalu }}x transaksi
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================
         GRAFIK TREN
         ============================================ --}}
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h6 class="panel-title">
                        <i class="fas fa-chart-area"></i>
                        Tren Penjualan & Pembelian
                    </h6>
                    <div class="periode-group">
                        <a href="{{ route('dashboard', ['periode' => '7']) }}"
                           class="periode-btn {{ $periode === '7' ? 'active' : '' }}">7 Hari</a>
                        <a href="{{ route('dashboard', ['periode' => '30']) }}"
                           class="periode-btn {{ $periode === '30' ? 'active' : '' }}">30 Hari</a>
                        <a href="{{ route('dashboard', ['periode' => '90']) }}"
                           class="periode-btn {{ $periode === '90' ? 'active' : '' }}">90 Hari</a>
                        <a href="{{ route('dashboard', ['periode' => 'bulan']) }}"
                           class="periode-btn {{ $periode === 'bulan' ? 'active' : '' }}">12 Bulan</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="chart-container chart-tren">
                        <canvas id="chartTren"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================
         ROW: KATEGORI + TOP PRODUK
         ============================================ --}}
    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-5">
            <div class="panel">
                <div class="panel-header">
                    <h6 class="panel-title">
                        <i class="fas fa-chart-pie"></i>
                        Distribusi Kategori
                    </h6>
                </div>
                <div class="panel-body">
                    <div class="chart-container chart-kategori">
                        <canvas id="chartKategori"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="panel">
                <div class="panel-header">
                    <h6 class="panel-title">
                        <i class="fas fa-trophy"></i>
                        Top 5 Produk Terlaris
                    </h6>
                    <span class="cell-subtitle">Bulan ini</span>
                </div>
                <div class="panel-body tight">
                    @if($topProduk->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-chart-bar"></i>
                            <div class="text">Belum ada penjualan bulan ini</div>
                        </div>
                    @else
                        @foreach($topProduk as $index => $tp)
                            <div class="list-row">
                                <span class="list-rank rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
                                <div class="list-content">
                                    <div class="list-title">{{ $tp->nama_produk }}</div>
                                    <div class="list-meta">{{ number_format($tp->total_qty, 0, ',', '.') }} pcs terjual</div>
                                </div>
                                <div class="list-value">
                                    <div class="list-value-main">Rp {{ number_format($tp->total_omzet, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================
         ROW: PENGELUARAN KATEGORI + STOK MENIPIS
         ============================================ --}}
    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-6">
            <div class="panel">
                <div class="panel-header">
                    <h6 class="panel-title">
                        <i class="fas fa-money-bill-transfer"></i>
                        Pengeluaran per Kategori
                    </h6>
                    <span class="cell-subtitle">Bulan ini</span>
                </div>
                <div class="panel-body tight">
                    @if($pengeluaranPerKategori->isEmpty())
                        <div class="empty-state success">
                            <i class="fas fa-check-circle"></i>
                            <div class="text">Belum ada pengeluaran bulan ini</div>
                        </div>
                    @else
                        @foreach($pengeluaranPerKategori as $pkg)
                            <div class="list-row">
                                <div class="list-content">
                                    <div class="list-title">{{ $pkg->kategori_label }}</div>
                                    <div class="list-meta">{{ $pkg->total_transaksi }}x transaksi</div>
                                </div>
                                <div class="list-value">
                                    <div class="list-value-main" style="color: #dc2626;">
                                        Rp {{ number_format($pkg->total_jumlah, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="panel">
                <div class="panel-header">
                    <h6 class="panel-title">
                        <i class="fas fa-exclamation-triangle" style="color: #dc2626;"></i>
                        Stok Menipis
                    </h6>
                    @can('produk-list')
                        <a href="{{ route('produk.index', ['stok_status' => 'menipis']) }}" class="panel-link">
                            Lihat semua <i class="fas fa-arrow-right"></i>
                        </a>
                    @endcan
                </div>
                <div class="panel-body tight">
                    @if($produkStokMenipis->isEmpty())
                        <div class="empty-state success">
                            <i class="fas fa-check-circle"></i>
                            <div class="text">Semua produk stok aman</div>
                        </div>
                    @else
                        @foreach($produkStokMenipis as $psm)
                            <div class="list-row">
                                <div class="list-content">
                                    <div class="list-title">{{ $psm->nama_produk }}</div>
                                    <div class="list-meta">{{ $psm->kode_produk }} • {{ $psm->kategori->nama_kategori ?? '-' }}</div>
                                </div>
                                <div>
                                    @if($psm->stok <= 0)
                                        <span class="badge-soft danger">
                                            <i class="fas fa-times-circle"></i> Habis
                                        </span>
                                    @else
                                        <span class="badge-soft warning">
                                            <i class="fas fa-exclamation-triangle"></i> {{ $psm->stok }} pcs
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================
         ROW: TRANSAKSI TERBARU + PEMBELIAN TERBARU
         ============================================ --}}
    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-6">
            <div class="panel">
                <div class="panel-header">
                    <h6 class="panel-title">
                        <i class="fas fa-history"></i>
                        Penjualan Terbaru
                    </h6>
                    @can('penjualan-list')
                        <a href="{{ route('transaksi-penjualan.index') }}" class="panel-link">
                            Lihat semua <i class="fas fa-arrow-right"></i>
                        </a>
                    @endcan
                </div>
                <div class="panel-body tight">
                    @if($transaksiTerbaru->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-shopping-cart"></i>
                            <div class="text">Belum ada transaksi</div>
                        </div>
                    @else
                        @foreach($transaksiTerbaru as $trx)
                            <div class="list-row">
                                <div class="list-content">
                                    <div class="list-title" style="font-family: monospace; color: #26476f;">
                                        {{ $trx->kode_transaksi }}
                                    </div>
                                    <div class="list-meta">
                                        {{ $trx->tanggal->format('d M Y') }} • {{ $trx->created_at->format('H:i') }} WIB
                                        @if($trx->nama_pelanggan)
                                            • {{ $trx->nama_pelanggan }}
                                        @endif
                                    </div>
                                </div>
                                <div class="list-value">
                                    <div class="list-value-main">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</div>
                                    <div class="list-value-sub">{{ $trx->details->count() }} item</div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="panel">
                <div class="panel-header">
                    <h6 class="panel-title">
                        <i class="fas fa-truck-loading"></i>
                        Pembelian Terbaru
                    </h6>
                    @can('pembelian-list')
                        <a href="{{ route('transaksi-pembelian.index') }}" class="panel-link">
                            Lihat semua <i class="fas fa-arrow-right"></i>
                        </a>
                    @endcan
                </div>
                <div class="panel-body tight">
                    @if($pembelianTerbaru->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-truck"></i>
                            <div class="text">Belum ada pembelian</div>
                        </div>
                    @else
                        @foreach($pembelianTerbaru as $pbl)
                            <div class="list-row">
                                <div class="list-content">
                                    <div class="list-title" style="font-family: monospace; color: #2563eb;">
                                        {{ $pbl->kode_pembelian }}
                                    </div>
                                    <div class="list-meta">
                                        {{ $pbl->tanggal->format('d M Y') }}
                                        @if($pbl->supplier)
                                            • {{ $pbl->supplier }}
                                        @endif
                                    </div>
                                </div>
                                <div class="list-value">
                                    <div class="list-value-main" style="color: #2563eb;">
                                        Rp {{ number_format($pbl->grand_total, 0, ',', '.') }}
                                    </div>
                                    <div class="list-value-sub">{{ $pbl->details->count() }} item</div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================
         ROW: PENGELUARAN TERBARU (full width)
         ============================================ --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h6 class="panel-title">
                        <i class="fas fa-money-bill-transfer"></i>
                        Pengeluaran Terbaru
                    </h6>
                    @can('pengeluaran-list')
                        <a href="{{ route('pengeluaran.index') }}" class="panel-link">
                            Lihat semua <i class="fas fa-arrow-right"></i>
                        </a>
                    @endcan
                </div>
                <div class="panel-body tight">
                    @if($pengeluaranTerbaru->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-receipt"></i>
                            <div class="text">Belum ada pengeluaran</div>
                        </div>
                    @else
                        <div class="row g-0">
                            @foreach($pengeluaranTerbaru as $pgl)
                                <div class="col-12">
                                    <div class="list-row">
                                        <div class="list-content">
                                            <div class="list-title" style="font-family: monospace; color: #dc2626;">
                                                {{ $pgl->kode_pengeluaran }}
                                            </div>
                                            <div class="list-meta">
                                                {{ $pgl->tanggal->format('d M Y') }} • {{ $pgl->kategori_label }} • {{ $pgl->keterangan }}
                                            </div>
                                        </div>
                                        <div class="list-value">
                                            <div class="list-value-main" style="color: #dc2626;">
                                                Rp {{ number_format($pgl->jumlah, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

{{-- ============================================
     SCRIPTS — CHART.JS & GRAFIK DASHBOARD
     ============================================ --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    'use strict';

    const LABELS          = @json($labels);
    const DATA_PENJUALAN  = @json($dataPenjualan);
    const DATA_PEMBELIAN  = @json($dataPembelian);
    const KATEGORI_LABELS = @json($kategoriLabels);
    const KATEGORI_COUNTS = @json($kategoriCounts);

    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka || 0);
    }

    // ============================================
    // 1. CHART TREN PENJUALAN & PEMBELIAN
    // ============================================
    const canvasTren = document.getElementById('chartTren');
    if (canvasTren) {
        const ctxTren = canvasTren.getContext('2d');

        const gradientPenjualan = ctxTren.createLinearGradient(0, 0, 0, 280);
        gradientPenjualan.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
        gradientPenjualan.addColorStop(1, 'rgba(16, 185, 129, 0)');

        const gradientPembelian = ctxTren.createLinearGradient(0, 0, 0, 280);
        gradientPembelian.addColorStop(0, 'rgba(59, 130, 246, 0.15)');
        gradientPembelian.addColorStop(1, 'rgba(59, 130, 246, 0)');

        new Chart(ctxTren, {
            type: 'line',
            data: {
                labels: LABELS,
                datasets: [
                    {
                        label: 'Penjualan',
                        data: DATA_PENJUALAN,
                        borderColor: '#10b981',
                        backgroundColor: gradientPenjualan,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                    },
                    {
                        label: 'Pembelian',
                        data: DATA_PEMBELIAN,
                        borderColor: '#3b82f6',
                        backgroundColor: gradientPembelian,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
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
                            boxWidth: 8, boxHeight: 8, padding: 12,
                            font: { family: "'Inter', sans-serif", size: 11, weight: '500' },
                            color: '#374151',
                            usePointStyle: true, pointStyle: 'circle',
                        },
                    },
                    tooltip: {
                        backgroundColor: '#1f2937', padding: 10, cornerRadius: 6,
                        titleFont: { family: "'Inter', sans-serif", size: 12, weight: '600' },
                        bodyFont: { family: "'Inter', sans-serif", size: 11 },
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': ' + formatRupiah(context.parsed.y);
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: "'Inter', sans-serif", size: 10 },
                            color: '#9ca3af', maxRotation: 0, autoSkipPadding: 20,
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.04)', drawBorder: false },
                        ticks: {
                            font: { family: "'Inter', sans-serif", size: 10 },
                            color: '#9ca3af', maxTicksLimit: 6,
                            callback: function (value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1).replace('.', ',') + ' jt';
                                }
                                if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                }
                                return 'Rp ' + value;
                            },
                        },
                    },
                },
            },
        });
    }

    // ============================================
    // 2. CHART KATEGORI (DOUGHNUT)
    // ============================================
    const canvasKategori = document.getElementById('chartKategori');
    if (canvasKategori) {
        const ctxKategori = canvasKategori.getContext('2d');

        const KATEGORI_COLORS = [
            '#26476f', '#10b981', '#f59e0b', '#dc2626', '#7c3aed',
            '#3b82f6', '#ec4899', '#06b6d4', '#84cc16', '#f97316',
        ];

        new Chart(ctxKategori, {
            type: 'doughnut',
            data: {
                labels: KATEGORI_LABELS,
                datasets: [{
                    data: KATEGORI_COUNTS,
                    backgroundColor: KATEGORI_COLORS,
                    borderColor: '#fff', borderWidth: 2, hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 8, boxHeight: 8, padding: 8,
                            font: { family: "'Inter', sans-serif", size: 10, weight: '500' },
                            color: '#374151',
                            usePointStyle: true, pointStyle: 'circle',
                        },
                    },
                    tooltip: {
                        backgroundColor: '#1f2937', padding: 10, cornerRadius: 6,
                        titleFont: { family: "'Inter', sans-serif", size: 12, weight: '600' },
                        bodyFont: { family: "'Inter', sans-serif", size: 11 },
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? ((value / total) * 100).toFixed(1).replace('.', ',') : 0;
                                return label + ': ' + value + ' produk (' + pct + '%)';
                            },
                        },
                    },
                },
            },
        });
    }

})();
</script>
@endpush