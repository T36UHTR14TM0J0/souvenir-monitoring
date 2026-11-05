@extends('layouts.app')

@section('title', 'Detail Pengeluaran - ' . $pengeluaran->kode_pengeluaran)

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
    .btn-warning-custom,
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

    .btn-warning-custom {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-color: #d97706;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(245, 158, 11, 0.25);
    }
    .btn-warning-custom:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        border-color: #b45309;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(217, 119, 6, 0.35);
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
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-credit-card"
        title="Detail Pengeluaran & Biaya"
        subtitle="Kode Pengeluaran: <span class='badge bg-danger-subtle text-danger border border-danger-subtle font-monospace'>{{ $pengeluaran->kode_pengeluaran }}</span> &bull; Alokasi: <span class='badge bg-primary-subtle text-primary border border-primary-subtle'>{{ $pengeluaran->tipe_alokasi_label }}</span>"
    >
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary-custom shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

            @can('pengeluaran-edit')
                <a href="{{ route('pengeluaran.edit', $pengeluaran) }}" class="btn btn-warning-custom shadow-sm">
                    <i class="fas fa-edit"></i>
                    <span>Edit</span>
                </a>
            @endcan

            @can('pengeluaran-delete')
                <button type="button" class="btn btn-danger-custom shadow-sm" id="btnHapusPengeluaran">
                    <i class="fas fa-trash-alt"></i>
                    <span>Hapus</span>
                </button>
            @endcan
        </div>
    </x-page-header>

    {{-- KPI CARDS (4 CARDS PERSIS KATALOG SOUVENIR) --}}
    <div class="row g-3 mb-3">
        {{-- 1. Nominal Pengeluaran --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-danger">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #fef2f2; color: #dc2626;">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div>
                        <div class="kpi-label" style="color: #dc2626;">Nominal Keluar</div>
                        <div class="kpi-value text-danger">- Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</div>
                        <div class="kpi-subtext">Total dana dikeluarkan</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Alokasi Keuangan --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-primary">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #eff6ff; color: #2563eb;">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Alokasi Keuangan</div>
                        <div class="kpi-value text-primary">{{ $pengeluaran->tipe_alokasi_label }}</div>
                        <div class="kpi-subtext">Kelompok beban kas</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Kategori Biaya --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-slate">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #f1f5f9; color: #475569;">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Kategori Biaya</div>
                        <div class="kpi-value text-dark text-truncate" style="max-width: 170px;" title="{{ $pengeluaran->kategori_label }}">{{ $pengeluaran->kategori_label }}</div>
                        <div class="kpi-subtext">Pos anggaran pengeluaran</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Status Bukti / Kasbon --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern {{ $pengeluaran->bukti_transaksi ? 'kpi-accent-success' : 'kpi-accent-warning' }}">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: {{ $pengeluaran->bukti_transaksi ? '#ecfdf5' : '#fffbeb' }}; color: {{ $pengeluaran->bukti_transaksi ? '#059669' : '#d97706' }};">
                        <i class="fas {{ $pengeluaran->bukti_transaksi ? 'fa-file-circle-check' : 'fa-file-circle-question' }}"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Lampiran Struk</div>
                        <div class="kpi-value {{ $pengeluaran->bukti_transaksi ? 'text-success' : 'text-warning' }}">
                            {{ $pengeluaran->bukti_transaksi ? 'Terlampir' : 'Tanpa Bukti' }}
                        </div>
                        <div class="kpi-subtext">{{ $pengeluaran->tanggal->translatedFormat('d F Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN DETAILS ROW (STRUKTUR PERSIS KATALOG SOUVENIR SHOW) --}}
    <div class="row g-3">
        {{-- KOLOM KIRI: Informasi Pengeluaran --}}
        <div class="col-12 col-lg-7">
            <div class="card card-modern">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-info-circle text-primary"></i>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Rincian Informasi Pengeluaran</h6>
                    </div>
                    @if($pengeluaran->tipe_alokasi === 'kasbon')
                        @can('pengeluaran-edit')
                            <form action="{{ route('pengeluaran.toggle-kasbon', $pengeluaran) }}" method="POST" class="m-0">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $pengeluaran->status_kasbon === 'lunas' ? 'btn-outline-warning' : 'btn-success' }} fw-semibold" style="font-size: 0.72rem;">
                                    <i class="fas {{ $pengeluaran->status_kasbon === 'lunas' ? 'fa-undo' : 'fa-check-circle' }} me-1"></i>
                                    {{ $pengeluaran->status_kasbon === 'lunas' ? 'Tandai Belum Lunas' : 'Tandai Kasbon Lunas' }}
                                </button>
                            </form>
                        @endcan
                    @endif
                </div>
                <div class="card-body p-3">
                    <div class="info-list">
                        <div class="info-list-item">
                            <span class="info-list-label">Kode Pengeluaran</span>
                            <span class="info-list-value font-monospace text-danger fw-bold">{{ $pengeluaran->kode_pengeluaran }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Tanggal Transaksi</span>
                            <span class="info-list-value">{{ $pengeluaran->tanggal->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Alokasi Keuangan</span>
                            <span class="info-list-value">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem;">
                                    {{ $pengeluaran->tipe_alokasi_label }}
                                </span>
                            </span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Kategori Biaya</span>
                            <span class="info-list-value">{{ $pengeluaran->kategori_label }}</span>
                        </div>
                        @if($pengeluaran->tipe_alokasi === 'kasbon')
                            <div class="info-list-item">
                                <span class="info-list-label">Nama Peminjam / Karyawan</span>
                                <span class="info-list-value text-primary fw-bold">{{ $pengeluaran->karyawan_nama ?? '-' }}</span>
                            </div>
                            <div class="info-list-item">
                                <span class="info-list-label">Status Pinjaman Kasbon</span>
                                <span class="info-list-value">
                                    <span class="badge {{ $pengeluaran->status_kasbon === 'lunas' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning text-dark' }}" style="font-size: 0.72rem;">
                                        {{ $pengeluaran->status_kasbon === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}
                                    </span>
                                </span>
                            </div>
                        @endif
                        <div class="info-list-item">
                            <span class="info-list-label">Keterangan Pengeluaran</span>
                            <span class="info-list-value">{{ $pengeluaran->keterangan }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Dicatat Oleh</span>
                            <span class="info-list-value">{{ $pengeluaran->user->name ?? 'Admin' }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Waktu Input</span>
                            <span class="info-list-value">{{ $pengeluaran->created_at ? $pengeluaran->created_at->translatedFormat('d M Y, H:i') : '-' }}</span>
                        </div>
                    </div>

                    @if($pengeluaran->catatan)
                        <div class="mt-3 pt-3 border-top">
                            <div class="info-list-label mb-1.5 fw-semibold text-dark">Catatan Tambahan:</div>
                            <div class="p-2.5 bg-light rounded text-muted" style="font-size: 0.8125rem; white-space: pre-line; line-height: 1.5;">
                                {{ $pengeluaran->catatan }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Lampiran Bukti Struk / Kuitansi --}}
        <div class="col-12 col-lg-5">
            <div class="card card-modern">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex align-items-center gap-2">
                    <i class="fas fa-paperclip text-warning"></i>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Bukti Struk / Kuitansi</h6>
                </div>
                <div class="card-body p-3">
                    @if($pengeluaran->bukti_transaksi)
                        @if($pengeluaran->is_pdf_bukti)
                            <div class="d-flex align-items-center justify-content-between p-2.5 bg-light rounded border">
                                <span class="small fw-semibold text-dark text-truncate"><i class="fas fa-file-pdf text-danger fa-lg me-2"></i>Dokumen Bukti (PDF)</span>
                                <a href="{{ $pengeluaran->bukti_transaksi_url }}" target="_blank" class="btn btn-primary-custom btn-sm">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span>Buka PDF</span>
                                </a>
                            </div>
                        @else
                            <div class="text-center p-2 bg-light rounded border">
                                <img src="{{ $pengeluaran->bukti_transaksi_url }}"
                                     alt="Bukti Pengeluaran"
                                     class="img-fluid rounded"
                                     style="max-height: 240px; cursor: pointer; object-fit: contain;"
                                     onclick="showBuktiModal('{{ $pengeluaran->bukti_transaksi_url }}', 'Bukti {{ $pengeluaran->kode_pengeluaran }}')">
                                <div class="mt-2">
                                    <button type="button"
                                            class="btn btn-secondary-custom btn-sm"
                                            onclick="showBuktiModal('{{ $pengeluaran->bukti_transaksi_url }}', 'Bukti {{ $pengeluaran->kode_pengeluaran }}')">
                                        <i class="fas fa-search-plus"></i>
                                        <span>Perbesar Bukti</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-receipt fa-2x mb-2 d-block text-secondary opacity-50"></i>
                            <div class="small">Tidak ada lampiran struk atau kuitansi pada transaksi ini.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Hidden form delete --}}
<form id="deleteFormShow" action="{{ route('pengeluaran.destroy', $pengeluaran) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnHapus = document.getElementById('btnHapusPengeluaran');
    const deleteForm = document.getElementById('deleteFormShow');

    if (btnHapus && deleteForm) {
        btnHapus.addEventListener('click', function() {
            Swal.fire({
                title: 'Hapus Pengeluaran?',
                html: `<p style="color: #4b5563;">Pengeluaran <b>"{{ $pengeluaran->kode_pengeluaran }}"</b> akan dihapus permanen.</p>`,
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
