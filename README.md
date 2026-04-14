# SIMAS ASET — SMKN 11 Kota Tangerang
## Sistem Informasi Manajemen Aset Sekolah

---

## 🎯 8 FITUR UTAMA

| # | Fitur | Deskripsi |
|---|-------|-----------|
| 1 | **Manajemen Data Aset** | CRUD aset lengkap: kode, nama, merek, serial, harga, tanggal beli, garansi, kondisi, lokasi, PJ |
| 2 | **Kategorisasi Aset** | Kelompokkan berdasarkan jenis (elektronik, furniture, kendaraan) & departemen |
| 3 | **Tracking Mutasi Aset** | Catat riwayat perpindahan aset antar divisi, karyawan, atau lokasi |
| 4 | **Manajemen Pemeliharaan** | Jadwal maintenance, riwayat perbaikan, biaya, teknisi, update status |
| 5 | **Kalkulator Depresiasi** | Garis Lurus & Saldo Menurun otomatis + grafik tren nilai aset |
| 6 | **Scan QR / Barcode** | Label QR setiap aset, cetak label, scan via kamera browser |
| 7 | **Laporan Inventaris** | Filter kondisi/departemen/kategori, cetak + tanda tangan |
| 8 | **Notifikasi Masa Berlaku** | Alert garansi hampir habis & jadwal maintenance 30 hari ke depan |

---

## 👥 SISTEM ROLE & HAK AKSES

| Role | Login | Dashboard | Lihat Data | Tambah/Edit/Hapus | Cetak QR | Kelola User |
|------|-------|-----------|------------|-------------------|----------|-------------|
| 👑 **Admin** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| 🏫 **Kepsek** | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| 👤 **Staff** | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |

> Kepsek & Staff hanya bisa **melihat** data, dashboard, laporan, dan notifikasi.
> Semua tombol aksi (Tambah, Edit, Hapus, Cetak QR) otomatis tersembunyi untuk Kepsek & Staff.

---

## 🔑 AKUN LOGIN DEFAULT

| Username | Password | Role |
|----------|----------|------|
| `admin` | `admin123` | Administrator |
| `kepsek` | `kepsek123` | Kepala Sekolah |
| `staff` | `staff123` | Staff |

---

## ⚡ CARA INSTALL CEPAT

### Langkah 1 — Buat Project Laravel
```bash
cd C:/laragon/www
composer create-project laravel/laravel simas-aset
```

### Langkah 2 — Copy File dari ZIP
Copy semua folder/file ini ke dalam `C:/laragon/www/simas-aset/`:
```
app/                → ke app/
bootstrap/app.php   → ke bootstrap/app.php  (TIMPA file lama!)
config/auth.php     → ke config/
database/           → ke database/
resources/          → ke resources/
routes/web.php      → ke routes/
.env.example        → ke root project
install.bat         → ke root project
```

### Langkah 3 — Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`, ubah bagian database:
```env
DB_DATABASE=simas_aset_smkn11
DB_USERNAME=root
DB_PASSWORD=         ← kosong jika Laragon default
```

### Langkah 4 — Buat Database
Buka phpMyAdmin → New → buat database:
```
Nama    : simas_aset_smkn11
Collation: utf8mb4_unicode_ci
```

### Langkah 5 — Install Package & Migrasi
```bash
composer require simplesoftwareio/simple-qrcode
php artisan migrate --seed
php artisan storage:link
```

### Langkah 6 — Jalankan
Pastikan Laragon **Start All**, lalu buka browser:
```
http://simas-aset.test
```

---

## 📁 STRUKTUR FILE

```
app/
  Http/
    Controllers/
      AuthController.php        ← Login & logout
      DashboardController.php   ← Statistik dashboard
      AsetController.php        ← CRUD Aset + QR Code (Fitur 1, 5, 6)
      MutasiAsetController.php  ← Tracking mutasi (Fitur 3)
      PemeliharaanController.php← Manajemen pemeliharaan (Fitur 4)
      LaporanController.php     ← Laporan & notifikasi (Fitur 7, 8)
      KategoriController.php    ← Master kategori (Fitur 2)
      DepartemenController.php  ← Master departemen (Fitur 2)
      KaryawanController.php    ← Master karyawan
      UserController.php        ← Manajemen user (Admin only)
    Middleware/
      RoleMiddleware.php        ← Kontrol akses per role
  Models/
    User.php                    ← Auth + role methods (canCreate, canEdit, dll)
    Aset.php                    ← Kalkulasi depresiasi otomatis
    MutasiAset.php, Pemeliharaan.php
    Kategori.php, Departemen.php, Karyawan.php
  Providers/
    AppServiceProvider.php      ← Locale Bahasa Indonesia

bootstrap/
  app.php                       ← Daftarkan middleware 'role'

config/
  auth.php                      ← Konfigurasi auth dengan username

database/
  migrations/                   ← 7 tabel (users + 6 tabel aset)
  seeders/DatabaseSeeder.php    ← 3 user + 4 kategori + 5 dept + 5 karya + 7 aset + 5 maintenance

resources/views/
  auth/login.blade.php          ← Login split-screen + loading screen animasi
  layouts/app.blade.php         ← Sidebar role-aware + responsif mobile
  dashboard/index.blade.php     ← Dashboard SMKN 11 + Chart.js
  aset/                         ← index, create, edit, show, qrcode, scan (role-controlled)
  mutasi/                       ← index, create, show
  pemeliharaan/                 ← index, create, edit, show
  kategori, departemen, karyawan/index.blade.php
  laporan/inventaris, notifikasi
  users/index.blade.php         ← Manajemen user (admin only)
  errors/403.blade.php          ← Halaman akses ditolak
  errors/404.blade.php          ← Halaman tidak ditemukan

routes/web.php                  ← Route dengan auth + role middleware
install.bat                     ← Script install otomatis Windows/Laragon
```

---

## 🔧 TROUBLESHOOTING

| Error | Solusi |
|-------|--------|
| Class not found | `php artisan optimize:clear && composer dump-autoload` |
| Table not found | `php artisan migrate:fresh --seed` ⚠️ hapus semua data |
| No encryption key | `php artisan key:generate` |
| QrCode not found | `composer require simplesoftwareio/simple-qrcode` |
| Login gagal | `php artisan config:clear && php artisan cache:clear` |
| 403 Forbidden | Pastikan role user sudah benar di database |
| Storage link exists | Hapus `public/storage` manual, lalu `php artisan storage:link` |

---

## 📦 DATA CONTOH (Seeder)

Setelah `php artisan migrate --seed` tersedia:
- **3 Akun**: Admin, Kepsek, Staff
- **4 Kategori**: Elektronik, Furniture, Kendaraan, Peralatan Praktik
- **5 Departemen**: IT, TU, Kurikulum, Sarana Prasarana, Kesiswaan
- **5 Karyawan**: Guru dan staf SMKN 11
- **7 Aset**: Beragam kondisi, beberapa garansi hampir habis
- **5 Pemeliharaan**: 4 terjadwal + 1 selesai

---

## 🖥️ TEKNOLOGI

- **Framework**: Laravel 11
- **Database**: MySQL 8.0
- **Frontend**: Bootstrap 5.3 + Chart.js 4
- **QR Code**: simple-qrcode ^4.2
- **Font**: Plus Jakarta Sans (Google Fonts)
- **Icons**: Bootstrap Icons 1.11

---

© 2025 SMKN 11 Kota Tangerang — Sistem Informasi Manajemen Aset
Dikembangkan untuk keperluan inventaris aset sekolah
