@extends('layouts.app')

@section('title', 'Kas Masuk & Modal Pemilik')

@push('styles')
<style>
    :root {
        --sidebar-bg-1: #26476f;
        --sidebar-bg-2: #1f3a5c;
        --sidebar-bg-3: #1a304d;
        --sidebar-cream: #fbfaf7;
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

    body { background-color: var(--gray-50); }

    .card-modern {
        border: 1px solid var(--gray-200);
        border-radius: 0.875rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .page-title {
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
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: 0.625rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }

    .page-subtitle { font-size: 0.875rem; color: var(--gray-500); margin-bottom: 0; }

    .btn-primary-custom {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: 1px solid #10b981;
        color: #fff;
        font-weight: 500;
        padding: 0.625rem 1.125rem;
        border-radius: 0.625rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
        text-decoration: none;
        height: 42px;
    }

    .btn-primary-custom:hover {
        background: #047857;
        color: #fff;
        transform: translateY(-1px);
    }

    .kpi-card {
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: 0.875rem;
        padding: 1.25rem;
        transition: all 0.2s ease;
        height: 100%;
    }
    .kpi-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        transform: translateY(-2px);
    }
    .kpi-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }
    .kpi-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        letter-spacing: -0.02em;
        margin-bottom: 0.25rem;
    }
    .kpi-icon {
        width: 40px;
        height: 40px;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    .kpi-icon-green { background: #ecfdf5; color: #059669; }
    .kpi-icon-blue  { background: #eff6ff; color: #2563eb; }
    .kpi-icon-navy  { background: rgba(38, 71, 111, 0.08); color: #26476f; }

    .form-control-modern, .form-select-modern {
        border: 1px solid var(--gray-200);
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        color: var(--gray-800);
        height: 42px;
    }
    .form-control-modern:focus, .form-select-modern:focus {
        border-color: #10b981;
        outline: none;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
    }

    .table-modern thead th {
        background-color: #f9fafb;
        color: var(--gray-600);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .table-modern tbody td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--gray-100);
        font-size: 0.875rem;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 0.375rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: transparent;
        transition: all 0.15s ease;
        font-size: 0.8125rem;
        text-decoration: none;
    }
    .btn-edit   { color: #d97706; }
    .btn-edit:hover { background-color: #fef3c7; color: #92400e; }
    .btn-delete { color: #dc2626; }
    .btn-delete:hover { background-color: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="page-title">
                <span class="page-title-icon">
                    <i class="fas fa-hand-holding-dollar"></i>
                </span>
                Kas Masuk & Modal Pemilik
            </h1>
            <p class="page-subtitle">Pencatatan setoran modal harian (Debit) dan pemasukan kas lainnya.</p>
        </div>
        <a href="{{ route('kas-masuk.create') }}" class="btn btn-primary-custom">
            <i class="fas fa-plus"></i>
            <span>Setor Modal / Kas Masuk</span>
        </a>
    </div>

    {{-- KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Kas Masuk Hari Ini</div>
                        <div class="kpi-value text-green">Rp {{ number_format($totalHariIni, 0, ',', '.') }}</div>
                    </div>
                    <div class="kpi-icon kpi-icon-green">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Modal Disetor Bulan Ini (Debit)</div>
                        <div class="kpi-value text-green">Rp {{ number_format($totalModalDisetorBulanIni, 0, ',', '.') }}</div>
                    </div>
                    <div class="kpi-icon kpi-icon-blue">
                        <i class="fas fa-hand-holding-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Total Semua Kas Masuk Bulan Ini</div>
                        <div class="kpi-value text-navy">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</div>
                    </div>
                    <div class="kpi-icon kpi-icon-navy">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER FORM --}}
    <div class="card card-modern mb-4 bg-white">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ route('kas-masuk.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <input type="text" name="search" class="form-control form-control-modern"
                               placeholder="Cari kode, keterangan, sumber..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-6 col-md-3">
                        <select name="tipe" class="form-select form-select-modern">
                            <option value="">Semua Tipe</option>
                            @foreach($tipes as $k => $v)
                                <option value="{{ $k }}" {{ request('tipe') === $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <input type="date" name="dari_tanggal" class="form-control form-control-modern" value="{{ request('dari_tanggal') }}">
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom flex-fill justify-content-center">
                            <i class="fas fa-filter"></i>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('kas-masuk.index') }}" class="btn btn-outline-secondary" style="height:42px; display:inline-flex; align-items:center;">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- DATA TABLE --}}
    <div class="card card-modern bg-white">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Sumber Dana</th>
                        <th>Keterangan</th>
                        <th class="text-end">Jumlah</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kasMasuks as $index => $km)
                        <tr>
                            <td class="ps-4" style="color: #6b7280;">{{ $kasMasuks->firstItem() + $index }}</td>
                            <td style="font-family: monospace; font-weight: 700; color: #10b981;">
                                {{ $km->kode_transaksi }}
                            </td>
                            <td>{{ $km->tanggal->format('d M Y') }}</td>
                            <td>
                                <span class="badge" style="background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0;">
                                    {{ $km->tipe_label }}
                                </span>
                            </td>
                            <td><strong>{{ $km->sumber }}</strong></td>
                            <td>{{ $km->keterangan }}</td>
                            <td class="text-end text-green" style="font-weight: 700; font-size: 1rem;">
                                + Rp {{ number_format($km->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('kas-masuk.edit', $km) }}" class="btn-icon btn-edit" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('kas-masuk.destroy', $km) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Hapus kas masuk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-delete" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5" style="color: #9ca3af;">
                                Belum ada data kas masuk / modal disetor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kasMasuks->hasPages())
            <div class="p-3 border-top">
                {{ $kasMasuks->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
