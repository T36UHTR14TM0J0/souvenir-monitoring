<?php

namespace App\Console\Commands;

use App\Models\KasMasuk;
use App\Models\Pengeluaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ImportKeuangan2025Command extends Command
{
    protected $signature = 'import:keuangan-2025 
                            {file? : Jalur file Excel (.xlsx) atau JSON import}
                            {--fresh : Hapus data kas masuk & pengeluaran tahun 2025 sebelum import}
                            {--force : Jalankan tanpa konfirmasi}';

    protected $description = 'Import data transaksi keuangan tahun 2025 (Agustus - Desember) dari file Excel / JSON';

    public function handle(): int
    {
        $filePath = $this->argument('file');
        if (! $filePath) {
            $defaultExcel = 'C:/Users/teguh/Downloads/KEUANGAN TAHUN 2025 (2).xlsx';
            if (file_exists($defaultExcel)) {
                $filePath = $defaultExcel;
            } elseif (file_exists(database_path('data/import_keuangan_2025.json'))) {
                $filePath = database_path('data/import_keuangan_2025.json');
            } else {
                $filePath = storage_path('app/import_keuangan_2025.json');
            }
        }

        if (! file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");

            return 1;
        }

        $user = User::first();
        if (! $user) {
            $this->error('Tidak ada user di database untuk mengaitkan transaksi.');

            return 1;
        }

        $this->info("Membaca file: {$filePath}...");

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if ($extension === 'xlsx' || $extension === 'xls') {
            $data = $this->parseExcelFile($filePath);
        } else {
            $raw = file_get_contents($filePath);
            $data = json_decode($raw, true);
        }

        if (! $data || ! isset($data['pengeluaran'])) {
            $this->error('Format data tidak valid.');

            return 1;
        }

        $kasMasukCount = count($data['kas_masuk'] ?? []);
        $pengeluaranCount = count($data['pengeluaran'] ?? []);

        $this->info("Ditemukan: {$kasMasukCount} Kas Masuk (Debit Modal) dan {$pengeluaranCount} Pengeluaran Toko/Bahan.");

        if (! $this->option('force') && ! $this->confirm('Apakah Anda ingin mengimpor data ini ke database?', true)) {
            $this->info('Import dibatalkan.');

            return 0;
        }

        DB::beginTransaction();
        try {
            if ($this->option('fresh')) {
                $this->warn('Menghapus data kas masuk dan pengeluaran tahun 2025 yang sudah ada...');
                KasMasuk::whereYear('tanggal', 2025)->delete();
                Pengeluaran::whereYear('tanggal', 2025)->delete();
            }

            // 1. Import Kas Masuk
            $this->info('Mengimpor Kas Masuk (Modal Pemilik)...');
            $kmIndex = 1;
            $importedKm = 0;
            foreach ($data['kas_masuk'] as $km) {
                $tanggalClean = date('Ymd', strtotime($km['tanggal']));
                $kode = sprintf('KM-%s-%04d', $tanggalClean, $kmIndex++);

                $existing = KasMasuk::where('tanggal', $km['tanggal'])
                    ->where('jumlah', $km['jumlah'])
                    ->where('keterangan', $km['keterangan'])
                    ->first();

                if (! $existing) {
                    KasMasuk::create([
                        'kode_transaksi' => $kode,
                        'tanggal' => $km['tanggal'],
                        'tipe' => $km['tipe'] ?? 'modal_pemilik',
                        'sumber' => $km['sumber'] ?? 'Debit / Transfer',
                        'jumlah' => $km['jumlah'],
                        'keterangan' => $km['keterangan'],
                        'catatan' => $km['catatan'] ?? null,
                        'user_id' => $user->id,
                    ]);
                    $importedKm++;
                }
            }

            // 2. Import Pengeluaran
            $this->info('Mengimpor Pengeluaran Toko, Bahan Baku, Kasbon & Prive...');
            $bar = $this->output->createProgressBar($pengeluaranCount);
            $bar->start();

            $pglIndex = 1;
            $importedBahan = 0;
            $importedOperasional = 0;
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

                if (! $existing) {
                    Pengeluaran::create([
                        'kode_pengeluaran' => $kode,
                        'tanggal' => $p['tanggal'],
                        'tipe_alokasi' => $p['tipe_alokasi'],
                        'kategori' => $p['kategori'],
                        'karyawan_nama' => $p['karyawan_nama'] ?? null,
                        'status_kasbon' => $p['status_kasbon'] ?? null,
                        'keterangan' => $p['keterangan'],
                        'jumlah' => $p['jumlah'],
                        'catatan' => $p['catatan'] ?? null,
                        'user_id' => $user->id,
                    ]);

                    if (in_array($p['kategori'], ['bahan_baku', 'jasa_produksi', 'perlengkapan'], true)) {
                        $importedBahan++;
                    } elseif ($p['tipe_alokasi'] === 'bisnis') {
                        $importedOperasional++;
                    } elseif ($p['tipe_alokasi'] === 'pribadi') {
                        $importedPribadi++;
                    } elseif ($p['tipe_alokasi'] === 'kasbon') {
                        $importedKasbon++;
                    }
                }

                $bar->advance();
            }

            $bar->finish();
            $this->newLine();

            DB::commit();

            $this->info('====================================================');
            $this->info('✅ IMPORT DATA SELESAI DENGAN SUKSES!');
            $this->info("  - Kas Masuk (Debit Modal): {$importedKm} transaksi");
            $this->info("  - Modal Bahan Baku & Jasa Cetak: {$importedBahan} transaksi");
            $this->info("  - Biaya Operasional & Ads: {$importedOperasional} transaksi");
            $this->info("  - Kasbon Tim Karyawan: {$importedKasbon} transaksi");
            $this->info("  - Pribadi / Prive Pemilik: {$importedPribadi} transaksi");
            $this->info('====================================================');

            return 0;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Gagal import data: '.$e->getMessage());

            return 1;
        }
    }

    private function parseExcelFile(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $kasMasuk = [];
        $pengeluaran = [];

        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            if (! $sheet) {
                continue;
            }

            $highestRow = $sheet->getHighestRow();
            $currentDate = $this->getDefaultDateForSheet($sheetName);

            for ($r = 4; $r <= $highestRow; $r++) {
                $noVal = trim((string) $sheet->getCell('A'.$r)->getFormattedValue());

                // Lewati baris ringkasan / total
                if (str_contains(strtoupper($noVal), 'TOTAL')) {
                    break;
                }

                // Cek tanggal di kolom M, K, atau L
                $dateRaw = $sheet->getCell('M'.$r)->getValue() ?: $sheet->getCell('K'.$r)->getValue();
                if ($dateRaw) {
                    $parsed = $this->parseDateValue($dateRaw, $currentDate);
                    if ($parsed) {
                        $currentDate = $parsed;
                    }
                }

                $opRaw = $sheet->getCell('B'.$r)->getValue();
                $modRaw = $sheet->getCell('D'.$r)->getValue();
                $debRaw = $sheet->getCell('E'.$r)->getValue();
                $ketRaw = $sheet->getCell('H'.$r)->getFormattedValue() ?: $sheet->getCell('J'.$r)->getFormattedValue();
                $keterangan = trim((string) $ketRaw);

                // 1. Kas Masuk (Debit / Modal)
                $debVal = $this->cleanNominal($debRaw);
                if ($debVal > 0) {
                    $kasMasuk[] = [
                        'tanggal' => $currentDate,
                        'tipe' => 'modal_pemilik',
                        'sumber' => 'Debit / Transfer',
                        'jumlah' => $debVal,
                        'keterangan' => $keterangan ?: 'Penerimaan Modal Harian (Debit Kas)',
                        'catatan' => "Sheet {$sheetName} Baris {$r}",
                    ];
                }

                // 2. Operasional
                $opVal = $this->cleanNominal($opRaw);
                if ($opVal > 0 && ! empty($keterangan)) {
                    $info = $this->detectCategoryAndAllocation($keterangan, false);
                    $pengeluaran[] = array_merge([
                        'tanggal' => $currentDate,
                        'jumlah' => $opVal,
                        'keterangan' => $keterangan,
                        'catatan' => "Import Excel {$sheetName} (B-{$r})",
                    ], $info);
                }

                // 3. Modal Bahan Baku
                $modVal = $this->cleanNominal($modRaw);
                if ($modVal > 0 && ! empty($keterangan)) {
                    $info = $this->detectCategoryAndAllocation($keterangan, true);
                    $pengeluaran[] = array_merge([
                        'tanggal' => $currentDate,
                        'jumlah' => $modVal,
                        'keterangan' => $keterangan,
                        'catatan' => "Import Excel {$sheetName} (D-{$r})",
                    ], $info);
                }
            }
        }

        return [
            'kas_masuk' => $kasMasuk,
            'pengeluaran' => $pengeluaran,
        ];
    }

    private function getDefaultDateForSheet(string $sheetName): string
    {
        $name = strtolower($sheetName);
        if (str_contains($name, 'agu')) {
            return '2025-08-01';
        }
        if (str_contains($name, 'sep')) {
            return '2025-09-01';
        }
        if (str_contains($name, 'okt')) {
            return '2025-10-01';
        }
        if (str_contains($name, 'nov')) {
            return '2025-11-01';
        }
        if (str_contains($name, 'des')) {
            return '2025-12-01';
        }

        return '2025-08-01';
    }

    private function parseDateValue(mixed $val, string $currentDate): ?string
    {
        if (! $val) {
            return null;
        }

        $str = trim((string) $val);
        if (empty($str) || in_array(strtoupper($str), ['ONLINE', 'OFFLINE', 'SALDO', 'TOTAL'], true)) {
            return null;
        }

        try {
            if (is_numeric($val) && (float) $val > 40000 && (float) $val < 60000) {
                $dt = ExcelDate::excelToDateTimeObject($val);

                return Carbon::instance($dt)->setYear(2025)->format('Y-m-d');
            }

            if (preg_match('/^[0-9]{1,2}[\/\-][0-9]{1,2}[\/\-][0-9]{2,4}$/', $str)) {
                $dt = Carbon::parse($str);

                return $dt->setYear(2025)->format('Y-m-d');
            }
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }

    private function cleanNominal(mixed $val): float
    {
        if (! $val) {
            return 0.0;
        }
        $digits = preg_replace('/[^0-9]/', '', (string) $val);

        return $digits !== '' ? (float) $digits : 0.0;
    }

    private function detectCategoryAndAllocation(string $keterangan, bool $isModal): array
    {
        $ketLower = strtolower($keterangan);

        // 1. Kasbon Tim Karyawan
        if (str_contains($ketLower, 'kasbon')) {
            $nama = $this->extractNamaKaryawan($keterangan);

            return [
                'tipe_alokasi' => 'kasbon',
                'kategori' => 'kasbon_karyawan',
                'karyawan_nama' => $nama ?: 'Karyawan Workshop',
                'status_kasbon' => 'belum_lunas',
            ];
        }

        // 2. Pribadi / Prive Pemilik
        if (str_contains($ketLower, 'prive') || str_contains($ketLower, 'pribadi') || str_contains($ketLower, 'rumah') || str_contains($ketLower, 'dapur')) {
            return [
                'tipe_alokasi' => 'pribadi',
                'kategori' => 'prive_penarikan',
                'karyawan_nama' => null,
                'status_kasbon' => null,
            ];
        }

        // 3. Konsumsi / Lunch Tim
        if (str_contains($ketLower, 'lunch') || str_contains($ketLower, 'makan') || str_contains($ketLower, 'konsumsi') || str_contains($ketLower, 'kopi') || str_contains($ketLower, 'sarapan')) {
            return [
                'tipe_alokasi' => 'bisnis',
                'kategori' => 'konsumsi',
                'karyawan_nama' => null,
                'status_kasbon' => null,
            ];
        }

        // 4. Transportasi & Bensin & Parkir
        if (str_contains($ketLower, 'bensin') || str_contains($ketLower, 'parkir') || str_contains($ketLower, 'toll') || str_contains($ketLower, 'tol') || str_contains($ketLower, 'transport')) {
            return [
                'tipe_alokasi' => 'bisnis',
                'kategori' => 'transport',
                'karyawan_nama' => null,
                'status_kasbon' => null,
            ];
        }

        // 5. Iklan Marketplace (Ads)
        if (str_contains($ketLower, 'ads') || str_contains($ketLower, 'iklan') || str_contains($ketLower, 'shopee ads') || str_contains($ketLower, 'tiktok ads')) {
            return [
                'tipe_alokasi' => 'bisnis',
                'kategori' => 'iklan',
                'karyawan_nama' => null,
                'status_kasbon' => null,
            ];
        }

        // 6. Packing & Pengemasan
        if (str_contains($ketLower, 'kardus') || str_contains($ketLower, 'bubble') || str_contains($ketLower, 'lakban') || str_contains($ketLower, 'packing') || str_contains($ketLower, 'plastik') || str_contains($ketLower, 'opp')) {
            return [
                'tipe_alokasi' => 'bisnis',
                'kategori' => 'packing',
                'karyawan_nama' => null,
                'status_kasbon' => null,
            ];
        }

        // 7. Jasa Cetak & Potong Luar
        if (str_contains($ketLower, 'motong') || str_contains($ketLower, 'print') || str_contains($ketLower, 'raja') || str_contains($ketLower, 'dunia baru') || str_contains($ketLower, 'sablon') || str_contains($ketLower, 'potong')) {
            return [
                'tipe_alokasi' => 'bisnis',
                'kategori' => 'jasa_produksi',
                'karyawan_nama' => null,
                'status_kasbon' => null,
            ];
        }

        // 8. Gaji & Upah
        if (str_contains($ketLower, 'gaji') || str_contains($ketLower, 'upah')) {
            return [
                'tipe_alokasi' => 'bisnis',
                'kategori' => 'gaji',
                'karyawan_nama' => null,
                'status_kasbon' => null,
            ];
        }

        // 9. Modal / Bahan Baku
        if ($isModal) {
            return [
                'tipe_alokasi' => 'bisnis',
                'kategori' => 'bahan_baku',
                'karyawan_nama' => null,
                'status_kasbon' => null,
            ];
        }

        // Default Operasional
        return [
            'tipe_alokasi' => 'bisnis',
            'kategori' => 'operasional',
            'karyawan_nama' => null,
            'status_kasbon' => null,
        ];
    }

    private function extractNamaKaryawan(string $keterangan): ?string
    {
        if (preg_match('/\[\s*(om\s+[a-z0-9_-]+)\s*[\)\]]/i', $keterangan, $m)) {
            return ucwords(strtolower(trim($m[1])));
        }
        if (preg_match('/(om\s+[a-z0-9_-]+)/i', $keterangan, $m)) {
            return ucwords(strtolower(trim($m[1])));
        }
        if (preg_match('/kasbon\s*\[?\s*([a-z0-9_\s-]+)\s*[\)\]]?/i', $keterangan, $m)) {
            $clean = trim(str_ireplace(['kasbon', '[', ']', '(', ')'], '', $m[1]));
            if (! empty($clean)) {
                return ucwords(strtolower($clean));
            }
        }

        return null;
    }
}
