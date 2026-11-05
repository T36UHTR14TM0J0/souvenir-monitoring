@extends('layouts.app')

@php
    $pageMeta = match($tab) {
        'penjualan' => [
            'title' => 'Laporan Penjualan Marketplace',
            'subtitle' => 'Analisis omzet kotor, potongan admin, dan dana bersih dari Shopee, Lazada & TikTok Shop.',
            'icon' => 'fas fa-bag-shopping',
            'search_placeholder' => 'Cari kode transaksi, no pesanan marketplace, pelanggan, channel, atau resi...',
        ],
        'pengeluaran' => [
            'title' => 'Laporan Pengeluaran & Belanja Bahan',
            'subtitle' => 'Monitoring pengeluaran toko: modal belanja bahan baku, operasional murni, ads, dan kasbon tim.',
            'icon' => 'fas fa-money-bill-wave',
            'search_placeholder' => 'Cari kode pengeluaran, kategori, atau keterangan biaya...',
        ],
        'kasbon' => [
            'title' => 'Laporan Buku Kasbon Tim',
            'subtitle' => 'Monitoring piutang kasbon anggota tim produksi & admin beserta status pelunasannya.',
            'icon' => 'fas fa-hand-holding-dollar',
            'search_placeholder' => 'Cari kode kasbon, nama anggota tim peminjam, atau keterangan...',
        ],
        default => [
            'title' => 'Laporan Laba/Rugi Bisnis',
            'subtitle' => 'Ringkasan komprehensif Laporan Keuangan: Penghasilan Bersih Marketplace, HPP Bahan Baku, Biaya Bisnis & Laba Bersih.',
            'icon' => 'fas fa-chart-line',
            'search_placeholder' => 'Cari nama bulan atau tanggal periode laporan...',
        ],
    };

    $periodeText = $bulan === 'all'
        ? 'Januari – Desember ' . $tahun
        : ($bulanList[(int) $bulan] ?? '') . ' ' . $tahun;
@endphp

