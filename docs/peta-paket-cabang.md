# Peta Paket Kerja ke Cabang

Satu baris = satu tugas WBS = satu cabang = satu PR. Nama cabang memakai kode WBS
sehingga issue, PR, dan rencana kerja selalu dapat dicocokkan.

Dokumen ini diturunkan dari [`wbs.md`](wbs.md) (WBS charter, 75 tugas). Aturan
mainnya ada di [rencana-git-flow.md](rencana-git-flow.md).

## Ringkasan cabang

| Tipe | Jumlah | Contoh |
|---|---:|---|
| `docs/` | 25 | `docs/1.1.1-inisiasi-proyek-dan-penyusunan-project-charter` |
| `feature/` | 28 | `feature/3.4.1-surat-masuk-crud-bidang-tujuan-klasifikasi` |
| `test/` | 11 | `test/4.1.1-rencana-pengujian-dan-test-case-dari-skpl` |
| `chore/` | 7 | `chore/5.1.2-deploy-aplikasi-dan-migrasi-database-produksi` |

## Fase 1 - Perencanaan & Analisis Kebutuhan (M1)

| Kode | Paket | PIC | Prioritas | Cabang |
|---|---|---|---|---|
| 1.1.1 | Inisiasi proyek & penyusunan Project Charter | Naufal | inti | `docs/1.1.1-inisiasi-proyek-dan-penyusunan-project-charter` |
| 1.1.2 | Wawancara & observasi kebutuhan dengan stakeholder Dishub | Naufal | inti | `docs/1.1.2-wawancara-dan-observasi-kebutuhan-dengan-stakeholder` |
| 1.1.3 | Studi literatur regulasi kearsipan (SIKD, JRA SKKAAD, Permenpan RB, Perka ANRI) | Nabila | inti | `docs/1.1.3-studi-literatur-regulasi-kearsipan-sikd-jra-skkaad` |
| 1.1.4 | Analisis sistem & alur kearsipan manual yang berjalan | Arta | inti | `docs/1.1.4-analisis-sistem-dan-alur-kearsipan-manual-yang-berjalan` |
| 1.2.1 | Analisis kebutuhan fungsional | Nabila | inti | `docs/1.2.1-analisis-kebutuhan-fungsional` |
| 1.2.2 | Analisis kebutuhan non-fungsional & batasan perancangan | Arta | inti | `docs/1.2.2-analisis-kebutuhan-non-fungsional-dan-batasan` |
| 1.2.3 | Penulisan dokumen SKPL (GL01-SKPL) | Nabila | inti | `docs/1.2.3-penulisan-dokumen-skpl-gl01-skpl` |
| 1.2.4 | Review dokumen SKPL (pemeriksa) | Arta | inti | `docs/1.2.4-review-dokumen-skpl-pemeriksa` |
| 1.2.5 | Persetujuan SKPL oleh PM & dosen pembimbing | Naufal | inti | `docs/1.2.5-persetujuan-skpl-oleh-pm-dan-dosen-pembimbing` |

## Fase 2 - Perancangan Sistem (M2)

