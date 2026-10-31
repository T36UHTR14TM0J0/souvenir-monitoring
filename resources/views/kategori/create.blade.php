@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-tag"
        title="Tambah Kategori"
        subtitle="Buat kategori baru untuk mengelompokkan produk toko souvenir."
    />

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
                    <div class="col-12">
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
                                   placeholder="Contoh: Kaos & Pakaian"
                                   maxlength="100"
                                   autofocus>
                        </div>
                        @error('nama_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="required-note">
                                <i class="fas fa-circle-info"></i>
                                <span>Wajib diisi, maksimal 100 karakter</span>
                            </div>
                        @enderror
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
                                  maxlength="500">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="required-note">
                                <i class="fas fa-circle-info"></i>
                                <span>Opsional, maksimal 500 karakter</span>
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- Info Box --}}
                <div class="info-box mt-4">
                    <i class="fas fa-lightbulb"></i>
                    <div>
                        <strong>Tips:</strong> Gunakan nama kategori yang singkat, jelas, dan mudah dikenali untuk memudahkan pengelompokan produk.
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

    const SWAL_PRIMARY = '#26476f';
    const SWAL_CANCEL  = '#a8a29e';

    const form      = document.getElementById('kategoriForm');
    const submitBtn = document.getElementById('submitBtn');
    if (!form) return;

    /* ============================================
       FORM SUBMIT — Konfirmasi
       ============================================ */
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const namaInput = document.getElementById('nama_kategori');

        if (!namaInput.value.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Nama kategori wajib diisi.',
                confirmButtonText: 'OK',
                confirmButtonColor: SWAL_PRIMARY,
            });
            namaInput.focus();
            return false;
        }

        Swal.fire({
            title: 'Simpan Kategori?',
            text: 'Pastikan semua data yang diisi sudah benar.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-1"></i> Ya, Simpan!',
            cancelButtonText: 'Batal',
            confirmButtonColor: SWAL_PRIMARY,
            cancelButtonColor: SWAL_CANCEL,
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Menyimpan...</span>';
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    });

})();
</script>
@endpush