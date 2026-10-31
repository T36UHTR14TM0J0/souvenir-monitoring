@extends('layouts.app')

@section('title', 'Tambah Pengeluaran')

@push('styles')
<style>
    :root {
        --sidebar-bg-1: #26476f;
        --sidebar-bg-2: #1f3a5c;
        --sidebar-bg-3: #1a304d;
        --sidebar-cream: #fbfaf7;
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
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: #fff;
        border: 1px solid rgba(220, 38, 38, 0.3);
        border-radius: 0.625rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
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
        box-shadow: 0 4px 14px rgba(38, 71, 111, 0.35);
    }

    .btn-primary-custom:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
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
    }

    .btn-danger-custom:hover {
        background-color: var(--danger-hover);
        border-color: var(--danger-hover);
        color: #fff;
        transform: translateY(-1px);
    }

    .form-label-custom {
        font-weight: 500;
        color: var(--gray-700);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        display: inline-block;
    }

    .form-label-custom .required {
        color: var(--danger-color);
        margin-left: 2px;
    }

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

    .invalid-feedback::before {
        content: '\f06a';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        font-size: 0.75rem;
        margin-right: 0.375rem;
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

    .form-section-title i {
        color: #dc2626;
        font-size: 0.875rem;
    }

    .kategori-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 0.625rem;
    }

    .kategori-card {
        background-color: #fff;
        border: 2px solid var(--gray-200);
        border-radius: 0.625rem;
        padding: 0.75rem;
        cursor: pointer;
        transition: all 0.15s ease;
        text-align: center;
        position: relative;
    }

    .kategori-card:hover {
        border-color: rgba(38, 71, 111, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(38, 71, 111, 0.12);
    }

    .kategori-card.selected {
        border-color: #26476f;
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%);
        color: #fff;
        box-shadow: 0 4px 14px rgba(38, 71, 111, 0.35);
    }

    .kategori-card .kategori-icon {
        font-size: 1.25rem;
        margin-bottom: 0.375rem;
        color: #26476f;
    }

    .kategori-card.selected .kategori-icon {
        color: #fde68a;
    }

    .kategori-card .kategori-label {
        font-size: 0.8rem;
        font-weight: 600;
        line-height: 1.2;
    }

    .input-jumlah-wrapper {
        position: relative;
    }

    .input-jumlah-wrapper .prefix {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-500);
        font-weight: 600;
        font-size: 0.95rem;
        pointer-events: none;
    }

    .input-jumlah-wrapper input {
        padding-left: 2.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        color: #dc2626;
        height: 56px;
    }

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

        .kategori-grid {
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <h1 class="page-title-custom">
            <span class="page-title-icon">
                <i class="fas fa-money-bill-transfer"></i>
            </span>
            Tambah Pengeluaran
        </h1>
        <p class="page-subtitle-custom">Input biaya operasional atau pengeluaran toko.</p>
    </div>

    <form action="{{ route('pengeluaran.store') }}" method="POST" id="pengeluaranForm" novalidate>
        @csrf

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card card-modern bg-white">
                    <div class="card-body p-4">

                        <div class="form-section-title">
                            <i class="fas fa-info-circle"></i>
                            Informasi Pengeluaran
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom">Kode Pengeluaran</label>
                                <input type="text" class="form-control form-control-modern"
                                       value="{{ $kodePengeluaran }}" readonly
                                       style="background-color: #f9fafb; font-family: monospace; font-weight: 600; color: #dc2626;">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label-custom" for="tanggal">
                                    Tanggal <span class="required">*</span>
                                </label>
                                <input type="date" name="tanggal" id="tanggal"
                                       class="form-control form-control-modern @error('tanggal') is-invalid @enderror"
                                       value="{{ old('tanggal', date('Y-m-d')) }}"
                                       max="{{ date('Y-m-d') }}">
                                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-section-title mt-4">
                            <i class="fas fa-tags"></i>
                            Pilih Kategori
                        </div>

                        <input type="hidden" name="kategori" id="kategoriInput" value="{{ old('kategori', 'operasional') }}">

                        <div class="kategori-grid" id="kategoriGrid">
                            @php
                                $kategoriIcons = [
                                    'operasional'  => 'fa-briefcase',
                                    'gaji'         => 'fa-users',
                                    'listrik'      => 'fa-bolt',
                                    'sewa'         => 'fa-store',
                                    'transport'    => 'fa-truck',
                                    'perlengkapan' => 'fa-box',
                                    'pemeliharaan' => 'fa-tools',
                                    'pajak'        => 'fa-file-invoice-dollar',
                                    'lainnya'      => 'fa-ellipsis-h',
                                ];
                            @endphp
                            @foreach($kategoris as $key => $label)
                                <div class="kategori-card {{ old('kategori', 'operasional') == $key ? 'selected' : '' }}"
                                     data-kategori="{{ $key }}">
                                    <div class="kategori-icon">
                                        <i class="fas {{ $kategoriIcons[$key] ?? 'fa-tag' }}"></i>
                                    </div>
                                    <div class="kategori-label">{{ $label }}</div>
                                </div>
                            @endforeach
                        </div>
                        @error('kategori')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                        <div class="form-section-title mt-4">
                            <i class="fas fa-edit"></i>
                            Detail Pengeluaran
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label-custom" for="keterangan">
                                    Keterangan <span class="required">*</span>
                                </label>
                                <input type="text" name="keterangan" id="keterangan"
                                       class="form-control form-control-modern @error('keterangan') is-invalid @enderror"
                                       value="{{ old('keterangan') }}"
                                       placeholder="Contoh: Bayar listrik bulan Oktober, Gaji karyawan minggu ke-2..."
                                       maxlength="255">
                                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label-custom" for="jumlah">
                                    Jumlah <span class="required">*</span>
                                </label>
                                <div class="input-jumlah-wrapper">
                                    <span class="prefix">Rp</span>
                                    <input type="number" name="jumlah" id="jumlah"
                                           class="form-control form-control-modern @error('jumlah') is-invalid @enderror"
                                           value="{{ old('jumlah', 0) }}"
                                           min="1" step="500"
                                           placeholder="0">
                                </div>
                                @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label-custom" for="catatan">Catatan Tambahan</label>
                                <textarea name="catatan" id="catatan" rows="3"
                                          class="form-control form-control-modern @error('catatan') is-invalid @enderror"
                                          placeholder="Catatan opsional..."
                                          maxlength="500">{{ old('catatan') }}</textarea>
                                @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card card-modern bg-white mb-3">
                    <div class="card-body p-4">
                        <div class="form-section-title">
                            <i class="fas fa-calculator"></i>
                            Preview
                        </div>

                        <div style="background: linear-gradient(135deg, #26476f 0%, #1a304d 100%); border-radius: 0.625rem; padding: 1.25rem; color: #fff;">
                            <div style="font-size: 0.75rem; opacity: 0.75; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                Jumlah Pengeluaran
                            </div>
                            <div style="font-size: 1.75rem; font-weight: 700; color: #fde68a; letter-spacing: -0.02em;" id="previewJumlah">
                                Rp 0
                            </div>
                            <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid rgba(255,255,255,0.15);">
                                <div style="font-size: 0.75rem; opacity: 0.75; margin-bottom: 0.25rem;">Kategori</div>
                                <div style="font-size: 0.875rem; font-weight: 600;" id="previewKategori">
                                    Operasional
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 1rem; padding: 0.75rem; background: #fffbeb; border: 1px solid #fde68a; border-radius: 0.5rem;">
                            <div style="font-size: 0.75rem; color: #92400e;">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Catatan:</strong> Data pengeluaran tidak mempengaruhi stok produk. Hanya tercatat sebagai biaya operasional.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('pengeluaran.index') }}" class="btn-danger-custom">
                        <i class="fas fa-arrow-left"></i>
                        <span>Batal</span>
                    </a>
                    <button type="submit" class="btn-primary-custom" id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span>Simpan</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
    'use strict';

    const SWAL_PRIMARY = '#26476f';
    const SWAL_CANCEL  = '#a8a29e';
    const KATEGORI_LABELS = @json($kategoris);

    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka || 0);
    }

    // KATEGORI SELECTOR
    document.querySelectorAll('.kategori-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.kategori-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');

            const kategori = this.dataset.kategori;
            document.getElementById('kategoriInput').value = kategori;
            document.getElementById('previewKategori').textContent = KATEGORI_LABELS[kategori] || kategori;
        });
    });

    // PREVIEW JUMLAH
    document.getElementById('jumlah').addEventListener('input', function() {
        const val = parseFloat(this.value) || 0;
        document.getElementById('previewJumlah').textContent = formatRupiah(val);
    });

    // FORM SUBMIT
    document.getElementById('pengeluaranForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
        if (jumlah < 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Jumlah Tidak Valid',
                text: 'Jumlah pengeluaran minimal Rp 1.',
                confirmButtonColor: SWAL_PRIMARY
            });
            document.getElementById('jumlah').focus();
            return false;
        }

        const keterangan = document.getElementById('keterangan').value.trim();
        if (!keterangan) {
            Swal.fire({
                icon: 'warning',
                title: 'Keterangan Kosong',
                text: 'Keterangan pengeluaran wajib diisi.',
                confirmButtonColor: SWAL_PRIMARY
            });
            document.getElementById('keterangan').focus();
            return false;
        }

        const kategori = document.getElementById('kategoriInput').value;
        const jumlahFormatted = formatRupiah(jumlah);
        const kategoriLabel = KATEGORI_LABELS[kategori] || kategori;

        Swal.fire({
            title: 'Simpan Pengeluaran?',
            html: `
                <p style="color: #4b5563; margin-bottom: 0.5rem;">Pastikan data sudah benar.</p>
                <div style="background: rgba(38, 71, 111, 0.06); padding: 0.75rem; border-radius: 8px; text-align: left;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 0.375rem;">
                        <span style="color: #6b7280;">Kategori:</span>
                        <span style="font-weight: 600; color: #26476f;">${kategoriLabel}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.8rem;">
                        <span style="color: #6b7280;">Jumlah:</span>
                        <span style="font-weight: 700; color: #dc2626; font-size: 1.1rem;">${jumlahFormatted}</span>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-1"></i> Ya, Simpan!',
            cancelButtonText: 'Batal',
            confirmButtonColor: SWAL_PRIMARY,
            cancelButtonColor: SWAL_CANCEL,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = document.getElementById('submitBtn');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Menyimpan...</span>';
                HTMLFormElement.prototype.submit.call(document.getElementById('pengeluaranForm'));
            }
        });
    });

    // INIT PREVIEW
    document.getElementById('jumlah').dispatchEvent(new Event('input'));

})();
</script>
@endpush