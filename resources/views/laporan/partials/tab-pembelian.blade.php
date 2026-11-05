{{-- CHART + PER PLATFORM --}}
<div class="row g-3 mb-3">
    <div class="col-12 col-lg-8">
        <div class="card card-modern bg-white h-100">
            <div class="card-header bg-white py-3 px-3 border-bottom d-flex align-items-center gap-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #e0f2fe; color: #0284c7;">
                    <i class="fas fa-chart-line"></i>
                </span>
                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Tren Pembelian Bahan Baku &amp; Packaging</h6>
            </div>
            <div class="card-body p-3">
                <div class="chart-container">
                    <canvas id="chartLaporan"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card card-modern bg-white h-100">
            <div class="card-header bg-white py-3 px-3 border-bottom d-flex align-items-center gap-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #eef2ff; color: #26476f;">
                    <i class="fas fa-globe"></i>
                </span>
                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Rekap per Platform Belanja</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-produk align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Platform</th>
                                <th class="text-center">Trx</th>
                                <th class="text-end pe-3">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($per_platform as $pf)
                                <tr>
                                    <td class="ps-3 fw-semibold text-dark">{{ $pf['platform'] }}</td>
                                    <td class="text-center">{{ $pf['count'] }}x</td>
                                    <td class="text-end pe-3 fw-bold font-monospace" style="color: #0284c7;">
                                        Rp {{ number_format($pf['total'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada data platform</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TABEL DETAIL PEMBELIAN --}}
<div class="card card-modern bg-white">
    <div class="card-header bg-white py-3 px-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #eef2ff; color: #26476f;">
                <i class="fas fa-table"></i>
            </span>
            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">
                Detail Transaksi Pembelian Bahan Baku ({{ $rows->count() }} Transaksi)
            </h6>
        </div>
        <a href="{{ route('transaksi-pembelian.index') }}" class="btn btn-primary-custom" style="height: 34px; font-size: 0.76rem; padding: 0 0.85rem;">
            <i class="fas fa-truck-ramp-box"></i>
            <span>Kelola Pembelian</span>
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-produk align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Tanggal</th>
                        <th>Supplier / Toko</th>
                        <th>Platform</th>
                        <th class="text-center">Status Paket</th>
                        <th class="text-center">Qty Bahan</th>
                        <th class="text-end">Ongkir</th>
                        <th class="text-end">Diskon</th>
                        <th class="text-end">Grand Total</th>
                        <th class="text-center pe-4 col-freeze-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $pbl)
                        <tr>
                            <td class="ps-4">
                                <span class="code-badge">{{ $pbl->kode_pembelian }}</span>
                            </td>
                            <td>{{ $pbl->tanggal->translatedFormat('d M Y') }}</td>
                            <td class="fw-semibold text-dark">
                                {{ $pbl->supplier ?: '-' }}
                                @if($pbl->no_invoice_online)
                                    <div class="small text-muted" style="font-size: 0.7rem;">Order: {{ $pbl->no_invoice_online }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $pbl->platform_label }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $pbl->status_badge_class }}">
                                    {{ $pbl->status_label }}
                                </span>
                            </td>
                            <td class="text-center fw-bold">{{ $pbl->details->sum('qty') }}</td>
                            <td class="text-end text-secondary font-monospace">
                                {{ $pbl->ongkir > 0 ? 'Rp ' . number_format($pbl->ongkir, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-end text-warning font-monospace">
                                {{ $pbl->total_diskon > 0 ? 'Rp ' . number_format($pbl->total_diskon, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-end fw-bold font-monospace" style="color: #0284c7;">
                                Rp {{ number_format($pbl->grand_total, 0, ',', '.') }}
                            </td>
                            <td class="text-center pe-4 col-freeze-aksi">
                                <a href="{{ route('transaksi-pembelian.show', $pbl->id) }}" class="btn-action-icon btn-action-view" title="Lihat Detail Pembelian">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="fas fa-truck fa-2x mb-2 d-block opacity-50"></i>
                                Belum ada transaksi pembelian bahan baku pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($rows->isNotEmpty())
                    <tfoot>
                        <tr>
                            <td colspan="5" class="ps-4">TOTAL PEMBELIAN BAHAN BAKU</td>
                            <td class="text-center">{{ number_format($total['item'], 0, ',', '.') }}</td>
                            <td class="text-end font-monospace">Rp {{ number_format($total['ongkir'] ?? 0, 0, ',', '.') }}</td>
                            <td class="text-end font-monospace">Rp {{ number_format($total['diskon'], 0, ',', '.') }}</td>
                            <td class="text-end font-monospace" style="color: #0284c7;">Rp {{ number_format($total['pembelian'], 0, ',', '.') }}</td>
                            <td class="col-freeze-aksi"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
