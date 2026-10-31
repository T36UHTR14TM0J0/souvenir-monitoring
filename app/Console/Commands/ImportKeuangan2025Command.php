<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KasMasuk;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ImportKeuangan2025Command extends Command
{
    protected $signature = 'import:keuangan-2025 {file? : Jalur file JSON import}';
    protected $description = 'Import data transaksi keuangan tahun 2025 (Agustus - Desember) dari file Excel';

    public function handle()
    {
        $filePath = $this->argument('file');
        if (!$filePath) {
            if (file_exists(database_path('data/import_keuangan_2025.json'))) {
                $filePath = database_path('data/import_keuangan_2025.json');
            } else {
                $filePath = storage_path('app/import_keuangan_2025.json');
            }
        }

        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        $this->info("Membaca file data: {$filePath}...");
        $raw = file_get_contents($filePath);
        $data = json_decode($raw, true);

        if (!$data || !isset($data['pengeluaran'])) {
            $this->error("Format JSON tidak valid.");
            return 1;
        }

        $user = User::first();
        if (!$user) {
            $this->error("Tidak ada user di database untuk mengaitkan transaksi.");
            return 1;
        }

        $kasMasukCount  = count($data['kas_masuk'] ?? []);
        $pengeluaranCount = count($data['pengeluaran'] ?? []);

        $this->info("Ditemukan: {$kasMasukCount} Kas Masuk (Modal) dan {$pengeluaranCount} Pengeluaran.");

        if (!$this->confirm('Apakah Anda ingin mengimpor data ini ke database?', true)) {
            $this->info('Import dibatalkan.');
            return 0;
        }

        DB::beginTransaction();
        try {
            // 1. Import Kas Masuk
            $this->info('Mengimpor Kas Masuk (Modal Pemilik)...');
            $kmIndex = 1;
            foreach ($data['kas_masuk'] as $km) {
                $tanggalClean = date('Ymd', strtotime($km['tanggal']));
                $kode = sprintf('KM-%s-%04d', $tanggalClean, $kmIndex++);

                // Cek jika sudah ada
                $existing = KasMasuk::where('tanggal', $km['tanggal'])
                    ->where('jumlah', $km['jumlah'])
                    ->where('keterangan', $km['keterangan'])
                    ->first();

                if (!$existing) {
                    KasMasuk::create([
                        'kode_transaksi' => $kode,
                        'tanggal'        => $km['tanggal'],
                        'tipe'           => $km['tipe'] ?? 'modal_pemilik',
                        'sumber'         => $km['sumber'] ?? 'Debit / Transfer Bank Pribadi',
                        'jumlah'         => $km['jumlah'],
                        'keterangan'     => $km['keterangan'],
                        'catatan'        => $km['catatan'] ?? null,
                        'user_id'        => $user->id,
                    ]);
                }
            }

            // 2. Import Pengeluaran
            $this->info('Mengimpor Pengeluaran (Bisnis, Pribadi, Kasbon)...');
            $bar = $this->output->createProgressBar($pengeluaranCount);
            $bar->start();

            $pglIndex = 1;
            $importedBisnis = 0;
            $importedPribadi = 0;
            $importedKasbon = 0;

            foreach ($data['pengeluaran'] as $p) {
                $tanggalClean = date('Ymd', strtotime($p['tanggal']));
                $kode = sprintf('PGL-%s-%04d', $tanggalClean, $pglIndex++);

                $existing = Pengeluaran::where('tanggal', $p['tanggal'])
                    ->where('jumlah', $p['jumlah'])
                    ->where('keterangan', $p['keterangan'])
                    ->where('tipe_alokasi', $p['tipe_alokasi'])
                    ->first();

                if (!$existing) {
                    Pengeluaran::create([
                        'kode_pengeluaran' => $kode,
                        'tanggal'          => $p['tanggal'],
                        'tipe_alokasi'     => $p['tipe_alokasi'],
                        'kategori'         => $p['kategori'],
                        'karyawan_nama'    => $p['karyawan_nama'] ?? null,
                        'status_kasbon'    => $p['status_kasbon'] ?? null,
                        'keterangan'       => $p['keterangan'],
                        'jumlah'           => $p['jumlah'],
                        'catatan'          => $p['catatan'] ?? null,
                        'user_id'          => $user->id,
                    ]);

                    if ($p['tipe_alokasi'] === 'bisnis') $importedBisnis++;
                    elseif ($p['tipe_alokasi'] === 'pribadi') $importedPribadi++;
                    elseif ($p['tipe_alokasi'] === 'kasbon') $importedKasbon++;
                }

                $bar->advance();
            }

            $bar->finish();
            $this->newLine();

            DB::commit();

            $this->info('====================================================');
            $this->info('✅ IMPORT DATA SELESAI DENGAN SUKSES!');
            $this->info("  - Kas Masuk (Modal): {$kmIndex} data");
            $this->info("  - Pengeluaran Bisnis Toko: {$importedBisnis} data");
            $this->info("  - Pengeluaran Pribadi (Prive): {$importedPribadi} data");
            $this->info("  - Kasbon Tim: {$importedKasbon} data");
            $this->info('====================================================');

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Gagal import data: ' . $e->getMessage());
            return 1;
        }
    }
}
