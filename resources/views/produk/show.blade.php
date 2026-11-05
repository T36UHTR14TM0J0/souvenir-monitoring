@extends('layouts.app')

@section('title', 'Detail Produk Souvenir - ' . $produk->nama_produk)

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

    /* INFO LIST */
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

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-gift"
        title="{{ $produk->nama_produk }}"
        subtitle="Kode: <span class='badge bg-light text-dark border font-monospace'>{{ $produk->kode_produk }}</span> &bull; Kategori: <span class='badge bg-primary-subtle text-primary border border-primary-subtle'>{{ $produk->kategori->nama_kategori }}</span>"
    >
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('produk.index') }}" class="btn btn-secondary-custom shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

            @can('produk-edit')
                <a href="{{ route('produk.edit', $produk) }}" class="btn btn-primary-custom shadow-sm">
                    <i class="fas fa-edit"></i>
                    <span>Edit Produk</span>
                </a>
            @endcan

            @can('produk-delete')
                <button type="button" class="btn btn-danger-custom shadow-sm btn-delete-produk"
                        data-url="{{ route('produk.destroy', $produk) }}"
                        data-name="{{ $produk->nama_produk }}">
                    <i class="fas fa-trash-alt"></i>
                    <span>Hapus</span>
                </button>
            @endcan
        </div>
    </x-page-header>

    {{-- KPI CARDS --}}
    @php
        $satuanNama    = $produk->satuan->nama_satuan ?? 'Pieces';
        $satuanSingkat = $produk->satuan->singkatan ?? 'pcs';
        $hargaFinal    = $produk->harga_setelah_diskon;
        $marginPerPcs  = $hargaFinal - $produk->harga_beli;
        $marginPersen  = $produk->harga_beli > 0 ? ($marginPerPcs / $produk->harga_beli) * 100 : 0;
        $nilaiAset     = $produk->stok * $produk->harga_beli;
        $potensiOmset  = $produk->stok * $hargaFinal;
    @endphp

    <div class="row g-3 mb-3">
        {{-- Modal Pokok (HPP) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-slate">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #f1f5f9; color: #475569;">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Modal Pokok (HPP)</div>
                        <div class="kpi-value text-secondary">Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</div>
                        <div class="kpi-subtext">Estimasi Biaya Satuan</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Harga Jual --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-primary">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #eff6ff; color: #2563eb;">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div>
                        <div class="kpi-label">
                            Harga Jual Toko
                            @if($produk->has_diskon)
                                <span class="badge bg-danger-subtle text-danger ms-1" style="font-size: 0.68rem; padding: 2px 5px;">-{{ $produk->label_diskon }}</span>
                            @endif
                        </div>
                        <div class="kpi-value text-primary">
                            Rp {{ number_format($produk->harga_setelah_diskon, 0, ',', '.') }}
                        </div>
                        <div class="kpi-subtext">
                            @if($produk->has_diskon)
                                <span class="text-decoration-line-through text-muted">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</span>
                                <span class="text-success fw-semibold ms-1">Promo Diskon</span>
                            @else
                                Harga Konsumen
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Estimasi Margin / Untung --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Margin Laba / {{ $satuanSingkat }}</div>
                        <div class="kpi-value text-success">+Rp {{ number_format($marginPerPcs, 0, ',', '.') }}</div>
                        <div class="kpi-subtext">
                            Markup: <strong class="text-success">{{ number_format($marginPersen, 1, ',', '.') }}%</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stok Saat Ini --}}
        <div class="col-12 col-sm-6 col-xl-3">
            @if($produk->stok <= 0)
                <div class="kpi-card-modern kpi-accent-danger">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #fef2f2; color: #dc2626;">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Status Stok</div>
                            <div class="kpi-value text-danger">Habis (0)</div>
                            <div class="kpi-subtext text-danger fw-semibold">Perlu produksi lagi</div>
                        </div>
                    </div>
                </div>
            @elseif($produk->stok <= $produk->stok_minimal)
                <div class="kpi-card-modern kpi-accent-warning">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background: #fffbeb; color: #d97706;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Status Stok</div>
                            <div class="kpi-value text-warning">{{ $produk->stok }} {{ $satuanSingkat }}</div>
                            <div class="kpi-subtext text-warning">Menipis (&le; {{ $produk->stok_minimal }})</div>
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
                            <div class="kpi-label">Status Stok</div>
                            <div class="kpi-value text-success">{{ $produk->stok }} {{ $satuanSingkat }}</div>
                            <div class="kpi-subtext text-success">Stok Aman (Min: {{ $produk->stok_minimal }})</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- MAIN DETAILS ROW --}}
    <div class="row g-3">
        {{-- KOLOM KIRI: Informasi & Valuasi Stok --}}
        <div class="col-12 col-lg-5">
            <div class="card card-modern mb-3">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex align-items-center gap-2">
                    <i class="fas fa-info-circle text-primary"></i>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Informasi Souvenir</h6>
                </div>
                <div class="card-body p-3">
                    <div class="info-list">
                        <div class="info-list-item">
                            <span class="info-list-label">Kode Produk</span>
                            <span class="info-list-value font-monospace text-primary fw-bold">{{ $produk->kode_produk }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Nama Produk</span>
                            <span class="info-list-value">{{ $produk->nama_produk }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Kategori</span>
                            <span class="info-list-value">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem;">{{ $produk->kategori->nama_kategori }}</span>
                            </span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Satuan Produk</span>
                            <span class="info-list-value text-dark fw-semibold">{{ $satuanNama }} ({{ $satuanSingkat }})</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Batas Stok Minimal</span>
                            <span class="info-list-value">{{ $produk->stok_minimal }} {{ $satuanSingkat }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Total Terjual</span>
                            <span class="info-list-value text-primary font-monospace fw-bold">{{ number_format($totalTerjual, 0, ',', '.') }} {{ $satuanSingkat }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Hasil Produksi Masuk</span>
                            <span class="info-list-value text-success font-monospace fw-bold">+{{ number_format($totalHasilProduksi, 0, ',', '.') }} {{ $satuanSingkat }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Dibuat Pada</span>
                            <span class="info-list-value">{{ $produk->created_at->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Terakhir Diperbarui</span>
                            <span class="info-list-value">{{ $produk->updated_at->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    @if($produk->deskripsi)
                        <div class="mt-3 pt-3 border-top">
                            <div class="info-list-label mb-1.5 fw-semibold text-dark">Deskripsi Produk:</div>
                            <div class="p-2.5 bg-light rounded text-muted" style="font-size: 0.8125rem; white-space: pre-line; line-height: 1.5;">
                                {{ $produk->deskripsi }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card Valuasi Inventaris --}}
            <div class="card card-modern">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex align-items-center gap-2">
                    <i class="fas fa-coins text-warning"></i>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Valuasi Stok Etalase</h6>
                </div>
                <div class="card-body p-3">
                    <div class="info-list">
                        <div class="info-list-item">
                            <span class="info-list-label">Total Nilai Aset Modal (Stok &times; HPP)</span>
                            <span class="info-list-value text-danger font-monospace">Rp {{ number_format($nilaiAset, 0, ',', '.') }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Potensi Omset Penjualan (Stok &times; Jual)</span>
                            <span class="info-list-value text-primary font-monospace">Rp {{ number_format($potensiOmset, 0, ',', '.') }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Potensi Laba Kotor Bersih</span>
                            <span class="info-list-value text-success font-monospace fw-bold">Rp {{ number_format($potensiOmset - $nilaiAset, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Riwayat Transaksi Penjualan & Produksi --}}
        <div class="col-12 col-lg-7">
            {{-- Riwayat Penjualan Terakhir --}}
            <div class="card card-modern mb-3">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-shopping-cart text-primary"></i>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Riwayat Penjualan Terakhir</h6>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem; padding: 0.25rem 0.55rem;">
                        Total: {{ $totalTerjual }} {{ $satuanSingkat }} terjual
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-detail-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">No Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th class="text-center">Qty</th>
                                    <th class="pe-3 text-end" style="width: 150px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatPenjualan as $item)
                                    <tr>
                                        <td class="ps-3 fw-semibold font-monospace text-primary">
                                            @if($item->transaksi)
                                                <a href="{{ route('transaksi-penjualan.show', $item->transaksi) }}" class="text-decoration-none fw-semibold font-monospace text-primary">
                                                    {{ $item->transaksi->kode_transaksi }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item->transaksi ? \Carbon\Carbon::parse($item->transaksi->tanggal)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            {{ $item->transaksi->nama_pelanggan ?? 'Umum' }}
                                        </td>
                                        <td class="text-center fw-bold">
                                            {{ $item->qty }} {{ $satuanSingkat }}
                                        </td>
                                        <td class="pe-3 text-end fw-bold font-monospace text-dark" style="font-variant-numeric: tabular-nums;">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                            Belum ada transaksi penjualan untuk produk ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Riwayat Hasil Produksi Masuk (Ready Stock / MTO) --}}
            <div class="card card-modern">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-boxes-stacked text-success"></i>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Riwayat Hasil Produksi Masuk (QC Workshop)</h6>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.72rem; padding: 0.25rem 0.55rem;">
                        Total: +{{ number_format($totalHasilProduksi, 0, ',', '.') }} {{ $satuanSingkat }} diterima
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-detail-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">No Penerimaan</th>
                                    <th>Tanggal</th>
                                    <th>Tipe Produksi</th>
                                    <th>PJ QC</th>
                                    <th class="text-center">Lolos QC</th>
                                    <th class="pe-3 text-center">Reject</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatPenerimaanProduksi as $item)
                                    <tr>
                                        <td class="ps-3 fw-medium">
                                            @if($item->penerimaanProduk)
                                                <a href="{{ route('penerimaan-produk.show', $item->penerimaanProduk) }}" class="text-decoration-none fw-semibold font-monospace" style="color: #26476f;">
                                                    {{ $item->penerimaanProduk->kode_penerimaan }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item->penerimaanProduk ? $item->penerimaanProduk->tanggal->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            @if($item->penerimaanProduk && $item->penerimaanProduk->tipe_produksi === 'pesanan_custom')
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 0.72rem;">Custom (MTO)</span>
                                            @else
                                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle" style="font-size: 0.72rem;">Ready Stock</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item->penerimaanProduk->nama_penanggung_jawab ?? '-' }}
                                        </td>
                                        <td class="text-center fw-bold text-success font-monospace">
                                            +{{ number_format($item->qty_bagus) }} {{ $satuanSingkat }}
                                        </td>
                                        <td class="pe-3 text-center font-monospace">
                                            @if($item->qty_reject > 0)
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">{{ number_format($item->qty_reject) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="fas fa-box-open fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                            Belum ada riwayat hasil produksi masuk untuk produk ini.
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
<form id="deleteProdukForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-delete-produk').forEach(btn => {
    btn.addEventListener('click', function () {
        const url  = this.dataset.url;
        const name = this.dataset.name;

        Swal.fire({
            title: 'Hapus Produk?',
            html: `<p style="color: #4b5563;">Produk <b>"${name}"</b> akan dihapus secara permanen.</p>`,
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
                const form = document.getElementById('deleteProdukForm');
                form.action = url;
                form.submit();
            }
        });
    });
});
</script>
@endpush
