# WBS SISIPIN DISHUB - Acuan Kerja

**Sumber:** WBS charter (ekspor GitHub Project "WBS Project Lembaga Pers 2026")
**Struktur:** 5 fase, 18 grup, 75 tugas
**Disusun:** 21 Oktober 2026
**Rujukan:** [`git-flow.md`](git-flow.md) - [`peta-paket-cabang.md`](peta-paket-cabang.md)

> Satu tugas = satu cabang = satu PR. Estimasi jam dipetakan dari WBS 56 paket
> sebelumnya agar beban kerja tetap terukur. Prioritas menentukan urutan, bukan
> menghapus tugas dari lingkup.

## Ringkasan

| Fase | Nama | Grup | Tugas | Jam |
|---|---|---:|---:|---:|
| 1 | Perencanaan & Analisis Kebutuhan (M1) | 2 | 9 | 31 |
| 2 | Perancangan Sistem (M2) | 3 | 13 | 49 |
| 3 | Implementasi / Pengembangan (M3) | 7 | 29 | 180 |
| 4 | Pengujian (M4) | 3 | 15 | 59 |
| 5 | Deployment & Serah Terima (M5) | 3 | 9 | 25 |
| **Total** | | **18** | **75** | **344** |

## 1. Perencanaan & Analisis Kebutuhan (M1)

| Kode | Paket Kerja | PIC | Peran | Jam | Prioritas |
|---|---|---|---|---:|---|
| 1.1.1 | Inisiasi proyek & penyusunan Project Charter | Naufal | PM | 4 | inti |
| 1.1.2 | Wawancara & observasi kebutuhan dengan stakeholder Dishub | Naufal | PM | 3 | inti |
| 1.1.3 | Studi literatur regulasi kearsipan (SIKD, JRA SKKAAD, Permenpan RB, Perka ANRI) | Nabila | Backend | 4 | inti |
| 1.1.4 | Analisis sistem & alur kearsipan manual yang berjalan | Arta | Frontend | 4 | inti |
| 1.2.1 | Analisis kebutuhan fungsional | Nabila | Backend | 4 | inti |
| 1.2.2 | Analisis kebutuhan non-fungsional & batasan perancangan | Arta | Frontend | 3 | inti |
| 1.2.3 | Penulisan dokumen SKPL (GL01-SKPL) | Nabila | Backend | 6 | inti |
| 1.2.4 | Review dokumen SKPL (pemeriksa) | Arta | Frontend | 2 | inti |
| 1.2.5 | Persetujuan SKPL oleh PM & dosen pembimbing | Naufal | PM | 1 | inti |
| | **Subtotal fase 1** | | | **31** | |

## 2. Perancangan Sistem (M2)

| Kode | Paket Kerja | PIC | Peran | Jam | Prioritas |
|---|---|---|---|---:|---|
| 2.1.1 | Diagram konteks sistem | Nabila | Backend | 2 | inti |
| 2.1.2 | DFD Level 0/1 (P1 Pengguna & Hak Akses, P2 Surat Menyurat, P3 Arsip & Naskah) & P-SPEC | Nabila | Backend | 5 | inti |
| 2.1.3 | Perancangan arsitektur sistem (Laravel MVC, client-server, hosting) | Naufal | PM | 3 | inti |
| 2.2.1 | ERD & skema database (data store D1-D11) | Nabila | Backend | 5 | inti |
| 2.2.2 | Kamus data & kerunutan Data Store vs E-R | Nabila | Backend | 3 | inti |
| 2.2.3 | Use Case Diagram (Super Admin, Tata Usaha, Pegawai, Publik) | Naufal | PM | 3 | inti |
| 2.2.4 | Activity Diagram alur utama (surat masuk/keluar, klasifikasi, tata naskah) | Naufal | PM | 3 | inti |
| 2.2.5 | Sequence Diagram & Class Diagram | Arta | Frontend | 4 | inti |
| 2.3.1 | Alur navigasi & struktur menu per role | Arta | Frontend | 3 | inti |
| 2.3.2 | Wireframe halaman publik & autentikasi | Arta | Frontend | 4 | inti |
| 2.3.3 | Wireframe dashboard, surat masuk/keluar & klasifikasi arsip | Arta | Frontend | 6 | inti |
| 2.3.4 | Wireframe tata naskah/template, utilitas dokumen, KP4 & manajemen pengguna | Arta | Frontend | 4 | inti |
| 2.3.5 | Prototipe responsif (desktop/mobile) & finalisasi UI/UX | Arta | Frontend | 4 | susulan |
| | **Subtotal fase 2** | | | **49** | |

## 3. Implementasi / Pengembangan (M3)

