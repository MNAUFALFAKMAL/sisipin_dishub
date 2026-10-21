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

## 4. Commit yang baik

Commit adalah satuan pekerjaan terkecil yang bisa dijelaskan, diuji, dan dibatalkan sendiri.
Satu commit memuat **satu alasan perubahan**. Commit yang rapi membuat tinjauan cepat,
riwayat mudah dibaca, dan kesalahan mudah dicari.

### Aturan

1. **Atomik** — satu commit untuk satu perubahan logis. Jangan mencampur fitur, perbaikan
   tampilan, dan penyesuaian konfigurasi dalam satu commit.
2. **Kalimat perintah** — tulis seolah melanjutkan kalimat "Bila diterapkan, commit ini akan
   …". Gunakan `tambah`, `perbaiki`, `ubah`, `hapus`, bukan `menambahkan` atau `sudah ditambah`.
3. **Ringkasan maksimal 72 karakter**, tanpa titik di akhir.
4. **Badan pesan** dipakai bila perubahan perlu penjelasan: tulis **apa** dan **mengapa**,
   bukan **bagaimana** (caranya sudah terlihat pada diff). Bungkus pada 72 karakter.
5. **Tautkan pekerjaan** — sertakan `Refs: WBS <kode>, <R-xx>` pada baris terakhir.
6. **Jangan commit setengah jadi** ke cabang bersama. `wip` hanya boleh di cabang sendiri dan
   akan hilang saat *squash merge*.
7. **Satu commit harus lolos uji** sebelum di-push. Bila uji belum ada, pastikan aplikasi
   setidaknya dapat dijalankan.

### Format baku

```
<tipe>(<kode-wbs>): <ringkasan kalimat perintah, maksimal 72 karakter>

<penjelasan bila perlu: apa dan mengapa>
Refs: WBS <kode>, <R-xx/NF-xx>
```

Contoh lengkap:

```
feat(3.1): catat surat masuk beserta unggahan lampiran

- nomor agenda unik per tahun
- batas 5 lampiran, 5 MB per berkas
- tolak berkas selain PDF, JPG, PNG, DOC, DOCX
Refs: WBS 3.1, R-04
```

```
fix(4.2): perbaiki status disposisi tidak berubah setelah tindak lanjut

Status hanya diperbarui bila kolom tindak_lanjut_oleh terisi.
Refs: WBS 4.2, R-04
```

```
docs(1.4): susun SKPL versi pengguna
Refs: WBS 1.4
```

### Tipe commit

| Tipe | Dipakai untuk | Contoh |
|---|---|---|
| `feat` | Kemampuan baru yang terlihat pengguna | `feat(3.1): catat surat masuk` |
| `fix` | Perbaikan kesalahan | `fix(4.2): perbaiki status disposisi` |
| `docs` | Dokumen, panduan, komentar penting | `docs(8.3): tambah panduan disposisi` |
| `test` | Uji otomatis atau matriks uji | `test(7.1): tambah uji surat masuk` |
| `refactor` | Rombak kode tanpa mengubah perilaku | `refactor(3.4): pisahkan service pencarian` |
| `style` | Format, spasi, urutan impor | `style(3.2): rapikan indentasi Blade` |
| `perf` | Peningkatan kecepatan | `perf(6.2): tambah indeks kolom tanggal` |
| `chore` | Konfigurasi, dependensi, perkakas | `chore(2.1): pasang Laravel Pint` |
| `revert` | Membatalkan commit sebelumnya | `revert: feat(3.1): catat surat masuk` |

Kode WBS wajib ada pada `feat`, `fix`, `docs`, `test`, dan `chore` yang menyentuh paket.
Untuk perubahan di luar paket (misalnya memperbaiki alur kerja), kode boleh diganti nama
singkat, contoh `chore(workflow): tambah templat PR`.

### Contoh benar dan salah

