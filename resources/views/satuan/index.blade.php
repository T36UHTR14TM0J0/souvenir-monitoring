@extends('layouts.app')

@section('title', 'Master Satuan Produk')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-ruler-combined"
        title="Satuan Produk"
        subtitle="Kelola satuan ukuran atau takaran kemasan produk souvenir (pcs, box, lusin, dll)."
    >
        @can('satuan-create')
            <a href="{{ route('satuan.create') }}" class="btn btn-primary-custom">
                <i class="fas fa-plus"></i>
                <span>Tambah Satuan</span>
            </a>
        @endcan
    </x-page-header>

    {{-- FILTER SEARCH --}}
    <div class="card card-modern mb-4">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ route('satuan.index') }}">
                <div class="row g-2 g-md-3 align-items-center">

                    <div class="col-12 col-md-6">
                        <div class="form-search-wrapper">
                            <i class="fas fa-search form-search-icon"></i>
                            <input type="text"
                                   name="search"
                                   class="form-control form-control-modern"
                                   placeholder="Cari nama satuan, singkatan, atau deskripsi..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-12 col-md-6 d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom flex-fill justify-content-center">
                            <i class="fas fa-filter"></i>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('satuan.index') }}" class="btn-reset" title="Reset">
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
                            <th>Nama Satuan</th>
                            <th>Singkatan / Simbol</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Produk</th>
                            <th class="text-center col-aksi" style="width: 70px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($satuans as $index => $satuan)
                        <tr>
                            <td class="ps-4">
                                <span class="cell-muted fw-medium">
                                    {{ $satuans->firstItem() + $index }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-pro rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="background: rgba(38, 71, 111, 0.08); color: #26476f;">
                                        <i class="fas fa-ruler"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="cell-title text-truncate d-flex align-items-center gap-2">
                                            {{ $satuan->nama_satuan }}
                                            @if(in_array($satuan->nama_satuan, ['Pieces', 'Pcs']))
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
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fw-bold font-monospace" style="font-size: 0.85rem;">
                                    {{ $satuan->singkatan }}
                                </span>
                            </td>
                            <td>
                                <span class="cell-subtitle text-truncate d-inline-block" style="max-width: 320px;">
                                    {{ $satuan->deskripsi ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-modern badge-perm-count">
                                    <i class="fas fa-box"></i>
                                    {{ $satuan->produks_count }} produk
                                </span>
                            </td>
                            <td class="text-center col-aksi">
                                <div class="d-inline-flex flex-column gap-1 align-items-center">
                                    @can('satuan-edit')
                                        <a href="{{ route('satuan.edit', $satuan) }}"
                                           class="btn-icon btn-icon-warning"
                                           title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    @endcan

                                    @can('satuan-delete')
                                        <button type="button"
                                                class="btn-icon btn-icon-danger btn-delete-satuan"
                                                data-url="{{ route('satuan.destroy', $satuan) }}"
                                                data-name="{{ $satuan->nama_satuan }} ({{ $satuan->singkatan }})"
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
                                    message="Belum ada data satuan yang terdaftar atau ditemukan."
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if($satuans->hasPages())
            <x-pagination :paginator="$satuans" label="satuan" />
        @endif
    </div>
</div>

{{-- Hidden form untuk delete --}}
<form id="deleteSatuanForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ============================================
       DELETE SATUAN KONFIRMASI
       ============================================ */
    document.querySelectorAll('.btn-delete-satuan').forEach(button => {
        button.addEventListener('click', function () {
            const url  = this.dataset.url;
            const name = this.dataset.name;

            Swal.fire({
                title: 'Hapus Satuan?',
                html: `
                    <p style="color: #4b5563; margin-bottom: 0;">
                        Satuan <b>"${name}"</b> akan dihapus secara permanen.
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
                    const form = document.getElementById('deleteSatuanForm');
                    form.action = url;
                    form.submit();
                }
            });
        });
    });

})();
</script>
@endpush
