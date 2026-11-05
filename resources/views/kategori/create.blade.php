@extends('layouts.app')

@section('title', 'Tambah Kategori Produk & Material')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-tags"
        title="Tambah Kategori Baru"
        subtitle="Daftarkan kategori klasifikasi baru untuk souvenir jadi atau bahan baku workshop."
    >
        <a href="{{ route('kategori.index') }}" class="btn btn-secondary-custom">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </x-page-header>

    {{-- FORM CARD --}}
    <div class="card card-modern">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('kategori.store') }}" method="POST" id="kategoriForm" novalidate>
                @csrf

                {{-- ============ SECTION: INFORMASI KATEGORI ============ --}}
                <div class="form-section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Kategori
                </div>

                <div class="row g-3">
                    {{-- Nama Kategori --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="nama_kategori">
                            Nama Kategori <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-tag input-icon"></i>
                            <input type="text"
                                   name="nama_kategori"
                                   id="nama_kategori"
                                   class="form-control form-control-modern @error('nama_kategori') is-invalid @enderror"
                                   value="{{ old('nama_kategori') }}"
                                   placeholder="Contoh: Mug & Tumbler Custom"
                                   maxlength="100"
                                   required
                                   autofocus>
                        </div>
                        <div class="invalid-feedback" id="error_nama_kategori" @if(!$errors->has('nama_kategori')) style="display: none !important;" @endif>
                            @error('nama_kategori'){{ $message }}@enderror
                        </div>
                        <div class="required-note" id="note_nama_kategori" @if($errors->has('nama_kategori')) style="display: none !important;" @endif>
                            <i class="fas fa-circle-info"></i>
                            <span>Wajib diisi, nama kategori harus unik dan maksimal 100 karakter</span>
                        </div>
                    </div>

                    {{-- Info Penggunaan Kategori --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">
                            Peran Klasifikasi <span class="badge bg-secondary-subtle text-secondary ms-1"><i class="fas fa-layer-group me-1"></i>Master Data</span>
                        </label>
                        <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #bbf7d0; border-radius: 0.5rem; padding: 0.65rem 0.9rem; display: flex; align-items: center; gap: 10px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: #22c55e; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0;">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div style="font-size: 0.78rem; color: #166534; line-height: 1.4;">
                                Kategori digunakan pada <strong>Katalog Souvenir</strong>, <strong>Stok Bahan Baku</strong>, serta filter laporan transaksi.
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">
                        <label class="form-label-custom" for="deskripsi">
                            Deskripsi Klasifikasi
                        </label>
                        <textarea name="deskripsi"
                                  id="deskripsi"
                                  rows="3"
                                  class="form-control form-control-modern @error('deskripsi') is-invalid @enderror"
                                  placeholder="Contoh: Kategori aneka mug keramik sablon sublim, tumbler stainless, botol minum souvenir (opsional)"
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
                    <a href="{{ route('kategori.index') }}" class="btn btn-danger-custom">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <button type="submit" class="btn btn-primary-custom" id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span>Simpan Kategori</span>
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
        fields.forEach(clearFieldError);

        const namaEl = document.getElementById('nama_kategori');
        const namaVal = namaEl ? namaEl.value.trim() : '';

        if (!namaVal) {
            setFieldError('nama_kategori', 'Nama kategori wajib diisi.');
            isValid = false;
        } else if (namaVal.length > 100) {
            setFieldError('nama_kategori', 'Nama kategori maksimal 100 karakter.');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            if (namaEl) namaEl.focus();
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
