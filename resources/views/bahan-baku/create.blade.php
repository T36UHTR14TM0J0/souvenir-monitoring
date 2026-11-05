@extends('layouts.app')

@section('title', 'Tambah Bahan Baku')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-layer-group"
        title="Tambah Bahan Baku"
        subtitle="Tambahkan master data material mentah, blanko souvenir, atau kemasan ke dalam inventori gudang workshop."
    />

    {{-- FORM CARD --}}
    <div class="card card-modern">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('bahan-baku.store') }}" method="POST" id="bahanBakuForm" novalidate>
                @csrf

                {{-- ============ SECTION: INFORMASI BAHAN BAKU ============ --}}
                <div class="form-section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Bahan Baku
                </div>

                <div class="row g-3">
                    {{-- Kode Bahan Baku --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label-custom" for="kode_produk">
                            Kode Bahan Baku <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-barcode input-icon"></i>
                            <input type="text"
                                   name="kode_produk"
                                   id="kode_produk"
                                   class="form-control form-control-modern @error('kode_produk') is-invalid @enderror"
                                   value="{{ old('kode_produk', $kodeBahan) }}"
                                   placeholder="BB00001"
                                   maxlength="50"
                                   style="background-color: #f9fafb;">
                        </div>
                        <div class="invalid-feedback" id="error_kode_produk" @if(!$errors->has('kode_produk')) style="display: none !important;" @endif>
                            @error('kode_produk'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_kode_produk" @if($errors->has('kode_produk')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Kode unik otomatis</span>
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
                                <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
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
                                <option value="{{ $sat->id }}" {{ old('satuan_id', $sat->singkatan === 'pcs' ? $sat->id : '') == $sat->id ? 'selected' : '' }}>
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

                    {{-- Nama Bahan Baku --}}
                    <div class="col-12">
                        <label class="form-label-custom" for="nama_produk">
                            Nama Bahan Baku &amp; Material <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-cube input-icon"></i>
                            <input type="text"
                                   name="nama_produk"
                                   id="nama_produk"
                                   class="form-control form-control-modern @error('nama_produk') is-invalid @enderror"
                                   value="{{ old('nama_produk') }}"
                                   placeholder="Contoh: Blanko Mug Keramik Putih Sublimasi SNI / Lembaran Akrilik 2mm A4"
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

                {{-- ============ SECTION: MANAJEMEN STOK & ALERT ============ --}}
                <div class="form-section mt-4">
                    <div class="form-section-title">
                        <i class="fas fa-warehouse"></i>
                        Manajemen Stok Gudang
                    </div>

                    <div class="row g-3">
                        {{-- Stok Minimal --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom" for="stok_minimal">
                                Stok Minimal Alert <span class="required">*</span>
                            </label>
                            <div class="input-group-modern">
                                <i class="fas fa-exclamation-triangle input-icon"></i>
                                <input type="number"
                                       name="stok_minimal"
                                       id="stok_minimal"
                                       class="form-control form-control-modern @error('stok_minimal') is-invalid @enderror"
                                       value="{{ old('stok_minimal', 20) }}"
                                       min="0">
                            </div>
                            <div class="invalid-feedback" id="error_stok_minimal" @if(!$errors->has('stok_minimal')) style="display: none !important;" @endif>
                                @error('stok_minimal'){{ $message }}@enderror
                            </div>
                            <div class="required-note" id="note_stok_minimal" @if($errors->has('stok_minimal')) style="display: none !important;" @endif>
                                <i class="fas fa-circle-info"></i>
                                <span>Batas peringatan restock bahan baku menipis</span>
                            </div>
                        </div>

                        {{-- Info Kebijakan Stok & Harga --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom">
                                Kebijakan Stok &amp; Harga Kulakan <span class="badge bg-secondary-subtle text-secondary ms-1"><i class="fas fa-lock me-1"></i>Otomatis</span>
                            </label>
                            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #bbf7d0; border-radius: 0.5rem; padding: 0.65rem 0.9rem; display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #22c55e; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0;">
                                    <i class="fas fa-truck-ramp-box"></i>
                                </div>
                                <div style="font-size: 0.78rem; color: #166534; line-height: 1.4;">
                                    Stok fisik &amp; harga beli kulakan otomatis tercatat melalui menu <strong>Pembelian Bahan Baku</strong> saat belanja supplier tiba di workshop.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ SECTION: DESKRIPSI ============ --}}
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label-custom" for="deskripsi">
                            Deskripsi Teknis &amp; Spesifikasi
                        </label>
                        <textarea name="deskripsi"
                                  id="deskripsi"
                                  rows="3"
                                  class="form-control form-control-modern @error('deskripsi') is-invalid @enderror"
                                  placeholder="Contoh: Mug polos coating siap press sublimasi tahan panas. Ukuran standar 11 oz diameter 8cm (opsional)"
                                  maxlength="500">{{ old('deskripsi') }}</textarea>
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
                    <a href="{{ route('bahan-baku.index') }}" class="btn btn-danger-custom">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <button type="submit" class="btn btn-primary-custom" id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span>Simpan Bahan Baku</span>
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

    const form      = document.getElementById('bahanBakuForm');
    const submitBtn = document.getElementById('submitBtn');
    if (!form) return;

    const fields = ['kode_produk', 'kategori_id', 'satuan_id', 'nama_produk', 'stok_minimal', 'deskripsi'];

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

    fields.forEach(function (fieldId) {
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

    form.addEventListener('submit', function (e) {
        let isValid = true;
        let firstInvalid = null;

        fields.forEach(clearFieldError);

        const kodeEl     = document.getElementById('kode_produk');
        const kategoriEl = document.getElementById('kategori_id');
        const satuanEl   = document.getElementById('satuan_id');
        const namaEl     = document.getElementById('nama_produk');
        const stokMinEl  = document.getElementById('stok_minimal');

        const kodeVal = kodeEl ? kodeEl.value.trim() : '';
        if (!kodeVal) {
            setFieldError('kode_produk', 'Kode bahan baku wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = kodeEl;
        } else if (!/^[A-Z0-9-]+$/i.test(kodeVal)) {
            setFieldError('kode_produk', 'Kode bahan baku hanya boleh berisi huruf kapital, angka, dan tanda hubung (-).');
            isValid = false;
            if (!firstInvalid) firstInvalid = kodeEl;
        }

        if (!kategoriEl || !kategoriEl.value) {
            setFieldError('kategori_id', 'Kategori bahan baku wajib dipilih.');
            isValid = false;
            if (!firstInvalid) firstInvalid = kategoriEl;
        }

        if (!satuanEl || !satuanEl.value) {
            setFieldError('satuan_id', 'Satuan takaran wajib dipilih.');
            isValid = false;
            if (!firstInvalid) firstInvalid = satuanEl;
        }

        const namaVal = namaEl ? namaEl.value.trim() : '';
        if (!namaVal) {
            setFieldError('nama_produk', 'Nama bahan baku wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = namaEl;
        } else if (namaVal.length > 150) {
            setFieldError('nama_produk', 'Nama bahan baku maksimal 150 karakter.');
            isValid = false;
            if (!firstInvalid) firstInvalid = namaEl;
        }

        const stokMinVal = stokMinEl ? stokMinEl.value.trim() : '';
        const stokMin    = Number(stokMinVal);
        if (stokMinVal === '' || isNaN(stokMin)) {
            setFieldError('stok_minimal', 'Batas stok minimum alert wajib diisi.');
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

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> <span>Menyimpan...</span>';
        }
    });

    const initialInvalid = document.querySelector('.is-invalid');
    if (initialInvalid) {
        initialInvalid.focus();
        initialInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
})();
</script>
@endpush
