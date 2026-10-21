# Penugasan Pertama — Nabila (Backend)

**Paket:** WBS 2.1 — Siapkan lingkungan aplikasi dan basis data
**Issue:** #7 · **Cabang:** `feature/2.1-siapkan-lingkungan-aplikasi-basis-data`
**Tenggat:** Kamis, 23 Oktober 2026, pukul 21.00 WIB
**Cara pakai:** salin teks di bawah garis, kirim lewat grup WhatsApp atau chat pribadi.

---

Halo Nabila, ini tugas pertama kamu di proyek **SISIPIN DISHUB** (website arsip Dinas
Perhubungan Provinsi Lampung). Kodenya belum ada sama sekali, jadi tugas pertama adalah
menyiapkan kerangka aplikasinya.

**Baca dulu, sekitar 10 menit:**

1. Rencana kerja: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/docs/rencana-git-flow.md
2. Aturan teknis: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/docs/git-flow.md
3. Nama cabang tiap paket: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/docs/peta-paket-cabang.md
4. Ringkasan kontribusi: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/CONTRIBUTING.md

**Tugas: paket WBS 2.1 — bagian kerangka dulu** (issue #7)

Batas waktu: **Kamis, 23 Oktober 2026, pukul 21.00 WIB.**

Yang dikerjakan:

1. Buat cabang dari `develop`:
   ```
   git checkout develop
   git pull --ff-only
   git checkout -b feature/2.1-siapkan-lingkungan-aplikasi-basis-data
   ```
2. Pasang Laravel 13 (PHP 8.4 atau lebih baru) dengan Vite dan Tailwind CSS.
3. Siapkan `.env.example` (jangan `.env`), koneksi MySQL/MariaDB, dan basis data lokal
   bernama `sisipin`.
4. Buat halaman sementara di rute `/` yang menampilkan "SISIPIN DISHUB — dalam
   pengembangan", memakai layout Tailwind sederhana dan responsif.
5. Tambahkan migrasi awal:
   - tabel `users` (bawaan Laravel, boleh ditambah kolom `peran`),
   - tabel `klasifikasi_arsip` (`kode`, `uraian`, `tahun_berlaku`).
   Kalau struktur perlu diubah, tulis alasannya di PR.
6. Pastikan `php artisan test` lulus dan `npm run build` berhasil.
7. Perbarui bagian "Cara menjalankan" di `README.md`: prasyarat, perintah pasang, dan
   cara menjalankan di lokal.

Yang tidak boleh:

- commit `.env`, `vendor/`, atau `node_modules/`;
- push langsung ke `main` atau `develop` (keduanya dilindungi);
- mengerjakan paket 2.2 dan seterusnya di cabang ini.

Cara mengirim hasil:

```
git add .
git commit -m "feat(2.1): siapkan kerangka Laravel, Tailwind, dan basis data"
git push -u origin HEAD
```

Lalu buka Pull Request ke `develop` (base: `develop`, compare: cabang kamu), isi templat PR,
dan tulis `Closes #7` pada deskripsi.

Lapor ke grup dengan format:

- Status: selesai / terkendala
- Link PR:
- Yang sudah jalan:
- Kendala (kalau ada):

Kalau ada yang tidak jelas, tanya di komentar issue #7. Jangan menebak.
