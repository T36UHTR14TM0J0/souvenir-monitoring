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
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
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

    .form-label-custom .required {
        color: #dc2626;
    }

    .form-control-modern {
        border: 1.5px solid var(--gray-300);
        border-radius: 0.625rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        color: var(--gray-900);
        transition: all 0.2s ease;
        background-color: #fff;
        width: 100%;
    }

    .form-control-modern:focus {
        border-color: #26476f;
        box-shadow: 0 0 0 3px rgba(38, 71, 111, 0.12);
        outline: none;
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

    /* ALOKASI TABS / SWITCHER */
    .alokasi-switch-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .alokasi-card {
        border: 2px solid var(--gray-200);
        border-radius: 0.625rem;
        padding: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        background: #fff;
    }

    .alokasi-card:hover {
        border-color: rgba(38, 71, 111, 0.3);
        transform: translateY(-1px);
    }

    .alokasi-card.active {
        border-color: #26476f;
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%);
        color: #fff;
        box-shadow: 0 4px 12px rgba(38, 71, 111, 0.25);
    }

    .alokasi-card .alokasi-icon {
        font-size: 1.25rem;
        margin-bottom: 0.25rem;
    }

    .alokasi-card.active .alokasi-icon {
        color: #fde68a;
    }

    .alokasi-card .alokasi-title {
        font-size: 0.85rem;
        font-weight: 700;
    }

    .alokasi-card .alokasi-desc {
        font-size: 0.7rem;
        opacity: 0.8;
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
        .alokasi-switch-grid { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column-reverse; align-items: stretch; }
        .form-actions .btn-primary-custom,
        .form-actions .btn-danger-custom { width: 100%; }
        .kategori-grid { grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); }
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
        <p class="page-subtitle-custom">Input biaya operasional toko, pengeluaran pribadi/prive, atau kasbon tim.</p>
    </div>

    <form action="{{ route('pengeluaran.store') }}" method="POST" id="pengeluaranForm" novalidate>
        @csrf

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card card-modern bg-white">
                    <div class="card-body p-4">

                        {{-- ALOKASI PENGELUARAN --}}
                        <div class="form-section-title">
                            <i class="fas fa-layer-group"></i>
                            Pilih Alokasi Keuangan <span class="text-danger">*</span>
                        </div>

                        <input type="hidden" name="tipe_alokasi" id="tipeAlokasiInput" value="{{ old('tipe_alokasi', 'bisnis') }}">

                        <div class="alokasi-switch-grid">
                            <div class="alokasi-card {{ old('tipe_alokasi', 'bisnis') === 'bisnis' ? 'active' : '' }}" data-alokasi="bisnis">
                                <div class="alokasi-icon"><i class="fas fa-store"></i></div>
                                <div class="alokasi-title">1. Bisnis (Toko)</div>
                                <div class="alokasi-desc">Operasional, listrik, bensin, ongkir, dll.</div>
                            </div>
                            <div class="alokasi-card {{ old('tipe_alokasi') === 'pribadi' ? 'active' : '' }}" data-alokasi="pribadi">
                                <div class="alokasi-icon"><i class="fas fa-user-tie"></i></div>
                                <div class="alokasi-title">2. Pribadi / Prive</div>
                                <div class="alokasi-desc">Makan/lunch, beli gas, tarik tunai owner.</div>
                            </div>
                            <div class="alokasi-card {{ old('tipe_alokasi') === 'kasbon' ? 'active' : '' }}" data-alokasi="kasbon">
                                <div class="alokasi-icon"><i class="fas fa-hand-holding-dollar"></i></div>
                                <div class="alokasi-title">3. Kasbon Tim</div>
                                <div class="alokasi-desc">Pinjaman karyawan (Om Jaka, Om Ucing, dll).</div>
                            </div>
                        </div>

                        {{-- INFO PENGELUARAN --}}
                        <div class="form-section-title mt-4">
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

                        {{-- BIDANG KHUSUS KASBON (JIKA KASBON DIPILIH) --}}
                        <div id="kasbonFields" style="{{ old('tipe_alokasi') === 'kasbon' ? 'display:block;' : 'display:none;' }}" class="mt-3 p-3 bg-light rounded border">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label-custom" for="karyawan_nama">
                                        Nama Peminjam / Karyawan <span class="required">*</span>
                                    </label>
                                    <input type="text" name="karyawan_nama" id="karyawan_nama"
                                           class="form-control form-control-modern @error('karyawan_nama') is-invalid @enderror"
                                           value="{{ old('karyawan_nama') }}"
                                           placeholder="Contoh: Om Jaka, Om Ucing, Om Gawil">
                                    <div class="invalid-feedback" id="error_karyawan_nama" @if(!$errors->has('karyawan_nama')) style="display: none !important;" @endif>
                                        @error('karyawan_nama'){{ $message }}@enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label-custom" for="status_kasbon">
                                        Status Pinjaman
                                    </label>
                                    <select name="status_kasbon" id="status_kasbon" class="form-control form-control-modern">
                                        <option value="belum_lunas" {{ old('status_kasbon', 'belum_lunas') === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas (Piutang Aktif)</option>
                                        <option value="lunas" {{ old('status_kasbon') === 'lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- PILIH KATEGORI --}}
                        <div class="form-section-title mt-4">
                            <i class="fas fa-tags"></i>
                            Pilih Kategori
                        </div>

                        <input type="hidden" name="kategori" id="kategoriInput" value="{{ old('kategori', 'operasional') }}">

                        {{-- GRID KATEGORI BISNIS --}}
                        <div class="kategori-group" id="group-bisnis" style="{{ old('tipe_alokasi', 'bisnis') === 'bisnis' ? 'display:block;' : 'display:none;' }}">
                            <div class="kategori-grid">
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
                                @foreach($kategoriBisnis as $key => $label)
                                    <div class="kategori-card {{ old('kategori', 'operasional') == $key ? 'selected' : '' }}"
                                         data-kategori="{{ $key }}">
                                        <div class="kategori-icon">
                                            <i class="fas {{ $kategoriIcons[$key] ?? 'fa-tag' }}"></i>
                                        </div>
                                        <div class="kategori-label">{{ $label }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- GRID KATEGORI PRIBADI --}}
                        <div class="kategori-group" id="group-pribadi" style="{{ old('tipe_alokasi') === 'pribadi' ? 'display:block;' : 'display:none;' }}">
                            <div class="kategori-grid">
                                @php
                                    $pribadiIcons = [
                                        'makan_pribadi'   => 'fa-utensils',
                                        'kebutuhan_rumah' => 'fa-house',
                                        'prive_penarikan' => 'fa-wallet',
                                        'pribadi_lainnya' => 'fa-receipt',
                                    ];
                                @endphp
                                @foreach($kategoriPribadi as $key => $label)
                                    <div class="kategori-card {{ old('kategori') == $key ? 'selected' : '' }}"
                                         data-kategori="{{ $key }}">
                                        <div class="kategori-icon">
                                            <i class="fas {{ $pribadiIcons[$key] ?? 'fa-tag' }}"></i>
                                        </div>
                                        <div class="kategori-label">{{ $label }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- GRID KATEGORI KASBON --}}
                        <div class="kategori-group" id="group-kasbon" style="{{ old('tipe_alokasi') === 'kasbon' ? 'display:block;' : 'display:none;' }}">
                            <div class="kategori-grid">
                                @foreach($kategoriKasbon as $key => $label)
                                    <div class="kategori-card {{ old('kategori', 'kasbon_karyawan') == $key ? 'selected' : '' }}"
                                         data-kategori="{{ $key }}">
                                        <div class="kategori-icon">
                                            <i class="fas fa-hand-holding-dollar"></i>
                                        </div>
                                        <div class="kategori-label">{{ $label }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @error('kategori')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                        {{-- DETAIL PENGELUARAN --}}
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
                                       placeholder="Contoh: Bensin antar pesanan, Lunch siang tim, Kasbon mingguan..."
                                       maxlength="255">
                                <div class="invalid-feedback" id="error_keterangan" @if(!$errors->has('keterangan')) style="display: none !important;" @endif>
                                    @error('keterangan'){{ $message }}@enderror
                                </div>
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
                                <div class="invalid-feedback" id="error_jumlah" @if(!$errors->has('jumlah')) style="display: none !important;" @endif>
                                    @error('jumlah'){{ $message }}@enderror
                                </div>
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
                            Ringkasan Input
                        </div>

                        <div style="background: linear-gradient(135deg, #26476f 0%, #1a304d 100%); border-radius: 0.625rem; padding: 1.25rem; color: #fff;">
                            <div style="font-size: 0.75rem; opacity: 0.75; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                Jumlah Pengeluaran
                            </div>
                            <div style="font-size: 1.75rem; font-weight: 700; color: #fde68a; letter-spacing: -0.02em;" id="previewJumlah">
                                Rp 0
                            </div>
                            <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid rgba(255,255,255,0.15);">
                                <div style="font-size: 0.75rem; opacity: 0.75; margin-bottom: 0.25rem;">Alokasi & Kategori</div>
                                <div style="font-size: 0.875rem; font-weight: 600;" id="previewKategori">
                                    Bisnis &bull; Operasional
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 1rem; padding: 0.75rem; background: #fffbeb; border: 1px solid #fde68a; border-radius: 0.5rem;" id="infoAlokasiBox">
                            <div style="font-size: 0.75rem; color: #92400e;">
                                <i class="fas fa-info-circle me-1"></i>
                                <span id="infoAlokasiText">Pengeluaran <strong>Bisnis</strong> akan mengurangi laba bersih toko secara riil.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('pengeluaran.index') }}" class="btn btn-danger-custom">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <button type="submit" class="btn btn-primary-custom" id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span>Simpan Pengeluaran</span>
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

    // ALOKASI SWITCHER
    document.querySelectorAll('.alokasi-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.alokasi-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            const alokasi = this.dataset.alokasi;
            document.getElementById('tipeAlokasiInput').value = alokasi;

            // Show/hide category groups
            document.getElementById('group-bisnis').style.display = (alokasi === 'bisnis') ? 'block' : 'none';
            document.getElementById('group-pribadi').style.display = (alokasi === 'pribadi') ? 'block' : 'none';
            document.getElementById('group-kasbon').style.display = (alokasi === 'kasbon') ? 'block' : 'none';

            // Show/hide kasbon fields
            document.getElementById('kasbonFields').style.display = (alokasi === 'kasbon') ? 'block' : 'none';

            // Set default category for active group
            let defaultKat = 'operasional';
            if (alokasi === 'pribadi') defaultKat = 'makan_pribadi';
            if (alokasi === 'kasbon') defaultKat = 'kasbon_karyawan';

            const activeGroup = document.getElementById('group-' + alokasi);
            const firstCard = activeGroup.querySelector(`[data-kategori="${defaultKat}"]`) || activeGroup.querySelector('.kategori-card');
            if (firstCard) {
                firstCard.click();
            }

            // Update info box
            const infoText = document.getElementById('infoAlokasiText');
            if (alokasi === 'bisnis') {
                infoText.innerHTML = 'Pengeluaran <strong>Bisnis</strong> akan mengurangi laba bersih operasional toko.';
            } else if (alokasi === 'pribadi') {
                infoText.innerHTML = 'Pengeluaran <strong>Pribadi / Prive</strong> TIDAK mengurangi laba bersih toko, tapi dicatat sebagai penarikan modal pemilik.';
            } else {
                infoText.innerHTML = 'Pengeluaran <strong>Kasbon</strong> dicatat sebagai piutang pinjaman karyawan dan dapat dilunasi nanti.';
            }
        });
    });

    // KATEGORI SELECTOR
    document.querySelectorAll('.kategori-card').forEach(card => {
        card.addEventListener('click', function() {
            const parent = this.closest('.kategori-grid');
            if (parent) {
                parent.querySelectorAll('.kategori-card').forEach(c => c.classList.remove('selected'));
            }
            this.classList.add('selected');

            const kategori = this.dataset.kategori;
            document.getElementById('kategoriInput').value = kategori;

            const alokasi = document.getElementById('tipeAlokasiInput').value;
            const alokasiTitle = alokasi.charAt(0).toUpperCase() + alokasi.slice(1);
            document.getElementById('previewKategori').textContent = alokasiTitle + ' • ' + (KATEGORI_LABELS[kategori] || kategori);
        });
    });

    // PREVIEW JUMLAH
    document.getElementById('jumlah').addEventListener('input', function() {
        const val = parseFloat(this.value) || 0;
        document.getElementById('previewJumlah').textContent = formatRupiah(val);
    });

    function setFieldError(fieldId, message) {
        const input   = document.getElementById(fieldId);
        const errorEl = document.getElementById('error_' + fieldId);

        if (input) {
            input.classList.add('is-invalid');
        }
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.style.setProperty('display', 'block', 'important');
        }
    }

    function clearFieldError(fieldId) {
        const input   = document.getElementById(fieldId);
        const errorEl = document.getElementById('error_' + fieldId);

        if (input) {
            input.classList.remove('is-invalid');
        }
        if (errorEl) {
            errorEl.textContent = '';
            errorEl.style.setProperty('display', 'none', 'important');
        }
    }

    ['keterangan', 'jumlah', 'karyawan_nama'].forEach(function(fieldId) {
        const el = document.getElementById(fieldId);
        if (!el) return;
        el.addEventListener('input', function() {
            clearFieldError(fieldId);
        });
    });

    // FORM SUBMIT
    document.getElementById('pengeluaranForm').addEventListener('submit', function (e) {
        let isValid = true;
        let firstInvalid = null;

        ['keterangan', 'jumlah', 'karyawan_nama'].forEach(clearFieldError);

        const alokasi = document.getElementById('tipeAlokasiInput').value;
        const jumlahInput = document.getElementById('jumlah');
        const jumlah = parseFloat(jumlahInput.value) || 0;

        if (jumlah < 1) {
            setFieldError('jumlah', 'Jumlah pengeluaran minimal Rp 1.');
            isValid = false;
            if (!firstInvalid) firstInvalid = jumlahInput;
        }

        if (alokasi === 'kasbon') {
            const namaEl = document.getElementById('karyawan_nama');
            const nama = namaEl ? namaEl.value.trim() : '';
            if (!nama) {
                setFieldError('karyawan_nama', 'Nama peminjam / karyawan wajib diisi untuk kasbon.');
                isValid = false;
                if (!firstInvalid) firstInvalid = namaEl;
            }
        }

        const ketEl = document.getElementById('keterangan');
        const keterangan = ketEl ? ketEl.value.trim() : '';
        if (!keterangan) {
            setFieldError('keterangan', 'Keterangan pengeluaran wajib diisi.');
            isValid = false;
            if (!firstInvalid) firstInvalid = ketEl;
        }

        if (!isValid) {
            e.preventDefault();
            if (firstInvalid) {
                firstInvalid.focus();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
        }

        // Form valid: submit langsung
        const btn = document.getElementById('submitBtn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> <span>Menyimpan...</span>';
        }
    });

    // INIT PREVIEW
    document.getElementById('jumlah').dispatchEvent(new Event('input'));

})();
</script>
@endpush
