@extends('layouts.app')

@section('title', 'Edit Kas Masuk')

@push('styles')
<style>
    .card-modern {
        border: 1px solid var(--gray-200);
        border-radius: 0.875rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        display: flex;
        align-items: center;
        gap: 0.625rem;
        margin-bottom: 0.25rem;
    }
    .btn-primary-custom {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: 1px solid #10b981;
        color: #fff;
        font-weight: 500;
        padding: 0.625rem 1.25rem;
        border-radius: 0.625rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-primary-custom:hover { background: #047857; color: #fff; }
    .form-control-modern, .form-select-modern {
        border: 1px solid var(--gray-200);
        border-radius: 0.5rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        width: 100%;
    }
    .form-control-modern:focus, .form-select-modern:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
        outline: none;
    }
    .form-label-custom {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.375rem;
        display: block;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <h1 class="page-title">
            <i class="fas fa-edit text-success"></i>
            Edit Kas Masuk
        </h1>
        <p class="text-muted">Perbarui data transaksi <strong>{{ $kasMasuk->kode_transaksi }}</strong>.</p>
    </div>

    <form action="{{ route('kas-masuk.update', $kasMasuk) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card card-modern bg-white">
            <div class="card-body p-4 p-md-5">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">Kode Transaksi</label>
                        <input type="text" class="form-control form-control-modern" value="{{ $kasMasuk->kode_transaksi }}" readonly style="background:#f9fafb; font-family:monospace; font-weight:700; color:#10b981;">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control form-control-modern @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $kasMasuk->tanggal->format('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">Tipe Kas Masuk <span class="text-danger">*</span></label>
                        <select name="tipe" class="form-select form-select-modern @error('tipe') is-invalid @enderror">
                            @foreach($tipes as $k => $v)
                                <option value="{{ $k }}" {{ old('tipe', $kasMasuk->tipe) === $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                        @error('tipe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">Sumber Dana <span class="text-danger">*</span></label>
                        <select name="sumber" class="form-select form-select-modern @error('sumber') is-invalid @enderror">
                            @foreach($sumbers as $sk => $sv)
                                <option value="{{ $sk }}" {{ old('sumber', $kasMasuk->sumber) === $sk ? 'selected' : '' }}>{{ $sv }}</option>
                            @endforeach
                        </select>
                        @error('sumber')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">Jumlah Masuk (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" class="form-control form-control-modern @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', $kasMasuk->jumlah) }}" min="1" step="500">
                        @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">Keterangan <span class="text-danger">*</span></label>
                        <input type="text" name="keterangan" class="form-control form-control-modern @error('keterangan') is-invalid @enderror" value="{{ old('keterangan', $kasMasuk->keterangan) }}">
                        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label-custom">Catatan Tambahan</label>
                        <textarea name="catatan" rows="2" class="form-control form-control-modern">{{ old('catatan', $kasMasuk->catatan) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('kas-masuk.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection
