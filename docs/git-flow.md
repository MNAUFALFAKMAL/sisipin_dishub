# Alur Kerja Git â€” 3 Peran

Panduan operasional untuk tim SISIPIN DISHUB. Repositori dimulai dari nol: belum ada kode
aplikasi, sehingga seluruh cabang di bawah ini disiapkan sebagai jalur kerja, bukan sebagai
salinan kode yang sudah ada.

- **Repositori:** `MNAUFALFAKMAL/sisipin_dishub`
- **Cabang utama:** `main` (rilis) dan `develop` (integrasi)
- **Jumlah paket kerja:** 56, tersebar dalam 8 fase dan 5 gerbang (M1â€“M5)
- **Peninjau:** PM & QA untuk semua perubahan

---

## 1. Prinsip yang dipakai

1. **`main` tidak pernah disentuh langsung.** Hanya menerima penggabungan dari cabang rilis
   atau perbaikan darurat, dan setiap penggabungan ke `main` otomatis memicu penerapan ke hosting.
2. **`develop` adalah tempat integrasi.** Semua pekerjaan harian bermuara di sini lewat Pull Request.
3. **Satu paket WBS = satu cabang = satu PR.** Tidak ada cabang "keranjang sampah".
4. **Kecil, sering, cepat ditinjau.** PR ideal di bawah 400 baris perubahan; umur cabang maksimal 3 hari kerja.
5. **Kontrak dulu, tampilan kemudian.** Backend menetapkan nama rute dan nama field sebelum Frontend mulai.
6. **Riwayat bersih.** Penggabungan memakai *squash*, judul commit mengikuti Conventional Commits.

---

## 2. Peta peran

| Peran | Anggota | Hak di GitHub | Wilayah kerja | Cabang yang dibuat |
|---|---|---|---|---|
| Project Manager & QA | Naufal (`@MNAUFALFAKMAL`) | Admin | Dokumen, pengujian, rilis, penerapan | `docs/`, `test/`, `chore/` |
| Backend Developer | Nabila | Write | `app/`, `database/`, `routes/`, `tests/`, `config/` | `feature/`, `fix/` |
| Frontend Developer | Arta | Write | `resources/views/`, `resources/css/`, `resources/js/` | `feature/`, `fix/` |

Hanya PM yang menekan tombol *Merge* pada PR menuju `main`. PR menuju `develop` boleh
digabung oleh peninjau setelah disetujui.

---

## 3. Model cabang

```
main â”€â”€â”€â”€â”€â—â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â—â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â—â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â—â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â–º  rilis (M1..M5)
           \          /            /            /
            \        /            /            /
develop â”€â”€â”€â”€â”€â—â”€â”€â—â”€â”€â—â”€â”€â—â”€â”€â—â”€â”€â—â”€â”€â—â”€â”€â—â”€â”€â—â”€â”€â—â”€â”€â—â”€â”€â—â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â–º  integrasi
              \   /   \   /   \   /
               feature/3.1  feature/3.2  feature/4.1  ...      satu paket WBS
```

| Cabang | Dibuat dari | Digabung ke | Umur | Keterangan |
|---|---|---|---|---|
| `main` | â€“ | â€“ | permanen | Produksi; setiap push memicu deploy |
| `develop` | `main` | â€“ | permanen | Integrasi harian |
| `feature/<kode>-<slug>` | `develop` | `develop` | â‰¤ 3 hari | Fitur baru (Backend/Frontend) |
| `fix/<kode>-<slug>` | `develop` | `develop` | â‰¤ 1 hari | Perbaikan temuan uji |
| `docs/<kode>-<slug>` | `develop` | `develop` | â‰¤ 3 hari | Dokumen, panduan, laporan |
| `test/<kode>-<slug>` | `develop` | `develop` | â‰¤ 2 hari | Matriks uji, skenario UAT |
| `chore/<kode>-<slug>` | `develop` | `develop` | â‰¤ 2 hari | Perkakas, konfigurasi, penerapan |
| `release/M<n>` | `develop` | `main` + `develop` | â‰¤ 2 hari | Persiapan gerbang; hanya perbaikan bug |
| `hotfix/<slug>` | `main` | `main` + `develop` | â‰¤ 1 hari | Darurat setelah rilis |

Aturan tambahan:

