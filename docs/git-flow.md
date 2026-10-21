# Rujukan Teknis Alur Kerja Git

> **Status: rujukan.** Rencana yang dibahas dan disepakati tim ada di
> [rencana-git-flow.md](rencana-git-flow.md). Dokumen ini memuat rincian aturan dan
> baru mengikat setelah rencana berstatus **Berlaku**.

Repositori dimulai dari nol: belum ada kode aplikasi, sehingga seluruh cabang di bawah ini
adalah jalur kerja, bukan salinan kode yang sudah ada.

- **Repositori:** `MNAUFALFAKMAL/sisipin_dishub`
- **Cabang tetap:** `main` (rilis) dan `develop` (integrasi)
- **Paket kerja:** 56 paket, 8 fase, 5 gerbang (M1-M5), 316 jam
- **Peta paket ke cabang:** [peta-paket-cabang.md](peta-paket-cabang.md)
- **Peninjau:** PM & QA untuk semua perubahan

---

## 1. Prinsip

1. `main` hanya menerima rilis; setiap push ke `main` memicu penerapan otomatis.
2. `develop` adalah tempat integrasi; menerima perubahan hanya lewat Pull Request.
3. Satu paket WBS = satu cabang = satu PR.
4. PR kecil dan cepat ditinjau; umur cabang maksimal 3 hari kerja.
5. Kontrak rute dan nama field ditetapkan Backend sebelum Frontend mulai.
6. Riwayat bersih: penggabungan memakai *squash*, judul mengikuti Conventional Commits.

---

## 2. Model cabang

```
main        rilis M1..M5 + tag
 |              ^        ^        ^
 |         release/M1  M2       M5
 |              ^
develop  ---+---+---+---+---+---+---  integrasi harian
             \     /   \     /
           feature  feature  feature   satu cabang per paket WBS
```

| Cabang | Dibuat dari | Digabung ke | Umur | Keterangan |
|---|---|---|---|---|
| `main` | - | - | permanen | Produksi, dilindungi |
| `develop` | `main` | - | permanen | Integrasi, dilindungi |
| `feature/<kode>-<slug>` | `develop` | `develop` | <= 3 hari | Fitur baru |
| `fix/<kode>-<slug>` | `develop` | `develop` | <= 1 hari | Perbaikan temuan uji |
| `docs/<kode>-<slug>` | `develop` | `develop` | <= 3 hari | Dokumen |
| `test/<kode>-<slug>` | `develop` | `develop` | <= 2 hari | Matriks dan skenario uji |
| `chore/<kode>-<slug>` | `develop` | `develop` | <= 2 hari | Perkakas, konfigurasi, penerapan |
| `release/M<n>` | `develop` | `main` + `develop` | <= 2 hari | Persiapan gerbang, hanya perbaikan bug |
| `hotfix/<slug>` | `main` | `main` + `develop` | <= 1 hari | Darurat setelah rilis |

Aturan tambahan:

- Cabang yang sudah digabung dihapus (otomatis bila pengaturan repositori aktif).
- Satu orang memegang satu cabang aktif pada satu waktu.
- Bila pekerjaan melewati 3 hari, pecah paketnya atau mulai ulang dari `develop` terbaru.

---

## 3. Penamaan cabang

Pola: `<tipe>/<kode-wbs>-<ringkasan-3-sampai-6-kata>`

| Tipe | Contoh |
|---|---|
| `feature` | `feature/3.1-surat-masuk-catat-ubah-hapus-lampiran` |
| `fix` | `fix/7.3-temuan-uji-backend` |
| `docs` | `docs/1.3-susun-project-charter-versi-mitra` |
| `test` | `test/7.1-matriks-uji-r-01-r-20` |
| `chore` | `chore/8.1-publikasi-website-domain-dan-https` |
| `release` | `release/M3` |
| `hotfix` | `hotfix/perbaikan-login` |

Gunakan huruf kecil dan tanda hubung. Jangan memakai spasi, huruf besar, garis bawah, atau
karakter `#`, `~`, `^`, `:`, `?`, `*`, `[`, `\`.

Daftar lengkap 56 nama cabang ada di [peta-paket-cabang.md](peta-paket-cabang.md).

---

## 4. Format commit

```
<tipe>(<kode-wbs>): <ringkasan kalimat perintah, maksimal 72 karakter>

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
Commit `wip` boleh pada cabang pribadi dan akan hilang saat *squash merge*.

