@extends('layouts.app')

@section('title', 'Pencairan Saldo & Modal')

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
    .table-kas-masuk {
        min-width: 980px;
    }
    .table-kas-masuk thead {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
    }
    .table-kas-masuk thead th {
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
    .table-kas-masuk tbody td {
        padding: 0.75rem 0.85rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.8125rem;
        background-color: #ffffff;
    }
    .table-kas-masuk tbody tr:hover td {
        background-color: #f8fafc;
    }

    /* FREEZE / STICKY ACTION COLUMN */
    .col-freeze-aksi {
        position: sticky !important;
        right: 0 !important;
        z-index: 5;
        min-width: 130px;
        width: 130px;
        box-shadow: -4px 0 8px -2px rgba(0, 0, 0, 0.08);
    }
    .table-kas-masuk thead th.col-freeze-aksi {
        background: linear-gradient(135deg, #1f3a5c 0%, #1a304d 100%) !important;
        z-index: 6;
    }
    .table-kas-masuk tbody td.col-freeze-aksi {
        background-color: #ffffff;
    }
    .table-kas-masuk tbody tr:hover td.col-freeze-aksi {
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
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }
    .badge-satuan {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        padding: 0.25rem 0.6rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
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
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        color: #059669;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
        border: 1px solid #a7f3d0;
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
        icon="fas fa-hand-holding-dollar"
        title="Pencairan Saldo & Modal"
        subtitle="Pencatatan pencairan saldo marketplace, setoran modal harian (Debit), dan pemasukan kas toko lainnya."
    />

    {{-- KPI STATS SUMMARY (4 CARDS) --}}
    <div class="row g-3 mb-3">
        {{-- 1. Kas Masuk Periode Aktif --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-info">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Kas Masuk ({{ $periodeLabel }})</div>
                        <div class="kpi-value text-primary">
                            Rp {{ number_format($totalBulanIni ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="kpi-subtext">
                            {{ number_format($totalTransaksiBulanIni ?? 0) }} transaksi @if(($totalHariIni ?? 0) > 0)| Hari ini: Rp {{ number_format($totalHariIni, 0, ',', '.') }}@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Modal Disetor Periode --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-indigo">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #eef2ff; color: #4f46e5;">
                        <i class="fas fa-hand-holding-dollar"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Modal Disetor ({{ $periodeLabel }})</div>
                        <div class="kpi-value text-dark">
                            Rp {{ number_format($totalModalDisetorBulanIni ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="kpi-subtext">Suntikan modal pemilik (Debit)</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Pendapatan Lain Periode --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-warning">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #fffbeb; color: #d97706;">
                        <i class="fas fa-sack-dollar"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #d97706;">Pendapatan Lain ({{ $periodeLabel }})</div>
                        <div class="kpi-value" style="color: #d97706;">
                            Rp {{ number_format($totalPendapatanLainBulanIni ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="kpi-subtext">Pemasukan kas non-modal</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Total Akumulasi Kas Masuk --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                        <i class="fas fa-vault"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #059669;">Akumulasi Kas Masuk</div>
                        <div class="kpi-value text-success">
                            Rp {{ number_format($totalAkumulasi ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="kpi-subtext">{{ number_format($totalTransaksiSemua ?? $kasMasuks->total()) }} transaksi tercatat (All-time)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH TOOLBAR (STRUKTUR PERSIS KATALOG SOUVENIR) --}}
    @php
        $activeFilterCount = 0;
        if (request()->filled('tipe')) $activeFilterCount++;
        if (request()->filled('sumber')) $activeFilterCount++;
        if (request()->filled('dari_tanggal')) $activeFilterCount++;
        if (request()->filled('sampai_tanggal')) $activeFilterCount++;
        if (request()->filled('bulan')) $activeFilterCount++;
        if (request()->filled('tahun')) $activeFilterCount++;
        if (request()->filled('sort') && request('sort') !== 'latest') $activeFilterCount++;
    @endphp

    <div class="card card-modern bg-white mb-3">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-stretch align-items-xl-center gap-2.5">
                {{-- Form Pencarian Cepat --}}
                <div class="flex-grow-1">
                    <form method="GET" action="{{ route('kas-masuk.index') }}" class="m-0">
                        @if(request('tipe')) <input type="hidden" name="tipe" value="{{ request('tipe') }}"> @endif
                        @if(request('sumber')) <input type="hidden" name="sumber" value="{{ request('sumber') }}"> @endif
                        @if(request('dari_tanggal')) <input type="hidden" name="dari_tanggal" value="{{ request('dari_tanggal') }}"> @endif
                        @if(request('sampai_tanggal')) <input type="hidden" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}"> @endif
                        @if(request('bulan')) <input type="hidden" name="bulan" value="{{ request('bulan') }}"> @endif
                        @if(request('tahun')) <input type="hidden" name="tahun" value="{{ request('tahun') }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <div class="input-group search-input-group shadow-none">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-3 pe-2">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   class="form-control border-start-0 ps-1"
                                   placeholder="Cari kode transaksi, keterangan, atau sumber dana..."
                                   value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('kas-masuk.index', request()->except('search')) }}" class="input-group-text bg-white text-muted text-decoration-none border-start-0 pe-3" title="Hapus Pencarian">
                                    <i class="fas fa-times text-secondary"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Action Buttons: Filter, Reset & Setor Modal / Kas Masuk (Sejajar Samping Filter) --}}
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" id="btnBukaFilterKasMasuk" class="btn btn-filter-custom position-relative" data-bs-toggle="modal" data-bs-target="#modalFilterKasMasuk">
                        <i class="fas fa-filter"></i>
                        <span>Filter Data</span>
                        @if($activeFilterCount > 0)
                            <span class="badge bg-white text-dark rounded-pill ms-1" style="font-size: 0.68rem; font-weight: 700; padding: 0.2rem 0.45rem;">{{ $activeFilterCount }}</span>
                        @endif
                    </button>

                    @if(request()->anyFilled(['search', 'tipe', 'sumber', 'dari_tanggal', 'sampai_tanggal', 'bulan', 'tahun', 'sort']))
                        <a href="{{ route('kas-masuk.index') }}" class="btn btn-reset-custom" title="Hapus Semua Filter">
                            <i class="fas fa-redo"></i>
                            <span class="d-none d-sm-inline">Reset</span>
                        </a>
                    @endif

                    @canany(['kas-masuk-create', 'pengeluaran-create'])
                        <a href="{{ route('kas-masuk.create') }}" class="btn btn-primary-custom shadow-sm">
                            <i class="fas fa-plus"></i>
                            <span>Setor Modal / Kas Masuk</span>
                        </a>
                    @endcanany
                </div>
            </div>

            {{-- ACTIVE FILTER CHIPS --}}
            @if($activeFilterCount > 0 || request('search'))
                <div class="d-flex flex-wrap align-items-center gap-2 mt-2.5 pt-2.5 border-top">
                    <span class="small text-muted fw-bold me-1" style="font-size: 0.72rem;">Filter Aktif:</span>

                    @if(request('search'))
                        <span class="filter-chip">
                            <i class="fas fa-search me-1"></i> "{{ request('search') }}"
                            <a href="{{ route('kas-masuk.index', request()->except('search')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('tipe'))
                        <span class="filter-chip">
                            <i class="fas fa-tag me-1 text-primary"></i> Tipe: {{ $tipes[request('tipe')] ?? request('tipe') }}
                            <a href="{{ route('kas-masuk.index', request()->except('tipe')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('sumber'))
                        <span class="filter-chip">
                            <i class="fas fa-building-columns me-1 text-primary"></i> Sumber: {{ request('sumber') }}
                            <a href="{{ route('kas-masuk.index', request()->except('sumber')) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('dari_tanggal') || request('sampai_tanggal'))
                        <span class="filter-chip">
                            <i class="fas fa-calendar me-1 text-primary"></i>
                            {{ request('dari_tanggal') ?: '...' }} s/d {{ request('sampai_tanggal') ?: '...' }}
                            <a href="{{ route('kas-masuk.index', request()->except(['dari_tanggal', 'sampai_tanggal'])) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('bulan') || request('tahun'))
                        <span class="filter-chip">
                            <i class="fas fa-calendar-check me-1 text-primary"></i>
                            Periode: {{ request('bulan') ? \Carbon\Carbon::createFromDate(request('tahun', now()->year), request('bulan'), 1)->translatedFormat('F') : '' }} {{ request('tahun') }}
                            <a href="{{ route('kas-masuk.index', request()->except(['bulan', 'tahun'])) }}"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    @if(request('sort') && request('sort') !== 'latest')
                        <span class="filter-chip">
                            <i class="fas fa-arrow-down-short-wide me-1"></i> Urutan: {{ request('sort') }}
                            <a href="{{ route('kas-masuk.index', request()->except('sort')) }}"><i class="fas fa-times"></i></a>
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
                <table class="table table-hover table-kas-masuk align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th style="min-width: 210px;">Kode &amp; Tanggal</th>
                            <th style="min-width: 190px;">Tipe Pemasukan</th>
                            <th style="min-width: 170px;">Sumber Dana</th>
                            <th style="min-width: 240px;">Keterangan</th>
                            <th class="text-end" style="width: 165px;">Nominal Masuk</th>
                            <th class="text-center pe-4 col-freeze-aksi" style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kasMasuks as $index => $km)
                        <tr>
                            {{-- 1. No --}}
                            <td class="ps-4 text-muted font-monospace small">
                                {{ $kasMasuks->firstItem() + $index }}
                            </td>

                            {{-- 2. Kode & Tanggal --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-icon-souvenir">
                                        <i class="fas fa-hand-holding-dollar"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                            <span class="badge-kode">{{ $km->kode_transaksi }}</span>
                                            @if($km->bukti_transaksi)
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle"
                                                      style="cursor: pointer; font-size: 0.65rem;"
                                                      title="Klik untuk lihat bukti transfer/setor"
                                                      onclick="showBuktiModal('{{ $km->bukti_transaksi_url }}', 'Bukti {{ $km->kode_transaksi }}')">
                                                    <i class="fas fa-paperclip me-1"></i>Bukti
                                                </span>
                                            @endif
                                        </div>
                                        <div class="small text-muted mt-1">
                                            <i class="far fa-calendar-alt me-1"></i>{{ $km->tanggal->translatedFormat('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- 3. Tipe Pemasukan --}}
                            <td>
                                @if($km->tipe === 'modal_pemilik')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fw-semibold" style="font-size: 0.75rem;">
                                        <i class="fas fa-coins me-1"></i>{{ $km->tipe_label }}
                                    </span>
                                @else
                                    <span class="badge-kategori-custom">
                                        <i class="fas fa-sack-dollar"></i>
                                        <span>{{ $km->tipe_label }}</span>
                                    </span>
                                @endif
                            </td>

                            {{-- 4. Sumber Dana --}}
                            <td>
                                <span class="badge-satuan">
                                    <i class="fas fa-building-columns text-secondary"></i>
                                    <span>{{ $km->sumber }}</span>
                                </span>
                            </td>

                            {{-- 5. Keterangan --}}
                            <td>
                                <div class="fw-semibold text-dark text-truncate" style="max-width: 280px;" title="{{ $km->keterangan }}">
                                    {{ $km->keterangan }}
                                </div>
                                @if($km->catatan)
                                    <div class="small text-muted text-truncate" style="max-width: 280px;" title="{{ $km->catatan }}">
                                        {{ $km->catatan }}
                                    </div>
                                @endif
                            </td>

                            {{-- 6. Nominal Masuk --}}
                            <td class="text-end text-nowrap font-monospace fw-bold text-success" style="font-size: 0.9rem;">
                                + Rp {{ number_format($km->jumlah, 0, ',', '.') }}
                            </td>

                            {{-- 7. Aksi (Solid gradient persis Katalog Souvenir) --}}
                            <td class="text-center pe-4 col-freeze-aksi">
                                <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                    @if($km->bukti_transaksi)
                                        <button type="button"
                                                class="btn-action-icon btn-action-view"
                                                title="Lihat Bukti Transfer/Setor"
                                                onclick="showBuktiModal('{{ $km->bukti_transaksi_url }}', 'Bukti {{ $km->kode_transaksi }}')">
                                            <i class="fas fa-paperclip"></i>
                                        </button>
                                    @endif

                                    @canany(['kas-masuk-edit', 'pengeluaran-edit'])
                                        <a href="{{ route('kas-masuk.edit', $km) }}"
                                           class="btn-action-icon btn-action-edit"
                                           title="Edit Kas Masuk">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcanany

                                    @canany(['kas-masuk-delete', 'pengeluaran-delete'])
                                        <button type="button"
                                                class="btn-action-icon btn-action-delete btn-delete-kas-masuk"
                                                data-url="{{ route('kas-masuk.destroy', $km) }}"
                                                data-kode="{{ $km->kode_transaksi }}"
                                                title="Hapus Kas Masuk">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endcanany
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-0">
                                <x-empty-state
                                    icon="fas fa-hand-holding-dollar"
                                    title="Belum Ada Data Kas Masuk"
                                    message="Belum ada data pencairan saldo atau setoran modal pemilik yang sesuai dengan pencarian atau filter yang Anda pilih."
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if($kasMasuks->hasPages())
            <x-pagination :paginator="$kasMasuks" label="kas masuk" />
        @endif
    </div>

</div>

{{-- MODAL FILTER KAS MASUK (SAMA PERSIS DESAIN MODAL FILTER KATALOG SOUVENIR) --}}
<div class="modal fade" id="modalFilterKasMasuk" tabindex="-1" aria-labelledby="modalFilterKasMasukLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.875rem;">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-filter fa-lg"></i>
                    <h5 class="modal-title fw-bold fs-6 mb-0 text-white" id="modalFilterKasMasukLabel">Filter Pencairan Saldo &amp; Modal</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('kas-masuk.index') }}">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="modal-body p-4">
                    <div class="row g-3">
                        {{-- 1. Tipe Kas Masuk --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_tipe">Tipe Pemasukan</label>
                            <select name="tipe" id="modal_tipe" class="form-select">
                                <option value="">Semua Tipe</option>
                                @foreach($tipes as $k => $v)
                                    <option value="{{ $k }}" {{ request('tipe') === $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. Sumber Dana --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_sumber">Sumber Dana</label>
                            <select name="sumber" id="modal_sumber" class="form-select">
                                <option value="">Semua Sumber Dana</option>
                                @foreach($sumbers ?? \App\Models\KasMasuk::SUMBER as $sk => $sv)
                                    <option value="{{ $sk }}" {{ request('sumber') === $sk ? 'selected' : '' }}>{{ $sv }}</option>
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

                        {{-- 4. Pilihan Bulan & Tahun Cepat --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_bulan">Atau Pilih Bulan</label>
                            <select name="bulan" id="modal_bulan" class="form-select">
                                <option value="">Semua Bulan</option>
                                @foreach([1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'] as $mNum => $mName)
                                    <option value="{{ $mNum }}" {{ request('bulan') == $mNum ? 'selected' : '' }}>{{ $mName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modal_tahun">Tahun</label>
                            <select name="tahun" id="modal_tahun" class="form-select">
                                <option value="">Semua Tahun</option>
                                @for($y = (int) now()->year; $y >= 2024; $y--)
                                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        {{-- 4. Urutkan Berdasarkan --}}
                        <div class="col-12">
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
                    <a href="{{ route('kas-masuk.index', request()->only('search')) }}" class="btn btn-reset-custom">
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
<form id="deleteKasMasukForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalFilterEl = document.getElementById('modalFilterKasMasuk');
    if (modalFilterEl && modalFilterEl.parentElement !== document.body) {
        document.body.appendChild(modalFilterEl);
    }

    const btnBukaFilter = document.getElementById('btnBukaFilterKasMasuk');
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

    const deleteButtons = document.querySelectorAll('.btn-delete-kas-masuk');
    const deleteForm = document.getElementById('deleteKasMasukForm');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const url  = this.getAttribute('data-url');
            const kode = this.getAttribute('data-kode');

            Swal.fire({
                title: 'Hapus Kas Masuk?',
                html: `<p style="color: #4b5563; margin-bottom: 8px;">Data kas masuk / modal <b>"${kode}"</b> akan dihapus permanen.</p>`,
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
