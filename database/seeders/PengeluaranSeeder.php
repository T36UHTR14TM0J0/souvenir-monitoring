<?php

namespace Database\Seeders;

use App\Models\Pengeluaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            $this->command->warn('⚠️ Tidak ada user untuk PengeluaranSeeder.');
            return;
        }

        // 22 Bulan: Jan 2025 s.d. Des 2025 (12 bulan) & Jan 2026 s.d. Okt 2026 (10 bulan)
        $periods = [];
        for ($m = 1; $m <= 12; $m++) {
            $periods[] = ['year' => 2025, 'month' => $m];
        }
        for ($m = 1; $m <= 10; $m++) {
            $periods[] = ['year' => 2026, 'month' => $m];
        }

        $pglCounter = 1;

        $karyawanList = ['Budi Santoso', 'Siti Aminah', 'Rian Pratama', 'Doni Kurniawan'];

        foreach ($periods as $p) {
            $year  = $p['year'];
            $month = $p['month'];

            // 1. PENGELUARAN BISNIS RUTIN BULANAN
            // A. Sewa Toko (tiap tgl 1)
            $tglSewa = Carbon::create($year, $month, 1, 10, 0);
            $this->createPengeluaran($pglCounter++, $tglSewa, 'bisnis', 'sewa', 'Biaya sewa tempat & ruko toko bulanan', 2000000, $user->id);

            // B. Listrik, Air & Kebersihan (tgl 5 - 8)
            $tglListrik = Carbon::create($year, $month, rand(5, 8), 11, 30);
            $this->createPengeluaran($pglCounter++, $tglListrik, 'bisnis', 'listrik', 'Tagihan listrik PLN, PDAM, dan retribusi toko', rand(450, 650) * 1000, $user->id);

            // C. Internet & Wi-Fi Toko (tgl 10 - 12)
            $tglInternet = Carbon::create($year, $month, rand(10, 12), 14, 0);
            $this->createPengeluaran($pglCounter++, $tglInternet, 'bisnis', 'operasional', 'Langganan internet fiber optik & POS system', 375000, $user->id);

            // D. Perlengkapan Packing (Bubble wrap, polymailer, selotip) (tgl 14 - 17)
            $tglPacking = Carbon::create($year, $month, rand(14, 17), 13, 15);
            $this->createPengeluaran($pglCounter++, $tglPacking, 'bisnis', 'perlengkapan', 'Restock bubble wrap, lakban, plastik zip, dan kardus packing', rand(150, 280) * 1000, $user->id);

            // E. Transportasi & Kirim Paket Ekspedisi (tgl 18 - 22)
            $tglTransport = Carbon::create($year, $month, rand(18, 22), 16, 45);
            $this->createPengeluaran($pglCounter++, $tglTransport, 'bisnis', 'transport', 'Bensin operasional & ongkos drop paket ke gerai J&T/SiCepat', rand(100, 220) * 1000, $user->id);

            // F. Gaji Karyawan Toko (tiap tgl 25 - 28)
            $tglGaji = Carbon::create($year, $month, rand(25, 28), 17, 0);
            $this->createPengeluaran($pglCounter++, $tglGaji, 'bisnis', 'gaji', 'Gaji pokok & uang makan staf kasir & penjaga toko', 3500000, $user->id);

            // G. Pemeliharaan berkala (sesekali tiap 3 bulan)
            if ($month % 3 === 0) {
                $tglMaint = Carbon::create($year, $month, rand(12, 20), 15, 30);
                $this->createPengeluaran($pglCounter++, $tglMaint, 'bisnis', 'pemeliharaan', 'Perawatan berkala AC toko & lampu penerangan display', rand(150, 350) * 1000, $user->id);
            }

            // 2. PENGELUARAN PRIBADI (PRIVE PEMILIK)
            // A. Penarikan Prive Bulanan
            $tglPrive = Carbon::create($year, $month, rand(18, 24), 12, 0);
            $this->createPengeluaran($pglCounter++, $tglPrive, 'pribadi', 'prive_penarikan', 'Penarikan prive modal/keuntungan pemilik toko', rand(1500, 2500) * 1000, $user->id);

            // B. Kebutuhan Rumah / Makan Pribadi (sesekali)
            if (rand(1, 100) <= 60) {
                $tglRumah = Carbon::create($year, $month, rand(7, 21), 13, 0);
                $this->createPengeluaran($pglCounter++, $tglRumah, 'pribadi', 'makan_pribadi', 'Makan siang & konsumsi pribadi owner', rand(50, 150) * 1000, $user->id);
            }

            // 3. KASBON KARYAWAN (Sesekali)
            if (rand(1, 100) <= 40) {
                $tglKasbon = Carbon::create($year, $month, rand(8, 22), 14, 30);
                $namaKaryawan = $karyawanList[array_rand($karyawanList)];
                
                // Di akhir 2026 (September & Oktober), ada kasbon yang sengaja belum lunas untuk testing laporan kasbon
                $isBelumLunas = ($year === 2026 && $month >= 9 && rand(1, 100) <= 65);
                $statusKasbon = $isBelumLunas ? 'belum_lunas' : 'lunas';

                $this->createPengeluaran(
                    $pglCounter++,
                    $tglKasbon,
                    'kasbon',
                    'kasbon_karyawan',
                    'Pinjaman kasbon ' . $namaKaryawan,
                    rand(300, 800) * 1000,
                    $user->id,
                    $namaKaryawan,
                    $statusKasbon
                );
            }
        }

        $this->command->info('✅ PengeluaranSeeder: ' . ($pglCounter - 1) . ' Data Pengeluaran (2025-2026) berhasil dibuat.');
    }

    private function createPengeluaran(
        int $counter,
        Carbon $tanggal,
        string $tipeAlokasi,
        string $kategori,
        string $keterangan,
        float $jumlah,
        int $userId,
        ?string $karyawanNama = null,
        ?string $statusKasbon = null
    ): void {
        $kode = 'PGL-' . $tanggal->format('Ymd') . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);

        $pengeluaran = Pengeluaran::create([
            'kode_pengeluaran' => $kode,
            'tanggal'          => $tanggal->format('Y-m-d'),
            'tipe_alokasi'     => $tipeAlokasi,
            'kategori'         => $kategori,
            'keterangan'       => $keterangan,
            'jumlah'           => $jumlah,
            'karyawan_nama'    => $karyawanNama,
            'status_kasbon'    => $statusKasbon,
            'catatan'          => null,
            'user_id'          => $userId,
        ]);

        $pengeluaran->created_at = $tanggal;
        $pengeluaran->updated_at = $tanggal;
        $pengeluaran->save();
    }
}
