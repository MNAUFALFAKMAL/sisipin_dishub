# Rencana Alur Kerja Git — 3 Peran

**Status:** Draf 0.7 — 2 dari 11 keputusan ditetapkan (K7, K11); acuan lingkup memakai WBS charter 75 tugas
**Disusun:** 21 Oktober 2026
**Diperbarui:** 21 Oktober 2026
**Pemilik:** Naufal (PM & QA)
**Peninjau:** Nabila (Backend), Arta (Frontend)
**Rujukan teknis:** [`git-flow.md`](git-flow.md) · **Acuan lingkup:** [`wbs.md`](wbs.md) · **Peta cabang:** [`peta-paket-cabang.md`](peta-paket-cabang.md)

> Dokumen ini adalah **rencana**, bukan aturan yang sudah final. Bagian yang belum
> disepakati ditandai ☐. Setelah ketiga anggota menyetujui, status berubah menjadi
> **Berlaku** dan barulah diterapkan pada repositori.

---

## 1. Ringkasan

Repositori `MNAUFALFAKMAL/sisipin_dishub` akan dipakai bertiga untuk mengembangkan website
SISIPIN secara bertahap. Rencana ini mengatur: siapa mengerjakan apa, di cabang mana, kapan
digabung, dan bagaimana hasilnya sampai ke `main` tanpa risiko saling menimpa.

Lima hal yang diusulkan:

1. Dua cabang tetap: `main` (rilis) dan `develop` (integrasi).
2. Setiap tugas WBS dikerjakan pada cabang sendiri, lalu masuk lewat Pull Request.
3. Tidak ada yang menulis langsung ke `main`; `develop` hanya menerima PR.
4. PM meninjau semua PR; Backend dan Frontend tidak meninjau pekerjaannya sendiri.
5. Rilis dilakukan per gerbang M1–M5 dengan tag, bukan kapan saja.

Lingkup kerja mengikuti **WBS charter**: 5 fase, 18 grup, 75 tugas
([`wbs.md`](wbs.md)). Satu tugas = satu cabang = satu PR; satu grup = satu issue
berisi daftar tugasnya.

---

## 2. Kondisi saat ini

| Hal | Keadaan | Keterangan |
|---|---|---|
| Repositori | Ada, publik | `MNAUFALFAKMAL/sisipin_dishub` |
| Kode aplikasi | **Belum ada di repositori ini** | Diisi bertahap mulai tugas 3.1.1 |
| Cabang `main` | Ada | 1 commit: dokumen awal |
| Cabang `develop` | Ada | 1 commit di depan `main` |
| Proteksi cabang | Sudah aktif | Dapat diubah/dimatikan bila tim belum siap |
| Issue | 18 buah | Satu per grup WBS, berisi checklist 75 tugas |
| Milestone | 5 buah | M1–M5 |
| Papan proyek | Ada | Projects "SISIPIN DISHUB": Backlog, Todo, In Progress, Review, Done (18 kartu) |
| Kolaborator | 2 dari 2 | `@nblable` (Backend) dan `@artaeka` (Frontend), akses Write |
| Kontribusi kode | Belum ada | Belum ada PR fitur |

*Bagian yang sudah aktif di atas disiapkan agar rencana dapat langsung dicoba. Bila tim
memutuskan lain, pengaturan dapat dikembalikan.*

---

## 3. Keputusan yang perlu diambil bersama

Diisi pada rapat pertama. Usulan sudah disiapkan agar rapat tidak melebar.