---

## 5. Alur harian

### Semua orang, setiap pagi

```bash
git checkout develop
git pull --ff-only
git checkout <cabang-saya>
git merge develop            # selesaikan konflik di lokal
php artisan test             # bila kode sudah ada
```

### Backend (Nabila)

1. Ambil satu paket dari kolom *Todo* pada papan Issues, pindahkan ke *In Progress*.
2. `git checkout -b feature/3.1-surat-masuk-catat-ubah-hapus-lampiran`
3. Kerjakan berurutan: migrasi, model, validasi, controller/service, uji fitur.
4. Tulis nama rute dan nama field pada issue (kontrak untuk Frontend).
5. `php artisan test` dan `./vendor/bin/pint --dirty`.
6. Push, buka PR ke `develop`, tautkan issue (`Closes #12`).
7. Setelah digabung: hapus cabang, ambil paket berikutnya.

### Frontend (Arta)

1. Pastikan kontrak rute dan nama field dari Backend sudah ada pada issue.
2. `git checkout -b feature/3.2-tampilan-surat-masuk-dan-unggah-lampiran`
3. Kerjakan Blade, komponen, dan Tailwind. Jangan mengubah `routes/web.php` atau controller.
4. Periksa tampilan pada 360 px dan 1366 px, empat peramban.
5. `npm run build`, lampirkan tangkapan layar pada PR.
6. Push, buka PR ke `develop`, minta tinjauan PM.

### PM & QA (Naufal)

1. Menjaga papan Issues, milestone, dan status paket pada WBS.
2. Meninjau PR maksimal 1 x 24 jam: lingkup, keterbacaan, uji, tidak ada rahasia.
3. Menggabung PR dengan *squash*, menghapus cabang, memastikan issue tertutup.
4. Menjalankan uji manual R-xx pada akhir fase, mencatat hasil pada matriks uji.
5. Menyiapkan `release/M<n>`, menjalankan daftar periksa gerbang, memberi tag.
6. Memperbarui dokumen bila ada perubahan lingkup.

---

## 6. Kontrak kerja antar peran

| Hal | Ditentukan oleh | Dipakai oleh | Ditulis di |
|---|---|---|---|
| Nama rute (`surat-masuk.index`, dst.) | Backend | Frontend | Issue + `routes/web.php` |
| Nama field dan validasi | Backend | Frontend | Issue + `app/Http/Requests/` |
| Struktur data tabel | Backend | Frontend | Issue + migrasi |
| Nama komponen Blade | Frontend | Frontend | `resources/views/components/` |
| Istilah pada layar (Bahasa Indonesia) | PM | Keduanya | Glosarium |

Bila kontrak berubah setelah Frontend mulai bekerja, Backend wajib menulis komentar pada
issue dan menandai pemilik cabang Frontend.

---

## 7. Kepemilikan berkas

| Berkas/folder | Pemilik | Aturan |
|---|---|---|
| `app/`, `config/`, `database/` | Backend | Hanya Backend yang mengubah |
| `routes/web.php` | Backend | Frontend mengajukan permintaan lewat issue |
| `tests/` | Backend + PM | Backend menulis uji; PM menambah uji penerimaan |
| `resources/views/`, `resources/css/`, `resources/js/` | Frontend | Backend tidak mengubah tanpa kesepakatan |
| `composer.json`, `composer.lock` | Backend | Wajib persetujuan PM |
| `package.json`, `package-lock.json` | Frontend | Wajib persetujuan PM |
| `docs/` | PM | Boleh dibaca semua orang |
| `.github/`, `.gitignore`, `README.md` | PM | Perubahan lewat PR |
| `.env`, `.env.*` | - | Tidak pernah di-commit |

Titik rawan konflik:

1. `routes/web.php` - satu pemilik (Backend).
2. Menu atau sidebar - Frontend mengubah satu berkas layout, tidak bersamaan.
3. Migrasi - jangan mengubah migrasi yang sudah digabung; buat migrasi baru.
4. Berkas kunci (`composer.lock`, `package-lock.json`) - umumkan sebelum PR.
5. Istilah dan terjemahan - PM menetapkan, Frontend memakai yang sudah ada.

