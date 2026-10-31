{{-- SUMMARY CARDS --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-lg-4">
        <div class="summary-card">
            <div class="summary-icon summary-icon-navy">
                <i class="fas fa-hand-holding-dollar"></i>
            </div>
            <div>
                <div class="summary-label">Total Kasbon Diberikan</div>
                <div class="summary-value">Rp {{ number_format($total['total_kasbon'], 0, ',', '.') }}</div>
                <div class="summary-sub">{{ $total['transaksi'] }} Catatan Transaksi</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="summary-card">
            <div class="summary-icon summary-icon-green">
                <i class="fas fa-circle-check"></i>
            </div>
            <div>
                <div class="summary-label">Kasbon Sudah Lunas</div>
                <div class="summary-value text-green">Rp {{ number_format($total['total_lunas'], 0, ',', '.') }}</div>
                <div class="summary-sub">Sudah Dikembalikan / Potong Gaji</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="summary-card">
            <div class="summary-icon summary-icon-red">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div class="summary-label">Sisa Kasbon Belum Lunas</div>
                <div class="summary-value text-red">Rp {{ number_format($total['total_belum_lunas'], 0, ',', '.') }}</div>
                <div class="summary-sub">Piutang Aktif Tim Toko</div>
            </div>
        </div>
    </div>
</div>

{{-- REKAP PER KARYAWAN --}}
@if($per_karyawan->isNotEmpty())
<div class="panel mb-3">
    <div class="panel-header">
        <h6 class="panel-title">
            <i class="fas fa-users"></i>
            Rekap Piutang Kasbon Per Anggota Tim
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table-laporan">
            <thead>
                <tr>
                    <th>Nama Anggota Tim</th>
                    <th style="text-align: center;">Frekuensi</th>
                    <th style="text-align: right;">Total Dipinjam</th>
                    <th style="text-align: right;">Sudah Dibayar</th>
                    <th style="text-align: right;">Sisa Hutang (Piutang)</th>
                    <th style="text-align: center; width: 140px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($per_karyawan as $pk)
                    <tr>
                        <td style="font-weight: 700; color: var(--gray-900);">
                            <i class="fas fa-user-circle me-1 text-primary"></i>
                            {{ $pk['nama'] }}
                        </td>
                        <td style="text-align: center;">{{ $pk['count'] }}x pinjam</td>
                        <td style="text-align: right;">Rp {{ number_format($pk['total'], 0, ',', '.') }}</td>
                        <td class="text-green" style="text-align: right;">Rp {{ number_format($pk['lunas'], 0, ',', '.') }}</td>
                        <td class="{{ $pk['sisa'] > 0 ? 'text-red' : 'text-green' }}" style="text-align: right; font-weight: 700;">
                            Rp {{ number_format($pk['sisa'], 0, ',', '.') }}
                        </td>
                        <td style="text-align: center;">
                            @if($pk['sisa'] <= 0)
                                <span class="badge-soft success">LUNAS</span>
                            @else
                                <span class="badge-soft warning">BELUM LUNAS</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- DETAIL TRANSAKSI KASBON --}}
<div class="panel">
    <div class="panel-header">
        <h6 class="panel-title">
            <i class="fas fa-list"></i>
            Rincian Riwayat Transaksi Kasbon
        </h6>
        <span class="badge-soft" style="background:#eff6ff; color:#1d4ed8;">
            {{ $rows->count() }} Data
        </span>
    </div>
    <div class="table-responsive">
        <table class="table-laporan">
            <thead>
                <tr>
                    <th style="width: 140px;">Kode</th>
                    <th style="width: 110px;">Tanggal</th>
                    <th style="width: 160px;">Nama Peminjam</th>
                    <th>Keterangan</th>
                    <th style="width: 140px; text-align: right;">Nominal</th>
                    <th style="width: 130px; text-align: center;">Status</th>
                    <th style="width: 120px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $kb)
                    <tr>
                        <td style="font-family: monospace; font-size: 0.75rem; font-weight: 600;">
                            {{ $kb->kode_pengeluaran }}
                        </td>
                        <td>{{ $kb->tanggal->format('d/m/Y') }}</td>
                        <td style="font-weight: 600;">{{ $kb->karyawan_nama ?? '-' }}</td>
                        <td>{{ $kb->keterangan }}</td>
                        <td style="text-align: right; font-weight: 700;" class="text-navy">
                            Rp {{ number_format($kb->jumlah, 0, ',', '.') }}
                        </td>
                        <td style="text-align: center;">
                            @if($kb->status_kasbon === 'lunas')
                                <span class="badge-soft success">
                                    <i class="fas fa-check me-1"></i> LUNAS
                                </span>
                            @else
                                <span class="badge-soft warning">
                                    <i class="fas fa-hourglass-half me-1"></i> BELUM LUNAS
                                </span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <form action="{{ route('pengeluaran.toggle-kasbon', $kb->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $kb->status_kasbon === 'lunas' ? 'btn-outline-warning' : 'btn-outline-success' }}" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;" title="Klik untuk ubah status">
                                    {{ $kb->status_kasbon === 'lunas' ? 'Tandai Belum' : 'Tandai Lunas' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <div class="text">Tidak ada data kasbon pada periode ini.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">TOTAL</td>
                    <td class="text-navy" style="text-align: right;">Rp {{ number_format($total['total_kasbon'], 0, ',', '.') }}</td>
                    <td colspan="2" style="text-align: center;" class="text-red">
                        Sisa Piutang: Rp {{ number_format($total['total_belum_lunas'], 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