| No | Keputusan | Usulan | Alternatif | Pemilik | Tenggat |
|---|---|---|---|---|---|
| K1 | Model cabang | `main` + `develop` + cabang per paket | Hanya `main` + cabang fitur | Naufal | 23 Okt |
| K2 | Cara menggabung | *Squash merge* (riwayat rapi) | *Merge commit* | Naufal | 23 Okt |
| K3 | Peninjau PR | PM meninjau semua | Tinjau silang BE↔FE | Naufal | 23 Okt |
| K4 | Nama cabang | Memakai kode WBS (`feature/3.4.1-...`) | Nama bebas | Naufal | 23 Okt |
| K5 | Penerapan ke hosting | Hanya dari `main`, saat gerbang | Unggah manual kapan saja | Naufal | 23 Okt |
| K6 | Lokasi tugas | GitHub Issues (18 issue grup) | Trello/Notion + issue ringkas | Nabila | 24 Okt |
| K7 | Jadwal | **Tetap** mengikuti WBS: 7 Sep – 27 Nov 2026 (ditetapkan 21 Okt) | ~~Geser 12 minggu dari 21 Okt~~ (tidak dipilih) | Naufal | Selesai |
| K8 | Batas waktu tinjauan | 1 × 24 jam hari kerja | 2 × 24 jam | Arta | 24 Okt |
| K9 | Alat komunikasi harian | Grup WhatsApp + komentar issue | Hanya issue | Arta | 24 Okt |
| K10 | Siapa boleh menghapus cabang | Otomatis setelah PR digabung | Manual oleh masing-masing | Naufal | 24 Okt |
| K11 | Acuan lingkup | **WBS charter**: 5 fase, 18 grup, 75 tugas (ditetapkan 21 Okt) | ~~WBS 56 paket lama~~ (tidak dipilih) | Naufal | Selesai |

> **K7 ditetapkan: tetap.** Tanggal dokumen mitra tidak diubah (M1 27 Sep, M2 11 Okt,
> M3 25 Okt, M4 15 Nov, M5 27 Nov 2026). Konsekuensinya:
>
> - M1 sudah terlewati, tetapi dokumennya sudah ada sehingga gerbang M1 dapat ditutup.
> - M2 (perancangan) dan M3 (implementasi) dimampatkan ke 22–31 Oktober.
> - Tanggal akhir M5 tidak bergeser; sisa waktu 5 minggu dipakai untuk fase 4–5.
> - Bila M3 meleset, yang digeser adalah tanggal M3 saja (maksimal 31 Okt), bukan M5.
>
> **K11 ditetapkan: WBS charter.** Penomoran memakai tiga tingkat
> (`fase.grup.tugas`, mis. `3.4.1`). WBS 56 paket lama tidak dipakai lagi; isinya
> dipetakan ulang ke 75 tugas pada [`wbs.md`](wbs.md).

---

## 4. Alur yang diusulkan

```
main        ●──────────────●──────────────●──────────────●  rilis M1–M5 + tag
            ↑              ↑              ↑              ↑
         release/M1     release/M2     release/M3     release/M5
            ↑              ↑              ↑              ↑
develop  ●──●──●──●──●──●──●──●──●──●──●──●──●──●──●──●  integrasi harian
           \    /   \    /   \    /
            fitur  fitur  fitur        satu cabang per paket WBS
```

| Cabang | Dari | Ke | Siapa | Umur maksimal |
|---|---|---|---|---|
| `main` | – | – | PM | permanen |
| `develop` | `main` | – | PM | permanen |
| `feature/<kode>-<slug>` | `develop` | `develop` | Backend, Frontend | 3 hari |
| `fix/<kode>-<slug>` | `develop` | `develop` | Backend, Frontend | 1 hari |
| `docs/<kode>-<slug>` | `develop` | `develop` | Semua peran | 3 hari |
| `test/<kode>-<slug>` | `develop` | `develop` | Semua peran | 2 hari |
| `chore/<kode>-<slug>` | `develop` | `develop` | Semua peran | 2 hari |
| `release/M<n>` | `develop` | `main` + `develop` | PM | 2 hari |
| `hotfix/<slug>` | `main` | `main` + `develop` | PM | 1 hari |

Contoh nyata:

- Nabila: `feature/3.4.1-surat-masuk-crud-bidang-tujuan-klasifikasi`
- Arta: `feature/3.4.7-antarmuka-surat-masuk-keluar-form-tabel-pencarian-filter`
- Naufal: `test/4.1.1-rencana-pengujian-dan-test-case-dari-skpl`

---

## 5. Pembagian tugas

### 5.1 Naufal — PM & QA