- Cabang yang sudah digabung **wajib dihapus** (GitHub melakukannya otomatis bila diaktifkan).
- Satu orang hanya memegang **satu cabang aktif** pada satu waktu.
- Bila pekerjaan belum selesai melewati 3 hari, pecah paketnya atau tutup cabangnya dan mulai ulang dari `develop` terbaru.

---

## 4. Penamaan cabang dan commit

### 4.1 Cabang

Pola: `<tipe>/<kode-wbs>-<ringkasan-3-sampai-6-kata>`

| Tipe | Dipakai untuk | Contoh |
|---|---|---|
| `feature` | fitur baru | `feature/3.1-surat-masuk-catat-ubah` |
| `fix` | perbaikan kesalahan | `fix/7.3-temuan-uji-backend` |
| `docs` | dokumen | `docs/1.3-project-charter` |
| `test` | pengujian | `test/7.1-matriks-uji-r-01-r-20` |
| `chore` | konfigurasi/perkakas | `chore/8.1-publikasi-domain-https` |
| `release` | persiapan gerbang | `release/M3` |
| `hotfix` | darurat | `hotfix/perbaikan-login` |

Larangan: spasi, huruf besar, garis bawah, karakter `#`, `~`, `^`, `:`, `?`, `*`, `[`, `\`.
Gunakan huruf kecil dan tanda hubung.

### 4.2 Commit

```
<tipe>(<kode-wbs>): <ringkasan kalimat perintah, â‰¤ 72 karakter>

<penjelasan bila perlu: apa dan mengapa, bukan bagaimana>
Refs: WBS <kode>, <R-xx/NF-xx>
```

Contoh:

```
feat(3.1): catat surat masuk beserta unggahan lampiran

- nomor agenda unik per tahun
- batas 5 lampiran, 5 MB per berkas
Refs: WBS 3.1, R-04
```

```
fix(4.2): perbaiki status disposisi tidak berubah setelah tindak lanjut
Refs: WBS 4.2, R-04
```

```
docs(1.4): susun SKPL versi pengguna
Refs: WBS 1.4
```

Tipe yang sah: `feat`, `fix`, `docs`, `test`, `chore`, `refactor`, `style`, `perf`.
Commit `wip` boleh pada cabang pribadi, tetapi akan hilang saat *squash merge*.

---

## 5. Alur harian tiga peran

### 5.1 Semua orang, setiap pagi

```bash
git checkout develop
git pull --ff-only
git checkout <cabang-saya>
git merge develop            # selesaikan konflik di lokal, jangan di GitHub
php artisan test             # bila kode sudah ada
```

### 5.2 Backend (Nabila)

1. Ambil satu paket dari kolom *Todo* pada papan Issues, pindahkan ke *In Progress*.
2. Buat cabang: `git checkout -b feature/3.1-surat-masuk-catat-ubah`.
3. Kerjakan berurutan: migrasi â†’ model â†’ *request validation* â†’ controller/service â†’ uji fitur.
4. Tulis nama rute dan nama field pada issue (kontrak untuk Frontend).
5. Jalankan `php artisan test` dan `./vendor/bin/pint --dirty`.
6. Push, buka PR ke `develop`, minta tinjauan PM, tautkan issue (`Closes #12`).
7. Setelah digabung: hapus cabang, ambil paket berikutnya.

### 5.3 Frontend (Arta)

1. Pastikan kontrak rute/field dari Backend sudah ada pada issue. Bila belum, kerjakan
   komponen dengan data contoh dan tulis `TODO(kontrak)` pada PR.
2. Buat cabang: `git checkout -b feature/3.2-tampilan-surat-masuk`.
3. Kerjakan Blade/komponen/Tailwind. **Jangan mengubah `routes/web.php` atau controller.**
4. Periksa tampilan pada 360 px dan 1366 px, empat peramban.
5. `npm run build`, lampirkan tangkapan layar pada PR.
6. Push, buka PR ke `develop`, minta tinjauan PM.
7. Setelah digabung: hapus cabang.

### 5.4 PM & QA (Naufal)

