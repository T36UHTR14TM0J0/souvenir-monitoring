{{-- SUMMARY CARDS --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon summary-icon-red">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <div class="summary-label">Total Pribadi / Prive</div>
                <div class="summary-value">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</div>
                <div class="summary-sub">Dibiayai dari Kas Toko</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon summary-icon-amber">
                <i class="fas fa-utensils"></i>
            </div>
            <div>
                <div class="summary-label">Makan / Lunch</div>
                <div class="summary-value">Rp {{ number_format($total['makan_pribadi'], 0, ',', '.') }}</div>
                <div class="summary-sub">Konsumsi Pribadi</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon summary-icon-navy">
                <i class="fas fa-house"></i>
            </div>
            <div>
                <div class="summary-label">Kebutuhan Rumah</div>
                <div class="summary-value">Rp {{ number_format($total['kebutuhan_rumah'], 0, ',', '.') }}</div>
                <div class="summary-sub">Gas, Galon, Dapur</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon summary-icon-green">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div>
                <div class="summary-label">Prive Penarikan</div>
                <div class="summary-value">Rp {{ number_format($total['prive_penarikan'], 0, ',', '.') }}</div>
                <div class="summary-sub">Tarik Tunai Pemilik</div>
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
                    Tren Pengeluaran Pribadi
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
                    Kategori Pribadi
                </h6>
            </div>
            <div class="panel-body-padded">
                @if($total['per_kategori']->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <div class="text">Belum ada data pengeluaran pribadi</div>
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($total['per_kategori'] as $pk)
                            @php
                                $pct = $total['pengeluaran'] > 0
                                    ? round(($pk['total'] / $total['pengeluaran']) * 100, 1)
                                    : 0;
                            @endphp
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span style="font-size: 0.75rem; font-weight: 600; color: var(--gray-800);">
                                        {{ $pk['kategori'] }}
                                    </span>
                                    <span style="font-size: 0.75rem; font-weight: 700; color: #dc2626;">
                                        Rp {{ number_format($pk['total'], 0, ',', '.') }}
                                        <small class="text-muted">({{ $pct }}%)</small>
                                    </span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-danger" role="progressbar"
                                         style="width: {{ $pct }}%"
                                         aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- TABEL RINCIAN PENGELUARAN PRIBADI --}}
<div class="panel">
    <div class="panel-header">
        <h6 class="panel-title">
            <i class="fas fa-list"></i>
            Rincian Transaksi Pengeluaran Pribadi
        </h6>
        <span class="badge-soft danger" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;">
            {{ $rows->count() }} Transaksi
        </span>
    </div>
    <div class="table-responsive">
        <table class="table-laporan">
            <thead>
                <tr>
                    <th style="width: 140px;">Kode</th>
                    <th style="width: 110px;">Tanggal</th>
                    <th style="width: 180px;">Kategori</th>
                    <th>Keterangan</th>
                    <th style="width: 150px; text-align: right;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $pgl)
                    <tr>
                        <td style="font-family: monospace; font-size: 0.75rem; font-weight: 600;">
                            {{ $pgl->kode_pengeluaran }}
                        </td>
                        <td>{{ $pgl->tanggal->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge-soft danger" style="background:#fee2e2; color:#991b1b; border:1px solid #fca5a5;">
                                {{ $pgl->kategori_label }}
                            </span>
                        </td>
                        <td>{{ $pgl->keterangan }}</td>
                        <td class="text-red">Rp {{ number_format($pgl->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <div class="text">Tidak ada pengeluaran pribadi pada periode ini.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">TOTAL</td>
                    <td class="text-red">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
