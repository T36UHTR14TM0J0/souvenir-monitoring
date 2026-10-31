# 🛍️ Souvenir Monitoring System

Aplikasi Manajemen & Pemantauan Keuangan, Penjualan, Stok Produk, dan Operasional Toko Souvenir berbasis **Laravel**.

---

## 📋 Daftar Isi
1. [Kebutuhan Sistem (Prerequisites)](#-kebutuhan-sistem-prerequisites)
2. [Instalasi di Komputer Lokal (Localhost / Laragon / XAMPP)](#-instalasi-di-komputer-lokal-localhost)
3. [Instalasi di Server Hosting (cPanel / DomaiNesia / Shared Hosting)](#-instalasi-di-server-hosting-cpanel--domainesia)
4. [Akun Default & Hak Akses (Credentials)](#-akun-default--hak-akses-credentials)
5. [Struktur Branch Git & Fitur Tambahan](#-struktur-branch-git--fitur-tambahan)
6. [Daftar Perintah Pemeliharaan (Maintenance Cheat Sheet)](#-daftar-perintah-pemeliharaan)

---

## ⚙️ Kebutuhan Sistem (Prerequisites)

* **PHP:** Versi 8.2 atau 8.3 (dengan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `gd`, `fileinfo`, `zip`)
* **Composer:** Versi 2.x
* **Database:** MySQL 8.x atau MariaDB 10.4+
* **Web Server:** Apache / Nginx / OpenLiteSpeed
* **Git:** Terpasang di komputer dan server hosting

---

## 💻 Instalasi di Komputer Lokal (Localhost)

Panduan untuk Windows (Laragon / XAMPP), macOS, atau Linux:

### 1. Clone Repositori
Buka terminal / PowerShell dan arahkan ke direktori web server Anda (misal `D:\laragon\www\` atau `C:\xampp\htdocs\`):
```bash
git clone https://github.com/T36UHTR14TM0J0/souvenir-monitoring.git
cd souvenir-monitoring
```

### 2. Pasang Dependensi Composer
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
# Windows PowerShell
copy .env.example .env

# Linux / macOS / Git Bash
cp .env.example .env
```

Buka file `.env` dan sesuaikan koneksi database MySQL:
```ini
APP_NAME="Souvenir Monitoring"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

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

### 5. Buat Database & Jalankan Migrasi + Seeder
Buat database bernama `souvenir_monitoring` di phpMyAdmin / MySQL client Anda, lalu jalankan:
```bash
php artisan migrate --seed
```
*Perintah ini akan membuat seluruh tabel dan mengisi akun pengguna default serta data master awal.*

### 6. Buat Symlink Storage (Penting untuk upload gambar & struk)
```bash
php artisan storage:link
```

### 7. Jalankan Server Lokal
```bash
php artisan serve
```
Buka browser dan akses: **`http://127.0.0.1:8000`** (atau via Laragon virtual host: `http://souvenir-monitoring.test`).

---

## 🌐 Instalasi di Server Hosting (cPanel / DomaiNesia)

Berikut panduan langkah-demi-langkah memasang aplikasi pada server hosting cPanel:

### Langkah 1: Buat Database MySQL di cPanel
1. Masuk ke cPanel hosting Anda.
2. Buka menu **MySQL Databases** / **MySQL Database Wizard**.
3. Buat database baru (misal: `tokosouv_souvenirdb`).
4. Buat user database baru (misal: `tokosouv_dbuser`) dan buat password yang kuat.
5. Hubungkan User ke Database dengan mencentang **ALL PRIVILEGES**.

### Langkah 2: Clone Repositori ke Hosting
1. Buka menu **Terminal** di cPanel.
2. Arahkan ke folder tujuan (misal di dalam `public_html` atau root user):
   ```bash
   cd ~/public_html
   git clone https://github.com/T36UHTR14TM0J0/souvenir-monitoring.git
   cd souvenir-monitoring
   ```
   > **Catatan jika Repo Private:**  
   > Jika meminta username & token, masukkan Personal Access Token (PAT) GitHub Anda atau ubah URL remote dengan token:
   > `git remote set-url origin https://TOKEN_ANDA@github.com/T36UHTR14TM0J0/souvenir-monitoring.git`

### Langkah 3: Konfigurasi File `.env` di Hosting
Salin file `.env` dan sesuaikan untuk environment production:
```bash
cp .env.example .env
nano .env   # atau edit melalui File Manager cPanel
```

Pastikan variabel penting ini diatur:
```ini
APP_NAME="Souvenir Monitoring"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://tokosouvenirjibranime.my.id/souvenir-monitoring/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tokosouv_souvenirdb
DB_USERNAME=tokosouv_dbuser
DB_PASSWORD=PasswordDatabaseAnda
```

### Langkah 4: Jalankan Perintah Setup Production
Jalankan rangkaian perintah berikut di Terminal cPanel:

```bash
# 1. Pasang dependensi tanpa package dev (lebih cepat & aman)
composer install --no-dev --optimize-autoloader

# 2. Generate Application Key (jika belum ada)
php artisan key:generate

# 3. Jalankan migrasi dan isi akun default
php artisan migrate --seed --force

# 4. Hubungkan link storage upload
php artisan storage:link

# 5. Optimasi performa cache Laravel
php artisan optimize
```

### Langkah 5: Pengaturan Akses URL / Document Root
* **Jika menggunakan Subdomain (Direkomendasikan):**  
  Arahkan *Document Root* subdomain langsung ke folder `souvenir-monitoring/public`.
* **Jika menggunakan Subfolder (`public_html/souvenir-monitoring`):**  
  Akses aplikasi via browser di:  
  `http://domain-anda.com/souvenir-monitoring/public/index.php/login`

---

## 🔑 Akun Default & Hak Akses (Credentials)

Setelah menjalankan `php artisan db:seed`, sistem telah menyediakan 3 akun default siap pakai:

| Role | Email Login | Password Default | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Pemilik (Owner)** | `pemilik@gmail.com` | `Pemilik123!` | Akses penuh ke seluruh menu, laporan laba rugi, keuangan & audit sistem |
| **Admin** | `admin@gmail.com` | `Admin123!` | Kelola produk, stok barang souvenir, kategori, dan master data |
| **Kasir** | `kasir@gmail.com` | `Kasir123!` | Mengelola transaksi kasir POS, penjualan, dan cetak struk |

> ⚠️ **PENTING:** Segera ubah password default ini setelah login pertama kali di server production demi keamanan.

---

## 🌿 Struktur Branch Git & Fitur Tambahan

Repositori ini memiliki 2 branch terpisah untuk menjaga stabilitas sistem:

1. **`main` (Production Base):**  
   Branch utama yang stabil dan menjalankan sistem monitoring souvenir standar.
2. **`feature/upgrade-keuangan-v2` (Upgrade Keuangan Mini ERP):**  
   Branch khusus yang memisahkan **3 Kantong Kas** (Bisnis Toko, Kebutuhan Pribadi/Prive Pemilik, dan Kasbon Tim) serta dilengkapi modul *Kas Masuk* modal awal dan laporan keuangan 4 tab.  
   * Dokumentasi lengkap upgrade ini tersedia di file: [`DOKUMENTASI_FITUR_KEUANGAN.md`](DOKUMENTASI_FITUR_KEUANGAN.md) pada branch tersebut.
   * Lampiran dokumen PDF resmi: `public/docs/Rincian_Penambahan_Fitur_Keuangan_Rp600rb.pdf`.

**Cara mengaktifkan branch upgrade keuangan di server:**
```bash
git checkout feature/upgrade-keuangan-v2
php artisan migrate --force
php artisan optimize:clear
php artisan import:keuangan-2025    # Mengimpor data riil Excel 2025
```

---

## 🛠️ Daftar Perintah Pemeliharaan

Gunakan perintah-perintah ini untuk perawatan rutin aplikasi:

* **Tarik Update Terbaru dari GitHub:**
  ```bash
  git pull origin main
  php artisan migrate --force
  php artisan optimize
  ```
* **Bersihkan Seluruh Cache Aplikasi (Jika ada error tampilan / route):**
  ```bash
  php artisan optimize:clear
  ```
* **Simpan Kredensial Git di Server (Biar tidak ditanya username/password):**
  ```bash
  git config --global credential.helper store
  ```
* **Cek Status Database Migration:**
  ```bash
  php artisan migrate:status
  ```

---

*Dikembangkan untuk Sistem Pemantauan Toko Souvenir.*
