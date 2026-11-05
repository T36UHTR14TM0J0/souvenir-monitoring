{{-- TABEL RINCIAN LABA RUGI --}}
<div class="card card-modern bg-white mb-3">
    <div class="card-header bg-white py-3 px-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #eef2ff; color: #26476f;">
                <i class="fas fa-table"></i>
            </span>
            <div>
                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Rincian Perhitungan Laba/Rugi Operasional</h6>
                <small class="text-muted" style="font-size: 0.72rem;">Pesanan berstatus Batal &amp; Retur otomatis dikecualikan dari Omzet &amp; Penghasilan Bersih</small>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-produk align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Periode</th>
                        <th class="text-end">Omzet Kotor</th>
                        <th class="text-end">Potongan Admin MP</th>
                        <th class="text-end">Penghasilan Bersih</th>
                        <th class="text-end">Belanja Bahan (HPP)</th>
                        <th class="text-end">Laba Kotor</th>
                        <th class="text-end">Biaya Bisnis &amp; Ads</th>
                        <th class="text-end pe-4">Laba Bersih</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">
                                {{ $row['label'] }}
                                @if(($row['count_batal_retur'] ?? 0) > 0)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle ms-1" style="font-size: 0.65rem;">
                                        {{ $row['count_batal_retur'] }} Batal/Retur
                                    </span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold text-dark font-monospace">
                                {{ $row['penjualan'] > 0 ? 'Rp ' . number_format($row['penjualan'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-end text-danger font-monospace">
                                {{ ($row['biaya_admin'] ?? 0) > 0 ? '-Rp ' . number_format($row['biaya_admin'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-end fw-bold text-success font-monospace">
                                @if(($row['penghasilan_bersih'] ?? 0) > 0 || ($row['pendapatan_lain'] ?? 0) > 0)
                                    Rp {{ number_format($row['penghasilan_bersih'] ?? $row['penjualan'], 0, ',', '.') }}
                                    @if(($row['pendapatan_lain'] ?? 0) > 0)
                                        <div class="small text-primary" style="font-size: 0.68rem;">+Lain: Rp {{ number_format($row['pendapatan_lain'], 0, ',', '.') }}</div>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end fw-semibold font-monospace" style="color: #0284c7;">
                                {{ $row['pembelian'] > 0 ? 'Rp ' . number_format($row['pembelian'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-end fw-bold font-monospace {{ $row['laba_kotor'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $row['laba_kotor'] != 0 ? 'Rp ' . number_format($row['laba_kotor'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-end fw-semibold font-monospace" style="color: #d97706;">
                                @if($row['pengeluaran'] > 0)
                                    Rp {{ number_format($row['pengeluaran'], 0, ',', '.') }}
                                    @if(($row['biaya_iklan'] ?? 0) > 0)
                                        <div class="small text-muted" style="font-size: 0.68rem;">Ads: Rp {{ number_format($row['biaya_iklan'], 0, ',', '.') }}</div>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end pe-4 fw-bold font-monospace {{ $row['laba_bersih'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $row['laba_bersih'] != 0 ? 'Rp ' . number_format($row['laba_bersih'], 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-chart-line fa-2x mb-2 d-block opacity-50"></i>
                                Tidak ada data periode yang cocok dengan pencarian Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td class="ps-4">TOTAL AKUMULASI</td>
                        <td class="text-end font-monospace">Rp {{ number_format($total['penjualan'], 0, ',', '.') }}</td>
                        <td class="text-end text-danger font-monospace">-Rp {{ number_format($total['biaya_admin'] ?? 0, 0, ',', '.') }}</td>
                        <td class="text-end text-success font-monospace">Rp {{ number_format($total['penghasilan_bersih'] ?? $total['penjualan'], 0, ',', '.') }}</td>
                        <td class="text-end font-monospace" style="color: #0284c7;">Rp {{ number_format($total['pembelian'], 0, ',', '.') }}</td>
                        <td class="text-end font-monospace {{ $total['laba_kotor'] >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($total['laba_kotor'], 0, ',', '.') }}
                        </td>
                        <td class="text-end font-monospace" style="color: #d97706;">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</td>
                        <td class="text-end pe-4 font-monospace {{ $total['laba_bersih'] >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($total['laba_bersih'], 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- GRAFIK LABA RUGI --}}
<div class="card card-modern bg-white">
    <div class="card-header bg-white py-3 px-3 border-bottom d-flex align-items-center gap-2">
        <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #ecfdf5; color: #059669;">
            <i class="fas fa-chart-column"></i>
        </span>
        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Grafik Perbandingan Penghasilan Bersih, HPP, Biaya Bisnis &amp; Laba Bersih</h6>
    </div>
    <div class="card-body p-3">
        <div class="chart-container">
            <canvas id="chartLaporan"></canvas>
        </div>
    </div>
</div>
