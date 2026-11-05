@extends('layouts.app')

@section('title', 'Catat Pengambilan Bahan Baku')

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
    .table-cart-pemakaian thead {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
    }
    .table-cart-pemakaian thead th {
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
    .table-cart-pemakaian tbody td {
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
    .btn-add-item:hover:not(:disabled) {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
    }
    .btn-add-item:disabled {
        opacity: 0.5;
        cursor: not-allowed;
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
        icon="fas fa-dolly"
        title="Catat Pengambilan Bahan Baku"
        subtitle="Form serah terima material mentah dari gudang workshop kepada operator produksi."
    />

    {{-- FORM CARD PERSIS KATALOG SOUVENIR CREATE --}}
    <div class="card card-modern">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('pemakaian-bahan.store') }}" method="POST" id="pemakaianForm" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- ============ SECTION 1: DATA SERAH TERIMA & OPERATOR ============ --}}
                <div class="form-section-title">
                    <i class="fas fa-id-card"></i>
                    Informasi Serah Terima &amp; Operator Produksi
                </div>

                <div class="row g-3">
                    {{-- Kode Pengambilan --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label-custom" for="kode_pemakaian_display">
                            Kode Pengambilan <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-barcode input-icon"></i>
                            <input type="text"
                                   id="kode_pemakaian_display"
                                   class="form-control form-control-modern"
                                   value="{{ $kodePemakaian }}"
                                   readonly
                                   style="background-color: #f9fafb; font-family: monospace; font-weight: 700; color: #26476f;">
                        </div>
                        <div class="required-note">
                            <i class="fas fa-circle-info"></i>
                            <span>Kode unik otomatis</span>
                        </div>
                    </div>

                    {{-- Tanggal Pengambilan --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label-custom" for="tanggal">
                            Tanggal Pengambilan <span class="required">*</span>
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
                            <span>Tanggal material diambil dari gudang</span>
                        </div>
                    </div>

                    {{-- Nama Operator Pengambil --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label-custom" for="nama_operator">
                            Nama Operator Pengambil <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-user-gear input-icon"></i>
                            <input type="text"
                                   name="nama_operator"
                                   id="nama_operator"
                                   class="form-control form-control-modern @error('nama_operator') is-invalid @enderror"
                                   value="{{ old('nama_operator') }}"
                                   placeholder="Contoh: Budi (Operator Press)"
                                   maxlength="150"
                                   required>
                        </div>
                        <div class="invalid-feedback" id="error_nama_operator" @if(!$errors->has('nama_operator')) style="display: none !important;" @endif>
                            @error('nama_operator'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_nama_operator" @if($errors->has('nama_operator')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib diisi, nama petugas produksi</span>
                        </div>
                    </div>

                    {{-- Keperluan / Pesanan --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label-custom" for="keperluan">
                            Keperluan / Pesanan <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-clipboard-list input-icon"></i>
                            <input type="text"
                                   name="keperluan"
                                   id="keperluan"
                                   class="form-control form-control-modern @error('keperluan') is-invalid @enderror"
                                   value="{{ old('keperluan') }}"
                                   placeholder="Contoh: Batch 100 Mug Shopee"
                                   maxlength="255"
                                   required>
                        </div>
                        <div class="invalid-feedback" id="error_keperluan" @if(!$errors->has('keperluan')) style="display: none !important;" @endif>
                            @error('keperluan'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_keperluan" @if($errors->has('keperluan')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib diisi, target peruntukan bahan</span>
                        </div>
                    </div>
                </div>

                {{-- ============ SECTION 2: PILIH & RINCIAN BAHAN BAKU ============ --}}
                <div class="form-section mt-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                        <div class="form-section-title mb-0">
                            <i class="fas fa-boxes-stacked"></i>
                            1. Pilih Material dari Rak Gudang
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
                                    <th style="font-size: 0.72rem;" class="text-center">Sisa Stok Gudang</th>
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
                                        @if($p->stok <= 0)
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle font-monospace">
                                                Habis (0 {{ $p->satuan->singkatan ?? 'unit' }})
                                            </span>
                                        @else
                                            <span class="badge-satuan font-monospace">
                                                {{ number_format($p->stok) }} {{ $p->satuan->singkatan ?? 'unit' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-3">
                                        <button type="button" class="btn-add-item" {{ $p->stok <= 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-plus me-1"></i> Ambil
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">
                                        Belum ada data bahan baku di gudang. Daftarkan material terlebih dahulu di menu <strong>Stok Bahan Baku</strong>.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- KERANJANG PENGAMBILAN BAHAN --}}
                    <div class="form-section-title mb-2">
                        <i class="fas fa-clipboard-check"></i>
                        2. Rincian Material yang Dikeluarkan
                    </div>

                    <div class="table-responsive rounded-3 border bg-white">
                        <table class="table table-cart-pemakaian align-middle mb-0" id="cartTable">
                            <thead>
                                <tr>
                                    <th class="ps-3">Informasi Bahan Baku</th>
                                    <th style="width: 150px;" class="text-center">Stok Tersedia</th>
                                    <th style="width: 170px;" class="text-center">Jumlah Diambil</th>
                                    <th>Keterangan / Catatan Spesifik</th>
                                    <th style="width: 70px;" class="text-center pe-3">Hapus</th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <tr id="cartEmptyRow">
                                    <td colspan="5" class="text-center py-4 text-muted small">
                                        <i class="fas fa-dolly fa-2x mb-2 text-muted d-block opacity-50"></i>
                                        Klik tombol <strong>"Ambil"</strong> pada salah satu bahan baku di atas untuk mencatat pengeluaran material.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Preview Ringkasan Pengambilan Material (Gaya Box Hijau Persis Katalog Souvenir) --}}
                    <div class="mt-3" id="preview_pemakaian_box">
                        <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: #22c55e; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                                    <i class="fas fa-dolly"></i>
                                </div>
                                <div>
                                    <div style="font-size: 0.75rem; color: #166534; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Ringkasan Pengambilan Material Gudang</div>
                                    <div style="font-size: 0.85rem; color: #15803d;">
                                        <span id="displayItemCount">0 Jenis Bahan</span> &bull;
                                        Otomatis memotong kuantitas stok gudang workshop
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 0.75rem; color: #15803d; font-weight: 600;">Total Unit yang Diambil</div>
                                <div style="font-size: 1.25rem; font-weight: 700; color: #166534;" id="displayTotalQty">0 Unit</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ SECTION 3: LAMPIRAN & CATATAN ============ --}}
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="bukti_pengambilan">
                            Foto Bon / Tanda Terima Serah Terima
                        </label>
                        <input type="file"
                               name="bukti_pengambilan"
                               id="bukti_pengambilan"
                               class="form-control form-control-modern @error('bukti_pengambilan') is-invalid @enderror"
                               accept=".jpg,.jpeg,.png,.webp,.pdf">
                        <div class="invalid-feedback" id="error_bukti_pengambilan" @if(!$errors->has('bukti_pengambilan')) style="display: none !important;" @endif>
                            @error('bukti_pengambilan'){{ $message }}@enderror
                        </div>
                        <div class="required-note">
                            <i class="fas fa-circle-info"></i>
                            <span>Opsional, unggah foto tanda tangan operator atau slip bon fisik (Maks. 5MB)</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="catatan">
                            Catatan / Keterangan Kondisi Material
                        </label>
                        <textarea name="catatan"
                                  id="catatan"
                                  rows="2"
                                  class="form-control form-control-modern @error('catatan') is-invalid @enderror"
                                  placeholder="Misal: Termasuk 2 pcs blanko mug untuk tes suhu mesin press..."
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
                    <a href="{{ route('pemakaian-bahan.index') }}" class="btn btn-danger-custom">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <button type="submit" class="btn btn-primary-custom" id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span>Simpan Pengambilan</span>
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

        if (p.stok <= 0) {
            alert(`Stok bahan "${p.nama}" di gudang habis.`);
            return;
        }

        if (cart[id]) {
            if (cart[id].qty + 1 > p.stok) {
                alert(`Jumlah pengambilan melebihi sisa stok di gudang (${p.stok} ${p.satuan}).`);
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
                qty: 1,
                keterangan: ''
            };
        }
        renderCart();
    };

    window.updateQty = function(id, val) {
        const qty = parseFloat(val) || 1;
        if (cart[id]) {
            if (qty > cart[id].stok_tersedia) {
                alert(`Pengambilan (${qty}) melebihi stok yang tersedia (${cart[id].stok_tersedia} ${cart[id].satuan}).`);
                cart[id].qty = cart[id].stok_tersedia;
            } else {
                cart[id].qty = Math.max(0.01, qty);
            }
            renderCart();
        }
    };

    window.updateKeterangan = function(id, val) {
        if (cart[id]) {
            cart[id].keterangan = val;
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
                        <i class="fas fa-dolly fa-2x mb-2 text-muted d-block opacity-50"></i>
                        Klik tombol <strong>"Ambil"</strong> pada salah satu bahan baku di atas untuk mencatat pengeluaran material.
                    </td>
                </tr>
            `;
            updateTotals(0, 0);
            return;
        }

        let html = '';
        let totalQty = 0;
        let index = 0;

        keys.forEach(id => {
            const item = cart[id];
            totalQty += item.qty;

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
                        <span class="badge-satuan font-monospace">
                            ${new Intl.NumberFormat('id-ID').format(item.stok_tersedia)} ${item.satuan}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="input-group input-group-sm justify-content-center">
                            <input type="number" min="0.01" max="${item.stok_tersedia}" step="any"
                                   class="form-control text-center fw-bold" style="max-width: 90px;"
                                   name="items[${index}][qty]"
                                   value="${item.qty}"
                                   onchange="updateQty(${item.id}, this.value)">
                            <span class="input-group-text">${item.satuan}</span>
                        </div>
                    </td>
                    <td>
                        <input type="text" name="items[${index}][keterangan]"
                               class="form-control form-control-sm"
                               placeholder="Keterangan opsional..."
                               value="${item.keterangan || ''}"
                               onchange="updateKeterangan(${item.id}, this.value)">
                    </td>
                    <td class="text-center pe-3">
                        <button type="button" class="btn-cart-delete" onclick="removeFromCart(${item.id})" title="Hapus dari daftar">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
            index++;
        });

        tbody.innerHTML = html;
        updateTotals(keys.length, totalQty);
    }

    function updateTotals(itemCount, totalQty) {
        document.getElementById('displayItemCount').textContent = itemCount + ' Jenis Bahan';
        document.getElementById('displayTotalQty').textContent = new Intl.NumberFormat('id-ID').format(totalQty) + ' Unit';
    }
})();
</script>
@endpush
