# Penugasan Pertama — Arta (Frontend)

**Paket:** WBS 1.5 — Rancangan tampilan (wireframe) dan panduan visual
**Issue:** #5 · **Cabang:** `feature/1.5-rancangan-tampilan-wireframe-dan-panduan`
**Tenggat:** Kamis, 23 Oktober 2026, pukul 21.00 WIB
**Cara pakai:** salin teks di bawah garis, kirim lewat grup WhatsApp atau chat pribadi.

---

Halo Arta, ini tugas pertama kamu di proyek **SISIPIN DISHUB** (website arsip Dinas
Perhubungan Provinsi Lampung). Kodenya belum ada, jadi kita mulai dari rancangan tampilan
agar Nabila bisa menyiapkan kerangka aplikasi dengan arah yang jelas.

**Baca dulu, sekitar 10 menit:**

1. Rencana kerja: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/docs/rencana-git-flow.md
2. Aturan teknis: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/docs/git-flow.md
3. Istilah yang wajib dipakai: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/docs/glosarium.md
4. Ringkasan kontribusi: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/CONTRIBUTING.md

**Tugas: paket WBS 1.5 — rancangan tampilan dan panduan visual** (issue #5)

Batas waktu: **Kamis, 23 Oktober 2026, pukul 21.00 WIB.** Belum menulis kode aplikasi,
karena kerangka Laravel sedang disiapkan Nabila.

Yang dikerjakan:

1. Buat cabang:
   ```
   git checkout develop
   git pull --ff-only
   git checkout -b feature/1.5-rancangan-tampilan-wireframe-dan-panduan
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
git commit -m "docs(1.5): rancangan tampilan dan panduan visual" -m "Refs: WBS 1.5"
git push -u origin HEAD
```

Lalu buka Pull Request ke `develop`, isi templat PR, dan tulis `Closes #5` pada deskripsi.

Lapor ke grup dengan format:

- Status: selesai / terkendala
- Link PR:
- Layar yang sudah dirancang:
- Kendala (kalau ada):

Kalau ada yang tidak jelas, tanya di komentar issue #5. Jangan menebak.
