{{-- CHART + PER KATEGORI --}}
<div class="row g-3 mb-3">
    <div class="col-12 col-lg-8">
        <div class="card card-modern bg-white h-100">
            <div class="card-header bg-white py-3 px-3 border-bottom d-flex align-items-center gap-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #fef2f2; color: #dc2626;">
                    <i class="fas fa-chart-line"></i>
                </span>
                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Grafik Pengeluaran Operasional &amp; Iklan Toko</h6>
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
                    <i class="fas fa-chart-pie"></i>
                </span>
                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Breakdown Kategori Bisnis</h6>
            </div>
            <div class="card-body p-3">
                @if($total['per_kategori']->isEmpty())
                    <div class="text-center py-4 text-muted small">
                        <i class="fas fa-chart-pie fa-2x mb-2 d-block opacity-50"></i>
                        Belum ada pengeluaran bisnis
                    </div>
                @else
                    <div class="d-flex flex-column gap-2">
                        @foreach($total['per_kategori'] as $pk)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="font-size: 0.8rem;">
                                <div>
                                    <div class="fw-semibold text-dark">{{ $pk['kategori'] }}</div>
                                    <small class="text-muted">{{ $pk['count'] }} transaksi</small>
                                </div>
                                <div class="fw-bold text-danger font-monospace">Rp {{ number_format($pk['total'], 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- TABEL DETAIL PENGELUARAN --}}
<div class="card card-modern bg-white">
    <div class="card-header bg-white py-3 px-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #eef2ff; color: #26476f;">
                <i class="fas fa-table"></i>
            </span>
            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">
                Detail Pengeluaran Operasional &amp; Ads ({{ $rows->count() }} Data)
            </h6>
        </div>
        <a href="{{ route('pengeluaran.index') }}" class="btn btn-primary-custom" style="height: 34px; font-size: 0.76rem; padding: 0 0.85rem;">
            <i class="fas fa-credit-card"></i>
            <span>Kelola Pengeluaran</span>
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-produk align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Tanggal</th>
                        <th>Kategori Bisnis</th>
                        <th>Keterangan</th>
                        <th class="text-end">Nominal</th>
                        <th class="text-center pe-4 col-freeze-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $pgl)
                        <tr>
                            <td class="ps-4">
                                <span class="code-badge">{{ $pgl->kode_pengeluaran }}</span>
                            </td>
                            <td>{{ $pgl->tanggal->translatedFormat('d M Y') }}</td>
                            <td>
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                    {{ $pgl->kategori_label }}
                                </span>
                            </td>
                            <td class="text-dark">{{ $pgl->keterangan }}</td>
                            <td class="text-end fw-bold text-danger font-monospace">Rp {{ number_format($pgl->jumlah, 0, ',', '.') }}</td>
                            <td class="text-center pe-4 col-freeze-aksi">
                                <a href="{{ route('pengeluaran.show', $pgl->id) }}" class="btn-action-icon btn-action-view" title="Lihat Detail Pengeluaran">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-receipt fa-2x mb-2 d-block opacity-50"></i>
                                Belum ada data pengeluaran operasional di periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($rows->isNotEmpty())
                    <tfoot>
                        <tr>
                            <td colspan="4" class="ps-4">TOTAL BIAYA OPERASIONAL &amp; ADS</td>
                            <td class="text-end text-danger font-monospace">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</td>
                            <td class="col-freeze-aksi"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
