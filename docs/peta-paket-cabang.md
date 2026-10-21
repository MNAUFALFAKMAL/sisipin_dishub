# Peta Paket Kerja ke Cabang

Satu baris = satu paket WBS = satu cabang = satu PR. Nama cabang memakai kode WBS
sehingga issue, PR, dan rencana kerja selalu dapat dicocokkan.

Dokumen ini dihasilkan dari WBS 56 paket (316 jam) dan tidak memuat keputusan baru.
Aturan mainnya ada di [rencana-git-flow.md](rencana-git-flow.md).

| Kode | Paket | PIC | Jam | Cabang |
|---|---|---|---:|---|

## Fase 1 - Perencanaan dan Dokumen

Minggu 1-3 - gerbang M1 (27 Sep)

| Kode | Paket | PIC | Jam | Cabang |
|---|---|---|---:|---|
| 1.1 | Rapat kick-off dan konfirmasi lingkup dengan dinas | Naufal | 3 | `docs/1.1-rapat-kick-off-dan-konfirmasi-lingkup` |
| 1.2 | Susun WBS, papan tugas, dan jadwal | Naufal | 5 | `chore/1.2-susun-wbs-papan-tugas-dan-jadwal` |
| 1.3 | Susun Project Charter versi mitra | Naufal | 5 | `docs/1.3-susun-project-charter-versi-mitra` |
| 1.4 | Susun SKPL versi mitra dan lampiran teknis | Naufal | 5 | `docs/1.4-susun-skpl-versi-mitra-dan-lampiran-teknis` |
| 1.5 | Rancangan tampilan (wireframe) dan panduan visual | Arta | 6 | `feature/1.5-rancangan-tampilan-wireframe-dan-panduan` |
| 1.6 | Daftar istilah dinas dan glosarium | Naufal | 2 | `docs/1.6-daftar-istilah-dinas-dan-glosarium` |
| | **Subtotal fase 1** | | **26** | |

## Fase 2 - Fondasi Sistem

Minggu 4-5 - gerbang M2 (11 Okt)

| Kode | Paket | PIC | Jam | Cabang |
|---|---|---|---:|---|
| 2.1 | Siapkan lingkungan aplikasi, basis data, dan data awal kode klasifikasi | Nabila | 7 | `feature/2.1-siapkan-lingkungan-aplikasi-basis-data` |
| 2.2 | Autentikasi, peran, dan hak akses | Nabila | 8 | `feature/2.2-autentikasi-peran-dan-hak-akses` |
| 2.3 | Tampilan masuk dan menu sesuai peran | Arta | 4 | `feature/2.3-tampilan-masuk-dan-menu-sesuai-peran` |
| 2.4 | Kerangka tampilan: navigasi, tabel, saringan, mode gelap | Arta | 12 | `feature/2.4-kerangka-tampilan-navigasi-tabel-saringan` |
| 2.5 | Riwayat aktivitas dan pengamanan dasar | Nabila | 5 | `feature/2.5-riwayat-aktivitas-dan-pengamanan-dasar` |
| 2.6 | Uji fondasi: masuk, akses, riwayat | Naufal | 3 | `test/2.6-uji-fondasi-masuk-akses-riwayat` |
| | **Subtotal fase 2** | | **39** | |

## Fase 3 - Modul Inti Kearsipan

Minggu 6-7 - gerbang M3 (25 Okt); paket 3.4-3.9 masuk M4

| Kode | Paket | PIC | Jam | Cabang |
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

## Fase 4 - Disposisi dan Tindak Lanjut

Minggu 8 - gerbang M4 (15 Nov)

| Kode | Paket | PIC | Jam | Cabang |
|---|---|---|---:|---|
| 4.1 | Rancangan dan skema disposisi | Nabila | 7 | `feature/4.1-rancangan-dan-skema-disposisi` |
| 4.2 | Alur disposisi dan tindak lanjut | Nabila | 7 | `feature/4.2-alur-disposisi-dan-tindak-lanjut` |
| 4.3 | Tampilan lembar disposisi dan riwayat | Arta | 10 | `feature/4.3-tampilan-lembar-disposisi-dan-riwayat` |
| 4.4 | Pemberitahuan dalam aplikasi | Nabila | 5 | `feature/4.4-pemberitahuan-dalam-aplikasi` |
| 4.5 | Penanda pemberitahuan pada antarmuka | Arta | 4 | `feature/4.5-penanda-pemberitahuan-pada-antarmuka` |
| 4.6 | Uji alur disposisi | Naufal | 4 | `test/4.6-uji-alur-disposisi` |
| | **Subtotal fase 4** | | **37** | |

