@extends('layouts.app')

@section('title', 'Kategori Produk')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-tags"
        title="Kategori Produk"
        subtitle="Kelola daftar kategori untuk mengelompokkan produk toko souvenir."
    >
        @can('kategori-create')
            <a href="{{ route('kategori.create') }}" class="btn btn-primary-custom">
                <i class="fas fa-plus"></i>
                <span>Tambah Kategori</span>
            </a>
        @endcan
    </x-page-header>

    {{-- FILTER --}}
    <div class="card card-modern mb-4">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ route('kategori.index') }}">
                <div class="row g-2 g-md-3 align-items-center">

                    <div class="col-12 col-md-6">
                        <div class="form-search-wrapper">
                            <i class="fas fa-search form-search-icon"></i>
                            <input type="text"
                                   name="search"
                                   class="form-control form-control-modern"
                                   placeholder="Cari nama kategori..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-12 col-md-6 d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom flex-fill justify-content-center">
                            <i class="fas fa-filter"></i>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('kategori.index') }}" class="btn-reset" title="Reset">
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
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Produk</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoris as $index => $kategori)
                        <tr>
                            <td class="ps-4">
                                <span class="cell-muted fw-medium">
                                    {{ $kategoris->firstItem() + $index }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-pro rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="cell-title text-truncate d-flex align-items-center gap-2">
                                            {{ $kategori->nama_kategori }}
                                            @if(in_array($kategori->nama_kategori, ['Umum', 'Lain-lain']))
                                                <span class="badge-protected">
                                                    <i class="fas fa-shield-alt"></i>
                                                    Default
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="cell-subtitle text-truncate d-inline-block" style="max-width: 320px;">
                                    {{ $kategori->deskripsi ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-modern badge-perm-count">
                                    <i class="fas fa-box"></i>
                                    {{ $kategori->produks_count }} produk
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex gap-1">
                                    @can('kategori-edit')
                                        <a href="{{ route('kategori.edit', $kategori) }}"
                                           class="btn-icon btn-icon-edit"
                                           title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    @endcan

                                    @can('kategori-delete')
                                        <button type="button"
                                                class="btn-icon btn-icon-danger btn-delete-kategori"
                                                data-url="{{ route('kategori.destroy', $kategori) }}"
                                                data-name="{{ $kategori->nama_kategori }}"
                                                title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <x-empty-state
                                    icon="fas fa-folder-open"
                                    title="Tidak Ada Data"
                                    message="Belum ada kategori yang terdaftar atau ditemukan."
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if($kategoris->hasPages())
            <x-pagination :paginator="$kategoris" label="kategori" />
        @endif
    </div>
</div>

{{-- Hidden form untuk delete --}}
<form id="deleteKategoriForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ============================================
       DELETE KATEGORI
       ============================================ */
    document.querySelectorAll('.btn-delete-kategori').forEach(button => {
        button.addEventListener('click', function () {
            const url  = this.dataset.url;
            const name = this.dataset.name;

            Swal.fire({
                title: 'Hapus Kategori?',
                html: `
                    <p style="color: #4b5563; margin-bottom: 0;">
                        Kategori <b>"${name}"</b> akan dihapus secara permanen.
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
                    const form = document.getElementById('deleteKategoriForm');
                    form.action = url;
                    form.submit();
                }
            });
        });
    });

})();
</script>
@endpush