---

## 8. Pull Request

| Ukuran PR | Tindakan |
|---|---|
| <= 200 baris | Ideal, tinjau hari itu |
| 200-400 baris | Wajar, sebutkan bagian yang perlu perhatian |
| > 400 baris | Pecah menjadi dua PR |

Kewajiban penulis:

- Judul mengikuti format commit, misalnya `feat(3.1): catat surat masuk`.
- Mengisi templat PR: ringkasan, cara menguji, daftar periksa.
- Menautkan issue dengan `Closes #<nomor>`.
- Menyertakan tangkapan layar untuk perubahan tampilan (360 px dan 1366 px).

Kewajiban peninjau:

- Memeriksa lingkup: sesuai paket WBS, tidak menyelipkan perubahan lain.
- Memeriksa keamanan: tidak ada rahasia, validasi di server, hak akses.
- Menjalankan `php artisan test` pada salinan lokal bila ragu.
- Memberi keputusan maksimal 1 x 24 jam: *Approve*, *Request changes*, atau komentar.

Cara menggabung:

- **Squash and merge** untuk semua PR fitur.
- **Rebase and merge** hanya bila commit sudah rapi dan perlu dipertahankan.
- **Merge commit** hanya untuk `release/*` ke `main`.
- Hapus cabang setelah digabung.

---

## 9. Gerbang rilis dan tag

| Gerbang | Tanggal | Paket yang harus selesai | Tag |
|---|---|---|---|
| M1 | 27 Sep 2026 | 1.1 - 1.6 | `v0.1.0-M1` |
| M2 | 11 Okt 2026 | 2.1 - 2.6 | `v0.2.0-M2` |
| M3 | 25 Okt 2026 | 3.1 - 3.10 | `v0.3.0-M3` |
| M4 | 15 Nov 2026 | 4.1 - 6.5 | `v0.4.0-M4` |
| M5 | 27 Nov 2026 | 7.1 - 8.6 | `v1.0.0-M5` |

Perintah rilis (dijalankan PM):

```bash
git checkout develop
git pull --ff-only
git checkout -b release/M3

# hanya perbaikan bug
git commit -am "chore(rilis): siapkan M3"

git push -u origin release/M3
gh pr create --base main --title "chore(rilis): M3" --body "Gerbang M3 - 25 Oktober 2026"

# setelah PR disetujui dan digabung
git checkout main
git pull --ff-only
git tag -a v0.3.0-M3 -m "Gerbang M3 - modul inti kearsipan"
git push origin v0.3.0-M3

git checkout develop
git merge --ff-only main
git push
```

Catatan:

- `.github/workflows/deploy.yml` menerapkan otomatis setiap push ke `main`.
- Jangan menggabung ke `main` sebelum daftar periksa gerbang lulus.
- Sebelum penggabungan yang memuat migrasi, pastikan pencadangan basis data sudah dibuat.

---

## 10. Issue, label, dan milestone

Judul issue: `[<kode>] <paket kerja>`, contoh `[3.1] Surat masuk: catat, ubah, hapus, lampiran`.

| Kelompok | Label |
|---|---|
| Fase | `fase:1` sampai `fase:8` |
| Peran | `peran:pm`, `peran:be`, `peran:fe` |
| Jenis | `jenis:feat`, `jenis:fix`, `jenis:docs`, `jenis:test`, `jenis:chore` |
| Prioritas | `prioritas:tinggi`, `prioritas:normal` |
| Status | `status:blocked`, `status:review` |

Milestone M1-M5 mengikuti tanggal gerbang. Kolom papan: Backlog, Todo, In Progress, Review,
Done. Satu kartu hanya berada di *In Progress* bila cabangnya sudah ada.

---

## 11. Definisi selesai per jenis

| Jenis | Definisi selesai |
|---|---|
| `feature` | Fungsi berjalan, uji fitur lulus, diuji manual pada dua ukuran layar (bila tampilan), PR disetujui |
| `fix` | Kesalahan tidak lagi muncul, uji regresi ditambahkan, PR disetujui |
| `docs` | Dokumen lengkap, istilah konsisten, ditinjau PM |
| `test` | Matriks atau skenario terisi, bukti dilampirkan |
| `chore` | Perubahan tidak mengubah perilaku, dicatat pada catatan rilis |

