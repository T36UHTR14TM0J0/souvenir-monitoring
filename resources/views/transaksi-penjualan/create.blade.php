@extends('layouts.app')

@section('title', 'Catat Pesanan Marketplace')

@push('styles')
<style>
    /* ==========================================================================
       CATAT PESANAN MARKETPLACE - MODERN DESIGN SYSTEM
       ========================================================================== */

    .card-modern {
        background: #ffffff;
        border: 1px solid var(--gray-200, #e5e7eb);
        border-radius: 0.875rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .form-section-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #26476f;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1.5px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label-custom {
        font-size: 0.8rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.35rem;
        display: block;
    }
    .form-label-custom .required {
        color: #dc2626;
    }

    .form-control-modern, .form-select-modern {
        border-radius: 0.5rem;
        border: 1px solid #d1d5db;
        font-size: 0.875rem;
        padding: 0.6rem 0.85rem;
        transition: all 0.2s ease;
    }
    .form-control-modern:focus, .form-select-modern:focus {
        border-color: #26476f;
        box-shadow: 0 0 0 3px rgba(38, 71, 111, 0.15);
        outline: none;
    }

    /* THEAD TABLE HEADER NAVY GRADIENT */
    .table-navy thead {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
    }
    .table-navy thead th {
        background-color: transparent !important;
        color: #ffffff !important;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        border-bottom: none !important;
        padding: 0.85rem 1rem;
        white-space: nowrap;
    }

    /* BUTTONS */
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
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    .btn-add-item {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff !important;
        border: none;
        border-radius: 0.375rem;
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(16, 185, 129, 0.3);
    }
    .btn-add-item:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
    }

    .btn-cart-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff !important;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 0.375rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        transition: all 0.15s ease;
    }
    .btn-cart-delete:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-1px);
    }

    .btn-qty-step {
        width: 28px;
        height: 28px;
        border-radius: 0.375rem;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #334155;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-qty-step:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* SUMMARY SIDEBAR CARD */
    .summary-card-modern {
        background: #ffffff;
        border: 1px solid var(--gray-200, #e5e7eb);
        border-radius: 0.875rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .summary-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.65rem 0;
        border-bottom: 1px dashed #e2e8f0;
        font-size: 0.875rem;
    }
    .summary-item-row.grand-total {
        border-bottom: none;
        border-top: 2px solid #26476f;
        margin-top: 0.5rem;
        padding-top: 1rem;
        font-size: 1.15rem;
        font-weight: 800;
        color: #26476f;
    }

    .badge-kode-souvenir {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.15rem 0.45rem;
        border-radius: 0.375rem;
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER PERSIS MODUL HASIL PRODUKSI MASUK --}}
    <x-page-header
        icon="fas fa-boxes-packing"
        title="Catat Pesanan Marketplace"
        subtitle="Pencatatan pesanan penjualan online dari Shopee, Lazada, atau TikTok Shop dan potong stok etalase otomatis."
    >
        <a href="{{ route('transaksi-penjualan.index') }}" class="btn btn-secondary-custom shadow-sm">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </x-page-header>

    <form action="{{ route('transaksi-penjualan.store') }}" method="POST" id="pesananForm" novalidate>
        @csrf

        {{-- ROW 1: INFORMASI TRANSAKSI & PENGIRIMAN --}}
        <div class="card card-modern bg-white mb-4">
            <div class="card-body p-4">
                <div class="form-section-title">
                    <i class="fas fa-store text-primary"></i> 1. Informasi Pesanan &amp; Logistik Pengiriman
                </div>

                <div class="row g-3">
                    {{-- Channel Marketplace --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label-custom">
                            Marketplace <span class="required">*</span>
                        </label>
                        <select name="channel" id="channelSelect" class="form-select form-select-modern @error('channel') is-invalid @enderror" required>
                            <option value="shopee" {{ old('channel', 'shopee') === 'shopee' ? 'selected' : '' }}>Shopee</option>
                            <option value="lazada" {{ old('channel') === 'lazada' ? 'selected' : '' }}>Lazada</option>
                            <option value="tiktok" {{ old('channel') === 'tiktok' ? 'selected' : '' }}>TikTok Shop</option>
                        </select>
                        @error('channel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kode Transaksi Sistem --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label-custom">Kode Transaksi (Sistem)</label>
                        <input type="text" class="form-control form-control-modern font-monospace fw-bold"
                               value="{{ $kodeTransaksi }}" readonly
                               style="background-color: #f8fafc; color: #26476f;">
                    </div>

                    {{-- Tanggal Pesanan --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label-custom" for="tanggal">
                            Tanggal Pesanan <span class="required">*</span>
                        </label>
                        <input type="date" name="tanggal" id="tanggal"
                               class="form-control form-control-modern @error('tanggal') is-invalid @enderror"
                               value="{{ old('tanggal', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status Pesanan --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label-custom" for="status_pesanan">Status Pesanan</label>
                        <select name="status_pesanan" id="status_pesanan" class="form-select form-select-modern">
                            <option value="siap_packing" {{ old('status_pesanan') === 'siap_packing' ? 'selected' : '' }}>Siap Packing</option>
                            <option value="perlu_dikirim" {{ old('status_pesanan') === 'perlu_dikirim' ? 'selected' : '' }}>Siap Dikirim</option>
                            <option value="dikirim" {{ old('status_pesanan') === 'dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
                            <option value="selesai" {{ old('status_pesanan', 'selesai') === 'selesai' ? 'selected' : '' }}>Selesai (Pesanan Selesai)</option>
                            <option value="retur" {{ old('status_pesanan') === 'retur' ? 'selected' : '' }}>Retur / Komplain</option>
                            <option value="batal" {{ old('status_pesanan') === 'batal' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    {{-- No. Pesanan Marketplace --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label-custom" for="no_pesanan_marketplace">No. Pesanan Marketplace</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fas fa-hashtag"></i></span>
                            <input type="text" name="no_pesanan_marketplace" id="no_pesanan_marketplace"
                                   class="form-control form-control-modern"
                                   placeholder="Contoh: 241105ABCD123"
                                   value="{{ old('no_pesanan_marketplace') }}" maxlength="100">
                        </div>
                    </div>

                    {{-- Kurir & No. Resi --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label-custom" for="no_resi">Kurir &amp; No. Resi Pengiriman</label>
                        <div class="input-group">
                            <input type="text" name="kurir" id="kurir" style="max-width: 120px;"
                                   class="form-control form-control-modern" placeholder="SPX/J&T/JNE"
                                   value="{{ old('kurir') }}">
                            <input type="text" name="no_resi" id="no_resi"
                                   class="form-control form-control-modern" placeholder="Nomor Resi"
                                   value="{{ old('no_resi') }}" maxlength="100">
                        </div>
                    </div>

                    {{-- Pembeli / Username --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label-custom" for="nama_pelanggan">Pembeli / Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fas fa-user"></i></span>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan"
                                   class="form-control form-control-modern"
                                   placeholder="Nama pembeli / akun marketplace"
                                   value="{{ old('nama_pelanggan') }}" maxlength="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 2: DAFTAR SOUVENIR JADI DARI KATALOG (PILIH PRODUK) --}}
        <div class="card card-modern bg-white mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                    <div class="form-section-title mb-0 border-bottom-0 pb-0">
                        <i class="fas fa-gift text-primary"></i> 2. Pilih Souvenir Jadi dari Katalog
                    </div>
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        {{-- Dropdown Pilih Cepat dengan Select2 --}}
                        <div style="min-width: 280px; max-width: 460px;" class="flex-grow-1">
                            <select id="quickSelectProduk" class="form-select form-select-modern" data-placeholder="Cari &amp; Pilih Produk Cepat (nama / kode)...">
                                <option value=""></option>
                                @foreach($produks as $p)
                                    <option value="{{ $p->id }}" {{ $p->stok <= 0 ? 'disabled' : '' }}>
                                        [{{ $p->kode_produk }}] {{ $p->nama_produk }} &bull; Ready: {{ $p->stok }} {{ $p->satuan->singkatan ?? 'pcs' }} &bull; Rp {{ number_format($p->harga_setelah_diskon, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Input Cari Cepat --}}
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <span class="input-group-text bg-white text-muted"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchProduct" class="form-control"
                                   placeholder="Filter nama atau kode...">
                        </div>
                    </div>
                </div>

                <div class="product-list-container mb-2" style="max-height: 240px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <table class="table table-hover align-middle mb-0" id="productTable">
                        <thead class="table-light sticky-top" style="z-index: 2;">
                            <tr>
                                <th class="ps-3" style="font-size: 0.75rem; width: 110px;">Kode</th>
                                <th style="font-size: 0.75rem;">Nama Souvenir Jadi</th>
                                <th style="font-size: 0.75rem; width: 150px;">Kategori</th>
                                <th style="font-size: 0.75rem; width: 140px;" class="text-end">Harga Satuan</th>
                                <th style="font-size: 0.75rem; width: 130px;" class="text-center">Stok Etalase</th>
                                <th style="font-size: 0.75rem; width: 100px;" class="text-center pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produks as $p)
                            <tr class="product-item-row" data-id="{{ $p->id }}" style="cursor: pointer; {{ $p->stok <= 0 ? 'opacity: 0.55;' : '' }}">
                                <td class="ps-3">
                                    <span class="badge-kode-souvenir">{{ $p->kode_produk }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $p->nama_produk }}</div>
                                    @if($p->has_diskon)
                                        <span class="badge bg-danger text-white rounded-pill px-2 py-0" style="font-size: 0.65rem;">
                                            Diskon {{ $p->label_diskon }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $p->kategori->nama_kategori ?? '-' }}</span>
                                </td>
                                <td class="text-end text-nowrap">
                                    @if($p->has_diskon)
                                        <div class="fw-bold text-success" style="font-size: 0.875rem;">
                                            Rp {{ number_format($p->harga_setelah_diskon, 0, ',', '.') }}
                                        </div>
                                        <div class="text-decoration-line-through text-muted small" style="font-size: 0.72rem;">
                                            Rp {{ number_format($p->harga_jual, 0, ',', '.') }}
                                        </div>
                                    @else
                                        <div class="fw-bold text-dark" style="font-size: 0.875rem;">
                                            Rp {{ number_format($p->harga_jual, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($p->stok <= 0)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Habis (0)</span>
                                    @elseif($p->stok <= $p->stok_minimal)
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                            {{ $p->stok }} {{ $p->satuan->singkatan ?? 'pcs' }}
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            {{ $p->stok }} {{ $p->satuan->singkatan ?? 'pcs' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center pe-3">
                                    @if($p->stok > 0)
                                        <button type="button" class="btn-add-item btn-add-to-cart" data-id="{{ $p->id }}">
                                            <i class="fas fa-plus me-1"></i> Tambah
                                        </button>
                                    @else
                                        <span class="badge bg-secondary text-white">Kosong</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted small">
                                    Belum ada data souvenir jadi. Daftarkan produk terlebih dahulu di menu <strong>Katalog Souvenir</strong>.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ROW 3: KERANJANG BELANJA (KIRI) & RINGKASAN PEMBAYARAN (KANAN) --}}
        <div class="row g-4">
            {{-- KOLOM KIRI: KERANJANG PESANAN --}}
            <div class="col-12 col-lg-8">
                <div class="card card-modern bg-white h-100">
                    <div class="card-body p-4">
                        <div class="form-section-title">
                            <i class="fas fa-shopping-cart text-primary"></i> 3. Keranjang Produk Pesanan
                        </div>

                        <div class="table-responsive">
                            <table class="table table-navy align-middle mb-0" id="cartTable">
                                <thead>
                                    <tr>
                                        <th class="ps-3" style="min-width: 240px;">Produk Souvenir</th>
                                        <th style="width: 140px;" class="text-end">Harga Satuan (Rp)</th>
                                        <th style="width: 130px;" class="text-center">Jumlah (Qty)</th>
                                        <th style="width: 140px;" class="text-end">Subtotal</th>
                                        <th style="width: 60px;" class="text-center pe-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="cartTableBody">
                                    <tr id="cartEmptyRow">
                                        <td colspan="5" class="text-center py-5 text-muted small">
                                            <i class="fas fa-shopping-basket fa-2x mb-2 text-muted d-block opacity-50"></i>
                                            Keranjang belanja masih kosong.<br>
                                            Klik tombol "<strong>+ Tambah</strong>" pada salah satu souvenir di atas untuk memasukkan ke pesanan.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="cartErrorAlert" class="alert alert-danger mt-3 py-2 px-3 small" style="display: none;">
                            <i class="fas fa-exclamation-circle me-1"></i> Silakan pilih minimal 1 produk souvenir sebelum menyimpan pesanan.
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: RINGKASAN PEMBAYARAN & SUBMIT --}}
            <div class="col-12 col-lg-4">
                <div class="summary-card-modern h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="form-section-title text-primary">
                            <i class="fas fa-receipt"></i> 4. Ringkasan Pesanan
                        </div>

                        <div class="summary-item-row">
                            <span class="text-muted">Total Macam Item</span>
                            <span class="fw-bold text-dark" id="summaryTotalMacam">0 macam</span>
                        </div>

                        <div class="summary-item-row">
                            <span class="text-muted">Total Kuantitas (Unit)</span>
                            <span class="fw-bold text-dark" id="summaryTotalQty">0 pcs</span>
                        </div>

                        <div class="summary-item-row">
                            <span class="text-muted">Total Nilai Pesanan (Gross)</span>
                            <span class="fw-bold text-dark" id="summaryGross">Rp 0</span>
                        </div>

                        {{-- Potongan Marketplace --}}
                        <div class="mt-3 pt-3 border-top">
                            <label class="form-label-custom" for="biaya_admin_marketplace">
                                Potongan Marketplace (Admin &amp; Promo)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">Rp</span>
                                <input type="number" step="any" min="0"
                                       name="biaya_admin_marketplace" id="biaya_admin_marketplace"
                                       class="form-control form-control-modern text-end"
                                       placeholder="0" value="{{ old('biaya_admin_marketplace', 0) }}">
                            </div>
                            <div class="form-text small text-muted">Biaya layanan, admin, komisi dan subsidi voucher toko.</div>
                        </div>

                        <div class="summary-item-row mt-2" style="background: #f8fafc; padding: 0.5rem 0.75rem; border-radius: 6px;">
                            <span class="text-success fw-semibold"><i class="fas fa-wallet me-1"></i>Estimasi Dana Bersih</span>
                            <span class="fw-bold text-success" id="summaryNet">Rp 0</span>
                        </div>

                        {{-- Grand Total --}}
                        <div class="summary-item-row grand-total">
                            <span>Grand Total</span>
                            <span id="summaryGrandTotal">Rp 0</span>
                        </div>

                        {{-- Catatan --}}
                        <div class="mt-3">
                            <label class="form-label-custom" for="catatan">Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan" id="catatan" rows="2"
                                      class="form-control form-control-modern"
                                      placeholder="Catatan packing, reques pembeli, dsb...">{{ old('catatan') }}</textarea>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('transaksi-penjualan.index') }}" class="btn btn-secondary-custom flex-fill justify-content-center">
                            <i class="fas fa-times"></i>
                            <span>Batal</span>
                        </a>
                        <button type="submit" class="btn btn-primary-custom flex-fill justify-content-center shadow-sm" id="submitBtn">
                            <i class="fas fa-save"></i>
                            <span>Simpan Pesanan</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    // Peta Data Souvenir Jadi
    const rawProduks = @json($produksJson ?? []);
    const produksMap = {};
    rawProduks.forEach(p => produksMap[p.id] = p);

    // State Keranjang
    let cart = {};

    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number || 0);
    }

    // 1. FILTER PENCARIAN PRODUK
    const searchInput = document.getElementById('searchProduct');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('#productTable tbody tr.product-item-row');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    // 2. DROPDOWN PILIH CEPAT DENGAN SELECT2
    function initQuickSelect2() {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            const $quickSelect = $('#quickSelectProduk');
            
            // Re-initialize if already initialized or initialize fresh
            if ($quickSelect.hasClass('select2-hidden-accessible')) {
                $quickSelect.select2('destroy');
            }

            $quickSelect.select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Cari & Pilih Produk Cepat (nama / kode)...',
                allowClear: false,
                language: {
                    noResults: function () {
                        return 'Produk souvenir tidak ditemukan';
                    },
                    searching: function () {
                        return 'Mencari produk...';
                    }
                }
            }).on('select2:select', function (e) {
                const selectedId = e.params.data.id;
                if (selectedId) {
                    addToCart(selectedId);
                    // Reset select2 kembali ke placeholder agar kasir bisa langsung memilih produk berikutnya
                    $(this).val('').trigger('change.select2');
                }
            });
        } else {
            const quickSelect = document.getElementById('quickSelectProduk');
            if (quickSelect) {
                quickSelect.addEventListener('change', function () {
                    if (this.value) {
                        addToCart(this.value);
                        this.value = '';
                    }
                });
            }
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initQuickSelect2);
    } else {
        initQuickSelect2();
    }

    // 3. KLIK TOMBOL ATAU BARIS TABEL PRODUK
    document.querySelectorAll('#productTable tbody tr.product-item-row').forEach(row => {
        row.addEventListener('click', function (e) {
            // Hindari trigger ganda jika yang diklik tombol di dalam cell aksi
            if (e.target.closest('.btn-add-to-cart')) return;
            const id = this.dataset.id;
            if (id) addToCart(id);
        });
    });

    document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const id = this.dataset.id;
            if (id) addToCart(id);
        });
    });

    // 4. FUNCTION ADD TO CART
    window.addToCart = function (produkId) {
        const id = parseInt(produkId);
        const p = produksMap[id];
        if (!p) {
            console.warn('Produk tidak ditemukan:', produkId);
            return;
        }

        if (p.stok <= 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Habis',
                    text: `Souvenir "${p.nama}" saat ini sedang kosong/habis.`,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                alert(`Souvenir "${p.nama}" sedang kosong.`);
            }
            return;
        }

        if (cart[id]) {
            if (cart[id].qty + 1 > p.stok) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Batas Stok Ready',
                        text: `Maksimal kuantitas "${p.nama}" tersedia hanya ${p.stok} ${p.satuan}.`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    alert(`Stok maksimal "${p.nama}" adalah ${p.stok} ${p.satuan}.`);
                }
                return;
            }
            cart[id].qty += 1;
        } else {
            cart[id] = {
                id: p.id,
                nama: p.nama,
                kode: p.kode,
                satuan: p.satuan,
                stok_tersedia: p.stok,
                harga_satuan: p.harga_jual,
                qty: 1
            };
        }

        document.getElementById('cartErrorAlert').style.display = 'none';
        renderCart();
    };

    // 5. UPDATE QTY
    window.changeCartQty = function (id, delta) {
        if (!cart[id]) return;
        const newQty = cart[id].qty + delta;
        if (newQty < 1) return;
        if (newQty > cart[id].stok_tersedia) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Batas Stok',
                    text: `Maksimal kuantitas hanya ${cart[id].stok_tersedia} ${cart[id].satuan}.`,
                    timer: 1800,
                    showConfirmButton: false
                });
            }
            return;
        }
        cart[id].qty = newQty;
        renderCart();
    };

    window.onCartQtyInput = function (id, val) {
        if (!cart[id]) return;
        let qty = parseInt(val) || 1;
        if (qty < 1) qty = 1;
        if (qty > cart[id].stok_tersedia) {
            qty = cart[id].stok_tersedia;
        }
        cart[id].qty = qty;
        renderCart();
    };

    // 6. UPDATE HARGA SATUAN
    window.onCartHargaInput = function (id, val) {
        if (!cart[id]) return;
        const harga = parseFloat(val) || 0;
        cart[id].harga_satuan = Math.max(0, harga);
        renderCart();
    };

    // 7. REMOVE DARI KERANJANG
    window.removeFromCart = function (id) {
        delete cart[id];
        renderCart();
    };

    // 8. RENDER KERANJANG & REKALKULASI TOTAL
    function renderCart() {
        const tbody = document.getElementById('cartTableBody');
        const keys = Object.keys(cart);

        if (keys.length === 0) {
            tbody.innerHTML = `
                <tr id="cartEmptyRow">
                    <td colspan="5" class="text-center py-5 text-muted small">
                        <i class="fas fa-shopping-basket fa-2x mb-2 text-muted d-block opacity-50"></i>
                        Keranjang belanja masih kosong.<br>
                        Klik tombol "<strong>+ Tambah</strong>" pada salah satu souvenir di atas untuk memasukkan ke pesanan.
                    </td>
                </tr>
            `;
            updateTotals(0, 0, 0);
            return;
        }

        let html = '';
        let totalQty = 0;
        let grossTotal = 0;
        let index = 0;

        keys.forEach(id => {
            const item = cart[id];
            const subtotal = item.qty * item.harga_satuan;
            totalQty += item.qty;
            grossTotal += subtotal;

            html += `
                <tr>
                    <td class="ps-3">
                        <input type="hidden" name="items[${index}][produk_id]" value="${item.id}">
                        <div class="fw-semibold text-dark">${item.nama}</div>
                        <div class="small text-muted font-monospace">
                            ${item.kode} &bull; Ready: ${item.stok_tersedia} ${item.satuan}
                        </div>
                    </td>
                    <td class="text-end" style="width: 140px;">
                        <input type="number" name="items[${index}][harga_satuan]"
                               class="form-control form-control-sm text-end fw-semibold"
                               min="0" step="100" value="${item.harga_satuan}"
                               onchange="onCartHargaInput(${item.id}, this.value)"
                               oninput="onCartHargaInput(${item.id}, this.value)">
                    </td>
                    <td class="text-center" style="width: 130px;">
                        <div class="d-inline-flex align-items-center gap-1">
                            <button type="button" class="btn-qty-step" onclick="changeCartQty(${item.id}, -1)">&minus;</button>
                            <input type="number" name="items[${index}][qty]"
                                   class="form-control form-control-sm text-center"
                                   style="width: 55px;"
                                   min="1" max="${item.stok_tersedia}" value="${item.qty}"
                                   onchange="onCartQtyInput(${item.id}, this.value)">
                            <button type="button" class="btn-qty-step" onclick="changeCartQty(${item.id}, 1)">&plus;</button>
                        </div>
                    </td>
                    <td class="text-end fw-bold text-dark text-nowrap" style="width: 140px;">
                        ${formatRupiah(subtotal)}
                    </td>
                    <td class="text-center pe-3" style="width: 60px;">
                        <button type="button" class="btn-cart-delete" onclick="removeFromCart(${item.id})" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
            index++;
        });

        tbody.innerHTML = html;
        updateTotals(keys.length, totalQty, grossTotal);
    }

    // 9. REKALKULASI TOTAL DI RINGKASAN
    function updateTotals(macamCount, totalQty, grossTotal) {
        document.getElementById('summaryTotalMacam').textContent = macamCount + ' macam';
        document.getElementById('summaryTotalQty').textContent = totalQty + ' pcs';
        document.getElementById('summaryGross').textContent = formatRupiah(grossTotal);
        document.getElementById('summaryGrandTotal').textContent = formatRupiah(grossTotal);

        const adminInput = document.getElementById('biaya_admin_marketplace');
        const adminFee = parseFloat(adminInput ? adminInput.value : 0) || 0;
        const netTotal = Math.max(0, grossTotal - adminFee);
        document.getElementById('summaryNet').textContent = formatRupiah(netTotal);
    }

    const adminInputEl = document.getElementById('biaya_admin_marketplace');
    if (adminInputEl) {
        adminInputEl.addEventListener('input', function () {
            const keys = Object.keys(cart);
            let gross = 0;
            let qty = 0;
            keys.forEach(id => {
                gross += (cart[id].qty * cart[id].harga_satuan);
                qty += cart[id].qty;
            });
            updateTotals(keys.length, qty, gross);
        });
    }

    // 10. VALIDASI SEBELUM SUBMIT
    const form = document.getElementById('pesananForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (Object.keys(cart).length === 0) {
                e.preventDefault();
                const alertEl = document.getElementById('cartErrorAlert');
                alertEl.style.display = 'block';
                alertEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }

            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
        });
    }

})();
</script>
@endpush