1. Menjaga papan Issues, milestone, dan status paket pada WBS.
2. Meninjau PR maksimal 1 Ã— 24 jam: cek ruang lingkup, keterbacaan, uji, dan tidak ada rahasia.
3. Menggabung PR dengan *squash*, menghapus cabang, memastikan issue tertutup.
4. Menjalankan uji manual R-xx pada tiap akhir fase, mencatat hasil pada matriks uji.
5. Menyiapkan `release/M<n>`, menjalankan daftar periksa gerbang, menggabung ke `main`, memberi tag.
6. Memperbarui dokumen (charter, SKPL, WBS) bila ada perubahan lingkup.

### 5.5 Contoh urutan satu modul (3.1 dan 3.2)

| Langkah | Pelaku | Cabang | Hasil |
|---|---|---|---|
| 1 | Nabila | `feature/3.1-surat-masuk-catat-ubah` | Rute, validasi, tabel, uji fitur |
| 2 | Nabila | â€“ | Menulis kontrak rute/field pada issue #3.1 |
| 3 | Arta | `feature/3.2-tampilan-surat-masuk` | Formulir, daftar, unggah lampiran |
| 4 | Naufal | â€“ | Meninjau kedua PR, menggabung berurutan (3.1 lalu 3.2) |
| 5 | Naufal | `test/3.10-uji-modul-surat-dan-arsip` | Matriks uji terisi |

---

## 6. Kontrak kerja antar peran

| Hal yang disepakati | Ditentukan oleh | Dipakai oleh | Ditulis di |
|---|---|---|---|
| Nama rute (`surat-masuk.index`, dst.) | Backend | Frontend | Issue + `routes/web.php` |
| Nama field formulir dan validasi | Backend | Frontend | Issue + `app/Http/Requests/` |
| Struktur data tabel | Backend | Frontend | Issue + migrasi |
| Nama komponen Blade | Frontend | Frontend | `resources/views/components/` |
| Istilah pada layar (Bahasa Indonesia) | PM | Keduanya | Glosarium |

Bila kontrak berubah setelah Frontend mulai bekerja, Backend **wajib** menulis komentar pada
issue dan menandai `@` pemilik cabang Frontend.

---

## 7. Kepemilikan berkas dan titik rawan konflik

| Berkas/folder | Pemilik | Aturan |
|---|---|---|
| `app/`, `config/`, `database/` | Backend | Hanya Backend yang mengubah |
| `routes/web.php` | Backend | Frontend mengajukan permintaan lewat issue |
| `tests/` | Backend + PM | Backend menulis uji; PM menambah uji penerimaan |
| `resources/views/`, `resources/css/`, `resources/js/` | Frontend | Backend tidak mengubah tanpa kesepakatan |
| `resources/views/components/` | Frontend | Komponen dipakai bersama; perubahan nama harus diumumkan |
| `composer.json`, `composer.lock` | Backend | Wajib persetujuan PM |
| `package.json`, `package-lock.json` | Frontend | Wajib persetujuan PM |
| `docs/` | PM | Boleh dibaca semua orang |
| `.github/`, `.gitignore`, `README.md` | PM | Perubahan lewat PR |
| `.env`, `.env.*` | â€“ | **Tidak pernah di-commit** |

Titik konflik tersering dan cara menghindarinya:

1. **`routes/web.php`** â€” satu pemilik (Backend), Frontend tidak menyentuh.
2. **Menu/sidebar** â€” Frontend mengubah satu berkas layout; tidak ada dua orang mengubahnya bersamaan.
3. **Migrasi** â€” jangan pernah mengubah migrasi yang sudah digabung; buat migrasi baru.
4. **Berkas kunci (`composer.lock`, `package-lock.json`)** â€” perubahan diumumkan di grup sebelum PR.
5. **Terjemahan/istilah** â€” PM menetapkan istilah; Frontend memakai yang sudah ada.

---

## 8. Pull Request dan peninjauan

Ukuran dan waktu:

| Ukuran PR | Tindakan |
|---|---|
| â‰¤ 200 baris | Ideal, tinjau hari itu |
| 200â€“400 baris | Wajar, sebutkan bagian yang perlu perhatian |
| > 400 baris | Pecah menjadi dua PR |

Kewajiban penulis PR:

- Judul mengikuti format commit, misalnya `feat(3.1): catat surat masuk`.
- Mengisi templat PR (ringkasan, cara menguji, daftar periksa).
- Menautkan issue dengan `Closes #<nomor>`.
- Menyertakan tangkapan layar untuk perubahan tampilan (360 px dan 1366 px).