| Salah | Mengapa salah | Perbaikan |
|---|---|---|
| `update` | Tidak menjelaskan apa pun | `fix(3.1): perbaiki validasi tanggal surat` |
| `fix bug` | Tidak ada kode WBS dan terlalu umum | `fix(3.1): tolak tanggal terima sebelum tanggal surat` |
| `feat: tambah surat masuk, surat keluar, laporan` | Tiga pekerjaan dalam satu commit | Pecah menjadi tiga commit |
| `wip` di `develop` | Riwayat bersama jadi kotor | Simpan di cabang sendiri, rapikan sebelum PR |
| `feat(3.1): Menambahkan surat masuk.` | Bukan kalimat perintah, ada titik | `feat(3.1): tambah surat masuk` |

### Sebelum commit

```bash
git status                     # berkas apa yang berubah
git diff                       # isi perubahan yang belum di-stage
git add -p                     # pilih potongan perubahan satu per satu
git diff --staged              # periksa yang akan masuk commit
```

Bila uji sudah ada:

```bash
php artisan test
./vendor/bin/pint --dirty
npm run build
```

### Yang tidak boleh masuk commit

- `.env`, kunci aplikasi, kata sandi, token, atau berkas kredensial apa pun.
- `vendor/`, `node_modules/`, `storage/logs/`, `*.sql` hasil ekspor, dan berkas > 5 MB.
- Berkas hasil *build* yang seharusnya dihasilkan ulang.
- Kode orang lain yang belum ditinjau (jangan gabungkan pekerjaan orang ke cabang sendiri).

### Kapan memecah commit

- Perubahan menyentuh lebih dari satu paket WBS.
- Ada perbaikan format bercampur perubahan perilaku.
- Satu commit memuat lebih dari ±200 baris yang tidak saling terkait.
- Commit perlu dua penjelasan berbeda pada badan pesan.

---

## 5. Push dan sinkronisasi

Push adalah menerbitkan commit ke repositori agar terlihat tim dan masuk tinjauan. Push
**bukan** cara menggabungkan pekerjaan — penggabungan hanya lewat Pull Request.

### Sekali saja di awal

```bash
git config --global user.name "Nama Lengkap"
git config --global user.email "surel@contoh.id"
gh auth login                       # pilih GitHub.com, HTTPS
gh auth setup-git                   # pemasangan kredensial untuk repo ini
```

Periksa sekali:

```bash
git config --get user.name
git config --get user.email
gh auth status
```

Gunakan surel yang sama dengan akun GitHub. Bila berbeda, commit tidak terhubung ke akun dan
tidak terhitung pada kontribusi.

### Push cabang baru (pertama kali)

```bash
git push -u origin HEAD
```

`-u` menghubungkan cabang lokal dengan cabang remote (`origin/<nama-cabang>`), sehingga push
dan pull berikutnya cukup `git push` / `git pull`.

Setelah push pertama, langsung buka PR:

```bash
gh pr create --base develop --fill
```

### Push berikutnya

```bash
git add <berkas>
git commit -m "fix(3.1): perbaiki validasi tanggal surat" -m "Refs: WBS 3.1, R-04"
git push
```

Push setiap kali satu commit logis selesai, bukan menumpuk seminggu. Cabang yang ter-push
adalah cadangan pekerjaan sekaligus bahan tinjauan.

### Sinkron dengan `develop` terbaru

Lakukan setiap pagi dan sebelum membuka PR:

```bash
git fetch origin
git rebase origin/develop
```

Bila ada konflik:

```bash
git status                     # lihat berkas yang bentrok
# sunting berkas, buang penanda <<<<<<< ======= >>>>>>>
git add <berkas>
git rebase --continue
```

Bila kacau dan ingin mengulang:

```bash
git rebase --abort             # kembali ke keadaan sebelum rebase
```

Rebase dipilih agar riwayat cabang lurus: tidak ada commit "Merge branch develop" yang
membingungkan peninjau. Karena PR digabung dengan *squash*, riwayat `develop` tetap satu
commit per paket.

### Bila push ditolak

Pesan `! [rejected] ... (non-fast-forward)` berarti remote memiliki commit yang tidak ada di
lokal — biasanya karena Anda melakukan rebase/amend, atau ada orang lain yang push ke cabang
yang sama.

```bash
git fetch origin
git rebase origin/<nama-cabang>
git push
```

Jangan langsung memakai `--force`. Periksa dulu dengan `git log --oneline origin/<nama-cabang>`
apa yang tertinggal.

### Aturan force push

| Keadaan | Boleh? | Perintah |
|---|---|---|
| Cabang sendiri, setelah rebase/amend, belum ditinjau | Boleh | `git push --force-with-lease` |
| Cabang sendiri yang sedang ditinjau orang lain | Sebaiknya tidak | Tambah commit baru, jangan tulis ulang |
| `develop` atau `main` | **Tidak pernah** | Ditolak proteksi cabang |
| Cabang milik orang lain | **Tidak pernah** | Minta pemiliknya memperbaiki |

`--force-with-lease` lebih aman daripada `--force`: push dibatalkan bila remote ternyata sudah
berubah sejak pengambilan terakhir, sehingga pekerjaan orang lain tidak tertimpa.

### Menghapus cabang

Setelah PR digabung:

```bash
git push origin --delete <nama-cabang>   # hapus di remote
git branch -d <nama-cabang>              # hapus di lokal
```

Repositori sudah diatur menghapus cabang otomatis setelah PR digabung, jadi perintah di atas
hanya untuk membersihkan sisa.

### Push tag rilis

Hanya PM yang melakukannya, setelah gerbang rilis lolos:

```bash
git tag -a v1.0.0-M3 -m "Rilis M3: surat masuk dan disposisi"
git push origin v1.0.0-M3
gh release create v1.0.0-M3 --generate-notes
```

Tag tidak boleh dipindahkan atau dihapus setelah diumumkan. Bila ada perbaikan, buat tag baru
(`v1.0.1-M3`), jangan menimpa.

---

## 6. Merapikan commit sebelum PR

Riwayat cabang tidak harus sempurna saat bekerja, tetapi harus rapi saat diserahkan.

### Periksa dulu

```bash
git log --oneline --graph origin/develop..HEAD
git diff --stat origin/develop...HEAD
```

Bila ada commit `wip`, `fix lagi`, atau `typo`, rapikan sebelum membuka PR.

### Membetulkan commit terakhir

```bash
git add <berkas-yang-terlewat>
git commit --amend --no-edit        # tambahkan berkas ke commit terakhir
git commit --amend                  # ubah pesan commit terakhir
```

Lakukan hanya pada commit yang belum di-push, atau pada cabang sendiri yang belum ditinjau.
Setelah amend pada cabang yang sudah di-push:

```bash
git push --force-with-lease
```

### Menggabungkan beberapa commit

```bash
git rebase -i origin/develop
```

Di editor, ganti `pick` pada commit kedua dan seterusnya:

| Perintah | Arti |
|---|---|
| `pick` | Pertahankan commit |
| `reword` | Pertahankan perubahan, ubah pesan |
| `squash` | Gabungkan ke commit di atasnya, pesan digabung |
| `fixup` | Gabungkan ke commit di atasnya, pesan dibuang |
| `drop` | Buang commit |

Contoh: tiga commit `wip` menjadi satu commit utuh.

```
pick   a1b2c3d feat(3.1): catat surat masuk
fixup  e4f5a6b wip
fixup  789abcd fix typo
```

Setelah selesai, periksa ulang dengan `git log --oneline`, jalankan uji, lalu
`git push --force-with-lease`.

### Membatalkan commit

| Keadaan | Perintah | Akibat |
|---|---|---|
| Belum di-push, ingin ubah isi | `git reset --soft HEAD~1` | Commit batal, perubahan tetap di-stage |
| Belum di-push, ingin buang total | `git reset --hard HEAD~1` | Perubahan hilang — pastikan dulu |
| Sudah di-push/digabung | `git revert <hash>` | Membuat commit pembatal, riwayat aman |
| Sudah di `develop`/`main` | `git revert` lewat PR | Jangan `reset` pada cabang bersama |

`reset --hard` tidak dapat diurungkan. Sebelum memakainya, pastikan tidak ada perubahan yang
belum di-commit atau simpan sementara dengan `git stash`.

---

## 7. Alur harian

### Semua orang, setiap pagi

```bash
git checkout develop
git pull --ff-only
git checkout <cabang-saya>
git fetch origin
git rebase origin/develop     # selesaikan konflik di lokal
php artisan test              # bila kode sudah ada
```

### Backend (Nabila)

1. Ambil satu paket dari kolom *Todo* pada papan Issues, pindahkan ke *In Progress*.
2. `git checkout -b feature/3.1-surat-masuk-catat-ubah-hapus-lampiran`
3. Kerjakan berurutan: migrasi, model, validasi, controller/service, uji fitur.
4. Tulis nama rute dan nama field pada issue (kontrak untuk Frontend).
5. Commit per satuan logis:
   ```bash
   git add database/migrations app/Models app/Http/Requests
   git commit -m "feat(3.1): tambah tabel dan model surat masuk" -m "Refs: WBS 3.1, R-04"
   ```
6. Sebelum push: `php artisan test` dan `./vendor/bin/pint --dirty`.
7. `git push -u origin HEAD` (pertama kali), lalu `gh pr create --base develop --fill`.
8. Setelah digabung: hapus cabang, ambil paket berikutnya.

### Frontend (Arta)

1. Pastikan kontrak rute dan nama field dari Backend sudah ada pada issue.
2. `git checkout -b feature/3.2-tampilan-surat-masuk-dan-unggah-lampiran`
3. Kerjakan Blade, komponen, dan Tailwind. Jangan mengubah `routes/web.php` atau controller.
4. Periksa tampilan pada 360 px dan 1366 px, empat peramban.
5. Commit dan push:
   ```bash
   git add resources/views resources/css
   git commit -m "feat(3.2): tampilkan daftar surat masuk" -m "Refs: WBS 3.2, R-04"
   npm run build
   git push -u origin HEAD
   ```
6. Buka PR, lampirkan tangkapan layar, minta tinjauan PM.

### PM & QA (Naufal)

1. Menjaga papan Issues, milestone, dan status paket pada WBS.
2. Meninjau PR maksimal 1 x 24 jam: lingkup, keterbacaan, uji, tidak ada rahasia.
3. Menggabung PR dengan *squash*, menghapus cabang, memastikan issue tertutup:
   ```bash
   gh pr checkout <nomor>
   php artisan test
   gh pr merge <nomor> --squash --admin --delete-branch
   ```
4. Menjalankan uji manual R-xx pada akhir fase, mencatat hasil pada matriks uji.
5. Menyiapkan `release/M<n>`, menjalankan daftar periksa gerbang, memberi tag.
6. Memperbarui dokumen bila ada perubahan lingkup.

---

## 8. Kontrak kerja antar peran

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

## 9. Kepemilikan berkas

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

## 10. Pull Request

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
- Bila `develop` sudah berubah, sinkronkan dengan `git rebase origin/develop` lalu
  `git push --force-with-lease`. Jangan memakai tombol *Update branch* — tombol itu membuat
  commit gabungan yang mengotori riwayat cabang. Bila PR sudah disetujui, minta tinjauan ulang
  setelah rebase.

Kewajiban peninjau:

- Memeriksa lingkup: sesuai paket WBS, tidak menyelipkan perubahan lain.
- Memeriksa keamanan: tidak ada rahasia, validasi di server, hak akses.
- Menjalankan `php artisan test` pada salinan lokal bila ragu.
- Memberi keputusan maksimal 1 x 24 jam: *Approve*, *Request changes*, atau komentar.

Cara menggabung:

- **Squash and merge** untuk semua PR, termasuk `release/*` ke `main`. Riwayat `develop` dan
  `main` menjadi satu commit per paket.
- Rebase merge dan merge commit dimatikan pada repositori agar tidak ada dua gaya riwayat.
- Hapus cabang setelah digabung.

---

## 11. Gerbang rilis dan tag

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

