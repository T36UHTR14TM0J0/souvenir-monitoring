# 🛍️ Toko Souvenir Jibranime — Sistem Monitoring Keuangan, Operasional & Predictive Forecasting

Sistem Informasi Manajemen Terpadu (Mini-ERP) berbasis **Laravel 12** yang dirancang khusus untuk **Toko Souvenir Jibranime**. Aplikasi ini mengintegrasikan pemantauan penjualan **3 Marketplace (Shopee, TikTok Shop, Lazada)**, **Manajemen Produksi & Quality Control (QC)**, **Inventori Gudang Souvenir & Bahan Baku**, **Akuntansi Pemisahan 3 Kantong Kas (Bisnis, Prive Pemilik, Kasbon Tim)**, **8 Modul Laporan Keuangan & Operasional**, serta **Teknologi Time-Series Forecasting (Holt’s Double Exponential Smoothing)**.

---

## 📋 Daftar Isi

1. [Fitur Utama & Modul Aplikasi](#-fitur-utama--modul-aplikasi)
2. [Teknologi Time-Series & Demand Forecasting](#-teknologi-time-series--demand-forecasting)
3. [Teknologi yang Digunakan (Tech Stack)](#-teknologi-yang-digunakan-tech-stack)
4. [Akun Default & Matriks Hak Akses (RBAC 57 Permissions)](#-akun-default--matriks-hak-akses-rbac-57-permissions)
5. [Kebutuhan Sistem (Prerequisites)](#-kebutuhan-sistem-prerequisites)
6. [Panduan Instalasi di Komputer Lokal (Laragon / XAMPP)](#-panduan-instalasi-di-komputer-lokal-laragon--xampp)
7. [Panduan Deployment di Server Hosting (cPanel / VPS)](#-panduan-deployment-di-server-hosting-cpanel--vps)
8. [Data Seeder Historis (Januari 2025 – Oktober 2026)](#-data-seeder-historis-januari-2025--oktober-2026)
9. [Pengujian Otomatis (Automated Testing)](#-pengujian-otomatis-automated-testing)
10. [Daftar Perintah Pemeliharaan (Maintenance Cheat Sheet)](#-daftar-perintah-pemeliharaan-maintenance-cheat-sheet)

---

## 🚀 Fitur Utama & Modul Aplikasi

Aplikasi ini mengorganisasikan seluruh alur kerja ke dalam **6 Grup Sidebar (15 Modul Menu)** dengan kontrol hak akses (*Role-Based Access Control*) yang presisi:

### 1. Menu Utama — Dashboard Eksekutif & Predictive Forecasting
- **4 Kartu KPI Utama Real-Time**:
  - *Penghasilan Bersih Marketplace* (otomatis mengecualikan pesanan berstatus `Batal` dan `Retur`).
  - *Total Belanja Bahan (HPP) & Biaya Operasional/Ads*.
  - *Laba Bersih Bisnis* beserta persentase *Margin Bersih* dan pertumbuhan *Month-over-Month (MoM)*.
  - *Proyeksi Bulan Depan (AI Forecast)* lengkap dengan estimasi laba dan persentase akurasi model.
- **4 Tab Analitik Interaktif**:
  1. **Tren & Forecasting**: Grafik historis 12 bulan + proyeksi 3/6 bulan ke depan dengan *Zona Proyeksi AI*, *Confidence Interval 90%*, dan tabel matriks proyeksi keuangan.
  2. **3 Marketplace & Produk**: Perbandingan performa *Shopee*, *TikTok Shop*, dan *Lazada* (Omzet Kotor, Dana Bersih, Pangsa Pasar, Forecast Bulan Depan) serta *Top 5 Souvenir Terlaris & Demand Forecasting*.
  3. **Produksi, Gudang & Kas**: Grafik output produksi (*Lolos QC vs Reject*), pemakaian bahan baku, struktur arus kas/modal/prive/kasbon, distribusi biaya per kategori, serta peringatan stok menipis.
  4. **Transaksi Terbaru**: Ringkasan cepat 5 pesanan marketplace terbaru dan 5 pengadaan bahan baku terbaru.

### 2. Pesanan & Produk
- **Pesanan Marketplace (`Transaksi Penjualan`)**:
  - Pencatatan pesanan dari **Shopee**, **TikTok Shop**, dan **Lazada** lengkap dengan Nomor Pesanan Marketplace, Ekspedisi & Nomor Resi, Potongan Biaya Admin/Layanan MP, Diskon, dan **Penghasilan Bersih Seller**.
  - Pipeline status pesanan: `Baru` $\rightarrow$ `Diproses` $\rightarrow$ `Dikirim` $\rightarrow$ `Selesai` (serta penanganan `Batal` / `Retur` dengan pengembalian stok otomatis).
  - Fitur **Cetak SPK (Surat Perintah Kerja) Produksi** dan **Cetak Packing Slip Pengiriman**.
- **Katalog Souvenir (`Produk`)**:
  - Manajemen SKU produk souvenir jadi, foto produk, harga pokok/jual, stok minimal, dan riwayat mutasi stok.
- **Hasil Produksi Masuk (`Penerimaan Produk & QC`)**:
  - Pencatatan hasil produksi dari workshop ke gudang dengan pemisahan **Qty Bagus (Lolos QC)** yang otomatis menambah stok katalog dan **Qty Reject (Cacat Produksi)** untuk evaluasi tingkat kelulusan QC (*Pass Rate*).

### 3. Gudang & Bahan Baku
- **Stok Bahan Baku**:
  - Inventori terpisah khusus material produksi & komponen kemasan (akrilik, gantungan kunci, resin, plastik opp, bubble wrap, kardus, dll.) dengan indikator stok aman/menipis/habis.
- **Pemakaian Bahan (`Pengambilan Bahan Produksi`)**:
  - Pencatatan pengeluaran bahan baku dari gudang ke tim produksi yang otomatis memotong stok bahan baku dan menghitung estimasi nilai HPP bahan terpakai.
- **Pembelian Bahan Baku (`Pengadaan Online & Supplier`)**:
  - Pencatatan belanja bahan baku dari marketplace/supplier lengkap dengan ongkos kirim kargo, nomor resi, bukti nota, dan konfirmasi penerimaan paket (`Dalam Perjalanan` $\rightarrow$ `Diterima` yang otomatis menambah stok gudang).

### 4. Keuangan & Kas (Sistem Pemisahan 3 Kantong Kas)
- **Kas Masuk & Modal (`KasMasuk`)**:
  - Pencatatan setoran **Modal Pemilik (Owner)** dan **Pendapatan Lain-lain / Pencairan Dana**.
- **Biaya Operasional & Ads (`Pengeluaran` — Tab Bisnis)**:
  - Pencatatan murni biaya operasional toko: *Iklan Marketplace (Shopee/TikTok/Lazada Ads)*, *Perlengkapan Packing*, *Gaji & Upah Produksi*, *Listrik/Air/Internet*, *Transportasi/Ekspedisi*, dan *Sewa/Perawatan Alat*.
- **Keuangan Pribadi / Prive (`Pengeluaran` — Tab Pribadi)**:
  - Pencatatan pengeluaran pribadi pemilik yang diambil dari kas toko (*Makan/Konsumsi Pribadi*, *Kebutuhan Rumah Tangga*, *Penarikan Prive*). **Otomatis dipisahkan** agar tidak mengurangi perhitungan Laba Bersih Bisnis toko.
- **Buku Kasbon Tim (`Pengeluaran` — Tab Kasbon)**:
  - Pencatatan pinjaman/kasbon anggota tim beserta fitur **Pelunasan Kasbon** (otomatis memperbarui status menjadi `Lunas`).

### 5. Laporan & Analitik (8 Menu Terpisah + Export PDF & Excel)
Setiap menu laporan memiliki tampilan tabel standar *Navy Gradient*, kartu ringkasan KPI spesifik, grafik visualisasi Chart.js, filter periode (Bulan & Tahun), pencarian cepat, serta tombol **Export PDF** dan **Export Excel**:
1. **Laba/Rugi Bisnis**: Perhitungan akuntansi *Omzet Kotor Valid $\rightarrow$ Potongan Admin MP $\rightarrow$ Penghasilan Bersih $\rightarrow$ HPP Pembelian Bahan $\rightarrow$ Laba Kotor $\rightarrow$ Biaya Operasional & Ads $\rightarrow$ Laba Bersih & Margin*.
2. **Laporan Penjualan MP**: Rekapitulasi transaksi per pesanan & komparasi per channel marketplace.
3. **Laporan Pembelian Bahan**: Rekapitulasi belanja bahan baku, ongkir kargo, dan distribusi platform supplier.
4. **Laporan Biaya & Ads**: Analisis rasio biaya iklan marketplace dan biaya operasional toko.
5. **Laporan Produksi & QC**: Evaluasi batch produksi masuk (*Lolos QC vs Reject*) dan rincian pemakaian bahan baku.
6. **Laporan Keuangan Pribadi**: Monitoring khusus penarikan prive dan konsumsi pribadi pemilik.
7. **Rekonsiliasi Kas & Modal**: Perbandingan *Modal Disetor Owner + Pendapatan Lain* terhadap *Penarikan Prive* untuk melihat *Saldo Netto Modal Pemilik* dan *Piutang Kasbon Aktif*.
8. **Laporan Buku Kasbon**: Daftar riwayat kasbon beserta matriks rekapitulasi total pinjaman, lunas, dan sisa hutang per karyawan.

### 6. Master Data & Pengaturan Sistem
- **Master Data**: Pengelolaan **Kategori Produk/Bahan** dan **Satuan Barang** (`pcs`, `set`, `pack`, `roll`, `lembar`, `kg`, `liter`, dll.).
- **Pengaturan Sistem**: Manajemen **Pengguna (Users)** (beserta toggle status aktif/nonaktif), **Role & Hak Akses** dengan pengelompokan visual 15 menu aplikasi, serta **Daftar Permission**.

---

## 📈 Teknologi Time-Series & Demand Forecasting

Dashboard dilengkapi mesin prediksi mandiri pada [`DashboardController::runHoltForecast()`](app/Http/Controllers/DashboardController.php) yang menerapkan metode statistik **Holt’s Linear Trend (Double Exponential Smoothing) dengan Damped Trend**:

$$\ell_t = \alpha y_t + (1 - \alpha)(\ell_{t-1} + \phi b_{t-1})$$
$$b_t = \beta (\ell_t - \ell_{t-1}) + (1 - \beta)\phi b_{t-1}$$
$$\hat{y}_{t+h|t} = \ell_t + \left(\sum_{i=1}^{h} \phi^i\right) b_t$$

- **Parameter Model**: Smoothing Level $\alpha = 0.45$, Smoothing Trend $\beta = 0.25$, dan Damping Factor $\phi = 0.90$ (mencegah proyeksi jangka menengah *overshooting* secara tidak realistis).
- **Rentang Kepercayaan 90% (90% Confidence Interval)**: Dihitung dari *Root Mean Square Error (RMSE)* residu historis ($\pm 1.645 \times \sigma \sqrt{h}$).
- **Evaluasi Akurasi Otomatis**: Menghitung *Mean Absolute Percentage Error (MAPE)* dan persentase **Akurasi Model** ($\max(0, 100 - \text{MAPE})$) secara dinamis dari data aktual 12 bulan terakhir.
- **Product Demand Forecasting**: Memprediksi permintaan kuantitas (*Forecast Qty*) untuk Top 5 produk souvenir terlaris pada bulan berikutnya dan memberikan **Saran Jumlah Produksi** otomatis berdasarkan stok gudang saat ini ditambah *safety buffer* 15%.

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

| Komponen | Teknologi / Package | Versi |
| :--- | :--- | :--- |
| **Backend Framework** | Laravel Framework | `^12.0` |
| **Bahasa Pemrograman** | PHP | `^8.2` / `8.3` |
| **Database** | MySQL / MariaDB | `8.0+` / `10.4+` |
| **Autentikasi & Otorisasi** | Laravel UI + Spatie Laravel Permission | `^4.6` / `^6.24` |
| **Export Laporan PDF** | Barryvdh Laravel DomPDF | `^3.1` |
| **Export Laporan Excel** | Maatwebsite Excel & PhpSpreadsheet | `^1.15` / `^5.5` |
| **Frontend UI & Styling** | Bootstrap 5 (AdminKit Custom Navy Theme) + FontAwesome 6 | `5.x` / `6.5` |
| **Visualisasi Grafik** | Chart.js | `4.4.1` |
| **Komponen Interaktif** | SweetAlert2 & Select2 Bootstrap 5 Theme | `11.x` / `4.1` |
| **Testing Framework** | PHPUnit | `^11.5` |

---

## 🔑 Akun Default & Matriks Hak Akses (RBAC 57 Permissions)

Setelah menjalankan `php artisan migrate:fresh --seed`, sistem menyediakan **3 akun pengguna default** dengan pemetaan **57 permission** yang selaras 1-ke-1 dengan struktur menu sidebar:

### Daftar Kredensial Login Default

| Role | Email Login | Password Default | Fokus Tanggung Jawab |
| :--- | :--- | :--- | :--- |
| **Pemilik** | `pemilik@gmail.com` | `Pemilik123!` | **Super Admin / Owner**: Akses penuh ke seluruh 57 permission (Monitoring, Operasional, Keuangan, Prive, Laporan Lengkap, dan Manajemen User/Role). |
| **Bendahara** | `bendahara@gmail.com` | `Bendahara123!` | **Manajemen Keuangan & Kas (34 Permission)**: Mengelola Kas Masuk/Modal, Pengeluaran (Bisnis, Pribadi, Kasbon), Pembelian Bahan Baku, Monitoring Pesanan/Stok (View-Only), serta akses penuh ke **8 Menu Laporan & Export PDF/Excel**. |
| **Admin** | `admin@gmail.com` | `Admin123!` | **Operasional Toko, Marketplace & Gudang (33 Permission)**: Mengelola penuh Pesanan 3 Marketplace (beserta SPK & Packing Slip), Katalog Souvenir, Hasil Produksi & QC, Stok & Pemakaian Bahan Baku, Input/Konfirmasi Pembelian, Master Data, serta **3 Laporan Operasional** (Penjualan MP, Pembelian Bahan, Produksi & QC). |

> ⚠️ **Catatan Keamanan:** Segera ubah password default di atas melalui menu **Manajemen User** setelah aplikasi dijalankan di server produksi.

### Matriks Hak Akses per Grup Menu

| Grup Sidebar / Modul | Pemilik | Bendahara | Admin |
| :--- | :---: | :---: | :---: |
| **Dashboard & Forecasting** | ✅ Full | ✅ Full | ✅ Full |
| **Pesanan Marketplace (SPK & Packing Slip)** | ✅ Full CRUD | 👁️ View Only | ✅ Full CRUD |
| **Katalog Souvenir & Hasil Produksi (QC)** | ✅ Full CRUD | 👁️ View Only | ✅ Full CRUD |
| **Stok Bahan Baku & Pemakaian Bahan** | ✅ Full CRUD | 👁️ View Only | ✅ Full CRUD |
| **Pembelian Bahan Baku (PO Supplier)** | ✅ Full CRUD | ✅ Full CRUD | ✅ List, Create, Edit |
| **Kas Masuk, Biaya Bisnis, Prive & Kasbon** | ✅ Full CRUD | ✅ Full CRUD | ❌ Tidak Ada Akses |
| **Laporan Laba/Rugi, Biaya, Pribadi, Rekonsiliasi, Kasbon** | ✅ Full + Export | ✅ Full + Export | ❌ Dibatasi (403) |
| **Laporan Penjualan MP, Pembelian Bahan, Produksi & QC** | ✅ Full + Export | ✅ Full + Export | ✅ View Operasional |
| **Master Data (Kategori & Satuan)** | ✅ Full CRUD | 👁️ View Only | ✅ Full CRUD |
| **Pengaturan Sistem (User, Role & Permission)** | ✅ Full CRUD | ❌ Dibatasi (403) | ❌ Dibatasi (403) |

---

## ⚙️ Kebutuhan Sistem (Prerequisites)

Pastikan lingkungan komputer lokal atau server hosting Anda memenuhi spesifikasi berikut:
- **PHP**: `>= 8.2` (Direkomendasikan **PHP 8.3**) dengan ekstensi aktif:
  - `pdo_mysql`, `mbstring`, `openssl`, `gd`, `fileinfo`, `zip`, `xml`, `ctype`, `json`, `bcmath`
- **Composer**: Versi `2.x`
- **Database**: MySQL `8.0+` atau MariaDB `10.4+`
- **Web Server**: Apache / Nginx / Laragon / Herd
- **Node.js & NPM** *(Opsional, hanya jika ingin memodifikasi aset bundel Vite)*: Node `18+`

---

## 💻 Panduan Instalasi di Komputer Lokal (Laragon / XAMPP)

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di komputer lokal:

### 1. Clone Repositori
Buka terminal (PowerShell / Git Bash / CMD) dan arahkan ke folder direktori web server Anda (contoh: `D:\laragon\www\`):
```bash
git clone https://github.com/T36UHTR14TM0J0/souvenir-monitoring.git
cd souvenir-monitoring
```

### 2. Instal Dependensi PHP via Composer
```bash
composer install
```

### 3. Salin & Konfigurasi File `.env`
```bash
# Windows PowerShell / CMD
copy .env.example .env

# macOS / Linux / Git Bash
cp .env.example .env
```

Buka file `.env` dan sesuaikan pengaturan database MySQL Anda:
```ini
APP_NAME="Toko Souvenir Jibranime"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://souvenir-monitoring.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=souvenir_monitoring
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Jalankan Migrasi Database & Data Seeder Lengkap
Buat database kosong bernama `souvenir_monitoring` di MySQL/phpMyAdmin, kemudian jalankan:
```bash
php artisan migrate:fresh --seed
```
> Perintah ini akan membuat seluruh struktur tabel, menjalankan [`RolePermissionSeeder`](database/seeders/RolePermissionSeeder.php), serta mengisi data transaksi realistis selama **22 bulan penuh (Januari 2025 – Oktober 2026)**.

### 6. Buat Symbolic Link Storage
Agar upload foto produk souvenir, nota pembelian, dan bukti kas/pengeluaran dapat diakses:
```bash
php artisan storage:link
```

### 7. Jalankan Aplikasi
- **Via Laragon Auto Virtual Host**: Buka browser dan akses `http://souvenir-monitoring.test`
- **Via Artisan Serve**:
  ```bash
  php artisan serve
  ```
  Lalu buka `http://127.0.0.1:8000` di browser Anda.

---

## 🌐 Panduan Deployment di Server Hosting (cPanel / VPS)

### Langkah 1: Persiapan Database di cPanel
1. Login ke **cPanel** $\rightarrow$ buka **MySQL Database Wizard**.
2. Buat database baru (misal: `tokosouv_monitoring`) beserta user database dan password.
3. Berikan hak akses **ALL PRIVILEGES** kepada user database tersebut.

### Langkah 2: Clone / Upload Proyek ke Server
Buka **Terminal** di cPanel dan jalankan:
```bash
cd ~/public_html
git clone https://github.com/T36UHTR14TM0J0/souvenir-monitoring.git
cd souvenir-monitoring
```

### Langkah 3: Konfigurasi `.env` Production
```bash
cp .env.example .env
nano .env
```
Pastikan pengaturan production diatur dengan benar:
```ini
APP_NAME="Toko Souvenir Jibranime"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tokosouv_monitoring
DB_USERNAME=tokosouv_user
DB_PASSWORD=PasswordKuatAnda
```

### Langkah 4: Instalasi & Optimasi Production
```bash
# 1. Instal dependensi production tanpa dev packages
composer install --no-dev --optimize-autoloader

# 2. Generate App Key
php artisan key:generate --force

# 3. Migrasi database & seed data awal
php artisan migrate --seed --force

# 4. Buat symlink storage
php artisan storage:link

# 5. Bersihkan dan optimasi cache konfigurasi, route & view
php artisan permission:cache-reset
php artisan optimize
```

---

## 📊 Data Seeder Historis (Januari 2025 – Oktober 2026)

Proyek ini dilengkapi rangkaian seeder terstruktur di dalam [`database/seeders/`](database/seeders/) untuk mensimulasikan skenario bisnis nyata selama **22 bulan berturut-turut (Januari 2025 s/d Oktober 2026)**:

| Seeder Class | Deskripsi Data yang Dibangkitkan |
| :--- | :--- |
| [`RolePermissionSeeder`](database/seeders/RolePermissionSeeder.php) | 57 Permission terstruktur, 3 Role (`Pemilik`, `Bendahara`, `Admin`), dan 3 akun pengguna default. |
| [`KategoriSeeder`](database/seeders/KategoriSeeder.php) & [`SatuanSeeder`](database/seeders/SatuanSeeder.php) | Kategori Souvenir Jadi, Bahan Baku, dan Kemasan beserta satuan standar gudang. |
| [`ProdukSeeder`](database/seeders/ProdukSeeder.php) | Katalog lengkap produk **Souvenir Jadi** (Gantungan Kunci Akrilik, Standee Anime, Pin, Tumbler, Totebag, Mug, Lanyard, Photocard) dan **Bahan Baku / Kemasan**. |
| [`KasMasukSeeder`](database/seeders/KasMasukSeeder.php) | Setoran modal pemilik dan pencairan pendapatan lain-lain sepanjang 2025–2026. |
| [`PembelianSeeder`](database/seeders/PembelianSeeder.php) | Transaksi pengadaan bahan baku & packaging dari supplier online lengkap dengan ongkir kargo dan status penerimaan. |
| [`PemakaianBahanSeeder`](database/seeders/PemakaianBahanSeeder.php) | Transaksi pengambilan material dari gudang untuk proses produksi di workshop. |
| [`PenerimaanProdukSeeder`](database/seeders/PenerimaanProdukSeeder.php) | Batch penyelesaian produksi souvenir dengan pencatatan kuantitas **Lolos QC** dan **Reject**. |
| [`TransaksiSeeder`](database/seeders/TransaksiSeeder.php) | Ratusan pesanan dari **Shopee, TikTok Shop, dan Lazada** dengan tren pertumbuhan musiman untuk menguji akurasi *Time-Series Forecasting*. |
| [`PengeluaranSeeder`](database/seeders/PengeluaranSeeder.php) | Riwayat pengeluaran yang mencakup 3 kantong kas: **Biaya Operasional & Ads**, **Keuangan Pribadi (Prive)**, dan **Kasbon Tim**. |

---

## 🧪 Pengujian Otomatis (Automated Testing)

Aplikasi ini dilengkapi *Feature Test Suite* berbasis **PHPUnit** pada [`tests/Feature/OnlineProcurementAndMaterialsTest.php`](tests/Feature/OnlineProcurementAndMaterialsTest.php) yang memverifikasi:
- Alur pengadaan bahan baku online, ongkos kirim, dan konfirmasi penerimaan paket ke stok gudang.
- Alur pemakaian bahan baku produksi dan penerimaan produk jadi melalui proses Quality Control (QC).
- Akurasi perhitungan ke-8 tab Laporan Keuangan & Analitik (mengecualikan pesanan `batal`/`retur` dari omzet dan memisahkan pengeluaran `pribadi`/`kasbon` dari Laba Bersih Bisnis).
- Keamanan otorisasi RBAC 57 permission untuk role `Pemilik`, `Bendahara`, dan `Admin`, termasuk render halaman Dashboard & Forecasting.

Jalankan seluruh pengujian dengan perintah:
```bash
php artisan test --compact
```
Atau jalankan spesifik file pengujian utama:
```bash
php artisan test --compact tests/Feature/OnlineProcurementAndMaterialsTest.php
```

---

## 🧰 Daftar Perintah Pemeliharaan (Maintenance Cheat Sheet)

| Kebutuhan | Perintah Terminal |
| :--- | :--- |
| **Reset Database & Isi Ulang Seeder 2025–2026** | `php artisan migrate:fresh --seed` |
| **Update Role & Permission Saja (Tanpa Hapus Data)** | `php artisan db:seed --class=RolePermissionSeeder` |
| **Reset Cache Spatie Permission** | `php artisan permission:cache-reset` |
| **Bersihkan Seluruh Cache (View, Route, Config)** | `php artisan optimize:clear` |
| **Format Kode PHP Sesuai Standar Laravel Pint** | `vendor/bin/pint` |
| **Jalankan Pengujian Otomatis (PHPUnit)** | `php artisan test --compact` |

---

*Dikembangkan untuk Sistem Informasi Manajemen, Keuangan & Forecasting **Toko Souvenir Jibranime**.*
