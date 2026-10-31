{{-- SUMMARY CARDS --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon summary-icon-green">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div>
                <div class="summary-label">Total Penjualan</div>
                <div class="summary-value">Rp {{ number_format($total['penjualan'], 0, ',', '.') }}</div>
                <div class="summary-sub">Pendapatan</div>
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
                <div class="summary-sub">Total transaksi</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon summary-icon-amber">
                <i class="fas fa-tag"></i>
            </div>
            <div>
                <div class="summary-label">Total Diskon</div>
                <div class="summary-value">Rp {{ number_format($total['diskon'], 0, ',', '.') }}</div>
                <div class="summary-sub">Potongan harga</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon summary-icon-purple">
                <i class="fas fa-box"></i>
            </div>
            <div>
                <div class="summary-label">Total Item Terjual</div>
                <div class="summary-value">{{ number_format($total['item'], 0, ',', '.') }} pcs</div>
                <div class="summary-sub">Unit produk</div>
            </div>
        </div>
    </div>
</div>

{{-- CHART --}}
<div class="panel">
    <div class="panel-header">
        <h6 class="panel-title">
            <i class="fas fa-chart-line"></i>
            Grafik Penjualan
        </h6>
    </div>
    <div class="panel-body-padded">
        <div class="chart-container">
            <canvas id="chartLaporan"></canvas>
        </div>
    </div>
</div>

{{-- TABLE --}}
<div class="panel">
    <div class="panel-header">
        <h6 class="panel-title">
            <i class="fas fa-table"></i>
            Detail Transaksi Penjualan ({{ $rows->count() }} transaksi)
        </h6>
    </div>

    @if($rows->isEmpty())
        <div class="empty-state">
            <i class="fas fa-shopping-cart"></i>
            <div class="text">Belum ada transaksi penjualan di periode ini</div>
        </div>
    @else
    <div style="overflow-x: auto;">
        <table class="table-laporan">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th class="text-center">Item</th>
                    <th>Diskon</th>
                    <th>Grand Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $trx)
                    <tr>
                        <td style="font-family: monospace; color: #26476f; font-weight: 600;">
                            {{ $trx->kode_transaksi }}
                        </td>
                        <td>{{ $trx->tanggal->format('d M Y') }}</td>
                        <td>{{ $trx->nama_pelanggan ?? '-' }}</td>
                        <td class="text-center">{{ $trx->details->count() }}</td>
                        <td class="text-amber">
                            @if($trx->total_diskon > 0)
                                Rp {{ number_format($trx->total_diskon, 0, ',', '.') }}
                            @else
                                <span class="text-muted-cell">-</span>
                            @endif
                        </td>
                        <td class="text-green">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">TOTAL</td>
                    <td class="text-green">Rp {{ number_format($total['penjualan'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
</div>