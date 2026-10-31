{{-- SUMMARY CARDS --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon summary-icon-green">
                <i class="fas fa-arrow-down"></i>
            </div>
            <div>
                <div class="summary-label">Modal Disetor (Debit)</div>
                <div class="summary-value">Rp {{ number_format($total['modal_disetor'], 0, ',', '.') }}</div>
                <div class="summary-sub">Uang Pribadi Masuk ke Kas Toko</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon summary-icon-red">
                <i class="fas fa-arrow-up"></i>
            </div>
            <div>
                <div class="summary-label">Prive Ditarik</div>
                <div class="summary-value">Rp {{ number_format($total['prive_ditarik'], 0, ',', '.') }}</div>
                <div class="summary-sub">Uang Toko Terpakai Pribadi</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon {{ $total['saldo_netto'] >= 0 ? 'summary-icon-blue' : 'summary-icon-amber' }}">
                <i class="fas fa-scale-balanced"></i>
            </div>
            <div>
                <div class="summary-label">Saldo Netto Pemilik</div>
                <div class="summary-value {{ $total['saldo_netto'] >= 0 ? 'text-green' : 'text-red' }}">
                    Rp {{ number_format($total['saldo_netto'], 0, ',', '.') }}
                </div>
                <div class="summary-sub">
                    {{ $total['saldo_netto'] >= 0 ? 'Modal Anda Mengendap di Toko' : 'Prive Melebihi Modal Disetor' }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="summary-card">
            <div class="summary-icon summary-icon-amber">
                <i class="fas fa-hand-holding-dollar"></i>
            </div>
            <div>
                <div class="summary-label">Kasbon Belum Lunas</div>
                <div class="summary-value">Rp {{ number_format($total['kasbon_aktif'], 0, ',', '.') }}</div>
                <div class="summary-sub">Piutang Tim Berjalan</div>
            </div>
        </div>
    </div>
</div>

{{-- GRAFIK MODAL VS PRIVE --}}
<div class="panel mb-3">
    <div class="panel-header">
        <h6 class="panel-title">
            <i class="fas fa-chart-column"></i>
            Grafik Perbandingan: Modal Disetor (Kas Masuk) vs Prive Ditarik (Pengeluaran Pribadi)
        </h6>
    </div>
    <div class="panel-body-padded">
        <div class="chart-container">
            <canvas id="chartLaporan"></canvas>
        </div>
    </div>
</div>

{{-- TABEL BREAKDOWN PER BULAN --}}
<div class="panel mb-3">
    <div class="panel-header">
        <h6 class="panel-title">
            <i class="fas fa-calendar-alt"></i>
            Rekonsiliasi Modal vs Prive Per Bulan
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table-laporan">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th style="text-align: right;">Modal Disetor (Debit)</th>
                    <th style="text-align: right;">Prive Ditarik (Pribadi)</th>
                    <th style="text-align: right;">Selisih Netto Bulan Ini</th>
                    <th style="text-align: center;">Status Arus Modal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $r)
                    <tr>
                        <td style="font-weight: 600;">{{ $r['bulan_label'] }}</td>
                        <td class="text-green">
                            {{ $r['modal'] > 0 ? 'Rp ' . number_format($r['modal'], 0, ',', '.') : '-' }}
                        </td>
                        <td class="text-red">
                            {{ $r['prive'] > 0 ? 'Rp ' . number_format($r['prive'], 0, ',', '.') : '-' }}
                        </td>
                        <td class="{{ $r['selisih'] >= 0 ? 'text-green' : 'text-red' }}" style="font-weight: 700;">
                            {{ $r['selisih'] != 0 ? 'Rp ' . number_format($r['selisih'], 0, ',', '.') : '-' }}
                        </td>
                        <td style="text-align: center;">
                            @if($r['modal'] == 0 && $r['prive'] == 0)
                                <span class="badge-soft" style="background:#f3f4f6; color:#6b7280;">Tidak Ada Transaksi</span>
                            @elseif($r['selisih'] > 0)
                                <span class="badge-soft success">Suntik Modal Lebih Besar</span>
                            @elseif($r['selisih'] < 0)
                                <span class="badge-soft warning">Penarikan Prive Lebih Besar</span>
                            @else
                                <span class="badge-soft" style="background:#eff6ff; color:#1d4ed8;">Seimbang (Nol)</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>TOTAL AKUMULASI</td>
                    <td class="text-green">Rp {{ number_format($total['modal_disetor'], 0, ',', '.') }}</td>
                    <td class="text-red">Rp {{ number_format($total['prive_ditarik'], 0, ',', '.') }}</td>
                    <td class="{{ $total['saldo_netto'] >= 0 ? 'text-green' : 'text-red' }}">
                        Rp {{ number_format($total['saldo_netto'], 0, ',', '.') }}
                    </td>
                    <td style="text-align: center;">
                        <strong>{{ $total['saldo_netto'] >= 0 ? 'Modal Mengendap' : 'Prive Lebih Besar' }}</strong>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- DAFTAR SETORAN MODAL --}}
@if(isset($list_modal) && $list_modal->isNotEmpty())
<div class="panel">
    <div class="panel-header">
        <h6 class="panel-title">
            <i class="fas fa-hand-holding-dollar"></i>
            Riwayat Setoran Kas / Modal Pemilik (Debit)
        </h6>
        <a href="{{ route('kas-masuk.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:0.75rem;">
            Lihat Semua Kas Masuk
        </a>
    </div>
    <div class="table-responsive">
        <table class="table-laporan">
            <thead>
                <tr>
                    <th style="width: 140px;">Kode</th>
                    <th style="width: 110px;">Tanggal</th>
                    <th style="width: 180px;">Sumber Pembayaran</th>
                    <th>Keterangan</th>
                    <th style="width: 150px; text-align: right;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list_modal as $km)
                    <tr>
                        <td style="font-family: monospace; font-size: 0.75rem; font-weight: 600;">
                            {{ $km->kode_transaksi }}
                        </td>
                        <td>{{ $km->tanggal->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge-soft success">{{ $km->sumber }}</span>
                        </td>
                        <td>{{ $km->keterangan }}</td>
                        <td class="text-green" style="font-weight: 700;">
                            Rp {{ number_format($km->jumlah, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
