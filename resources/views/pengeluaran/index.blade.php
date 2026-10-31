@extends('layouts.app')

@section('title', 'Pengeluaran')

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

    .card-modern {
        border: 1px solid var(--gray-200);
        border-radius: 0.875rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

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

    .page-subtitle { font-size: 0.875rem; color: var(--gray-500); margin-bottom: 0; }

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
    .kpi-icon-red   { background: #fef2f2; color: #dc2626; }
    .kpi-icon-amber { background: #fffbeb; color: #d97706; }

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

    .kode-pengeluaran {
        font-family: 'Courier New', monospace;
        font-size: 0.8rem;
        font-weight: 600;
        color: #dc2626;
        background-color: #fef2f2;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        border: 1px solid #fecaca;
        display: inline-block;
    }

    .badge-kategori {
        font-weight: 500;
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }
    .badge-operasional  { background-color: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
    .badge-gaji         { background-color: #fdf4ff; color: #86198f; border: 1px solid #f5d0fe; }
    .badge-listrik      { background-color: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .badge-sewa         { background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-transport    { background-color: #f0f9ff; color: #075985; border: 1px solid #bae6fd; }
    .badge-perlengkapan { background-color: #fef3c7; color: #78350f; border: 1px solid #fcd34d; }
    .badge-pemeliharaan { background-color: #f5f3ff; color: #5b21b6; border: 1px solid #ddd6fe; }
    .badge-pajak        { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    .badge-lainnya      { background-color: #f3f4f6; color: #4b5563; border: 1px solid #e5e7eb; }

    .jumlah-display {
        font-weight: 700;
        color: #dc2626;
        font-size: 0.95rem;
    }

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
    .btn-edit { color: #f59e0b; }
    .btn-edit:hover { background-color: #fffbeb; border-color: #fde68a; color: #d97706; }
    .btn-delete { color: #ef4444; }
    .btn-delete:hover { background-color: #fef2f2; border-color: #fecaca; color: #dc2626; }

    .form-control-modern,
    .form-select-modern {
        border: 1px solid var(--gray-200);
        border-radius: 0.5rem;
        padding: 0.5rem 0.875rem;
        font-size: 0.875rem;
        transition: all 0.15s ease;
        height: 42px;
    }
    .form-control-modern:focus,
    .form-select-modern:focus {
        border-color: var(--sidebar-bg-1);
        box-shadow: 0 0 0 3px rgba(38, 71, 111, 0.1);
        outline: none;
    }
    .form-control-modern.ps-5 { padding-left: 2.5rem; }

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

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="page-title">
                <span class="page-title-icon">
                    <i class="fas fa-money-bill-transfer"></i>
                </span>
                Pengeluaran
            </h1>
            <p class="page-subtitle">Kelola biaya operasional dan pengeluaran toko.</p>
        </div>
        @can('pengeluaran-create')
        <a href="{{ route('pengeluaran.create') }}" class="btn btn-primary-custom">
            <i class="fas fa-plus"></i>
            <span>Tambah Pengeluaran</span>
        </a>
        @endcan
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Bisnis Toko (Bulan Ini)</div>
                        <div class="kpi-value" style="font-size: 1.25rem;">Rp {{ number_format($totalBisnisBulanIni, 0, ',', '.') }}</div>
                    </div>
                    <div class="kpi-icon kpi-icon-navy">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Pribadi / Prive (Bulan Ini)</div>
                        <div class="kpi-value" style="font-size: 1.25rem; color: #dc2626;">Rp {{ number_format($totalPribadiBulanIni, 0, ',', '.') }}</div>
                    </div>
                    <div class="kpi-icon kpi-icon-red">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Kasbon Tim (Bulan Ini)</div>
                        <div class="kpi-value" style="font-size: 1.25rem; color: #d97706;">Rp {{ number_format($totalKasbonBulanIni, 0, ',', '.') }}</div>
                    </div>
                    <div class="kpi-icon kpi-icon-amber">
                        <i class="fas fa-hand-holding-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Total Semua Pengeluaran</div>
                        <div class="kpi-value" style="font-size: 1.25rem;">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</div>
                    </div>
                    <div class="kpi-icon" style="background: rgba(38, 71, 111, 0.1); color: #26476f;">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-modern mb-4 bg-white">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ route('pengeluaran.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-3">
                        <div class="position-relative">
                            <i class="fas fa-search position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.875rem;"></i>
                            <input type="text" name="search" class="form-control form-control-modern ps-5"
                                   placeholder="Cari kode, ket, orang..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <select name="tipe_alokasi" class="form-select form-select-modern" style="width: 100%;">
                            <option value="">Semua Alokasi</option>
                            @foreach($tipeAlokasis as $tKey => $tLabel)
                                <option value="{{ $tKey }}" {{ request('tipe_alokasi') == $tKey ? 'selected' : '' }}>
                                    {{ $tLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <select name="kategori" class="form-select form-select-modern" style="width: 100%;">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $key => $label)
                                <option value="{{ $key }}" {{ request('kategori') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="date" name="dari_tanggal" class="form-control form-control-modern"
                               value="{{ request('dari_tanggal') }}">
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="date" name="sampai_tanggal" class="form-control form-control-modern"
                               value="{{ request('sampai_tanggal') }}">
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom flex-fill justify-content-center">
                            <i class="fas fa-filter"></i>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('pengeluaran.index') }}"
                           class="btn-icon"
                           title="Reset"
                           style="border: 1px solid var(--gray-200); color: var(--gray-600); width: 42px; height: 42px; flex-shrink: 0;">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-modern bg-white overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Alokasi</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            <th class="text-end">Jumlah</th>
                            <th class="pe-4 text-end" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengeluarans as $index => $pgl)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-medium" style="color: #6b7280;">{{ $pengeluarans->firstItem() + $index }}</span>
                            </td>
                            <td>
                                <span class="kode-pengeluaran">{{ $pgl->kode_pengeluaran }}</span>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem; color: #374151;">
                                    {{ $pgl->tanggal->format('d M Y') }}
                                </div>
                                <div style="font-size: 0.75rem; color: #9ca3af;">
                                    <i class="far fa-clock me-1"></i>{{ $pgl->created_at->format('H:i') }} WIB
                                </div>
                            </td>
                            <td>
                                @if($pgl->tipe_alokasi === 'bisnis')
                                    <span class="badge" style="background: rgba(38,71,111,0.1); color: #26476f; font-size: 0.75rem;">
                                        <i class="fas fa-store me-1"></i> Bisnis
                                    </span>
                                @elseif($pgl->tipe_alokasi === 'pribadi')
                                    <span class="badge" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; font-size: 0.75rem;">
                                        <i class="fas fa-user-tie me-1"></i> Pribadi
                                    </span>
                                @else
                                    <span class="badge" style="background: #fffbeb; color: #d97706; border: 1px solid #fde68a; font-size: 0.75rem;">
                                        <i class="fas fa-hand-holding-dollar me-1"></i> Kasbon
                                    </span>
                                    @if($pgl->status_kasbon === 'lunas')
                                        <span class="badge bg-success" style="font-size: 0.65rem;">Lunas</span>
                                    @else
                                        <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">Belum</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <span class="badge-kategori badge-{{ $pgl->kategori }}">
                                    <i class="fas fa-tag"></i>
                                    {{ $pgl->kategori_label }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem; color: #374151; max-width: 260px;" class="text-truncate">
                                    @if($pgl->tipe_alokasi === 'kasbon' && $pgl->karyawan_nama)
                                        <span class="fw-bold text-primary">[{{ $pgl->karyawan_nama }}]</span>
                                    @endif
                                    {{ $pgl->keterangan }}
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="jumlah-display">
                                    - Rp {{ number_format($pgl->jumlah, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('pengeluaran.show', $pgl) }}" class="btn-icon btn-view" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('pengeluaran-edit')
                                    <a href="{{ route('pengeluaran.edit', $pgl) }}" class="btn-icon btn-edit" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    @endcan
                                    @can('pengeluaran-delete')
                                    <button type="button"
                                            class="btn-icon btn-delete btn-delete-pgl"
                                            data-url="{{ route('pengeluaran.destroy', $pgl) }}"
                                            data-kode="{{ $pgl->kode_pengeluaran }}"
                                            title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-icon mb-3">
                                    <i class="fas fa-money-bill-transfer fs-3"></i>
                                </div>
                                <h6 class="fw-semibold mb-1" style="color: #374151;">Belum Ada Pengeluaran</h6>
                                <p class="small mb-3" style="color: #9ca3af;">
                                    @if(request()->hasAny(['search', 'kategori', 'dari_tanggal', 'sampai_tanggal']))
                                        Tidak ada pengeluaran yang cocok dengan filter.
                                    @else
                                        Belum ada pengeluaran tercatat.
                                    @endif
                                </p>
                                @can('pengeluaran-create')
                                <a href="{{ route('pengeluaran.create') }}" class="btn btn-primary-custom">
                                    <i class="fas fa-plus"></i>
                                    <span>Tambah Pengeluaran Pertama</span>
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($pengeluarans->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Menampilkan
                <b>{{ $pengeluarans->firstItem() }}</b>-<b>{{ $pengeluarans->lastItem() }}</b>
                dari <b>{{ $pengeluarans->total() }}</b> pengeluaran
            </div>
            <nav aria-label="Page navigation">
                <ul class="perm-pagination">
                    @if ($pengeluarans->onFirstPage())
                        <li><span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span></li>
                    @else
                        <li><a href="{{ $pengeluarans->previousPageUrl() }}" class="page-btn" rel="prev"><i class="fas fa-chevron-left"></i></a></li>
                    @endif

                    @php
                        $currentPage = $pengeluarans->currentPage();
                        $lastPage = $pengeluarans->lastPage();
                        $window = 2;
                    @endphp

                    @if ($currentPage > $window + 1)
                        <li class="page-number"><a href="{{ $pengeluarans->url(1) }}" class="page-btn">1</a></li>
                        @if ($currentPage > $window + 2)
                            <li><span class="page-dots">...</span></li>
                        @endif
                    @endif

                    @for ($page = max(1, $currentPage - $window); $page <= min($lastPage, $currentPage + $window); $page++)
                        <li class="page-number {{ $page == $currentPage ? 'active' : '' }}">
                            @if ($page == $currentPage)
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $pengeluarans->url($page) }}" class="page-btn">{{ $page }}</a>
                            @endif
                        </li>
                    @endfor

                    @if ($currentPage < $lastPage - $window)
                        @if ($currentPage < $lastPage - $window - 1)
                            <li><span class="page-dots">...</span></li>
                        @endif
                        <li class="page-number"><a href="{{ $pengeluarans->url($lastPage) }}" class="page-btn">{{ $lastPage }}</a></li>
                    @endif

                    @if ($pengeluarans->hasMorePages())
                        <li><a href="{{ $pengeluarans->nextPageUrl() }}" class="page-btn" rel="next"><i class="fas fa-chevron-right"></i></a></li>
                    @else
                        <li><span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span></li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>

<form id="deletePglForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
    'use strict';

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

    document.querySelectorAll('.btn-delete-pgl').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.dataset.url;
            const kode = this.dataset.kode;

            Swal.fire({
                title: 'Hapus Pengeluaran?',
                html: `
                    <p style="color: #4b5563; margin-bottom: 0;">
                        Pengeluaran <b>"${kode}"</b> akan dihapus permanen.
                    </p>
                    <p style="font-size: 0.8rem; color: #6b7280; margin-top: 0.5rem;">
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
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
                    const form = document.getElementById('deletePglForm');
                    form.action = url;
                    form.submit();
                }
            });
        });
    });

})();
</script>
@endpush