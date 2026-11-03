@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@push('styles')
<style>
    :root {
        --sidebar-bg-1: #26476f;
        --sidebar-bg-2: #1f3a5c;
        --sidebar-bg-3: #1a304d;
        --sidebar-cream: #fbfaf7;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;
    }

    body { background-color: var(--gray-50); }

    .page-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--gray-900);
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }
    .page-title-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, var(--sidebar-bg-1) 0%, var(--sidebar-bg-3) 100%);
        color: var(--sidebar-cream);
        border: 1px solid rgba(95, 143, 194, 0.3);
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(38, 71, 111, 0.25);
    }
    .page-subtitle {
        font-size: 0.8rem;
        color: var(--gray-500);
        margin-bottom: 0;
    }

    .btn-danger-custom {
        background-color: #dc2626;
        border: 1px solid #dc2626;
        color: #fff;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        text-decoration: none;
        height: 38px;
    }
    .btn-danger-custom:hover {
        background-color: #b91c1c;
        border-color: #b91c1c;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .btn-success-custom {
        background-color: #10b981;
        border: 1px solid #10b981;
        color: #fff;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        text-decoration: none;
        height: 38px;
    }
    .btn-success-custom:hover {
        background-color: #059669;
        border-color: #059669;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .tabs-container {
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: 0.75rem;
        padding: 0.5rem;
        margin-bottom: 1rem;
        display: flex;
        gap: 0.375rem;
        overflow-x: auto;
        flex-wrap: wrap;
    }
    .tab-btn {
        background: transparent;
        border: none;
        color: var(--gray-600);
        font-size: 0.8rem;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
    }
    .tab-btn:hover {
        background-color: var(--gray-100);
        color: var(--sidebar-bg-1);
    }
    .tab-btn.active {
        background: linear-gradient(135deg, var(--sidebar-bg-1) 0%, var(--sidebar-bg-3) 100%);
        color: #fff;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(38, 71, 111, 0.25);
    }
    .tab-btn.active i { color: #fff; }
    .tab-btn i {
        font-size: 0.85rem;
        color: var(--gray-500);
    }

    .filter-bar {
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: 0.75rem;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
    }
    .filter-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.375rem;
        display: block;
    }
    .form-select-modern {
        border: 1px solid var(--gray-200);
        border-radius: 0.5rem;
        padding: 0.5rem 0.875rem;
        font-size: 0.8rem;
        transition: all 0.15s ease;
        height: 38px;
        width: 100%;
        background-color: #fff;
    }
    .form-select-modern:focus {
        border-color: var(--sidebar-bg-1);
        box-shadow: 0 0 0 3px rgba(38, 71, 111, 0.1);
        outline: none;
    }

    .summary-card {
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: 0.75rem;
        padding: 1rem 1.125rem;
        height: 100%;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        transition: all 0.2s ease;
    }
    .summary-card:hover {
        border-color: var(--gray-300);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .summary-icon {
        width: 38px;
        height: 38px;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
    }
    .summary-icon-green  { background: #ecfdf5; color: #059669; }
    .summary-icon-blue   { background: #eff6ff; color: #2563eb; }
    .summary-icon-red    { background: #fef2f2; color: #dc2626; }
    .summary-icon-amber  { background: #fffbeb; color: #d97706; }
    .summary-icon-navy   { background: rgba(38, 71, 111, 0.08); color: #26476f; }
    .summary-icon-purple { background: #f5f3ff; color: #7c3aed; }

    .summary-label {
        font-size: 0.65rem;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.25rem;
    }
    .summary-value {
        font-size: 1rem;
        font-weight: 700;
        color: var(--gray-900);
        letter-spacing: -0.02em;
        line-height: 1.25;
        word-break: break-word;
        margin-bottom: 0.15rem;
    }
    .summary-sub {
        font-size: 0.65rem;
        color: var(--gray-500);
    }

    .panel {
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: 0.75rem;
        overflow: hidden;
        margin-bottom: 1rem;
    }
    .panel-header {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--gray-100);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.625rem;
        flex-wrap: wrap;
    }
    .panel-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--gray-900);
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin: 0;
    }
    .panel-title i {
        color: #26476f;
        font-size: 0.8rem;
    }
    .panel-body { padding: 0; }
    .panel-body-padded { padding: 1rem; }

    .table-laporan {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-laporan thead th {
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%);
        color: #fff;
        font-weight: 600;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem 0.875rem;
        text-align: center !important;
        white-space: nowrap;
        border: none;
    }
    .table-laporan thead th:first-child { text-align: center !important; }
    .table-laporan thead th.text-center { text-align: center !important; }

    .table-laporan tbody td {
        padding: 0.625rem 0.875rem;
        border-bottom: 1px solid var(--gray-100);
        font-size: 0.8rem;
        color: var(--gray-700);
        text-align: right;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-laporan tbody td:first-child { text-align: left; }
    .table-laporan tbody td.text-center { text-align: center; }
    .table-laporan tbody tr:hover td { background-color: #fafbfc; }
    .table-laporan tbody tr:last-child td { border-bottom: none; }

    .table-laporan tfoot td {
        padding: 0.875rem;
        font-size: 0.8rem;
        font-weight: 700;
        background: var(--gray-50);
        border-top: 2px solid var(--gray-200);
        color: var(--gray-900);
        text-align: right;
        white-space: nowrap;
    }
    .table-laporan tfoot td:first-child { text-align: left; }

    .text-green { color: #059669; font-weight: 600; white-space: nowrap !important; }
    .text-red   { color: #dc2626; font-weight: 600; white-space: nowrap !important; }
    .text-amber { color: #d97706; font-weight: 600; white-space: nowrap !important; }
    .text-navy  { color: #26476f; font-weight: 600; white-space: nowrap !important; }
    .text-muted-cell { color: var(--gray-400); }

    .badge-soft {
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        font-size: 0.65rem;
        font-weight: 600;
        padding: 0.2rem 0.45rem;
        border-radius: 999px;
        white-space: nowrap;
    }
    .badge-soft.success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-soft.warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

    .chart-container {
        position: relative;
        height: 320px;
        width: 100%;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--gray-400);
    }
    .empty-state i {
        font-size: 2.25rem;
        margin-bottom: 0.75rem;
        display: block;
        opacity: 0.4;
    }
    .empty-state .text {
        font-size: 0.8rem;
    }

    @media (max-width: 768px) {
        .page-title { font-size: 1.15rem; }
        .page-title-icon { width: 32px; height: 32px; font-size: 0.8rem; }
        .summary-value { font-size: 0.9rem; }
        .chart-container { height: 240px; }
        .table-laporan thead th,
        .table-laporan tbody td { padding: 0.5rem 0.5rem; font-size: 0.7rem; }
        .tab-btn { font-size: 0.75rem; padding: 0.4rem 0.75rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="page-title">
                <span class="page-title-icon">
                    <i class="fas fa-chart-line"></i>
                </span>
                Laporan Keuangan
            </h1>
            <p class="page-subtitle">Analisis keuangan toko berdasarkan periode.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('laporan.keuangan.export-excel', ['tab' => $tab, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
               class="btn-success-custom">
                <i class="fas fa-file-excel"></i>
                <span>Export Excel</span>
            </a>
            <a href="{{ route('laporan.keuangan.export-pdf', ['tab' => $tab, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
               class="btn-danger-custom">
                <i class="fas fa-file-pdf"></i>
                <span>Export PDF</span>
            </a>
        </div>
    </div>

    {{-- TABS --}}
    <div class="tabs-container">
        <a href="{{ route('laporan.keuangan', ['tab' => 'laba-rugi', 'bulan' => $bulan, 'tahun' => $tahun]) }}"
           class="tab-btn {{ $tab === 'laba-rugi' ? 'active' : '' }}">
            <i class="fas fa-balance-scale"></i>
            <span>Laba/Rugi (Toko)</span>
        </a>
        <a href="{{ route('laporan.keuangan', ['tab' => 'penjualan', 'bulan' => $bulan, 'tahun' => $tahun]) }}"
           class="tab-btn {{ $tab === 'penjualan' ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i>
            <span>Penjualan</span>
        </a>
        <a href="{{ route('laporan.keuangan', ['tab' => 'pembelian', 'bulan' => $bulan, 'tahun' => $tahun]) }}"
           class="tab-btn {{ $tab === 'pembelian' ? 'active' : '' }}">
            <i class="fas fa-truck-loading"></i>
            <span>Pembelian</span>
        </a>
        <a href="{{ route('laporan.keuangan', ['tab' => 'pengeluaran', 'bulan' => $bulan, 'tahun' => $tahun]) }}"
           class="tab-btn {{ $tab === 'pengeluaran' ? 'active' : '' }}">
            <i class="fas fa-money-bill-transfer"></i>
            <span>Pengeluaran Toko</span>
        </a>
        <a href="{{ route('laporan.keuangan', ['tab' => 'pribadi', 'bulan' => $bulan, 'tahun' => $tahun]) }}"
           class="tab-btn {{ $tab === 'pribadi' ? 'active' : '' }}">
            <i class="fas fa-wallet"></i>
            <span>Keuangan Pribadi (Prive)</span>
        </a>
        <a href="{{ route('laporan.keuangan', ['tab' => 'rekonsiliasi', 'bulan' => $bulan, 'tahun' => $tahun]) }}"
           class="tab-btn {{ $tab === 'rekonsiliasi' ? 'active' : '' }}">
            <i class="fas fa-scale-balanced"></i>
            <span>Hubungan Owner ⇄ Toko</span>
        </a>
        <a href="{{ route('laporan.keuangan', ['tab' => 'kasbon', 'bulan' => $bulan, 'tahun' => $tahun]) }}"
           class="tab-btn {{ $tab === 'kasbon' ? 'active' : '' }}">
            <i class="fas fa-hand-holding-dollar"></i>
            <span>Kasbon Tim</span>
        </a>
    </div>

    {{-- FILTER --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('laporan.keuangan') }}" id="filterForm">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="row g-3 align-items-end">
                <div class="col-6 col-md-3">
                    <label class="filter-label">Bulan</label>
                    <select name="bulan" class="form-select-modern" onchange="this.form.submit()">
                        <option value="all" {{ $bulan === 'all' ? 'selected' : '' }}>Semua Bulan</option>
                        @foreach($bulanList as $num => $nama)
                            <option value="{{ $num }}" {{ $bulan != 'all' && (int) $bulan === $num ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="filter-label">Tahun</label>
                    <select name="tahun" class="form-select-modern" onchange="this.form.submit()">
                        @foreach($tahunList as $th)
                            <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>
                                {{ $th }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <div style="font-size: 0.75rem; color: var(--gray-500); padding-top: 0.5rem;">
                        <i class="fas fa-info-circle me-1"></i>
                        Menampilkan data:
                        <strong>
                            @if($bulan === 'all')
                                Semua Bulan {{ $tahun }}
                            @else
                                {{ $bulanList[(int) $bulan] }} {{ $tahun }}
                            @endif
                        </strong>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- KONTEN TAB --}}
    @if($tab === 'laba-rugi')
        @include('laporan.partials.tab-laba-rugi', ['rows' => $rows, 'total' => $total, 'chart' => $chart])
    @endif

    @if($tab === 'penjualan')
        @include('laporan.partials.tab-penjualan', ['rows' => $rows, 'total' => $total, 'chart' => $chart])
    @endif

    @if($tab === 'pembelian')
        @include('laporan.partials.tab-pembelian', ['rows' => $rows, 'total' => $total, 'chart' => $chart])
    @endif

    @if($tab === 'pengeluaran')
        @include('laporan.partials.tab-pengeluaran', ['rows' => $rows, 'total' => $total, 'chart' => $chart])
    @endif

    @if($tab === 'pribadi')
        @include('laporan.partials.tab-pribadi', ['rows' => $rows, 'total' => $total, 'chart' => $chart])
    @endif

    @if($tab === 'rekonsiliasi')
        @include('laporan.partials.tab-rekonsiliasi', ['rows' => $rows, 'total' => $total, 'chart' => $chart, 'list_modal' => $list_modal ?? collect()])
    @endif

    @if($tab === 'kasbon')
        @include('laporan.partials.tab-kasbon', ['rows' => $rows, 'total' => $total, 'chart' => $chart, 'per_karyawan' => $per_karyawan ?? collect()])
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    'use strict';

    window.formatRupiah = function (angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka || 0);
    };

    window.chartOptions = function (isLine) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 8,
                        boxHeight: 8,
                        padding: 12,
                        font: { family: "'Inter', sans-serif", size: 11, weight: '500' },
                        color: '#374151',
                        usePointStyle: true,
                        pointStyle: 'circle',
                    },
                },
                tooltip: {
                    backgroundColor: '#1f2937',
                    padding: 10,
                    cornerRadius: 6,
                    titleFont: { family: "'Inter', sans-serif", size: 12, weight: '600' },
                    bodyFont: { family: "'Inter', sans-serif", size: 11 },
                    callbacks: {
                        label: function (context) {
                            return context.dataset.label + ': ' + window.formatRupiah(context.parsed.y);
                        },
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: "'Inter', sans-serif", size: 10 },
                        color: '#9ca3af',
                        maxRotation: 0,
                        autoSkipPadding: 20,
                    },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.04)', drawBorder: false },
                    ticks: {
                        font: { family: "'Inter', sans-serif", size: 10 },
                        color: '#9ca3af',
                        maxTicksLimit: 6,
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
        };
    };

    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('chartLaporan');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const tab = @json($tab);
        const chartData = @json($chart);

        if (tab === 'laba-rugi') {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Penjualan',
                            data: chartData.penjualan,
                            backgroundColor: '#10b981',
                            borderRadius: 4,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8,
                        },
                        {
                            label: 'Pembelian',
                            data: chartData.pembelian,
                            backgroundColor: '#3b82f6',
                            borderRadius: 4,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8,
                        },
                        {
                            label: 'Pengeluaran',
                            data: chartData.pengeluaran,
                            backgroundColor: '#f59e0b',
                            borderRadius: 4,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8,
                        },
                        {
                            label: 'Laba Bersih',
                            data: chartData.laba_bersih,
                            backgroundColor: '#26476f',
                            borderRadius: 4,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8,
                        },
                    ],
                },
                options: window.chartOptions(),
            });
        }

        if (tab === 'rekonsiliasi') {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Modal Disetor (Debit)',
                            data: chartData.modal,
                            backgroundColor: '#10b981',
                            borderRadius: 4,
                        },
                        {
                            label: 'Prive Ditarik (Pribadi)',
                            data: chartData.prive,
                            backgroundColor: '#dc2626',
                            borderRadius: 4,
                        }
                    ]
                },
                options: window.chartOptions()
            });
        }

        if (tab === 'penjualan' || tab === 'pembelian' || tab === 'pengeluaran' || tab === 'pribadi') {
            const colors = {
                penjualan:   { border: '#10b981', bg: 'rgba(16, 185, 129, 0.1)',  label: 'Penjualan' },
                pembelian:   { border: '#3b82f6', bg: 'rgba(59, 130, 246, 0.1)',  label: 'Pembelian' },
                pengeluaran: { border: '#dc2626', bg: 'rgba(220, 38, 38, 0.1)',   label: 'Pengeluaran Toko' },
                pribadi:     { border: '#f59e0b', bg: 'rgba(245, 158, 11, 0.1)',   label: 'Pengeluaran Pribadi' },
            };
            const c = colors[tab];

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: c.label,
                        data: chartData.values,
                        borderColor: c.border,
                        backgroundColor: c.bg,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: c.border,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                    }],
                },
                options: window.chartOptions(true),
            });
        }
    });

})();
</script>
@endpush