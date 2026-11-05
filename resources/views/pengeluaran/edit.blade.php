@extends('layouts.app')

@section('title', 'Edit Pengeluaran & Biaya')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-edit"
        title="Edit Pengeluaran & Biaya"
        subtitle="Perbarui data pengeluaran <strong>{{ $pengeluaran->kode_pengeluaran }}</strong>."
    >
        <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary-custom">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </x-page-header>

    {{-- FORM CARD --}}
    <div class="card card-modern">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('pengeluaran.update', $pengeluaran) }}" method="POST" id="pengeluaranForm" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                {{-- ============ SECTION 1: INFORMASI PENGELUARAN ============ --}}
                <div class="form-section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Pengeluaran &amp; Alokasi
                </div>

                <div class="row g-3">
                    {{-- Kode Pengeluaran --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label-custom" for="kode_pengeluaran">Kode Pengeluaran</label>
                        <div class="input-group-modern">
                            <i class="fas fa-barcode input-icon"></i>
                            <input type="text"
                                   id="kode_pengeluaran"
                                   class="form-control form-control-modern"
                                   value="{{ $pengeluaran->kode_pengeluaran }}"
                                   readonly
                                   style="background-color: #f8fafc; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-weight: 700; color: #dc2626;">
                        </div>
                        <div class="required-note">
                            <i class="fas fa-circle-info"></i>
                            <span>Kode tidak dapat diubah</span>
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label-custom" for="tanggal">
                            Tanggal <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-calendar-day input-icon"></i>
                            <input type="date"
                                   name="tanggal"
                                   id="tanggal"
                                   class="form-control form-control-modern @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal', $pengeluaran->tanggal->format('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}">
                        </div>
                        @error('tanggal')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        <div class="required-note">
                            <i class="fas fa-circle-info"></i>
                            <span>Tanggal transaksi pengeluaran</span>
                        </div>
                    </div>

                    {{-- Alokasi Keuangan --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label-custom" for="tipe_alokasi">
                            Alokasi Keuangan <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-layer-group input-icon"></i>
                            <select name="tipe_alokasi" id="tipe_alokasi" class="form-select form-select-modern @error('tipe_alokasi') is-invalid @enderror">
                                @foreach($tipeAlokasis as $tKey => $tLabel)
                                    <option value="{{ $tKey }}" {{ old('tipe_alokasi', $pengeluaran->tipe_alokasi) === $tKey ? 'selected' : '' }}>
                                        {{ $tLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('tipe_alokasi')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        <div class="required-note">
                            <i class="fas fa-circle-info"></i>
                            <span>Tipe alokasi beban keuangan</span>
                        </div>
                    </div>

                    {{-- Nama Peminjam (Jika Kasbon) --}}
                    <div class="col-12 col-md-6" id="karyawanNamaWrap" style="{{ old('tipe_alokasi', $pengeluaran->tipe_alokasi) === 'kasbon' ? 'display:block;' : 'display:none;' }}">
                        <label class="form-label-custom" for="karyawan_nama">
                            Nama Peminjam / Karyawan <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text"
                                   name="karyawan_nama"
                                   id="karyawan_nama"
                                   class="form-control form-control-modern @error('karyawan_nama') is-invalid @enderror"
                                   value="{{ old('karyawan_nama', $pengeluaran->karyawan_nama) }}">
                        </div>
                        @error('karyawan_nama')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- Status Kasbon (Jika Kasbon) --}}
                    <div class="col-12 col-md-6" id="statusKasbonWrap" style="{{ old('tipe_alokasi', $pengeluaran->tipe_alokasi) === 'kasbon' ? 'display:block;' : 'display:none;' }}">
                        <label class="form-label-custom" for="status_kasbon">
                            Status Kasbon
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-clock input-icon"></i>
                            <select name="status_kasbon" id="status_kasbon" class="form-select form-select-modern">
                                <option value="belum_lunas" {{ old('status_kasbon', $pengeluaran->status_kasbon) !== 'lunas' ? 'selected' : '' }}>Belum Lunas (Piutang Aktif)</option>
                                <option value="lunas" {{ old('status_kasbon', $pengeluaran->status_kasbon) === 'lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                            </select>
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="kategori">
                            Kategori Biaya <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-tag input-icon"></i>
                            <select name="kategori" id="kategori" class="form-select form-select-modern @error('kategori') is-invalid @enderror">
                                @foreach($kategoris as $key => $label)
                                    <option value="{{ $key }}" {{ old('kategori', $pengeluaran->kategori) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('kategori')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
                                   value="{{ old('jumlah', $pengeluaran->jumlah) }}"
                                   min="1"
                                   step="500">
                        </div>
                        @error('jumlah')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-12">
                        <label class="form-label-custom" for="keterangan">
                            Keterangan Pengeluaran <span class="required">*</span>
                        </label>
                        <div class="input-group-modern">
                            <i class="fas fa-pen input-icon"></i>
                            <input type="text"
                                   name="keterangan"
                                   id="keterangan"
                                   class="form-control form-control-modern @error('keterangan') is-invalid @enderror"
                                   value="{{ old('keterangan', $pengeluaran->keterangan) }}"
                                   maxlength="255">
                        </div>
                        @error('keterangan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- ============ SECTION 2: LAMPIRAN & CATATAN ============ --}}
                <div class="form-section-title mt-4">
                    <i class="fas fa-paperclip"></i>
                    Lampiran Bukti &amp; Catatan
                </div>

                <div class="row g-3">
                    {{-- Catatan Tambahan --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="catatan">Catatan Tambahan</label>
                        <textarea name="catatan"
                                  id="catatan"
                                  rows="3"
                                  class="form-control form-control-modern @error('catatan') is-invalid @enderror"
                                  placeholder="Catatan opsional..."
                                  maxlength="500">{{ old('catatan', $pengeluaran->catatan) }}</textarea>
                        @error('catatan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- Bukti Transaksi --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="bukti_transaksi">
                            Bukti Struk / Kuitansi (Opsional)
                        </label>

                        @if($pengeluaran->bukti_transaksi)
                            <div class="p-3 mb-2 bg-light rounded border d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    @if($pengeluaran->is_pdf_bukti)
                                        <i class="fas fa-file-pdf text-danger fa-2x"></i>
                                        <div>
                                            <span class="fw-bold small d-block">Dokumen PDF Terlampir</span>
                                            <a href="{{ $pengeluaran->bukti_transaksi_url }}" target="_blank" class="small text-primary">Buka PDF <i class="fas fa-external-link-alt"></i></a>
                                        </div>
                                    @else
                                        <img src="{{ $pengeluaran->bukti_transaksi_url }}" alt="Bukti" class="rounded border" style="height: 48px; width: 48px; object-fit: cover; cursor: pointer;" onclick="showBuktiModal('{{ $pengeluaran->bukti_transaksi_url }}', 'Bukti {{ $pengeluaran->kode_pengeluaran }}')">
                                        <div>
                                            <span class="fw-bold small d-block">Gambar Struk Terlampir</span>
                                            <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none" onclick="showBuktiModal('{{ $pengeluaran->bukti_transaksi_url }}', 'Bukti {{ $pengeluaran->kode_pengeluaran }}')">
                                                <i class="fas fa-search-plus me-1"></i>Lihat Bukti
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hapus_bukti" value="1" id="hapusBuktiCheck">
                                    <label class="form-check-label text-danger small" for="hapusBuktiCheck">
                                        <i class="fas fa-trash-alt me-1"></i>Hapus Bukti Ini
                                    </label>
                                </div>
                            </div>
                        @endif

                        <input type="file"
                               name="bukti_transaksi"
                               id="bukti_transaksi"
                               accept="image/jpeg,image/png,image/webp,application/pdf"
                               class="form-control form-control-modern @error('bukti_transaksi') is-invalid @enderror">
                        <div class="required-note">
                            <i class="fas fa-circle-info"></i>
                            <span>{{ $pengeluaran->bukti_transaksi ? 'Pilih file baru jika ingin mengganti bukti.' : 'Format: JPG, PNG, WEBP, atau PDF (Maks. 5MB).' }}</span>
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

                <hr class="form-divider">

                {{-- ============ ACTIONS ============ --}}
                <div class="form-actions">
                    <a href="{{ route('pengeluaran.index') }}" class="btn btn-danger-custom">
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
document.getElementById('tipe_alokasi').addEventListener('change', function() {
    const isKasbon = this.value === 'kasbon';
    document.getElementById('karyawanNamaWrap').style.display = isKasbon ? 'block' : 'none';
    document.getElementById('statusKasbonWrap').style.display = isKasbon ? 'block' : 'none';
});

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
</script>
@endpush
