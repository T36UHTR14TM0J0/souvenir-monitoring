@extends('layouts.app')

@section('title', 'Detail Hasil Produksi Masuk - ' . $penerimaanProduk->kode_penerimaan)

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

    @php
        $tipeLabel = match($penerimaanProduk->tipe_produksi) {
            'ready_stock' => 'Ready Stock Etalase',
            'pesanan_khusus', 'pesanan_custom' => 'Pesanan Custom (MTO)',
            'restock_toko' => 'Restock Toko Fisik',
            default => ucfirst(str_replace('_', ' ', $penerimaanProduk->tipe_produksi)),
        };
        $totalDiproses = $penerimaanProduk->total_bagus + $penerimaanProduk->total_reject;
        $persenLolos = $totalDiproses > 0 ? ($penerimaanProduk->total_bagus / $totalDiproses) * 100 : 100;
    @endphp

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-boxes-stacked"
        title="Detail Hasil Produksi Masuk"
        subtitle="Nomor Penerimaan: <span class='badge bg-light text-dark border font-monospace'>{{ $penerimaanProduk->kode_penerimaan }}</span> &bull; Tipe: <span class='badge bg-primary-subtle text-primary border border-primary-subtle'>{{ $tipeLabel }}</span>"
    >
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('penerimaan-produk.index') }}" class="btn btn-secondary-custom shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

            <a href="{{ route('penerimaan-produk.cetak', $penerimaanProduk) }}" target="_blank" class="btn btn-primary-custom shadow-sm">
                <i class="fas fa-print"></i>
                <span>Cetak Bon Penerimaan</span>
            </a>

            @can('penerimaan-produk-delete')
                <button type="button" class="btn btn-danger-custom shadow-sm" id="btnBatalPenerimaan">
                    <i class="fas fa-trash-alt"></i>
                    <span>Batalkan</span>
                </button>
            @endcan
        </div>
    </x-page-header>

    {{-- KPI CARDS (4 CARDS PERSIS KATALOG SOUVENIR) --}}
    <div class="row g-3 mb-3">
        {{-- 1. Total Macam Souvenir --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-slate">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #f1f5f9; color: #475569;">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Macam Souvenir</div>
                        <div class="kpi-value text-secondary">{{ number_format($penerimaanProduk->details->count()) }} <small class="fs-6 fw-normal">Item</small></div>
                        <div class="kpi-subtext">Varian Produk Diterima</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Total Lolos QC (Masuk Stok) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                        <i class="fas fa-circle-check"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Lolos QC (Masuk Stok)</div>
                        <div class="kpi-value text-success">+{{ number_format($penerimaanProduk->total_bagus) }} <small class="fs-6 fw-normal">Pcs</small></div>
                        <div class="kpi-subtext">Otomatis Menambah Etalase</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Total Reject / Cacat --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern {{ $penerimaanProduk->total_reject > 0 ? 'kpi-accent-danger' : 'kpi-accent-primary' }}">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: {{ $penerimaanProduk->total_reject > 0 ? '#fef2f2' : '#eff6ff' }}; color: {{ $penerimaanProduk->total_reject > 0 ? '#dc2626' : '#2563eb' }};">
                        <i class="fas {{ $penerimaanProduk->total_reject > 0 ? 'fa-triangle-exclamation' : 'fa-shield-halved' }}"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Total Cacat / Reject</div>
                        <div class="kpi-value {{ $penerimaanProduk->total_reject > 0 ? 'text-danger' : 'text-primary' }}">
                            {{ number_format($penerimaanProduk->total_reject) }} <small class="fs-6 fw-normal">Pcs</small>
                        </div>
                        <div class="kpi-subtext">{{ $penerimaanProduk->total_reject > 0 ? 'Cacat proses produksi' : 'Zero defect (100% mulus)' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Rasio Kelolosan QC --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card-modern kpi-accent-primary">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon-wrap" style="background: #eff6ff; color: #2563eb;">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div>
                        <div class="kpi-label">Tingkat Kelolosan QC</div>
                        <div class="kpi-value text-primary">{{ number_format($persenLolos, 1, ',', '.') }}%</div>
                        <div class="kpi-subtext">Dari total {{ number_format($totalDiproses) }} unit diproses</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN DETAILS ROW (STRUKTUR PERSIS KATALOG SOUVENIR SHOW) --}}
    <div class="row g-3">
        {{-- KOLOM KIRI: Informasi Penerimaan & Bukti Dokumen --}}
        <div class="col-12 col-lg-5">
            <div class="card card-modern mb-3">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex align-items-center gap-2">
                    <i class="fas fa-info-circle text-primary"></i>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Informasi Penerimaan &amp; QC</h6>
                </div>
                <div class="card-body p-3">
                    <div class="info-list">
                        <div class="info-list-item">
                            <span class="info-list-label">Nomor Penerimaan</span>
                            <span class="info-list-value font-monospace text-primary fw-bold">{{ $penerimaanProduk->kode_penerimaan }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Tanggal Selesai / Masuk</span>
                            <span class="info-list-value">{{ $penerimaanProduk->tanggal->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Tipe Produksi</span>
                            <span class="info-list-value">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem;">{{ $tipeLabel }}</span>
                            </span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Penanggung Jawab QC</span>
                            <span class="info-list-value text-dark fw-semibold">
                                <i class="fas fa-user-check text-primary me-1"></i>{{ $penerimaanProduk->nama_penanggung_jawab }}
                            </span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Referensi Bon Bahan (PBB)</span>
                            <span class="info-list-value font-monospace">
                                @if($penerimaanProduk->no_referensi_pemakaian)
                                    <span class="text-primary fw-bold">{{ $penerimaanProduk->no_referensi_pemakaian }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Petugas Verifikasi</span>
                            <span class="info-list-value">{{ $penerimaanProduk->user->name ?? 'Admin' }}</span>
                        </div>
                        <div class="info-list-item">
                            <span class="info-list-label">Dicatat Pada</span>
                            <span class="info-list-value">{{ $penerimaanProduk->created_at ? $penerimaanProduk->created_at->translatedFormat('d M Y, H:i') : '-' }}</span>
                        </div>
                    </div>

                    @if($penerimaanProduk->catatan)
                        <div class="mt-3 pt-3 border-top">
                            <div class="info-list-label mb-1.5 fw-semibold text-dark">Catatan Evaluasi QC:</div>
                            <div class="p-2.5 bg-light rounded text-muted" style="font-size: 0.8125rem; white-space: pre-line; line-height: 1.5;">
                                {{ $penerimaanProduk->catatan }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card Bukti Dokumen QC / Status Stok --}}
            <div class="card card-modern">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex align-items-center gap-2">
                    <i class="fas fa-paperclip text-warning"></i>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Bukti QC &amp; Serah Terima</h6>
                </div>
                <div class="card-body p-3">
                    @if($penerimaanProduk->bukti_dokumen)
                        @if($penerimaanProduk->is_pdf_bukti)
                            <div class="d-flex align-items-center justify-content-between p-2.5 bg-light rounded border">
                                <span class="small fw-semibold text-dark text-truncate"><i class="fas fa-file-pdf text-danger fa-lg me-2"></i>Dokumen Checklist QC (PDF)</span>
                                <a href="{{ $penerimaanProduk->bukti_dokumen_url }}" target="_blank" class="btn btn-primary-custom btn-sm">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span>Buka PDF</span>
                                </a>
                            </div>
                        @else
                            <div class="text-center p-2 bg-light rounded border">
                                <img src="{{ $penerimaanProduk->bukti_dokumen_url }}" alt="Bukti Penerimaan" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                                <div class="mt-2">
                                    <a href="{{ $penerimaanProduk->bukti_dokumen_url }}" target="_blank" class="btn btn-secondary-custom btn-sm">
                                        <i class="fas fa-search-plus"></i>
                                        <span>Lihat Ukuran Penuh</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="info-list">
                            <div class="info-list-item">
                                <span class="info-list-label">Lampiran Dokumen QC</span>
                                <span class="info-list-value text-muted fst-italic">Tidak ada lampiran file</span>
                            </div>
                            <div class="info-list-item">
                                <span class="info-list-label">Status Sinkronisasi Etalase</span>
                                <span class="info-list-value text-success fw-bold">
                                    <i class="fas fa-check-circle me-1"></i>Stok Bertambah (+{{ number_format($penerimaanProduk->total_bagus) }} unit)
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Rincian Souvenir Jadi Diterima --}}
        <div class="col-12 col-lg-7">
            <div class="card card-modern">
                <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-gift text-primary"></i>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.05em;">Rincian Souvenir Jadi Diterima ({{ $penerimaanProduk->details->count() }} Jenis)</h6>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.72rem; padding: 0.25rem 0.55rem;">
                        Total: +{{ number_format($penerimaanProduk->total_bagus) }} unit lolos QC
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-detail-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3" style="width: 45px;">No</th>
                                    <th>Nama Produk Souvenir &amp; Kode</th>
                                    <th>Kategori</th>
                                    <th class="text-center">Lolos QC (Bagus)</th>
                                    <th class="text-center">Reject</th>
                                    <th class="pe-3">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penerimaanProduk->details as $index => $detail)
                                    <tr>
                                        <td class="ps-3 text-muted font-monospace small">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">
                                                @if($detail->produk)
                                                    <a href="{{ route('produk.show', $detail->produk) }}" class="text-dark text-decoration-none">
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
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem;">
                                                {{ $detail->produk->kategori->nama_kategori ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center fw-bold text-success font-monospace">
                                            +{{ number_format($detail->qty_bagus) }} {{ $detail->satuan }}
                                        </td>
                                        <td class="text-center font-monospace">
                                            @if($detail->qty_reject > 0)
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">{{ number_format($detail->qty_reject) }} {{ $detail->satuan }}</span>
                                            @else
                                                <span class="text-muted">0</span>
                                            @endif
                                        </td>
                                        <td class="pe-3 text-muted small">
                                            {{ $detail->keterangan ?: '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                            Tidak ada rincian item souvenir.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr style="background: #f8fafc; font-weight: 700;">
                                    <td colspan="3" class="text-end ps-3 py-2.5" style="color: #475569; font-size: 0.78rem;">TOTAL HASIL PRODUKSI:</td>
                                    <td class="text-center py-2.5 text-success font-monospace" style="font-size: 0.85rem;">
                                        +{{ number_format($penerimaanProduk->total_bagus) }} unit
                                    </td>
                                    <td class="text-center py-2.5 text-danger font-monospace" style="font-size: 0.85rem;">
                                        {{ number_format($penerimaanProduk->total_reject) }} unit
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

{{-- Hidden form delete --}}
<form id="deleteFormShow" action="{{ route('penerimaan-produk.destroy', $penerimaanProduk) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnBatal = document.getElementById('btnBatalPenerimaan');
    const deleteForm = document.getElementById('deleteFormShow');

    if (btnBatal && deleteForm) {
        btnBatal.addEventListener('click', function() {
            Swal.fire({
                title: 'Batalkan Penerimaan Produk?',
                html: `<p style="color: #4b5563;">Transaksi <b>"{{ $penerimaanProduk->kode_penerimaan }}"</b> akan dibatalkan dan stok souvenir jadi akan dikurangi kembali dari katalog.</p>`,
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
