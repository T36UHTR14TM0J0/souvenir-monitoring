{{-- GRAFIK MODAL VS PRIVE --}}
<div class="card card-modern bg-white mb-3">
    <div class="card-header bg-white py-3 px-3 border-bottom d-flex align-items-center gap-2">
        <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #eef2ff; color: #26476f;">
            <i class="fas fa-chart-column"></i>
        </span>
        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Grafik Perbandingan: Setoran Modal, Pencairan/Pendapatan Lain vs Prive</h6>
    </div>
    <div class="card-body p-3">
        <div class="chart-container">
            <canvas id="chartLaporan"></canvas>
        </div>
    </div>
</div>

{{-- TABEL BREAKDOWN PER BULAN --}}
<div class="card card-modern bg-white mb-3">
    <div class="card-header bg-white py-3 px-3 border-bottom d-flex align-items-center gap-2">
        <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #ecfdf5; color: #059669;">
            <i class="fas fa-calendar-alt"></i>
        </span>
        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Rekonsiliasi Kas Masuk &amp; Prive Per Bulan</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-produk align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Bulan</th>
                        <th class="text-end">Modal Disetor (Owner)</th>
                        <th class="text-end">Pendapatan Lain / Pencairan</th>
                        <th class="text-end">Prive Ditarik (Pribadi)</th>
                        <th class="text-end">Selisih Netto</th>
                        <th class="text-center pe-4">Status Arus Modal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $r)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $r['bulan_label'] }}</td>
                            <td class="text-end fw-semibold text-success font-monospace">
                                {{ $r['modal'] > 0 ? 'Rp ' . number_format($r['modal'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-end fw-semibold font-monospace" style="color: #0284c7;">
                                {{ ($r['pendapatan_lain'] ?? 0) > 0 ? 'Rp ' . number_format($r['pendapatan_lain'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-end fw-semibold text-danger font-monospace">
                                {{ $r['prive'] > 0 ? 'Rp ' . number_format($r['prive'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-end fw-bold font-monospace {{ $r['selisih'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $r['selisih'] != 0 ? 'Rp ' . number_format($r['selisih'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-center pe-4">
                                @if($r['modal'] == 0 && ($r['pendapatan_lain'] ?? 0) == 0 && $r['prive'] == 0)
                                    <span class="badge bg-light text-secondary border">Tidak Ada Transaksi</span>
                                @elseif($r['selisih'] > 0)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Kas Masuk Lebih Besar</span>
                                @elseif($r['selisih'] < 0)
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Penarikan Prive Lebih Besar</span>
                                @else
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">Seimbang</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Tidak ada data bulan yang sesuai</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td class="ps-4">TOTAL AKUMULASI</td>
                        <td class="text-end text-success font-monospace">Rp {{ number_format($total['modal_disetor'], 0, ',', '.') }}</td>
                        <td class="text-end font-monospace" style="color: #0284c7;">Rp {{ number_format($total['pendapatan_lain'] ?? 0, 0, ',', '.') }}</td>
                        <td class="text-end text-danger font-monospace">Rp {{ number_format($total['prive_ditarik'], 0, ',', '.') }}</td>
                        <td class="text-end font-monospace {{ $total['saldo_netto'] >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($total['saldo_netto'], 0, ',', '.') }}
                        </td>
                        <td class="text-center pe-4">
                            <strong>{{ $total['saldo_netto'] >= 0 ? 'Modal Mengendap' : 'Prive Lebih Besar' }}</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- DAFTAR RIWAYAT KAS MASUK --}}
@if(isset($list_modal) && $list_modal->isNotEmpty())
<div class="card card-modern bg-white">
    <div class="card-header bg-white py-3 px-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; background: #ecfdf5; color: #059669;">
                <i class="fas fa-hand-holding-dollar"></i>
            </span>
            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Riwayat Setoran Modal &amp; Pencairan Dana Masuk</h6>
        </div>
        <a href="{{ route('kas-masuk.index') }}" class="btn btn-primary-custom" style="height: 34px; font-size: 0.76rem; padding: 0 0.85rem;">
            <i class="fas fa-hand-holding-dollar"></i>
            <span>Kelola Kas Masuk</span>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-produk align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Tanggal</th>
                        <th>Tipe Kas Masuk</th>
                        <th>Sumber Dana</th>
                        <th>Keterangan</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-center pe-4 col-freeze-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list_modal as $km)
                        <tr>
                            <td class="ps-4"><span class="code-badge">{{ $km->kode_transaksi }}</span></td>
                            <td>{{ $km->tanggal->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $km->tipe === 'modal_pemilik' ? 'bg-primary-subtle text-primary border-primary-subtle' : 'bg-info-subtle text-info border-info-subtle' }} border">
                                    {{ $km->tipe_label }}
                                </span>
                            </td>
                            <td><span class="badge bg-success-subtle text-success border border-success-subtle">{{ $km->sumber }}</span></td>
                            <td>{{ $km->keterangan }}</td>
                            <td class="text-end fw-bold text-success font-monospace">Rp {{ number_format($km->jumlah, 0, ',', '.') }}</td>
                            <td class="text-center pe-4 col-freeze-aksi">
                                <a href="{{ route('kas-masuk.edit', $km->id) }}" class="btn-action-icon btn-action-edit" title="Edit Kas Masuk">
                                    <i class="fas fa-pen"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