| No | Tugas | Kapan | Status |
|---|---|---|---|
| 1 | Menyusun rencana ini dan meminta persetujuan | 21–23 Okt | ☐ |
| 2 | Menjaga papan Issues dan milestone | setiap Jumat | ☐ |
| 3 | Meninjau PR maksimal 1 × 24 jam | harian | ☐ |
| 4 | Menggabung PR (squash) dan menghapus cabang | harian | ☐ |
| 5 | Menjalankan matriks uji R-01…R-20 | akhir tiap fase | ☐ |
| 6 | Menyiapkan `release/M<n>`, tag, dan catatan rilis | tiap gerbang | ☐ |
| 7 | Memperbarui dokumen (charter, SKPL, WBS) | bila lingkup berubah | ☐ |
| 8 | Mengerjakan tugas PM pada WBS (`1.1.1`, `1.1.2`, `1.2.5`, `2.1.3`, `2.2.3`, `2.2.4`, `3.1.2`, `3.7.5`, `4.1.1`, `4.2.5`, `4.3.1`, `4.3.2`, `4.3.5`, `5.2.2`, `5.3.2`–`5.3.4`) | harian | ☐ |

### 5.2 Nabila — Backend

| No | Tugas | Kapan | Status |
|---|---|---|---|
| 1 | Undangan diterima; mencoba satu PR percobaan | 22–23 Okt | ☐ |
| 2 | Menetapkan kontrak rute dan nama field untuk Frontend | sebelum modul dimulai | ☐ |
| 3 | Mengerjakan paket `feature/` sesuai urutan WBS | harian | ☐ |
| 4 | Menulis uji fitur untuk setiap paket | bersamaan | ☐ |
| 5 | Menjaga `routes/web.php`, `database/`, `app/` | harian | ☐ |
| 6 | Melaporkan hambatan pada issue, bukan menunda | harian | ☐ |

### 5.3 Arta — Frontend

| No | Tugas | Kapan | Status |
|---|---|---|---|
| 1 | Akun GitHub aktif, menerima undangan `@artaeka` | 22 Okt | ☑ |
| 2 | Menyiapkan kerangka tampilan (layout, komponen) | tugas `3.1.1` | ☐ |
| 3 | Mengerjakan paket `feature/` tampilan sesuai WBS | harian | ☐ |
| 4 | Memeriksa 360 px dan 1366 px pada 4 peramban | setiap PR tampilan | ☐ |
| 5 | Melampirkan tangkapan layar pada PR | setiap PR tampilan | ☐ |
| 6 | Tidak mengubah rute/controller tanpa kesepakatan | harian | ☐ |

---

## 6. Rencana penerapan bertahap

| Tahap | Waktu | Kegiatan | Penanggung jawab | Penanda selesai |
|---|---|---|---|---|
| 0. Kesepakatan | 21–22 Okt | Isi K1–K6, K8–K11; terima undangan; baca dokumen | Naufal | Dokumen berstatus **Berlaku** |
| 1. Pemanasan | 22 Okt | Satu PR percobaan dari Nabila dan Arta | Nabila, Arta | Dua PR tergabung |
| 2. Perancangan (M2) | 22–25 Okt | Fase 2 (`2.1`–`2.3`): DFD, ERD, use case, UI/UX; minimal `2.3.1`–`2.3.4` | Nabila, Arta, Naufal | Gerbang M2 (jadwal 11 Okt, ditutup 25 Okt) |
| 3. Implementasi inti (M3) | 25–31 Okt | Fase 3 inti: `3.1`, `3.2`, `3.3`, `3.4.1`–`3.4.4`, `3.4.7`, `3.6.1`–`3.6.2` | Nabila, Arta, Naufal | Gerbang M3 (jadwal 25 Okt, batas 31 Okt) |
| 4. Sisa implementasi | 1–10 Nov | Fase 3 susulan: `3.4.5`–`3.4.6`, `3.5.1`–`3.5.2`, `3.6.3`, `3.7.1`–`3.7.3`, `3.7.5` | Nabila, Arta, Naufal | Semua modul tersedia di `develop` |
| 5. Pengujian & UAT (M4) | 11–15 Nov | Fase 4: `4.1`, `4.2.1`, `4.3` | Ketiganya | Gerbang M4, tag `v0.4.0-M4` |
| 6. Deployment & serah terima (M5) | 16–27 Nov | Fase 5: `5.1`, `5.2`, `5.3` | Ketiganya | Gerbang M5, tag `v1.0.0-M5` |

*K7 menetapkan jadwal tetap, sehingga tahap 2–4 dimampatkan. Bila M3 meleset, batas
toleransinya 31 Oktober; tanggal M4 dan M5 tidak berubah. Tugas `3.5.3`, `3.5.4`, dan
`3.7.4` berstatus ditunda dan tidak masuk gerbang mana pun.*

---

## 7. Ritme kerja bersama