| Kode | Paket | PIC | Prioritas | Cabang |
|---|---|---|---|---|
| 2.1.1 | Diagram konteks sistem | Nabila | inti | `docs/2.1.1-diagram-konteks-sistem` |
| 2.1.2 | DFD Level 0/1 (P1 Pengguna & Hak Akses, P2 Surat Menyurat, P3 Arsip & Naskah) & P-SPEC | Nabila | inti | `docs/2.1.2-dfd-level-0-1-p1-pengguna-dan-hak-akses-p2-surat` |
| 2.1.3 | Perancangan arsitektur sistem (Laravel MVC, client-server, hosting) | Naufal | inti | `docs/2.1.3-perancangan-arsitektur-sistem-laravel-mvc-client-server` |
| 2.2.1 | ERD & skema database (data store D1-D11) | Nabila | inti | `docs/2.2.1-erd-dan-skema-database-data-store-d1-d11` |
| 2.2.2 | Kamus data & kerunutan Data Store vs E-R | Nabila | inti | `docs/2.2.2-kamus-data-dan-kerunutan-data-store-vs-e-r` |
| 2.2.3 | Use Case Diagram (Super Admin, Tata Usaha, Pegawai, Publik) | Naufal | inti | `docs/2.2.3-use-case-diagram-super-admin-tata-usaha-pegawai-publik` |
| 2.2.4 | Activity Diagram alur utama (surat masuk/keluar, klasifikasi, tata naskah) | Naufal | inti | `docs/2.2.4-activity-diagram-alur-utama-surat-masuk-keluar` |
| 2.2.5 | Sequence Diagram & Class Diagram | Arta | inti | `docs/2.2.5-sequence-diagram-dan-class-diagram` |
| 2.3.1 | Alur navigasi & struktur menu per role | Arta | inti | `docs/2.3.1-alur-navigasi-dan-struktur-menu-per-role` |
| 2.3.2 | Wireframe halaman publik & autentikasi | Arta | inti | `docs/2.3.2-wireframe-halaman-publik-dan-autentikasi` |
| 2.3.3 | Wireframe dashboard, surat masuk/keluar & klasifikasi arsip | Arta | inti | `docs/2.3.3-wireframe-dashboard-surat-masuk-keluar-dan-klasifikasi` |
| 2.3.4 | Wireframe tata naskah/template, utilitas dokumen, KP4 & manajemen pengguna | Arta | inti | `docs/2.3.4-wireframe-tata-naskah-template-utilitas-dokumen-kp4-dan` |
| 2.3.5 | Prototipe responsif (desktop/mobile) & finalisasi UI/UX | Arta | susulan | `docs/2.3.5-prototipe-responsif-desktop-mobile-dan-finalisasi-ui-ux` |

## Fase 3 - Implementasi / Pengembangan (M3)

