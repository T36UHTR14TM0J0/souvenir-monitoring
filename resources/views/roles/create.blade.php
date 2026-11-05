@extends('layouts.app')

@section('title', 'Tambah Role')

@push('styles')
<style>
    .card-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }
    .card-header-gradient {
        background: linear-gradient(135deg, #26476f 0%, #1f3a5c 50%, #1a304d 100%);
        color: #ffffff;
        padding: 1rem 1.25rem;
    }
    .form-label-custom {
        font-size: 0.76rem;
        font-weight: 700;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 0.35rem;
        display: block;
    }
    .input-group-custom .input-group-text {
        background-color: #f8fafc;
        border-color: #cbd5e1;
        color: #64748b;
        width: 40px;
        justify-content: center;
    }
    .input-group-custom .form-control {
        height: 40px;
        border-color: #cbd5e1;
        font-size: 0.85rem;
    }
    .perm-module-box {
        border: 1px solid #e2e8f0;
        border-radius: 0.65rem;
        background: #ffffff;
        overflow: hidden;
        height: 100%;
    }
    .perm-module-header {
        background: #f8fafc;
        padding: 0.7rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .perm-module-body {
        padding: 0.85rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }
    .btn-primary-custom,
    .btn-secondary-custom {
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        font-size: 0.8125rem;
        font-weight: 600;
        padding: 0 1.15rem;
        border-radius: 0.5rem;
        text-decoration: none;
        border: 1px solid transparent;
    }
    .btn-primary-custom {
        background: linear-gradient(135deg, #26476f 0%, #1a304d 100%);
        color: #ffffff !important;
    }
    .btn-secondary-custom {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <x-page-header
        icon="fas fa-user-shield"
        title="Tambah Role Baru"
        subtitle="Buat role baru dan pilih cakupan modul permission yang diizinkan."
    />

    <div class="card card-modern">
        <div class="card-header-gradient d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-shield-halved"></i>
                <h6 class="mb-0 fw-bold text-white" style="font-size: 0.9rem;">Konfigurasi Role &amp; Matriks Permission</h6>
            </div>
            <span class="badge bg-white text-dark fw-bold" id="permCounter">0 dipilih</span>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('roles.store') }}" method="POST" id="roleForm" novalidate>
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom" for="name">
                            Nama Role <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-custom">
                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Supervisor Gudang, Kasir, Auditor"
                                   autofocus>
                        </div>
                        @error('name')
                            <div class="text-danger small mt-1"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6 d-flex align-items-end">
                        <div class="p-2 px-3 rounded-3 border bg-light w-100 d-flex align-items-center justify-content-between" style="height: 40px;">
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" id="selectAllGlobal" style="cursor: pointer;">
                                <label class="form-check-label fw-bold text-dark small" for="selectAllGlobal" style="cursor: pointer;">
                                    Pilih Semua Permission Sistem
                                </label>
                            </div>
                            <span class="small text-muted">{{ $permissions->flatten()->count() }} total akses</span>
                        </div>
                    </div>
                </div>

                {{-- MATRIKS PERMISSION PER MODUL --}}
                <div class="row g-3">
                    @foreach($permissions as $group => $perms)
                        @php
                            $mInfo = $moduleMeta[$group] ?? ['label' => strtoupper($group), 'icon' => 'fas fa-folder'];
                            $slug = Str::slug($group);
                        @endphp
                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="perm-module-box">
                                <div class="perm-module-header">
                                    <div class="form-check m-0 d-flex align-items-center gap-2">
                                        <input class="form-check-input perm-select-all-group m-0"
                                               type="checkbox"
                                               id="grp_{{ $slug }}"
                                               data-group="{{ $slug }}"
                                               style="cursor: pointer;">
                                        <label class="form-check-label fw-bold text-dark" for="grp_{{ $slug }}" style="font-size: 0.8rem; cursor: pointer;">
                                            <i class="{{ $mInfo['icon'] }} text-primary me-1"></i>{{ $mInfo['label'] }}
                                        </label>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" id="counter-{{ $slug }}" style="font-size: 0.68rem;">
                                        0 / {{ $perms->count() }}
                                    </span>
                                </div>
                                <div class="perm-module-body">
                                    @foreach($perms as $perm)
                                        @php
                                            $pLabel = $permLabels[$perm->name] ?? null;
                                        @endphp
                                        <div class="form-check m-0 py-0.5">
                                            <input class="form-check-input perm-checkbox-item group-{{ $slug }}"
                                                   type="checkbox"
                                                   name="permissions[]"
                                                   value="{{ $perm->name }}"
                                                   id="perm_{{ $perm->id }}"
                                                   data-group="{{ $slug }}"
                                                   {{ in_array($perm->name, old('permissions', [])) ? 'checked' : '' }}
                                                   style="cursor: pointer;">
                                            <label class="form-check-label d-flex flex-column" for="perm_{{ $perm->id }}" style="cursor: pointer;">
                                                @if($pLabel)
                                                    <span class="fw-semibold text-dark" style="font-size: 0.78rem; line-height: 1.25;">{{ $pLabel }}</span>
                                                    <span class="font-monospace text-muted" style="font-size: 0.7rem;">{{ $perm->name }}</span>
                                                @else
                                                    <span class="font-monospace text-secondary" style="font-size: 0.78rem;">{{ $perm->name }}</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary-custom">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <button type="submit" class="btn btn-primary-custom" id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span>Simpan Role</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAllGlob   = document.getElementById('selectAllGlobal');
    const groupCheckboxes = document.querySelectorAll('.perm-select-all-group');
    const itemCheckboxes  = document.querySelectorAll('.perm-checkbox-item');
    const permCounter     = document.getElementById('permCounter');

    function updateCounters() {
        const total   = itemCheckboxes.length;
        const checked = Array.from(itemCheckboxes).filter(cb => cb.checked).length;

        if (permCounter) {
            permCounter.textContent = `${checked} dari ${total} dipilih`;
        }

        if (selectAllGlob) {
            selectAllGlob.checked       = checked === total && total > 0;
            selectAllGlob.indeterminate = checked > 0 && checked < total;
        }

        groupCheckboxes.forEach(gcb => {
            const group  = gcb.dataset.group;
            const items  = document.querySelectorAll(`.perm-checkbox-item.group-${group}`);
            const gTotal = items.length;
            const gCheck = Array.from(items).filter(cb => cb.checked).length;
            const badge  = document.getElementById(`counter-${group}`);

            if (badge) badge.textContent = `${gCheck} / ${gTotal}`;

            gcb.checked       = gCheck === gTotal && gTotal > 0;
            gcb.indeterminate = gCheck > 0 && gCheck < gTotal;
        });
    }

    if (selectAllGlob) {
        selectAllGlob.addEventListener('change', function () {
            itemCheckboxes.forEach(cb => cb.checked = this.checked);
            updateCounters();
        });
    }

    groupCheckboxes.forEach(gcb => {
        gcb.addEventListener('change', function () {
            const group = this.dataset.group;
            document.querySelectorAll(`.perm-checkbox-item.group-${group}`)
                .forEach(cb => cb.checked = this.checked);
            updateCounters();
        });
    });

    itemCheckboxes.forEach(cb => cb.addEventListener('change', updateCounters));
    updateCounters();
});
</script>
@endpush