Kewajiban peninjau:

- Memeriksa ruang lingkup: apakah sesuai paket WBS, tidak menyelipkan perubahan lain.
- Memeriksa keamanan: tidak ada rahasia, validasi di server, hak akses.
- Menjalankan `php artisan test` pada salinan lokal bila ragu.
- Memberi keputusan maksimal 1 Ã— 24 jam: *Approve*, *Request changes*, atau komentar.

Cara menggabung:

- **Squash and merge** untuk semua PR fitur (riwayat linear).
- **Rebase and merge** hanya bila commit sudah rapi dan perlu dipertahankan.
- **Merge commit** hanya untuk `release/*` â†’ `main`.
- Hapus cabang setelah digabung.

---

## 9. Gerbang rilis, tag, dan penerapan

Setiap gerbang diakhiri dengan cabang `release/M<n>` dari `develop`. Isi rilis hanya
perbaikan bug dan penyesuaian kecil; tidak ada fitur baru.

| Gerbang | Tanggal | Paket yang harus selesai | Tag | Dampak |
|---|---|---|---|---|
| M1 | 27 Sep 2026 | 1.1 â€“ 1.6 (dokumen, wireframe) | `v0.1.0-M1` | Belum ada aplikasi; hanya dokumen |
| M2 | 11 Okt 2026 | 2.1 â€“ 2.6 (fondasi, akun, tampilan dasar) | `v0.2.0-M2` | Aplikasi dasar dapat dibuka |
| M3 | 25 Okt 2026 | 3.1 â€“ 3.10 (surat, klasifikasi, ekspor) | `v0.3.0-M3` | Modul inti berfungsi |
| M4 | 15 Nov 2026 | 4.1 â€“ 6.5 (disposisi, laporan, keamanan) | `v0.4.0-M4` | Seluruh modul tersedia |
| M5 | 27 Nov 2026 | 7.1 â€“ 8.6 (UAT, penerapan, serah terima) | `v1.0.0-M5` | Siap diserahkan |

Perintah rilis (dijalankan PM):

```bash
git checkout develop
git pull --ff-only
git checkout -b release/M3

# hanya perbaikan bug; perbarui versi bila perlu
git commit -am "chore(rilis): siapkan M3"

git push -u origin release/M3
gh pr create --base main --title "chore(rilis): M3" --body "Gerbang M3 - 25 Oktober 2026"

# setelah PR disetujui dan digabung:
git checkout main
git pull --ff-only
git tag -a v0.3.0-M3 -m "Gerbang M3 - modul inti kearsipan"
git push origin v0.3.0-M3

# kembalikan main ke develop
git checkout develop
git merge --ff-only main
git push
```

Catatan penting:

- `.github/workflows/deploy.yml` menerapkan otomatis setiap push ke `main`. Karena itu
  **jangan menggabung ke `main` sebelum daftar periksa gerbang lulus**.
- Sampai M5, alamat produksi tidak dibagikan kepada mitra; anggap sebagai lingkungan uji.
- Sebelum penggabungan yang memuat migrasi, pastikan pencadangan basis data sudah dibuat.

---

## 10. Peta 56 paket kerja ke cabang

Aturan: satu baris = satu cabang = satu PR. Nama cabang sudah memuat kode WBS sehingga
papan GitHub, PR, dan WBS selalu dapat dicocokkan.

### Fase 1 â€” Perencanaan dan Dokumen

*Minggu 1â€“3 Â· gerbang M1 Â· 27 Sep*

| Kode | Paket kerja | PIC | Jam | Cabang |
|---|---|---|---:|---|
| 1.1 | Rapat kick-off dan konfirmasi lingkup dengan dinas | Naufal | 3 | `docs/1.1-rapat-kick-off-dan-konfirmasi-lingkup` |
| 1.2 | Susun WBS, papan tugas, dan jadwal | Naufal | 5 | `chore/1.2-susun-wbs-papan-tugas-dan-jadwal` |
| 1.3 | Susun Project Charter versi mitra | Naufal | 5 | `docs/1.3-susun-project-charter-versi-mitra` |
| 1.4 | Susun SKPL versi mitra dan lampiran teknis | Naufal | 5 | `docs/1.4-susun-skpl-versi-mitra-dan-lampiran-teknis` |
| 1.5 | Rancangan tampilan (wireframe) dan panduan visual | Arta | 6 | `feature/1.5-rancangan-tampilan-wireframe-dan-panduan` |
| 1.6 | Daftar istilah dinas dan glosarium | Naufal | 2 | `docs/1.6-daftar-istilah-dinas-dan-glosarium` |
| | **Subtotal fase 1** | | **26** | |

