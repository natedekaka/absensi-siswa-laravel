# Absensi Siswa Laravel

Sistem Informasi Absensi Siswa berbasis Laravel 11 dengan PHP 8.2.

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

## Cara Memasang (Installation)

### Prasyarat (Requirements)

- Git
- Docker Desktop / Podman
- Web Browser

### Langkah 1: Clone Repository

```bash
git clone https://github.com/natedekaka/absensi-siswa-laravel.git
cd absensi-siswa-laravel
```

### Langkah 2: Jalankan dengan Docker

> **Catatan:** Dengan Docker, file `.env` tidak diperlukan karena semua konfigurasi sudah ada di `docker-compose.yml`.

```bash
docker-compose up -d
```

Tunggu beberapa menit hingga database ter-import. Cek status:

```bash
docker-compose ps
```

### Langkah 3: Akses Aplikasi

Buka browser dan kunjungi:
```
http://localhost:8082
```

### Langkah 4: Login

| Role | Username | Password |
|------|----------|----------|
| Admin | admin | admin |

> **Penting:** Segera ganti password default setelah login pertama!

---

## Cara Pakai (Usage Guide)

### Setup Awal Setelah Install

1. **Login** dengan akun admin (admin / admin)
2. **Konfigurasi Sekolah**: Menu **Konfigurasi** → Upload logo & atur warna sekolah
3. **Set Tahun Ajaran**: Menu **Tahun Ajaran** → Tambah tahun ajaran dan semester
4. **Aktifkan Semester**: Pilih semester yang sedang berjalan sebagai "Aktif"

### Manajemen Siswa

**Tambah Siswa Manual:**
1. Menu **Siswa** → Klik "Tambah Siswa"
2. Isi form: NIS, NISN, Nama, Kelas, Jenis Kelamin
3. Klik **Simpan**

**Import dari CSV:**
1. Menu **Siswa** → Klik **Import CSV**
2. Download template → Isi data sesuai format kolom
3. Upload file CSV → Klik **Import**

### Absensi Harian

**Input Manual:**
1. Menu **Absensi**
2. Pilih Tanggal, Kelas, Semester
3. Klik tombol absensi pada setiap siswa
4. Pilih status:
   - **H** = Hadir
   - **T** = Terlambat
   - **S** = Sakit
   - **I** = Izin
   - **A** = Alfa (Tidak Hadir)
5. Klik **Simpan**

**Input Barcode:**
1. Menu **Absensi** → Tab **Barcode**
2. Scan QR code siswa atau input NIS manual
3. Status otomatis tersimpan

### Kenaikan & Kelulusan

**Proses Kenaikan Kelas:**
1. Menu **Kenaikan**
2. Pilih tingkat (X→XI atau XI→XII)
3. Centang siswa yang naik
4. Klik **Proses Kenaikan**

**Redistribusi (Pindah Kelas):**
1. Menu **Kenaikan** → Tab **Redistribusi**
2. Centang siswa yang akan dipindahkan
3. Pilih kelas tujuan
4. Klik **Pindahkan**

**Proses Kelulusan:**
1. Menu **Kenaikan** → Tab **Kelulusan**
2. Pilih tahun lulus
3. Klik **Proses Kelulusan**
4. Siswa kelas 12 akan berpindah ke data alumni

### Rekap Absensi

1. Menu **Rekap**
2. Pilih kelas dan range tanggal
3. Lihat statistik per semester
4. Klik **Export** untuk download Excel/PDF

### Manajemen User

1. Menu **User**
2. Tambah user baru dengan role Admin atau Operator
3. Operator memiliki akses terbatas hanya ke fitur absensi

---

## Troubleshooting

### Container tidak mau start
```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Database connection error
1. Pastikan container running: `docker-compose ps`
2. Cek logs: `docker-compose logs db`
3. Tunggu sampai database healthy

### Aplikasi blank/blank page
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear
```

### Reset Database
```bash
docker-compose exec db mysql -uroot -prootpass -e "DROP DATABASE absensi_siswa; CREATE DATABASE absensi_siswa;"
docker-compose restart
```

---

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
│   ├── seeders/              # Data seeders
│   └── absensi_siswa.sql     # Full database dump
├── public/
│   └── storage/logos/        # Logo sekolah
├── resources/
│   └── views/                # Blade templates
│       ├── layouts/          # Layout utama
│       ├── auth/             # Halaman login
│       ├── dashboard/        # Dashboard
│       ├── siswa/            # Manajemen siswa
│       ├── kelas/            # Manajemen kelas
│       ├── absensi/          # Absensi manual & barcode
│       ├── rekap/            # Rekap absensi
│       ├── kenaikan/         # Kenaikan & kelulusan
│       ├── tahun-ajaran/     # Tahun ajaran
│       ├── user/             # User management
│       └── konfigurasi/      # Konfigurasi
├── routes/
│   └── web.php              # Web routes
├── storage/
│   └── app/                 # Storage files
├── docker-compose.yml        # Docker compose (App + Database)
├── Dockerfile               # Container config
├── router.php               # PHP built-in server router
└── README.md
```

---

## Development (Tanpa Docker)

### Prasyarat
- PHP 8.2+
- Composer
- MySQL/MariaDB 5.7+

### Setup

**1. Install Dependencies**
```bash
composer install
```

**2. Buat file `.env`**
```bash
cp .env.example .env
```

**3. Konfigurasi `.env`**

Buka file `.env` dan sesuaikan konfigurasi database:

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

> **Sesuaikan:** Ganti `DB_HOST`, `DB_PORT`, `DB_USERNAME`, dan `DB_PASSWORD` sesuai konfigurasi MySQL di komputer kamu.

**4. Generate Application Key**
```bash
php artisan key:generate
```

**5. Buat Database**
```bash
mysql -u root -p -e "CREATE DATABASE absensi_siswa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**6. Import Database**
```bash
mysql -u root -p absensi_siswa < database/absensi_siswa.sql
```

**7. Jalankan Aplikasi**
```bash
php artisan serve --host=0.0.0.0 --port=8082
```

Akses di http://localhost:8082

---

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

---

## License

MIT License

## Support

Untuk pertanyaan atau laporan bug, buka issue di:
https://github.com/natedekaka/absensi-siswa-laravel/issues