| Kode | Paket | PIC | Prioritas | Cabang |
|---|---|---|---|---|
| 3.1.1 | Inisialisasi Laravel 13, Tailwind CSS v3, Alpine.js & layout Blade dasar | Arta | inti | `feature/3.1.1-inisialisasi-laravel-13-tailwind-css-v3-alpine-js-dan` |
| 3.1.2 | Setup repository Git, branching & Conventional Commits | Naufal | inti | `chore/3.1.2-setup-repository-git-branching-dan-conventional-commits` |
| 3.1.3 | Konfigurasi MySQL, migrasi 11 tabel & seeder awal | Nabila | inti | `feature/3.1.3-konfigurasi-mysql-migrasi-11-tabel-dan-seeder-awal` |
| 3.2.1 | Autentikasi login/logout (Laravel Breeze kustom) | Nabila | inti | `feature/3.2.1-autentikasi-login-logout-laravel-breeze-kustom` |
| 3.2.2 | Manajemen pengguna (tambah, ubah, hapus, aktif/nonaktif) | Nabila | inti | `feature/3.2.2-manajemen-pengguna-tambah-ubah-hapus-aktif-nonaktif` |
| 3.2.3 | Role & hak akses (Super Admin, Tata Usaha, Pegawai) | Nabila | inti | `feature/3.2.3-role-dan-hak-akses-super-admin-tata-usaha-pegawai` |
| 3.2.4 | Antarmuka login & kelola pengguna | Arta | inti | `feature/3.2.4-antarmuka-login-dan-kelola-pengguna` |
| 3.2.5 | Profil pengguna & ubah password | Arta | inti | `feature/3.2.5-profil-pengguna-dan-ubah-password` |
| 3.3.1 | Seeding 2.985 kode klasifikasi JRA SKKAAD hierarkis & masa simpan | Nabila | inti | `feature/3.3.1-seeding-2-985-kode-klasifikasi-jra-skkaad-hierarkis-dan` |
| 3.3.2 | Katalog & pencarian klasifikasi arsip (UI) | Arta | inti | `feature/3.3.2-katalog-dan-pencarian-klasifikasi-arsip-ui` |
| 3.4.1 | Surat masuk (CRUD, bidang tujuan, klasifikasi) | Nabila | inti | `feature/3.4.1-surat-masuk-crud-bidang-tujuan-klasifikasi` |
| 3.4.2 | Surat keluar (CRUD; jenis SPT, SPPD, SK, Undangan) | Nabila | inti | `feature/3.4.2-surat-keluar-crud-jenis-spt-sppd-sk-undangan` |
| 3.4.3 | Disposisi, pencetakan & pelacakan status surat | Nabila | inti | `feature/3.4.3-disposisi-pencetakan-dan-pelacakan-status-surat` |
| 3.4.4 | Pencarian surat & filter tahun arsip | Nabila | inti | `feature/3.4.4-pencarian-surat-dan-filter-tahun-arsip` |
| 3.4.5 | Import/Export data surat format Excel (.xlsx) | Nabila | susulan | `feature/3.4.5-import-export-data-surat-format-excel-xlsx` |
| 3.4.6 | Export data surat format PDF (DomPDF) | Arta | susulan | `feature/3.4.6-export-data-surat-format-pdf-dompdf` |
| 3.4.7 | Antarmuka surat masuk/keluar (form, tabel, pencarian, filter) | Arta | inti | `feature/3.4.7-antarmuka-surat-masuk-keluar-form-tabel-pencarian` |
| 3.5.1 | Katalog tata naskah | Arta | susulan | `feature/3.5.1-katalog-tata-naskah` |
| 3.5.2 | Manajemen template dokumen (upload, ubah, hapus) | Nabila | susulan | `feature/3.5.2-manajemen-template-dokumen-upload-ubah-hapus` |
| 3.5.3 | Utilitas dokumen: foto ke PDF, Word ke PDF, gabung PDF | Nabila | ditunda | `feature/3.5.3-utilitas-dokumen-foto-ke-pdf-word-ke-pdf-gabung-pdf` |
| 3.5.4 | KP4 Generator (dokumen tunjangan keluarga dari template) | Arta | ditunda | `feature/3.5.4-kp4-generator-dokumen-tunjangan-keluarga-dari-template` |
| 3.6.1 | Statistik & grafik tren surat masuk/keluar dengan filter tanggal & bidang (query) | Nabila | inti | `feature/3.6.1-statistik-dan-grafik-tren-surat-masuk-keluar-dengan` |
| 3.6.2 | Antarmuka dashboard (kartu statistik & grafik) | Arta | inti | `feature/3.6.2-antarmuka-dashboard-kartu-statistik-dan-grafik` |
| 3.6.3 | Ekspor laporan dashboard ke PDF & Excel | Nabila | susulan | `feature/3.6.3-ekspor-laporan-dashboard-ke-pdf-dan-excel` |
| 3.7.1 | Audit log pencatatan aktivitas pengguna | Nabila | susulan | `feature/3.7.1-audit-log-pencatatan-aktivitas-pengguna` |
| 3.7.2 | Halaman publik (landing, kode klasifikasi, tata naskah) | Arta | susulan | `feature/3.7.2-halaman-publik-landing-kode-klasifikasi-tata-naskah` |
| 3.7.3 | Halaman panduan penggunaan bagi pengguna baru | Arta | susulan | `feature/3.7.3-halaman-panduan-penggunaan-bagi-pengguna-baru` |
| 3.7.4 | SEO: sitemap XML & robots.txt | Nabila | ditunda | `feature/3.7.4-seo-sitemap-xml-dan-robots-txt` |
| 3.7.5 | Integrasi antar modul & penyelarasan responsif | Naufal | susulan | `feature/3.7.5-integrasi-antar-modul-dan-penyelarasan-responsif` |

## Fase 4 - Pengujian (M4)

