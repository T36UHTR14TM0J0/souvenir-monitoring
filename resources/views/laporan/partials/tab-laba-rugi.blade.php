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
            <div class="summary-icon summary-icon-amber">
                <i class="fas fa-receipt"></i>
            </div>
            <div>
                <div class="summary-label">Total Pengeluaran</div>
                <div class="summary-value">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</div>
                <div class="summary-sub">Biaya operasional</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon {{ $total['laba_bersih'] >= 0 ? 'summary-icon-green' : 'summary-icon-red' }}">
                <i class="fas fa-{{ $total['laba_bersih'] >= 0 ? 'arrow-trend-up' : 'arrow-trend-down' }}"></i>
            </div>
            <div>
                <div class="summary-label">Laba Bersih</div>
                <div class="summary-value" style="color: {{ $total['laba_bersih'] >= 0 ? '#059669' : '#dc2626' }};">
                    Rp {{ number_format($total['laba_bersih'], 0, ',', '.') }}
                </div>
                <div class="summary-sub">Kotor: Rp {{ number_format($total['laba_kotor'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- TABLE --}}
<div class="panel">
    <div class="panel-header">
        <h6 class="panel-title">
            <i class="fas fa-table"></i>
            Rincian Laba/Rugi
        </h6>
    </div>

    <div style="overflow-x: auto;">
        <table class="table-laporan">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Penjualan</th>
                    <th>Pembelian</th>
                    <th>Laba Kotor</th>
                    <th>Pengeluaran</th>
                    <th>Laba Bersih</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td style="font-weight: 600; color: var(--gray-800);">{{ $row['label'] }}</td>
                        <td class="text-green">
                            @if($row['penjualan'] > 0)
                                Rp {{ number_format($row['penjualan'], 0, ',', '.') }}
                            @else
                                <span class="text-muted-cell">-</span>
                            @endif
                        </td>
                        <td class="text-navy">
                            @if($row['pembelian'] > 0)
                                Rp {{ number_format($row['pembelian'], 0, ',', '.') }}
                            @else
                                <span class="text-muted-cell">-</span>
                            @endif
                        </td>
                        <td class="{{ $row['laba_kotor'] >= 0 ? 'text-green' : 'text-red' }}">
                            @if($row['laba_kotor'] != 0)
                                Rp {{ number_format($row['laba_kotor'], 0, ',', '.') }}
                            @else
                                <span class="text-muted-cell">-</span>
                            @endif
                        </td>
                        <td class="text-amber">
                            @if($row['pengeluaran'] > 0)
                                Rp {{ number_format($row['pengeluaran'], 0, ',', '.') }}
                            @else
                                <span class="text-muted-cell">-</span>
                            @endif
                        </td>
                        <td class="{{ $row['laba_bersih'] >= 0 ? 'text-green' : 'text-red' }}">
                            @if($row['laba_bersih'] != 0)
                                Rp {{ number_format($row['laba_bersih'], 0, ',', '.') }}
                            @else
                                <span class="text-muted-cell">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>TOTAL</td>
                    <td class="text-green">Rp {{ number_format($total['penjualan'], 0, ',', '.') }}</td>
                    <td class="text-navy">Rp {{ number_format($total['pembelian'], 0, ',', '.') }}</td>
                    <td class="{{ $total['laba_kotor'] >= 0 ? 'text-green' : 'text-red' }}">
                        Rp {{ number_format($total['laba_kotor'], 0, ',', '.') }}
                    </td>
                    <td class="text-amber">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</td>
                    <td class="{{ $total['laba_bersih'] >= 0 ? 'text-green' : 'text-red' }}">
                        Rp {{ number_format($total['laba_bersih'], 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- CHART --}}
<div class="panel">
    <div class="panel-header">
        <h6 class="panel-title">
            <i class="fas fa-chart-bar"></i>
            Grafik Laba/Rugi
        </h6>
    </div>
    <div class="panel-body-padded">
        <div class="chart-container">
            <canvas id="chartLaporan"></canvas>
        </div>
    </div>
</div>