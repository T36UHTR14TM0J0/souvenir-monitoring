{{-- SUMMARY CARDS --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon summary-icon-red">
                <i class="fas fa-money-bill-transfer"></i>
            </div>
            <div>
                <div class="summary-label">Total Pengeluaran</div>
                <div class="summary-value">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</div>
                <div class="summary-sub">Biaya</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon summary-icon-navy">
                <i class="fas fa-receipt"></i>
            </div>
            <div>
                <div class="summary-label">Jumlah Transaksi</div>
                <div class="summary-value">{{ number_format($total['transaksi'], 0, ',', '.') }}x</div>
                <div class="summary-sub">Total pengeluaran</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="summary-card">
            <div class="summary-icon summary-icon-purple">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div>
                <div class="summary-label">Kategori Terbanyak</div>
                <div class="summary-value">
                    @if($total['per_kategori']->isNotEmpty())
                        {{ $total['per_kategori']->first()['kategori'] }}
                    @else
                        -
                    @endif
                </div>
                <div class="summary-sub">
                    @if($total['per_kategori']->isNotEmpty())
                        Rp {{ number_format($total['per_kategori']->first()['total'], 0, ',', '.') }}
                    @else
                        Belum ada data
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CHART + PER KATEGORI --}}
<div class="row g-3 mb-3">
    <div class="col-12 col-lg-8">
        <div class="panel" style="margin-bottom: 0;">
            <div class="panel-header">
                <h6 class="panel-title">
                    <i class="fas fa-chart-line"></i>
                    Grafik Pengeluaran
                </h6>
            </div>
            <div class="panel-body-padded">
                <div class="chart-container">
                    <canvas id="chartLaporan"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="panel" style="margin-bottom: 0; height: 100%;">
            <div class="panel-header">
                <h6 class="panel-title">
                    <i class="fas fa-chart-pie"></i>
                    Per Kategori
                </h6>
            </div>
            <div class="panel-body-padded">
                @if($total['per_kategori']->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-chart-pie"></i>
                        <div class="text">Belum ada data</div>
                    </div>
                @else
                    @foreach($total['per_kategori'] as $pk)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid var(--gray-100); font-size: 0.8rem;">
                            <div style="font-weight: 500; color: var(--gray-700);">{{ $pk['kategori'] }}</div>
                            <div class="text-red">Rp {{ number_format($pk['total'], 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

{{-- TABLE --}}
<div class="panel">
    <div class="panel-header">
        <h6 class="panel-title">
            <i class="fas fa-table"></i>
            Detail Pengeluaran ({{ $rows->count() }} data)
        </h6>
    </div>

    @if($rows->isEmpty())
        <div class="empty-state">
            <i class="fas fa-receipt"></i>
            <div class="text">Belum ada data pengeluaran di periode ini</div>
        </div>
    @else
    <div style="overflow-x: auto;">
        <table class="table-laporan">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th style="text-align: left;">Kategori</th>
                    <th style="text-align: left;">Keterangan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $pgl)
                    <tr>
                        <td style="font-family: monospace; color: #dc2626; font-weight: 600;">
                            {{ $pgl->kode_pengeluaran }}
                        </td>
                        <td>{{ $pgl->tanggal->format('d M Y') }}</td>
                        <td style="text-align: left;">
                            <span class="badge-soft warning">{{ $pgl->kategori_label }}</span>
                        </td>
                        <td style="text-align: left; color: var(--gray-600);">{{ $pgl->keterangan }}</td>
                        <td class="text-red">Rp {{ number_format($pgl->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">TOTAL</td>
                    <td class="text-red">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
</div>