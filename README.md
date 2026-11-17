# 💅 NAIL ART STOCK MANAGEMENT SYSTEM

Aplikasi Web Full-Stack berbasis **Laravel PHP** & **Tailwind CSS** untuk mengelola persediaan barang dan bahan *nail art* (kutek gel, base/top coat, nail tips, stiker kuku, lem, rhinestone, dan kuas).

Desain antarmuka dirancang modern, *aesthetic*, elegan, dan responsif dengan palet warna **pink pastel**, **putih**, dan **ungu muda (*lilac*)** yang sangat cocok untuk bisnis salon kuku dan kecantikan.

---

## 🌟 Fitur Utama Sistem

1. **Halaman Autentikasi Admin (Login & Logout)**
   - Login admin dengan email dan password.
   - Dilengkapi proteksi session, validasi input, fitur *remember me*, dan *toggle show/hide password*.
   - Proteksi middleware auth pada seluruh rute internal.

2. **Dashboard Utama & Statistik Interaktif**
   - **6 Metrik Utama**: Total Seluruh Barang, Total Kategori, Total Stok Tersedia, Barang Hampir Habis, Masuk Bulan Ini, dan Keluar Bulan Ini.
   - **Grafik Tren Stok (Chart.js)**: Visualisasi perbandingan barang masuk vs keluar selama 7 hari terakhir.
   - **Daftar Reorder Cepat**: Tabel barang dengan stok kritis / habis yang perlu segera dibeli kembali.
   - **Aktivitas Terkini**: Ringkasan transaksi mutasi stok terbaru.

3. **Manajemen Data Barang (CRUD Lengkap)**
   - **Create**: Tambah barang dengan kode otomatis, nama barang, kategori, merek, satuan, harga beli, stok awal, dan stok minimum.
   - **Read**: Tabel katalog rapi dengan indikator status warna (🟢 Aman, 🟠 Menipis, 🔴 Habis).
   - **Update**: Edit rincian informasi dan spesifikasi barang.
   - **Delete**: Hapus barang dengan dialog konfirmasi aman.
   - **Filter & Search**: Pencarian cerdas berdasarkan nama, kode, atau merek, serta filter per kategori dan status stok.

4. **Manajemen Kategori Barang (CRUD)**
   - Pengelompokan jenis barang (Kutek, Base & Top Coat, Aksesoris, Alat, Perawatan, Lem).
   - Modal edit kategori interaktif.
   - Proteksi penghapusan jika kategori masih memiliki barang terkait.

5. **Transaksi Barang Masuk (Restok / Pembelian)**
   - Form pencatatan barang masuk: Tanggal, Nama Barang, Jumlah, dan Keterangan / Supplier / No. Nota.
   - **Otomatisasi Stok**: Jumlah stok barang bertambah secara otomatis dan realtime di database setelah disimpan.

6. **Transaksi Barang Keluar (Pemakaian Salon)**
   - Form pencatatan pemakaian bahan nail art untuk pelanggan salon.
   - **Pencegahan Stok Negatif (Anti-Minus)**: Sistem secara otomatis menolak transaksi jika jumlah pemakaian melebihi stok yang tersedia.
   - **Otomatisasi Stok**: Jumlah stok barang langsung berkurang otomatis di database.

7. **Notifikasi & Peringatan Stok Menipis**
   - Sistem deteksi otomatis untuk barang dengan kondisi `stok <= stok_minimum`.
   - Kartu peringatan visual dengan indikator warna merah (Habis) dan oranye (Menipis).
   - Tampilan progress bar level ketersediaan stok fisik terhadap ambang batas minimum.
   - Tombol langsung untuk menuju restok barang masuk.

8. **Riwayat & Jurnal Mutasi Transaksi**
   - Menampilkan seluruh transaksi barang masuk dan keluar secara kronologis.
   - Filter rentang tanggal (*Dari Tanggal* s/d *Sampai Tanggal*), filter jenis transaksi (Masuk / Keluar), dan pencarian nama barang.
   - Tombol cetak (*Print/PDF*) siap pakai.

---

## 🛠️ Spesifikasi Teknologi

- **Backend Framework**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL / MariaDB (XAMPP) & SQLite (File-based)
- **Frontend**: HTML5, Blade Templating, Tailwind CSS (CDN), FontAwesome 6 Icons
- **Grafik**: Chart.js 4
- **Tipografi**: Plus Jakarta Sans & Poppins

---

## 📁 Struktur Folder Proyek