| Kode | Paket Kerja | PIC | Peran | Jam | Prioritas |
|---|---|---|---|---:|---|
| 3.1.1 | Inisialisasi Laravel 13, Tailwind CSS v3, Alpine.js & layout Blade dasar | Arta | Frontend | 8 | inti |
| 3.1.2 | Setup repository Git, branching & Conventional Commits | Naufal | PM | 3 | inti |
| 3.1.3 | Konfigurasi MySQL, migrasi 11 tabel & seeder awal | Nabila | Backend | 7 | inti |
| 3.2.1 | Autentikasi login/logout (Laravel Breeze kustom) | Nabila | Backend | 6 | inti |
| 3.2.2 | Manajemen pengguna (tambah, ubah, hapus, aktif/nonaktif) | Nabila | Backend | 6 | inti |
| 3.2.3 | Role & hak akses (Super Admin, Tata Usaha, Pegawai) | Nabila | Backend | 6 | inti |
| 3.2.4 | Antarmuka login & kelola pengguna | Arta | Frontend | 5 | inti |
| 3.2.5 | Profil pengguna & ubah password | Arta | Frontend | 3 | inti |
| 3.3.1 | Seeding 2.985 kode klasifikasi JRA SKKAAD hierarkis & masa simpan | Nabila | Backend | 5 | inti |
| 3.3.2 | Katalog & pencarian klasifikasi arsip (UI) | Arta | Frontend | 5 | inti |
| 3.4.1 | Surat masuk (CRUD, bidang tujuan, klasifikasi) | Nabila | Backend | 12 | inti |
| 3.4.2 | Surat keluar (CRUD; jenis SPT, SPPD, SK, Undangan) | Nabila | Backend | 10 | inti |
| 3.4.3 | Disposisi, pencetakan & pelacakan status surat | Nabila | Backend | 12 | inti |
| 3.4.4 | Pencarian surat & filter tahun arsip | Nabila | Backend | 5 | inti |
| 3.4.5 | Import/Export data surat format Excel (.xlsx) | Nabila | Backend | 8 | susulan |
| 3.4.6 | Export data surat format PDF (DomPDF) | Arta | Frontend | 6 | susulan |
| 3.4.7 | Antarmuka surat masuk/keluar (form, tabel, pencarian, filter) | Arta | Frontend | 10 | inti |
| 3.5.1 | Katalog tata naskah | Arta | Frontend | 4 | susulan |
| 3.5.2 | Manajemen template dokumen (upload, ubah, hapus) | Nabila | Backend | 6 | susulan |
| 3.5.3 | Utilitas dokumen: foto ke PDF, Word ke PDF, gabung PDF | Nabila | Backend | 8 | ditunda |
| 3.5.4 | KP4 Generator (dokumen tunjangan keluarga dari template) | Arta | Frontend | 6 | ditunda |
| 3.6.1 | Statistik & grafik tren surat masuk/keluar dengan filter tanggal & bidang (query) | Nabila | Backend | 6 | inti |
| 3.6.2 | Antarmuka dashboard (kartu statistik & grafik) | Arta | Frontend | 6 | inti |
| 3.6.3 | Ekspor laporan dashboard ke PDF & Excel | Nabila | Backend | 5 | susulan |
| 3.7.1 | Audit log pencatatan aktivitas pengguna | Nabila | Backend | 4 | susulan |
| 3.7.2 | Halaman publik (landing, kode klasifikasi, tata naskah) | Arta | Frontend | 6 | susulan |
| 3.7.3 | Halaman panduan penggunaan bagi pengguna baru | Arta | Frontend | 3 | susulan |
| 3.7.4 | SEO: sitemap XML & robots.txt | Nabila | Backend | 3 | ditunda |
| 3.7.5 | Integrasi antar modul & penyelarasan responsif | Naufal | PM | 6 | susulan |
| | **Subtotal fase 3** | | | **180** | |

## 4. Pengujian (M4)

| Kode | Paket Kerja | PIC | Peran | Jam | Prioritas |
|---|---|---|---|---:|---|
| 4.1.1 | Rencana pengujian & test case dari SKPL | Naufal | PM | 4 | inti |
| 4.1.2 | Unit test (model, controller, service) | Nabila | Backend | 5 | inti |
| 4.1.3 | Integration test antar modul & alur per role | Arta | Frontend | 4 | inti |
| 4.1.4 | Perbaikan bug hasil pengujian fungsional sisi backend | Nabila | Backend | 6 | inti |
| 4.1.5 | Perbaikan bug hasil pengujian fungsional sisi frontend | Arta | Frontend | 6 | inti |
| 4.2.1 | Uji keamanan (bcrypt, CSRF, validasi input, session 24 jam, HTTPS) | Nabila | Backend | 4 | inti |
| 4.2.2 | Uji kinerja (muat halaman <=3 dtk, pencarian <=2 dtk) & kebutuhan sumber daya | Arta | Frontend | 3 | susulan |
| 4.2.3 | Uji kompatibilitas browser, responsivitas & ergonomi | Arta | Frontend | 4 | susulan |
| 4.2.4 | Uji ketersediaan & keandalan (backup harian, error rate <0,1%) | Nabila | Backend | 3 | susulan |
| 4.2.5 | Review bahasa antarmuka (Bahasa Indonesia) | Naufal | PM | 2 | susulan |
| 4.3.1 | Penyusunan skenario UAT & form sign-off | Naufal | PM | 4 | inti |
| 4.3.2 | Pelaksanaan UAT bersama staf Dishub | Naufal | PM | 4 | inti |
| 4.3.3 | Perbaikan temuan UAT sisi backend | Nabila | Backend | 5 | inti |
| 4.3.4 | Perbaikan temuan UAT sisi frontend | Arta | Frontend | 4 | inti |
| 4.3.5 | Persetujuan hasil UAT (sign-off) | Naufal | PM | 1 | inti |
| | **Subtotal fase 4** | | | **59** | |

