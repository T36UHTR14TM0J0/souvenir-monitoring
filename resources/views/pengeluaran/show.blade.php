@extends('layouts.app')

@section('title', 'Detail Pengeluaran')

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
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: #fff;
        border: 1px solid rgba(220, 38, 38, 0.3);
        border-radius: 0.625rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
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
        border-left: 3px solid #dc2626;
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

    .jumlah-box {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        border-radius: 0.875rem;
        padding: 2rem;
        color: #fff;
        box-shadow: 0 8px 24px rgba(220, 38, 38, 0.25);
        text-align: center;
    }

    .jumlah-box .label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        opacity: 0.8;
        margin-bottom: 0.75rem;
    }

    .jumlah-box .value {
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    @media (max-width: 768px) {
        .page-title-custom { font-size: 1.25rem; }
        .page-title-icon { width: 36px; height: 36px; }
        .jumlah-box .value { font-size: 1.5rem; }
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
                Detail Pengeluaran
            </h1>
            <p class="page-subtitle-custom">Kode: <strong style="color: #dc2626;">{{ $pengeluaran->kode_pengeluaran }}</strong></p>
        </div>
        <div class="d-flex gap-2">
            @can('pengeluaran-edit')
            <a href="{{ route('pengeluaran.edit', $pengeluaran) }}" class="btn-secondary-custom">
                <i class="fas fa-pen"></i>
                <span>Edit</span>
            </a>
            @endcan
            <a href="{{ route('pengeluaran.index') }}" class="btn-secondary-custom">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card card-modern bg-white">
                <div class="card-body p-4">
                    <h6 class="mb-3" style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #dc2626;">
                        <i class="fas fa-info-circle me-1"></i> Informasi Pengeluaran
                    </h6>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-item-label">Tanggal</div>
                            <div class="info-item-value">{{ $pengeluaran->tanggal->format('d F Y') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item-label">Waktu Input</div>
                            <div class="info-item-value">{{ $pengeluaran->created_at->format('H:i') }} WIB</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item-label">Kategori</div>
                            <div class="info-item-value">{{ $pengeluaran->kategori_label }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item-label">Input Oleh</div>
                            <div class="info-item-value">{{ $pengeluaran->user->name ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="mt-4 p-3" style="background: var(--gray-50); border-radius: 0.5rem;">
                        <div class="info-item-label">Keterangan</div>
                        <div style="font-size: 0.95rem; color: #374151; font-weight: 500;">
                            {{ $pengeluaran->keterangan }}
                        </div>
                    </div>

                    @if($pengeluaran->catatan)
                    <div class="mt-3 p-3" style="background: var(--gray-50); border-radius: 0.5rem;">
                        <div class="info-item-label">Catatan</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">{{ $pengeluaran->catatan }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="jumlah-box">
                <div class="label">Jumlah Pengeluaran</div>
                <div class="value">- Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</div>
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2); font-size: 0.8rem; opacity: 0.9;">
                    <i class="fas fa-info-circle me-1"></i>
                    Biaya operasional
                </div>
            </div>
        </div>
    </div>
</div>
@endsection