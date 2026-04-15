# Absensi Siswa Laravel

Sistem Informasi Absensi Siswa berbasis Laravel 11 dengan PHP 8.2. Aplikasi ini merupakan hasil migrasi dari aplikasi PHP native.

![Laravel](https://img.shields.io/badge/Laravel-11-orange.svg)
![PHP](https://img.shields.io/badge/PHP-8.2-blue.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

## Fitur

- **Dashboard** - Statistik kehadiran, charts, dan tren harian
- **Manajemen Siswa** - CRUD dengan import CSV, hapus batch, pagination
- **Manajemen Kelas** - CRUD dengan card layout dan total siswa
- **Absensi Manual** - Input kehadiran per tanggal dengan filter kelas
- **Absensi Barcode** - Scanner QR code untuk input cepat
- **Rekap Absensi** - Statistik per semester dengan export Excel/PDF
- **Kenaikan Kelas** - Proses kenaikan tingkat siswa
- **Kelulusan** - Proses kelulusan siswa kelas 12
- **Redistribusi** - Pindahkan siswa antar kelas
- **Tahun Ajaran & Semester** - Manajemen periode ajaran
- **User Management** - Role admin dan operator
- **Konfigurasi** - Logo dan warna sekolah

## Requirements

- PHP 8.2+
- Composer
- MySQL/MariaDB 5.7+
- Podman atau Docker (untuk container)
- Git

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/natedekaka/absensi-siswa-laravel.git
cd absensi-siswa-laravel
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Setup Environment

```bash
cp .env.example .env
# Atau buat file .env dengan konfigurasi berikut:
```

Edit file `.env`:

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
DB_PASSWORD=rootpass
```

Generate application key:

```bash
php artisan key:generate
```

### 4. Setup Database

Buat database:

```sql
CREATE DATABASE absensi_siswa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Import database dump:

```bash
mysql -u root -p absensi_siswa < database/absensi_siswa.sql
```

### 5. Jalankan Aplikasi

**Dengan Laravel Artisan:**
```bash
php artisan serve --host=0.0.0.0 --port=8082
```

**Dengan Docker/Podman:**
```bash
podman-compose up -d
```

Akses di http://localhost:8082

## Default Login

| Role | Username | Password |
|------|----------|----------|
| Admin | admin | admin |

> **Penting:** Ganti password default setelah login pertama!

## Struktur Project

```
absensi-siswa-laravel/
├── app/
│   ├── Http/Controllers/     # Controller aplikasi
│   ├── Models/               # Eloquent models
│   └── Providers/            # Service providers
├── bootstrap/                # Bootstrap files
├── config/                   # Konfigurasi Laravel
├── database/
│   ├── migrations/           # Database migrations
│   └── absensi_siswa.sql    # Full database dump
├── public/
│   └── storage/logos/       # Logo sekolah
├── resources/
│   └── views/                # Blade templates
│       ├── layouts/         # Layout utama
│       ├── auth/            # Halaman login
│       ├── dashboard/       # Dashboard
│       ├── siswa/           # Manajemen siswa
│       ├── kelas/          # Manajemen kelas
│       ├── absensi/        # Absensi manual & barcode
│       ├── rekap/          # Rekap absensi
│       ├── kenaikan/       # Kenaikan & kelulusan
│       ├── tahun-ajaran/   # Tahun ajaran
│       ├── user/           # User management
│       └── konfigurasi/    # Konfigurasi
├── routes/
│   └── web.php             # Web routes
├── storage/
│   └── app/                # Storage files
├── docker-compose.yml       # Podman/Docker compose
├── Dockerfile              # Container config
└── router.php             # PHP built-in server router
```

## Konfigurasi Container

### Podman (Direkomendasikan)

```bash
# Start container
podman-compose up -d

# Stop container
podman-compose down

# Restart container
podman-compose restart

# View logs
podman-compose logs -f
```

### Docker

```bash
docker-compose up -d
```

### Koneksi Database External

Jika database di container lain, update `docker-compose.yml`:

```yaml
environment:
  - DB_HOST=10.89.7.2  # IP container database
  - DB_PORT=3306
  - DB_DATABASE=absensi_siswa
  - DB_USERNAME=root
  - DB_PASSWORD=rootpass
```

## Panduan Pengguna

### 1. Setup Awal

1. Login dengan akun admin
2. Menu **Konfigurasi** → Upload logo & setting warna sekolah
3. Menu **Tahun Ajaran** → Tambah tahun ajaran dan semester
4. Aktifkan semester yang sedang berjalan

### 2. Manajemen Siswa

**Tambah Siswa Manual:**
1. Menu **Siswa** → Klik "Tambah Siswa"
2. Isi form NIS, NISN, Nama, Kelas, Jenis Kelamin
3. Klik Simpan

**Import dari CSV:**
1. Menu **Siswa** → Import CSV
2. Download template → Isi data sesuai format
3. Upload file CSV → Klik Import

### 3. Absensi Harian

**Input Manual:**
1. Menu **Absensi**
2. pilih Tanggal, Kelas, Semester
3. Klik Absensi pada setiap siswa
4. Pilih status: Hadir (H), Terlambat (T), Sakit (S), Izin (I), Alfa (A)
5. Klik Simpan

**Input Barcode:**
1. Menu **Absensi** → Tab Barcode
2. Scan QR code siswa atau input NIS manual
3. Status otomatis tersimpan

### 4. Kenaikan Kelas

**Proses Kenaikan:**
1. Menu **Kenaikan**
2. Pilih tingkat (X→XI atau XI→XII)
3. Klik "Proses Kenaikan"

**Redistribusi Kelas:**
1. Menu **Kenaikan** → Redistribusi
2. Centang siswa yang akan dipindahkan
3. Pilih kelas tujuan
4. Klik "Pindahkan"

### 5. Kelulusan

1. Menu **Kenaikan** → Kelulusan
2. Pilih tahun lulus
3. Klik "Proses Kelulusan"
4. Siswa kelas 12 akan berpindah ke alumni

### 6. Rekap Absensi

1. Menu **Rekap**
2. Pilih kelas dan range tanggal
3. Lihat statistik Semester 1 & 2
4. Export ke Excel/PDF

## API Routes

| Method | URI | Deskripsi |
|--------|-----|-----------|
| GET | / | Halaman login |
| POST | /login | Proses login |
| POST | /logout | Logout |
| GET | /dashboard | Dashboard |
| GET | /siswa | Daftar siswa |
| POST | /siswa | Tambah siswa |
| GET | /kelas | Daftar kelas |
| GET | /absensi | Input absensi |
| GET | /rekap | Rekap absensi |
| GET | /kenaikan | Kenaikan kelas |
| GET | /kelulusan | Kelulusan |
| GET | /tahun-ajaran | Tahun ajaran |

## Troubleshooting

### Logo tidak tampil
```bash
# Buat storage symlink
php artisan storage:link

# Atau copy manual
mkdir -p public/storage/logos
cp storage/app/public/logos/* public/storage/logos/
```

### Database connection error
1. Pastikan MySQL/MariaDB running
2. Cek kredensial di `.env`
3. Pastikan database `absensi_siswa` ada

### Container won't start
```bash
# Rebuild container
podman-compose down
podman-compose build --no-cache
podman-compose up -d
```

## License

MIT License - lihat file [LICENSE](LICENSE) untuk detail.

## Support

Untuk pertanyaan atau laporan bug, buka issue di:
https://github.com/natedekaka/absensi-siswa-laravel/issues