```text
nail-art-stock-management/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php          # Controller login & logout
│   │       ├── DashboardController.php     # Controller metrik & chart dashboard
│   │       ├── CategoryController.php      # Controller CRUD kategori
│   │       ├── ItemController.php          # Controller CRUD barang & filter
│   │       └── TransactionController.php   # Controller barang masuk, keluar & riwayat
│   └── Models/
│       ├── User.php                        # Model akun admin
│       ├── Category.php                    # Model kategori barang
│       ├── Item.php                        # Model data barang & status stok
│       └── Transaction.php                 # Model mutasi transaksi masuk/keluar
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2024_01_01_000001_create_categories_table.php
│   │   ├── 2024_01_01_000002_create_items_table.php
│   │   └── 2024_01_01_000003_create_transactions_table.php
│   ├── seeders/
│   │   └── DatabaseSeeder.php              # Data awal admin, kategori, barang & transaksi
│   ├── database.sqlite                     # Database SQLite siap pakai langsung
│   └── nail_art_stock.sql                  # File dump SQL untuk import ke MySQL (phpMyAdmin)
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php               # Template layout utama & sidebar navigasi
│       ├── auth/
│       │   └── login.blade.php             # Halaman login estetis
│       ├── categories/
│       │   └── index.blade.php             # Manajemen kategori
│       ├── items/
│       │   ├── index.blade.php             # Tabel katalog barang & filter
│       │   ├── create.blade.php            # Form tambah barang baru
│       │   └── edit.blade.php              # Form edit barang
│       ├── transactions/
│       │   ├── masuk.blade.php             # Form & riwayat barang masuk
│       │   ├── keluar.blade.php            # Form & riwayat barang keluar
│       │   └── riwayat.blade.php           # Log mutasi seluruh transaksi
│       ├── notifications/
│       │   └── index.blade.php             # Peringatan stok menipis
│       └── dashboard.blade.php             # Dashboard utama & visualisasi grafik
├── routes/
│   └── web.php                             # Rute seluruh modul aplikasi
├── tests/
│   └── Feature/
│       └── NailArtStockTest.php            # 17 Unit & Feature Automated Tests
├── .env                                    # Konfigurasi aplikasi aktif
└── .env.mysql                              # Template konfigurasi MySQL (XAMPP)
```

---

## 🔑 Kredensial Login Demo

Gunakan akun administrator berikut untuk masuk ke sistem:

- **URL Login**: `http://localhost:8000` atau `http://localhost:8000/login`
- **Email**: `admin@nailart.com`
- **Password**: `password123`

*(Kredensial demo ini juga sudah terisi otomatis pada form login untuk kemudahan pengujian).*

---

## 🚀 Panduan Menjalankan Aplikasi Secara Lokal

### Opsi A: Menjalankan Langsung (Menggunakan SQLite - Siap Pakai)
Aplikasi sudah terkonfigurasi dan seluruh data awal (seeder) sudah terisi otomatis ke dalam `database/database.sqlite`.

1. Buka Terminal / PowerShell di folder proyek:
   ```powershell
   cd "d:\prposal proyek- sistem pengelolaan stok barang nail art\nail-art-stock-management"
   ```

2. Jalankan server lokal Laravel:
   ```powershell
   php artisan serve
   ```

3. Buka browser dan akses:
   ```text
   http://localhost:8000
   ```

---

### Opsi B: Menjalankan Menggunakan MySQL (XAMPP)
Jika ingin menggunakan server MySQL XAMPP:

1. Buka **XAMPP Control Panel**, lalu klik **Start** pada modul **Apache** dan **MySQL**.
2. Buat database baru bernama `nail_art_stock` melalui **phpMyAdmin** (`http://localhost/phpmyadmin`) atau terminal MySQL:
   ```sql
   CREATE DATABASE nail_art_stock;
   ```
3. Ubah pengaturan pada file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nail_art_stock
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Atau Anda bisa menyalin isi file `.env.mysql` ke `.env`)*
4. Jalankan migrasi dan seeder:
   ```powershell
   php artisan migrate:fresh --seed
   ```
   *Alternatif*: Anda juga dapat mengimpor file `database/nail_art_stock.sql` langsung melalui menu Import di phpMyAdmin.
5. Jalankan server:
   ```powershell
   php artisan serve
   ```

---

## 🧪 Pengujian Otomatis (Automated Tests)

Seluruh fitur inti telah dilengkapi dengan unit & feature test otomatis (17 pengujian, 54 asersi). Untuk menjalankan pengujian:

```powershell
php artisan test
```

Hasil uji mengonfirmasi bahwa:
- ✅ Autentikasi login & logout berhasil divalidasi.
- ✅ Dashboard menampilkan seluruh data metrik dengan tepat.
- ✅ CRUD Kategori & CRUD Barang berfungsi penuh.
- ✅ Barang masuk menambah stok secara otomatis.
- ✅ Barang keluar mengurangi stok secara otomatis.
- ✅ Sistem secara aman menolak mutasi yang mengakibatkan stok minus.
- ✅ Peringatan stok menipis dan habis berfungsi secara realtime.
