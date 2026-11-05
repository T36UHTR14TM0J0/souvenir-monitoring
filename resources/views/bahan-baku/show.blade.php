@extends('layouts.app')

@section('title', 'Detail Bahan Baku - ' . $bahanBaku->nama_produk)

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
    .kpi-accent-slate   { border-left: 3.5px solid #64748b; }
    .kpi-accent-primary { border-left: 3.5px solid #2563eb; }
    .kpi-accent-success { border-left: 3.5px solid #10b981; }
    .kpi-accent-warning { border-left: 3.5px solid #f59e0b; }
    .kpi-accent-danger  { border-left: 3.5px solid #ef4444; }

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

    /* UNIFIED ACTION BUTTONS */
    .btn-primary-custom,
    .btn-secondary-custom,
    .btn-danger-custom {
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

    .btn-danger-custom {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-color: #dc2626;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.25);
    }
    .btn-danger-custom:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        border-color: #b91c1c;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.35);
    }

    /* INFO LIST PERSIS KATALOG SOUVENIR */
    .info-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.65rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .info-list-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .info-list-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
    }
    .info-list-value {
        font-size: 0.8125rem;
        color: #0f172a;
        font-weight: 600;
        text-align: right;
    }

    /* THEAD TABLE HEADER NAVY GRADIENT */
    .table-detail-modern thead {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
    }
    .table-detail-modern thead th {
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
    .table-detail-modern tbody td {
        padding: 0.75rem 0.85rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.8125rem;
        color: #334155;
        background-color: #ffffff;
    }
    .table-detail-modern tbody tr:hover td {
        background-color: #f8fafc;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER PERSIS KATALOG SOUVENIR SHOW --}}
    <x-page-header
        icon="fas fa-layer-group"
        title="{{ $bahanBaku->nama_produk }}"
        subtitle="Kode: <span class='badge bg-light text-dark border font-monospace'>{{ $bahanBaku->kode_produk }}</span> &bull; Kategori: <span class='badge bg-primary-subtle text-primary border border-primary-subtle'>{{ $bahanBaku->kategori->nama_kategori ?? '-' }}</span>"
    >
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('bahan-baku.index') }}" class="btn btn-secondary-custom shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

            @can('bahan-baku-edit')
                <a href="{{ route('bahan-baku.edit', $bahanBaku) }}" class="btn btn-primary-custom shadow-sm">
                    <i class="fas fa-edit"></i>
                    <span>Edit Bahan</span>
                </a>
            @endcan

            @can('bahan-baku-delete')
                <button type="button" class="btn btn-danger-custom shadow-sm btn-delete-bahan"
                        data-url="{{ route('bahan-baku.destroy', $bahanBaku) }}"
                        data-nama="{{ $bahanBaku->nama_produk }}">
                    <i class="fas fa-trash-alt"></i>
                    <span>Hapus</span>
                </button>
            @endcan
        </div>
    </x-page-header>

    {{-- KPI CARDS (4 CARDS PERSIS KATALOG SOUVENIR SHOW) --}}
    @php
        $satuanNama    = $bahanBaku->satuan->nama_satuan ?? 'Pieces';
        $satuanSingkat = $bahanBaku->satuan->singkatan ?? 'pcs';
        $totalMasuk    = (float) $bahanBaku->pembelianDetails->sum('qty');
        $totalKeluar   = (float) $bahanBaku->pemakaianDetails->sum('qty');
        $nilaiAset     = (float) $bahanBaku->stok * (float) $bahanBaku->harga_beli;
    @endphp

    <div class="row g-3 mb-3">
        {{-- 1. Harga Kulakan Terakhir (HPP) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-slate">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #f1f5f9; color: #475569;">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Harga Kulakan Terakhir</div>
                        <div class="kpi-value text-secondary">Rp {{ number_format($bahanBaku->harga_beli, 0, ',', '.') }}</div>
                        <div class="kpi-subtext">HPP per {{ $satuanSingkat }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Total Masuk Pengadaan --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-primary">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #eff6ff; color: #2563eb;">
                        <i class="fas fa-truck-ramp-box"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total Masuk Pengadaan</div>
                        <div class="kpi-value text-primary">+{{ number_format($totalMasuk) }} <small class="fs-6 fw-normal">{{ $satuanSingkat }}</small></div>
                        <div class="kpi-subtext">Dari {{ $bahanBaku->pembelianDetails->count() }}x belanja kargo</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Total Diambil Produksi --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                        <i class="fas fa-dolly"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total Diambil Operator</div>
                        <div class="kpi-value text-success">{{ number_format($totalKeluar) }} <small class="fs-6 fw-normal">{{ $satuanSingkat }}</small></div>
                        <div class="kpi-subtext">Dari {{ $bahanBaku->pemakaianDetails->count() }}x bon pemakaian</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Status Stok Saat Ini --}}
        <div class="col-12 col-sm-6 col-xl-3">
            @if($bahanBaku->stok <= 0)
                <div class="kpi-card-modern kpi-accent-danger">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #fef2f2; color: #dc2626;">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Status Stok Gudang</div>
                            <div class="kpi-value text-danger">Habis (0)</div>
                            <div class="kpi-subtext text-danger fw-semibold">Segera beli bahan baku</div>
                        </div>
                    </div>
                </div>
            @elseif($bahanBaku->stok <= $bahanBaku->stok_minimal)
                <div class="kpi-card-modern kpi-accent-warning">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #fffbeb; color: #d97706;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Status Stok Gudang</div>
                            <div class="kpi-value text-warning">{{ number_format($bahanBaku->stok) }} {{ $satuanSingkat }}</div>
                            <div class="kpi-subtext text-warning">Menipis (&le; {{ number_format($bahanBaku->stok_minimal) }})</div>
                        </div>
                    </div>
                </div>
            @else
                <div class="kpi-card-modern kpi-accent-success">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #f0fdf4; color: #16a34a;">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Status Stok Gudang</div>
                            <div class="kpi-value text-success">{{ number_format($bahanBaku->stok) }} {{ $satuanSingkat }}</div>
                            <div class="kpi-subtext text-success">Stok Aman (Min: {{ number_format($bahanBaku->stok_minimal) }})</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- MAIN DETAILS ROW PERSIS KATALOG SOUVENIR SHOW --}}
    <div class="row g-3">
        {{-- KOLOM KIRI: Informasi Bahan Baku & Valuasi Stok --}}
        <div class="col-12 col-lg-5">
            <div class="card card-modern mb-3">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex align-items-center gap-2">
                    <i class="fas fa-info-circle text-primary"></i>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Informasi Bahan Baku</h6>
                </div>
                <div class="card-body p-3">
                    <div class="info-list">
                        <div class="info-list-item">
                            <span class="info-list-label">Kode Material</span>
                            <span class="info-list-value font-monospace text-primary fw-bold">{{ $bahanBaku->kode_produk }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Nama Bahan Baku</span>
                            <span class="info-list-value">{{ $bahanBaku->nama_produk }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Kategori Material</span>
                            <span class="info-list-value">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem;">{{ $bahanBaku->kategori->nama_kategori ?? '-' }}</span>
                            </span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Satuan Takaran</span>
                            <span class="info-list-value text-dark fw-semibold">{{ $satuanNama }} ({{ $satuanSingkat }})</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Batas Stok Minimal</span>
                            <span class="info-list-value">{{ number_format($bahanBaku->stok_minimal) }} {{ $satuanSingkat }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Total Pengadaan Masuk</span>
                            <span class="info-list-value text-success font-monospace fw-bold">+{{ number_format($totalMasuk, 0, ',', '.') }} {{ $satuanSingkat }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Total Diambil Operator</span>
                            <span class="info-list-value text-danger font-monospace fw-bold">-{{ number_format($totalKeluar, 0, ',', '.') }} {{ $satuanSingkat }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Didaftarkan Pada</span>
                            <span class="info-list-value">{{ $bahanBaku->created_at->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Terakhir Diperbarui</span>
                            <span class="info-list-value">{{ $bahanBaku->updated_at->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    @if($bahanBaku->deskripsi)
                        <div class="mt-3 pt-3 border-top">
                            <div class="info-list-label mb-1.5 fw-semibold text-dark">Deskripsi &amp; Spesifikasi Teknis:</div>
                            <div class="p-2.5 bg-light rounded text-muted" style="font-size: 0.8125rem; white-space: pre-line; line-height: 1.5;">
                                {{ $bahanBaku->deskripsi }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card Valuasi Stok Gudang --}}
            <div class="card card-modern">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex align-items-center gap-2">
                    <i class="fas fa-coins text-warning"></i>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Valuasi Stok Gudang</h6>
                </div>
                <div class="card-body p-3">
                    <div class="info-list">
                        <div class="info-list-item">
                            <span class="info-list-label">Sisa Stok Fisik Gudang</span>
                            <span class="info-list-value text-primary font-monospace fw-bold">{{ number_format($bahanBaku->stok) }} {{ $satuanSingkat }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Harga Kulakan Terakhir</span>
                            <span class="info-list-value text-dark font-monospace">Rp {{ number_format($bahanBaku->harga_beli, 0, ',', '.') }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Total Nilai Aset Modal (Stok &times; HPP)</span>
                            <span class="info-list-value text-warning font-monospace fw-bold">Rp {{ number_format($nilaiAset, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Riwayat Pembelian Supplier & Pemakaian Operator --}}
        <div class="col-12 col-lg-7">
            {{-- Riwayat Pengadaan dari Supplier Online --}}
            <div class="card card-modern mb-3">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-truck-ramp-box text-primary"></i>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Riwayat Pengadaan dari Supplier Online</h6>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem; padding: 0.25rem 0.55rem;">
                        Total: +{{ number_format($totalMasuk, 0, ',', '.') }} {{ $satuanSingkat }} masuk
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-detail-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">No. Pembelian</th>
                                    <th>Tanggal</th>
                                    <th>Supplier &amp; Platform</th>
                                    <th class="text-center">Qty Masuk</th>
                                    <th class="pe-3 text-end" style="width: 150px;">Harga Beli</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bahanBaku->pembelianDetails as $detail)
                                    <tr>
                                        <td class="ps-3 fw-semibold font-monospace text-primary">
                                            @if($detail->pembelian)
                                                <a href="{{ route('transaksi-pembelian.show', $detail->pembelian) }}" class="text-decoration-none fw-semibold font-monospace text-primary">
                                                    {{ $detail->pembelian->kode_pembelian }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            {{ $detail->pembelian ? $detail->pembelian->tanggal->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $detail->pembelian->supplier ?? '-' }}</div>
                                            <span class="badge bg-light text-secondary border" style="font-size: 0.65rem; text-transform: uppercase;">
                                                {{ $detail->pembelian->platform ?? 'Online' }}
                                            </span>
                                        </td>
                                        <td class="text-center fw-bold text-success font-monospace">
                                            +{{ number_format($detail->qty) }} {{ $satuanSingkat }}
                                        </td>
                                        <td class="pe-3 text-end fw-bold font-monospace text-dark" style="font-variant-numeric: tabular-nums;">
                                            Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                            Belum ada riwayat pembelian untuk bahan baku ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Riwayat Pengambilan oleh Operator Workshop --}}
            <div class="card card-modern">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-dolly text-success"></i>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Riwayat Pengambilan oleh Operator Workshop</h6>
                    </div>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.72rem; padding: 0.25rem 0.55rem;">
                        Total: -{{ number_format($totalKeluar, 0, ',', '.') }} {{ $satuanSingkat }} keluar
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-detail-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">No. Pengambilan</th>
                                    <th>Tanggal</th>
                                    <th>Operator</th>
                                    <th>Keperluan</th>
                                    <th class="pe-3 text-center">Qty Keluar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bahanBaku->pemakaianDetails as $detail)
                                    <tr>
                                        <td class="ps-3 fw-medium">
                                            @if($detail->pemakaian)
                                                <a href="{{ route('pemakaian-bahan.show', $detail->pemakaian) }}" class="text-decoration-none fw-semibold font-monospace" style="color: #26476f;">
                                                    {{ $detail->pemakaian->kode_pemakaian }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            {{ $detail->pemakaian ? $detail->pemakaian->tanggal->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            {{ $detail->pemakaian->nama_operator ?? '-' }}
                                        </td>
                                        <td>
                                            <div class="text-muted small text-truncate" style="max-width: 200px;">
                                                {{ $detail->pemakaian->keperluan ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="pe-3 text-center fw-bold text-danger font-monospace">
                                            -{{ number_format($detail->qty) }} {{ $detail->satuan }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-box-open fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                            Belum ada riwayat pengambilan oleh operator untuk bahan baku ini.
                                        </td>
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

{{-- Hidden form delete --}}
<form id="deleteBahanForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-delete-bahan').forEach(btn => {
    btn.addEventListener('click', function () {
        const url  = this.dataset.url;
        const nama = this.dataset.nama;

        Swal.fire({
            title: 'Hapus Bahan Baku?',
            html: `<p style="color: #4b5563;">Bahan baku <b>"${nama}"</b> akan dihapus secara permanen.</p>`,
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
                const form = document.getElementById('deleteBahanForm');
                form.action = url;
                form.submit();
            }
        });
    });
});
</script>
@endpush
