@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER (variant primary untuk edit) --}}
    <x-page-header
        icon="fas fa-edit"
        title="Edit Produk"
        subtitle="Perbarui informasi produk <strong>{{ $produk->nama_produk }}</strong>."
        iconVariant="primary"
    />

    {{-- FORM CARD --}}
    <div class="card card-modern">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('produk.update', $produk) }}" method="POST" id="produkForm" novalidate>
                @csrf
                @method('PUT')

                {{-- ============ SECTION: INFORMASI PRODUK ============ --}}
                <div class="form-section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Produk
                </div>

                <div class="row g-3">
                    {{-- Kode Produk --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label-custom" for="kode_produk">
                            Kode Produk <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-barcode input-icon"></i>
                            <input type="text"
                                   name="kode_produk"
                                   id="kode_produk"
                                   class="form-control form-control-modern @error('kode_produk') is-invalid @enderror"
                                   value="{{ old('kode_produk', $produk->kode_produk) }}"
                                   maxlength="50"
                                   style="background-color: #f9fafb;">
                        </div>
                        <div class="invalid-feedback" id="error_kode_produk" @if(!$errors->has('kode_produk')) style="display: none !important;" @endif>
                            @error('kode_produk'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_kode_produk" @if($errors->has('kode_produk')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Hanya huruf kapital, angka, dan tanda hubung</span>
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label-custom" for="kategori_id">
                            Kategori <span class="required">*</span>
                        </label>
                        <select name="kategori_id"
                                id="kategori_id"
                                class="form-select form-select-modern @error('kategori_id') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}"
                                    {{ old('kategori_id', $produk->kategori_id) == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="error_kategori_id" @if(!$errors->has('kategori_id')) style="display: none !important;" @endif>
                            @error('kategori_id'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_kategori_id" @if($errors->has('kategori_id')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib dipilih</span>
                        </div>
                    </div>

                    {{-- Satuan --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label-custom" for="satuan_id">
                            Satuan <span class="required">*</span>
                        </label>
                        <select name="satuan_id"
                                id="satuan_id"
                                class="form-select form-select-modern @error('satuan_id') is-invalid @enderror">
                            <option value="">-- Pilih Satuan --</option>
                            @foreach($satuans as $sat)
                                <option value="{{ $sat->id }}"
                                    {{ old('satuan_id', $produk->satuan_id) == $sat->id ? 'selected' : '' }}>
                                    {{ $sat->nama_satuan }} ({{ $sat->singkatan }})
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="error_satuan_id" @if(!$errors->has('satuan_id')) style="display: none !important;" @endif>
                            @error('satuan_id'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_satuan_id" @if($errors->has('satuan_id')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib dipilih</span>
                        </div>
                    </div>

                    {{-- Nama Produk --}}
                    <div class="col-12">
                        <label class="form-label-custom" for="nama_produk">
                            Nama Produk <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-cube input-icon"></i>
                            <input type="text"
                                   name="nama_produk"
                                   id="nama_produk"
                                   class="form-control form-control-modern @error('nama_produk') is-invalid @enderror"
                                   value="{{ old('nama_produk', $produk->nama_produk) }}"
                                   maxlength="150">
                        </div>
                        <div class="invalid-feedback" id="error_nama_produk" @if(!$errors->has('nama_produk')) style="display: none !important;" @endif>
                            @error('nama_produk'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_nama_produk" @if($errors->has('nama_produk')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib diisi, maksimal 150 karakter</span>
                        </div>
                    </div>
                </div>

                {{-- ============ SECTION: HARGA ============ --}}
                <div class="form-section mt-4">
                    <div class="form-section-title">
                        <i class="fas fa-money-bill-wave"></i>
                        Harga
                    </div>

                    <div class="row g-3">
                        {{-- Harga Beli --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom" for="harga_beli">
                                Harga Beli <span class="required">*</span>
                            </label>
                            <div class="input-rupiah">
                                <span class="prefix">Rp</span>
                                <input type="text"
                                       inputmode="numeric"
                                       name="harga_beli"
                                       id="harga_beli"
                                       class="form-control form-control-modern @error('harga_beli') is-invalid @enderror"
                                       value="{{ number_format((float) preg_replace('/\D/', '', (string) old('harga_beli', $produk->harga_beli)), 0, ',', '.') }}"
                                       placeholder="0">
                            </div>
                            <div class="invalid-feedback" id="error_harga_beli" @if(!$errors->has('harga_beli')) style="display: none !important;" @endif>
                                @error('harga_beli'){{ $message }}@enderror
                            </div>
                            <div class="required-note" id="note_harga_beli" @if($errors->has('harga_beli')) style="display: none !important;" @endif>
                                <i class="fas fa-circle-info"></i>
                                <span>Harga beli dari supplier (contoh: 2.000.000)</span>
                            </div>
                        </div>

                        {{-- Harga Jual --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom" for="harga_jual">
                                Harga Jual <span class="required">*</span>
                            </label>
                            <div class="input-rupiah">
                                <span class="prefix">Rp</span>
                                <input type="text"
                                       inputmode="numeric"
                                       name="harga_jual"
                                       id="harga_jual"
                                       class="form-control form-control-modern @error('harga_jual') is-invalid @enderror"
                                       value="{{ number_format((float) preg_replace('/\D/', '', (string) old('harga_jual', $produk->harga_jual)), 0, ',', '.') }}"
                                       placeholder="0">
                            </div>
                            <div class="invalid-feedback" id="error_harga_jual" @if(!$errors->has('harga_jual')) style="display: none !important;" @endif>
                                @error('harga_jual'){{ $message }}@enderror
                            </div>
                            <div class="required-note" id="note_harga_jual" @if($errors->has('harga_jual')) style="display: none !important;" @endif>
                                <i class="fas fa-circle-info"></i>
                                <span>Harus &ge; harga beli (contoh: 2.500.000)</span>
                            </div>
                        </div>

                        {{-- Tipe Diskon --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom" for="tipe_diskon">
                                Jenis Diskon
                            </label>
                            <select name="tipe_diskon" id="tipe_diskon" class="form-select form-select-modern @error('tipe_diskon') is-invalid @enderror">
                                <option value="none" {{ old('tipe_diskon', $produk->tipe_diskon ?? 'none') === 'none' ? 'selected' : '' }}>Tanpa Diskon</option>
                                <option value="persen" {{ old('tipe_diskon', $produk->tipe_diskon ?? 'none') === 'persen' ? 'selected' : '' }}>Persentase (%)</option>
                                <option value="nominal" {{ old('tipe_diskon', $produk->tipe_diskon ?? 'none') === 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                            </select>
                            <div class="invalid-feedback" id="error_tipe_diskon" @if(!$errors->has('tipe_diskon')) style="display: none !important;" @endif>
                                @error('tipe_diskon'){{ $message }}@enderror
                            </div>
                            <div class="required-note" id="note_tipe_diskon">
                                <i class="fas fa-circle-info"></i>
                                <span>Pilih jika produk sedang ada promo diskon</span>
                            </div>
                        </div>

                        {{-- Nilai Diskon --}}
                        <div class="col-12 col-md-6" id="diskon_wrapper" style="{{ old('tipe_diskon', $produk->tipe_diskon ?? 'none') === 'none' ? 'display: none;' : '' }}">
                            <label class="form-label-custom" for="diskon" id="diskon_label">
                                Nilai Diskon
                            </label>
                            <div style="display: flex; align-items: stretch;">
                                <span id="diskon_prefix" style="display: none; padding: 0.5rem 0.875rem; background: #f8fafc; border: 1px solid var(--gray-200); border-right: none; border-radius: 0.5rem 0 0 0.5rem; font-weight: 600; color: var(--gray-600); font-size: 0.875rem; align-items: center;">Rp</span>
                                <input type="text"
                                       name="diskon"
                                       id="diskon"
                                       class="form-control form-control-modern @error('diskon') is-invalid @enderror"
                                       value="{{ old('diskon', floor($produk->diskon) == $produk->diskon ? (int) $produk->diskon : $produk->diskon) }}"
                                       placeholder="0"
                                       style="border-radius: 0.5rem;">
                                <span id="diskon_suffix" style="display: none; padding: 0.5rem 0.875rem; background: #f8fafc; border: 1px solid var(--gray-200); border-left: none; border-radius: 0 0.5rem 0.5rem 0; font-weight: 600; color: var(--gray-600); font-size: 0.875rem; align-items: center;">%</span>
                            </div>
                            <div class="invalid-feedback" id="error_diskon" @if(!$errors->has('diskon')) style="display: none !important;" @endif>
                                @error('diskon'){{ $message }}@enderror
                            </div>
                            <div class="required-note" id="note_diskon">
                                <i class="fas fa-circle-info"></i>
                                <span id="diskon_hint">Masukkan nilai diskon</span>
                            </div>
                        </div>

                        {{-- Preview Harga Setelah Diskon --}}
                        <div class="col-12" id="preview_diskon_box" style="{{ old('tipe_diskon', $produk->tipe_diskon ?? 'none') === 'none' ? 'display: none;' : '' }}">
                            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: #22c55e; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.75rem; color: #166534; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Harga Jual Akhir (Setelah Promo)</div>
                                        <div style="font-size: 0.85rem; color: #15803d;" id="preview_detail_diskon">Hemat Rp 0</div>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 0.8rem; color: #15803d; text-decoration: line-through; opacity: 0.7;" id="preview_harga_asli">Rp 0</div>
                                    <div style="font-size: 1.25rem; font-weight: 700; color: #166534;" id="preview_harga_akhir">Rp 0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ SECTION: STOK ============ --}}
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-warehouse"></i>
                        Manajemen Stok
                    </div>

                    <div class="row g-3">
                        {{-- Stok (Readonly / Terkunci) --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom" for="stok">
                                Stok Saat Ini <span class="badge bg-secondary-subtle text-secondary ms-1"><i class="fas fa-lock me-1"></i>Terkunci</span>
                            </label>
                            <div class="input-group-modern">
                                <i class="fas fa-cubes input-icon text-muted"></i>
                                <input type="number"
                                       name="stok"
                                       id="stok"
                                       class="form-control form-control-modern"
                                       value="{{ old('stok', $produk->stok) }}"
                                       readonly
                                       style="background-color: #f1f5f9; cursor: not-allowed; color: #475569;"
                                       tabindex="-1">
                            </div>
                            <div class="required-note" id="note_stok">
                                <i class="fas fa-circle-info text-primary"></i>
                                <span>Stok berjalan otomatis dari transaksi. Untuk menambah stok, gunakan menu <strong>Transaksi Pembelian</strong>.</span>
                            </div>
                        </div>

                        {{-- Stok Minimal --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom" for="stok_minimal">
                                Stok Minimal <span class="required">*</span>
                            </label>
                            <div class="input-group-modern">
                                <i class="fas fa-exclamation-triangle input-icon"></i>
                                <input type="number"
                                       name="stok_minimal"
                                       id="stok_minimal"
                                       class="form-control form-control-modern @error('stok_minimal') is-invalid @enderror"
                                       value="{{ old('stok_minimal', $produk->stok_minimal) }}"
                                       min="0">
                            </div>
                            <div class="invalid-feedback" id="error_stok_minimal" @if(!$errors->has('stok_minimal')) style="display: none !important;" @endif>
                                @error('stok_minimal'){{ $message }}@enderror
                            </div>
                            <div class="required-note" id="note_stok_minimal" @if($errors->has('stok_minimal')) style="display: none !important;" @endif>
                                <i class="fas fa-circle-info"></i>
                                <span>Batas peringatan stok menipis</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ SECTION: DESKRIPSI ============ --}}
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label-custom" for="deskripsi">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi"
                                  id="deskripsi"
                                  rows="3"
                                  class="form-control form-control-modern @error('deskripsi') is-invalid @enderror"
                                  placeholder="Deskripsi singkat produk (opsional)"
                                  maxlength="500">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        <div class="invalid-feedback" id="error_deskripsi" @if(!$errors->has('deskripsi')) style="display: none !important;" @endif>
                            @error('deskripsi'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_deskripsi" @if($errors->has('deskripsi')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Opsional, maksimal 500 karakter</span>
                        </div>
                    </div>
                </div>

                <hr class="form-divider">

                {{-- ============ ACTIONS ============ --}}
                <div class="form-actions">
                    <a href="{{ route('produk.index') }}" class="btn btn-danger-custom">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <button type="submit" class="btn btn-primary-custom" id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const form      = document.getElementById('produkForm');
    const submitBtn = document.getElementById('submitBtn');
    if (!form) return;

    const fields = ['kode_produk', 'kategori_id', 'satuan_id', 'nama_produk', 'harga_beli', 'harga_jual', 'tipe_diskon', 'diskon', 'stok_minimal', 'deskripsi'];

    // Format angka dengan titik ribuan (contoh: 2.000.000)
    function formatRupiahInput(input) {
        const cursorPos = input.selectionStart;
        const val = input.value;
        const digitsBeforeCursor = (val.slice(0, cursorPos).match(/\d/g) || []).length;

        const raw = val.replace(/\D/g, '');
        if (!raw) {
            input.value = '';
            return;
        }

        const formatted = new Intl.NumberFormat('id-ID').format(raw);
        input.value = formatted;

        // Pertahankan posisi kursor pengguna
        let newCursorPos = formatted.length;
        let count = 0;
        for (let i = 0; i < formatted.length; i++) {
            if (/\d/.test(formatted[i])) {
                count++;
            }
            if (count === digitsBeforeCursor) {
                newCursorPos = i + 1;
                break;
            }
        }
        input.setSelectionRange(newCursorPos, newCursorPos);
    }

    function setFieldError(fieldId, message) {
        const input   = document.getElementById(fieldId);
        const errorEl = document.getElementById('error_' + fieldId);
        const noteEl  = document.getElementById('note_' + fieldId);

        if (input) {
            input.classList.add('is-invalid');
            const select2Container = input.nextElementSibling;
            if (select2Container && select2Container.classList.contains('select2-container')) {
                select2Container.classList.add('is-invalid');
            }
        }
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.style.setProperty('display', 'block', 'important');
        }
        if (noteEl) {
            noteEl.style.setProperty('display', 'none', 'important');
        }
    }

    function clearFieldError(fieldId) {
        const input   = document.getElementById(fieldId);
        const errorEl = document.getElementById('error_' + fieldId);
        const noteEl  = document.getElementById('note_' + fieldId);

        if (input) {
            input.classList.remove('is-invalid');
            const select2Container = input.nextElementSibling;
            if (select2Container && select2Container.classList.contains('select2-container')) {
                select2Container.classList.remove('is-invalid');
            }
        }
        if (errorEl) {
            errorEl.textContent = '';
            errorEl.style.setProperty('display', 'none', 'important');
        }
        if (noteEl) {
            noteEl.style.removeProperty('display');
        }
    }

    // Inisialisasi dan event listener untuk harga beli & jual dengan titik ribuan
    ['harga_beli', 'harga_jual'].forEach(function (id) {
        const el = document.getElementById(id);
        if (!el) return;

        if (el.value) {
            formatRupiahInput(el);
        }

        el.addEventListener('input', function () {
            formatRupiahInput(el);
            clearFieldError(id);

            const hbRaw = document.getElementById('harga_beli').value.replace(/\D/g, '');
            const hjRaw = document.getElementById('harga_jual').value.replace(/\D/g, '');
            if (hbRaw !== '' && hjRaw !== '') {
                const hb = parseInt(hbRaw, 10);
                const hj = parseInt(hjRaw, 10);
                if (hj >= hb) {
                    clearFieldError('harga_jual');
                }
            }
            updateDiscountUI();
        });
    });

    // ============================================
    // LOGIKA PERHITUNGAN DISKON PRODUK
    // ============================================
    const tipeDiskonEl      = document.getElementById('tipe_diskon');
    const diskonEl          = document.getElementById('diskon');
    const diskonWrapper     = document.getElementById('diskon_wrapper');
    const diskonPrefix      = document.getElementById('diskon_prefix');
    const diskonSuffix      = document.getElementById('diskon_suffix');
    const diskonHint        = document.getElementById('diskon_hint');
    const previewBox        = document.getElementById('preview_diskon_box');
    const previewHargaAsli  = document.getElementById('preview_harga_asli');
    const previewHargaAkhir = document.getElementById('preview_harga_akhir');
    const previewDetail     = document.getElementById('preview_detail_diskon');

    function updateDiscountUI() {
        if (!tipeDiskonEl) return;
        const tipe  = tipeDiskonEl.value;
        const hjRaw = document.getElementById('harga_jual').value.replace(/\D/g, '');
        const hj    = hjRaw ? parseInt(hjRaw, 10) : 0;

        if (tipe === 'none') {
            if (diskonWrapper) diskonWrapper.style.display = 'none';
            if (previewBox) previewBox.style.display = 'none';
            if (diskonEl) diskonEl.value = '0';
            return;
        }

        if (diskonWrapper) diskonWrapper.style.display = 'block';

        let diskonVal = 0;
        if (tipe === 'persen') {
            if (diskonPrefix) diskonPrefix.style.display = 'none';
            if (diskonSuffix) diskonSuffix.style.display = 'flex';
            if (diskonEl) {
                diskonEl.style.borderRadius = '0.5rem 0 0 0.5rem';
                diskonEl.placeholder = 'Contoh: 10';
            }
            if (diskonHint) diskonHint.textContent = 'Maksimal 100%';

            diskonVal = parseFloat(diskonEl.value.replace(',', '.')) || 0;
        } else if (tipe === 'nominal') {
            if (diskonPrefix) diskonPrefix.style.display = 'flex';
            if (diskonSuffix) diskonSuffix.style.display = 'none';
            if (diskonEl) {
                diskonEl.style.borderRadius = '0 0.5rem 0.5rem 0';
                diskonEl.placeholder = 'Contoh: 5.000';
                formatRupiahInput(diskonEl);
            }
            if (diskonHint) diskonHint.textContent = 'Maksimal sama dengan harga jual';

            diskonVal = parseInt(diskonEl.value.replace(/\D/g, ''), 10) || 0;
        }

        if (hj > 0 && diskonVal > 0) {
            let potongan = 0;
            if (tipe === 'persen') {
                potongan = Math.round(hj * (diskonVal / 100));
            } else {
                potongan = diskonVal;
            }
            const hargaAkhir = Math.max(0, hj - potongan);

            if (previewBox) previewBox.style.display = 'block';
            if (previewHargaAsli) previewHargaAsli.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hj);
            if (previewHargaAkhir) previewHargaAkhir.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaAkhir);
            if (previewDetail) {
                previewDetail.textContent = 'Hemat Rp ' + new Intl.NumberFormat('id-ID').format(potongan) + (tipe === 'persen' ? ' (' + diskonVal + '%)' : '');
            }
        } else {
            if (previewBox) previewBox.style.display = 'none';
        }
    }

    if (tipeDiskonEl) {
        tipeDiskonEl.addEventListener('change', function () {
            clearFieldError('tipe_diskon');
            clearFieldError('diskon');
            if (this.value === 'none') {
                if (diskonEl) diskonEl.value = '0';
            }
            updateDiscountUI();
        });
    }

    if (diskonEl) {
        diskonEl.addEventListener('input', function () {
            clearFieldError('diskon');
            if (tipeDiskonEl && tipeDiskonEl.value === 'nominal') {
                formatRupiahInput(this);
            }
            updateDiscountUI();
        });
    }

    // Panggil saat pertama load
    updateDiscountUI();

    // Real-time listener: bersihkan text merah saat pengguna mengetik/memilih field lain
    fields.forEach(function (fieldId) {
        if (fieldId === 'harga_beli' || fieldId === 'harga_jual') return;
        const el = document.getElementById(fieldId);
        if (!el) return;
        const evt = el.tagName === 'SELECT' ? 'change' : 'input';
        el.addEventListener(evt, function () {
            clearFieldError(fieldId);
        });
    });

    if (window.jQuery) {
        $('#kategori_id, #satuan_id').on('change', function () {
            clearFieldError(this.id);
        });
    }

    // Validasi Form saat Submit: Tampilkan teks merah di bawah field tanpa SweetAlert
    form.addEventListener('submit', function (e) {
        let isValid = true;
        let firstInvalid = null;

        // Bersihkan pesan error sebelumnya
        fields.forEach(clearFieldError);

        const kodeEl     = document.getElementById('kode_produk');
        const kategoriEl = document.getElementById('kategori_id');
        const satuanEl   = document.getElementById('satuan_id');
        const namaEl     = document.getElementById('nama_produk');
        const hbEl       = document.getElementById('harga_beli');
        const hjEl       = document.getElementById('harga_jual');
        const stokMinEl  = document.getElementById('stok_minimal');

        // 1. Validasi Kode Produk
        const kodeVal = kodeEl ? kodeEl.value.trim() : '';
        if (!kodeVal) {
            setFieldError('kode_produk', 'Kode produk wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = kodeEl;
        } else if (!/^[A-Z0-9-]+$/i.test(kodeVal)) {
            setFieldError('kode_produk', 'Kode produk hanya boleh berisi huruf kapital, angka, dan tanda hubung (-).');
            isValid = false;
            if (!firstInvalid) firstInvalid = kodeEl;
        }

        // 2. Validasi Kategori
        if (!kategoriEl || !kategoriEl.value) {
            setFieldError('kategori_id', 'Kategori wajib dipilih.');
            isValid = false;
            if (!firstInvalid) firstInvalid = kategoriEl;
        }

        // 3. Validasi Satuan
        if (!satuanEl || !satuanEl.value) {
            setFieldError('satuan_id', 'Satuan wajib dipilih.');
            isValid = false;
            if (!firstInvalid) firstInvalid = satuanEl;
        }

        // 4. Validasi Nama Produk
        const namaVal = namaEl ? namaEl.value.trim() : '';
        if (!namaVal) {
            setFieldError('nama_produk', 'Nama produk wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = namaEl;
        } else if (namaVal.length > 150) {
            setFieldError('nama_produk', 'Nama produk maksimal 150 karakter.');
            isValid = false;
            if (!firstInvalid) firstInvalid = namaEl;
        }

        // 4. Validasi Harga Beli
        const hbRaw = hbEl ? hbEl.value.replace(/\D/g, '') : '';
        const hb    = hbRaw === '' ? NaN : parseInt(hbRaw, 10);
        if (hbRaw === '' || isNaN(hb)) {
            setFieldError('harga_beli', 'Harga beli wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = hbEl;
        } else if (hb < 0) {
            setFieldError('harga_beli', 'Harga beli tidak boleh negatif.');
            isValid = false;
            if (!firstInvalid) firstInvalid = hbEl;
        }

        // 5. Validasi Harga Jual
        const hjRaw = hjEl ? hjEl.value.replace(/\D/g, '') : '';
        const hj    = hjRaw === '' ? NaN : parseInt(hjRaw, 10);
        if (hjRaw === '' || isNaN(hj)) {
            setFieldError('harga_jual', 'Harga jual wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = hjEl;
        } else if (hj < 0) {
            setFieldError('harga_jual', 'Harga jual tidak boleh negatif.');
            isValid = false;
            if (!firstInvalid) firstInvalid = hjEl;
        } else if (!isNaN(hb) && hj < hb) {
            setFieldError('harga_jual', 'Harga jual tidak boleh lebih kecil dari harga beli.');
            isValid = false;
            if (!firstInvalid) firstInvalid = hjEl;
        }

        // 6. Validasi Diskon
        const tipeVal = tipeDiskonEl ? tipeDiskonEl.value : 'none';
        if (tipeVal === 'persen') {
            const diskonVal = parseFloat(diskonEl.value.replace(',', '.')) || 0;
            if (diskonVal < 0 || diskonVal > 100) {
                setFieldError('diskon', 'Diskon persen harus antara 0% s.d. 100%.');
                isValid = false;
                if (!firstInvalid) firstInvalid = diskonEl;
            }
        } else if (tipeVal === 'nominal') {
            const diskonVal = parseInt(diskonEl.value.replace(/\D/g, ''), 10) || 0;
            if (diskonVal < 0) {
                setFieldError('diskon', 'Diskon nominal tidak boleh negatif.');
                isValid = false;
                if (!firstInvalid) firstInvalid = diskonEl;
            } else if (!isNaN(hj) && diskonVal > hj) {
                setFieldError('diskon', 'Diskon nominal tidak boleh melebihi harga jual.');
                isValid = false;
                if (!firstInvalid) firstInvalid = diskonEl;
            }
        }

        // 7. Validasi Stok Minimal
        const stokMinVal = stokMinEl ? stokMinEl.value.trim() : '';
        const stokMin    = Number(stokMinVal);
        if (stokMinVal === '' || isNaN(stokMin)) {
            setFieldError('stok_minimal', 'Stok minimal wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = stokMinEl;
        } else if (stokMin < 0) {
            setFieldError('stok_minimal', 'Stok minimal tidak boleh negatif.');
            isValid = false;
            if (!firstInvalid) firstInvalid = stokMinEl;
        } else if (!Number.isInteger(stokMin)) {
            setFieldError('stok_minimal', 'Stok minimal harus berupa angka bulat.');
            isValid = false;
            if (!firstInvalid) firstInvalid = stokMinEl;
        }

        // Jika tidak valid, tahan submit dan fokuskan field pertama yang error (tanpa SweetAlert)
        if (!isValid) {
            e.preventDefault();
            if (firstInvalid) {
                if (window.jQuery && $(firstInvalid).data('select2')) {
                    $(firstInvalid).select2('open');
                } else {
                    firstInvalid.focus();
                }
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
        }

        // Sebelum submit, hilangkan format titik agar server menerima angka murni
        if (hbEl) hbEl.value = hbRaw;
        if (hjEl) hjEl.value = hjRaw;
        if (tipeVal === 'nominal' && diskonEl) {
            diskonEl.value = diskonEl.value.replace(/\D/g, '') || '0';
        } else if (tipeVal === 'none' && diskonEl) {
            diskonEl.value = '0';
        }

        // Data valid: submit langsung
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> <span>Menyimpan...</span>';
        }
    });

    // Fokuskan field pertama yang error jika ada error validasi dari server
    const initialInvalid = document.querySelector('.is-invalid');
    if (initialInvalid) {
        initialInvalid.focus();
        initialInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
})();
</script>
@endpush
