@extends('layouts.app')

@section('title', 'Detail Pengambilan Bahan Baku - ' . $pemakaianBahan->kode_pemakaian)

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

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-dolly"
        title="Detail Pengambilan Bahan Baku"
        subtitle="Nomor Bon: <span class='badge bg-light text-dark border font-monospace'>{{ $pemakaianBahan->kode_pemakaian }}</span> &bull; Operator: <span class='badge bg-primary-subtle text-primary border border-primary-subtle'>{{ $pemakaianBahan->nama_operator }}</span>"
    >
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('pemakaian-bahan.index') }}" class="btn btn-secondary-custom shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

            <a href="{{ route('pemakaian-bahan.cetak', $pemakaianBahan) }}" target="_blank" class="btn btn-primary-custom shadow-sm">
                <i class="fas fa-print"></i>
                <span>Cetak Bon Pengambilan</span>
            </a>

            @can('pemakaian-bahan-delete')
                <button type="button" class="btn btn-danger-custom shadow-sm" id="btnBatalPemakaian">
                    <i class="fas fa-trash-alt"></i>
                    <span>Batalkan</span>
                </button>
            @endcan
        </div>
    </x-page-header>

    {{-- KPI CARDS (4 CARDS PERSIS KATALOG SOUVENIR) --}}
    <div class="row g-3 mb-3">
        {{-- 1. Macam Material --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-slate">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #f1f5f9; color: #475569;">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Macam Material</div>
                        <div class="kpi-value text-secondary">{{ number_format($pemakaianBahan->details->count()) }} <small class="fs-6 fw-normal">Jenis</small></div>
                        <div class="kpi-subtext">Varian Bahan Dikeluarkan</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Total Kuantitas Keluar --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-warning">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #fffbeb; color: #d97706;">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total Material Keluar</div>
                        <div class="kpi-value" style="color: #d97706;">{{ number_format($pemakaianBahan->total_kuantitas) }} <small class="fs-6 fw-normal">Unit</small></div>
                        <div class="kpi-subtext">Otomatis Memotong Gudang</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Operator Produksi --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-primary">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #eff6ff; color: #2563eb;">
                        <i class="fas fa-user-gear"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Operator Pengambil</div>
                        <div class="kpi-value text-primary text-truncate" style="max-width: 170px;">{{ $pemakaianBahan->nama_operator }}</div>
                        <div class="kpi-subtext">Petugas Workshop</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Tanggal Serah Terima --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Tanggal Serah Terima</div>
                        <div class="kpi-value text-success">{{ $pemakaianBahan->tanggal->translatedFormat('d M Y') }}</div>
                        <div class="kpi-subtext">Dicatat oleh {{ $pemakaianBahan->user->name ?? 'Admin' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN DETAILS ROW (STRUKTUR PERSIS KATALOG SOUVENIR SHOW) --}}
    <div class="row g-3">
        {{-- KOLOM KIRI: Informasi Serah Terima & Bukti Dokumen --}}
        <div class="col-12 col-lg-5">
            <div class="card card-modern mb-3">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex align-items-center gap-2">
                    <i class="fas fa-info-circle text-primary"></i>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Informasi Serah Terima Material</h6>
                </div>
                <div class="card-body p-3">
                    <div class="info-list">
                        <div class="info-list-item">
                            <span class="info-list-label">Nomor Bon Pengambilan</span>
                            <span class="info-list-value font-monospace text-primary fw-bold">{{ $pemakaianBahan->kode_pemakaian }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Tanggal Pengambilan</span>
                            <span class="info-list-value">{{ $pemakaianBahan->tanggal->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Operator Pengambil</span>
                            <span class="info-list-value text-dark fw-semibold">
                                <i class="fas fa-user-gear text-primary me-1"></i>{{ $pemakaianBahan->nama_operator }}
                            </span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Keperluan / Pesanan</span>
                            <span class="info-list-value">{{ $pemakaianBahan->keperluan }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Total Material Keluar</span>
                            <span class="info-list-value text-primary fw-bold">
                                {{ number_format($pemakaianBahan->total_kuantitas) }} unit ({{ $pemakaianBahan->total_item }} macam bahan)
                            </span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Petugas Pencatat</span>
                            <span class="info-list-value">{{ $pemakaianBahan->user->name ?? 'Admin' }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Dicatat Pada</span>
                            <span class="info-list-value">{{ $pemakaianBahan->created_at ? $pemakaianBahan->created_at->translatedFormat('d M Y, H:i') : '-' }}</span>
                        </div>
                    </div>

                    @if($pemakaianBahan->catatan)
                        <div class="mt-3 pt-3 border-top">
                            <div class="info-list-label mb-1.5 fw-semibold text-dark">Catatan Tambahan:</div>
                            <div class="p-2.5 bg-light rounded text-muted" style="font-size: 0.8125rem; white-space: pre-line; line-height: 1.5;">
                                {{ $pemakaianBahan->catatan }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card Bukti Serah Terima / Status Stok --}}
            <div class="card card-modern">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex align-items-center gap-2">
                    <i class="fas fa-paperclip text-warning"></i>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Bukti Serah Terima / Bon</h6>
                </div>
                <div class="card-body p-3">
                    @if($pemakaianBahan->bukti_pengambilan)
                        @if($pemakaianBahan->is_pdf_bukti)
                            <div class="d-flex align-items-center justify-content-between p-2.5 bg-light rounded border">
                                <span class="small fw-semibold text-dark text-truncate"><i class="fas fa-file-pdf text-danger fa-lg me-2"></i>Dokumen Tanda Terima (PDF)</span>
                                <a href="{{ $pemakaianBahan->bukti_pengambilan_url }}" target="_blank" class="btn btn-primary-custom btn-sm">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span>Buka PDF</span>
                                </a>
                            </div>
                        @else
                            <div class="text-center p-2 bg-light rounded border">
                                <img src="{{ $pemakaianBahan->bukti_pengambilan_url }}" alt="Bukti Serah Terima" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                                <div class="mt-2">
                                    <a href="{{ $pemakaianBahan->bukti_pengambilan_url }}" target="_blank" class="btn btn-secondary-custom btn-sm">
                                        <i class="fas fa-search-plus"></i>
                                        <span>Lihat Ukuran Penuh</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="info-list">
                            <div class="info-list-item">
                                <span class="info-list-label">Lampiran Bon Fisik</span>
                                <span class="info-list-value text-muted fst-italic">Tidak ada lampiran file</span>
                            </div>
                            <div class="info-list-item">
                                <span class="info-list-label">Status Sinkronisasi Gudang</span>
                                <span class="info-list-value text-success fw-bold">
                                    <i class="fas fa-check-circle me-1"></i>Stok Terpotong (-{{ number_format($pemakaianBahan->total_kuantitas) }} unit)
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Rincian Material yang Dikeluarkan --}}
        <div class="col-12 col-lg-7">
            <div class="card card-modern">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-boxes-stacked text-primary"></i>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Rincian Material yang Dikeluarkan ({{ $pemakaianBahan->details->count() }} Jenis)</h6>
                    </div>
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 0.72rem; padding: 0.25rem 0.55rem;">
                        Total: {{ number_format($pemakaianBahan->total_kuantitas) }} unit keluar
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-detail-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3" style="width: 45px;">No</th>
                                    <th>Nama Bahan Baku &amp; Kode</th>
                                    <th>Kategori</th>
                                    <th class="text-center">Jumlah Diambil</th>
                                    <th class="pe-3">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pemakaianBahan->details as $index => $detail)
                                    <tr>
                                        <td class="ps-3 text-muted font-monospace small">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">
                                                @if($detail->produk)
                                                    <a href="{{ route('bahan-baku.show', $detail->produk) }}" class="text-dark text-decoration-none">
                                                        {{ $detail->nama_bahan }}
                                                    </a>
                                                @else
                                                    {{ $detail->nama_bahan }}
                                                @endif
                                            </div>
                                            <div class="small text-muted font-monospace">
                                                {{ $detail->produk->kode_produk ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem;">
                                                {{ $detail->produk->kategori->nama_kategori ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center fw-bold text-primary font-monospace">
                                            {{ number_format($detail->qty) }} {{ $detail->satuan }}
                                        </td>
                                        <td class="pe-3 text-muted small">
                                            {{ $detail->keterangan ?: '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                            Tidak ada rincian item material.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr style="background: #f8fafc; font-weight: 700;">
                                    <td colspan="3" class="text-end ps-3 py-2.5" style="color: #475569; font-size: 0.78rem;">TOTAL MATERIAL KELUAR:</td>
                                    <td class="text-center py-2.5 text-primary font-monospace" style="font-size: 0.85rem;">
                                        {{ number_format($pemakaianBahan->total_kuantitas) }} unit
                                    </td>
                                    <td class="pe-3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Hidden Delete Form --}}
<form id="deleteFormShow" action="{{ route('pemakaian-bahan.destroy', $pemakaianBahan) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnBatal = document.getElementById('btnBatalPemakaian');
    const deleteForm = document.getElementById('deleteFormShow');

    if (btnBatal && deleteForm) {
        btnBatal.addEventListener('click', function() {
            Swal.fire({
                title: 'Batalkan Pengambilan Bahan?',
                html: `<p style="color: #4b5563;">Transaksi <b>"{{ $pemakaianBahan->kode_pemakaian }}"</b> akan dibatalkan dan kuantitas stok bahan baku akan otomatis dikembalikan ke rak gudang.</p>`,
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
                    deleteForm.submit();
                }
            });
        });
    }
});
</script>
@endpush
