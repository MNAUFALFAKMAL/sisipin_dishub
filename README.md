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
| Nabila Ramadhani Mujahidin | `@nblable` | Backend — basis data, logika, keamanan |
| Arta Eka Yuly Rajagukguk | `@artaeka` | Frontend — tampilan, alur, responsif |

## Alur kerja

Repositori ini memakai alur bercabang: `main` hanya menerima rilis, `develop` menjadi
tempat integrasi, dan setiap paket kerja dikerjakan pada cabang sendiri.

- **Rencana yang dibahas tim (mulai di sini):** [`docs/rencana-git-flow.md`](docs/rencana-git-flow.md)
- Rujukan teknis aturan: [`docs/git-flow.md`](docs/git-flow.md)
- Peta 56 paket ke cabang: [`docs/peta-paket-cabang.md`](docs/peta-paket-cabang.md)
- Glosarium istilah layar: [`docs/glosarium.md`](docs/glosarium.md)
- Matriks uji R-01…R-20: [`docs/matriks-uji.md`](docs/matriks-uji.md)
- Agenda kick-off: [`docs/rapat/2026-10-22-kickoff.md`](docs/rapat/2026-10-22-kickoff.md)
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
