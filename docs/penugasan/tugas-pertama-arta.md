# Penugasan Pertama — Arta (Frontend)

**Tugas:** WBS `2.3.2` — Wireframe halaman publik & autentikasi (serta `2.3.3`, `2.3.4`)
**Issue:** grup `[2.3] Perancangan Antarmuka (UI/UX)` · **Cabang:** `docs/2.3.2-wireframe-halaman-publik-dan-autentikasi`
**Tenggat:** Kamis, 23 Oktober 2026, pukul 21.00 WIB
**Cara pakai:** salin teks di bawah garis, kirim lewat grup WhatsApp atau chat pribadi.

---

Halo Arta, ini tugas pertama kamu di proyek **SISIPIN DISHUB** (website arsip Dinas
Perhubungan Provinsi Lampung). Kita mulai dari rancangan tampilan (wireframe) agar Nabila
bisa menyiapkan kerangka aplikasi dengan arah yang jelas.

**Baca dulu, sekitar 10 menit:**

1. Rencana kerja: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/docs/rencana-git-flow.md
2. Aturan teknis: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/docs/git-flow.md
3. Istilah yang wajib dipakai: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/docs/glosarium.md
4. Ringkasan kontribusi: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/CONTRIBUTING.md

**Tugas: WBS `2.3.2`–`2.3.4` — Wireframe halaman publik, autentikasi, dashboard, surat, klasifikasi, tata naskah, KP4, manajemen pengguna**

Batas waktu: **Kamis, 23 Oktober 2026, pukul 21.00 WIB.** Belum menulis kode aplikasi,
karena kerangka Laravel sedang disiapkan Nabila (tugas `3.1.1` dan `3.1.3`).

Yang dikerjakan:

1. Buat cabang:
   ```
   git checkout develop
   git pull --ff-only
   git checkout -b docs/2.3.2-wireframe-halaman-publik-dan-autentikasi
   ```
2. Buat folder `docs/wireframe/` berisi `README.md` dan gambar rancangan. Boleh memakai
   Excalidraw, draw.io, atau Figma (tautan harus bisa dibuka publik tanpa akun berbayar).
3. Rancang minimal sepuluh layar:
   - masuk,
   - dasbor (angka dan grafik),
   - daftar surat masuk,
   - formulir surat masuk (termasuk unggah lampiran),
   - detail surat dan disposisi,
   - daftar surat keluar per jenis (SPT, SPPD, SK, undangan),
   - daftar kode klasifikasi,
   - laporan dan pratinjau cetak PDF,
   - profil dan kata sandi,
   - halaman publik (klasifikasi dan tata naskah tanpa masuk).
4. Untuk setiap layar, tuliskan: tujuan layar, elemen utama, keadaan kosong, keadaan
   galat, dan catatan untuk lebar 360 px serta 1366 px.
5. Susun panduan visual: palet warna (kode hex), tipografi (jenis huruf dan ukuran),
   jarak, tombol, formulir, tabel, dan notifikasi.
6. Gunakan istilah dari glosarium. Jangan memakai istilah Inggris pada teks layar.
7. Sertakan catatan aksesibilitas: kontras teks, ukuran klik minimal, label formulir, dan
   navigasi dengan papan ketik.

Yang tidak boleh:

- mengubah `README.md`, `CONTRIBUTING.md`, atau berkas di luar `docs/wireframe/`;
- push langsung ke `main` atau `develop`;
- memasang paket atau lisensi berbayar.

Cara mengirim hasil:

```
git add -p
git commit -m "docs(2.3.2): rancangan tampilan dan panduan visual" -m "Refs: WBS 2.3.2, 2.3.3, 2.3.4"
git push -u origin HEAD
```

Lalu buka Pull Request ke `develop`, isi templat PR, dan tulis `Closes #<nomor-issue-grup-2.3>` pada deskripsi.

Lapor ke grup dengan format:

- Status: selesai / terkendala
- Link PR:
- Layar yang sudah dirancang:
- Kendala (kalau ada):

Kalau ada yang tidak jelas, tanya di komentar issue grup `[2.3]`. Jangan menebak.

---

## Tugas Kedua — Arta (Frontend)

**Tugas:** WBS `3.1.1` — Inisialisasi Laravel 13, Tailwind CSS v3, Alpine.js & layout Blade dasar
**Issue:** grup `[3.1] Setup proyek (Laravel 13, MySQL, Git)` · **Cabang:** `feature/3.1.1-inisialisasi-laravel-13-tailwind-css-v3-alpine-js-dan`
**Tenggat:** Selasa, 27 Oktober 2026, pukul 21.00 WIB

Setelah wireframe selesai dan disetujui, tugas selanjutnya adalah menyiapkan kerangka
aplikasi Laravel.

Yang dikerjakan:

1. Buat cabang baru dari `develop` terbaru:
   ```
   git checkout develop
   git pull --ff-only
   git checkout -b feature/3.1.1-inisialisasi-laravel-13-tailwind-css-v3-alpine-js-dan
   ```
2. Pasang Laravel 13 (PHP 8.4+), Vite, Tailwind CSS v3, Alpine.js.
3. Siapkan layout Blade dasar: `layouts/app.blade.php` (header, sidebar, footer),
   `layouts/auth.blade.php` untuk halaman login.
4. Buat halaman sementara di rute `/` menampilkan "SISIPIN DISHUB — dalam pengembangan"
   memakai layout Tailwind sederhana dan responsif.
5. Pastikan `npm run build` berhasil dan `php artisan test` lulus.
6. Perbarui `README.md`: prasyarat, perintah pasang, cara menjalankan di lokal.

Cara mengirim hasil:

```
git add -p
git commit -m "feat(3.1.1): inisialisasi Laravel, Tailwind, Alpine, layout Blade" -m "Refs: WBS 3.1.1"
git push -u origin HEAD
```

Lalu buka Pull Request ke `develop`, isi templat PR, dan tulis `Closes #<nomor-issue-grup-3.1>`.