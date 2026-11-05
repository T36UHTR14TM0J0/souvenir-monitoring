@extends('layouts.app')

@section('title', 'Detail Pesanan Marketplace')

@push('styles')
<style>
    .card-modern {
        background: #ffffff;
        border: 1px solid var(--gray-200, #e5e7eb);
        border-radius: 0.875rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .btn-secondary-custom {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        border: 1px solid #475569;
        color: #ffffff !important;
        font-weight: 600;
        padding: 0.625rem 1.25rem;
        border-radius: 0.625rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        font-size: 0.875rem;
        box-shadow: 0 2px 4px rgba(71, 85, 105, 0.25);
    }
    .btn-secondary-custom:hover {
        background: linear-gradient(135deg, #475569 0%, #334155 100%);
        border-color: #334155;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(71, 85, 105, 0.35);
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%);
        border: 1px solid #1a304d;
        color: #ffffff !important;
        font-weight: 600;
        padding: 0.625rem 1.25rem;
        border-radius: 0.625rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        font-size: 0.875rem;
        box-shadow: 0 2px 4px rgba(38, 71, 111, 0.25);
    }
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #1f3a5c 0%, #14243b 100%);
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(38, 71, 111, 0.35);
    }

    .btn-danger-custom {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: 1px solid #dc2626;
        color: #ffffff !important;
        font-weight: 600;
        padding: 0.625rem 1.25rem;
        border-radius: 0.625rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        font-size: 0.875rem;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.25);
    }
    .btn-danger-custom:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.35);
    }

    /* INFO GRID */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 1rem;
    }
    .info-item {
        padding: 0.875rem 1rem;
        background: #f8fafc;
        border-radius: 0.625rem;
        border-left: 3.5px solid #26476f;
    }
    .info-item-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }
    .info-item-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #0f172a;
    }

    /* TABLE THEAD NAVY GRADIENT */
    .table-detail thead {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
    }
    .table-detail thead th {
        background-color: transparent !important;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        border-bottom: none !important;
        padding: 0.95rem 1rem;
        white-space: nowrap;
    }
    .table-detail tbody td {
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        padding: 0.9rem 1rem;
        background-color: #ffffff;
        font-size: 0.875rem;
    }

    /* SUMMARY BOX */
    .summary-box {
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%);
        border-radius: 0.875rem;
        padding: 1.5rem;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(38, 71, 111, 0.25);
    }
    .summary-box h6 {
        color: #ffffff !important;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        font-size: 0.875rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    .summary-row:last-child {
        border-bottom: none;
    }
    .summary-row .label {
        color: rgba(255, 255, 255, 0.8);
    }
    .summary-row .value {
        font-weight: 600;
    }
    .summary-row.grand {
        padding-top: 0.85rem;
        margin-top: 0.5rem;
        border-top: 2px solid rgba(255, 255, 255, 0.25);
        border-bottom: none;
    }
    .summary-row.grand .label {
        font-size: 0.95rem;
        color: #ffffff;
        font-weight: 700;
    }
    .summary-row.grand .value {
        font-size: 1.4rem;
        font-weight: 700;
        color: #fde68a;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-receipt"
        title="Detail Pesanan Marketplace"
        subtitle="Nomor Pesanan: {{ $transaksi->kode_transaksi }}"
    >
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('transaksi-penjualan.index') }}" class="btn btn-secondary-custom">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar</span>
            </a>
            <a href="{{ route('transaksi-penjualan.cetak-struk', $transaksi) }}" target="_blank" class="btn btn-primary-custom">
                <i class="fas fa-clipboard-check"></i>
                <span>Cetak Packing Slip</span>
            </a>
        </div>
    </x-page-header>

    <div class="row g-4 mb-4">
        {{-- KOLOM KIRI: INFORMASI PESANAN & RINCIAN PRODUK --}}
        <div class="col-12 col-lg-8">
            {{-- INFORMASI PESANAN & MARKETPLACE --}}
            <div class="card card-modern bg-white mb-4">
                <div class="card-body p-4">
                    <h6 class="mb-3 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #26476f;">
                        <i class="fas fa-info-circle me-1"></i> Informasi Pesanan &amp; Pengiriman
                    </h6>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-item-label">Marketplace</div>
                            <div class="info-item-value">
                                @php $channelBadge = $transaksi->channel_badge_config; @endphp
                                <span class="badge" style="background: {{ $channelBadge['bg'] }}; color: {{ $channelBadge['color'] }}; border: 1px solid {{ $channelBadge['border'] }}; font-size: 0.8rem; padding: 0.35rem 0.65rem;">
                                    <i class="fas {{ $channelBadge['icon'] }} me-1"></i>{{ $channelBadge['label'] }}
                                </span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-item-label">Status Pesanan</div>
                            <div class="info-item-value">
                                @php $statusBadge = $transaksi->status_pesanan_badge_config; @endphp
                                <span class="badge" style="background: {{ $statusBadge['bg'] }}; color: {{ $statusBadge['color'] }}; border: 1px solid {{ $statusBadge['border'] }}; font-size: 0.8rem; padding: 0.35rem 0.65rem;">
                                    {{ $statusBadge['label'] }}
                                </span>
                            </div>
                        </div>

                        @if($transaksi->no_pesanan_marketplace)
                        <div class="info-item">
                            <div class="info-item-label">No. Pesanan Marketplace</div>
                            <div class="info-item-value font-monospace" style="color: #26476f; font-size: 0.85rem;">
                                {{ $transaksi->no_pesanan_marketplace }}
                            </div>
                        </div>
                        @endif

                        <div class="info-item">
                            <div class="info-item-label">Ekspedisi / Kurir</div>
                            <div class="info-item-value">
                                {{ $transaksi->kurir ?: 'Menunggu Penjemputan Kurir' }}
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-item-label">Nomor Resi Pengiriman</div>
                            <div class="info-item-value font-monospace">
                                {{ $transaksi->no_resi ?: 'Belum Ada Resi' }}
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-item-label">Tanggal Pesanan</div>
                            <div class="info-item-value">{{ $transaksi->tanggal->translatedFormat('d F Y') }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-item-label">Pembeli / Penerima</div>
                            <div class="info-item-value">{{ $transaksi->nama_pelanggan ?: 'Pembeli Online' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-item-label">Pencatatan Saldo</div>
                            <div class="info-item-value">
                                <span class="badge bg-light text-dark border">
                                    {{ $transaksi->metode_bayar_label }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($transaksi->catatan)
                    <div class="mt-3 p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="info-item-label">Catatan Pesanan</div>
                        <div style="font-size: 0.875rem; color: #475569;">{{ $transaksi->catatan }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- DETAIL PRODUK SOUVENIR JADI --}}
            <div class="card card-modern bg-white">
                <div class="card-body p-0">
                    <div class="p-4 pb-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #26476f;">
                            <i class="fas fa-boxes-stacked me-1"></i> Rincian Produk Souvenir Jadi ({{ $transaksi->details->count() }} macam)
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-detail align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="width: 50px;">No</th>
                                    <th>Nama Produk Souvenir</th>
                                    <th class="text-end" style="width: 150px;">Harga Satuan</th>
                                    <th class="text-center" style="width: 100px;">Jumlah</th>
                                    <th class="pe-4 text-end" style="width: 170px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksi->details as $index => $item)
                                <tr>
                                    <td class="ps-4 text-muted font-monospace small">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $item->nama_produk }}</div>
                                        <div class="small text-muted font-monospace">
                                            {{ $item->produk->kode_produk ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-2 py-1 fs-6">
                                            {{ $item->qty }}
                                        </span>
                                        <span class="text-muted small ms-1">{{ $item->produk->satuan->singkatan ?? 'pcs' }}</span>
                                    </td>
                                    <td class="pe-4 text-end fw-bold text-dark">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background: #f8fafc; font-weight: 700;">
                                    <td colspan="3" class="text-end ps-4 py-3" style="color: #475569;">TOTAL PRODUK:</td>
                                    <td class="text-center py-3 text-primary">
                                        {{ $transaksi->details->sum('qty') }} unit
                                    </td>
                                    <td class="pe-4 text-end py-3 text-dark">
                                        Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: RINGKASAN FINANSIAL & UPDATE STATUS --}}
        <div class="col-12 col-lg-4">
            {{-- SUMMARY BOX --}}
            <div class="summary-box mb-4">
                <h6 class="mb-3 text-white" style="font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: #ffffff !important;">
                    <i class="fas fa-calculator me-1 text-white"></i> Ringkasan Keuangan Pesanan
                </h6>

                <div class="summary-row">
                    <span class="label">Total Nilai Produk</span>
                    <span class="value">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>

                @if($transaksi->total_diskon > 0)
                <div class="summary-row">
                    <span class="label">Diskon Penjualan</span>
                    <span class="value" style="color: #fca5a5;">- Rp {{ number_format($transaksi->total_diskon, 0, ',', '.') }}</span>
                </div>
                @endif

                <div class="summary-row grand">
                    <span class="label">Grand Total (Omzet)</span>
                    <span class="value">Rp {{ number_format($transaksi->grand_total, 0, ',', '.') }}</span>
                </div>

                @if($transaksi->biaya_admin_marketplace > 0)
                <div class="summary-row" style="color: #fca5a5; margin-top: 0.25rem;">
                    <span class="label" style="color: #fca5a5;"><i class="fas fa-percent me-1"></i>Potongan Marketplace</span>
                    <span class="value">- Rp {{ number_format($transaksi->biaya_admin_marketplace, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row" style="background: rgba(16, 185, 129, 0.2); border-radius: 8px; padding: 0.65rem 0.85rem; margin-top: 0.65rem;">
                    <span class="label" style="color: #6ee7b7; font-weight: 700;"><i class="fas fa-wallet me-1"></i>Dana Bersih Masuk Saldo</span>
                    <span class="value" style="color: #6ee7b7; font-size: 1.15rem; font-weight: 700;">Rp {{ number_format($transaksi->penghasilan_bersih, 0, ',', '.') }}</span>
                </div>
                @endif

                <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255,255,255,0.15);">
                    <div class="text-center" style="font-size: 0.75rem; opacity: 0.85;">
                        <i class="fas fa-check-circle text-success me-1"></i> Stok produk souvenir jadi telah dipotong otomatis
                    </div>
                </div>
            </div>

            {{-- FORM UPDATE STATUS PESANAN & RESI --}}
            <div class="card card-modern bg-white p-4 mb-4">
                <h6 class="fw-bold mb-3 text-dark" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: #26476f;">
                    <i class="fas fa-sync-alt text-primary me-1"></i> Update Status Pesanan &amp; Resi
                </h6>

                <form action="{{ route('transaksi-penjualan.update-status', $transaksi) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="status_pesanan" class="form-label text-muted small fw-bold mb-1">Status Alur Pesanan <span class="text-danger">*</span></label>
                        <select name="status_pesanan" id="status_pesanan" class="form-select" required>
                            @foreach(App\Models\Transaksi::STATUS_PESANAN as $statusKey => $statusLabel)
                                <option value="{{ $statusKey }}" {{ $transaksi->status_pesanan === $statusKey ? 'selected' : '' }}>
                                    {{ $statusLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="kurir" class="form-label text-muted small fw-bold mb-1">Ekspedisi / Kurir</label>
                        <input type="text" name="kurir" id="kurir" class="form-control" placeholder="Contoh: SPX Standard, J&T, JNE" value="{{ old('kurir', $transaksi->kurir) }}">
                    </div>

                    <div class="mb-3">
                        <label for="no_resi" class="form-label text-muted small fw-bold mb-1">Nomor Resi Ekspedisi</label>
                        <input type="text" name="no_resi" id="no_resi" class="form-control font-monospace" placeholder="Nomor Resi / AWB" value="{{ old('no_resi', $transaksi->no_resi) }}">
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 justify-content-center">
                        <i class="fas fa-save me-1"></i>
                        <span>Simpan Perubahan Status</span>
                    </button>
                </form>
            </div>

            {{-- BUKTI TRANSAKSI JIKA ADA --}}
            @if($transaksi->bukti_transaksi)
            <div class="card card-modern bg-white p-3 mb-4">
                <h6 class="fw-bold mb-2 text-dark" style="font-size: 0.85rem;">
                    <i class="fas fa-paperclip text-primary me-1"></i> Bukti Transaksi Marketplace
                </h6>
                @if($transaksi->is_pdf_bukti)
                    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                        <span class="small text-truncate"><i class="fas fa-file-pdf text-danger fa-lg me-2"></i>Dokumen PDF</span>
                        <a href="{{ $transaksi->bukti_transaksi_url }}" target="_blank" class="btn btn-sm btn-primary-custom">
                            <i class="fas fa-external-link-alt me-1"></i> Buka PDF
                        </a>
                    </div>
                @else
                    <div class="text-center p-2 bg-light rounded-3">
                        <img src="{{ $transaksi->bukti_transaksi_url }}" alt="Bukti Transaksi" class="img-fluid rounded" style="max-height: 220px; object-fit: contain;">
                        <div class="mt-2">
                            <a href="{{ $transaksi->bukti_transaksi_url }}" target="_blank" class="btn btn-sm btn-secondary-custom">
                                <i class="fas fa-search-plus me-1"></i> Lihat Ukuran Penuh
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            @endif

            {{-- PEMBATALAN / HAPUS PESANAN --}}
            @can('penjualan-delete')
                @if($transaksi->tanggal->isToday())
                <div class="card card-modern bg-white p-4">
                    <h6 class="fw-bold mb-2 text-danger" style="font-size: 0.85rem;">
                        <i class="fas fa-exclamation-triangle me-1"></i> Pembatalan Transaksi
                    </h6>
                    <p class="text-muted small mb-3">
                        Pesanan ini dapat dihapus karena dicatat hari ini. Kuantitas stok produk souvenir jadi akan <strong>otomatis dikembalikan ke etalase</strong>.
                    </p>

                    <button type="button" class="btn btn-danger-custom w-100 justify-content-center" id="btnHapusPesanan">
                        <i class="fas fa-trash-alt me-1"></i>
                        <span>Hapus &amp; Kembalikan Stok</span>
                    </button>
                </div>

                {{-- Hidden Delete Form --}}
                <form id="deleteFormShow" action="{{ route('transaksi-penjualan.destroy', $transaksi) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
                @endif
            @endcan
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnHapus = document.getElementById('btnHapusPesanan');
    const deleteForm = document.getElementById('deleteFormShow');

    if (btnHapus && deleteForm) {
        btnHapus.addEventListener('click', function() {
            if (window.Swal) {
                Swal.fire({
                    title: 'Hapus Pesanan?',
                    html: `Apakah Anda yakin ingin membatalkan transaksi <b>"{{ $transaksi->kode_transaksi }}"</b>?<br><small class="text-muted">Kuantitas stok souvenir jadi akan otomatis dikembalikan ke katalog etalase.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#475569',
                    confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Ya, Hapus & Kembalikan Stok',
                    cancelButtonText: '<i class="fas fa-times me-1"></i> Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteForm.submit();
                    }
                });
            } else {
                if (confirm('Apakah Anda yakin ingin menghapus transaksi ini? Stok akan dikembalikan ke etalase.')) {
                    deleteForm.submit();
                }
            }
        });
    }
});
</script>
@endpush
