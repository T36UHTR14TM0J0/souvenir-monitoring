@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-users"
        title="Manajemen User"
        subtitle="Kelola daftar pengguna, peranan, dan status akses sistem."
    >
        @can('user-create')
            <a href="{{ route('users.create') }}" class="btn btn-primary-custom">
                <i class="fas fa-plus"></i>
                <span>Tambah User</span>
            </a>
        @endcan
    </x-page-header>

    {{-- FILTER --}}
    <div class="card card-modern mb-4">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="row g-2 g-md-3 align-items-center">

                    <div class="col-12 col-md-4">
                        <div class="form-search-wrapper">
                            <i class="fas fa-search form-search-icon"></i>
                            <input type="text" name="search"
                                   class="form-control form-control-modern"
                                   placeholder="Cari nama atau email..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <select name="role" class="form-select form-select-modern">
                            <option value="">Semua Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-3">
                        <select name="status" class="form-select form-select-modern">
                            <option value="">Semua Status</option>
                            <option value="aktif"    {{ request('status') == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom flex-fill justify-content-center">
                            <i class="fas fa-filter"></i>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('users.index') }}" class="btn-reset" title="Reset">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card card-modern overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 60px;">No</th>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Login Terakhir</th>
                            <th class="text-center col-aksi" style="width: 70px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td class="ps-4">
                                <span class="cell-muted fw-medium">{{ $users->firstItem() + $index }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-pro rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="cell-title text-truncate">{{ $user->name }}</div>
                                        <div class="cell-subtitle text-truncate">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                        <span class="badge badge-modern badge-neutral">{{ ucfirst($role->name) }}</span>
                                    @empty
                                        <span class="badge badge-modern badge-neutral fst-italic">Tanpa Role</span>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge badge-modern badge-status-active">
                                        <span class="status-dot status-dot-active"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge badge-modern badge-status-inactive">
                                        <span class="status-dot status-dot-inactive"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="cell-icon-text">
                                    <i class="far fa-clock"></i>
                                    {{ $user->last_login_at?->diffForHumans() ?? 'Belum pernah' }}
                                </span>
                            </td>
                            <td class="text-center col-aksi">
                                <div class="d-inline-flex flex-column gap-1 align-items-center">
                                    @can('user-edit')
                                        <a href="{{ route('users.edit', $user) }}" class="btn-icon btn-icon-warning" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <button type="button"
                                                class="btn-icon {{ $user->is_active ? 'btn-icon-warning' : 'btn-icon-success' }} btn-toggle-status"
                                                data-url="{{ route('users.toggle-active', $user) }}"
                                                data-name="{{ $user->name }}"
                                                data-active="{{ $user->is_active ? '1' : '0' }}"
                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                    @endcan
                                    @can('user-delete')
                                        <button type="button"
                                                class="btn-icon btn-icon-danger btn-delete-user"
                                                data-url="{{ route('users.destroy', $user) }}"
                                                data-name="{{ $user->name }}"
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
                                    message="Belum ada user yang terdaftar."
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if($users->hasPages())
            <x-pagination :paginator="$users" label="user" />
        @endif
    </div>
</div>

{{-- Hidden Forms --}}
<form id="toggleStatusForm" method="POST" style="display: none;">
    @csrf @method('PATCH')
</form>
<form id="deleteUserForm" method="POST" style="display: none;">
    @csrf @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    // ============================================
    // TOGGLE STATUS USER
    // ============================================
    document.querySelectorAll('.btn-toggle-status').forEach(button => {
        button.addEventListener('click', function () {
            const url        = this.dataset.url;
            const name       = this.dataset.name;
            const isActive   = this.dataset.active === '1';
            const action     = isActive ? 'menonaktifkan' : 'mengaktifkan';
            const actionText = isActive ? 'Ya, Nonaktifkan!' : 'Ya, Aktifkan!';
            const confirmColor = isActive ? '#f59e0b' : '#10b981';

            Swal.fire({
                title: 'Konfirmasi',
                html: `Apakah Anda yakin ingin <b>${action}</b> user <br><b>${name}</b>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: actionText,
                cancelButtonText: 'Batal',
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#a8a29e',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('toggleStatusForm');
                    form.action = url;
                    form.submit();
                }
            });
        });
    });

    // ============================================
    // DELETE USER
    // ============================================
    document.querySelectorAll('.btn-delete-user').forEach(button => {
        button.addEventListener('click', function () {
            const url  = this.dataset.url;
            const name = this.dataset.name;

            Swal.fire({
                title: 'Hapus User?',
                html: `User <b>"${name}"</b> akan dihapus secara permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#a8a29e',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteUserForm');
                    form.action = url;
                    form.submit();
                }
            });
        });
    });
</script>
@endpush