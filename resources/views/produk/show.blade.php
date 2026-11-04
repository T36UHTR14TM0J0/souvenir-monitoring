@extends('layouts.app')

@section('title', 'Detail Produk - ' . $produk->nama_produk)

@push('styles')
<style>
    .kpi-card-detail {
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: 0.75rem;
        padding: 1.25rem;
        height: 100%;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .kpi-card-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .kpi-detail-icon {
        width: 42px;
        height: 42px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .kpi-detail-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--gray-500);
        margin-bottom: 0.25rem;
    }
    .kpi-detail-value {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1.2;
    }
    .kpi-detail-sub {
        font-size: 0.75rem;
        color: var(--gray-500);
        margin-top: 0.35rem;
    }
    .info-list-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-100);
    }
    .info-list-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .info-list-label {
        font-size: 0.825rem;
        color: var(--gray-500);
        font-weight: 500;
    }
    .info-list-value {
        font-size: 0.875rem;
        color: var(--gray-900);
        font-weight: 600;
        text-align: right;
    }
    .btn-outline-danger-custom {
        background-color: transparent;
        border: 1px solid var(--color-danger, #dc2626);
        color: var(--color-danger, #dc2626);
        font-weight: 500;
        padding: 0.625rem 1.25rem;
        border-radius: 0.625rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        cursor: pointer;
    }
    .btn-outline-danger-custom:hover {
        background-color: var(--color-danger, #dc2626);
        color: #fff;
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-box"
        title="{{ $produk->nama_produk }}"
        subtitle="Kode: <span class='badge bg-light text-dark border'>{{ $produk->kode_produk }}</span> &bull; Kategori: <span class='badge bg-primary-subtle text-primary border border-primary-subtle'>{{ $produk->kategori->nama_kategori }}</span>"
    >
        <div class="d-flex gap-2">
            <a href="{{ route('produk.index') }}" class="btn btn-danger-custom">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

            @can('produk-edit')
                <a href="{{ route('produk.edit', $produk) }}" class="btn btn-warning-custom">
                    <i class="fas fa-edit"></i>
                    <span>Edit Produk</span>
                </a>
            @endcan

            @can('produk-delete')
                <button type="button" class="btn btn-outline-danger-custom btn-delete-produk"
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

    <div class="row g-3 mb-4">
        {{-- Harga Beli --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-detail">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-detail-icon bg-secondary-subtle text-secondary">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div>
                        <div class="kpi-detail-label">Harga Beli Modal</div>
                        <div class="kpi-detail-value">Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</div>
                        <div class="kpi-detail-sub">Dari Supplier</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Harga Jual --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-detail">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-detail-icon bg-primary-subtle text-primary">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div>
                        <div class="kpi-detail-label">
                            Harga Jual Toko
                            @if($produk->has_diskon)
                                <span class="badge bg-danger-subtle text-danger ms-1" style="font-size: 0.7rem; padding: 2px 5px;">-{{ $produk->label_diskon }}</span>
                            @endif
                        </div>
                        <div class="kpi-detail-value text-primary">
                            Rp {{ number_format($produk->harga_setelah_diskon, 0, ',', '.') }}
                        </div>
                        <div class="kpi-detail-sub">
                            @if($produk->has_diskon)
                                <span class="text-decoration-line-through text-muted">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</span>
                                <span class="text-success fw-medium ms-1">Promo</span>
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
            <div class="kpi-card-detail">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-detail-icon bg-success-subtle text-success">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div class="kpi-detail-label">Margin Laba / {{ $satuanSingkat }}</div>
                        <div class="kpi-detail-value text-success">+Rp {{ number_format($marginPerPcs, 0, ',', '.') }}</div>
                        <div class="kpi-detail-sub">
                            Markup: <strong class="text-success">{{ number_format($marginPersen, 1, ',', '.') }}%</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stok Saat Ini --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-detail">
                <div class="d-flex align-items-center gap-3">
                    @if($produk->stok <= 0)
                        <div class="kpi-detail-icon bg-danger-subtle text-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div>
                            <div class="kpi-detail-label">Status Stok</div>
                            <div class="kpi-detail-value text-danger">Habis (0)</div>
                            <div class="kpi-detail-sub text-danger font-medium">Harus segera restock</div>
                        </div>
                    @elseif($produk->stok <= $produk->stok_minimal)
                        <div class="kpi-detail-icon bg-warning-subtle text-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <div class="kpi-detail-label">Status Stok</div>
                            <div class="kpi-detail-value text-warning">{{ $produk->stok }} {{ $satuanSingkat }}</div>
                            <div class="kpi-detail-sub text-warning">Menipis (&le; {{ $produk->stok_minimal }})</div>
                        </div>
                    @else
                        <div class="kpi-detail-icon bg-success-subtle text-success">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div>
                            <div class="kpi-detail-label">Status Stok</div>
                            <div class="kpi-detail-value text-success">{{ $produk->stok }} {{ $satuanSingkat }}</div>
                            <div class="kpi-detail-sub text-success">Stok Aman (Min: {{ $produk->stok_minimal }})</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN DETAILS ROW --}}
    <div class="row g-4">
        {{-- KOLOM KIRI: Informasi & Valuasi Stok --}}
        <div class="col-12 col-lg-5">
            <div class="card card-modern mb-4">
                <div class="card-body p-4">
                    <div class="form-section-title mb-3">
                        <i class="fas fa-info-circle"></i>
                        Informasi Produk
                    </div>

                    <div class="info-list">
                        <div class="info-list-item">
                            <span class="info-list-label">Kode Produk</span>
                            <span class="info-list-value">{{ $produk->kode_produk }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Nama Produk</span>
                            <span class="info-list-value">{{ $produk->nama_produk }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Kategori</span>
                            <span class="info-list-value">{{ $produk->kategori->nama_kategori }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Satuan Produk</span>
                            <span class="info-list-value text-primary fw-bold">{{ $satuanNama }} ({{ $satuanSingkat }})</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Batas Stok Minimal</span>
                            <span class="info-list-value">{{ $produk->stok_minimal }} {{ $satuanSingkat }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Total Terjual</span>
                            <span class="info-list-value text-primary">{{ number_format($totalTerjual, 0, ',', '.') }} {{ $satuanSingkat }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Total Dibeli (Supplier)</span>
                            <span class="info-list-value text-secondary">{{ number_format($totalDibeli, 0, ',', '.') }} {{ $satuanSingkat }}</span>
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
                        <div class="mt-4 pt-3 border-top">
                            <div class="info-list-label mb-2">Deskripsi Produk:</div>
                            <div class="p-3 bg-light rounded text-muted" style="font-size: 0.875rem; white-space: pre-line;">
                                {{ $produk->deskripsi }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card Valuasi Inventaris --}}
            <div class="card card-modern">
                <div class="card-body p-4">
                    <div class="form-section-title mb-3">
                        <i class="fas fa-coins"></i>
                        Valuasi Stok Toko
                    </div>

                    <div class="info-list">
                        <div class="info-list-item">
                            <span class="info-list-label">Total Nilai Aset Modal (Stok &times; Beli)</span>
                            <span class="info-list-value text-danger">Rp {{ number_format($nilaiAset, 0, ',', '.') }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Potensi Omset Penjualan (Stok &times; Jual)</span>
                            <span class="info-list-value text-primary">Rp {{ number_format($potensiOmset, 0, ',', '.') }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Potensi Laba Kotor Bersih</span>
                            <span class="info-list-value text-success">Rp {{ number_format($potensiOmset - $nilaiAset, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Riwayat Transaksi --}}
        <div class="col-12 col-lg-7">
            {{-- Riwayat Penjualan Terakhir --}}
            <div class="card card-modern mb-4">
                <div class="card-header bg-white border-bottom p-3 p-md-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold" style="color: #26476f;">
                        <i class="fas fa-shopping-cart me-2"></i>Riwayat Penjualan Terakhir
                    </h6>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                        Total: {{ $totalTerjual }} {{ $satuanSingkat }} terjual
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th class="text-center">Qty</th>
                                    <th class="pe-4 text-end text-nowrap col-nominal" style="width: 160px; min-width: 160px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatPenjualan as $item)
                                    <tr>
                                        <td class="ps-4 fw-medium text-primary">
                                            {{ $item->transaksi->kode_transaksi ?? '-' }}
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
                                        <td class="pe-4 text-end fw-semibold text-nowrap col-nominal" style="width: 160px; min-width: 160px;">
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

            {{-- Riwayat Pembelian Supplier Terakhir --}}
            <div class="card card-modern">
                <div class="card-header bg-white border-bottom p-3 p-md-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold" style="color: #26476f;">
                        <i class="fas fa-truck-loading me-2"></i>Riwayat Pembelian Restock Supplier
                    </h6>
                    <span class="badge bg-secondary-subtle text-secondary border">
                        Total: {{ $totalDibeli }} {{ $satuanSingkat }} masuk
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No Pembelian</th>
                                    <th>Tanggal</th>
                                    <th>Supplier</th>
                                    <th class="text-center">Qty</th>
                                    <th class="pe-4 text-end text-nowrap col-nominal" style="width: 160px; min-width: 160px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatPembelian as $item)
                                    <tr>
                                        <td class="ps-4 fw-medium text-dark">
                                            {{ $item->pembelian->kode_pembelian ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $item->pembelian ? \Carbon\Carbon::parse($item->pembelian->tanggal)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            {{ $item->pembelian->supplier ?? '-' }}
                                        </td>
                                        <td class="text-center fw-bold text-success">
                                            +{{ $item->qty }} {{ $satuanSingkat }}
                                        </td>
                                        <td class="pe-4 text-end fw-semibold text-nowrap col-nominal" style="width: 160px; min-width: 160px;">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-boxes-packing fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                            Belum ada riwayat pembelian supplier untuk produk ini.
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
