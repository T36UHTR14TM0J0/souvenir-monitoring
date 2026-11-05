@extends('layouts.app')

@section('title', 'Edit Satuan - ' . $satuan->nama_satuan)

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER (variant primary untuk edit persis Katalog Souvenir) --}}
    <x-page-header
        icon="fas fa-ruler-combined"
        title="Edit Satuan Ukuran"
        subtitle="Perbarui data satuan takaran <strong>{{ $satuan->nama_satuan }} ({{ $satuan->singkatan }})</strong>."
        iconVariant="primary"
    >
        <a href="{{ route('satuan.index') }}" class="btn btn-secondary-custom">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </x-page-header>

    {{-- FORM CARD --}}
    <div class="card card-modern">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('satuan.update', $satuan) }}" method="POST" id="satuanEditForm" novalidate>
                @csrf
                @method('PUT')

                {{-- ============ SECTION: INFORMASI SATUAN ============ --}}
                <div class="form-section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Satuan Ukuran &amp; Takaran
                </div>

                <div class="row g-3">
                    {{-- Nama Satuan --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="nama_satuan">
                            Nama Satuan Lengkap <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-ruler-combined input-icon"></i>
                            <input type="text"
                                   name="nama_satuan"
                                   id="nama_satuan"
                                   class="form-control form-control-modern @error('nama_satuan') is-invalid @enderror"
                                   value="{{ old('nama_satuan', $satuan->nama_satuan) }}"
                                   placeholder="Contoh: Pieces, Box, Roll"
                                   maxlength="50"
                                   required
                                   autofocus>
                        </div>
                        <div class="invalid-feedback" id="error_nama_satuan" @if(!$errors->has('nama_satuan')) style="display: none !important;" @endif>
                            @error('nama_satuan'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_nama_satuan" @if($errors->has('nama_satuan')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib diisi, nama lengkap satuan (maksimal 50 karakter)</span>
                        </div>
                    </div>

                    {{-- Singkatan / Simbol --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="singkatan">
                            Singkatan / Simbol Ringkas <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-tag input-icon"></i>
                            <input type="text"
                                   name="singkatan"
                                   id="singkatan"
                                   class="form-control form-control-modern font-monospace @error('singkatan') is-invalid @enderror"
                                   value="{{ old('singkatan', $satuan->singkatan) }}"
                                   placeholder="Contoh: pcs, box, roll"
                                   maxlength="20"
                                   required>
                        </div>
                        <div class="invalid-feedback" id="error_singkatan" @if(!$errors->has('singkatan')) style="display: none !important;" @endif>
                            @error('singkatan'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_singkatan" @if($errors->has('singkatan')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib diisi, simbol tercetak pada label &amp; nota (maksimal 20 karakter)</span>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">
                        <label class="form-label-custom" for="deskripsi">
                            Deskripsi atau Keterangan Takaran
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

    const form      = document.getElementById('satuanEditForm');
    const submitBtn = document.getElementById('submitBtn');
    if (!form) return;

    const fields = ['nama_satuan', 'singkatan', 'deskripsi'];

    function setFieldError(fieldId, message) {
        const input   = document.getElementById(fieldId);
        const errorEl = document.getElementById('error_' + fieldId);
        const noteEl  = document.getElementById('note_' + fieldId);

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
        const input   = document.getElementById(fieldId);
        const errorEl = document.getElementById('error_' + fieldId);
        const noteEl  = document.getElementById('note_' + fieldId);

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

    form.addEventListener('submit', function (e) {
        let isValid = true;
        let firstInvalid = null;
        fields.forEach(clearFieldError);

        const namaEl      = document.getElementById('nama_satuan');
        const singkatanEl = document.getElementById('singkatan');

        const namaVal = namaEl ? namaEl.value.trim() : '';
        if (!namaVal) {
            setFieldError('nama_satuan', 'Nama satuan wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = namaEl;
        }

        const singkatanVal = singkatanEl ? singkatanEl.value.trim() : '';
        if (!singkatanVal) {
            setFieldError('singkatan', 'Singkatan/simbol satuan wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = singkatanEl;
        }

        if (!isValid) {
            e.preventDefault();
            if (firstInvalid) firstInvalid.focus();
            return false;
        }

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> <span>Menyimpan...</span>';
        }
    });
})();
</script>
@endpush
