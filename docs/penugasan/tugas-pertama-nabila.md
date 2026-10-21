# Penugasan Pertama — Nabila (Backend)

**Tugas:** WBS `3.1.3` — Konfigurasi MySQL, migrasi 11 tabel & seeder awal
**Issue:** grup `[3.1] Setup proyek (Laravel 13, MySQL, Git)` · **Cabang:** `feature/3.1.3-konfigurasi-mysql-migrasi-11-tabel-dan-seeder-awal`
**Tenggat:** Kamis, 23 Oktober 2026, pukul 21.00 WIB
**Cara pakai:** salin teks di bawah garis, kirim lewat grup WhatsApp atau chat pribadi.

---

Halo Nabila, ini tugas pertama kamu di proyek **SISIPIN DISHUB** (website arsip Dinas
Perhubungan Provinsi Lampung). Kerangka Laravel sudah disiapkan Arta pada tugas `3.1.1`,
jadi tugas kamu adalah menyiapkan basis data dan struktur tabelnya.

**Baca dulu, sekitar 10 menit:**

1. Rencana kerja: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/docs/rencana-git-flow.md
2. Aturan teknis: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/docs/git-flow.md
3. Nama cabang tiap tugas: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/docs/peta-paket-cabang.md
4. Ringkasan kontribusi: https://github.com/MNAUFALFAKMAL/sisipin_dishub/blob/develop/CONTRIBUTING.md

**Tugas: WBS `3.1.3` — Konfigurasi MySQL, migrasi 11 tabel & seeder awal**

Batas waktu: **Kamis, 23 Oktober 2026, pukul 21.00 WIB.**

Yang dikerjakan:

1. Buat cabang dari `develop`:
   ```
   git checkout develop
   git pull --ff-only
   git checkout -b feature/3.1.3-konfigurasi-mysql-migrasi-11-tabel-dan-seeder-awal
   ```
2. Siapkan `.env.example` (jangan `.env`), koneksi MySQL/MariaDB, dan basis data lokal
   bernama `sisipin`.
3. Buat 11 migrasi sesuai ERD (data store D1–D11): `users`, `peran`, `bidang`, `surat_masuk`,
   `surat_keluar`, `klasifikasi_arsip`, `tata_naskah`, `template_dokumen`, `audit_log`,
   `disposisi`, `pengaturan`. Tambahkan foreign key dan indeks yang wajar.
4. Tulis seeder awal: peran (Super Admin, Tata Usaha, Pegawai), satu user Super Admin,
   seeding 2.985 kode klasifikasi JRA SKKAAD (file CSV/JSON di `database/seeders/data/`).
5. Pastikan `php artisan migrate --seed` jalan tanpa error dan `php artisan test` lulus.
6. Perbarui bagian "Cara menjalankan" di `README.md`: prasyarat, perintah pasang, dan
   cara menjalankan migrasi/seeder di lokal.

Yang tidak boleh:

- commit `.env`, `vendor/`, atau `node_modules/`;
- push langsung ke `main` atau `develop` (keduanya dilindungi);
- mengerjakan tugas lain di cabang ini.

Cara mengirim hasil:

```
git add -p
git commit -m "feat(3.1.3): migrasi 11 tabel dan seeder awal" -m "Refs: WBS 3.1.3, R-02, R-08"
git push -u origin HEAD
```

Lalu buka Pull Request ke `develop` (base: `develop`, compare: cabang kamu), isi templat PR,
dan tulis `Closes #<nomor-issue-grup-3.1>` pada deskripsi.

Lapor ke grup dengan format:

- Status: selesai / terkendala
- Link PR:
- Yang sudah jalan:
- Kendala (kalau ada):

Kalau ada yang tidak jelas, tanya di komentar issue grup `[3.1]`. Jangan menebak.