| Kode | Paket | PIC | Prioritas | Cabang |
|---|---|---|---|---|
| 4.1.1 | Rencana pengujian & test case dari SKPL | Naufal | inti | `test/4.1.1-rencana-pengujian-dan-test-case-dari-skpl` |
| 4.1.2 | Unit test (model, controller, service) | Nabila | inti | `test/4.1.2-unit-test-model-controller-service` |
| 4.1.3 | Integration test antar modul & alur per role | Arta | inti | `test/4.1.3-integration-test-antar-modul-dan-alur-per-role` |
| 4.1.4 | Perbaikan bug hasil pengujian fungsional sisi backend | Nabila | inti | `fix/4.1.4-perbaikan-bug-hasil-pengujian-fungsional-sisi-backend` |
| 4.1.5 | Perbaikan bug hasil pengujian fungsional sisi frontend | Arta | inti | `fix/4.1.5-perbaikan-bug-hasil-pengujian-fungsional-sisi-frontend` |
| 4.2.1 | Uji keamanan (bcrypt, CSRF, validasi input, session 24 jam, HTTPS) | Nabila | inti | `test/4.2.1-uji-keamanan-bcrypt-csrf-validasi-input-session-24-jam` |
| 4.2.2 | Uji kinerja (muat halaman <=3 dtk, pencarian <=2 dtk) & kebutuhan sumber daya | Arta | susulan | `test/4.2.2-uji-kinerja-muat-halaman-3-dtk-pencarian-2-dtk-dan` |
| 4.2.3 | Uji kompatibilitas browser, responsivitas & ergonomi | Arta | susulan | `test/4.2.3-uji-kompatibilitas-browser-responsivitas-dan-ergonomi` |
| 4.2.4 | Uji ketersediaan & keandalan (backup harian, error rate <0,1%) | Nabila | susulan | `test/4.2.4-uji-ketersediaan-dan-keandalan-backup-harian-error-rate` |
| 4.2.5 | Review bahasa antarmuka (Bahasa Indonesia) | Naufal | susulan | `test/4.2.5-review-bahasa-antarmuka-bahasa-indonesia` |
| 4.3.1 | Penyusunan skenario UAT & form sign-off | Naufal | inti | `test/4.3.1-penyusunan-skenario-uat-dan-form-sign-off` |
| 4.3.2 | Pelaksanaan UAT bersama staf Dishub | Naufal | inti | `test/4.3.2-pelaksanaan-uat-bersama-staf-dishub` |
| 4.3.3 | Perbaikan temuan UAT sisi backend | Nabila | inti | `fix/4.3.3-perbaikan-temuan-uat-sisi-backend` |
| 4.3.4 | Perbaikan temuan UAT sisi frontend | Arta | inti | `fix/4.3.4-perbaikan-temuan-uat-sisi-frontend` |
| 4.3.5 | Persetujuan hasil UAT (sign-off) | Naufal | inti | `test/4.3.5-persetujuan-hasil-uat-sign-off` |

## Fase 5 - Deployment & Serah Terima (M5)

| Kode | Paket | PIC | Prioritas | Cabang |
|---|---|---|---|---|
| 5.1.1 | Konfigurasi hosting Hostinger, domain .my.id & SSL | Nabila | inti | `chore/5.1.1-konfigurasi-hosting-hostinger-domain-my-id-dan-ssl` |
| 5.1.2 | Deploy aplikasi & migrasi database produksi | Nabila | inti | `chore/5.1.2-deploy-aplikasi-dan-migrasi-database-produksi` |
| 5.1.3 | Smoke test produksi | Arta | inti | `chore/5.1.3-smoke-test-produksi` |
| 5.2.1 | Penyusunan user manual (panduan pengguna staf dinas) | Arta | susulan | `docs/5.2.1-penyusunan-user-manual-panduan-pengguna-staf-dinas` |
| 5.2.2 | Pelatihan pengguna Dishub (Super Admin, Tata Usaha, Pegawai) | Naufal | susulan | `docs/5.2.2-pelatihan-pengguna-dishub-super-admin-tata-usaha` |
| 5.3.1 | Finalisasi dokumentasi teknis (arsitektur & panduan pengembangan) | Nabila | susulan | `docs/5.3.1-finalisasi-dokumentasi-teknis-arsitektur-dan-panduan` |
| 5.3.2 | Serah terima sistem & akses ke Dishub (berita acara) | Naufal | inti | `chore/5.3.2-serah-terima-sistem-dan-akses-ke-dishub-berita-acara` |
| 5.3.3 | Dukungan pasca implementasi (pemeliharaan awal) | Naufal | susulan | `chore/5.3.3-dukungan-pasca-implementasi-pemeliharaan-awal` |
| 5.3.4 | Penutupan proyek & sign-off Project Charter | Naufal | inti | `chore/5.3.4-penutupan-proyek-dan-sign-off-project-charter` |