@section('title', $pageMeta['title'])

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
    .search-input-group .form-control::placeholder {
        color: #94a3b8;
        font-size: 0.8125rem;
    }

    /* THEAD TABLE HEADER NAVY GRADIENT (PERSIS KATALOG SOUVENIR) */
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
    .table-produk tfoot td {
        padding: 0.8rem 0.85rem;
        background-color: #f8fafc;
        border-top: 2px solid #cbd5e1;
        font-weight: 800;
        font-size: 0.82rem;
        color: #0f172a;
        white-space: nowrap;
    }

    /* FREEZE / STICKY ACTION COLUMN PERSIS KATALOG SOUVENIR */
    .col-freeze-aksi {
        position: sticky !important;
        right: 0 !important;
        z-index: 5;
        min-width: 100px;
        width: 100px;
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

    /* UNIFIED TOOLBAR ACTION BUTTONS PERSIS KATALOG SOUVENIR */
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
    .toolbar-filter-card,
    .toolbar-filter-card .card-body {
        overflow: visible !important;
    }
    .dropdown-export-menu {
        position: absolute !important;
        top: calc(100% + 6px) !important;
        right: 0 !important;
        z-index: 1060 !important;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.14) !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.6rem !important;
        background: #ffffff !important;
        display: none;
    }
    .dropdown-export-menu.show {
        display: block !important;
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
    .code-badge,
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

    .chart-container {
        position: relative;
        height: 290px;
        width: 100%;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- 1. HEADER (SAMA PERSIS KONSEP KATALOG SOUVENIR - TANPA TOMBOL DI HEADER) --}}
    <x-page-header
        :icon="$pageMeta['icon']"
        :title="$pageMeta['title']"
        :subtitle="$pageMeta['subtitle']"
    />

    {{-- 2. KPI STATS SUMMARY (4 CARDS - IKON DI KIRI PERSIS KATALOG SOUVENIR) --}}
    <div class="row g-3 mb-3">
        @if($tab === 'penjualan')
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern kpi-accent-indigo">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #eef2ff; color: #4f46e5;">
                            <i class="fas fa-bag-shopping"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Omzet Kotor (Valid)</div>
                            <div class="kpi-value">Rp {{ number_format($total['penjualan'], 0, ',', '.') }}</div>
                            <div class="kpi-subtext">{{ number_format($total['transaksi'], 0, ',', '.') }} pesanan aktif/selesai</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern kpi-accent-danger">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #fef2f2; color: #dc2626;">
                            <i class="fas fa-percent"></i>
                        </div>
                        <div>
                            <div class="kpi-label" style="color: #dc2626;">Potongan Admin MP</div>
                            <div class="kpi-value text-danger">Rp {{ number_format($total['biaya_admin'] ?? 0, 0, ',', '.') }}</div>
                            <div class="kpi-subtext">Diskon Produk: Rp {{ number_format($total['diskon'], 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern kpi-accent-success">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div>
                            <div class="kpi-label" style="color: #059669;">Penghasilan Bersih Seller</div>
                            <div class="kpi-value text-success">Rp {{ number_format($total['penghasilan_bersih'] ?? $total['penjualan'], 0, ',', '.') }}</div>
                            <div class="kpi-subtext">Dana bersih masuk saldo toko</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern kpi-accent-info">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-gift"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Total Souvenir Terjual</div>
                            <div class="kpi-value text-primary">{{ number_format($total['item'], 0, ',', '.') }} pcs</div>
                            <div class="kpi-subtext">
                                @if(($total['batal_retur_count'] ?? 0) > 0)
                                    <span class="text-danger">{{ $total['batal_retur_count'] }} Batal/Retur (Rp {{ number_format($total['batal_retur_nominal'], 0, ',', '.') }})</span>
                                @else
                                    0 pesanan batal / retur
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($tab === 'pengeluaran')
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern kpi-accent-danger">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #fef2f2; color: #dc2626;">
                            <i class="fas fa-money-bill-transfer"></i>
                        </div>
                        <div>
                            <div class="kpi-label" style="color: #dc2626;">Total Biaya Bisnis</div>
                            <div class="kpi-value text-danger">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</div>
                            <div class="kpi-subtext">{{ number_format($total['transaksi'], 0, ',', '.') }} transaksi operasional toko</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern kpi-accent-warning">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #fffbeb; color: #d97706;">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div>
                            <div class="kpi-label" style="color: #d97706;">Biaya Iklan (Ads MP)</div>
                            <div class="kpi-value" style="color: #d97706;">Rp {{ number_format($total['iklan'] ?? 0, 0, ',', '.') }}</div>
                            <div class="kpi-subtext">Shopee, Lazada & TikTok Ads</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern kpi-accent-info">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Packing & Pengemasan</div>
                            <div class="kpi-value text-primary">Rp {{ number_format($total['packing'] ?? 0, 0, ',', '.') }}</div>
                            <div class="kpi-subtext">Bubble wrap, lakban, kardus</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern kpi-accent-indigo">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #eef2ff; color: #4f46e5;">
                            <i class="fas fa-gears"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Operasional Lainnya</div>
                            <div class="kpi-value">Rp {{ number_format($total['operasional_lain'] ?? 0, 0, ',', '.') }}</div>
                            <div class="kpi-subtext">Gaji, listrik, bensin & sewa</div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($tab === 'kasbon')
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern kpi-accent-indigo">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #eef2ff; color: #4f46e5;">
                            <i class="fas fa-hand-holding-dollar"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Total Kasbon Diberikan</div>
                            <div class="kpi-value">Rp {{ number_format($total['total_kasbon'], 0, ',', '.') }}</div>
                            <div class="kpi-subtext">{{ $total['transaksi'] }} catatan transaksi kasbon</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern kpi-accent-success">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                            <i class="fas fa-circle-check"></i>
                        </div>
                        <div>
                            <div class="kpi-label" style="color: #059669;">Kasbon Sudah Lunas</div>
                            <div class="kpi-value text-success">Rp {{ number_format($total['total_lunas'], 0, ',', '.') }}</div>
                            <div class="kpi-subtext">Sudah dikembalikan / potong gaji</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern kpi-accent-danger">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #fef2f2; color: #dc2626;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div class="kpi-label" style="color: #dc2626;">Sisa Kasbon Belum Lunas</div>
                            <div class="kpi-value text-danger">Rp {{ number_format($total['total_belum_lunas'], 0, ',', '.') }}</div>
                            <div class="kpi-subtext">Piutang aktif tim toko</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern kpi-accent-info">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Anggota Tim Peminjam</div>
                            <div class="kpi-value text-primary">{{ ($per_karyawan ?? collect())->count() }} Orang</div>
                            <div class="kpi-subtext">Tercatat pada periode ini</div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Default: Laba/Rugi Bisnis --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern kpi-accent-success">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                            <i class="fas fa-sack-dollar"></i>
                        </div>
                        <div>
                            <div class="kpi-label" style="color: #059669;">Penghasilan Bersih MP</div>
                            <div class="kpi-value text-success">Rp {{ number_format($total['penghasilan_bersih'], 0, ',', '.') }}</div>
                            <div class="kpi-subtext">Omzet Kotor: Rp {{ number_format($total['penjualan'], 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern kpi-accent-info">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-boxes-packing"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Pembelian Bahan (HPP)</div>
                            <div class="kpi-value text-primary">Rp {{ number_format($total['pembelian'], 0, ',', '.') }}</div>
                            <div class="kpi-subtext">Laba Kotor: <strong>Rp {{ number_format($total['laba_kotor'], 0, ',', '.') }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern kpi-accent-warning">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #fffbeb; color: #d97706;">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div>
                            <div class="kpi-label" style="color: #d97706;">Biaya Operasional & Ads</div>
                            <div class="kpi-value" style="color: #d97706;">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</div>
                            <div class="kpi-subtext">Iklan MP: Rp {{ number_format($total['biaya_iklan'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card-modern {{ $total['laba_bersih'] >= 0 ? 'kpi-accent-indigo' : 'kpi-accent-danger' }}">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: {{ $total['laba_bersih'] >= 0 ? '#eef2ff' : '#fef2f2' }}; color: {{ $total['laba_bersih'] >= 0 ? '#4f46e5' : '#dc2626' }};">
                            <i class="fas fa-{{ $total['laba_bersih'] >= 0 ? 'arrow-trend-up' : 'arrow-trend-down' }}"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Laba Bersih Bisnis</div>
                            <div class="kpi-value {{ $total['laba_bersih'] >= 0 ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($total['laba_bersih'], 0, ',', '.') }}
                            </div>
                            <div class="kpi-subtext">Margin Bersih: <strong>{{ $total['margin_bersih'] ?? 0 }}%</strong> dari Omzet</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- 3. FILTER & SEARCH TOOLBAR (STRUKTUR 100% PERSIS KATALOG SOUVENIR & KAS MASUK) --}}
    @php
        $activeFilterCount = 0;
        if ($bulan !== 'all') $activeFilterCount++;
        if ((int) $tahun !== (int) now()->year) $activeFilterCount++;
    @endphp

    <div class="card card-modern toolbar-filter-card bg-white mb-3" style="overflow: visible !important;">
        <div class="card-body p-3" style="overflow: visible !important;">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-stretch align-items-xl-center gap-2.5">
                {{-- Form Pencarian Cepat --}}
                <div class="flex-grow-1">
                    <form method="GET" action="{{ route('laporan.keuangan') }}" class="m-0">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <input type="hidden" name="bulan" value="{{ $bulan }}">
                        <input type="hidden" name="tahun" value="{{ $tahun }}">

                        <div class="input-group search-input-group shadow-none">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-3 pe-2">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   class="form-control border-start-0 ps-1"
                                   placeholder="{{ $pageMeta['search_placeholder'] }}"
                                   value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('laporan.keuangan', ['tab' => $tab, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
                                   class="input-group-text bg-white text-muted text-decoration-none border-start-0 pe-3"
                                   title="Hapus Pencarian">
                                    <i class="fas fa-times text-secondary"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Action Buttons: Filter Data, Reset, Export PDF & Export Excel (Sejajar Samping Search) --}}
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" id="btnBukaFilterLaporan" class="btn btn-filter-custom position-relative" data-bs-toggle="modal" data-bs-target="#modalFilterLaporan">
                        <i class="fas fa-filter"></i>
                        <span>Filter Data</span>
                        @if($activeFilterCount > 0)
                            <span class="badge bg-white text-dark rounded-pill ms-1" style="font-size: 0.68rem; font-weight: 700; padding: 0.2rem 0.45rem;">{{ $activeFilterCount }}</span>
                        @endif
                    </button>

                    @if($activeFilterCount > 0 || request()->filled('search'))
                        <a href="{{ route('laporan.keuangan', ['tab' => $tab]) }}" class="btn btn-reset-custom" title="Hapus Semua Filter">
                            <i class="fas fa-redo"></i>
                            <span class="d-none d-sm-inline">Reset</span>
                        </a>
                    @endif

                    @can('laporan-export')
                        <a href="{{ route('laporan.keuangan.export-pdf', ['tab' => $tab, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
                           class="btn btn-secondary-custom">
                            <i class="fas fa-file-pdf"></i>
                            <span>Export PDF</span>
                        </a>

                        <div class="dropdown d-inline-block position-relative" style="overflow: visible;">
                            <button type="button"
                                    class="btn btn-primary-custom shadow-sm"
                                    id="dropdownExportExcel"
                                    data-bs-toggle="dropdown"
                                    data-bs-display="static"
                                    aria-expanded="false"
                                    title="Pilihan Format Export Excel">
                                <i class="fas fa-file-excel"></i>
                                <span>Export Excel</span>
                                <i class="fas fa-chevron-down ms-1" style="font-size: 0.65rem;"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-export-menu shadow border-0" id="menuExportExcel" aria-labelledby="dropdownExportExcel" style="font-size: 0.8125rem; min-width: 290px; z-index: 1060;">
                                <li>
                                    <h6 class="dropdown-header text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.05em; color: #64748b;">Pilihan Format Excel</h6>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2.5" href="{{ route('laporan.keuangan.export-excel', ['tab' => $tab, 'bulan' => $bulan, 'tahun' => $tahun]) }}">
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 30px; height: 30px; background: #eef2ff; color: #26476f; flex-shrink: 0;">
                                            <i class="fas fa-table"></i>
                                        </span>
                                        <div>
                                            <div class="fw-semibold text-dark">Format Standar Laporan</div>
                                            <div class="text-muted" style="font-size: 0.71rem;">Sesuai tab {{ $pageMeta[$tab]['title'] ?? ucfirst($tab) }}</div>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2.5" href="{{ route('laporan.keuangan.export-excel-klien', ['bulan' => $bulan, 'tahun' => $tahun]) }}">
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 30px; height: 30px; background: #ecfdf5; color: #059669; flex-shrink: 0;">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </span>
                                        <div>
                                            <div class="fw-semibold text-dark">Format Asli Klien (Buku Kas)</div>
                                            <div class="text-muted" style="font-size: 0.71rem;">Rincian Pembelanjaan (Operasional vs Modal)</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endcan
                </div>
            </div>

            {{-- ACTIVE FILTER & PERIODE CHIPS --}}
            <div class="d-flex flex-wrap align-items-center gap-2 mt-2.5 pt-2.5 border-top">
                <span class="small text-muted fw-bold me-1" style="font-size: 0.72rem;">Periode &amp; Filter Aktif:</span>

                <span class="filter-chip" style="background: #f8fafc; color: #334155; border-color: #cbd5e1;">
                    <i class="fas fa-calendar-check me-1 text-primary"></i> Periode: <strong>{{ $periodeText }}</strong>
                </span>

                @if(request('search'))
                    <span class="filter-chip">
                        <i class="fas fa-search me-1"></i> "{{ request('search') }}"
                        <a href="{{ route('laporan.keuangan', ['tab' => $tab, 'bulan' => $bulan, 'tahun' => $tahun]) }}"><i class="fas fa-times"></i></a>
                    </span>
                @endif

                @if($bulan !== 'all')
                    <span class="filter-chip">
                        <i class="fas fa-calendar-day me-1 text-primary"></i> Bulan: {{ $bulanList[(int) $bulan] ?? $bulan }}
                        <a href="{{ route('laporan.keuangan', ['tab' => $tab, 'bulan' => 'all', 'tahun' => $tahun, 'search' => request('search')]) }}"><i class="fas fa-times"></i></a>
                    </span>
                @endif

                @if((int) $tahun !== (int) now()->year)
                    <span class="filter-chip">
                        <i class="fas fa-calendar me-1 text-primary"></i> Tahun: {{ $tahun }}
                        <a href="{{ route('laporan.keuangan', ['tab' => $tab, 'bulan' => $bulan, 'tahun' => now()->year, 'search' => request('search')]) }}"><i class="fas fa-times"></i></a>
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- 4. KONTEN CHART & TABEL LAPORAN AKTIF --}}
    @if($tab === 'laba-rugi')
        @include('laporan.partials.tab-laba-rugi', ['rows' => $rows, 'total' => $total, 'chart' => $chart])
    @elseif($tab === 'penjualan')
        @include('laporan.partials.tab-penjualan', ['rows' => $rows, 'total' => $total, 'chart' => $chart, 'per_channel' => $per_channel ?? collect()])
    @elseif($tab === 'pengeluaran')
        @include('laporan.partials.tab-pengeluaran', ['rows' => $rows, 'total' => $total, 'chart' => $chart])
    @elseif($tab === 'kasbon')
        @include('laporan.partials.tab-kasbon', ['rows' => $rows, 'total' => $total, 'chart' => $chart, 'per_karyawan' => $per_karyawan ?? collect()])
    @endif

</div>

{{-- MODAL FILTER LAPORAN (SAMA PERSIS DESAIN MODAL FILTER KATALOG SOUVENIR & KAS MASUK) --}}
<div class="modal fade" id="modalFilterLaporan" tabindex="-1" aria-labelledby="modalFilterLaporanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.875rem;">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-filter fa-lg"></i>
                    <h5 class="modal-title fw-bold fs-6 mb-0 text-white" id="modalFilterLaporanLabel">Filter {{ $pageMeta['title'] }}</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('laporan.keuangan') }}">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="modal_tab">Jenis Laporan &amp; Analitik</label>
                            <select name="tab" id="modal_tab" class="form-select">
                                @can('laporan-laba-rugi')
                                    <option value="laba-rugi" {{ $tab === 'laba-rugi' ? 'selected' : '' }}>Laba / Rugi Bisnis</option>
                                @endcan
                                @can('laporan-penjualan')
                                    <option value="penjualan" {{ $tab === 'penjualan' ? 'selected' : '' }}>Laporan Penjualan Marketplace</option>
                                @endcan
                                @can('laporan-pengeluaran')
                                    <option value="pengeluaran" {{ $tab === 'pengeluaran' ? 'selected' : '' }}>Laporan Pengeluaran &amp; Belanja Bahan</option>
                                @endcan
                                @can('laporan-kasbon')
                                    <option value="kasbon" {{ $tab === 'kasbon' ? 'selected' : '' }}>Laporan Kasbon &amp; Piutang Tim</option>
                                @endcan
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_bulan">Bulan Periode</label>
                            <select name="bulan" id="modal_bulan" class="form-select">
                                <option value="all" {{ $bulan === 'all' ? 'selected' : '' }}>Semua Bulan (1 Tahun Penuh)</option>
                                @foreach($bulanList as $num => $nama)
                                    <option value="{{ $num }}" {{ $bulan !== 'all' && (int) $bulan === $num ? 'selected' : '' }}>
                                        {{ $nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_tahun">Tahun Periode</label>
                            <select name="tahun" id="modal_tahun" class="form-select">
                                @foreach($tahunList as $th)
                                    <option value="{{ $th }}" {{ (int) $tahun === (int) $th ? 'selected' : '' }}>
                                        Tahun {{ $th }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top py-3 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('laporan.keuangan', ['tab' => $tab]) }}" class="btn btn-reset-custom">
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    'use strict';

    window.formatRupiah = function (angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka || 0);
    };

    window.chartOptions = function (isUnitQty) {
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
                        font: { family: "'Inter', sans-serif", size: 11, weight: '600' },
                        color: '#334155',
                        usePointStyle: true,
                        pointStyle: 'circle',
                    },
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 10,
                    cornerRadius: 6,
                    titleFont: { family: "'Inter', sans-serif", size: 12, weight: '700' },
                    bodyFont: { family: "'Inter', sans-serif", size: 11 },
                    callbacks: {
                        label: function (context) {
                            if (isUnitQty) {
                                return context.dataset.label + ': ' + new Intl.NumberFormat('id-ID').format(context.parsed.y) + ' pcs';
                            }
                            return context.dataset.label + ': ' + window.formatRupiah(context.parsed.y);
                        },
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: "'Inter', sans-serif", size: 10, weight: '500' },
                        color: '#64748b',
                        maxRotation: 0,
                        autoSkipPadding: 20,
                    },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(15, 23, 42, 0.05)', drawBorder: false },
                    ticks: {
                        font: { family: "'Inter', sans-serif", size: 10 },
                        color: '#64748b',
                        maxTicksLimit: 6,
                        callback: function (value) {
                            if (isUnitQty) {
                                return new Intl.NumberFormat('id-ID').format(value) + ' pcs';
                            }
                            if (Math.abs(value) >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1).replace('.', ',') + ' jt';
                            }
                            if (Math.abs(value) >= 1000) {
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
        // Modal Filter Handler (identik dengan Katalog Souvenir & Kas Masuk)
        const modalFilterEl = document.getElementById('modalFilterLaporan');
        if (modalFilterEl && modalFilterEl.parentElement !== document.body) {
            document.body.appendChild(modalFilterEl);
        }

        const btnBukaFilter = document.getElementById('btnBukaFilterLaporan');
        if (btnBukaFilter && modalFilterEl) {
            btnBukaFilter.addEventListener('click', function (e) {
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
            modalFilterEl.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
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

        // Dropdown Export Excel Handler (Vanilla JS + Bootstrap Fallback)
        const btnExport = document.getElementById('dropdownExportExcel');
        const menuExport = document.getElementById('menuExportExcel');
        if (btnExport && menuExport) {
            btnExport.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const isShown = menuExport.classList.contains('show');
                if (isShown) {
                    menuExport.classList.remove('show');
                    btnExport.setAttribute('aria-expanded', 'false');
                } else {
                    menuExport.classList.add('show');
                    btnExport.setAttribute('aria-expanded', 'true');
                }
            });

            document.addEventListener('click', function (e) {
                if (!btnExport.contains(e.target) && !menuExport.contains(e.target)) {
                    menuExport.classList.remove('show');
                    btnExport.setAttribute('aria-expanded', 'false');
                }
            });
        }

        // Chart.js Initialization
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
                            label: 'Penghasilan Bersih MP',
                            data: chartData.penjualan,
                            backgroundColor: '#10b981',
                            borderRadius: 4,
                            barPercentage: 0.72,
                            categoryPercentage: 0.8,
                        },
                        {
                            label: 'Modal Belanja Bahan Baku',
                            data: chartData.pembelian,
                            backgroundColor: '#3b82f6',
                            borderRadius: 4,
                            barPercentage: 0.72,
                            categoryPercentage: 0.8,
                        },
                        {
                            label: 'Biaya Operasional Toko',
                            data: chartData.pengeluaran,
                            backgroundColor: '#f59e0b',
                            borderRadius: 4,
                            barPercentage: 0.72,
                            categoryPercentage: 0.8,
                        },
                        {
                            label: 'Laba Bersih',
                            data: chartData.laba_bersih,
                            backgroundColor: '#26476f',
                            borderRadius: 4,
                            barPercentage: 0.72,
                            categoryPercentage: 0.8,
                        },
                    ],
                },
                options: window.chartOptions(false),
            });
        } else if (['penjualan', 'pengeluaran'].includes(tab)) {
            const colors = {
                penjualan:   { border: '#10b981', bg: 'rgba(16, 185, 129, 0.12)', label: 'Omzet Penjualan Valid' },
                pengeluaran: { border: '#ef4444', bg: 'rgba(239, 68, 68, 0.12)',  label: 'Biaya Pengeluaran & Bahan' },
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
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: c.border,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 3.5,
                        pointHoverRadius: 5,
                    }],
                },
                options: window.chartOptions(false),
            });
        }
    });
})();
</script>
@endpush