Definisi selesai pada WBS tetap menjadi acuan utama.

---

## 12. Perbaikan darurat setelah rilis

```bash
git checkout main
git pull --ff-only
git checkout -b hotfix/perbaikan-login

git commit -am "fix(hotfix): perbaikan login"
git push -u origin hotfix/perbaikan-login

gh pr create --base main --title "fix(hotfix): perbaikan login"
# setelah digabung dan diterapkan
git tag -a v1.0.1 -m "Perbaikan darurat login"
git push origin v1.0.1

git checkout develop
git merge --ff-only main
git push
```

---

## 13. Larangan

1. `git push --force` ke `main` atau `develop`.
2. Menulis langsung ke `main` atau `develop`.
3. Menggabung PR sendiri tanpa peninjau.
4. Commit `.env`, kunci, kata sandi, atau data pribadi.
5. Mengubah migrasi yang sudah pernah digabung.
6. Menyimpan `vendor/`, `node_modules/`, atau `storage/` pada repositori.
7. Menghapus atau menimpa pekerjaan orang lain tanpa berdiskusi.
8. Menunda penggabungan sampai akhir fase.

---

## 14. Pengaturan repositori

| Hal | Status |
|---|---|
| Cabang `main` dan `develop` | Ada |
| Wajib Pull Request + 1 persetujuan | Aktif pada keduanya |
| Larangan force push dan hapus cabang | Aktif |
| Squash merge dan hapus cabang otomatis | Aktif |
| Rebase merge | Dimatikan |
| Templat PR dan issue | Tersedia di `.github/` |
| Label dan milestone | Tersedia |
| Kolaborator | Nabila (`@nblable`) menunggu menerima undangan; Arta menyusul |

Bila aturan cabang perlu diubah, PM melakukannya melalui **Settings -> Rules** atau `gh api`,
bukan dengan mematikan aturan sementara.

---

## 15. Contekan

### Naufal - PM & QA

```bash
gh pr list --base develop
gh pr checkout <nomor>
php artisan test
gh pr merge <nomor> --squash --delete-branch

git checkout develop && git pull --ff-only
git checkout -b release/M3
git push -u origin release/M3
gh pr create --base main --fill
```

### Nabila - Backend

```bash
git checkout develop && git pull --ff-only
git checkout -b feature/3.1-surat-masuk-catat-ubah-hapus-lampiran
php artisan make:model SuratMasuk -m
php artisan test
./vendor/bin/pint --dirty
git push -u origin HEAD
gh pr create --base develop --fill
```

### Arta - Frontend

```bash
git checkout develop && git pull --ff-only
git checkout -b feature/3.2-tampilan-surat-masuk-dan-unggah-lampiran
npm run dev
npm run build
git push -u origin HEAD
gh pr create --base develop --fill
```

---

## 16. Risiko dan penanganan

| Risiko | Tanda | Penanganan |
|---|---|---|
| Cabang terlalu lama | Umur > 3 hari, konflik berulang | Pecah paket, gabung lebih awal, sinkron harian |
| Konflik `routes/web.php` | Dua PR mengubah baris yang sama | Satu pemilik, Frontend lewat issue |
| PR menumpuk | Lebih dari 3 PR terbuka | Tinjauan harian, batasi pekerjaan paralel |
| `main` rusak | Situs produksi error | Pulihkan tag terakhir, `hotfix` dari `main` |
| Rahasia ter-commit | Peringatan pemindai | Cabut kunci, ganti kredensial, bersihkan riwayat |
| Pekerjaan hilang | Cabang terhapus sebelum digabung | Cabang hanya dihapus setelah PR digabung |

---

## 17. Rujukan

- Rencana dan keputusan tim: [rencana-git-flow.md](rencana-git-flow.md)
- Peta 56 paket ke cabang: [peta-paket-cabang.md](peta-paket-cabang.md)
- Ringkasan kontribusi: [../CONTRIBUTING.md](../CONTRIBUTING.md)
