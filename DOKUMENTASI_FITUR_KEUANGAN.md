# 📘 DOKUMENTASI LENGKAP UPGRADE SISTEM KEUANGAN (v2.0)
**Branch:** `feature/upgrade-keuangan-v2`  
**Nama Proyek:** Integrasi Keuangan Bisnis, Pribadi & Kasbon Tim (Souvenir Monitoring)  
**Valuasi Proyek:** Rp 600.000,- (Selesai & Production-Ready)  
**Dokumen Resmi PDF:** [`public/docs/Rincian_Penambahan_Fitur_Keuangan_Rp600rb.pdf`](public/docs/Rincian_Penambahan_Fitur_Keuangan_Rp600rb.pdf)

---

## 🎯 1. Ikhtisar & Tujuan Upgrade
Sebelum penambahan fitur ini, sistem mencampuradukkan biaya operasional toko dengan kebutuhan pribadi pemilik (dapur, makan, laundry) dan pinjaman kasbon tim. Hal tersebut menyebabkan laporan laba rugi toko menjadi rusak dan bias.

Upgrade ini membagi arus kas menjadi **3 Kantong Kas** yang jelas serta menambahkan modul pencatatan modal awal:
1. **Bisnis Toko** — Beban murni operasional (stok souvenir, packing, listrik toko, ads).
2. **Pribadi / Prive** — Uang toko yang ditarik pemilik untuk keperluan non-bisnis.
3. **Kasbon Tim** — Pinjaman karyawan yang tercatat status pelunasannya.
4. **Kas Masuk (Inflow)** — Setoran modal debit pemilik & penerimaan non-penjualan.

---

## 📂 2. Daftar File & Komponen yang Ditambahkan / Dimodifikasi

### A. Database (Migrations)
* `database/migrations/2026_10_31_134000_add_alokasi_keuangan_to_pengeluarans_table.php`:
  * Menambah kolom `tipe_alokasi` (`bisnis`, `pribadi`, `kasbon`).
  * Menambah kolom `karyawan_nama` (string nullable).
  * Menambah kolom `status_kasbon` (`belum_lunas`, `lunas`).
* `database/migrations/2026_10_31_134500_create_kas_masuks_table.php`:
  * Menambah tabel `kas_masuks` untuk setoran modal debit, pelunasan kasbon, dan pendapatan non-penjualan.

### B. Backend (Models, Controllers & Exports)
* `app/Models/Pengeluaran.php`: Ditambahkan Eloquent Scopes (`scopeBisnis()`, `scopePribadi()`, `scopeKasbon()`).
* `app/Models/KasMasuk.php`: Model baru untuk kas masuk modal.
* `app/Http/Controllers/PengeluaranController.php`: Filter multi-alokasi & method `toggleKasbon()`.
* `app/Http/Controllers/KasMasukController.php`: Controller CRUD lengkap kas masuk.
* `app/Http/Controllers/LaporanKeuanganController.php`: Controller laporan 4-tab spesifik.
* `app/Exports/LaporanKeuanganExport.php`: Ekspor Excel (.xlsx) dengan 4 tab laporan.
* `app/Console/Commands/ImportKeuangan2025Command.php`: CLI artisan command impor data riil 2025.

### C. Antarmuka (Views)
* `resources/views/kas-masuk/` (`index.blade.php`, `create.blade.php`, `edit.blade.php`): Antarmuka kas masuk modal.
* `resources/views/pengeluaran/` (`index.blade.php`, `create.blade.php`, `edit.blade.php`): Switch alokasi kas & toggle kasbon.
* `resources/views/laporan/keuangan.blade.php`: Tampilan 4 tab laporan keuangan.
* `resources/views/laporan/keuangan-pdf.blade.php`: Cetak PDF resmi untuk seluruh tab laporan.
* `resources/views/layouts/partials/sidebar.blade.php`: Penambahan link menu navigasi *Kas Masuk*.

### D. Data Riil & Dokumen
* `database/data/import_keuangan_2025.json`: Data historis hasil extract Excel (1.697 data).
* `public/docs/Rincian_Penambahan_Fitur_Keuangan_Rp600rb.pdf`: Invoice resmi PDF & spesifikasi deliverables senilai Rp 600.000.

---

## 🚀 3. Panduan Deployment / Aktivasi di Server Hosting

Jika sewaktu-waktu ingin mengaktifkan fitur ini di hosting:

### Langkah 1: Checkout ke branch fitur
```bash
git checkout feature/upgrade-keuangan-v2
# atau jika ingin digabung permanen ke branch utama (main):
git checkout main
git merge feature/upgrade-keuangan-v2
git push origin main
```

### Langkah 2: Jalankan Migrasi Database
```bash
php artisan migrate --force
```

### Langkah 3: Bersihkan & Segarkan Cache
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Langkah 4: Impor Data Riil Excel 2025 (Opsional)
```bash
php artisan import:keuangan-2025
```
*Hasil impor:*
* 6 Kas Masuk Modal: **Rp 10.000.000**
* 1.460 Beban Operasional Toko: **Rp 608.525.356**
* 89 Pengeluaran Pribadi/Prive: **Rp 10.032.000**
* 142 Pinjaman Kasbon Tim: **Rp 5.762.000**

---

## ⏪ 4. Panduan Rollback / Kembali ke Versi Lama (Jika Dibatalkan)

Jika memutuskan tidak menggunakan fitur ini sama sekali:

1. **Kembalikan Database:**
   ```bash
   php artisan migrate:rollback --step=2
   ```
2. **Pindah kembali ke branch utama (main):**
   ```bash
   git checkout main
   php artisan optimize:clear
   ```
Sistem Anda akan kembali 100% seperti versi awal tanpa modifikasi apa pun.
