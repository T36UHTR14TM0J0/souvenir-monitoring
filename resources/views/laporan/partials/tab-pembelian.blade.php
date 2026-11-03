{{-- SUMMARY CARDS --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon summary-icon-blue">
                <i class="fas fa-truck"></i>
            </div>
            <div>
                <div class="summary-label">Total Pembelian</div>
                <div class="summary-value">Rp {{ number_format($total['pembelian'], 0, ',', '.') }}</div>
                <div class="summary-sub">Modal / HPP</div>
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
                <div class="summary-sub">Total pembelian</div>
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
                <div class="summary-sub">Dari supplier</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon summary-icon-purple">
                <i class="fas fa-box"></i>
            </div>
            <div>
                <div class="summary-label">Total Item Masuk</div>
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
            Grafik Pembelian
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
            Detail Transaksi Pembelian ({{ $rows->count() }} transaksi)
        </h6>
    </div>

    @if($rows->isEmpty())
        <div class="empty-state">
            <i class="fas fa-truck"></i>
            <div class="text">Belum ada transaksi pembelian di periode ini</div>
        </div>
    @else
    <div style="overflow-x: auto;">
        <table class="table-laporan">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th class="text-center">Item</th>
                    <th style="width: 140px; min-width: 140px;">Diskon</th>
                    <th style="width: 160px; min-width: 160px;">Grand Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $pbl)
                    <tr>
                        <td style="font-family: monospace; color: #2563eb; font-weight: 600;">
                            {{ $pbl->kode_pembelian }}
                        </td>
                        <td>{{ $pbl->tanggal->translatedFormat('d M Y') }}</td>
                        <td>{{ $pbl->supplier ?? '-' }}</td>
                        <td class="text-center">{{ $pbl->details->count() }}</td>
                        <td class="text-amber">
                            @if($pbl->total_diskon > 0)
                                Rp {{ number_format($pbl->total_diskon, 0, ',', '.') }}
                            @else
                                <span class="text-muted-cell">-</span>
                            @endif
                        </td>
                        <td class="text-navy">Rp {{ number_format($pbl->grand_total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">TOTAL</td>
                    <td class="text-navy">Rp {{ number_format($total['pembelian'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
</div>