### Fase 2 â€” Fondasi Sistem

*Minggu 4â€“5 Â· gerbang M2 Â· 11 Okt*

| Kode | Paket kerja | PIC | Jam | Cabang |
|---|---|---|---:|---|
| 2.1 | Siapkan lingkungan aplikasi, basis data, dan data awal kode klasifikasi | Nabila | 7 | `feature/2.1-siapkan-lingkungan-aplikasi-basis-data` |
| 2.2 | Autentikasi, peran, dan hak akses | Nabila | 8 | `feature/2.2-autentikasi-peran-dan-hak-akses` |
| 2.3 | Tampilan masuk dan menu sesuai peran | Arta | 4 | `feature/2.3-tampilan-masuk-dan-menu-sesuai-peran` |
| 2.4 | Kerangka tampilan: navigasi, tabel, saringan, mode gelap | Arta | 12 | `feature/2.4-kerangka-tampilan-navigasi-tabel-saringan` |
| 2.5 | Riwayat aktivitas dan pengamanan dasar | Nabila | 5 | `feature/2.5-riwayat-aktivitas-dan-pengamanan-dasar` |
| 2.6 | Uji fondasi: masuk, akses, riwayat | Naufal | 3 | `test/2.6-uji-fondasi-masuk-akses-riwayat` |
| | **Subtotal fase 2** | | **39** | |

### Fase 3 â€” Modul Inti Kearsipan

*Minggu 6â€“7 Â· gerbang M3 Â· 25 Okt*

| Kode | Paket kerja | PIC | Jam | Cabang |
|---|---|---|---:|---|
| 3.1 | Surat masuk: catat, ubah, hapus, lampiran | Nabila | 12 | `feature/3.1-surat-masuk-catat-ubah-hapus-lampiran` |
| 3.2 | Tampilan surat masuk dan unggah lampiran | Arta | 8 | `feature/3.2-tampilan-surat-masuk-dan-unggah-lampiran` |
| 3.3 | Surat keluar per jenis: SPT, SPPD, SK, undangan | Nabila | 10 | `feature/3.3-surat-keluar-per-jenis-spt-sppd-sk` |
| 3.4 | Tampilan surat keluar dan pemilih pegawai | Arta | 8 | `feature/3.4-tampilan-surat-keluar-dan-pemilih-pegawai` |
| 3.5 | Penomoran agenda dan tahun arsip | Nabila | 7 | `feature/3.5-penomoran-agenda-dan-tahun-arsip` |
| 3.6 | Klasifikasi arsip dan pencarian kode | Nabila | 7 | `feature/3.6-klasifikasi-arsip-dan-pencarian-kode` |
| 3.7 | Tampilan klasifikasi dan pemilih kode | Arta | 6 | `feature/3.7-tampilan-klasifikasi-dan-pemilih-kode` |
| 3.8 | Impor dan ekspor Excel | Nabila | 8 | `feature/3.8-impor-dan-ekspor-excel` |
| 3.9 | Ekspor rekap PDF | Nabila | 6 | `feature/3.9-ekspor-rekap-pdf` |
| 3.10 | Uji modul surat dan arsip | Naufal | 5 | `test/3.10-uji-modul-surat-dan-arsip` |
| | **Subtotal fase 3** | | **77** | |

### Fase 4 â€” Disposisi dan Tindak Lanjut

*Minggu 8 Â· gerbang M4 Â· 15 Nov*

| Kode | Paket kerja | PIC | Jam | Cabang |
|---|---|---|---:|---|
| 4.1 | Rancangan dan skema disposisi | Nabila | 7 | `feature/4.1-rancangan-dan-skema-disposisi` |
| 4.2 | Alur disposisi dan tindak lanjut | Nabila | 7 | `feature/4.2-alur-disposisi-dan-tindak-lanjut` |
| 4.3 | Tampilan lembar disposisi dan riwayat | Arta | 10 | `feature/4.3-tampilan-lembar-disposisi-dan-riwayat` |
| 4.4 | Pemberitahuan dalam aplikasi | Nabila | 5 | `feature/4.4-pemberitahuan-dalam-aplikasi` |
| 4.5 | Penanda pemberitahuan pada antarmuka | Arta | 4 | `feature/4.5-penanda-pemberitahuan-pada-antarmuka` |
| 4.6 | Uji alur disposisi | Naufal | 4 | `test/4.6-uji-alur-disposisi` |
| | **Subtotal fase 4** | | **37** | |

