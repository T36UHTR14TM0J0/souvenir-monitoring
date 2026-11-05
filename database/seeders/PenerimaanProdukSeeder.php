<?php

namespace Database\Seeders;

use App\Models\PemakaianBahan;
use App\Models\PenerimaanProduk;
use App\Models\PenerimaanProdukDetail;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenerimaanProdukSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@gmail.com')->first() ?? User::first();
        if (! $admin) {
            return;
        }

        $souvenirs = Produk::where('jenis', 'souvenir_jadi')->with('satuan')->get();
        if ($souvenirs->isEmpty()) {
            return;
        }

        $qcLeaders = [
            'Ahmad Fauzi (Supervisor QC)',
            'Hendri Gunawan (Kepala Workshop)',
            'Dewi Lestari (QC & Packaging Lead)',
            'Bayu Pratama (Supervisor Finishing)',
        ];

        // 22 Bulan: Jan 2025 s.d. Des 2025 (12 bulan) & Jan 2026 s.d. Okt 2026 (10 bulan)
        $periods = [];
        for ($m = 1; $m <= 12; $m++) {
            $periods[] = ['year' => 2025, 'month' => $m];
        }
        for ($m = 1; $m <= 10; $m++) {
            $periods[] = ['year' => 2026, 'month' => $m];
        }

        $counter = 1;

        foreach ($periods as $p) {
            $year = $p['year'];
            $month = $p['month'];

            // Ambil referensi pemakaian bahan pada bulan yang sama jika ada
            $pemakaianBulanIni = PemakaianBahan::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->pluck('kode_pemakaian')
                ->toArray();

            $batchCount = rand(2, 3);

            for ($b = 0; $b < $batchCount; $b++) {
                $day = rand(5, 27);
                $tanggal = Carbon::create($year, $month, $day, rand(13, 18), rand(0, 59));
                $qcLeader = $qcLeaders[array_rand($qcLeaders)];
                $tipe = ($counter % 3 === 0) ? 'pesanan_custom' : 'ready_stock';
                $refPemakaian = ! empty($pemakaianBulanIni)
                    ? $pemakaianBulanIni[array_rand($pemakaianBulanIni)]
                    : null;

                $kodePenerimaan = 'HPD-'.$tanggal->format('Ymd').'-'.str_pad($counter++, 4, '0', STR_PAD_LEFT);

                DB::beginTransaction();
                try {
                    $selectedProduk = $souvenirs->random(min(rand(2, 3), $souvenirs->count()));

                    $penerimaan = PenerimaanProduk::create([
                        'kode_penerimaan' => $kodePenerimaan,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'tipe_produksi' => $tipe,
                        'nama_penanggung_jawab' => $qcLeader,
                        'no_referensi_pemakaian' => $refPemakaian,
                        'catatan' => ($counter % 4 === 0) ? 'Batch produksi lolos inspeksi warna sublimasi & ketahanan laminasi.' : null,
                        'total_item' => $selectedProduk->count(),
                        'total_bagus' => 0,
                        'total_reject' => 0,
                        'user_id' => $admin->id,
                        'created_at' => $tanggal,
                        'updated_at' => $tanggal,
                    ]);

                    $sumBagus = 0;
                    $sumReject = 0;

                    foreach ($selectedProduk as $prod) {
                        $qtyBagus = rand(25, 65);
                        $qtyReject = rand(0, 3);

                        PenerimaanProdukDetail::create([
                            'penerimaan_produk_id' => $penerimaan->id,
                            'produk_id' => $prod->id,
                            'nama_produk' => $prod->nama_produk,
                            'qty_bagus' => $qtyBagus,
                            'qty_reject' => $qtyReject,
                            'satuan' => $prod->satuan->singkatan ?? 'pcs',
                            'keterangan' => $qtyReject > 0
                                ? "Lolos QC {$qtyBagus} pcs, reject {$qtyReject} pcs (defect sablon/gores)"
                                : 'Lolos uji QC 100% tanpa cacat',
                            'created_at' => $tanggal,
                            'updated_at' => $tanggal,
                        ]);

                        $sumBagus += $qtyBagus;
                        $sumReject += $qtyReject;

                        $prod->increment('stok', $qtyBagus);
                    }

                    $penerimaan->update([
                        'total_bagus' => $sumBagus,
                        'total_reject' => $sumReject,
                    ]);

                    DB::commit();
                } catch (\Throwable $e) {
                    DB::rollBack();
                }
            }
        }

        $this->command->info('PenerimaanProdukSeeder: Data hasil produksi masuk & QC (Jan 2025 - Des 2025 & Jan 2026 - Okt 2026) berhasil di-seed.');
    }
}
