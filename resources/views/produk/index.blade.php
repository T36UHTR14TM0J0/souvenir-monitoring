@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-box"
        title="Daftar Produk"
        subtitle="Kelola katalog produk souvenir, harga, stok, dan kategori."
    >
        @can('produk-create')
            <a href="{{ route('produk.create') }}" class="btn btn-primary-custom">
                <i class="fas fa-plus"></i>
                <span>Tambah Produk</span>
            </a>
        @endcan
    </x-page-header>

    {{-- FILTER --}}
    <div class="card card-modern mb-4">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ route('produk.index') }}">
                <div class="row g-2 g-md-3 align-items-center">

                    <div class="col-12 col-md-3">
                        <div class="form-search-wrapper">
                            <i class="fas fa-search form-search-icon"></i>
                            <input type="text"
                                   name="search"
                                   class="form-control form-control-modern"
                                   placeholder="Cari nama / kode produk..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <select name="kategori_id" class="form-select form-select-modern">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <select name="satuan_id" class="form-select form-select-modern">
                            <option value="">Semua Satuan</option>
                            @foreach($satuans as $sat)
                                <option value="{{ $sat->id }}" {{ request('satuan_id') == $sat->id ? 'selected' : '' }}>
                                    {{ $sat->nama_satuan }} ({{ $sat->singkatan }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <select name="stok_status" class="form-select form-select-modern">
                            <option value="">Semua Stok</option>
                            <option value="menipis" {{ request('stok_status') == 'menipis' ? 'selected' : '' }}>
                                Stok Menipis
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom flex-fill justify-content-center">
                            <i class="fas fa-filter"></i>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('produk.index') }}" class="btn-reset" title="Reset">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card card-modern overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 60px;">No</th>
                            <th>Produk</th>
                            <th style="width: 140px;">Kategori</th>
                            <th style="width: 100px;">Satuan</th>
                            <th class="text-end text-nowrap col-nominal" style="width: 170px; min-width: 170px;">Harga</th>
                            <th class="text-center" style="width: 110px;">Stok</th>
                            <th class="text-center col-aksi" style="width: 70px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produks as $index => $produk)
                        <tr>
                            <td class="ps-4">
                                <span class="cell-muted fw-medium">
                                    {{ $produks->firstItem() + $index }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-pro rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                        <i class="fas fa-cube"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="cell-title text-truncate">
                                            <a href="{{ route('produk.show', $produk) }}" class="text-dark text-decoration-none hover-primary">
                                                {{ $produk->nama_produk }}
                                            </a>
                                        </div>
                                        <div class="mt-1">
                                            <span class="kode-produk">{{ $produk->kode_produk }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-kategori">
                                    <i class="fas fa-tag"></i>
                                    {{ $produk->kategori->nama_kategori }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fw-medium" style="font-size: 0.8rem;">
                                    <i class="fas fa-ruler-combined me-1"></i>{{ $produk->satuan->singkatan ?? ($produk->satuan->nama_satuan ?? 'pcs') }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap col-nominal" style="width: 170px; min-width: 170px;">
                                @if($produk->has_diskon)
                                    <div class="harga-jual text-nowrap text-success fw-bold">
                                        Rp {{ number_format($produk->harga_setelah_diskon, 0, ',', '.') }}
                                    </div>
                                    <div class="text-nowrap" style="font-size: 0.75rem;">
                                        <span class="text-decoration-line-through text-muted">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</span>
                                        <span class="badge bg-danger-subtle text-danger ms-1" style="font-size: 0.7rem; padding: 2px 5px;">-{{ $produk->label_diskon }}</span>
                                    </div>
                                @else
                                    <div class="harga-jual text-nowrap">
                                        Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                                    </div>
                                @endif
                                <div class="harga-beli text-nowrap" style="margin-top: 2px;">
                                    Beli: Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($produk->stok <= $produk->stok_minimal)
                                    <span class="badge-stok-low">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $produk->stok }} {{ $produk->satuan->singkatan ?? 'pcs' }}
                                    </span>
                                @else
                                    <span class="badge-stok-ok">
                                        <i class="fas fa-check"></i>
                                        {{ $produk->stok }} {{ $produk->satuan->singkatan ?? 'pcs' }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center col-aksi">
                                <div class="d-inline-flex flex-column gap-1 align-items-center">
                                    <a href="{{ route('produk.show', $produk) }}"
                                       class="btn-icon btn-icon-info"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('produk-edit')
                                        <a href="{{ route('produk.edit', $produk) }}"
                                           class="btn-icon btn-icon-warning"
                                           title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    @endcan

                                    @can('produk-delete')
                                        <button type="button"
                                                class="btn-icon btn-icon-danger btn-delete-produk"
                                                data-url="{{ route('produk.destroy', $produk) }}"
                                                data-name="{{ $produk->nama_produk }}"
                                                title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <x-empty-state
                                    icon="fas fa-folder-open"
                                    title="Tidak Ada Data"
                                    message="Belum ada produk yang terdaftar atau ditemukan."
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if($produks->hasPages())
            <x-pagination :paginator="$produks" label="produk" />
        @endif
    </div>
</div>

{{-- Hidden form untuk delete --}}
<form id="deleteProdukForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ============================================
       DELETE PRODUK
       ============================================ */
    document.querySelectorAll('.btn-delete-produk').forEach(button => {
        button.addEventListener('click', function () {
            const url  = this.dataset.url;
            const name = this.dataset.name;

            Swal.fire({
                title: 'Hapus Produk?',
                html: `
                    <p style="color: #4b5563; margin-bottom: 0;">
                        Produk <b>"${name}"</b> akan dihapus secara permanen.
                    </p>
                    <p style="font-size: 0.8rem; color: #6b7280; margin-top: 0.5rem;">
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#a8a29e',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteProdukForm');
                    form.action = url;
                    form.submit();
                }
            });
        });
    });

})();
</script>
@endpush