### Fase 5 â€” Laporan, Naskah, dan Utilitas

*Minggu 9 Â· gerbang M4 Â· 15 Nov*

| Kode | Paket kerja | PIC | Jam | Cabang |
|---|---|---|---:|---|
| 5.1 | Dasbor statistik dan grafik | Nabila | 6 | `feature/5.1-dasbor-statistik-dan-grafik` |
| 5.2 | Tampilan dasbor dan grafik | Arta | 8 | `feature/5.2-tampilan-dasbor-dan-grafik` |
| 5.3 | Tata naskah dan pengelolaan template | Nabila | 8 | `feature/5.3-tata-naskah-dan-pengelolaan-template` |
| 5.4 | Tampilan tata naskah dan template | Arta | 6 | `feature/5.4-tampilan-tata-naskah-dan-template` |
| 5.5 | Pembuatan dokumen KP4 | Nabila | 6 | `feature/5.5-pembuatan-dokumen-kp4` |
| 5.6 | Utilitas dokumen: foto, Word, gabung PDF | Nabila | 8 | `feature/5.6-utilitas-dokumen-foto-word-gabung-pdf` |
| 5.7 | Tampilan utilitas dokumen | Arta | 6 | `feature/5.7-tampilan-utilitas-dokumen` |
| 5.8 | Halaman publik, panduan, dan SEO | Arta | 8 | `feature/5.8-halaman-publik-panduan-dan-seo` |
| 5.9 | Uji laporan dan utilitas | Naufal | 4 | `test/5.9-uji-laporan-dan-utilitas` |
| | **Subtotal fase 5** | | **60** | |

### Fase 6 â€” Non-Fungsional dan Pengamanan

*Minggu 10 Â· gerbang M4 Â· 15 Nov*

| Kode | Paket kerja | PIC | Jam | Cabang |
|---|---|---|---:|---|
| 6.1 | Pencadangan harian dan uji pemulihan | Nabila | 4 | `feature/6.1-pencadangan-harian-dan-uji-pemulihan` |
| 6.2 | Pengamanan: sesi, batas masuk, unggahan | Nabila | 5 | `feature/6.2-pengamanan-sesi-batas-masuk-unggahan` |
| 6.3 | Uji kecepatan dan perbaikan kueri | Nabila | 4 | `feature/6.3-uji-kecepatan-dan-perbaikan-kueri` |
| 6.4 | Uji tampilan ponsel dan lintas peramban | Arta | 6 | `feature/6.4-uji-tampilan-ponsel-dan-lintas-peramban` |
| 6.5 | Peninjauan bahasa dan kemudahan pemakaian | Naufal | 3 | `feature/6.5-peninjauan-bahasa-dan-kemudahan-pemakaian` |
| | **Subtotal fase 6** | | **22** | |

### Fase 7 â€” Pengujian dan Uji Penerimaan

*Minggu 11 Â· gerbang M5 Â· 27 Nov*

| Kode | Paket kerja | PIC | Jam | Cabang |
|---|---|---|---:|---|
| 7.1 | Matriks uji R-01 sampai R-20 | Naufal | 4 | `test/7.1-matriks-uji-r-01-sampai-r-20` |
| 7.2 | Uji otomatis fitur utama | Nabila | 5 | `feature/7.2-uji-otomatis-fitur-utama` |
| 7.3 | Perbaikan temuan pengujian internal (backend) | Nabila | 6 | `fix/7.3-perbaikan-temuan-pengujian-internal` |
| 7.4 | Perbaikan temuan pengujian internal (tampilan) | Arta | 6 | `fix/7.4-perbaikan-temuan-pengujian-internal` |
| 7.5 | Skenario dan formulir uji penerimaan | Naufal | 4 | `test/7.5-skenario-dan-formulir-uji-penerimaan` |
| 7.6 | Sesi uji penerimaan bersama staf dinas | Naufal | 4 | `test/7.6-sesi-uji-penerimaan-bersama-staf-dinas` |
| 7.7 | Perbaikan temuan uji penerimaan (backend) | Nabila | 6 | `fix/7.7-perbaikan-temuan-uji-penerimaan-backend` |
| 7.8 | Perbaikan temuan uji penerimaan (tampilan) | Arta | 4 | `fix/7.8-perbaikan-temuan-uji-penerimaan-tampilan` |
| | **Subtotal fase 7** | | **39** | |

