@extends('layouts.app')

@section('title', 'Edit Pengeluaran')

@push('styles')
<style>
    :root {
        --sidebar-bg-1: #26476f;
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
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.06);
    }

    .page-title-custom {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 0.625rem;
        margin-bottom: 0.25rem;
    }

    .page-title-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #b45309;
        border: 1px solid #fcd34d;
        border-radius: 0.625rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(180, 83, 9, 0.15);
    }

    .page-subtitle-custom {
        font-size: 0.875rem;
        color: var(--gray-500);
        margin-bottom: 0;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%);
        border: 1px solid #26476f;
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
        background: linear-gradient(135deg, #1f3a5c 0%, #1a304d 100%);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-primary-custom:disabled { opacity: 0.6; cursor: not-allowed; }

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
    }

    .btn-danger-custom:hover {
        background-color: var(--danger-hover);
        color: #fff;
        transform: translateY(-1px);
    }

    .form-label-custom {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.375rem;
        display: block;
    }

    .form-label-custom .required { color: var(--danger-color); }

    .form-control-modern,
    .form-select-modern {
        border: 1px solid var(--gray-200);
        border-radius: 0.5rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        color: var(--gray-800);
        transition: all 0.15s ease;
        background-color: #fff;
        width: 100%;
    }

    .form-control-modern:focus,
    .form-select-modern:focus {
        border-color: #26476f;
        box-shadow: 0 0 0 3px rgba(38, 71, 111, 0.1);
        outline: none;
    }

    .form-control-modern.is-invalid,
    .form-select-modern.is-invalid {
        border-color: var(--danger-color) !important;
    }

    .invalid-feedback {
        display: block !important;
        font-size: 0.75rem !important;
        color: var(--danger-color) !important;
        margin-top: 0.375rem !important;
        font-weight: 500 !important;
    }

    .form-section-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--gray-800);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-section-title i { color: #26476f; font-size: 0.875rem; }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.625rem;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .page-title-custom { font-size: 1.25rem; }
        .page-title-icon { width: 36px; height: 36px; }
        .form-actions { flex-direction: column-reverse; align-items: stretch; }
        .form-actions .btn-primary-custom,
        .form-actions .btn-danger-custom { width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <h1 class="page-title-custom">
            <span class="page-title-icon">
                <i class="fas fa-edit"></i>
            </span>
            Edit Pengeluaran
        </h1>
        <p class="page-subtitle-custom">Perbarui data pengeluaran <strong>{{ $pengeluaran->kode_pengeluaran }}</strong>.</p>
    </div>

    <form action="{{ route('pengeluaran.update', $pengeluaran) }}" method="POST" id="pengeluaranForm" novalidate>
        @csrf
        @method('PUT')

        <div class="card card-modern bg-white">
            <div class="card-body p-4 p-md-5">

                <div class="form-section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Pengeluaran
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label-custom">Kode Pengeluaran</label>
                        <input type="text" class="form-control form-control-modern"
                               value="{{ $pengeluaran->kode_pengeluaran }}" readonly
                               style="background-color: #f9fafb; font-family: monospace; font-weight: 600; color: #dc2626;">
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label-custom" for="tanggal">
                            Tanggal <span class="required">*</span>
                        </label>
                        <input type="date" name="tanggal" id="tanggal"
                               class="form-control form-control-modern @error('tanggal') is-invalid @enderror"
                               value="{{ old('tanggal', $pengeluaran->tanggal->format('Y-m-d')) }}"
                               max="{{ date('Y-m-d') }}">
                        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label-custom" for="tipe_alokasi">
                            Alokasi Keuangan <span class="required">*</span>
                        </label>
                        <select name="tipe_alokasi" id="tipe_alokasi" class="form-select form-select-modern @error('tipe_alokasi') is-invalid @enderror">
                            @foreach($tipeAlokasis as $tKey => $tLabel)
                                <option value="{{ $tKey }}" {{ old('tipe_alokasi', $pengeluaran->tipe_alokasi) === $tKey ? 'selected' : '' }}>
                                    {{ $tLabel }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipe_alokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6" id="karyawanNamaWrap" style="{{ old('tipe_alokasi', $pengeluaran->tipe_alokasi) === 'kasbon' ? 'display:block;' : 'display:none;' }}">
                        <label class="form-label-custom" for="karyawan_nama">
                            Nama Peminjam / Karyawan <span class="required">*</span>
                        </label>
                        <input type="text" name="karyawan_nama" id="karyawan_nama"
                               class="form-control form-control-modern @error('karyawan_nama') is-invalid @enderror"
                               value="{{ old('karyawan_nama', $pengeluaran->karyawan_nama) }}">
                        @error('karyawan_nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6" id="statusKasbonWrap" style="{{ old('tipe_alokasi', $pengeluaran->tipe_alokasi) === 'kasbon' ? 'display:block;' : 'display:none;' }}">
                        <label class="form-label-custom" for="status_kasbon">
                            Status Kasbon
                        </label>
                        <select name="status_kasbon" id="status_kasbon" class="form-select form-select-modern">
                            <option value="belum_lunas" {{ old('status_kasbon', $pengeluaran->status_kasbon) !== 'lunas' ? 'selected' : '' }}>Belum Lunas (Piutang Aktif)</option>
                            <option value="lunas" {{ old('status_kasbon', $pengeluaran->status_kasbon) === 'lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="kategori">
                            Kategori <span class="required">*</span>
                        </label>
                        <select name="kategori" id="kategori"
                                class="form-select form-select-modern @error('kategori') is-invalid @enderror">
                            @foreach($kategoris as $key => $label)
                                <option value="{{ $key }}" {{ old('kategori', $pengeluaran->kategori) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="jumlah">
                            Jumlah <span class="required">*</span>
                        </label>
                        <input type="number" name="jumlah" id="jumlah"
                               class="form-control form-control-modern @error('jumlah') is-invalid @enderror"
                               value="{{ old('jumlah', $pengeluaran->jumlah) }}"
                               min="1" step="500">
                        @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label-custom" for="keterangan">
                            Keterangan <span class="required">*</span>
                        </label>
                        <input type="text" name="keterangan" id="keterangan"
                               class="form-control form-control-modern @error('keterangan') is-invalid @enderror"
                               value="{{ old('keterangan', $pengeluaran->keterangan) }}"
                               maxlength="255">
                        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label-custom" for="catatan">Catatan Tambahan</label>
                        <textarea name="catatan" id="catatan" rows="3"
                                  class="form-control form-control-modern @error('catatan') is-invalid @enderror"
                                  placeholder="Catatan opsional..."
                                  maxlength="500">{{ old('catatan', $pengeluaran->catatan) }}</textarea>
                        @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <hr style="border-color: var(--gray-100); margin: 1.75rem 0 1.5rem;">

                <div class="form-actions">
                    <a href="{{ route('pengeluaran.index') }}" class="btn-danger-custom">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <button type="submit" class="btn-primary-custom" id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('tipe_alokasi').addEventListener('change', function() {
    const isKasbon = this.value === 'kasbon';
    document.getElementById('karyawanNamaWrap').style.display = isKasbon ? 'block' : 'none';
    document.getElementById('statusKasbonWrap').style.display = isKasbon ? 'block' : 'none';
});
</script>
@endpush
