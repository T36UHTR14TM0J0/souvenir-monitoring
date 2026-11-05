{{-- GRAFIK HASIL PRODUKSI VS REJECT QC --}}
<div class="card card-modern bg-white mb-3">
    <div class="card-header bg-white py-3 px-3 border-bottom d-flex align-items-center gap-2">
        <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #ecfdf5; color: #059669;">
            <i class="fas fa-chart-column"></i>
        </span>
        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Grafik Output Produksi: Lolos QC vs Reject QC</h6>
    </div>
    <div class="card-body p-3">
        <div class="chart-container">
            <canvas id="chartLaporan"></canvas>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- TABEL HASIL PRODUKSI MASUK --}}
    <div class="col-12 col-xl-6">
        <div class="card card-modern bg-white h-100">
            <div class="card-header bg-white py-3 px-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #ecfdf5; color: #059669;">
                        <i class="fas fa-boxes-packing"></i>
                    </span>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Hasil Produksi Masuk ({{ $rows->count() }} Batch)</h6>
                </div>
                <a href="{{ route('penerimaan-produk.index') }}" class="btn btn-primary-custom" style="height: 34px; font-size: 0.74rem; padding: 0 0.75rem;">
                    <i class="fas fa-boxes-stacked"></i>
                    <span>Kelola Produksi</span>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-produk align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Kode</th>
                                <th>Tanggal</th>
                                <th>Petugas QC</th>
                                <th class="text-center">Lolos QC</th>
                                <th class="text-center">Reject</th>
                                <th class="text-center pe-3 col-freeze-aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $prd)
                                <tr>
                                    <td class="ps-3"><span class="code-badge">{{ $prd->kode_penerimaan }}</span></td>
                                    <td>{{ $prd->tanggal->format('d/m/Y') }}</td>
                                    <td class="fw-semibold">{{ $prd->nama_petugas ?: '-' }}</td>
                                    <td class="text-center fw-bold text-success">+{{ number_format($prd->total_qty_bagus, 0, ',', '.') }}</td>
                                    <td class="text-center fw-bold {{ $prd->total_qty_reject > 0 ? 'text-danger' : 'text-muted' }}">
                                        {{ $prd->total_qty_reject > 0 ? number_format($prd->total_qty_reject, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="text-center pe-3 col-freeze-aksi">
                                        <a href="{{ route('penerimaan-produk.show', $prd->id) }}" class="btn-action-icon btn-action-view" title="Lihat Detail Produksi">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data penerimaan hasil produksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL PEMAKAIAN BAHAN BAKU --}}
    <div class="col-12 col-xl-6">
        <div class="card card-modern bg-white h-100">
            <div class="card-header bg-white py-3 px-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-dolly"></i>
                    </span>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Pemakaian Bahan Baku ({{ $rows_pemakaian->count() }} Data)</h6>
                </div>
                <a href="{{ route('pemakaian-bahan.index') }}" class="btn btn-primary-custom" style="height: 34px; font-size: 0.74rem; padding: 0 0.75rem;">
                    <i class="fas fa-dolly"></i>
                    <span>Kelola Pemakaian</span>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-produk align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Kode</th>
                                <th>Tanggal</th>
                                <th>Operator &amp; Keperluan</th>
                                <th class="text-center">Total Qty</th>
                                <th class="text-end">Est. Nilai</th>
                                <th class="text-center pe-3 col-freeze-aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows_pemakaian as $pb)
                                @php
                                    $nilaiPb = $pb->details->sum(fn ($d) => (float) $d->qty * (float) ($d->produk->harga_beli ?? 0));
                                @endphp
                                <tr>
                                    <td class="ps-3"><span class="code-badge">{{ $pb->kode_pemakaian }}</span></td>
                                    <td>{{ $pb->tanggal->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $pb->nama_operator ?: '-' }}</div>
                                        <small class="text-muted">{{ $pb->keperluan }}</small>
                                    </td>
                                    <td class="text-center fw-bold" style="color: #0284c7;">{{ number_format($pb->total_kuantitas, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold font-monospace">Rp {{ number_format($nilaiPb, 0, ',', '.') }}</td>
                                    <td class="text-center pe-3 col-freeze-aksi">
                                        <a href="{{ route('pemakaian-bahan.show', $pb->id) }}" class="btn-action-icon btn-action-view" title="Lihat Detail Pemakaian Bahan">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada catatan pengambilan bahan baku</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