## 5. Deployment & Serah Terima (M5)

| Kode | Paket Kerja | PIC | Peran | Jam | Prioritas |
|---|---|---|---|---:|---|
| 5.1.1 | Konfigurasi hosting Hostinger, domain .my.id & SSL | Nabila | Backend | 3 | inti |
| 5.1.2 | Deploy aplikasi & migrasi database produksi | Nabila | Backend | 2 | inti |
| 5.1.3 | Smoke test produksi | Arta | Frontend | 2 | inti |
| 5.2.1 | Penyusunan user manual (panduan pengguna staf dinas) | Arta | Frontend | 4 | susulan |
| 5.2.2 | Pelatihan pengguna Dishub (Super Admin, Tata Usaha, Pegawai) | Naufal | PM | 4 | susulan |
| 5.3.1 | Finalisasi dokumentasi teknis (arsitektur & panduan pengembangan) | Nabila | Backend | 4 | susulan |
| 5.3.2 | Serah terima sistem & akses ke Dishub (berita acara) | Naufal | PM | 2 | inti |
| 5.3.3 | Dukungan pasca implementasi (pemeliharaan awal) | Naufal | PM | 3 | susulan |
| 5.3.4 | Penutupan proyek & sign-off Project Charter | Naufal | PM | 1 | inti |
| | **Subtotal fase 5** | | | **25** | |

## Rekap Beban

| Anggota | Peran | Tugas | Jam |
|---|---|---:|---:|
| Naufal | PM | 17 | 51 |
| Nabila | Backend | 31 | 170 |
| Arta | Frontend | 27 | 123 |
| **Total** | | **75** | **344** |

## Prioritas

| Prioritas | Tugas | Arti |
|---|---|---|
| inti | 1.1.1, 1.1.2, 1.1.3, 1.1.4, 1.2.1, 1.2.2, 1.2.3, 1.2.4, 1.2.5, 2.1.1, 2.1.2, 2.1.3, 2.2.1, 2.2.2, 2.2.3, 2.2.4, 2.2.5, 2.3.1, 2.3.2, 2.3.3, 2.3.4, 3.1.1, 3.1.2, 3.1.3, 3.2.1, 3.2.2, 3.2.3, 3.2.4, 3.2.5, 3.3.1, 3.3.2, 3.4.1, 3.4.2, 3.4.3, 3.4.4, 3.4.7, 3.6.1, 3.6.2, 4.1.1, 4.1.2, 4.1.3, 4.1.4, 4.1.5, 4.2.1, 4.3.1, 4.3.2, 4.3.3, 4.3.4, 4.3.5, 5.1.1, 5.1.2, 5.1.3, 5.3.2, 5.3.4 | Wajib selesai sebelum uji penerimaan |
| susulan | 2.3.5, 3.4.5, 3.4.6, 3.5.1, 3.5.2, 3.6.3, 3.7.1, 3.7.2, 3.7.3, 3.7.5, 4.2.2, 4.2.3, 4.2.4, 4.2.5, 5.2.1, 5.2.2, 5.3.1, 5.3.3 | Dikerjakan setelah paket inti pada fase yang sama |
| ditunda | 3.5.3, 3.5.4, 3.7.4 | Dikerjakan setelah M5 bila waktu tersisa |

## Catatan penyesuaian

- `2.3.5` prototipe responsif dipindah dari PM ke Arta agar PM tetap pada tugas
  peninjauan.
- `3.4.5` impor/ekspor Excel dan `3.5.3` utilitas dokumen dipindah dari PM ke Nabila
  karena keduanya pekerjaan backend.
- `3.7.5` integrasi antar modul tetap di Naufal karena sifatnya pemeriksaan, bukan
  penulisan fitur.
- `1.3.4.3` pada WBS lama (disposisi) dipecah menjadi bagian dari `3.4.3` dan rincian
  alur disposisi mengikuti dokumen SKPL.