### Fase 8 â€” Penerapan Website dan Serah Terima

*Minggu 12 Â· gerbang M5 Â· 27 Nov*

| Kode | Paket kerja | PIC | Jam | Cabang |
|---|---|---|---:|---|
| 8.1 | Publikasi website, pengaturan domain, dan HTTPS | Nabila | 3 | `chore/8.1-publikasi-website-pengaturan-domain-dan` |
| 8.2 | Pendampingan penerapan dan pemeriksaan akhir | Naufal | 2 | `chore/8.2-pendampingan-penerapan-dan-pemeriksaan` |
| 8.3 | Buku panduan pengguna | Naufal | 4 | `docs/8.3-buku-panduan-pengguna` |
| 8.4 | Tampilan panduan di dalam aplikasi | Arta | 2 | `feature/8.4-tampilan-panduan-di-dalam-aplikasi` |
| 8.5 | Pelatihan dua sesi | Naufal | 4 | `docs/8.5-pelatihan-dua-sesi` |
| 8.6 | Serah terima akun dan dokumen | Naufal | 1 | `chore/8.6-serah-terima-akun-dan-dokumen` |
| | **Subtotal fase 8** | | **16** | |


---

## 11. Rekap beban dan cabang

| Anggota | Peran | Paket | Jam | Cabang yang dibuat |
|---|---|---:|---:|---|
| Naufal | PM & QA | 17 | 62 | 17 cabang (satu per paket) |
| Nabila | Backend | 23 | 150 | 23 cabang (satu per paket) |
| Arta | Frontend | 16 | 104 | 16 cabang (satu per paket) |
| **Total** | | **56** | **316** | |

---

## 12. Issue, label, dan milestone

### 12.1 Judul issue

`[<kode>] <paket kerja>` â€” contoh: `[3.1] Surat masuk: catat, ubah, hapus, lampiran`

### 12.2 Label

| Kelompok | Label | Arti |
|---|---|---|
| Fase | `fase:1` â€¦ `fase:8` | Fase WBS |
| Peran | `peran:pm`, `peran:be`, `peran:fe` | Penanggung jawab |
| Jenis | `jenis:feat`, `jenis:fix`, `jenis:docs`, `jenis:test`, `jenis:chore` | Jenis pekerjaan |
| Prioritas | `prioritas:tinggi`, `prioritas:normal` | Urutan pengerjaan |
| Status | `status:blocked`, `status:review` | Hambatan |

### 12.3 Milestone

| Milestone | Due date | Cakupan |
|---|---|---|
| M1 â€” Inisiasi & analisis | 27 Sep 2026 | Fase 1 |
| M2 â€” Fondasi | 11 Okt 2026 | Fase 2 |
| M3 â€” Modul inti | 25 Okt 2026 | Fase 3 |
| M4 â€” Fitur & pengujian | 15 Nov 2026 | Fase 4â€“6 |
| M5 â€” UAT & serah terima | 27 Nov 2026 | Fase 7â€“8 |

### 12.4 Papan

Kolom papan: **Backlog â†’ Todo â†’ In Progress â†’ Review â†’ Done**.
Satu kartu hanya boleh berada di *In Progress* bila cabangnya sudah ada.

---

## 13. Definisi selesai per jenis paket

| Jenis | Definisi selesai |
|---|---|
| `feature` | Fungsi berjalan, uji fitur lulus, diuji manual pada dua ukuran layar (bila tampilan), PR disetujui |
| `fix` | Kesalahan tidak lagi muncul, uji regresi ditambahkan, PR disetujui |
| `docs` | Dokumen lengkap, istilah konsisten, ditinjau PM |
| `test` | Matriks/skenario terisi, bukti (tangkapan layar/berkas) dilampirkan |
| `chore` | Perubahan tidak mengubah perilaku, dicatat pada catatan rilis |