## Fase 5 - Laporan, Naskah, dan Utilitas

Minggu 9 - gerbang M4 (15 Nov)

| Kode | Paket | PIC | Jam | Cabang |
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

## Fase 6 - Non-Fungsional dan Pengamanan

Minggu 10 - gerbang M4 (15 Nov)

| Kode | Paket | PIC | Jam | Cabang |
|---|---|---|---:|---|
| 6.1 | Pencadangan harian dan uji pemulihan | Nabila | 4 | `feature/6.1-pencadangan-harian-dan-uji-pemulihan` |
| 6.2 | Pengamanan: sesi, batas masuk, unggahan | Nabila | 5 | `feature/6.2-pengamanan-sesi-batas-masuk-unggahan` |
| 6.3 | Uji kecepatan dan perbaikan kueri | Nabila | 4 | `feature/6.3-uji-kecepatan-dan-perbaikan-kueri` |
| 6.4 | Uji tampilan ponsel dan lintas peramban | Arta | 6 | `feature/6.4-uji-tampilan-ponsel-dan-lintas-peramban` |
| 6.5 | Peninjauan bahasa dan kemudahan pemakaian | Naufal | 3 | `feature/6.5-peninjauan-bahasa-dan-kemudahan-pemakaian` |
| | **Subtotal fase 6** | | **22** | |

## Fase 7 - Pengujian dan Uji Penerimaan

Minggu 11 - gerbang M5 (27 Nov)

| Kode | Paket | PIC | Jam | Cabang |
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

## Fase 8 - Penerapan Website dan Serah Terima

Minggu 12 - gerbang M5 (27 Nov)

| Kode | Paket | PIC | Jam | Cabang |
|---|---|---|---:|---|
| 8.1 | Publikasi website, pengaturan domain, dan HTTPS | Nabila | 3 | `chore/8.1-publikasi-website-pengaturan-domain-dan` |
| 8.2 | Pendampingan penerapan dan pemeriksaan akhir | Naufal | 2 | `chore/8.2-pendampingan-penerapan-dan-pemeriksaan` |
| 8.3 | Buku panduan pengguna | Naufal | 4 | `docs/8.3-buku-panduan-pengguna` |
| 8.4 | Tampilan panduan di dalam aplikasi | Arta | 2 | `feature/8.4-tampilan-panduan-di-dalam-aplikasi` |
| 8.5 | Pelatihan dua sesi | Naufal | 4 | `docs/8.5-pelatihan-dua-sesi` |
| 8.6 | Serah terima akun dan dokumen | Naufal | 1 | `chore/8.6-serah-terima-akun-dan-dokumen` |
| | **Subtotal fase 8** | | **16** | |

## Prioritas pengerjaan

Ditetapkan 21 Oktober 2026. Paket inti tidak boleh ditunda; urutan lengkapnya ada pada
[rencana-git-flow.md](rencana-git-flow.md) bagian 9.

| Prioritas | Paket | Catatan |
|---|---|---|
| Inti | 1.1–1.6, 2.1–2.4, 3.1–3.7, 3.9, 4.1–4.3, 5.1–5.2, 6.1–6.2, 7.1, 7.3–7.8, 8.1–8.3, 8.5–8.6 | Wajib ada sebelum uji penerimaan |
| Susulan | 2.5–2.6, 3.8, 3.10, 4.4–4.6, 5.3–5.4, 5.9, 6.3–6.5, 7.2, 8.4 | Dikerjakan setelah paket inti pada fase yang sama |
| Ditunda | 5.5–5.7, bagian SEO pada 5.8 | Dikerjakan setelah M5 bila waktu tersisa |

Paket yang ditunda tetap tercatat di papan dan tidak dihapus, agar lingkupnya tidak hilang.

## Rekap

| Anggota | Peran | Paket | Jam |
|---|---|---:|---:|
| Naufal | PM & QA | 17 | 62 |
| Nabila | Backend | 23 | 150 |
| Arta | Frontend | 16 | 104 |
| **Total** | | **56** | **316** |
