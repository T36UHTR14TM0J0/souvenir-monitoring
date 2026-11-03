@extends('layouts.app')

@section('title', 'Setor Modal / Kas Masuk')

@push('styles')
<style>
    :root {
        --sidebar-bg-1: #26476f;
        --sidebar-bg-2: #1f3a5c;
        --sidebar-bg-3: #1a304d;
        --danger-color: #dc2626;
        --danger-hover: #b91c1c;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;
    }

    .card-modern {
        border: 1px solid var(--gray-200);
        border-radius: 0.875rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }
    .page-title-custom {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        display: flex;
        align-items: center;
        gap: 0.625rem;
        margin-bottom: 0.25rem;
    }
    .page-title-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--sidebar-bg-1) 0%, var(--sidebar-bg-3) 100%);
        color: #fff;
        border: 1px solid rgba(38, 71, 111, 0.3);
        border-radius: 0.625rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(38, 71, 111, 0.25);
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--sidebar-bg-1) 0%, var(--sidebar-bg-3) 100%);
        border: 1px solid var(--sidebar-bg-1);
        color: #fff;
        font-weight: 500;
        padding: 0.625rem 1.25rem;
        border-radius: 0.625rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        box-shadow: 0 2px 8px rgba(38, 71, 111, 0.25);
        cursor: pointer;
    }
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, var(--sidebar-bg-2) 0%, var(--sidebar-bg-3) 100%);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(38, 71, 111, 0.35);
    }

    .btn-danger-custom {
        background-color: var(--danger-color);
        border: 1px solid var(--danger-color);
        color: #fff;
        font-weight: 500;
        padding: 0.625rem 1.25rem;
        border-radius: 0.625rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
    }
    .btn-danger-custom:hover {
        background-color: var(--danger-hover);
        color: #fff;
        transform: translateY(-1px);
    }

    .form-control-modern, .form-select-modern {
        border: 1.5px solid var(--gray-300);
        border-radius: 0.625rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        width: 100%;
        background-color: #fff;
    }
    .form-control-modern:focus, .form-select-modern:focus {
        border-color: #26476f;
        box-shadow: 0 0 0 3px rgba(38, 71, 111, 0.12);
        outline: none;
    }
    .form-label-custom {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.375rem;
        display: block;
    }
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.625rem;
        flex-wrap: wrap;
    }
    @media (max-width: 768px) {
        .form-actions { flex-direction: column-reverse; align-items: stretch; }
        .form-actions .btn-primary-custom, .form-actions .btn-danger-custom { width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <h1 class="page-title-custom">
            <span class="page-title-icon">
                <i class="fas fa-hand-holding-dollar"></i>
            </span>
            Setor Modal / Kas Masuk
        </h1>
        <p class="text-muted" style="font-size: 0.875rem;">Catat penambahan uang kas toko dari suntikan modal debit pribadi atau pendapatan lain.</p>
    </div>

    <form action="{{ route('kas-masuk.store') }}" method="POST">
        @csrf

        <div class="card card-modern bg-white">
            <div class="card-body p-4 p-md-5">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">Kode Transaksi</label>
                        <input type="text" class="form-control form-control-modern" value="{{ $kodeTransaksi }}" readonly style="background:#f9fafb; font-family:monospace; font-weight:700; color:#10b981;">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control form-control-modern @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">Tipe Kas Masuk <span class="text-danger">*</span></label>
                        <select name="tipe" class="form-select form-select-modern @error('tipe') is-invalid @enderror">
                            @foreach($tipes as $k => $v)
                                <option value="{{ $k }}" {{ old('tipe') === $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                        @error('tipe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">Sumber Dana <span class="text-danger">*</span></label>
                        <select name="sumber" class="form-select form-select-modern @error('sumber') is-invalid @enderror">
                            @foreach($sumbers as $sk => $sv)
                                <option value="{{ $sk }}" {{ old('sumber', 'Debit / Transfer') === $sk ? 'selected' : '' }}>{{ $sv }}</option>
                            @endforeach
                        </select>
                        @error('sumber')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">Jumlah Masuk (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" class="form-control form-control-modern @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}" min="1" step="500" placeholder="Contoh: 2000000">
                        @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">Keterangan <span class="text-danger">*</span></label>
                        <input type="text" name="keterangan" class="form-control form-control-modern @error('keterangan') is-invalid @enderror" value="{{ old('keterangan', 'Modal Harian / Top-up Kas Toko') }}" placeholder="Contoh: Modal kas harian, suntikan saldo debit...">
                        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label-custom">Catatan Tambahan</label>
                        <textarea name="catatan" rows="2" class="form-control form-control-modern" placeholder="Opsional...">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                <hr style="border-color: var(--gray-100); margin: 1.75rem 0 1.5rem;">

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
            </div>
        </div>
    </form>

</div>
@endsection