Definisi selesai pada WBS tetap menjadi acuan utama; tabel di atas hanya pelengkap.

---

## 14. Perbaikan darurat setelah rilis

```bash
git checkout main
git pull --ff-only
git checkout -b hotfix/perbaikan-login

# perbaikan minimal
git commit -am "fix(hotfix): perbaikan login"
git push -u origin hotfix/perbaikan-login

gh pr create --base main --title "fix(hotfix): perbaikan login"
# setelah digabung dan diterapkan:
git tag -a v1.0.1 -m "Perbaikan darurat login"
git push origin v1.0.1

# sinkronkan kembali
git checkout develop
git merge --ff-only main
git push
```

---

## 15. Larangan

1. `git push --force` ke `main` atau `develop`.
2. Menulis langsung ke `main`/`develop` (ditolak oleh aturan cabang).
3. Menggabung PR sendiri tanpa peninjau.
4. Commit `.env`, `.env.backup`, kunci, kata sandi, atau data pribadi.
5. Mengubah migrasi yang sudah pernah digabung.
6. Menyimpan `vendor/`, `node_modules/`, `storage/` pada repositori.
7. Menghapus atau menimpa pekerjaan orang lain tanpa berdiskusi.
8. Menunda penggabungan sampai akhir fase (menyulitkan peninjauan).

---

## 16. Persiapan yang sudah dilakukan pada repositori

| Hal | Status |
|---|---|
| Cabang `main` | Ada, dilindungi |
| Cabang `develop` | Ada, dilindungi |
| Wajib Pull Request + 1 persetujuan | Aktif pada `main` dan `develop` |
| Larangan force push dan hapus cabang | Aktif |
| Riwayat linear (squash) | Aktif |
| Templat PR dan issue | Tersedia di `.github/` |
| Label dan milestone | Dibuat |
| Kolaborator | Diundang setelah nama akun diterima |

Bila aturan cabang perlu diubah, PM melakukannya melalui **Settings â†’ Rules** (atau
`gh api`), bukan dengan mematikan aturan sementara.

---

## 17. Contekan per peran

### Naufal â€” PM & QA

```bash
# meninjau
gh pr list --base develop
gh pr checkout <nomor>
php artisan test

# menggabung (squash) dan membersihkan
gh pr merge <nomor> --squash --delete-branch

# rilis
git checkout develop && git pull --ff-only
git checkout -b release/M3
git push -u origin release/M3
gh pr create --base main --fill
```

### Nabila â€” Backend

```bash
git checkout develop && git pull --ff-only
git checkout -b feature/3.1-surat-masuk-catat-ubah
php artisan make:model SuratMasuk -m
php artisan test
./vendor/bin/pint --dirty
git push -u origin HEAD
gh pr create --base develop --fill
```

### Arta â€” Frontend

```bash
git checkout develop && git pull --ff-only
git checkout -b feature/3.2-tampilan-surat-masuk
npm run dev
npm run build
git push -u origin HEAD
gh pr create --base develop --fill
```

---

## 18. Risiko alur kerja dan penanganannya

| Risiko | Tanda | Penanganan |
|---|---|---|
| Cabang terlalu lama | Umur > 3 hari, konflik berulang | Pecah paket, gabung lebih awal, sinkronkan harian |
| Konflik `routes/web.php` | Dua PR mengubah baris yang sama | Satu pemilik (Backend), Frontend mengajukan lewat issue |
| PR menumpuk | Lebih dari 3 PR terbuka | Tinjauan harian, batasi pekerjaan paralel |
| `main` rusak | Situs produksi error | Tag terakhir dipulihkan, `hotfix` dari `main` |
| Rahasia ter-commit | Peringatan pemindai | Cabut kunci, ganti kredensial, bersihkan riwayat, laporkan |
| Pekerjaan hilang | Cabang terhapus sebelum digabung | Cabang hanya dihapus otomatis setelah PR digabung |

---

## 19. Rujukan

- Rencana kerja (WBS): 56 paket, 316 jam, 8 fase, 5 gerbang
- Spesifikasi kebutuhan R-01â€¦R-20 dan NF-01â€¦NF-11
- Panduan kontribusi ringkas: `CONTRIBUTING.md`
