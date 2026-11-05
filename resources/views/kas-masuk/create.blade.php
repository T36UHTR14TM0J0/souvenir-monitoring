@extends('layouts.app')

@section('title', 'Setor Modal / Kas Masuk')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-hand-holding-dollar"
        title="Setor Modal / Kas Masuk"
        subtitle="Catat penambahan uang kas toko dari suntikan modal debit pribadi, pencairan saldo, atau pendapatan lain."
    >
        <a href="{{ route('kas-masuk.index') }}" class="btn btn-secondary-custom">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </x-page-header>

    {{-- FORM CARD --}}
    <div class="card card-modern">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('kas-masuk.store') }}" method="POST" id="kasMasukForm" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- ============ SECTION 1: INFORMASI TRANSAKSI KAS MASUK ============ --}}
                <div class="form-section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Kas Masuk &amp; Modal
                </div>

                <div class="row g-3">
                    {{-- Kode Transaksi --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="kode_transaksi">
                            Kode Transaksi
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-barcode input-icon"></i>
                            <input type="text"
                                   id="kode_transaksi"
                                   class="form-control form-control-modern"
                                   value="{{ $kodeTransaksi }}"
                                   readonly
                                   style="background: #f8fafc; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-weight: 700; color: #059669;">
                        </div>
                        <div class="required-note">
                            <i class="fas fa-circle-info"></i>
                            <span>Kode transaksi dibuat otomatis oleh sistem</span>
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="tanggal">
                            Tanggal Transaksi <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-calendar-day input-icon"></i>
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
                            <span>Wajib diisi, tanggal uang kas masuk diterima</span>
                        </div>
                    </div>

                    {{-- Tipe Kas Masuk --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="tipe">
                            Tipe Kas Masuk <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-tags input-icon"></i>
                            <select name="tipe" id="tipe" class="form-select form-select-modern @error('tipe') is-invalid @enderror" required>
                                @foreach($tipes as $k => $v)
                                    <option value="{{ $k }}" {{ old('tipe') === $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="invalid-feedback" id="error_tipe" @if(!$errors->has('tipe')) style="display: none !important;" @endif>
                            @error('tipe'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_tipe" @if($errors->has('tipe')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Pilih kategori sumber pemasukan kas</span>
                        </div>
                    </div>

                    {{-- Sumber Dana --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="sumber">
                            Sumber Dana <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-building-columns input-icon"></i>
                            <select name="sumber" id="sumber" class="form-select form-select-modern @error('sumber') is-invalid @enderror" required>
                                @foreach($sumbers as $sk => $sv)
                                    <option value="{{ $sk }}" {{ old('sumber', 'Debit / Transfer') === $sk ? 'selected' : '' }}>{{ $sv }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="invalid-feedback" id="error_sumber" @if(!$errors->has('sumber')) style="display: none !important;" @endif>
                            @error('sumber'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_sumber" @if($errors->has('sumber')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Metode atau rekening asal dana masuk</span>
                        </div>
                    </div>

                    {{-- Jumlah Masuk --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="jumlah">
                            Jumlah Masuk (Rp) <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-money-bill-wave input-icon"></i>
                            <input type="number"
                                   name="jumlah"
                                   id="jumlah"
                                   class="form-control form-control-modern @error('jumlah') is-invalid @enderror"
                                   value="{{ old('jumlah') }}"
                                   min="1"
                                   step="500"
                                   placeholder="Contoh: 2000000"
                                   required>
                        </div>
                        <div class="invalid-feedback" id="error_jumlah" @if(!$errors->has('jumlah')) style="display: none !important;" @endif>
                            @error('jumlah'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_jumlah" @if($errors->has('jumlah')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib diisi, nominal minimal Rp 1</span>
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="keterangan">
                            Keterangan <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-pen input-icon"></i>
                            <input type="text"
                                   name="keterangan"
                                   id="keterangan"
                                   class="form-control form-control-modern @error('keterangan') is-invalid @enderror"
                                   value="{{ old('keterangan', 'Modal Harian / Top-up Kas Toko') }}"
                                   placeholder="Contoh: Modal kas harian, suntikan saldo debit..."
                                   maxlength="255"
                                   required>
                        </div>
                        <div class="invalid-feedback" id="error_keterangan" @if(!$errors->has('keterangan')) style="display: none !important;" @endif>
                            @error('keterangan'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_keterangan" @if($errors->has('keterangan')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib diisi, ringkasan tujuan setoran modal atau kas masuk</span>
                        </div>
                    </div>
                </div>

                {{-- ============ SECTION 2: LAMPIRAN & CATATAN ============ --}}
                <div class="form-section-title mt-4">
                    <i class="fas fa-paperclip"></i>
                    Lampiran Bukti &amp; Catatan
                </div>

                <div class="row g-3">
                    {{-- Catatan Tambahan --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="catatan">
                            Catatan Tambahan
                        </label>
                        <textarea name="catatan"
                                  id="catatan"
                                  rows="3"
                                  class="form-control form-control-modern @error('catatan') is-invalid @enderror"
                                  placeholder="Catatan opsional..."
                                  maxlength="500">{{ old('catatan') }}</textarea>
                        @error('catatan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        <div class="required-note">
                            <i class="fas fa-circle-info"></i>
                            <span>Opsional, maksimal 500 karakter</span>
                        </div>
                    </div>

                    {{-- Bukti Transaksi --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="bukti_transaksi">
                            Bukti Transfer / Slip Setor Modal (Opsional)
                        </label>
                        <input type="file"
                               name="bukti_transaksi"
                               id="bukti_transaksi"
                               accept="image/jpeg,image/png,image/webp,application/pdf"
                               class="form-control form-control-modern @error('bukti_transaksi') is-invalid @enderror">
                        <div class="required-note">
                            <i class="fas fa-circle-info"></i>
                            <span>Format: JPG, PNG, WEBP, atau PDF (Maks. 5MB)</span>
                        </div>
                        <div id="previewBuktiContainer" class="mt-2 p-2 bg-light rounded text-center d-none" style="border: 1px dashed #cbd5e1;">
                            <img id="previewBuktiImg" src="" alt="Preview Bukti" class="img-fluid rounded d-none" style="max-height: 140px; object-fit: contain;">
                            <div id="previewBuktiPdfName" class="text-dark small d-none" style="font-size: 0.85rem;">
                                <i class="fas fa-file-pdf text-danger me-1"></i> <span id="pdfFileName"></span>
                            </div>
                        </div>
                        @error('bukti_transaksi')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="form-divider">

                {{-- ============ ACTIONS ============ --}}
                <div class="form-actions">
                    <a href="{{ route('kas-masuk.index') }}" class="btn btn-danger-custom">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <button type="submit" class="btn btn-primary-custom" id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span>Simpan Kas Masuk</span>
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

    const form = document.getElementById('kasMasukForm');
    const submitBtn = document.getElementById('submitBtn');
    const fields = ['tanggal', 'jumlah', 'keterangan'];

    function setFieldError(fieldId, message) {
        const input = document.getElementById(fieldId);
        const errorEl = document.getElementById('error_' + fieldId);
        const noteEl = document.getElementById('note_' + fieldId);

        if (input) input.classList.add('is-invalid');
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.style.setProperty('display', 'block', 'important');
        }
        if (noteEl) {
            noteEl.style.setProperty('display', 'none', 'important');
        }
    }

    function clearFieldError(fieldId) {
        const input = document.getElementById(fieldId);
        const errorEl = document.getElementById('error_' + fieldId);
        const noteEl = document.getElementById('note_' + fieldId);

        if (input) input.classList.remove('is-invalid');
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
        el.addEventListener('input', function () {
            clearFieldError(fieldId);
        });
    });

    if (form) {
        form.addEventListener('submit', function (e) {
            let isValid = true;
            fields.forEach(clearFieldError);

            const jumlahEl = document.getElementById('jumlah');
            const jumlahVal = parseFloat(jumlahEl ? jumlahEl.value : 0) || 0;
            if (jumlahVal < 1) {
                setFieldError('jumlah', 'Jumlah kas masuk minimal Rp 1.');
                isValid = false;
            }

            const ketEl = document.getElementById('keterangan');
            const ketVal = ketEl ? ketEl.value.trim() : '';
            if (!ketVal) {
                setFieldError('keterangan', 'Keterangan kas masuk wajib diisi.');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> <span>Menyimpan...</span>';
            }
        });
    }

    const inputBukti = document.getElementById('bukti_transaksi');
    if (inputBukti) {
        inputBukti.addEventListener('change', function() {
            const file = this.files[0];
            const previewContainer = document.getElementById('previewBuktiContainer');
            const previewImg = document.getElementById('previewBuktiImg');
            const previewPdfName = document.getElementById('previewBuktiPdfName');
            const pdfFileName = document.getElementById('pdfFileName');

            if (!file) {
                previewContainer.classList.add('d-none');
                return;
            }

            previewContainer.classList.remove('d-none');
            if (file.type.includes('image')) {
                previewImg.src = URL.createObjectURL(file);
                previewImg.classList.remove('d-none');
                previewPdfName.classList.add('d-none');
            } else {
                previewImg.classList.add('d-none');
                previewPdfName.classList.remove('d-none');
                pdfFileName.textContent = file.name;
            }
        });
    }
})();
</script>
@endpush
