@extends('layouts.app')

@section('title', 'Detail Pembelian Bahan Baku - ' . $pembelian->kode_pembelian)

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
    .btn-success-custom,
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

    .btn-success-custom {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-color: #059669;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.25);
    }
    .btn-success-custom:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.35);
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

    /* MODAL FORM INPUTS */
    .modal-dialog .form-control,
    .modal-dialog .form-select {
        height: 38px;
        font-size: 0.8125rem;
        border-radius: 0.45rem;
        border-color: #cbd5e1;
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

    @php
        $platformBadges = [
            'shopee' => ['bg' => '#ee4d2d', 'label' => 'Shopee'],
            'tokopedia' => ['bg' => '#03ac0e', 'label' => 'Tokopedia'],
            'tiktok' => ['bg' => '#000000', 'label' => 'TikTok Shop'],
            'lazada' => ['bg' => '#0f146d', 'label' => 'Lazada'],
            'offline' => ['bg' => '#6b7280', 'label' => 'Toko Offline'],
            'lainnya' => ['bg' => '#4b5563', 'label' => 'Lainnya'],
        ];
        $badge = $platformBadges[$pembelian->platform] ?? ['bg' => '#26476f', 'label' => ucfirst($pembelian->platform ?? 'Online')];

        $statusLabel = match($pembelian->status_pembelian) {
            'diterima' => 'Diterima di Workshop',
            'dikirim' => 'Sedang Dikirim Kargo',
            default => 'Dipesan ke Supplier',
        };
    @endphp

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-truck-ramp-box"
        title="Detail Pembelian Bahan Baku"
        subtitle="Nomor Referensi: <span class='badge bg-light text-dark border font-monospace'>{{ $pembelian->kode_pembelian }}</span> &bull; Status: <span class='badge bg-primary-subtle text-primary border border-primary-subtle'>{{ $statusLabel }}</span>"
    >
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('transaksi-pembelian.index') }}" class="btn btn-secondary-custom shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

            <button type="button" class="btn btn-primary-custom shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUpdateStatusPembelian">
                <i class="fas fa-truck-fast"></i>
                <span>Update Status Pengadaan</span>
            </button>

            @can('pembelian-delete')
                @if($pembelian->tanggal->isToday())
                    <button type="button" class="btn btn-danger-custom shadow-sm" id="btnHapusPembelianShow">
                        <i class="fas fa-trash-alt"></i>
                        <span>Hapus</span>
                    </button>
                @endif
            @endcan
        </div>
    </x-page-header>

    {{-- QUICK ACTION BANNER JIKA BELUM DITERIMA --}}
    @if($pembelian->status_pembelian !== 'diterima')
    <div class="alert border-0 shadow-sm d-flex flex-column flex-md-row align-items-md-center justify-content-between p-3 mb-3 rounded-3 gap-3"
         style="background: #fffbeb; border: 1px solid #fde68a !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 40px; height: 40px; background: #fef3c7; color: #b45309; flex-shrink: 0;">
                <i class="fas fa-truck-fast"></i>
            </div>
            <div>
                <strong class="d-block text-dark" style="font-size: 0.85rem;">
                    @if($pembelian->status_pembelian === 'dipesan')
                        Pesanan Masih Dalam Proses Supplier Online
                    @else
                        Paket Kargo Sedang Dalam Perjalanan Menuju Workshop
                    @endif
                </strong>
                <span class="small text-muted" style="font-size: 0.78rem;">
                    Stok bahan baku di rak gudang belum bertambah. Klik tombol konfirmasi setelah paket fisik diterima &amp; dicek di workshop.
                </span>
            </div>
        </div>
        <div>
            <form action="{{ route('transaksi-pembelian.konfirmasi-diterima', $pembelian) }}" method="POST"
                  onsubmit="return confirm('Konfirmasi bahwa paket bahan baku ini sudah sampai dan dicek di workshop? Kuantitas stok gudang akan otomatis bertambah.');">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success-custom shadow-sm">
                    <i class="fas fa-check-circle"></i>
                    <span>Konfirmasi Diterima di Workshop</span>
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- KPI CARDS (4 CARDS PERSIS KATALOG SOUVENIR) --}}
    <div class="row g-3 mb-3">
        {{-- 1. Macam Bahan --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-slate">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #f1f5f9; color: #475569;">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Macam Bahan Baku</div>
                        <div class="kpi-value text-secondary">{{ number_format($pembelian->details->count()) }} <small class="fs-6 fw-normal">Item</small></div>
                        <div class="kpi-subtext">Total {{ number_format($pembelian->details->sum('qty')) }} unit dipesan</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Total Harga Bahan --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-primary">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #eff6ff; color: #2563eb;">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Subtotal Harga Bahan</div>
                        <div class="kpi-value text-primary">Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</div>
                        <div class="kpi-subtext">Nilai pokok material</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Ongkir Kargo --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-warning">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #fffbeb; color: #d97706;">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Ongkos Kirim Kargo</div>
                        <div class="kpi-value" style="color: #d97706;">Rp {{ number_format($pembelian->ongkir ?? 0, 0, ',', '.') }}</div>
                        <div class="kpi-subtext">{{ $pembelian->kurir_kargo ?: 'Biaya ekspedisi pengiriman' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Grand Total Biaya --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Grand Total Biaya</div>
                        <div class="kpi-value text-success">Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}</div>
                        <div class="kpi-subtext">
                            @if($pembelian->total_diskon > 0)
                                Diskon: -Rp {{ number_format($pembelian->total_diskon, 0, ',', '.') }}
                            @else
                                Total dibayarkan ke supplier
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN DETAILS ROW (STRUKTUR PERSIS KATALOG SOUVENIR SHOW) --}}
    <div class="row g-3">
        {{-- KOLOM KIRI: Informasi Pembelian Online & Bukti Invoice --}}
        <div class="col-12 col-lg-5">
            <div class="card card-modern mb-3">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex align-items-center gap-2">
                    <i class="fas fa-info-circle text-primary"></i>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Informasi Pembelian Online &amp; Kargo</h6>
                </div>
                <div class="card-body p-3">
                    <div class="info-list">
                        <div class="info-list-item">
                            <span class="info-list-label">Nomor Pembelian</span>
                            <span class="info-list-value font-monospace text-primary fw-bold">{{ $pembelian->kode_pembelian }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Status Pengadaan</span>
                            <span class="info-list-value">
                                @if($pembelian->status_pembelian === 'diterima')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.72rem;"><i class="fas fa-check-circle me-1"></i>Diterima di Workshop</span>
                                @elseif($pembelian->status_pembelian === 'dikirim')
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle" style="font-size: 0.72rem;"><i class="fas fa-truck-fast me-1"></i>Sedang Dikirim</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 0.72rem;"><i class="fas fa-clock me-1"></i>Dipesan ke Supplier</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Platform Toko Online</span>
                            <span class="info-list-value">
                                <span class="badge text-white" style="background-color: {{ $badge['bg'] }}; font-size: 0.72rem;">
                                    {{ $badge['label'] }}
                                </span>
                            </span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Nama Supplier / Seller</span>
                            <span class="info-list-value text-dark fw-semibold">{{ $pembelian->supplier ?? '-' }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">No. Invoice Marketplace</span>
                            <span class="info-list-value font-monospace">{{ $pembelian->no_invoice_online ?? '-' }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Tanggal Transaksi</span>
                            <span class="info-list-value">{{ $pembelian->tanggal->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Ekspedisi / Kurir Kargo</span>
                            <span class="info-list-value">{{ $pembelian->kurir_kargo ?? '-' }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Nomor Resi Pengiriman</span>
                            <span class="info-list-value font-monospace">{{ $pembelian->no_resi_kargo ?? '-' }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Petugas Pencatat</span>
                            <span class="info-list-value">{{ $pembelian->user->name ?? '-' }}</span>
                        </div>
                    </div>

                    @if($pembelian->catatan)
                        <div class="mt-3 pt-3 border-top">
                            <div class="info-list-label mb-1.5 fw-semibold text-dark">Catatan Pembelian:</div>
                            <div class="p-2.5 bg-light rounded text-muted" style="font-size: 0.8125rem; white-space: pre-line; line-height: 1.5;">
                                {{ $pembelian->catatan }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card Bukti Pembayaran / Invoice --}}
            <div class="card card-modern">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex align-items-center gap-2">
                    <i class="fas fa-paperclip text-warning"></i>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Bukti Pembayaran / Invoice</h6>
                </div>
                <div class="card-body p-3">
                    @if($pembelian->bukti_transaksi)
                        @if($pembelian->is_pdf_bukti)
                            <div class="d-flex align-items-center justify-content-between p-2.5 bg-light rounded border">
                                <span class="small fw-semibold text-dark text-truncate"><i class="fas fa-file-pdf text-danger fa-lg me-2"></i>Dokumen Invoice (PDF)</span>
                                <a href="{{ $pembelian->bukti_transaksi_url }}" target="_blank" class="btn btn-primary-custom btn-sm">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span>Buka PDF</span>
                                </a>
                            </div>
                        @else
                            <div class="text-center p-2 bg-light rounded border">
                                <img src="{{ $pembelian->bukti_transaksi_url }}" alt="Bukti Pembelian" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                                <div class="mt-2">
                                    <a href="{{ $pembelian->bukti_transaksi_url }}" target="_blank" class="btn btn-secondary-custom btn-sm">
                                        <i class="fas fa-search-plus"></i>
                                        <span>Lihat Ukuran Penuh</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="info-list">
                            <div class="info-list-item">
                                <span class="info-list-label">Lampiran Bukti Invoice</span>
                                <span class="info-list-value text-muted fst-italic">Tidak ada lampiran file</span>
                            </div>
                            <div class="info-list-item">
                                <span class="info-list-label">Status Sinkronisasi Gudang</span>
                                <span class="info-list-value">
                                    @if($pembelian->status_pembelian === 'diterima')
                                        <span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i>Stok Bertambah di Gudang</span>
                                    @else
                                        <span class="text-warning fw-bold"><i class="fas fa-clock me-1"></i>Menunggu Kedatangan Paket</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Rincian Bahan Baku yang Dibeli --}}
        <div class="col-12 col-lg-7">
            <div class="card card-modern">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-boxes-stacked text-primary"></i>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Rincian Bahan Baku yang Dibeli ({{ $pembelian->details->count() }} Jenis)</h6>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem; padding: 0.25rem 0.55rem;">
                        Grand Total: Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-detail-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3" style="width: 45px;">No</th>
                                    <th>Nama Bahan Baku &amp; Kode</th>
                                    <th class="text-end text-nowrap">Harga Satuan</th>
                                    <th class="text-center">Qty Dibeli</th>
                                    <th class="pe-3 text-end text-nowrap">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembelian->details as $index => $detail)
                                    <tr>
                                        <td class="ps-3 text-muted font-monospace small">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">
                                                @if($detail->produk)
                                                    <a href="{{ route('bahan-baku.show', $detail->produk) }}" class="text-dark text-decoration-none">
                                                        {{ $detail->nama_produk }}
                                                    </a>
                                                @else
                                                    {{ $detail->nama_produk }}
                                                @endif
                                            </div>
                                            <div class="small text-muted font-monospace">
                                                {{ $detail->produk->kode_produk ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="text-end text-nowrap font-monospace">
                                            Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center font-monospace">
                                            <strong class="text-dark">{{ number_format($detail->qty) }}</strong>
                                            <span class="text-muted small ms-1">{{ $detail->produk->satuan->singkatan ?? 'pcs' }}</span>
                                        </td>
                                        <td class="pe-3 text-end fw-bold text-nowrap font-monospace" style="color: #26476f;">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                            Tidak ada rincian item bahan baku.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr style="background: #f8fafc;">
                                    <td colspan="4" class="text-end ps-3 py-2 text-muted small fw-semibold">Subtotal Harga Bahan:</td>
                                    <td class="pe-3 text-end py-2 fw-semibold font-monospace">Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr style="background: #f8fafc;">
                                    <td colspan="4" class="text-end ps-3 py-2 text-muted small fw-semibold">Ongkos Kirim Kargo (+):</td>
                                    <td class="pe-3 text-end py-2 fw-semibold text-primary font-monospace">+ Rp {{ number_format($pembelian->ongkir ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                @if($pembelian->total_diskon > 0)
                                <tr style="background: #f8fafc;">
                                    <td colspan="4" class="text-end ps-3 py-2 text-muted small fw-semibold">Diskon / Cashback (-):</td>
                                    <td class="pe-3 text-end py-2 fw-semibold text-danger font-monospace">- Rp {{ number_format($pembelian->total_diskon, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr style="background: #f1f5f9; font-weight: 700;">
                                    <td colspan="4" class="text-end ps-3 py-2.5" style="color: #0f172a; font-size: 0.8rem;">GRAND TOTAL BIAYA PENGADAAN:</td>
                                    <td class="pe-3 text-end py-2.5 text-success font-monospace" style="font-size: 0.9rem;">
                                        Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- MODAL UPDATE STATUS PENGADAAN (SHOW PAGE) --}}
<div class="modal fade" id="modalUpdateStatusPembelian" tabindex="-1" aria-labelledby="modalUpdateStatusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.875rem;">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #26476f 0%, #1f3a5c 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-truck-ramp-box fa-lg"></i>
                    <h5 class="modal-title fw-bold fs-6 mb-0 text-white" id="modalUpdateStatusLabel">Update Status Pengadaan</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('transaksi-pembelian.update-status', $pembelian) }}">
                @csrf
                @method('PATCH')

                <div class="modal-body p-4">
                    <div class="p-3 mb-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Nomor Pembelian</div>
                        <div class="fw-bold font-monospace text-primary fs-6">{{ $pembelian->kode_pembelian }}</div>
                    </div>

                    <div class="row g-3">
                        {{-- Pilihan Status --}}
                        <div class="col-12">
                            <label for="show_status_select" class="form-label">
                                Pilih Status Pengadaan <span class="text-danger">*</span>
                            </label>
                            <select name="status_pembelian" id="show_status_select" class="form-select" required>
                                <option value="dipesan" {{ $pembelian->status_pembelian === 'dipesan' ? 'selected' : '' }}>
                                    Dipesan (Menunggu dikirim supplier online, stok belum bertambah)
                                </option>
                                <option value="dikirim" {{ $pembelian->status_pembelian === 'dikirim' ? 'selected' : '' }}>
                                    Dikirim (Paket dalam perjalanan kargo, stok belum bertambah)
                                </option>
                                <option value="diterima" {{ $pembelian->status_pembelian === 'diterima' ? 'selected' : '' }}>
                                    Diterima di Workshop (Barang fisik sampai &amp; dicek, stok otomatis masuk gudang)
                                </option>
                            </select>
                        </div>

                        {{-- Kurir & Resi --}}
                        <div class="col-12 col-md-6">
                            <label for="show_kurir" class="form-label">Kurir / Ekspedisi Kargo</label>
                            <input type="text" name="kurir_kargo" id="show_kurir" class="form-control"
                                   placeholder="Misal: J&amp;T Cargo" value="{{ $pembelian->kurir_kargo }}">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="show_resi" class="form-label">Nomor Resi Kargo</label>
                            <input type="text" name="no_resi_kargo" id="show_resi" class="form-control"
                                   placeholder="No. Resi Pengiriman" value="{{ $pembelian->no_resi_kargo }}">
                        </div>

                        {{-- Info Card Stok Alert --}}
                        <div class="col-12">
                            <div class="p-3 rounded-3 border" id="showStatusNotice" style="background: #eff6ff; border-color: #bfdbfe; font-size: 0.8rem; color: #1e40af;">
                                <i class="fas fa-info-circle me-1"></i>
                                <span>Perubahan status ke <strong>Diterima</strong> otomatis menambahkan kuantitas ke stok fisik gudang.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top py-3 px-4 d-flex justify-content-end align-items-center gap-2">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan Status</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Hidden Delete Form --}}
<form id="deleteFormShowPbl" action="{{ route('transaksi-pembelian.destroy', $pembelian) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalStatusEl = document.getElementById('modalUpdateStatusPembelian');
    if (modalStatusEl && modalStatusEl.parentElement !== document.body) {
        document.body.appendChild(modalStatusEl);
    }

    const selectStatus = document.getElementById('show_status_select');
    const noticeEl = document.getElementById('showStatusNotice');

    if (selectStatus && noticeEl) {
        function updateNotice(val) {
            if (val === 'diterima') {
                noticeEl.className = 'p-3 rounded-3 border bg-success-subtle border-success text-success';
                noticeEl.innerHTML = '<i class="fas fa-check-circle me-1"></i> Status <strong>Diterima</strong>: Bahan baku fisik sudah tiba dan kuantitas akan otomatis bertambah ke stok gudang workshop.';
            } else if (val === 'dikirim') {
                noticeEl.className = 'p-3 rounded-3 border bg-info-subtle border-info text-primary';
                noticeEl.innerHTML = '<i class="fas fa-truck-fast me-1"></i> Status <strong>Dikirim</strong>: Paket kargo dalam perjalanan. Stok bahan baku di gudang belum bertambah.';
            } else {
                noticeEl.className = 'p-3 rounded-3 border bg-warning-subtle border-warning text-warning';
                noticeEl.innerHTML = '<i class="fas fa-clock me-1"></i> Status <strong>Dipesan</strong>: Order menunggu diproses & dikirim supplier online. Stok bahan baku belum bertambah.';
            }
        }

        selectStatus.addEventListener('change', function() {
            updateNotice(this.value);
        });

        updateNotice(selectStatus.value);
    }

    const btnHapus = document.getElementById('btnHapusPembelianShow');
    const deleteForm = document.getElementById('deleteFormShowPbl');
    if (btnHapus && deleteForm) {
        btnHapus.addEventListener('click', function() {
            Swal.fire({
                title: 'Hapus Pembelian?',
                html: `<p style="color: #4b5563;">Transaksi pembelian <b>"{{ $pembelian->kode_pembelian }}"</b> akan dihapus.</p>`,
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
                    deleteForm.submit();
                }
            });
        });
    }
});
</script>
@endpush
