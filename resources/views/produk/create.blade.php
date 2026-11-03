@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-box-open"
        title="Tambah Produk"
        subtitle="Tambahkan produk souvenir baru ke dalam sistem."
    />

    {{-- FORM CARD --}}
    <div class="card card-modern">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('produk.store') }}" method="POST" id="produkForm" novalidate>
                @csrf

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
                                   value="{{ old('kode_produk', $kodeProduk) }}"
                                   placeholder="PRD00001"
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
                                   value="{{ old('nama_produk') }}"
                                   placeholder="Contoh: Kaos Souvenir Custom"
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
                                       value="{{ old('harga_beli') !== null ? number_format((float) preg_replace('/\D/', '', (string) old('harga_beli')), 0, ',', '.') : '0' }}"
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
                                       value="{{ old('harga_jual') !== null ? number_format((float) preg_replace('/\D/', '', (string) old('harga_jual')), 0, ',', '.') : '0' }}"
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
                    </div>
                </div>

                {{-- ============ SECTION: STOK ============ --}}
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-warehouse"></i>
                        Manajemen Stok
                    </div>

                    <div class="row g-3">
                        {{-- Stok --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom" for="stok">
                                Stok Awal <span class="required">*</span>
                            </label>
                            <div class="input-group-modern">
                                <i class="fas fa-cubes input-icon"></i>
                                <input type="number"
                                       name="stok"
                                       id="stok"
                                       class="form-control form-control-modern @error('stok') is-invalid @enderror"
                                       value="{{ old('stok', 0) }}"
                                       min="0">
                            </div>
                            <div class="invalid-feedback" id="error_stok" @if(!$errors->has('stok')) style="display: none !important;" @endif>
                                @error('stok'){{ $message }}@enderror
                            </div>
                            <div class="required-note" id="note_stok" @if($errors->has('stok')) style="display: none !important;" @endif>
                                <i class="fas fa-circle-info"></i>
                                <span>Modal pengadaan stok awal (Stok &times; Harga Beli) otomatis dicatat ke Laporan Keuangan</span>
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
                                       value="{{ old('stok_minimal', 5) }}"
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
                    <a href="{{ route('produk.index') }}" class="btn btn-danger-custom">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <button type="submit" class="btn btn-primary-custom" id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span>Simpan Produk</span>
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

    const fields = ['kode_produk', 'kategori_id', 'satuan_id', 'nama_produk', 'harga_beli', 'harga_jual', 'stok', 'stok_minimal', 'deskripsi'];

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
        });
    });

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
        const stokEl     = document.getElementById('stok');
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

        // 6. Validasi Stok
        const stokVal = stokEl ? stokEl.value.trim() : '';
        const stok    = Number(stokVal);
        if (stokVal === '' || isNaN(stok)) {
            setFieldError('stok', 'Stok wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = stokEl;
        } else if (stok < 0) {
            setFieldError('stok', 'Stok tidak boleh negatif.');
            isValid = false;
            if (!firstInvalid) firstInvalid = stokEl;
        } else if (!Number.isInteger(stok)) {
            setFieldError('stok', 'Stok harus berupa angka bulat.');
            isValid = false;
            if (!firstInvalid) firstInvalid = stokEl;
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
