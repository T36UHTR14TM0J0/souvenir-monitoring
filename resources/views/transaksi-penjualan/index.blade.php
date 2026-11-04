@extends('layouts.app')

@section('title', 'Transaksi Penjualan')

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

    /* ============================================
       CARD MODERN
       ============================================ */
    .card-modern {
        border: 1px solid var(--gray-200);
        border-radius: 0.875rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.06);
        transition: box-shadow 0.2s ease;
        overflow: hidden;
    }
    .card-modern:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 2px 4px rgba(0, 0, 0, 0.06);
    }

    /* ============================================
       PAGE HEADER
       ============================================ */
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 0.625rem;
        margin-bottom: 0.25rem;
    }
    .page-title-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--sidebar-bg-1) 0%, var(--sidebar-bg-3) 100%);
        color: var(--sidebar-cream);
        border: 1px solid rgba(95, 143, 194, 0.3);
        border-radius: 0.625rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(38, 71, 111, 0.25);
    }
    .page-subtitle {
        font-size: 0.875rem;
        color: var(--gray-500);
        margin-bottom: 0;
    }

    /* ============================================
       BUTTONS
       ============================================ */
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--sidebar-bg-1) 0%, var(--sidebar-bg-3) 100%);
        border: 1px solid var(--sidebar-bg-1);
        color: #fff;
        font-weight: 500;
        padding: 0.625rem 1.125rem;
        border-radius: 0.625rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        box-shadow: 0 2px 8px rgba(38, 71, 111, 0.25);
        text-decoration: none;
        height: 42px;
    }
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, var(--sidebar-bg-2) 0%, var(--sidebar-bg-3) 100%);
        border-color: var(--sidebar-bg-2);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(38, 71, 111, 0.35);
    }
    .btn-primary-custom:active { transform: translateY(0); }

    /* ============================================
       KPI CARDS
       ============================================ */
    .kpi-card {
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: 0.875rem;
        padding: 1.25rem;
        transition: all 0.2s ease;
        height: 100%;
    }
    .kpi-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        transform: translateY(-2px);
    }
    .kpi-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }
    .kpi-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        letter-spacing: -0.02em;
        margin-bottom: 0.25rem;
    }
    .kpi-icon {
        width: 40px;
        height: 40px;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .kpi-icon-navy  { background: rgba(38, 71, 111, 0.1); color: var(--sidebar-bg-1); }
    .kpi-icon-green { background: #ecfdf5; color: #059669; }
    .kpi-icon-amber { background: #fffbeb; color: #d97706; }

    /* ============================================
       FILTER BAR
       ============================================ */
    .filter-bar {
        padding: 1.25rem 1.5rem;
        background-color: #fff;
        border: 1px solid var(--gray-200);
        border-radius: 0.875rem;
        margin-bottom: 1.25rem;
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
    .search-wrapper {
        position: relative;
    }
    .search-wrapper i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-400);
        font-size: 0.875rem;
        pointer-events: none;
        z-index: 3;
    }

    /* ============================================
       FORM CONTROLS
       ============================================ */
    .form-control-modern,
    .form-select-modern {
        border: 1px solid var(--gray-200);
        border-radius: 0.5rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        transition: all 0.15s ease;
        height: 42px;
        background-color: #fff;
        color: var(--gray-800);
        width: 100%;
        cursor: pointer;
    }
    .form-control-modern:focus,
    .form-select-modern:focus {
        border-color: var(--sidebar-bg-1);
        box-shadow: 0 0 0 3px rgba(38, 71, 111, 0.1);
        outline: none;
    }
    .form-control-modern.ps-5 { padding-left: 2.5rem; }

    /* ============================================
       TABLE MODERN
       ============================================ */
    .table-modern { margin-bottom: 0; }
    .table-modern thead {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
    }
    .table-modern thead th {
        background-color: transparent;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border-bottom: none;
        padding: 1rem;
        white-space: nowrap;
        position: relative;
    }
    .table-modern thead th:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 25%;
        height: 50%;
        width: 1px;
        background-color: rgba(255, 255, 255, 0.15);
    }
    .table-modern tbody td {
        vertical-align: middle;
        border-bottom: 1px solid var(--gray-100);
        color: var(--gray-700);
        padding: 1rem;
        background-color: #fff;
    }
    .table-modern tbody tr { transition: background-color 0.15s ease; }
    .table-modern tbody tr:hover td { background-color: #fafbfc; }
    .table-modern tbody tr:last-child td { border-bottom: none; }

    /* ============================================
       BADGES & KODE
       ============================================ */
    .kode-transaksi {
        font-family: 'Courier New', monospace;
        font-size: 0.8rem;
        font-weight: 600;
        color: #26476f;
        background-color: #eff6ff;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        border: 1px solid #bfdbfe;
        display: inline-block;
    }

    .badge-item-count {
        background-color: var(--gray-100);
        color: var(--gray-600);
        border: 1px solid var(--gray-200);
        font-weight: 500;
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .grand-total {
        font-weight: 700;
        color: #059669;
        font-size: 0.95rem;
    }

    /* ============================================
       ACTION BUTTONS
       ============================================ */
    .btn-icon {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        border: 1px solid transparent;
        background: transparent;
        transition: all 0.18s ease;
        font-size: 0.8rem;
    }
    .btn-view { color: #3b82f6; }
    .btn-view:hover { background-color: #eff6ff; border-color: #bfdbfe; color: #2563eb; }
    .btn-delete { color: #ef4444; }
    .btn-delete:hover { background-color: #fef2f2; border-color: #fecaca; color: #dc2626; }

    /* ============================================
       EMPTY STATE
       ============================================ */
    .empty-icon {
        width: 64px;
        height: 64px;
        background-color: var(--gray-100);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--gray-400);
    }

    /* ============================================
       PAGINATION NAVY
       ============================================ */
    .pagination-wrapper {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--gray-100);
        background-color: #fff;
    }
    @media (min-width: 768px) {
        .pagination-wrapper {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }
    .pagination-info { font-size: 0.8rem; color: var(--gray-500); }
    .pagination-info b { color: var(--gray-800); font-weight: 600; }

    .perm-pagination {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        list-style: none;
        padding: 0;
        margin: 0;
        flex-wrap: wrap;
    }
    .perm-pagination li { list-style: none; margin: 0; }
    .perm-pagination .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0.375rem 0.625rem;
        border-radius: 0.5rem;
        border: 1px solid var(--gray-200);
        color: var(--gray-600);
        font-size: 0.85rem;
        font-weight: 500;
        background-color: #fff;
        transition: all 0.15s ease;
        text-decoration: none;
        cursor: pointer;
    }
    .perm-pagination .page-btn:hover {
        background-color: rgba(38, 71, 111, 0.06);
        border-color: rgba(38, 71, 111, 0.25);
        color: var(--sidebar-bg-1);
        transform: translateY(-1px);
    }
    .perm-pagination .page-btn.active {
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%);
        border-color: #26476f;
        color: #fff;
        box-shadow: 0 2px 6px rgba(38, 71, 111, 0.3);
        font-weight: 600;
    }
    .perm-pagination .page-btn.disabled {
        color: var(--gray-300);
        background-color: #fff;
        border-color: var(--gray-100);
        cursor: not-allowed;
        pointer-events: none;
        opacity: 0.6;
    }
    .perm-pagination .page-btn i { font-size: 0.75rem; }
    .perm-pagination .page-dots {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        color: var(--gray-400);
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (max-width: 768px) {
        .page-title { font-size: 1.25rem; }
        .page-title-icon { width: 36px; height: 36px; }
        .table-modern thead th,
        .table-modern tbody td { padding: 0.75rem 0.5rem; }
        .perm-pagination .page-btn {
            min-width: 34px;
            height: 34px;
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        .perm-pagination li.page-number:not(.active) { display: none; }
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
                    <i class="fas fa-shopping-cart"></i>
                </span>
                Transaksi Penjualan
            </h1>
            <p class="page-subtitle">Kelola transaksi penjualan produk toko souvenir.</p>
        </div>
        @can('penjualan-create')
        <a href="{{ route('transaksi-penjualan.create') }}" class="btn btn-primary-custom">
            <i class="fas fa-plus"></i>
            <span>Tambah Penjualan</span>
        </a>
        @endcan
    </div>

    {{-- KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Penjualan Hari Ini</div>
                        <div class="kpi-value">Rp {{ number_format($totalHariIni, 0, ',', '.') }}</div>
                    </div>
                    <div class="kpi-icon kpi-icon-green">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Penjualan Bulan Ini</div>
                        <div class="kpi-value">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</div>
                    </div>
                    <div class="kpi-icon kpi-icon-navy">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Total Transaksi Bulan Ini</div>
                        <div class="kpi-value">
                            {{ number_format($totalTransaksi, 0, ',', '.') }}
                            <span style="font-size: 0.875rem; font-weight: 500; color: #6b7280;">transaksi</span>
                        </div>
                    </div>
                    <div class="kpi-icon kpi-icon-amber">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER BAR --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('transaksi-penjualan.index') }}" id="filterForm">
            <div class="row g-3 align-items-end">
                {{-- Search --}}
                <div class="col-12 col-md-6">
                    <label class="filter-label">Pencarian</label>
                    <div class="search-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search"
                               class="form-control form-control-modern ps-5"
                               placeholder="Cari kode / pelanggan..."
                               value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Dari Tanggal --}}
                <div class="col-6 col-md-2">
                    <label class="filter-label">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal"
                           class="form-control form-control-modern"
                           value="{{ request('dari_tanggal') }}">
                </div>

                {{-- Sampai Tanggal --}}
                <div class="col-6 col-md-2">
                    <label class="filter-label">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal"
                           class="form-control form-control-modern"
                           value="{{ request('sampai_tanggal') }}">
                </div>

                {{-- Actions --}}
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary-custom flex-fill justify-content-center">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('transaksi-penjualan.index') }}"
                       class="btn-icon"
                       title="Reset"
                       style="border: 1px solid var(--gray-200); color: var(--gray-600); width: 42px; height: 42px; flex-shrink: 0;">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>

            {{-- Info filter aktif --}}
            @if(request()->hasAny(['search', 'dari_tanggal', 'sampai_tanggal']))
            <div class="mt-3 pt-3" style="border-top: 1px dashed var(--gray-200);">
                <div class="d-flex align-items-center gap-2 flex-wrap" style="font-size: 0.8rem; color: var(--gray-600);">
                    <i class="fas fa-filter" style="color: #26476f;"></i>
                    <span>Filter aktif:</span>

                    @if(request('search'))
                        <span class="badge" style="background: rgba(38, 71, 111, 0.08); color: #26476f; border: 1px solid rgba(38, 71, 111, 0.15); font-weight: 500; font-size: 0.75rem; padding: 0.375rem 0.75rem; border-radius: 999px;">
                            <i class="fas fa-search me-1"></i>"{{ request('search') }}"
                        </span>
                    @endif

                    @if(request('dari_tanggal'))
                        <span class="badge" style="background: rgba(38, 71, 111, 0.08); color: #26476f; border: 1px solid rgba(38, 71, 111, 0.15); font-weight: 500; font-size: 0.75rem; padding: 0.375rem 0.75rem; border-radius: 999px;">
                            <i class="fas fa-calendar-alt me-1"></i>Dari: {{ \Carbon\Carbon::parse(request('dari_tanggal'))->translatedFormat('d M Y') }}
                        </span>
                    @endif

                    @if(request('sampai_tanggal'))
                        <span class="badge" style="background: rgba(38, 71, 111, 0.08); color: #26476f; border: 1px solid rgba(38, 71, 111, 0.15); font-weight: 500; font-size: 0.75rem; padding: 0.375rem 0.75rem; border-radius: 999px;">
                            <i class="fas fa-calendar-alt me-1"></i>Sampai: {{ \Carbon\Carbon::parse(request('sampai_tanggal'))->translatedFormat('d M Y') }}
                        </span>
                    @endif

                    <span style="color: var(--gray-500);">
                        → <b style="color: var(--gray-800);">{{ $transaksis->total() }}</b> hasil
                    </span>
                </div>
            </div>
            @endif
        </form>
    </div>

    {{-- TABLE --}}
    <div class="card card-modern bg-white overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 60px;">No</th>
                            <th style="width: 150px;">Kode Transaksi</th>
                            <th style="width: 130px;">Tanggal</th>
                            <th>Pelanggan</th>
                            <th style="width: 100px;">Items</th>
                            <th class="text-end text-nowrap col-nominal" style="width: 170px; min-width: 170px;">Grand Total</th>
                            <th class="text-center col-aksi" style="width: 70px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $index => $trx)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-medium" style="color: #6b7280;">{{ $transaksis->firstItem() + $index }}</span>
                            </td>
                            <td>
                                <span class="kode-transaksi">{{ $trx->kode_transaksi }}</span>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem; color: #374151;">
                                    {{ $trx->tanggal->translatedFormat('d M Y') }}
                                </div>
                                <div style="font-size: 0.75rem; color: #9ca3af;">
                                    <i class="far fa-clock me-1"></i>{{ $trx->created_at->format('H:i') }} WIB
                                </div>
                            </td>
                            <td>
                                @if($trx->nama_pelanggan)
                                    <span style="font-size: 0.875rem; color: #374151;">
                                        <i class="fas fa-user me-1" style="color: #9ca3af; font-size: 0.75rem;"></i>
                                        {{ $trx->nama_pelanggan }}
                                    </span>
                                @else
                                    <span style="font-size: 0.8rem; color: #9ca3af; font-style: italic;">
                                        <i class="fas fa-user-secret me-1"></i>Walk-in
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-item-count">
                                    <i class="fas fa-box"></i>{{ $trx->details->count() }} item
                                </span>
                            </td>
                            <td class="text-end text-nowrap col-nominal" style="width: 170px; min-width: 170px;">
                                <div class="grand-total">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</div>
                                @if($trx->total_diskon > 0)
                                    <div style="font-size: 0.7rem; color: #9ca3af;">
                                        <i class="fas fa-tag me-1"></i>Diskon: Rp {{ number_format($trx->total_diskon, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-center col-aksi">
                                <div class="d-inline-flex flex-column gap-1 align-items-center">
                                    <a href="{{ route('transaksi-penjualan.show', $trx) }}" class="btn-icon btn-view" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('penjualan-delete')
                                        @if($trx->tanggal->isToday())
                                        <button type="button"
                                                class="btn-icon btn-delete btn-delete-trx"
                                                data-url="{{ route('transaksi-penjualan.destroy', $trx) }}"
                                                data-kode="{{ $trx->kode_transaksi }}"
                                                title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-icon mb-3">
                                    <i class="fas fa-shopping-cart fs-3"></i>
                                </div>
                                <h6 class="fw-semibold mb-1" style="color: #374151;">Belum Ada Transaksi</h6>
                                <p class="small mb-3" style="color: #9ca3af;">
                                    @if(request()->hasAny(['search', 'dari_tanggal', 'sampai_tanggal']))
                                        Tidak ada transaksi yang cocok dengan filter.
                                    @else
                                        Belum ada transaksi penjualan tercatat.
                                    @endif
                                </p>
                                @can('penjualan-create')
                                <a href="{{ route('transaksi-penjualan.create') }}" class="btn btn-primary-custom">
                                    <i class="fas fa-plus"></i>
                                    <span>Tambah Transaksi Pertama</span>
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if($transaksis->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Menampilkan
                <b>{{ $transaksis->firstItem() }}</b>-<b>{{ $transaksis->lastItem() }}</b>
                dari <b>{{ $transaksis->total() }}</b> transaksi
            </div>
            <nav aria-label="Page navigation">
                <ul class="perm-pagination">
                    @if ($transaksis->onFirstPage())
                        <li><span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span></li>
                    @else
                        <li><a href="{{ $transaksis->previousPageUrl() }}" class="page-btn" rel="prev"><i class="fas fa-chevron-left"></i></a></li>
                    @endif

                    @php
                        $currentPage = $transaksis->currentPage();
                        $lastPage = $transaksis->lastPage();
                        $window = 2;
                    @endphp

                    @if ($currentPage > $window + 1)
                        <li class="page-number"><a href="{{ $transaksis->url(1) }}" class="page-btn">1</a></li>
                        @if ($currentPage > $window + 2)
                            <li><span class="page-dots">...</span></li>
                        @endif
                    @endif

                    @for ($page = max(1, $currentPage - $window); $page <= min($lastPage, $currentPage + $window); $page++)
                        <li class="page-number {{ $page == $currentPage ? 'active' : '' }}">
                            @if ($page == $currentPage)
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $transaksis->url($page) }}" class="page-btn">{{ $page }}</a>
                            @endif
                        </li>
                    @endfor

                    @if ($currentPage < $lastPage - $window)
                        @if ($currentPage < $lastPage - $window - 1)
                            <li><span class="page-dots">...</span></li>
                        @endif
                        <li class="page-number"><a href="{{ $transaksis->url($lastPage) }}" class="page-btn">{{ $lastPage }}</a></li>
                    @endif

                    @if ($transaksis->hasMorePages())
                        <li><a href="{{ $transaksis->nextPageUrl() }}" class="page-btn" rel="next"><i class="fas fa-chevron-right"></i></a></li>
                    @else
                        <li><span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span></li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
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
(function () {
    'use strict';

    // ============================================
    // FLASH MESSAGES
    // ============================================
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            confirmButtonColor: '#26476f',
            timer: 2500,
            timerProgressBar: true,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: @json(session('error')),
            confirmButtonText: 'OK',
            confirmButtonColor: '#26476f'
        });
    @endif

    // ============================================
    // DELETE TRANSAKSI
    // ============================================
    document.querySelectorAll('.btn-delete-trx').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.dataset.url;
            const kode = this.dataset.kode;

            Swal.fire({
                title: 'Hapus Transaksi?',
                html: `
                    <p style="color: #4b5563; margin-bottom: 0;">
                        Transaksi <b>"${kode}"</b> akan dihapus.
                    </p>
                    <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 0.75rem; margin-top: 0.75rem; text-align: left;">
                        <div style="display: flex; align-items: flex-start; gap: 0.5rem; color: #92400e; font-size: 0.8rem;">
                            <i class="fas fa-info-circle" style="margin-top: 1px;"></i>
                            <div><strong>Stok akan dikembalikan otomatis.</strong></div>
                        </div>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#a8a29e',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteTrxForm');
                    form.action = url;
                    form.submit();
                }
            });
        });
    });

})();
</script>
@endpush