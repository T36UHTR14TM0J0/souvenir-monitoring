@extends('layouts.app')

@section('title', 'Detail Pembelian')

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

    .page-title-custom {
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
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        color: #fff;
        border: 1px solid rgba(37, 99, 235, 0.3);
        border-radius: 0.625rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .page-subtitle-custom { font-size: 0.875rem; color: var(--gray-500); margin-bottom: 0; }

    .btn-secondary-custom {
        background-color: #fff;
        border: 1px solid var(--gray-300);
        color: var(--gray-700);
        font-weight: 500;
        padding: 0.625rem 1.25rem;
        border-radius: 0.625rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        text-decoration: none;
    }

    .btn-secondary-custom:hover {
        background-color: var(--gray-50);
        border-color: var(--gray-400);
        color: var(--gray-700);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
    }

    .info-item {
        padding: 0.875rem;
        background: var(--gray-50);
        border-radius: 0.5rem;
        border-left: 3px solid #2563eb;
    }

    .info-item-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.375rem;
    }

    .info-item-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--gray-900);
    }

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
    }
    .table-modern tbody td {
        vertical-align: middle;
        border-bottom: 1px solid var(--gray-100);
        color: var(--gray-700);
        padding: 0.875rem 1rem;
        background-color: #fff;
        font-size: 0.875rem;
    }
    .table-modern tbody tr:last-child td { border-bottom: none; }

    .summary-box {
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%);
        border-radius: 0.875rem;
        padding: 1.5rem;
        color: #fff;
        box-shadow: 0 8px 24px rgba(38, 71, 111, 0.25);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        font-size: 0.875rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .summary-row:last-child { border-bottom: none; }
    .summary-row .label { color: rgba(255, 255, 255, 0.75); }
    .summary-row .value { font-weight: 600; }

    .summary-row.grand {
        padding-top: 1rem;
        margin-top: 0.5rem;
        border-top: 2px solid rgba(255, 255, 255, 0.3);
        border-bottom: none;
    }

    .summary-row.grand .label { font-size: 1rem; color: #fff; font-weight: 600; }

    .summary-row.grand .value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #fde68a;
        letter-spacing: -0.02em;
    }

    @media (max-width: 768px) {
        .page-title-custom { font-size: 1.25rem; }
        .page-title-icon { width: 36px; height: 36px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="page-title-custom">
                <span class="page-title-icon">
                    <i class="fas fa-file-invoice"></i>
                </span>
                Detail Pembelian
            </h1>
            <p class="page-subtitle-custom">Kode: <strong style="color: #2563eb;">{{ $pembelian->kode_pembelian }}</strong></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('transaksi-pembelian.index') }}" class="btn btn-danger-custom">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card card-modern bg-white mb-4">
                <div class="card-body p-4">
                    <h6 class="mb-3" style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #26476f;">
                        <i class="fas fa-info-circle me-1"></i> Informasi Pembelian
                    </h6>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-item-label">Tanggal</div>
                            <div class="info-item-value">{{ $pembelian->tanggal->translatedFormat('d F Y') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item-label">Waktu Input</div>
                            <div class="info-item-value">{{ $pembelian->created_at->format('H:i') }} WIB</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item-label">Supplier</div>
                            <div class="info-item-value">{{ $pembelian->supplier ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item-label">Input Oleh</div>
                            <div class="info-item-value">{{ $pembelian->user->name ?? '-' }}</div>
                        </div>
                    </div>

                    @if($pembelian->catatan)
                    <div class="mt-3 p-3" style="background: var(--gray-50); border-radius: 0.5rem;">
                        <div class="info-item-label">Catatan</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">{{ $pembelian->catatan }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card card-modern bg-white">
                <div class="card-body p-0">
                    <div class="p-4 pb-3">
                        <h6 class="mb-0" style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #26476f;">
                            <i class="fas fa-box-open me-1"></i> Produk yang Dibeli ({{ $pembelian->details->count() }} item)
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-modern align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="width: 50px;">No</th>
                                    <th>Nama Produk</th>
                                    <th class="text-end text-nowrap col-nominal" style="width: 150px; min-width: 150px;">Harga Beli</th>
                                    <th class="text-center">Qty</th>
                                    <th class="pe-4 text-end text-nowrap col-nominal" style="width: 160px; min-width: 160px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pembelian->details as $index => $detail)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold" style="color: #111827;">{{ $detail->nama_produk }}</div>
                                        <div style="font-size: 0.75rem; color: #9ca3af; font-family: monospace;">
                                            {{ $detail->produk->kode_produk ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="text-end text-nowrap col-nominal" style="width: 150px; min-width: 150px;">Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                                    <td class="text-center"><strong>{{ $detail->qty }}</strong> <small class="text-muted">{{ $detail->produk->satuan->singkatan ?? 'pcs' }}</small></td>
                                    <td class="pe-4 text-end fw-semibold text-nowrap col-nominal" style="color: #2563eb; width: 160px; min-width: 160px;">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="summary-box">
                <h6 class="mb-3" style="font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.9;">
                    <i class="fas fa-calculator me-1"></i> Ringkasan
                </h6>

                <div class="summary-row">
                    <span class="label">Total Harga</span>
                    <span class="value">Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</span>
                </div>

                @if($pembelian->total_diskon > 0)
                <div class="summary-row">
                    <span class="label">Diskon</span>
                    <span class="value" style="color: #fca5a5;">- Rp {{ number_format($pembelian->total_diskon, 0, ',', '.') }}</span>
                </div>
                @endif

                <div class="summary-row grand">
                    <span class="label">Grand Total</span>
                    <span class="value">Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}</span>
                </div>

                <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255,255,255,0.15);">
                    <div class="text-center" style="font-size: 0.75rem; opacity: 0.7;">
                        <i class="fas fa-arrow-up"></i> Stok produk telah ditambahkan otomatis
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection