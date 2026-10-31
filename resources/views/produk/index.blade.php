@extends('layouts.app')

@section('title', 'Data Produk')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-box"
        title="Data Produk"
        subtitle="Kelola daftar produk toko souvenir beserta harga dan stoknya."
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

                    <div class="col-12 col-md-4">
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
                        <select name="stok_status" class="form-select form-select-modern">
                            <option value="">Semua Stok</option>
                            <option value="menipis" {{ request('stok_status') == 'menipis' ? 'selected' : '' }}>
                                Stok Menipis
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-3 d-flex gap-2">
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
                            <th>Kategori</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Stok</th>
                            <th class="pe-4 text-end">Aksi</th>
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
                                        <div class="cell-title text-truncate">{{ $produk->nama_produk }}</div>
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
                            <td class="text-end">
                                <div class="harga-jual">
                                    Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                                </div>
                                <div class="harga-beli">
                                    Beli: Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($produk->stok <= $produk->stok_minimal)
                                    <span class="badge-stok-low">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $produk->stok }}
                                    </span>
                                @else
                                    <span class="badge-stok-ok">
                                        <i class="fas fa-check"></i>
                                        {{ $produk->stok }}
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex gap-1">
                                    @can('produk-edit')
                                        <a href="{{ route('produk.edit', $produk) }}"
                                           class="btn-icon btn-icon-edit"
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
                            <td colspan="6">
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