# SISIPIN DISHUB

Sistem Informasi Arsip Internal untuk Subbagian Umum dan Kepegawaian
Dinas Perhubungan Provinsi Lampung.

> **Status:** repositori baru. Belum ada kode aplikasi — pengembangan dimulai dari nol
> mengikuti rencana kerja (WBS) 12 minggu, 7 September – 27 November 2026.

## Tentang

SISIPIN DISHUB adalah aplikasi web untuk mencatat, mengelompokkan, dan menelusuri
surat masuk dan surat keluar, meneruskan disposisi, serta menyusun laporan kearsipan.
Sistem dipakai oleh dua peran (Kepala Subbagian dan Staff Subbagian), ditambah satu
akun pengelola teknis dan halaman publik tanpa akun.

## Rencana teknologi

| Bagian | Pilihan |
|---|---|
| Kerangka kerja | Laravel 13 (PHP 8.5) |
| Basis data | MySQL 8 / MariaDB 10.5+ |
| Tampilan | Blade, Tailwind CSS, Alpine.js |
| Berkas & PDF | DomPDF / mPDF, pengolah PDF |
| Penyajian | Layanan hosting sewa (tanpa perangkat keras sendiri) |

## Tim dan peran

| Nama | Peran GitHub | Fokus |
|---|---|---|
| Muhammad Naufal Fikri Akmal | `@MNAUFALFAKMAL` | Project Manager & QA — dokumen, pengujian, rilis |
| Nabila Ramadhani Mujahidin | _(diundang)_ | Backend — basis data, logika, keamanan |
| Arta Eka Yuly Rajagukguk | _(diundang)_ | Frontend — tampilan, alur, responsif |

## Alur kerja

Repositori ini memakai alur bercabang: `main` hanya menerima rilis, `develop` menjadi
tempat integrasi, dan setiap paket kerja dikerjakan pada cabang sendiri.

- Panduan lengkap: [`docs/git-flow.md`](docs/git-flow.md)
- Ringkasan kontribusi: [`CONTRIBUTING.md`](CONTRIBUTING.md)
- Papan pekerjaan: Issues + Milestone M1–M5
- Jangan pernah menulis langsung ke `main` atau `develop`.

## Struktur cabang

```
main         rilis / produksi (dilindungi)
└── develop  integrasi harian (dilindungi)
    ├── feature/<kode-wbs>-<ringkas>
    ├── fix/<kode-wbs>-<ringkas>
    ├── docs/<kode-wbs>-<ringkas>
    ├── test/<kode-wbs>-<ringkas>
    └── chore/<kode-wbs>-<ringkas>
hotfix/<ringkas>   darurat, bercabang dari main
```

## Lisensi

Belum ditetapkan.