| Ritme | Waktu | Isi | Durasi |
|---|---|---|---|
| Sinkron harian | pagi, sebelum mulai | `git pull`, bagi tugas hari itu, sebutkan hambatan | 15 menit |
| Tinjauan PR | kapan saja | PM memberi keputusan | ≤ 1 × 24 jam |
| Demo mingguan | Jumat sore | Tunjukkan yang sudah jalan di `develop` | 30 menit |
| Gerbang | M1–M5 | Uji bersama, tag rilis, catatan | 60 menit |

Aturan komunikasi:

- Pertanyaan teknis → komentar pada issue terkait, agar jejaknya tersimpan.
- Hal mendesak (tidak bisa lanjut) → grup WhatsApp, lalu diringkas di issue.
- Keputusan yang mengubah lingkup → PR pada dokumen, bukan hanya kesepakatan lisan.

---

## 8. Aturan main ringkas

1. Tidak menulis langsung ke `main` atau `develop`.
2. Satu tugas WBS = satu cabang = satu PR.
3. Cabang dibuat dari `develop` terbaru, bukan dari cabang orang lain.
4. Commit mengikuti `tipe(kode-wbs): ringkasan`.
5. PR wajib menyebut kode WBS dan kebutuhan (R-xx / NF-xx).
6. Perubahan tampilan wajib menyertakan tangkapan layar.
7. Jangan commit `.env`, kunci, atau data pribadi.
8. Migrasi yang sudah digabung tidak boleh diubah; buat migrasi baru.
9. Cabang yang sudah digabung dihapus.
10. Bila pekerjaan lebih dari 3 hari, pecah tugasnya menjadi beberapa PR.

Rincian aturan ada di [`git-flow.md`](git-flow.md), daftar 75 nama cabang ada di
[`peta-paket-cabang.md`](peta-paket-cabang.md). Istilah layar mengikuti
[`glosarium.md`](glosarium.md) dan hasil pengujian dicatat pada [`matriks-uji.md`](matriks-uji.md).

---

## 9. Urutan dan prioritas tugas

Urutan tidak boleh dibalik karena Frontend membutuhkan kontrak rute dan nama field dari
Backend:

1. Fase 2 menjadi dasar fase 3: `2.1.x` (DFD) → `2.2.x` (ERD & diagram) → `2.3.1`–`2.3.4`
   (wireframe).
2. `3.1.2` repositori → `3.1.1` kerangka tampilan → `3.1.3` basis data dan seeder.
3. `3.2.1` autentikasi → `3.2.2` pengguna → `3.2.3` hak akses, sejalan dengan `3.2.4`
   dan `3.2.5` (antarmuka).
4. `3.3.1` seeding klasifikasi → `3.3.2` katalog dan pencarian.
5. `3.4.1` surat masuk → `3.4.7` antarmuka → `3.4.2` surat keluar → `3.4.3` disposisi
   dan pencetakan → `3.4.4` pencarian dan filter tahun.
6. `3.6.1` query statistik → `3.6.2` antarmuka dashboard.
7. Tugas uji dikerjakan setelah modul yang diuji tergabung: `4.1.x` setelah fase 3,
   `4.3.x` setelah `4.1.x`, dan `5.1.x` setelah gerbang M4.

Prioritas:

| Prioritas | Tugas | Catatan |
|---|---|---|
| Inti | `1.1.1`–`1.2.5`, `2.1.1`–`2.3.4`, `3.1.1`–`3.4.4`, `3.4.7`, `3.6.1`–`3.6.2`, `4.1.1`–`4.1.5`, `4.2.1`, `4.3.1`–`4.3.5`, `5.1.1`–`5.1.3`, `5.3.2`, `5.3.4` | Menentukan alur kerja harian dinas dan harus ada sebelum uji penerimaan |
| Susulan | `2.3.5`, `3.4.5`–`3.4.6`, `3.5.1`–`3.5.2`, `3.6.3`, `3.7.1`–`3.7.3`, `3.7.5`, `4.2.2`–`4.2.5`, `5.2.1`–`5.2.2`, `5.3.1`, `5.3.3` | Dikerjakan bila tugas inti pada fase yang sama sudah tergabung |
| Ditunda | `3.5.3`, `3.5.4`, `3.7.4` | Tidak menghambat pencatatan surat; dikerjakan setelah M5 bila waktu tersisa |

Aturan:

- Tugas susulan dan ditunda tidak boleh dikerjakan sebelum tugas inti pada fase yang sama selesai.
- Tugas ditunda tetap tercatat di papan, tidak dihapus, agar lingkupnya tidak hilang.
- Bila sisa waktu sebuah tugas inti kurang dari tiga hari, yang dipangkas adalah lingkup
  tugasnya, bukan tanggal M5.

---

## 10. Risiko rencana ini

| Risiko | Bila terjadi | Pengurangan |
|---|---|---|
| Rencana tidak disepakati | Semua berjalan tanpa aturan | Bahas K1–K10 pada satu rapat, catat hasilnya di sini |
| Anggota lupa alur | Cabang menumpuk, konflik | Panduan satu halaman + PR percobaan di tahap 1 |
| Jadwal tetap dengan sisa 5 minggu | M2/M3 meleset, fase 4–5 tidak selesai | M2–M3 dimampatkan; M3 boleh geser maksimal 31 Okt; uji dinas 16–27 Nov |
| 75 tugas untuk 3 orang | Beban 344 jam, jalur kritis backend | Tugas ditunda dipangkas; `3.4.5` dan `3.5.3` dipindah ke Backend, `2.3.5` ke Frontend |
| Terlalu banyak issue | Papan penuh, sulit fokus | 18 issue grup; hanya grup fase berjalan yang dipindah ke *Todo* |
| Proteksi terasa menghambat | Anggota mencari jalan pintas | PM membuka jalur `hotfix` dan tinjauan cepat |
| Hanya 3 orang | PR menunggu bila PM sibuk | Sepakati peninjau pengganti (BE↔FE) pada K3 |

---

## 11. Yang belum diputuskan (isi saat rapat)

- [ ] K1 Model cabang
- [ ] K2 Cara menggabung
- [ ] K3 Peninjau PR (termasuk peninjau pengganti)
- [ ] K4 Nama cabang
- [ ] K5 Cara penerapan ke hosting
- [ ] K6 Lokasi tugas
- [x] K7 Jadwal — **tetap** mengikuti WBS, ditetapkan 21 Oktober 2026
- [ ] K8 Batas waktu tinjauan
- [ ] K9 Alat komunikasi harian
- [ ] K10 Penghapusan cabang
- [x] K11 Acuan lingkup — **WBS charter** 75 tugas, ditetapkan 21 Oktober 2026
- [ ] Apakah proteksi `main` dan `develop` dipertahankan
- [x] Susunan issue — 18 issue grup dengan checklist 75 tugas, dipilih 21 Oktober 2026

---

## 12. Cara mengubah rencana ini

1. Buat cabang `docs/<kode>-<ringkas>` dari `develop`.
2. Ubah bagian yang perlu, tambahkan baris pada riwayat perubahan.
3. Buka PR ke `develop`, minta tinjauan dua anggota lain.
4. Setelah disetujui, status dokumen dinaikkan (Draf → Berlaku).

---

## 13. Riwayat perubahan

| Versi | Tanggal | Perubahan | Oleh |
|---|---|---|---|
| 0.1 | 21 Oktober 2026 | Draf awal: ringkasan, keputusan K1–K10, pembagian tugas, rencana penerapan | Naufal |
| 0.2 | 21 Oktober 2026 | K7 ditetapkan tetap; tahap penerapan disesuaikan dengan sisa waktu | Naufal |
| 0.3 | 21 Oktober 2026 | Kolaborator lengkap (`@nblable`, `@artaeka`); 57 issue sudah ditugaskan | Naufal |
| 0.4 | 21 Oktober 2026 | Papan Projects dibuat; 5 kartu Todo, 52 Backlog | Naufal |
| 0.5 | 21 Oktober 2026 | Urutan dan prioritas paket (inti, susulan, ditunda); istilah "dari nol" diganti menjadi pengembangan bertahap | Naufal |
| 0.6 | 21 Oktober 2026 | Cakupan M3 diselaraskan: 2.1–2.4 dan 3.1–3.3; paket 3.4–3.9 dipindah ke M4 | Naufal |
| 0.7 | 21 Oktober 2026 | Acuan lingkup diganti WBS charter (K11): 5 fase, 18 grup, 75 tugas; penerapan, prioritas, dan contoh cabang disesuaikan | Naufal |
| 1.0 | – | Disepakati dan berlaku | – |
