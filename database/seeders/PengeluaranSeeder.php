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
        $user = User::where('email', 'bendahara@gmail.com')->first() ?? User::first();
        if (! $user) {
            $this->command->warn('Tidak ada user untuk PengeluaranSeeder.');

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
        $karyawanList = [
            'Agus Subekti (Produksi)',
            'Rian Pratama (Produksi)',
            'Siti Aminah (Admin Marketplace)',
            'Doni Kurniawan (Packing)',
        ];

        foreach ($periods as $p) {
            $year = $p['year'];
            $month = $p['month'];

            // 1. PENGELUARAN BISNIS RUTIN BULANAN
            // A. Sewa Tempat Workshop (tiap tgl 1)
            $tglSewa = Carbon::create($year, $month, 1, 10, 0);
            $this->createPengeluaran($pglCounter++, $tglSewa, 'bisnis', 'sewa', 'Biaya sewa workshop produksi & gudang packing bulanan', 1500000, $user->id);

            // B. Listrik & Air Workshop (mesin sublimasi, laser, press) (tgl 5 - 8)
            $tglListrik = Carbon::create($year, $month, rand(5, 8), 11, 30);
            $this->createPengeluaran($pglCounter++, $tglListrik, 'bisnis', 'listrik', 'Listrik PLN daya 3500VA untuk mesin press & workshop', rand(450, 650) * 1000, $user->id);

            // C. Internet Fiber Optik & Operasional Toko (tgl 10 - 12)
            $tglInternet = Carbon::create($year, $month, rand(10, 12), 14, 0);
            $this->createPengeluaran($pglCounter++, $tglInternet, 'bisnis', 'operasional', 'Internet fiber optik workshop & langganan aplikasi operasional', 350000, $user->id);

            // D. Biaya Iklan Marketplace (Shopee Ads, Lazada & TikTok Ads) (tgl 12 - 15)
            $tglIklan = Carbon::create($year, $month, rand(12, 15), 15, 0);
            $this->createPengeluaran($pglCounter++, $tglIklan, 'bisnis', 'iklan', 'Top-up saldo iklan Shopee Ads, Lazada Sponsored & TikTok Ads campaign', rand(650, 1250) * 1000, $user->id);

            // E. Perlengkapan Packing (Bubble wrap, lakban fragile, kardus die cut) (tgl 16 - 19)
            $tglPacking = Carbon::create($year, $month, rand(16, 19), 13, 15);
            $this->createPengeluaran($pglCounter++, $tglPacking, 'bisnis', 'packing', 'Restock bubble wrap tebal, lakban fragile, dan kardus die-cut', rand(250, 450) * 1000, $user->id);

            // F. Gaji Staf Produksi & Admin Marketplace (tgl 25 - 28)
            $tglGaji = Carbon::create($year, $month, rand(25, 28), 17, 0);
            $this->createPengeluaran($pglCounter++, $tglGaji, 'bisnis', 'gaji', 'Gaji bulanan tim produksi souvenir & admin marketplace', 3500000, $user->id);

            // G. Pemeliharaan Mesin & Laser (tiap 3 bulan)
            if ($month % 3 === 0) {
                $tglMaint = Carbon::create($year, $month, rand(18, 22), 15, 30);
                $this->createPengeluaran($pglCounter++, $tglMaint, 'bisnis', 'pemeliharaan', 'Servis berkala mesin heat press sublimasi & laser cutting', rand(200, 350) * 1000, $user->id);
            }

            // 2. PENGELUARAN PRIBADI (PRIVE PEMILIK — variasi makan_pribadi, kebutuhan_rumah, prive_penarikan)
            $tglMakan = Carbon::create($year, $month, rand(6, 14), 12, 30);
            $this->createPengeluaran($pglCounter++, $tglMakan, 'pribadi', 'makan_pribadi', 'Konsumsi makan siang & jamuan keluarga pemilik', rand(250, 450) * 1000, $user->id);

            $tglRumah = Carbon::create($year, $month, rand(15, 20), 16, 0);
            $this->createPengeluaran($pglCounter++, $tglRumah, 'pribadi', 'kebutuhan_rumah', 'Belanja kebutuhan rumah tangga (gas, galon, listrik rumah)', rand(350, 600) * 1000, $user->id);

            $tglPrive = Carbon::create($year, $month, rand(22, 27), 12, 0);
            $this->createPengeluaran($pglCounter++, $tglPrive, 'pribadi', 'prive_penarikan', 'Penarikan prive keuntungan bulanan pemilik toko', rand(1500, 2500) * 1000, $user->id);

            // 3. KASBON KARYAWAN (Setiap 1-2 bulan)
            if ($month % 2 === 1 || ($year === 2026 && $month >= 8)) {
                $tglKasbon = Carbon::create($year, $month, rand(8, 22), 14, 30);
                $namaKaryawan = $karyawanList[($month + $year) % count($karyawanList)];

                $isBelumLunas = ($year === 2026 && $month >= 9);
                $statusKasbon = $isBelumLunas ? 'belum_lunas' : 'lunas';

                $this->createPengeluaran(
                    $pglCounter++,
                    $tglKasbon,
                    'kasbon',
                    'kasbon_karyawan',
                    'Pinjaman kasbon '.$namaKaryawan,
                    rand(300, 750) * 1000,
                    $user->id,
                    $namaKaryawan,
                    $statusKasbon
                );
            }
        }

        $this->command->info('PengeluaranSeeder: Biaya operasional, ads, prive & kasbon (Jan 2025 - Des 2025 & Jan 2026 - Okt 2026) berhasil di-seed.');
    }

    private function createPengeluaran(
        int $counter,
        Carbon $tanggal,
        string $kantong,
        string $kategori,
        string $keterangan,
        float $jumlah,
        int $userId,
        ?string $namaKaryawan = null,
        string $statusKasbon = 'lunas'
    ): void {
        $kode = 'PGL-'.$tanggal->format('Ymd').'-'.str_pad($counter, 4, '0', STR_PAD_LEFT);

        Pengeluaran::create([
            'kode_pengeluaran' => $kode,
            'tanggal' => $tanggal->format('Y-m-d'),
            'tipe_alokasi' => $kantong,
            'kategori' => $kategori,
            'keterangan' => $keterangan,
            'jumlah' => $jumlah,
            'karyawan_nama' => $namaKaryawan,
            'status_kasbon' => $statusKasbon,
            'user_id' => $userId,
            'created_at' => $tanggal,
            'updated_at' => $tanggal,
        ]);
    }
}