## 12. Issue, label, dan milestone

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
Papan: [Projects SISIPIN DISHUB](https://github.com/users/MNAUFALFAKMAL/projects/1).

---

## 13. Definisi selesai per jenis

| Jenis | Definisi selesai |
|---|---|
| `feature` | Fungsi berjalan, uji fitur lulus, diuji manual pada dua ukuran layar (bila tampilan), PR disetujui |
| `fix` | Kesalahan tidak lagi muncul, uji regresi ditambahkan, PR disetujui |
| `docs` | Dokumen lengkap, istilah konsisten, ditinjau PM |
| `test` | Matriks atau skenario terisi, bukti dilampirkan |
| `chore` | Perubahan tidak mengubah perilaku, dicatat pada catatan rilis |

Definisi selesai pada WBS tetap menjadi acuan utama.

---

## 14. Perbaikan darurat setelah rilis

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

## 15. Larangan

1. `git push --force` ke cabang mana pun; bila perlu, hanya `--force-with-lease` pada cabang
   sendiri.
2. Menulis langsung ke `main` atau `develop`.
3. Menulis ulang (rebase/amend) cabang yang sedang ditinjau orang lain.
4. Menggabung PR sendiri tanpa peninjau.
5. Commit `.env`, kunci, kata sandi, atau data pribadi.
6. Mengubah migrasi yang sudah pernah digabung.
7. Menyimpan `vendor/`, `node_modules/`, atau `storage/` pada repositori.
8. Menghapus atau menimpa pekerjaan orang lain tanpa berdiskusi.
9. Menunda penggabungan sampai akhir fase.

---

## 16. Pengaturan repositori

| Hal | Status |
|---|---|
| Cabang `main` dan `develop` | Ada |
| Wajib Pull Request + 1 persetujuan | Aktif pada keduanya |
| Larangan force push dan hapus cabang | Aktif |
| Squash merge dan hapus cabang otomatis | Aktif |
| Rebase merge | Dimatikan |
| Templat PR dan issue | Tersedia di `.github/` |
| Label dan milestone | Tersedia |
| Kolaborator | Nabila (`@nblable`) dan Arta (`@artaeka`) aktif dengan akses Write |

Bila aturan cabang perlu diubah, PM melakukannya melalui **Settings -> Rules** atau `gh api`,
bukan dengan mematikan aturan sementara.

---

## 17. Contekan

### Alur satu commit

```bash
git status
git diff
git add -p
git diff --staged
php artisan test
git commit -m "feat(3.1): catat surat masuk" -m "Refs: WBS 3.1, R-04"
git push
```

### Naufal - PM & QA

```bash
git fetch origin
gh pr list --base develop
gh pr checkout <nomor>
php artisan test
gh pr merge <nomor> --squash --admin --delete-branch

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
git add database/migrations app/Models
git commit -m "feat(3.1): tambah tabel dan model surat masuk" -m "Refs: WBS 3.1, R-04"

php artisan test
./vendor/bin/pint --dirty
git fetch origin && git rebase origin/develop
git push -u origin HEAD
gh pr create --base develop --fill
```

### Arta - Frontend

```bash
git checkout develop && git pull --ff-only
git checkout -b feature/3.2-tampilan-surat-masuk-dan-unggah-lampiran

npm run dev
git add resources/views resources/css
git commit -m "feat(3.2): tampilkan daftar surat masuk" -m "Refs: WBS 3.2, R-04"

npm run build
git fetch origin && git rebase origin/develop
git push -u origin HEAD
gh pr create --base develop --fill
```

### Saat push ditolak

```bash
git fetch origin
git rebase origin/<nama-cabang>
git push
# hanya bila commit sendiri sudah ditulis ulang:
git push --force-with-lease
```

---

## 18. Risiko dan penanganan

| Risiko | Tanda | Penanganan |
|---|---|---|
| Cabang terlalu lama | Umur > 3 hari, konflik berulang | Pecah paket, gabung lebih awal, sinkron harian |
| Konflik `routes/web.php` | Dua PR mengubah baris yang sama | Satu pemilik, Frontend lewat issue |
| PR menumpuk | Lebih dari 3 PR terbuka | Tinjauan harian, batasi pekerjaan paralel |
| `main` rusak | Situs produksi error | Pulihkan tag terakhir, `hotfix` dari `main` |
| Rahasia ter-commit | Peringatan pemindai | Cabut kunci, ganti kredensial, bersihkan riwayat |
| Pekerjaan hilang | Cabang terhapus sebelum digabung | Cabang hanya dihapus setelah PR digabung |

---

## 19. Rujukan

- Rencana dan keputusan tim: [rencana-git-flow.md](rencana-git-flow.md)
- Peta 56 paket ke cabang: [peta-paket-cabang.md](peta-paket-cabang.md)
- Ringkasan kontribusi: [../CONTRIBUTING.md](../CONTRIBUTING.md)
