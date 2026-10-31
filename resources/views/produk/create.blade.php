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
                    <div class="col-12 col-md-6">
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
                        @error('kode_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="required-note">
                                <i class="fas fa-circle-info"></i>
                                <span>Kode otomatis — hanya huruf kapital, angka, dan tanda hubung</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="col-12 col-md-6">
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
                        @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="required-note">
                                <i class="fas fa-circle-info"></i>
                                <span>Wajib dipilih</span>
                            </div>
                        @enderror
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
                        @error('nama_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="required-note">
                                <i class="fas fa-circle-info"></i>
                                <span>Wajib diisi, maksimal 150 karakter</span>
                            </div>
                        @enderror
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
                                <input type="number"
                                       name="harga_beli"
                                       id="harga_beli"
                                       class="form-control form-control-modern @error('harga_beli') is-invalid @enderror"
                                       value="{{ old('harga_beli', 0) }}"
                                       min="0" step="100">
                            </div>
                            @error('harga_beli')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="required-note">
                                    <i class="fas fa-circle-info"></i>
                                    <span>Harga beli dari supplier</span>
                                </div>
                            @enderror
                        </div>

                        {{-- Harga Jual --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom" for="harga_jual">
                                Harga Jual <span class="required">*</span>
                            </label>
                            <div class="input-rupiah">
                                <span class="prefix">Rp</span>
                                <input type="number"
                                       name="harga_jual"
                                       id="harga_jual"
                                       class="form-control form-control-modern @error('harga_jual') is-invalid @enderror"
                                       value="{{ old('harga_jual', 0) }}"
                                       min="0" step="100">
                            </div>
                            @error('harga_jual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="required-note">
                                    <i class="fas fa-circle-info"></i>
                                    <span>Harus ≥ harga beli</span>
                                </div>
                            @enderror
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
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="required-note">
                                    <i class="fas fa-circle-info"></i>
                                    <span>Jumlah stok saat ini</span>
                                </div>
                            @enderror
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
                            @error('stok_minimal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="required-note">
                                    <i class="fas fa-circle-info"></i>
                                    <span>Batas peringatan stok menipis</span>
                                </div>
                            @enderror
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

    const SWAL_PRIMARY = '#26476f';
    const SWAL_CANCEL  = '#a8a29e';

    const form      = document.getElementById('produkForm');
    const submitBtn = document.getElementById('submitBtn');
    if (!form) return;

    /* ============================================
       FORM SUBMIT — Konfirmasi + Validasi
       ============================================ */
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const kode     = document.getElementById('kode_produk');
        const kategori = document.getElementById('kategori_id');
        const nama     = document.getElementById('nama_produk');
        const hb       = parseFloat(document.getElementById('harga_beli').value) || 0;
        const hj       = parseFloat(document.getElementById('harga_jual').value) || 0;

        // Validasi bertahap
        if (!kode.value.trim()) {
            Swal.fire({ icon: 'warning', title: 'Kode Kosong', text: 'Kode produk wajib diisi.', confirmButtonColor: SWAL_PRIMARY });
            kode.focus();
            return false;
        }
        if (!kategori.value) {
            Swal.fire({ icon: 'warning', title: 'Kategori Belum Dipilih', text: 'Silakan pilih kategori produk.', confirmButtonColor: SWAL_PRIMARY });
            kategori.focus();
            return false;
        }
        if (!nama.value.trim()) {
            Swal.fire({ icon: 'warning', title: 'Nama Kosong', text: 'Nama produk wajib diisi.', confirmButtonColor: SWAL_PRIMARY });
            nama.focus();
            return false;
        }
        if (hj < hb) {
            Swal.fire({
                icon: 'warning',
                title: 'Harga Tidak Valid',
                text: 'Harga jual tidak boleh lebih kecil dari harga beli.',
                confirmButtonColor: SWAL_PRIMARY
            });
            document.getElementById('harga_jual').focus();
            return false;
        }

        Swal.fire({
            title: 'Simpan Produk?',
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