@extends('layouts.app')

@section('title', 'Edit Satuan')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-ruler-combined"
        title="Edit Satuan"
        subtitle="Perbarui data satuan ukuran atau takaran kemasan produk souvenir."
    />

    {{-- FORM CARD --}}
    <div class="card card-modern">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('satuan.update', $satuan) }}" method="POST" id="satuanForm" novalidate>
                @csrf
                @method('PUT')

                {{-- ============ SECTION: INFORMASI SATUAN ============ --}}
                <div class="form-section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Satuan
                </div>

                <div class="row g-3">
                    {{-- Nama Satuan --}}
                    <div class="col-12 col-md-7">
                        <label class="form-label-custom" for="nama_satuan">
                            Nama Satuan <span class="required">*</span>
                            @if(in_array($satuan->nama_satuan, ['Pieces', 'Pcs']))
                                <span class="badge-protected ms-1"><i class="fas fa-lock me-1"></i>Default Sistem</span>
                            @endif
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-ruler input-icon"></i>
                            <input type="text"
                                   name="nama_satuan"
                                   id="nama_satuan"
                                   class="form-control form-control-modern @error('nama_satuan') is-invalid @enderror"
                                   value="{{ old('nama_satuan', $satuan->nama_satuan) }}"
                                   placeholder="Contoh: Lusin, Box, Lembar, Pack"
                                   maxlength="50"
                                   @if(in_array($satuan->nama_satuan, ['Pieces', 'Pcs'])) readonly style="background-color: #f1f5f9; cursor: not-allowed;" @endif
                                   autofocus>
                        </div>
                        <div class="invalid-feedback" id="error_nama_satuan" @if(!$errors->has('nama_satuan')) style="display: none !important;" @endif>
                            @error('nama_satuan'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_nama_satuan" @if($errors->has('nama_satuan')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Nama lengkap satuan, maksimal 50 karakter</span>
                        </div>
                    </div>

                    {{-- Singkatan / Simbol --}}
                    <div class="col-12 col-md-5">
                        <label class="form-label-custom" for="singkatan">
                            Singkatan / Simbol <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-tag input-icon"></i>
                            <input type="text"
                                   name="singkatan"
                                   id="singkatan"
                                   class="form-control form-control-modern font-monospace @error('singkatan') is-invalid @enderror"
                                   value="{{ old('singkatan', $satuan->singkatan) }}"
                                   placeholder="Contoh: lsn, box, lbr, pck"
                                   maxlength="20">
                        </div>
                        <div class="invalid-feedback" id="error_singkatan" @if(!$errors->has('singkatan')) style="display: none !important;" @endif>
                            @error('singkatan'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_singkatan" @if($errors->has('singkatan')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Ditampilkan pada label stok & transaksi (maksimal 20 karakter)</span>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">
                        <label class="form-label-custom" for="deskripsi">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi"
                                  id="deskripsi"
                                  rows="3"
                                  class="form-control form-control-modern @error('deskripsi') is-invalid @enderror"
                                  placeholder="Deskripsi atau keterangan takaran isi satuan (opsional)"
                                  maxlength="255">{{ old('deskripsi', $satuan->deskripsi) }}</textarea>
                        <div class="invalid-feedback" id="error_deskripsi" @if(!$errors->has('deskripsi')) style="display: none !important;" @endif>
                            @error('deskripsi'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_deskripsi" @if($errors->has('deskripsi')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Opsional, maksimal 255 karakter</span>
                        </div>
                    </div>
                </div>

                <hr class="form-divider">

                {{-- ============ ACTIONS ============ --}}
                <div class="form-actions">
                    <a href="{{ route('satuan.index') }}" class="btn btn-danger-custom">
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

    const form      = document.getElementById('satuanForm');
    const submitBtn = document.getElementById('submitBtn');
    if (!form) return;

    const fields = ['nama_satuan', 'singkatan', 'deskripsi'];

    function setFieldError(fieldId, message) {
        const input   = document.getElementById(fieldId);
        const errorEl = document.getElementById('error_' + fieldId);
        const noteEl  = document.getElementById('note_' + fieldId);

        if (input) {
            input.classList.add('is-invalid');
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
        }
        if (errorEl) {
            errorEl.textContent = '';
            errorEl.style.setProperty('display', 'none', 'important');
        }
        if (noteEl) {
            noteEl.style.removeProperty('display');
        }
    }

    // Real-time listener: bersihkan teks merah saat pengguna mengetik
    fields.forEach(function (fieldId) {
        const el = document.getElementById(fieldId);
        if (!el) return;
        el.addEventListener('input', function () {
            clearFieldError(fieldId);
        });
    });

    // Validasi Form saat Submit: Tampilkan teks merah di bawah field tanpa SweetAlert
    form.addEventListener('submit', function (e) {
        let isValid = true;
        let firstInvalid = null;

        fields.forEach(clearFieldError);

        const namaEl = document.getElementById('nama_satuan');
        const singkatanEl = document.getElementById('singkatan');

        // 1. Validasi Nama Satuan
        const namaVal = namaEl ? namaEl.value.trim() : '';
        if (!namaVal) {
            setFieldError('nama_satuan', 'Nama satuan wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = namaEl;
        } else if (namaVal.length > 50) {
            setFieldError('nama_satuan', 'Nama satuan maksimal 50 karakter.');
            isValid = false;
            if (!firstInvalid) firstInvalid = namaEl;
        }

        // 2. Validasi Singkatan
        const singkatanVal = singkatanEl ? singkatanEl.value.trim() : '';
        if (!singkatanVal) {
            setFieldError('singkatan', 'Singkatan / simbol satuan wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = singkatanEl;
        } else if (singkatanVal.length > 20) {
            setFieldError('singkatan', 'Singkatan maksimal 20 karakter.');
            isValid = false;
            if (!firstInvalid) firstInvalid = singkatanEl;
        }

        if (!isValid) {
            e.preventDefault();
            if (firstInvalid) {
                firstInvalid.focus();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
        }

        // Form valid
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
