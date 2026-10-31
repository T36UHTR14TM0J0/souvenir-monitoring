@extends('layouts.app')

@section('title', 'Manajemen Role')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-user-tag"
        title="Manajemen Role"
        subtitle="Kelola daftar role dan hak aksesnya."
    >
        @can('role-create')
            <a href="{{ route('roles.create') }}" class="btn btn-primary-custom">
                <i class="fas fa-plus"></i>
                <span>Tambah Role</span>
            </a>
        @endcan
    </x-page-header>

    {{-- TABLE CARD --}}
    <div class="card card-modern overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 60px;">No</th>
                            <th>Role</th>
                            <th>Statistik</th>
                            <th>Permissions</th>
                            <th class="pe-4 text-end" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $index => $role)
                        <tr>
                            <td class="ps-4">
                                <span class="cell-muted fw-medium">
                                    {{ $roles instanceof \Illuminate\Pagination\LengthAwarePaginator ? $roles->firstItem() + $index : $index + 1 }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="role-avatar rounded-circle">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                    <div>
                                        <div class="cell-title">{{ ucfirst($role->name) }}</div>
                                        <div class="cell-subtitle">
                                            <code class="code-tag">{{ $role->name }}</code>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge badge-modern badge-user-count align-self-start">
                                        <i class="fas fa-users" style="font-size: 10px;"></i>
                                        {{ $role->users_count }} User
                                    </span>
                                    <span class="badge badge-modern badge-perm-count align-self-start">
                                        <i class="fas fa-shield-alt" style="font-size: 10px;"></i>
                                        {{ $role->permissions->count() }} Permission
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if($role->permissions->count() > 0)
                                    <div class="perm-preview">
                                        @foreach($role->permissions->take(3) as $perm)
                                            <span class="perm-tag">{{ $perm->name }}</span>
                                        @endforeach

                                        @if($role->permissions->count() > 3)
                                            <span class="perm-tag perm-tag-more"
                                                  title="{{ $role->permissions->skip(3)->pluck('name')->join(', ') }}">
                                                +{{ $role->permissions->count() - 3 }} lainnya
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted fst-italic cell-subtitle">
                                        Belum ada permission
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex gap-1">
                                    @can('role-edit')
                                        <a href="{{ route('roles.edit', $role) }}"
                                           class="btn-icon btn-icon-edit"
                                           title="Edit Role">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    @endcan

                                    @can('role-delete')
                                        <button type="button"
                                                class="btn-icon btn-icon-danger btn-delete-role"
                                                data-url="{{ route('roles.destroy', $role) }}"
                                                data-name="{{ $role->name }}"
                                                data-users="{{ $role->users_count }}"
                                                title="Hapus Role">
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
                                    icon="fas fa-user-tag"
                                    title="Belum Ada Role"
                                    message="Belum ada role yang terdaftar dalam sistem."
                                >
                                    @can('role-create')
                                        <div class="empty-state-action">
                                            <a href="{{ route('roles.create') }}" class="btn btn-primary-custom">
                                                <i class="fas fa-plus"></i>
                                                <span>Tambah Role Pertama</span>
                                            </a>
                                        </div>
                                    @endcan
                                </x-empty-state>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if($roles->hasPages())
            <x-pagination :paginator="$roles" label="role" />
        @endif
    </div>
</div>

{{-- Hidden form untuk delete --}}
<form id="deleteRoleForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    document.querySelectorAll('.btn-delete-role').forEach(button => {
        button.addEventListener('click', function () {
            const url        = this.dataset.url;
            const name       = this.dataset.name;
            const usersCount = parseInt(this.dataset.users) || 0;

            let warningHtml = '';
            if (usersCount > 0) {
                warningHtml = `
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 0.75rem; margin-top: 0.75rem; text-align: left;">
                        <div style="display: flex; align-items: flex-start; gap: 0.5rem; color: #991b1b; font-size: 0.8rem;">
                            <i class="fas fa-exclamation-triangle" style="margin-top: 1px;"></i>
                            <div>
                                <strong>Perhatian!</strong><br>
                                Role ini masih digunakan oleh <b>${usersCount} user</b>.
                                Menghapus role ini dapat mempengaruhi hak akses mereka.
                            </div>
                        </div>
                    </div>
                `;
            }

            Swal.fire({
                title: 'Hapus Role?',
                html: `
                    <p style="color: #4b5563; margin-bottom: 0;">
                        Role <b>"${name}"</b> akan dihapus secara permanen.
                    </p>
                    <p style="font-size: 0.8rem; color: #6b7280; margin-top: 0.5rem;">
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                    ${warningHtml}
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#a8a29e',
                reverseButtons: true,
                focusCancel: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteRoleForm');
                    form.action = url;
                    form.submit();
                }
            });
        });
    });
})();
</script>
@endpush