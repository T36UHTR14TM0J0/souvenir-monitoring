@extends('layouts.app')

@section('title', 'Pemakaian Bahan Baku')

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
    .table-pemakaian {
        min-width: 1100px;
    }
    .table-pemakaian thead {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
    }
    .table-pemakaian thead th {
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
    .table-pemakaian tbody td {
        padding: 0.75rem 0.85rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.8125rem;
        background-color: #ffffff;
    }
    .table-pemakaian tbody tr:hover td {
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
    .table-pemakaian thead th.col-freeze-aksi {
        background: linear-gradient(135deg, #1f3a5c 0%, #1a304d 100%) !important;
        z-index: 6;
    }
    .table-pemakaian tbody td.col-freeze-aksi {
        background-color: #ffffff;
    }
    .table-pemakaian tbody tr:hover td.col-freeze-aksi {
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
        icon="fas fa-dolly"
        title="Pemakaian Bahan Baku"
        subtitle="Pencatatan pengambilan material mentah &amp; perlengkapan packing workshop oleh operator produksi."
    />

    {{-- KPI STATS SUMMARY (4 CARDS) --}}
    <div class="row g-3 mb-3">
        {{-- 1. Total Material Keluar --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-warning">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #fffbeb; color: #d97706;">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #d97706;">Total Material Keluar</div>
                        <div class="kpi-value" style="color: #d97706;">
                            {{ number_format($totalKuantitasKeluar) }}
                            <small class="text-muted fs-6 fw-normal">Unit</small>
                        </div>
                        <div class="kpi-subtext">Dari {{ number_format($totalTransaksi) }} kali pengambilan</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Total Transaksi --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-info">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total Transaksi</div>
                        <div class="kpi-value text-dark">
                            {{ number_format($totalTransaksi) }}
                            <small class="text-muted fs-6 fw-normal">Batch</small>
                        </div>
                        <div class="kpi-subtext">Bon serah terima operator</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Bulan Ini --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-indigo">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #eef2ff; color: #4f46e5;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Bulan Ini</div>
                        <div class="kpi-value text-primary">
                            {{ number_format($totalBulanIni) }}
                            <small class="text-muted fs-6 fw-normal">Batch</small>
                        </div>
                        <div class="kpi-subtext">{{ number_format($kuantitasBulanIni) }} unit material keluar</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Hari Ini --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #059669;">Hari Ini</div>
                        <div class="kpi-value text-success">
                            {{ number_format($totalHariIni) }}
                            <small class="text-muted fs-6 fw-normal">Batch</small>
                        </div>
                        <div class="kpi-subtext">Aktivitas produksi berjalan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH TOOLBAR (STRUKTUR PERSIS KATALOG SOUVENIR) --}}
    @php
        $activeFilterCount = 0;
        if (request()->filled('nama_operator')) $activeFilterCount++;
        if (request()->filled('dari_tanggal')) $activeFilterCount++;
        if (request()->filled('sampai_tanggal')) $activeFilterCount++;
        if (request()->filled('sort') && request('sort') !== 'latest') $activeFilterCount++;
    @endphp

    <div class="card card-modern bg-white mb-3">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-stretch align-items-xl-center gap-2.5">
                {{-- Form Pencarian Cepat --}}
                <div class="flex-grow-1">
                    <form method="GET" action="{{ route('pemakaian-bahan.index') }}" class="m-0">
                        @if(request('nama_operator')) <input type="hidden" name="nama_operator" value="{{ request('nama_operator') }}"> @endif
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
                                   placeholder="Cari kode pengambilan, nama operator, atau keperluan produksi..."
                                   value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('pemakaian-bahan.index', request()->except('search')) }}" class="input-group-text bg-white text-muted text-decoration-none border-start-0 pe-3" title="Hapus Pencarian">
                                    <i class="fas fa-times text-secondary"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Action Buttons: Filter, Reset & Catat Pengambilan Bahan (Sejajar Samping Filter) --}}
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" id="btnBukaFilterPemakaian" class="btn btn-filter-custom position-relative" data-bs-toggle="modal" data-bs-target="#modalFilterPemakaian">
                        <i class="fas fa-filter"></i>
                        <span>Filter Data</span>
                        @if($activeFilterCount > 0)
                            <span class="badge bg-white text-dark rounded-pill ms-1" style="font-size: 0.68rem; font-weight: 700; padding: 0.2rem 0.45rem;">{{ $activeFilterCount }}</span>
                        @endif
                    </button>

                    @if(request()->anyFilled(['search', 'nama_operator', 'dari_tanggal', 'sampai_tanggal', 'sort']))
                        <a href="{{ route('pemakaian-bahan.index') }}" class="btn btn-reset-custom" title="Hapus Semua Filter">
                            <i class="fas fa-redo"></i>
                            <span class="d-none d-sm-inline">Reset</span>
                        </a>
                    @endif

                    @can('pemakaian-bahan-create')
                        <a href="{{ route('pemakaian-bahan.create') }}" class="btn btn-primary-custom shadow-sm">
                            <i class="fas fa-plus"></i>
                            <span>Catat Pengambilan Bahan</span>
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
                            <a href="{{ route('pemakaian-bahan.index', request()->except('search')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('nama_operator'))
                        <span class="filter-chip">
                            <i class="fas fa-user-gear me-1 text-primary"></i> Operator: {{ request('nama_operator') }}
                            <a href="{{ route('pemakaian-bahan.index', request()->except('nama_operator')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('dari_tanggal') || request('sampai_tanggal'))
                        <span class="filter-chip">
                            <i class="fas fa-calendar-alt me-1 text-primary"></i>
                            Tanggal: {{ request('dari_tanggal') ? date('d/m/Y', strtotime(request('dari_tanggal'))) : 'Awal' }}
                            s.d. {{ request('sampai_tanggal') ? date('d/m/Y', strtotime(request('sampai_tanggal'))) : 'Sekarang' }}
                            <a href="{{ route('pemakaian-bahan.index', request()->except(['dari_tanggal', 'sampai_tanggal'])) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('sort') && request('sort') !== 'latest')
                        <span class="filter-chip">
                            <i class="fas fa-arrow-down-short-wide me-1"></i> Urutan: {{ request('sort') }}
                            <a href="{{ route('pemakaian-bahan.index', request()->except('sort')) }}"><i class="fas fa-times"></i></a>
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
                <table class="table table-hover table-pemakaian align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th style="min-width: 220px;">Informasi Pengambilan</th>
                            <th style="width: 170px;">Operator Pengambil</th>
                            <th style="min-width: 220px;">Keperluan / Proyek Produksi</th>
                            <th style="min-width: 230px;">Material yang Diambil</th>
                            <th class="text-center" style="width: 130px;">Total Keluar</th>
                            <th style="width: 130px;">Petugas Input</th>
                            <th class="text-center pe-4 col-freeze-aksi" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemakaians as $index => $item)
                        <tr>
                            {{-- 1. No --}}
                            <td class="ps-4 text-muted font-monospace small">
                                {{ $pemakaians->firstItem() + $index }}
                            </td>

                            {{-- 2. Informasi Pengambilan (Avatar + Kode + Tanggal persis Katalog Souvenir) --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-icon-souvenir">
                                        <i class="fas fa-dolly"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="fw-bold text-dark">
                                            <a href="{{ route('pemakaian-bahan.show', $item) }}" class="text-dark text-decoration-none font-monospace">
                                                {{ $item->kode_pemakaian }}
                                            </a>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                            <span class="badge-kode">
                                                <i class="far fa-calendar me-1"></i>{{ $item->tanggal->translatedFormat('d M Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- 3. Operator Pengambil --}}
                            <td>
                                <div class="fw-semibold text-dark">
                                    <i class="fas fa-user-gear text-primary me-1"></i>{{ $item->nama_operator }}
                                </div>
                            </td>

                            {{-- 4. Keperluan / Proyek Produksi --}}
                            <td>
                                <div class="text-dark fw-semibold" style="font-size: 0.8125rem;">
                                    {{ $item->keperluan }}
                                </div>
                                @if($item->catatan)
                                    <div class="text-muted small text-truncate mt-0.5" style="max-width: 240px; font-size: 0.72rem;" title="{{ $item->catatan }}">
                                        <em>"{{ $item->catatan }}"</em>
                                    </div>
                                @endif
                            </td>

                            {{-- 5. Material yang Diambil --}}
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @foreach($item->details->take(2) as $det)
                                        <div class="d-flex align-items-center gap-1.5 text-truncate" style="max-width: 240px;" title="{{ $det->nama_bahan }}">
                                            <span class="badge-satuan font-monospace py-0 px-1.5" style="font-size: 0.7rem;">
                                                {{ number_format($det->qty) }} {{ $det->satuan }}
                                            </span>
                                            <span class="fw-medium text-dark text-truncate" style="font-size: 0.8rem;">{{ $det->nama_bahan }}</span>
                                        </div>
                                    @endforeach
                                    @if($item->details->count() > 2)
                                        <span class="text-muted small fst-italic" style="font-size: 0.72rem;">
                                            +{{ $item->details->count() - 2 }} bahan lainnya...
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- 6. Total Keluar --}}
                            <td class="text-center">
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 fw-bold font-monospace" style="font-size: 0.75rem;">
                                    <i class="fas fa-arrow-up-from-bracket me-1"></i>{{ number_format($item->total_kuantitas) }} Unit
                                </span>
                                <div class="text-muted mt-1 font-monospace" style="font-size: 0.7rem;">
                                    {{ $item->details->count() }} macam bahan
                                </div>
                            </td>

                            {{-- 7. Petugas Input --}}
                            <td>
                                <span class="badge-satuan">
                                    <i class="fas fa-user text-muted me-1"></i>{{ $item->user->name ?? 'Admin' }}
                                </span>
                            </td>

                            {{-- 8. Aksi (Solid gradient persis Katalog Souvenir) --}}
                            <td class="text-center pe-4 col-freeze-aksi">
                                <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                    {{-- Lihat Detail --}}
                                    <a href="{{ route('pemakaian-bahan.show', $item) }}"
                                       class="btn-action-icon btn-action-view"
                                       title="Lihat Detail Pengambilan">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Cetak Bon --}}
                                    <a href="{{ route('pemakaian-bahan.cetak', $item) }}"
                                       target="_blank"
                                       class="btn-action-icon btn-action-print"
                                       title="Cetak Bon Pengambilan">
                                        <i class="fas fa-print"></i>
                                    </a>

                                    {{-- Batalkan & Kembalikan Stok --}}
                                    @can('pemakaian-bahan-delete')
                                        <button type="button"
                                                class="btn-action-icon btn-action-delete btn-delete-pemakaian"
                                                data-url="{{ route('pemakaian-bahan.destroy', $item) }}"
                                                data-kode="{{ $item->kode_pemakaian }}"
                                                title="Batalkan &amp; Kembalikan Stok Gudang">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-0">
                                <x-empty-state
                                    icon="fas fa-dolly"
                                    title="Tidak Ada Pemakaian Bahan Ditemukan"
                                    message="Belum ada data pengambilan bahan baku yang sesuai dengan kriteria pencarian atau filter yang Anda pilih."
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if($pemakaians->hasPages())
            <x-pagination :paginator="$pemakaians" label="pemakaian bahan" />
        @endif
    </div>

</div>

{{-- MODAL FILTER PEMAKAIAN BAHAN (SAMA PERSIS DESAIN MODAL FILTER KATALOG SOUVENIR) --}}
<div class="modal fade" id="modalFilterPemakaian" tabindex="-1" aria-labelledby="modalFilterPemakaianLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.875rem;">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-filter fa-lg"></i>
                    <h5 class="modal-title fw-bold fs-6 mb-0 text-white" id="modalFilterPemakaianLabel">Filter Data Pemakaian Bahan Baku</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('pemakaian-bahan.index') }}">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="modal-body p-4">
                    <div class="row g-3">
                        {{-- 1. Nama Operator --}}
                        <div class="col-12">
                            <label class="form-label" for="modal_nama_operator">Nama Operator Produksi</label>
                            <select name="nama_operator" id="modal_nama_operator" class="form-select">
                                <option value="">Semua Operator</option>
                                @foreach($operatorList as $op)
                                    <option value="{{ $op }}" {{ request('nama_operator') === $op ? 'selected' : '' }}>
                                        {{ $op }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. Rentang Tanggal --}}
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

                        {{-- 3. Urutkan Berdasarkan --}}
                        <div class="col-12">
                            <label class="form-label" for="modal_sort">Urutkan Data</label>
                            <select name="sort" id="modal_sort" class="form-select">
                                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Terbaru Dicatat</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama Dicatat</option>
                                <option value="qty_desc" {{ request('sort') === 'qty_desc' ? 'selected' : '' }}>Kuantitas Keluar Terbanyak</option>
                                <option value="qty_asc" {{ request('sort') === 'qty_asc' ? 'selected' : '' }}>Kuantitas Keluar Paling Sedikit</option>
                                <option value="item_desc" {{ request('sort') === 'item_desc' ? 'selected' : '' }}>Jenis Material Terbanyak</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-3 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('pemakaian-bahan.index', request()->only('search')) }}" class="btn btn-reset-custom">
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

{{-- Hidden Delete Form --}}
<form id="deletePemakaianForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Pindahkan modal ke <body> agar tidak terhalang layout AdminKit
    const modalFilterEl = document.getElementById('modalFilterPemakaian');
    if (modalFilterEl && modalFilterEl.parentElement !== document.body) {
        document.body.appendChild(modalFilterEl);
    }

    // 2. Event listener pembuka modal filter
    const btnBukaFilter = document.getElementById('btnBukaFilterPemakaian');
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

    // 3. Event listener penutup modal
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

    // 4. Konfirmasi Hapus / Batal Pemakaian Bahan
    const deleteBtns = document.querySelectorAll('.btn-delete-pemakaian');
    const deleteForm = document.getElementById('deletePemakaianForm');

    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const kode = this.getAttribute('data-kode');

            Swal.fire({
                title: 'Batalkan Pengambilan Bahan?',
                html: `<p style="color: #4b5563; margin-bottom: 8px;">Transaksi <b>"${kode}"</b> akan dibatalkan dan kuantitas bahan baku akan otomatis dikembalikan ke stok gudang.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Batalkan!',
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
