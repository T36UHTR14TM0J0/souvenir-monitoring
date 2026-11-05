@extends('layouts.app')

@section('title', 'Catat Pembelian Bahan Baku Online')

@push('styles')
<style>
    /* SEARCH INPUT GROUP PERSIS KATALOG SOUVENIR */
    .search-input-group {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .search-input-group .input-group-text {
        height: 38px;
        background-color: #ffffff;
        border-color: #cbd5e1;
        color: #64748b;
        font-size: 0.8125rem;
    }
    .search-input-group .form-control {
        height: 38px;
        background-color: #ffffff;
        border-color: #cbd5e1;
        font-size: 0.8125rem;
        color: #1e293b;
    }
    .search-input-group .form-control:focus {
        background-color: #ffffff;
        border-color: #26476f;
        box-shadow: 0 0 0 3px rgba(38, 71, 111, 0.12);
    }

    /* THEAD TABLE HEADER NAVY GRADIENT PERSIS KATALOG SOUVENIR */
    .table-cart-pembelian thead {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
    }
    .table-cart-pembelian thead th {
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
    .table-cart-pembelian tbody td {
        padding: 0.75rem 0.85rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.8125rem;
        background-color: #ffffff;
    }

    /* BADGES & AVATAR PERSIS KATALOG SOUVENIR */
    .badge-kode {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.725rem;
        font-weight: 700;
        padding: 0.2rem 0.5rem;
        border-radius: 0.375rem;
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #e2e8f0;
    }
    .badge-satuan {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        padding: 0.25rem 0.6rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        border: 1px solid #e2e8f0;
    }
    .badge-kategori-custom {
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 600;
        padding: 0.25rem 0.6rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        border: 1px solid #bfdbfe;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .avatar-icon-souvenir {
        width: 34px;
        height: 34px;
        border-radius: 0.5rem;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        color: #4338ca;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        flex-shrink: 0;
        border: 1px solid #c7d2fe;
    }

    .btn-add-item {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff !important;
        border: 1px solid #059669;
        border-radius: 0.45rem;
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(16, 185, 129, 0.25);
    }
    .btn-add-item:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
    }

    .btn-cart-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff !important;
        border: 1px solid #dc2626;
        width: 30px;
        height: 30px;
        border-radius: 0.45rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        transition: all 0.15s ease;
    }
    .btn-cart-delete:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER PERSIS KATALOG SOUVENIR CREATE --}}
    <x-page-header
        icon="fas fa-truck-ramp-box"
        title="Catat Pembelian Bahan Baku Online"
        subtitle="Pengadaan material mentah, blanko souvenir, dan packaging kargo dari marketplace supplier."
    />

    {{-- FORM CARD PERSIS KATALOG SOUVENIR CREATE --}}
    <div class="card card-modern">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('transaksi-pembelian.store') }}" method="POST" id="pembelianForm" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- ============ SECTION 1: DATA TOKO SUPPLIER & MARKETPLACE ============ --}}
                <div class="form-section-title">
                    <i class="fas fa-store"></i>
                    Informasi Toko Supplier &amp; Pengiriman Kargo
                </div>

                <div class="row g-3">
                    {{-- Kode Pembelian --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label-custom" for="kode_pembelian_display">
                            Kode Pembelian <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-barcode input-icon"></i>
                            <input type="text"
                                   id="kode_pembelian_display"
                                   class="form-control form-control-modern"
                                   value="{{ $kodePembelian }}"
                                   readonly
                                   style="background-color: #f9fafb; font-family: monospace; font-weight: 700; color: #26476f;">
                        </div>
                        <div class="required-note">
                            <i class="fas fa-circle-info"></i>
                            <span>Kode unik otomatis</span>
                        </div>
                    </div>

                    {{-- Tanggal Beli --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label-custom" for="tanggal">
                            Tanggal Beli <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-calendar-check input-icon"></i>
                            <input type="date"
                                   name="tanggal"
                                   id="tanggal"
                                   class="form-control form-control-modern @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                        </div>
                        <div class="invalid-feedback" id="error_tanggal" @if(!$errors->has('tanggal')) style="display: none !important;" @endif>
                            @error('tanggal'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_tanggal" @if($errors->has('tanggal')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Tanggal checkout / transaksi</span>
                        </div>
                    </div>

                    {{-- Platform Belanja --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label-custom" for="platform">
                            Platform Belanja <span class="required">*</span>
                        </label>
                        <select name="platform"
                                id="platform"
                                class="form-select form-select-modern @error('platform') is-invalid @enderror"
                                required>
                            @foreach(App\Models\Pembelian::PLATFORMS as $key => $label)
                                <option value="{{ $key }}" {{ old('platform') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="error_platform" @if(!$errors->has('platform')) style="display: none !important;" @endif>
                            @error('platform'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_platform" @if($errors->has('platform')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Pilih kanal pembelian bahan</span>
                        </div>
                    </div>

                    {{-- Nama Toko / Seller Online --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label-custom" for="supplier">
                            Nama Toko / Supplier <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-shop input-icon"></i>
                            <input type="text"
                                   name="supplier"
                                   id="supplier"
                                   class="form-control form-control-modern @error('supplier') is-invalid @enderror"
                                   value="{{ old('supplier') }}"
                                   placeholder="Contoh: Pabrik Keramik Official"
                                   maxlength="150"
                                   required>
                        </div>
                        <div class="invalid-feedback" id="error_supplier" @if(!$errors->has('supplier')) style="display: none !important;" @endif>
                            @error('supplier'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_supplier" @if($errors->has('supplier')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib diisi, nama seller / toko</span>
                        </div>
                    </div>

                    {{-- No. Pesanan / Invoice Supplier --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label-custom" for="no_invoice_online">
                            No. Pesanan / Invoice Supplier
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-file-invoice input-icon"></i>
                            <input type="text"
                                   name="no_invoice_online"
                                   id="no_invoice_online"
                                   class="form-control form-control-modern @error('no_invoice_online') is-invalid @enderror"
                                   value="{{ old('no_invoice_online') }}"
                                   placeholder="Contoh: INV/20261105/SHP/998822">
                        </div>
                        <div class="required-note">
                            <i class="fas fa-circle-info"></i>
                            <span>Opsional, nomor referensi pesanan marketplace</span>
                        </div>
                    </div>

                    {{-- Status Kedatangan Barang --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label-custom" for="status_pembelian">
                            Status Kedatangan Barang <span class="required">*</span>
                        </label>
                        <select name="status_pembelian"
                                id="status_pembelian"
                                class="form-select form-select-modern @error('status_pembelian') is-invalid @enderror"
                                required>
                            <option value="dipesan" {{ old('status_pembelian') === 'dipesan' ? 'selected' : '' }}>
                                Dipesan (Stok belum bertambah)
                            </option>
                            <option value="dikirim" {{ old('status_pembelian') === 'dikirim' ? 'selected' : '' }}>
                                Dikirim Kargo (Stok belum bertambah)
                            </option>
                            <option value="diterima" {{ old('status_pembelian', 'diterima') === 'diterima' ? 'selected' : '' }}>
                                Diterima di Workshop (Stok langsung masuk gudang)
                            </option>
                        </select>
                        <div class="required-note">
                            <i class="fas fa-circle-info"></i>
                            <span>Stok gudang bertambah saat berstatus Diterima</span>
                        </div>
                    </div>

                    {{-- Kurir Kargo & No. Resi --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label-custom" for="kurir_kargo">
                            Kurir Kargo &amp; Nomor Resi
                        </label>
                        <div class="input-group">
                            <input type="text"
                                   name="kurir_kargo"
                                   id="kurir_kargo"
                                   style="max-width: 130px;"
                                   class="form-control form-control-modern"
                                   placeholder="J&amp;T Cargo"
                                   value="{{ old('kurir_kargo') }}">
                            <input type="text"
                                   name="no_resi_kargo"
                                   id="no_resi_kargo"
                                   class="form-control form-control-modern"
                                   placeholder="No. Resi Pengiriman"
                                   value="{{ old('no_resi_kargo') }}">
                        </div>
                        <div class="required-note">
                            <i class="fas fa-circle-info"></i>
                            <span>Opsional, nama ekspedisi &amp; nomor resi pelacakan</span>
                        </div>
                    </div>
                </div>

                {{-- ============ SECTION 2: PILIH & RINCIAN BAHAN BAKU ============ --}}
                <div class="form-section mt-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                        <div class="form-section-title mb-0">
                            <i class="fas fa-layer-group"></i>
                            1. Pilih Material Mentah &amp; Blanko Souvenir
                        </div>
                        <div style="min-width: 280px;">
                            <div class="input-group search-input-group shadow-none">
                                <span class="input-group-text bg-white border-end-0 text-muted ps-3 pe-2">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text"
                                       id="searchProduct"
                                       class="form-control border-start-0 ps-1"
                                       placeholder="Cari nama atau kode bahan...">
                            </div>
                        </div>
                    </div>

                    <div class="product-list-container bg-white mb-4" style="max-height: 230px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 0.625rem;">
                        <table class="table table-hover align-middle mb-0" id="productTable">
                            <thead class="table-light sticky-top" style="z-index: 2;">
                                <tr>
                                    <th class="ps-3 text-start" style="font-size: 0.72rem;">Informasi Bahan Baku</th>
                                    <th style="font-size: 0.72rem;">Kategori</th>
                                    <th style="font-size: 0.72rem;" class="text-center">Stok Gudang</th>
                                    <th style="font-size: 0.72rem;" class="text-end">Harga Beli Terakhir</th>
                                    <th style="font-size: 0.72rem; width: 110px;" class="text-center pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produks as $p)
                                <tr onclick="addToCart({{ $p->id }})" class="product-item-row" style="cursor: pointer;">
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="avatar-icon-souvenir">
                                                <i class="fas fa-layer-group"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size: 0.8125rem;">{{ $p->nama_produk }}</div>
                                                <span class="badge-kode">{{ $p->kode_produk }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-kategori-custom">
                                            <i class="fas fa-tag text-primary"></i>
                                            <span>{{ $p->kategori->nama_kategori ?? '-' }}</span>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-satuan font-monospace">
                                            {{ number_format($p->stok) }} {{ $p->satuan->singkatan ?? 'pcs' }}
                                        </span>
                                    </td>
                                    <td class="text-end font-monospace fw-semibold text-dark" style="font-size: 0.8125rem;">
                                        Rp {{ number_format($p->harga_beli, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-3">
                                        <button type="button" class="btn-add-item">
                                            <i class="fas fa-plus me-1"></i> Tambah
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted small">
                                        Belum ada data bahan baku. Daftarkan bahan baku terlebih dahulu di menu <strong>Stok Bahan Baku</strong>.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- KERANJANG RINCIAN PEMBELIAN --}}
                    <div class="form-section-title mb-2">
                        <i class="fas fa-shopping-cart"></i>
                        2. Rincian Bahan yang Dipesan / Dibeli
                    </div>

                    <div class="table-responsive rounded-3 border bg-white mb-3">
                        <table class="table table-cart-pembelian align-middle mb-0" id="cartTable">
                            <thead>
                                <tr>
                                    <th class="ps-3">Informasi Bahan Baku</th>
                                    <th style="width: 160px;" class="text-center">Qty Dibeli</th>
                                    <th style="width: 190px;" class="text-end">Harga Satuan (Rp)</th>
                                    <th style="width: 190px;" class="text-end">Subtotal (Rp)</th>
                                    <th style="width: 70px;" class="text-center pe-3">Hapus</th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <tr id="cartEmptyRow">
                                    <td colspan="5" class="text-center py-4 text-muted small">
                                        <i class="fas fa-cart-arrow-down fa-2x mb-2 text-muted d-block opacity-50"></i>
                                        Klik tombol <strong>"Tambah"</strong> pada salah satu bahan baku di atas untuk menambahkan ke rincian pembelian.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Input Ongkir & Diskon --}}
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom" for="ongkirInput">
                                Ongkos Kirim Kargo (+)
                            </label>
                            <div class="input-group-modern">
                                <i class="fas fa-truck-fast input-icon"></i>
                                <input type="number"
                                       step="any"
                                       min="0"
                                       name="ongkir"
                                       id="ongkirInput"
                                       class="form-control form-control-modern"
                                       value="{{ old('ongkir', 0) }}"
                                       placeholder="0">
                            </div>
                            <div class="required-note">
                                <i class="fas fa-circle-info"></i>
                                <span>Biaya pengiriman kargo / ekspedisi (Rp)</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label-custom" for="diskonInput">
                                Diskon / Voucher Cashback (-)
                            </label>
                            <div class="input-group-modern">
                                <i class="fas fa-tags input-icon"></i>
                                <input type="number"
                                       step="any"
                                       min="0"
                                       name="total_diskon"
                                       id="diskonInput"
                                       class="form-control form-control-modern"
                                       value="{{ old('total_diskon', 0) }}"
                                       placeholder="0">
                            </div>
                            <div class="required-note">
                                <i class="fas fa-circle-info"></i>
                                <span>Potongan harga dari toko / platform (Rp)</span>
                            </div>
                        </div>
                    </div>

                    {{-- Preview Ringkasan Biaya Pengadaan (Gaya Box Hijau Persis Katalog Souvenir) --}}
                    <div id="preview_pembelian_box">
                        <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: #22c55e; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                                    <i class="fas fa-calculator"></i>
                                </div>
                                <div>
                                    <div style="font-size: 0.75rem; color: #166534; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Ringkasan Biaya Pengadaan Bahan Baku</div>
                                    <div style="font-size: 0.85rem; color: #15803d;">
                                        Subtotal Bahan: <strong id="displaySubtotal">Rp 0</strong>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 0.75rem; color: #15803d; font-weight: 600;">Grand Total Pembelian</div>
                                <div style="font-size: 1.25rem; font-weight: 700; color: #166534;" id="displayGrandTotal">Rp 0</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ SECTION 3: LAMPIRAN & CATATAN ============ --}}
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="bukti_transaksi">
                            Upload Bukti Invoice / Checkout Marketplace
                        </label>
                        <input type="file"
                               name="bukti_transaksi"
                               id="bukti_transaksi"
                               class="form-control form-control-modern @error('bukti_transaksi') is-invalid @enderror"
                               accept=".jpg,.jpeg,.png,.webp,.pdf">
                        <div class="invalid-feedback" id="error_bukti_transaksi" @if(!$errors->has('bukti_transaksi')) style="display: none !important;" @endif>
                            @error('bukti_transaksi'){{ $message }}@enderror
                        </div>
                        <div class="required-note">
                            <i class="fas fa-circle-info"></i>
                            <span>Opsional, screenshot pembayaran Shopee/Tokopedia atau invoice PDF kargo (Maks. 5MB)</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="catatan">
                            Catatan Kulakan / Pengadaan
                        </label>
                        <textarea name="catatan"
                                  id="catatan"
                                  rows="2"
                                  class="form-control form-control-modern @error('catatan') is-invalid @enderror"
                                  placeholder="Catatan opsional mengenai pembelian bahan baku ini..."
                                  maxlength="500">{{ old('catatan') }}</textarea>
                        <div class="required-note">
                            <i class="fas fa-circle-info"></i>
                            <span>Opsional, maksimal 500 karakter</span>
                        </div>
                    </div>
                </div>

                <hr class="form-divider">

                {{-- ============ ACTIONS PERSIS KATALOG SOUVENIR CREATE ============ --}}
                <div class="form-actions">
                    <a href="{{ route('transaksi-pembelian.index') }}" class="btn btn-danger-custom">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <button type="submit" class="btn btn-primary-custom" id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span>Simpan Pembelian</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';

    const rawProduks = @json($produksJson ?? []);
    const produksMap = {};
    rawProduks.forEach(p => produksMap[p.id] = p);

    let cart = {};

    const searchInput = document.getElementById('searchProduct');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.product-item-row');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    window.addToCart = function(id) {
        const p = produksMap[id];
        if (!p) return;

        if (cart[id]) {
            cart[id].qty += 1;
        } else {
            cart[id] = {
                id: p.id,
                nama: p.nama,
                kode: p.kode,
                satuan: p.satuan,
                harga_beli: p.harga_beli,
                qty: 1
            };
        }
        renderCart();
    };

    window.updateQty = function(id, val) {
        const qty = parseInt(val) || 1;
        if (cart[id]) {
            cart[id].qty = Math.max(1, qty);
            renderCart();
        }
    };

    window.updateHarga = function(id, val) {
        const harga = parseFloat(val) || 0;
        if (cart[id]) {
            cart[id].harga_beli = Math.max(0, harga);
            renderCart();
        }
    };

    window.removeFromCart = function(id) {
        delete cart[id];
        renderCart();
    };

    function renderCart() {
        const tbody = document.getElementById('cartTableBody');
        const keys = Object.keys(cart);

        if (keys.length === 0) {
            tbody.innerHTML = `
                <tr id="cartEmptyRow">
                    <td colspan="5" class="text-center py-4 text-muted small">
                        <i class="fas fa-cart-arrow-down fa-2x mb-2 text-muted d-block opacity-50"></i>
                        Klik tombol <strong>"Tambah"</strong> pada salah satu bahan baku di atas untuk menambahkan ke rincian pembelian.
                    </td>
                </tr>
            `;
            updateTotals(0);
            return;
        }

        let html = '';
        let subtotalAll = 0;
        let index = 0;

        keys.forEach(id => {
            const item = cart[id];
            const subtotal = item.qty * item.harga_beli;
            subtotalAll += subtotal;

            html += `
                <tr>
                    <td class="ps-3">
                        <input type="hidden" name="items[${index}][produk_id]" value="${item.id}">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="avatar-icon-souvenir">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">${item.nama}</div>
                                <span class="badge-kode">${item.kode}</span>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="input-group input-group-sm justify-content-center">
                            <input type="number" min="1" class="form-control text-center fw-bold" style="max-width: 85px;"
                                   name="items[${index}][qty]"
                                   value="${item.qty}"
                                   onchange="updateQty(${item.id}, this.value)">
                            <span class="input-group-text">${item.satuan}</span>
                        </div>
                    </td>
                    <td class="text-end">
                        <input type="number" min="0" step="any" class="form-control form-control-sm text-end font-monospace"
                               name="items[${index}][harga_beli]"
                               value="${item.harga_beli}"
                               onchange="updateHarga(${item.id}, this.value)">
                    </td>
                    <td class="text-end fw-bold text-dark font-monospace">
                        Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}
                    </td>
                    <td class="text-center pe-3">
                        <button type="button" class="btn-cart-delete" onclick="removeFromCart(${item.id})" title="Hapus dari keranjang">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
            index++;
        });

        tbody.innerHTML = html;
        updateTotals(subtotalAll);
    }

    function updateTotals(subtotalAll) {
        const ongkir = parseFloat(document.getElementById('ongkirInput').value) || 0;
        const diskon = parseFloat(document.getElementById('diskonInput').value) || 0;
        const grandTotal = Math.max(0, (subtotalAll + ongkir) - diskon);

        document.getElementById('displaySubtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotalAll);
        document.getElementById('displayGrandTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);
    }

    document.getElementById('ongkirInput').addEventListener('input', () => {
        let subtotalAll = 0;
        Object.keys(cart).forEach(id => {
            subtotalAll += cart[id].qty * cart[id].harga_beli;
        });
        updateTotals(subtotalAll);
    });

    document.getElementById('diskonInput').addEventListener('input', () => {
        let subtotalAll = 0;
        Object.keys(cart).forEach(id => {
            subtotalAll += cart[id].qty * cart[id].harga_beli;
        });
        updateTotals(subtotalAll);
    });
})();
</script>
@endpush
