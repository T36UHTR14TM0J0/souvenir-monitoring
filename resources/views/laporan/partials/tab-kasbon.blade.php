{{-- REKAP PER KARYAWAN --}}
@if($per_karyawan->isNotEmpty())
<div class="card card-modern bg-white mb-3">
    <div class="card-header bg-white py-3 px-3 border-bottom d-flex align-items-center gap-2">
        <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #eef2ff; color: #26476f;">
            <i class="fas fa-users"></i>
        </span>
        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Rekap Piutang Kasbon Per Anggota Tim</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-produk align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Nama Anggota Tim</th>
                        <th class="text-center">Frekuensi</th>
                        <th class="text-end">Total Dipinjam</th>
                        <th class="text-end">Sudah Dibayar</th>
                        <th class="text-end">Sisa Hutang (Piutang)</th>
                        <th class="text-center pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($per_karyawan as $pk)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">
                                <i class="fas fa-user-circle me-1 text-primary"></i>
                                {{ $pk['nama'] }}
                            </td>
                            <td class="text-center">{{ $pk['count'] }}x pinjam</td>
                            <td class="text-end fw-semibold font-monospace">Rp {{ number_format($pk['total'], 0, ',', '.') }}</td>
                            <td class="text-end fw-semibold text-success font-monospace">Rp {{ number_format($pk['lunas'], 0, ',', '.') }}</td>
                            <td class="text-end fw-bold font-monospace {{ $pk['sisa'] > 0 ? 'text-danger' : 'text-success' }}">
                                Rp {{ number_format($pk['sisa'], 0, ',', '.') }}
                            </td>
                            <td class="text-center pe-4">
                                @if($pk['sisa'] <= 0)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">LUNAS</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">BELUM LUNAS</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- DETAIL TRANSAKSI KASBON --}}
<div class="card card-modern bg-white">
    <div class="card-header bg-white py-3 px-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #eef2ff; color: #26476f;">
                <i class="fas fa-list"></i>
            </span>
            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Rincian Riwayat Transaksi Kasbon ({{ $rows->count() }} Data)</h6>
        </div>
        <a href="{{ route('pengeluaran.index', ['tipe_alokasi' => 'kasbon']) }}" class="btn btn-primary-custom" style="height: 34px; font-size: 0.76rem; padding: 0 0.85rem;">
            <i class="fas fa-hand-holding-dollar"></i>
            <span>Kelola Kasbon</span>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-produk align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Tanggal</th>
                        <th>Nama Peminjam</th>
                        <th>Keterangan</th>
                        <th class="text-end">Nominal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4 col-freeze-aksi" style="min-width: 120px; width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $kb)
                        <tr>
                            <td class="ps-4"><span class="code-badge">{{ $kb->kode_pengeluaran }}</span></td>
                            <td>{{ $kb->tanggal->format('d/m/Y') }}</td>
                            <td class="fw-semibold text-dark">{{ $kb->karyawan_nama ?? '-' }}</td>
                            <td>{{ $kb->keterangan }}</td>
                            <td class="text-end fw-bold text-dark font-monospace">
                                Rp {{ number_format($kb->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if($kb->status_kasbon === 'lunas')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="fas fa-check me-1"></i> LUNAS
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                        <i class="fas fa-hourglass-half me-1"></i> BELUM LUNAS
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-4 col-freeze-aksi">
                                <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                    <form action="{{ route('pengeluaran.toggle-kasbon', $kb->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-action-icon {{ $kb->status_kasbon === 'lunas' ? 'btn-action-edit' : 'btn-action-view' }}" title="{{ $kb->status_kasbon === 'lunas' ? 'Tandai Belum Lunas' : 'Tandai Lunas' }}">
                                            <i class="fas {{ $kb->status_kasbon === 'lunas' ? 'fa-undo' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('pengeluaran.show', $kb->id) }}" class="btn-action-icon btn-action-view" title="Lihat Detail Kasbon">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                                Tidak ada data kasbon pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($rows->isNotEmpty())
                    <tfoot>
                        <tr>
                            <td colspan="4" class="ps-4">TOTAL KASBON TIM</td>
                            <td class="text-end font-monospace">Rp {{ number_format($total['total_kasbon'], 0, ',', '.') }}</td>
                            <td colspan="2" class="text-center text-danger font-monospace">
                                Sisa Piutang: Rp {{ number_format($total['total_belum_lunas'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
