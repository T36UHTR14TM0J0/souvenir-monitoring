@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-tag"
        title="Edit Kategori"
        subtitle="Perbarui data kategori produk toko souvenir."
    />

    {{-- FORM CARD --}}
    <div class="card card-modern">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('kategori.update', $kategori) }}" method="POST" id="kategoriForm" novalidate>
                @csrf
                @method('PUT')

                {{-- ============ SECTION: INFORMASI KATEGORI ============ --}}
                <div class="form-section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Kategori
                </div>

                <div class="row g-3">
                    {{-- Nama Kategori --}}
                    <div class="col-12">
                        <label class="form-label-custom" for="nama_kategori">
                            Nama Kategori <span class="required">*</span>
                            @if(in_array($kategori->nama_kategori, ['Umum', 'Lain-lain']))
                                <span class="badge-protected ms-1"><i class="fas fa-lock me-1"></i>Default Sistem</span>
                            @endif
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-tag input-icon"></i>
                            <input type="text"
                                   name="nama_kategori"
                                   id="nama_kategori"
                                   class="form-control form-control-modern @error('nama_kategori') is-invalid @enderror"
                                   value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                                   placeholder="Contoh: Kaos & Pakaian"
                                   maxlength="100"
                                   @if(in_array($kategori->nama_kategori, ['Umum', 'Lain-lain'])) readonly style="background-color: #f1f5f9; cursor: not-allowed;" @endif
                                   autofocus>
                        </div>
                        <div class="invalid-feedback" id="error_nama_kategori" @if(!$errors->has('nama_kategori')) style="display: none !important;" @endif>
                            @error('nama_kategori'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_nama_kategori" @if($errors->has('nama_kategori')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib diisi, maksimal 100 karakter</span>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">
                        <label class="form-label-custom" for="deskripsi">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi"
                                  id="deskripsi"
                                  rows="4"
                                  class="form-control form-control-modern @error('deskripsi') is-invalid @enderror"
                                  placeholder="Deskripsi singkat kategori (opsional)"
                                  maxlength="500">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
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
                    <a href="{{ route('kategori.index') }}" class="btn btn-danger-custom">
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

    const form      = document.getElementById('kategoriForm');
    const submitBtn = document.getElementById('submitBtn');
    if (!form) return;

    const fields = ['nama_kategori', 'deskripsi'];

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

        const namaEl = document.getElementById('nama_kategori');
        const namaVal = namaEl ? namaEl.value.trim() : '';

        // Validasi Nama Kategori
        if (!namaVal) {
            setFieldError('nama_kategori', 'Nama kategori wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = namaEl;
        } else if (namaVal.length > 100) {
            setFieldError('nama_kategori', 'Nama kategori maksimal 100 karakter.');
            isValid = false;
            if (!firstInvalid) firstInvalid = namaEl;
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
