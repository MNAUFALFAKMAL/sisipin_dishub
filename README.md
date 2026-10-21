# SISIPIN DISHUB

**Sistem Informasi Arsip Dinamis** — Dinas Perhubungan Provinsi Lampung

---

## Tentang Proyek

SISIPIN DISHUB adalah aplikasi pengelolaan arsip dinas berbasis web untuk Dinas Perhubungan Provinsi Lampung. Aplikasi ini dikembangkan mengikuti standar kearsipan sesuai Pergub Lampung Nomor 16 Tahun 2023.

**Versi:** 1.0.0 (Development)  
**Framework:** Laravel 13  
**Frontend:** Tailwind CSS v3 + Alpine.js + Vite  
**Bahasa:** PHP 8.4+  
**Database:** MySQL / SQLite

---

## Prasyarat

Sebelum menjalankan aplikasi, pastikan sistem Anda memiliki:

| Perangkat Lunak | Versi Minimum |
|-----------------|---------------|
| PHP | 8.3+ (direkomendasikan 8.4) |
| Composer | 2.6+ |
| Node.js | 20+ |
| npm | 10+ |
| Database | MySQL 8.0+ / MariaDB 10.6+ / SQLite |

Ekstensi PHP yang diperlukan: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `intl`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `zip`, `zip`

---

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/MNAUFALFAKMAL/sisipin_dishub.git
cd sisipin_dishub
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Install Dependensi Frontend

```bash
npm install
```

### 4. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sisipin_dishub
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Atau gunakan SQLite untuk development:

```env
DB_CONNECTION=sqlite
# Buat file database
touch database/database.sqlite
```

### 5. Migrasi Database

```bash
php artisan migrate --seed
```

### 6. Build Asset Frontend

**Development (dengan hot reload):**

```bash
npm run dev
```

**Production:**

```bash
npm run build
```

---

## Menjalankan Aplikasi

### Development Server

```bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Vite dev server (hot reload)
npm run dev
```

Akses aplikasi di: **http://localhost:8000**

---

## Struktur Proyek Utama

```
sisipin_dishub/
├── app/                    # Logika aplikasi (Models, Controllers, Services)
├── bootstrap/              # Bootstrap framework
├── config/                 # File konfigurasi
├── database/               # Migrasi, factory, seeder
├── docs/                   # Dokumentasi (wireframe, WBS, glosarium, dsb.)
│   └── wireframe/          # Desain wireframe 10 layar
├── public/                 # Asset publik & entry point
├── resources/
│   ├── css/                # Tailwind CSS entry (app.css)
│   ├── js/                 # JavaScript entry (app.js, bootstrap.js)
│   └── views/              # Blade templates
│       ├── layouts/        # Layout utama (app.blade.php, auth.blade.php)
│       └── welcome.blade.php  # Halaman landing
├── routes/                 # Definisi rute (web.php, api.php)
├── storage/                # File storage, logs, cache
├── tests/                  # Unit & feature tests
├── vendor/                 # Dependensi Composer
├── .env                    # Environment configuration
├── artisan                 # CLI Laravel
├── composer.json           # Dependensi PHP
├── package.json            # Dependensi Node.js
├── tailwind.config.js      # Konfigurasi Tailwind CSS v3
├── postcss.config.js       # Konfigurasi PostCSS
├── vite.config.js          # Konfigurasi Vite
└── phpunit.xml             # Konfigurasi PHPUnit
```

---

## Rute Utama (Planned)

| Rute | Nama | Deskripsi |
|------|------|-----------|
| `/` | `home` | Halaman landing (dalam pengembangan) |
| `/masuk` | `login` | Halaman autentikasi |
| `/dasbor` | `dashboard` | Dasbor utama setelah login |
| `/surat-masuk` | `surat-masuk.index` | Daftar surat masuk |
| `/surat-keluar` | `surat-keluar.index` | Daftar surat keluar per jenis |
| `/klasifikasi` | `klasifikasi.index` | Kelola kode klasifikasi |
| `/laporan` | `laporan.index` | Laporan & cetak PDF |
| `/profil` | `profil.show` | Profil & kata sandi |
| `/tata-naskah` | `tata-naskah.index` | Tata naskah publik |

---

## Pengujian

```bash
# Jalankan semua test
php artisan test

# Jalankan test dengan coverage
php artisan test --coverage

# Jalankan test spesifik
php artisan test --filter=NamaTest
```

---

## Kode Standar & Linting

```bash
# PHP Code Style (Laravel Pint)
./vendor/bin/pint

# JavaScript/TypeScript Linting (jika dikonfigurasi)
npm run lint
```

---

## Deployment

### Production Build

```bash
# 1. Optimize autoloader
composer install --optimize-autoloader --no-dev

# 2. Cache konfigurasi
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Build asset production
npm run build

# 4. Migrasi database
php artisan migrate --force
```

### Environment Production

Pastikan `.env` production memiliki:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

LOG_CHANNEL=stack
LOG_LEVEL=error

CACHE_DRIVER=redis
SESSION_DRIVER=database
QUEUE_CONNECTION=redis
```

---

## Dokumentasi Teknis

- [Wireframe & Panduan Visual](docs/wireframe/README.md)
- [Rencana Git Flow](docs/rencana-git-flow.md)
- [Aturan Teknis Git](docs/git-flow.md)
- [Glosarium Istilah](docs/glosarium.md)
- [Panduan Kontribusi](CONTRIBUTING.md)
- [WBS Charter](docs/wbs.md)

---

## Tim Pengembang

| Peran | Nama | GitHub |
|-------|------|--------|
| PM & QA | Muhammad Naufal Fikri Akmal | [@MNAUFALFAKMAL](https://github.com/MNAUFALFAKMAL) |
| Backend | Nabila Ramadhani Mujahidin | [@nblable](https://github.com/nblable) |
| Frontend | Arta Eka Yuly Rajagukguk | [@artaeka](https://github.com/artaeka) |

---

## Lisensi

Proyek ini dikembangkan untuk Dinas Perhubungan Provinsi Lampung. Hak Cipta Dilindungi Undang-Undang.

---

## Kontak

**Dinas Perhubungan Provinsi Lampung**  
Alamat: Jl. Gatot Subroto No. 1, Bandar Lampung  
Email: dishub@lampungprov.go.id  
Website: https://dishub.lampungprov.go.id