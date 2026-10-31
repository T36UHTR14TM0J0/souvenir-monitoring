@extends('layouts.app')

@section('title', 'Tambah Pembelian')

@push('styles')
<style>
    :root {
        --sidebar-bg-1: #26476f;
        --sidebar-bg-2: #1f3a5c;
        --sidebar-bg-3: #1a304d;
        --sidebar-cream: #fbfaf7;
        --danger-color: #dc2626;
        --danger-hover: #b91c1c;
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
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%);
        color: var(--sidebar-cream);
        border: 1px solid rgba(95, 143, 194, 0.3);
        border-radius: 0.625rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(38, 71, 111, 0.25);
    }

    .page-subtitle-custom {
        font-size: 0.875rem;
        color: var(--gray-500);
        margin-bottom: 0;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%);
        border: 1px solid #26476f;
        color: #fff;
        font-weight: 500;
        padding: 0.625rem 1.25rem;
        border-radius: 0.625rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        box-shadow: 0 2px 8px rgba(38, 71, 111, 0.25);
        cursor: pointer;
        width: 100%;
    }

    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #1f3a5c 0%, #1a304d 100%);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(38, 71, 111, 0.35);
    }

    .btn-primary-custom:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-danger-custom {
        background-color: var(--danger-color);
        border: 1px solid var(--danger-color);
        color: #fff;
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
        width: 100%;
    }

    .btn-danger-custom:hover {
        background-color: var(--danger-hover);
        border-color: var(--danger-hover);
        color: #fff;
        transform: translateY(-1px);
    }

    .form-label-custom {
        font-weight: 500;
        color: var(--gray-700);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        display: inline-block;
    }

    .form-control-modern {
        border: 1px solid var(--gray-200);
        border-radius: 0.5rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        color: var(--gray-800);
        transition: all 0.15s ease;
        background-color: #fff;
        width: 100%;
    }

    .form-control-modern:focus {
        border-color: #26476f;
        box-shadow: 0 0 0 3px rgba(38, 71, 111, 0.1);
        outline: none;
    }

    .form-section-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--gray-800);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-section-title i {
        color: #26476f;
        font-size: 0.875rem;
    }

    .items-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .items-table thead th {
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem 0.5rem;
        text-align: left;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .items-table thead th:first-child { border-top-left-radius: 0.5rem; }
    .items-table thead th:last-child  { border-top-right-radius: 0.5rem; }

    .items-table tbody td {
        padding: 0.625rem 0.5rem;
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
        font-size: 0.875rem;
    }

    .table-hover tbody tr:hover td {
        background-color: var(--gray-50);
    }

    .items-table .form-control-modern {
        padding: 0.35rem 0.5rem;
        font-size: 0.85rem;
    }

    .subtotal-display {
        font-weight: 600;
        color: #26476f;
        font-size: 0.9rem;
    }

    .btn-remove-row {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.375rem;
        border: 1px solid transparent;
        background: transparent;
        color: #ef4444;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-remove-row:hover {
        background-color: #fef2f2;
        border-color: #fecaca;
        color: #dc2626;
    }

    .btn-qty {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--gray-300);
        background: #fff;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        color: var(--gray-700);
        transition: all 0.2s;
    }

    .btn-qty:hover { background: var(--gray-100); }

    .summary-card {
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

    .summary-row:last-of-type { border-bottom: none; }
    .summary-row .label { color: rgba(255, 255, 255, 0.7); }
    .summary-row .value { font-weight: 600; }

    .summary-row.grand {
        padding-top: 1rem;
        margin-top: 0.5rem;
        border-top: 2px solid rgba(255, 255, 255, 0.3);
        border-bottom: none;
    }

    .summary-row.grand .label { font-size: 1rem; color: #fff; font-weight: 600; }
    .summary-row.grand .value { font-size: 1.5rem; font-weight: 700; color: #fde68a; letter-spacing: -0.02em; }

    .badge-stock {
        padding: 0.25em 0.5em;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.7rem;
    }
    .bg-success-custom { background-color: #10b981; color: white; }
    .bg-danger-custom { background-color: #ef4444; color: white; }
    .bg-info-custom { background-color: #3b82f6; color: white; }

    .product-list-container {
        max-height: 300px;
        overflow-y: auto;
    }
    .product-list-container::-webkit-scrollbar { width: 6px; }
    .product-list-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
    .product-list-container::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
    .product-list-container::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <h1 class="page-title-custom">
            <span class="page-title-icon">
                <i class="fas fa-truck-loading"></i>
            </span>
            Tambah Transaksi Pembelian
        </h1>
        <p class="page-subtitle-custom">Pilih produk dari daftar, lalu periksa keranjang dan grand total di bawah.</p>
    </div>

    <form action="{{ route('transaksi-pembelian.store') }}" method="POST" id="pembelianForm" novalidate>
        @csrf

        <!-- ROW 1: INFORMASI PEMBELIAN HEADER -->
        <div class="card card-modern bg-white mb-4">
            <div class="card-body p-3 p-md-4">
                <div class="row g-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label-custom">Kode Pembelian</label>
                        <input type="text" class="form-control form-control-modern"
                               value="{{ $kodePembelian }}" readonly
                               style="background-color: #f9fafb; font-family: monospace; font-weight: 600; color: #2563eb;">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label-custom" for="tanggal">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" id="tanggal"
                               class="form-control form-control-modern @error('tanggal') is-invalid @enderror"
                               value="{{ old('tanggal', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="supplier">Supplier / Toko</label>
                        <input type="text" name="supplier" id="supplier"
                               class="form-control form-control-modern @error('supplier') is-invalid @enderror"
                               value="{{ old('supplier') }}"
                               placeholder="Contoh: Toko Grosir Jaya, Supplier ABC..."
                               maxlength="150">
                        @error('supplier')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 2: DAFTAR PRODUK (FULL WIDTH) -->
        <div class="card card-modern bg-white mb-4">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-section-title mb-0">
                        <i class="fas fa-box"></i> Daftar Produk
                    </div>
                    <input type="text" id="searchProduct" class="form-control form-control-modern"
                           placeholder="Cari nama/kode produk..." style="width: 300px; padding: 0.35rem 0.75rem;">
                </div>

                @if($produks->isEmpty())
                    <div class="alert alert-warning" style="background: #fffbeb; border-color: #fde68a; color: #92400e; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.85rem;">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Belum ada produk. Silakan tambah produk terlebih dahulu di menu <b>Produk</b>.
                    </div>
                @else
                    <div class="product-list-container">
                        <table class="items-table table-hover w-100" id="productTable">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Produk</th>
                                    <th class="text-center">Stok Saat Ini</th>
                                    <th class="text-end">Harga Beli</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produks as $p)
                                <tr onclick="addToCart({{ $p->id }})" style="cursor: pointer;">
                                    <td><span style="font-size: 0.85rem; color: var(--gray-500);">{{ $p->kode_produk }}</span></td>
                                    <td style="font-weight: 500; color: var(--gray-800);">{{ $p->nama_produk }}</td>
                                    <td class="text-center">
                                        @if($p->stok > 0)
                                            <span class="badge-stock bg-info-custom">{{ $p->stok }} Pcs</span>
                                        @else
                                            <span class="badge-stock bg-danger-custom">Habis</span>
                                        @endif
                                    </td>
                                    <td class="text-end" style="color: #2563eb; font-weight: 600;">Rp {{ number_format($p->harga_beli, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- ROW 3: KERANJANG (KIRI) & SUMMARY (KANAN) -->
        <div class="row g-4">

            <!-- KOLOM KIRI: KERANJANG -->
            <div class="col-12 col-lg-8">
                <div class="card card-modern bg-white h-100">
                    <div class="card-body p-3 p-md-4">
                        <div class="form-section-title">
                            <i class="fas fa-shopping-basket"></i> Daftar Pembelian
                        </div>

                        <div style="overflow-x: auto;">
                            <table class="items-table w-100" id="cartTable">
                                <thead>
                                    <tr>
                                        <th style="width: 35%;">Produk</th>
                                        <th style="width: 20%;">Harga Beli</th>
                                        <th style="width: 20%; text-align: center;">Qty</th>
                                        <th style="width: 20%; text-align: right;">Subtotal</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    <tr id="emptyCartMsg">
                                        <td colspan="5" class="text-center text-muted py-5" style="font-size: 0.9rem;">
                                            <i class="fas fa-shopping-basket fa-2x mb-2 text-gray-300"></i><br>
                                            Belum ada produk dipilih. Klik produk di tabel atas untuk menambahkan.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: SUMMARY / GRAND TOTAL -->
            <div class="col-12 col-lg-4">
                <div class="summary-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="form-section-title text-white mb-3" style="border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 0.5rem;">
                            <i class="fas fa-calculator text-white"></i> Ringkasan Pembelian
                        </div>
                        <div class="summary-row">
                            <span class="label">Total Item</span>
                            <span class="value" id="summaryTotalItem">0</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Total Harga</span>
                            <span class="value" id="summaryTotalHarga">Rp 0</span>
                        </div>
                        <div class="summary-row" style="padding-top: 0.75rem;">
                            <span class="label">Diskon (Rp)</span>
                            <div style="width: 50%;">
                                <input type="number" name="total_diskon" id="total_diskon"
                                       class="form-control" value="{{ old('total_diskon', 0) }}" min="0" step="500"
                                       style="background: rgba(255,255,255,0.95); border: none; padding: 0.375rem 0.625rem; font-size: 0.85rem; text-align: right; font-weight: 600; color: #26476f;">
                            </div>
                        </div>
                        <div class="summary-row grand">
                            <span class="label">Grand Total</span>
                            <span class="value" id="summaryGrandTotal">Rp 0</span>
                        </div>
                        <div class="mt-4 mb-4">
                            <label class="form-label-custom text-white" style="font-size: 0.8rem;">Catatan Tambahan</label>
                            <textarea name="catatan" rows="2" class="form-control form-control-modern"
                                      placeholder="Opsional..." maxlength="500"
                                      style="background: rgba(255,255,255,0.95); border: none; font-size: 0.85rem;">{{ old('catatan') }}</textarea>
                        </div>
                    </div>

                    <div class="row g-2 mt-auto">
                        <div class="col-6">
                            <a href="{{ route('transaksi-pembelian.index') }}" class="btn-danger-custom">
                                Batal
                            </a>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn-primary-custom" id="submitBtn" {{ $produks->isEmpty() ? 'disabled' : '' }}>
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
    'use strict';

    const SWAL_PRIMARY = '#26476f';
    const SWAL_CANCEL  = '#a8a29e';
    const PRODUKS = @json($produksJson);

    let rowCounter = 0;

    // FITUR PENCARIAN
    document.getElementById('searchProduct').addEventListener('keyup', function() {
        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll('#productTable tbody tr');

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? '' : 'none';
        });
    });

    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka || 0);
    }

    // TAMBAH KE KERANJANG
    window.addToCart = function(produkId) {
        const p = PRODUKS.find(x => x.id == produkId);
        if(!p) return;

        document.getElementById('emptyCartMsg').style.display = 'none';
        const rowId = `cart_row_${p.id}`;
        const existingRow = document.getElementById(rowId);

        if (existingRow) {
            const qtyInput = existingRow.querySelector('.qty-input');
            let currentQty = parseInt(qtyInput.value) || 0;
            qtyInput.value = currentQty + 1;
            updateSubtotal(existingRow);
            return;
        }

        rowCounter++;
        const tr = document.createElement('tr');
        tr.id = rowId;
        tr.innerHTML = `
            <td>
                <div style="font-weight: 600; color: var(--gray-800);">${p.nama}</div>
                <div style="font-size: 0.75rem; color: var(--gray-500); margin-top: 2px;">Stok saat ini: ${p.stok}</div>
                <input type="hidden" name="items[${rowCounter}][produk_id]" value="${p.id}">
            </td>
            <td>
                <input type="number" name="items[${rowCounter}][harga_beli]" class="form-control form-control-modern harga-input"
                       min="0" step="500" value="${p.harga_beli}" required onchange="onHargaChange(this)" oninput="onHargaChange(this)">
            </td>
            <td>
                <div style="display: flex; align-items: center; justify-content: center; gap: 4px;">
                    <button type="button" class="btn-qty" onclick="changeQty('${rowId}', -1)">-</button>
                    <input type="number" name="items[${rowCounter}][qty]" class="form-control form-control-modern qty-input text-center"
                           style="width: 50px; padding: 0.2rem;" min="1" value="1" required
                           onchange="onQtyChange(this)" oninput="onQtyChange(this)">
                    <button type="button" class="btn-qty" onclick="changeQty('${rowId}', 1)">+</button>
                </div>
            </td>
            <td class="text-end">
                <span class="subtotal-display">${formatRupiah(p.harga_beli)}</span>
            </td>
            <td class="text-center">
                <button type="button" class="btn-remove-row" onclick="removeRow('${rowId}')" title="Hapus">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;

        document.getElementById('itemsBody').appendChild(tr);
        updateSummary();
    };

    window.changeQty = function(rowId, delta) {
        const row = document.getElementById(rowId);
        const input = row.querySelector('.qty-input');
        let val = parseInt(input.value) || 0;
        val += delta;

        if (val < 1) val = 1;

        input.value = val;
        updateSubtotal(row);
    };

    window.onQtyChange = function(input) {
        let val = parseInt(input.value) || 0;
        if (val < 1) input.value = 1;
        updateSubtotal(input.closest('tr'));
    };

    window.onHargaChange = function(input) {
        updateSubtotal(input.closest('tr'));
    };

    window.removeRow = function(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            row.remove();
            updateSummary();

            const tbody = document.getElementById('itemsBody');
            if (tbody.querySelectorAll('tr:not(#emptyCartMsg)').length === 0) {
                document.getElementById('emptyCartMsg').style.display = 'table-row';
            }
        }
    };

    function updateSubtotal(row) {
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
        const subtotal = qty * harga;

        row.querySelector('.subtotal-display').textContent = formatRupiah(subtotal);
        updateSummary();
    }

    function updateSummary() {
        let totalItem = 0;
        let totalHarga = 0;

        document.querySelectorAll('#itemsBody tr:not(#emptyCartMsg)').forEach(function(row) {
            const qty = parseFloat(row.querySelector('.qty-input')?.value) || 0;
            const harga = parseFloat(row.querySelector('.harga-input')?.value) || 0;
            totalItem += qty;
            totalHarga += (qty * harga);
        });

        const diskon = parseFloat(document.getElementById('total_diskon').value) || 0;
        const grandTotal = Math.max(0, totalHarga - diskon);

        document.getElementById('summaryTotalItem').textContent = totalItem;
        document.getElementById('summaryTotalHarga').textContent = formatRupiah(totalHarga);
        document.getElementById('summaryGrandTotal').textContent = formatRupiah(grandTotal);
    }

    document.getElementById('total_diskon').addEventListener('input', updateSummary);

    // VALIDASI FORM SUBMIT
    document.getElementById('pembelianForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const rows = document.querySelectorAll('#itemsBody tr:not(#emptyCartMsg)');
        if (rows.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Keranjang Kosong', text: 'Silakan pilih minimal 1 produk.', confirmButtonColor: SWAL_PRIMARY });
            return false;
        }

        const grandTotal = document.getElementById('summaryGrandTotal').textContent;

        Swal.fire({
            title: 'Simpan Pembelian?',
            html: `
                <p style="color: #4b5563; margin-bottom: 0.5rem;">Pastikan data sudah benar.</p>
                <div style="background: rgba(38, 71, 111, 0.06); padding: 0.75rem; border-radius: 8px;">
                    <div style="font-size: 0.8rem; color: #6b7280;">Grand Total</div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: #26476f;">${grandTotal}</div>
                </div>
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 0.5rem 0.75rem; margin-top: 0.75rem; text-align: left;">
                    <div style="font-size: 0.75rem; color: #065f46;">
                        <i class="fas fa-arrow-up"></i> Stok produk akan bertambah otomatis.
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-1"></i> Ya, Simpan!',
            cancelButtonText: 'Batal',
            confirmButtonColor: SWAL_PRIMARY,
            cancelButtonColor: SWAL_CANCEL,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = document.getElementById('submitBtn');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                HTMLFormElement.prototype.submit.call(document.getElementById('pembelianForm'));
            }
        });
    });

})();
</script>
@endpush