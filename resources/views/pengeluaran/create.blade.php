@extends('layouts.app')

@section('title', 'Tambah Pengeluaran & Biaya')

@push('styles')
<style>
    /* ALOKASI TABS / SWITCHER */
    .alokasi-switch-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .alokasi-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 0.625rem;
        padding: 0.875rem;
        cursor: pointer;
        transition: all 0.18s ease;
        text-align: center;
        background: #ffffff;
    }

    .alokasi-card:hover {
        border-color: rgba(38, 71, 111, 0.35);
        transform: translateY(-1px);
    }

    .alokasi-card.active {
        border-color: #26476f;
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(38, 71, 111, 0.25);
    }

    .alokasi-card .alokasi-icon {
        font-size: 1.2rem;
        margin-bottom: 0.25rem;
        color: #26476f;
    }

    .alokasi-card.active .alokasi-icon {
        color: #fde68a;
    }

    .alokasi-card .alokasi-title {
        font-size: 0.825rem;
        font-weight: 700;
    }

    .alokasi-card .alokasi-desc {
        font-size: 0.72rem;
        opacity: 0.8;
    }

    .kategori-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 0.625rem;
    }

    .kategori-card {
        background-color: #ffffff;
        border: 1.5px solid #e2e8f0;
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
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(38, 71, 111, 0.35);
    }

    .kategori-card .kategori-icon {
        font-size: 1.15rem;
        margin-bottom: 0.375rem;
        color: #26476f;
    }

    .kategori-card.selected .kategori-icon {
        color: #fde68a;
    }

    .kategori-card .kategori-label {
        font-size: 0.78rem;
        font-weight: 600;
        line-height: 1.25;
    }

    @media (max-width: 768px) {
        .alokasi-switch-grid { grid-template-columns: 1fr; }
        .kategori-grid { grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-credit-card"
        title="Tambah Pengeluaran & Biaya"
        subtitle="Catat biaya operasional workshop, iklan marketplace (Ads), pengeluaran pribadi/prive, atau kasbon tim."
    >
        <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary-custom">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </x-page-header>

    <form action="{{ route('pengeluaran.store') }}" method="POST" id="pengeluaranForm" enctype="multipart/form-data" novalidate>
        @csrf

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card card-modern bg-white">
                    <div class="card-body p-4 p-md-5">

                        {{-- ============ SECTION 1: ALOKASI PENGELUARAN ============ --}}
                        <div class="form-section-title">
                            <i class="fas fa-layer-group"></i>
                            Pilih Alokasi Keuangan <span class="text-danger">*</span>
                        </div>

                        <input type="hidden" name="tipe_alokasi" id="tipeAlokasiInput" value="{{ old('tipe_alokasi', 'bisnis') }}">

                        <div class="alokasi-switch-grid">
                            <div class="alokasi-card {{ old('tipe_alokasi', 'bisnis') === 'bisnis' ? 'active' : '' }}" data-alokasi="bisnis">
                                <div class="alokasi-icon"><i class="fas fa-store"></i></div>
                                <div class="alokasi-title">1. Bisnis (Toko)</div>
                                <div class="alokasi-desc">Operasional, Ads, packing, listrik, dll.</div>
                            </div>
                            <div class="alokasi-card {{ old('tipe_alokasi') === 'pribadi' ? 'active' : '' }}" data-alokasi="pribadi">
                                <div class="alokasi-icon"><i class="fas fa-user-tie"></i></div>
                                <div class="alokasi-title">2. Pribadi / Prive</div>
                                <div class="alokasi-desc">Makan/lunch, kebutuhan rumah, prive owner.</div>
                            </div>
                            <div class="alokasi-card {{ old('tipe_alokasi') === 'kasbon' ? 'active' : '' }}" data-alokasi="kasbon">
                                <div class="alokasi-icon"><i class="fas fa-hand-holding-dollar"></i></div>
                                <div class="alokasi-title">3. Kasbon Tim</div>
                                <div class="alokasi-desc">Pinjaman karyawan yang dapat dilunasi.</div>
                            </div>
                        </div>

                        {{-- ============ SECTION 2: INFORMASI PENGELUARAN ============ --}}
                        <div class="form-section-title mt-4">
                            <i class="fas fa-info-circle"></i>
                            Informasi Pengeluaran
                        </div>

                        <div class="row g-3">
                            {{-- Kode Pengeluaran --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom" for="kode_pengeluaran">Kode Pengeluaran</label>
                                <div class="input-group-modern">
                                    <i class="fas fa-barcode input-icon"></i>
                                    <input type="text"
                                           id="kode_pengeluaran"
                                           class="form-control form-control-modern"
                                           value="{{ $kodePengeluaran }}"
                                           readonly
                                           style="background-color: #f8fafc; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-weight: 700; color: #dc2626;">
                                </div>
                                <div class="required-note">
                                    <i class="fas fa-circle-info"></i>
                                    <span>Kode pengeluaran dibuat otomatis oleh sistem</span>
                                </div>
                            </div>

                            {{-- Tanggal --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom" for="tanggal">
                                    Tanggal Pengeluaran <span class="required">*</span>
                                </label>
                                <div class="input-group-modern">
                                    <i class="fas fa-calendar-day input-icon"></i>
                                    <input type="date"
                                           name="tanggal"
                                           id="tanggal"
                                           class="form-control form-control-modern @error('tanggal') is-invalid @enderror"
                                           value="{{ old('tanggal', date('Y-m-d')) }}"
                                           max="{{ date('Y-m-d') }}">
                                </div>
                                @error('tanggal')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                <div class="required-note">
                                    <i class="fas fa-circle-info"></i>
                                    <span>Wajib diisi, tanggal transaksi biaya dikeluarkan</span>
                                </div>
                            </div>
                        </div>

                        {{-- BIDANG KHUSUS KASBON (JIKA KASBON DIPILIH) --}}
                        <div id="kasbonFields" style="{{ old('tipe_alokasi') === 'kasbon' ? 'display:block;' : 'display:none;' }}" class="mt-3 p-3 bg-light rounded border">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label-custom" for="karyawan_nama">
                                        Nama Peminjam / Karyawan <span class="required">*</span>
                                    </label>
                                    <div class="input-group-modern">
                                        <i class="fas fa-user input-icon"></i>
                                        <input type="text"
                                               name="karyawan_nama"
                                               id="karyawan_nama"
                                               class="form-control form-control-modern @error('karyawan_nama') is-invalid @enderror"
                                               value="{{ old('karyawan_nama') }}"
                                               placeholder="Contoh: Om Jaka, Om Ucing, Om Gawil">
                                    </div>
                                    <div class="invalid-feedback" id="error_karyawan_nama" @if(!$errors->has('karyawan_nama')) style="display: none !important;" @endif>
                                        @error('karyawan_nama'){{ $message }}@enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label-custom" for="status_kasbon">
                                        Status Pinjaman
                                    </label>
                                    <div class="input-group-modern">
                                        <i class="fas fa-clock input-icon"></i>
                                        <select name="status_kasbon" id="status_kasbon" class="form-select form-select-modern">
                                            <option value="belum_lunas" {{ old('status_kasbon', 'belum_lunas') === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas (Piutang Aktif)</option>
                                            <option value="lunas" {{ old('status_kasbon') === 'lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============ SECTION 3: PILIH KATEGORI ============ --}}
                        <div class="form-section-title mt-4">
                            <i class="fas fa-tags"></i>
                            Pilih Kategori Biaya
                        </div>

                        <input type="hidden" name="kategori" id="kategoriInput" value="{{ old('kategori', 'operasional') }}">

                        {{-- GRID KATEGORI BISNIS --}}
                        <div class="kategori-group" id="group-bisnis" style="{{ old('tipe_alokasi', 'bisnis') === 'bisnis' ? 'display:block;' : 'display:none;' }}">
                            <div class="kategori-grid">
                                @php
                                    $kategoriIcons = [
                                        'bahan_baku'    => 'fa-boxes-stacked',
                                        'jasa_produksi' => 'fa-scissors',
                                        'operasional'   => 'fa-briefcase',
                                        'konsumsi'      => 'fa-utensils',
                                        'transport'     => 'fa-gas-pump',
                                        'iklan'         => 'fa-bullhorn',
                                        'packing'       => 'fa-box-open',
                                        'gaji'          => 'fa-users',
                                        'listrik'       => 'fa-bolt',
                                        'sewa'          => 'fa-store',
                                        'perlengkapan'  => 'fa-box',
                                        'pemeliharaan'  => 'fa-tools',
                                        'pajak'         => 'fa-file-invoice-dollar',
                                        'lainnya'       => 'fa-ellipsis-h',
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

                        {{-- ============ SECTION 4: DETAIL & LAMPIRAN ============ --}}
                        <div class="form-section-title mt-4">
                            <i class="fas fa-edit"></i>
                            Detail Pengeluaran &amp; Bukti
                        </div>

                        <div class="row g-3">
                            {{-- Keterangan --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom" for="keterangan">
                                    Keterangan Pengeluaran <span class="required">*</span>
                                </label>
                                <div class="input-group-modern">
                                    <i class="fas fa-pen input-icon"></i>
                                    <input type="text"
                                           name="keterangan"
                                           id="keterangan"
                                           class="form-control form-control-modern @error('keterangan') is-invalid @enderror"
                                           value="{{ old('keterangan') }}"
                                           placeholder="Contoh: Top-up iklan Shopee Ads, beli lakban..."
                                           maxlength="255">
                                </div>
                                <div class="invalid-feedback" id="error_keterangan" @if(!$errors->has('keterangan')) style="display: none !important;" @endif>
                                    @error('keterangan'){{ $message }}@enderror
                                </div>
                                <div class="required-note" id="note_keterangan" @if($errors->has('keterangan')) style="display: none !important;" @endif>
                                    <i class="fas fa-circle-info"></i>
                                    <span>Wajib diisi, ringkasan rincian pengeluaran</span>
                                </div>
                            </div>

                            {{-- Jumlah --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom" for="jumlah">
                                    Jumlah Pengeluaran (Rp) <span class="required">*</span>
                                </label>
                                <div class="input-group-modern">
                                    <i class="fas fa-money-bill-wave input-icon"></i>
                                    <input type="number"
                                           name="jumlah"
                                           id="jumlah"
                                           class="form-control form-control-modern @error('jumlah') is-invalid @enderror"
                                           value="{{ old('jumlah', 0) }}"
                                           min="1"
                                           step="500"
                                           placeholder="0">
                                </div>
                                <div class="invalid-feedback" id="error_jumlah" @if(!$errors->has('jumlah')) style="display: none !important;" @endif>
                                    @error('jumlah'){{ $message }}@enderror
                                </div>
                                <div class="required-note" id="note_jumlah" @if($errors->has('jumlah')) style="display: none !important;" @endif>
                                    <i class="fas fa-circle-info"></i>
                                    <span>Wajib diisi, nominal minimal Rp 1</span>
                                </div>
                            </div>

                            {{-- Catatan Tambahan --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom" for="catatan">Catatan Tambahan</label>
                                <textarea name="catatan"
                                          id="catatan"
                                          rows="3"
                                          class="form-control form-control-modern @error('catatan') is-invalid @enderror"
                                          placeholder="Catatan opsional..."
                                          maxlength="500">{{ old('catatan') }}</textarea>
                                @error('catatan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                <div class="required-note">
                                    <i class="fas fa-circle-info"></i>
                                    <span>Opsional, maksimal 500 karakter</span>
                                </div>
                            </div>

                            {{-- Bukti Transaksi --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom" for="bukti_transaksi">
                                    Bukti Struk / Kuitansi (Opsional)
                                </label>
                                <input type="file"
                                       name="bukti_transaksi"
                                       id="bukti_transaksi"
                                       accept="image/jpeg,image/png,image/webp,application/pdf"
                                       class="form-control form-control-modern @error('bukti_transaksi') is-invalid @enderror">
                                <div class="required-note">
                                    <i class="fas fa-circle-info"></i>
                                    <span>Format: JPG, PNG, WEBP, atau PDF (Maks. 5MB)</span>
                                </div>
                                <div id="previewBuktiContainer" class="mt-2 p-2 bg-light rounded text-center d-none" style="border: 1px dashed #cbd5e1;">
                                    <img id="previewBuktiImg" src="" alt="Preview Bukti" class="img-fluid rounded d-none" style="max-height: 140px; object-fit: contain;">
                                    <div id="previewBuktiPdfName" class="text-dark small d-none" style="font-size: 0.85rem;">
                                        <i class="fas fa-file-pdf text-danger me-1"></i> <span id="pdfFileName"></span>
                                    </div>
                                </div>
                                @error('bukti_transaksi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
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
                                <div style="font-size: 0.75rem; opacity: 0.75; margin-bottom: 0.25rem;">Alokasi &amp; Kategori</div>
                                <div style="font-size: 0.875rem; font-weight: 600;" id="previewKategori">
                                    Bisnis &bull; Operasional Toko
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
<script>
(function () {
    'use strict';

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

            document.getElementById('group-bisnis').style.display = (alokasi === 'bisnis') ? 'block' : 'none';
            document.getElementById('group-pribadi').style.display = (alokasi === 'pribadi') ? 'block' : 'none';
            document.getElementById('group-kasbon').style.display = (alokasi === 'kasbon') ? 'block' : 'none';

            document.getElementById('kasbonFields').style.display = (alokasi === 'kasbon') ? 'block' : 'none';

            let defaultKat = 'operasional';
            if (alokasi === 'pribadi') defaultKat = 'makan_pribadi';
            if (alokasi === 'kasbon') defaultKat = 'kasbon_karyawan';

            const activeGroup = document.getElementById('group-' + alokasi);
            const firstCard = activeGroup.querySelector(`[data-kategori="${defaultKat}"]`) || activeGroup.querySelector('.kategori-card');
            if (firstCard) {
                firstCard.click();
            }

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

        const btn = document.getElementById('submitBtn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> <span>Menyimpan...</span>';
        }
    });

    // Preview Bukti Transaksi
    const inputBukti = document.getElementById('bukti_transaksi');
    if (inputBukti) {
        inputBukti.addEventListener('change', function() {
            const file = this.files[0];
            const previewContainer = document.getElementById('previewBuktiContainer');
            const previewImg = document.getElementById('previewBuktiImg');
            const previewPdfName = document.getElementById('previewBuktiPdfName');
            const pdfFileName = document.getElementById('pdfFileName');

            if (!file) {
                previewContainer.classList.add('d-none');
                return;
            }

            previewContainer.classList.remove('d-none');
            if (file.type.includes('image')) {
                previewImg.src = URL.createObjectURL(file);
                previewImg.classList.remove('d-none');
                previewPdfName.classList.add('d-none');
            } else {
                previewImg.classList.add('d-none');
                previewPdfName.classList.remove('d-none');
                pdfFileName.textContent = file.name;
            }
        });
    }

    document.getElementById('jumlah').dispatchEvent(new Event('input'));
})();
</script>
@endpush
