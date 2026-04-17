# 📋 Absensi Siswa Laravel

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker" alt="Docker">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

> Sistem Informasi Absensi Siswa berbasis web dengan Laravel 11. Mudah dipasang, lengkap fiturnya, siap pakai untuk sekolah.

---

## 📝 Daftar Isi

- [Fitur](#-fitur)
- [Pemasangan](#-cara-memasang)
  - [Dengan Docker](#-dengan-docker)
  - [Tanpa Docker](#-tanpa-docker)
- [Cara Pakai](#-cara-pakai)
- [Troubleshooting](#-troubleshooting)
- [Struktur Project](#-struktur-project)

---

## ✨ Fitur

| Modul | Deskripsi |
|-------|-----------|
| 📊 **Dashboard** | Statistik kehadiran, charts, dan tren harian |
| 👨‍🎓 **Manajemen Siswa** | CRUD, import CSV, hapus batch, pagination |
| 🏫 **Manajemen Kelas** | CRUD dengan card layout dan total siswa |
| ✏️ **Absensi Manual** | Input kehadiran per tanggal dengan filter kelas |
| 📱 **Absensi Barcode** | Scanner QR code untuk input cepat |
| 📈 **Rekap Absensi** | Statistik per semester dengan export Excel/PDF |
| ⬆️ **Kenaikan Kelas** | Proses kenaikan tingkat siswa otomatis |
| 🎓 **Kelulusan** | Proses kelulusan siswa kelas 12 |
| 🔄 **Redistribusi** | Pindahkan siswa antar kelas |
| 📅 **Tahun Ajaran** | Manajemen periode dan semester ajaran |
| 👤 **User Management** | Role admin dan operator |
| ⚙️ **Konfigurasi** | Logo dan warna sekolah |

---

## 🚀 Cara Memasang

Pilih metode sesuai kebutuhan:

### 🐳 Dengan Docker

> **Recommended** - Paling cepat dan mudah. Semua sudah termasuk (App + Database).

#### 1. Clone Repository

```bash
git clone https://github.com/natedekaka/absensi-siswa-laravel.git
cd absensi-siswa-laravel
```

#### 2. Jalankan Container

```bash
docker-compose up -d
```

> ⚠️ **Catatan:** File `.env` tidak diperlukan karena semua konfigurasi sudah ada di `docker-compose.yml`.

#### 3. Tunggu Database Ready

```bash
docker-compose ps
```

Akan terlihat seperti ini jika sudah ready:

```
NAME                    STATUS
absensi-siswa-app       Up (healthy)
absensi-siswa-db        Up (healthy)
```

#### 4. Akses Aplikasi

| Service | URL |
|---------|-----|
| 🌐 **Aplikasi** | http://localhost:8082 |
| 🗄️ **phpMyAdmin** | http://localhost:8084 |

**phpMyAdmin Login:**
- Server: `db`
- Username: `root`
- Password: `rootpass`

#### 5. Login Default

| Role | Username | Password |
|:----:|:--------:|:--------:|
| 👑 Admin | `admin` | `admin` |

> 🔒 **Penting:** Segera ganti password default setelah login pertama!

---

### 💻 Tanpa Docker

> Untuk development local dengan PHP & MySQL terinstall di komputer.

#### Prasyarat

- PHP 8.2+
- Composer
- MySQL/MariaDB 5.7+

#### Langkah 1: Install Dependencies

```bash
composer install
```

#### Langkah 2: Buat File `.env`

```bash
cp .env.example .env
```

#### Langkah 3: Edit Konfigurasi `.env`

Buka file `.env` dan sesuaikan:

```env
APP_NAME="Absensi Siswa"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8082

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensi_siswa
DB_USERNAME=root
DB_PASSWORD=your_password
```

> 📌 Ganti `DB_PASSWORD` sesuai password MySQL kamu.

#### Langkah 4: Generate App Key

```bash
php artisan key:generate
```

#### Langkah 5: Buat Database

```bash
mysql -u root -p -e "CREATE DATABASE absensi_siswa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

#### Langkah 6: Import Database

```bash
mysql -u root -p absensi_siswa < database/absensi_siswa.sql
```

#### Langkah 7: Jalankan

```bash
php artisan serve --host=0.0.0.0 --port=8082
```

Akses di `http://localhost:8082`

---

## 📖 Cara Pakai

### ⚡ Setup Awal

Setelah login pertama kali:

```
1. 🏫 Konfigurasi  → Upload logo & atur warna sekolah
2. 📅 Tahun Ajaran → Tambah tahun ajaran & semester
3. ✅ Aktifkan     → Set semester yang berjalan sebagai "Aktif"
```

---

### 👨‍🎓 Manajemen Siswa

#### Tambah Manual

```
Menu Siswa → Tambah Siswa → Isi form (NIS, NISN, Nama, Kelas, JK) → Simpan
```

#### Import CSV

```
Menu Siswa → Import CSV → Download template → Isi data → Upload → Import
```

---

### ✏️ Absensi Harian

#### Input Manual

```
Menu Absensi → Pilih Tanggal, Kelas, Semester → Klik status tiap siswa → Simpan
```

**Kode Status:**

| Kode | Status | Warna |
|:----:|--------|-------|
| H | Hadir | 🟢 Hijau |
| T | Terlambat | 🟡 Kuning |
| S | Sakit | 🔵 Biru |
| I | Izin | 🟠 Orange |
| A | Alfa | 🔴 Merah |

#### Input Barcode

```
Menu Absensi → Tab Barcode → Scan QR / Input NIS → Otomatis tersimpan
```

---

### ⬆️ Kenaikan & Kelulusan

#### Kenaikan Kelas

```
Menu Kenaikan → Pilih Tingkat → Centang siswa → Proses Kenaikan
```

#### Redistribusi (Pindah Kelas)

```
Menu Kenaikan → Redistribusi → Centang siswa → Pilih kelas tujuan → Pindahkan
```

#### Kelulusan

```
Menu Kenaikan → Kelulusan → Pilih tahun → Proses → Siswa kelas 12 → Alumni
```

---

### 📊 Rekap Absensi

```
Menu Rekap → Pilih Kelas & Tanggal → Lihat statistik → Export Excel/PDF
```

---

### 👤 Manajemen User

```
Menu User → Tambah User → Pilih Role (Admin/Operator) → Simpan
```

| Role | Akses |
|------|-------|
| 👑 Admin | Semua menu |
| 👤 Operator | Hanya fitur absensi |

---

## ❓ Troubleshooting

### Container tidak mau start

```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Database connection error

```bash
# Cek status container
docker-compose ps

# Cek logs database
docker-compose logs db

# Tunggu sampai status healthy
```

### Aplikasi blank/putih

```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear
```

### Reset Database

```bash
docker-compose exec db mysql -uroot -prootpass -e "DROP DATABASE absensi_siswa"
docker-compose restart
```

---

## 📁 Struktur Project

```
absensi-siswa-laravel/
├── app/
│   ├── Http/Controllers/     # Controller
│   ├── Models/               # Model Eloquent
│   └── Providers/           # Service Providers
├── bootstrap/
├── config/
├── database/
│   ├── migrations/           # Database migrations
│   ├── seeders/             # Seeders
│   └── absensi_siswa.sql    # Full database dump
├── public/
├── resources/views/
│   ├── layouts/             # Layout utama
│   ├── auth/                # Login
│   ├── dashboard/           # Dashboard
│   ├── siswa/               # Siswa
│   ├── kelas/               # Kelas
│   ├── absensi/             # Absensi
│   ├── rekap/               # Rekap
│   ├── kenaikan/            # Kenaikan
│   ├── tahun-ajaran/        # Tahun Ajaran
│   ├── user/                # User
│   └── konfigurasi/         # Konfigurasi
├── routes/
├── storage/
├── docker-compose.yml       # Docker + Database
├── Dockerfile
├── router.php
└── README.md
```

---

## 📜 License

MIT License - Bebas digunakan untuk keperluan apapun.

---

## 💬 Support

Ada pertanyaan atau menemukan bug?

👉 https://github.com/natedekaka/absensi-siswa-laravel/issues

---

<p align="center">
  Dibuat dengan ❤️ menggunakan Laravel 11
</p>
