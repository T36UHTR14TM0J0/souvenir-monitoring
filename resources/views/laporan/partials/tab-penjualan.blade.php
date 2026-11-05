{{-- BREAKDOWN PER MARKETPLACE CHANNEL + CHART --}}
<div class="row g-3 mb-3">
    <div class="col-12 col-lg-7">
        <div class="card card-modern bg-white h-100">
            <div class="card-header bg-white py-3 px-3 border-bottom d-flex align-items-center gap-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #ecfdf5; color: #059669;">
                    <i class="fas fa-chart-line"></i>
                </span>
                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Tren Omzet Penjualan Marketplace</h6>
            </div>
            <div class="card-body p-3">
                <div class="chart-container">
                    <canvas id="chartLaporan"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card card-modern bg-white h-100">
            <div class="card-header bg-white py-3 px-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #eef2ff; color: #26476f;">
                        <i class="fas fa-store"></i>
                    </span>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Performa per Channel Marketplace</h6>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-produk align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Channel</th>
                                <th class="text-center">Order</th>
                                <th class="text-end">Omzet Kotor</th>
                                <th class="text-end pe-3">Dana Bersih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($per_channel as $ch)
                                <tr>
                                    <td class="ps-3 fw-bold">
                                        <span class="badge bg-{{ $ch['badge'] }}-subtle text-{{ $ch['badge'] }} border border-{{ $ch['badge'] }}-subtle px-2 py-1">
                                            {{ $ch['label'] }}
                                        </span>
                                        <div class="small text-muted mt-1" style="font-size: 0.7rem;">{{ $ch['item'] }} pcs ({{ $ch['persen_omzet'] }}%)</div>
                                    </td>
                                    <td class="text-center fw-semibold">{{ $ch['transaksi'] }}x</td>
                                    <td class="text-end font-monospace">
                                        <div class="fw-semibold">Rp {{ number_format($ch['omzet_kotor'], 0, ',', '.') }}</div>
                                        @if($ch['biaya_admin'] > 0)
                                            <small class="text-danger" style="font-size: 0.68rem;">Fee: -Rp {{ number_format($ch['biaya_admin'], 0, ',', '.') }}</small>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3 fw-bold text-success font-monospace">
                                        Rp {{ number_format($ch['penghasilan_bersih'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TABEL DETAIL TRANSAKSI PENJUALAN --}}
<div class="card card-modern bg-white">
    <div class="card-header bg-white py-3 px-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #eef2ff; color: #26476f;">
                <i class="fas fa-receipt"></i>
            </span>
            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">
                Rincian Pesanan Marketplace ({{ $rows->count() }} Transaksi)
            </h6>
        </div>
        <a href="{{ route('transaksi-penjualan.index') }}" class="btn btn-primary-custom" style="height: 34px; font-size: 0.76rem; padding: 0 0.85rem;">
            <i class="fas fa-boxes-packing"></i>
            <span>Kelola Pesanan</span>
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-produk align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Kode &amp; No. Pesanan</th>
                        <th>Tanggal</th>
                        <th>Channel</th>
                        <th>Pelanggan &amp; Kurir</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Omzet Kotor</th>
                        <th class="text-end">Fee Admin</th>
                        <th class="text-end">Dana Bersih</th>
                        <th class="text-center pe-4 col-freeze-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $trx)
                        @php
                            $isCancelled = in_array($trx->status_pesanan, ['batal', 'retur'], true);
                            $danaBersih = (float) $trx->grand_total - (float) $trx->biaya_admin_marketplace;
                        @endphp
                        <tr class="{{ $isCancelled ? 'opacity-75' : '' }}">
                            <td class="ps-4">
                                <span class="code-badge">{{ $trx->kode_transaksi }}</span>
                                @if($trx->no_pesanan_marketplace)
                                    <div class="small text-muted mt-1" style="font-size: 0.7rem;">MP: {{ $trx->no_pesanan_marketplace }}</div>
                                @endif
                            </td>
                            <td>{{ $trx->tanggal->translatedFormat('d M Y') }}</td>
                            <td>
                                <span class="badge bg-light text-dark border text-uppercase">
                                    {{ $trx->channel_label }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $trx->nama_pelanggan ?: '-' }}</div>
                                @if($trx->kurir || $trx->no_resi)
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $trx->kurir }} {{ $trx->no_resi ? '• '.$trx->no_resi : '' }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                @php $statusCfg = $trx->status_pesanan_badge_config; @endphp
                                <span class="badge" style="background: {{ $statusCfg['bg'] }}; color: {{ $statusCfg['color'] }}; border: 1px solid {{ $statusCfg['border'] }};">
                                    {{ $trx->status_pesanan_label }}
                                </span>
                            </td>
                            <td class="text-center fw-bold">{{ $trx->details->sum('qty') }} pcs</td>
                            <td class="text-end font-monospace fw-semibold {{ $isCancelled ? 'text-decoration-line-through text-muted' : 'text-dark' }}">
                                Rp {{ number_format($trx->grand_total, 0, ',', '.') }}
                            </td>
                            <td class="text-end font-monospace text-danger">
                                {{ $trx->biaya_admin_marketplace > 0 ? '-Rp ' . number_format($trx->biaya_admin_marketplace, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-end font-monospace fw-bold {{ $isCancelled ? 'text-decoration-line-through text-danger' : 'text-success' }}">
                                Rp {{ number_format($danaBersih, 0, ',', '.') }}
                            </td>
                            <td class="text-center pe-4 col-freeze-aksi">
                                <a href="{{ route('transaksi-penjualan.show', $trx->id) }}" class="btn-action-icon btn-action-view" title="Lihat Detail Pesanan">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="fas fa-shopping-bag fa-2x mb-2 d-block opacity-50"></i>
                                Belum ada transaksi penjualan pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($rows->isNotEmpty())
                    <tfoot>
                        <tr>
                            <td colspan="5" class="ps-4">TOTAL PESANAN VALID (TIDAK TERMASUK BATAL/RETUR)</td>
                            <td class="text-center">{{ number_format($total['item'], 0, ',', '.') }} pcs</td>
                            <td class="text-end font-monospace">Rp {{ number_format($total['penjualan'], 0, ',', '.') }}</td>
                            <td class="text-end font-monospace text-danger">-Rp {{ number_format($total['biaya_admin'] ?? 0, 0, ',', '.') }}</td>
                            <td class="text-end font-monospace text-success">Rp {{ number_format($total['penghasilan_bersih'] ?? $total['penjualan'], 0, ',', '.') }}</td>
                            <td class="col-freeze-aksi"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
