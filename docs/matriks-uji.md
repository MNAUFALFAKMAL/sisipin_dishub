# Matriks Uji Penerimaan

**Pemilik:** Naufal (PM & QA)
**Sumber:** `02-Spesifikasi-Kebutuhan-SISIPIN-DISHUB.md` (R-01…R-20, NF-01…NF-11)
**Status dokumen:** kerangka — diisi bertahap mulai fase 3

## Cara memakai

1. Setiap baris diuji sekali pada fase terkait, lalu diulang pada UAT (fase 7).
2. Status hanya boleh **Lulus** bila bukti sudah dilampirkan.
3. Bukti disimpan di `docs/uji/img/<ID>-<ringkas>.png` dan dicatat pada kolom Bukti.
4. Kegagalan dicatat sebagai issue baru berlabel `jenis:fix`, lalu diuji ulang.

| Status | Arti |
|---|---|
| Belum | Belum diuji |
| Sedang | Sedang diuji |
| Lulus | Sesuai kriteria, bukti ada |
| Gagal | Tidak sesuai, sudah dibuat issue perbaikan |
| Tidak berlaku | Memang tidak diuji (mis. NF-07) |

## Jadwal pengujian

| Fase | Paket uji | Cakupan |
|---|---|---|
| 3 | 3.10 | R-04, R-05, R-08, R-09, R-14, R-16, R-17 |
| 4 | 4.6 | R-04 (disposisi), R-14 |
| 5 | 5.9 | R-06, R-07, R-10, R-11, R-12, R-13, R-15, R-18, R-19 |
| 6 | 7.1–7.5 | R-01, R-02, R-03, R-20, NF-01…NF-11 |
| 7 | 7.6–7.9 | Seluruh R-01…R-20 (UAT bersama dinas) |

## Kebutuhan fungsional

| ID | Kebutuhan | Cara uji | Status | Bukti | Tanggal |
|---|---|---|---|---|---|
| R-01 | Masuk dan keluar aplikasi | Masuk dengan akun Kasubbag dan Staff; coba kata sandi salah; tekan keluar lalu buka halaman terbatas | Belum | | |
| R-02 | Pengelolaan akun pengguna | Buat akun baru; nonaktifkan; coba masuk dengan akun nonaktif; setel ulang kata sandi | Belum | | |
| R-03 | Peran dan hak akses | Dengan akun Staff, buka menu khusus Kasubbag; periksa riwayat aktivitas | Belum | | |
| R-04 | Pencatatan dan disposisi surat masuk | Catat surat dengan 3 lampiran; teruskan ke Kasubbag; tandai selesai; cek daftar | Belum | | |
| R-05 | Pencatatan surat keluar | Catat SPT, SPPD, SK, dan undangan; saring daftar per jenis | Belum | | |
| R-06 | Impor dan ekspor Excel | Impor berkas 50 baris; ekspor; buka hasil di Excel | Belum | | |
| R-07 | Unduh laporan PDF | Unduh daftar dan rekap; periksa isi periode dan nomor halaman | Belum | | |
| R-08 | Daftar kode klasifikasi arsip | Cari kata kunci kode; cocokkan uraian dengan Pergub Lampung 16/2023 | Belum | | |
| R-09 | Dasbor dan grafik | Ganti tahun arsip; pastikan angka dan grafik berubah | Belum | | |
| R-10 | Katalog tata naskah | Buka katalog; unduh satu berkas | Belum | | |
| R-11 | Pengelolaan template naskah | Unggah template; ubah; hapus; cek tampil pada halaman tata naskah | Belum | | |
| R-12 | Utilitas dokumen | Tiga foto menjadi satu PDF; gabungkan dua PDF | Belum | | |
| R-13 | Pembuatan dokumen KP4 | Pilih pegawai; pastikan isian otomatis; unduh hasil | Belum | | |
| R-14 | Riwayat aktivitas | Tambah, ubah, hapus data; cek nama pengguna dan waktu pada riwayat | Belum | | |
| R-15 | Halaman publik | Buka tanpa masuk; pastikan hanya kode klasifikasi dan tata naskah yang tampil | Belum | | |
| R-16 | Pencarian surat | Cari nomor, perihal, pengirim, dan tanggal; ukur waktu tanggap | Belum | | |
| R-17 | Saringan tahun arsip | Pilih 2026; pastikan hanya surat 2026 tampil | Belum | | |
| R-18 | Panduan penggunaan | Buka dari menu; cek tiga langkah tercantum | Belum | | |
| R-19 | Kemudahan ditemukan mesin pencari | Buka `sitemap.xml` dan `robots.txt` melalui peramban | Belum | | |
| R-20 | Profil dan kata sandi | Ubah profil; ganti kata sandi; masuk dengan sandi baru; sandi lama harus gagal | Belum | | |

## Kebutuhan non-fungsional

| ID | Kebutuhan | Cara uji | Status | Bukti | Tanggal |
|---|---|---|---|---|---|
| NF-01 | Tersedia pada jam kerja | Pantau ketersediaan satu bulan; hitung persentase | Belum | | |
| NF-02 | Data dicadangkan setiap hari | Cek 7 cadangan harian dan 4 mingguan; uji pemulihan satu kali | Belum | | |
| NF-03 | Mudah dipakai staf | Tiga staf baru mencatat satu surat dengan panduan satu halaman; catat waktu dan kesalahan | Belum | | |
| NF-04 | Komputer dan ponsel | Empat peramban pada 360 px dan 1366 px; tanpa geser ke samping | Belum | | |
| NF-05 | Ringan | Unggah 5 berkas × 5 MB pada hosting 512 MB / 2 GB | Belum | | |
| NF-06 | Waktu tanggap | Daftar 1.000 surat ≤ 3 dtk; pencarian ≤ 2 dtk; simpan ≤ 3 dtk (10 Mbps, 5 kali, nilai tengah) | Belum | | |
| NF-07 | Keselamatan manusia | Tidak berlaku | Tidak berlaku | | |
| NF-08 | Keamanan data | Delapan butir daftar periksa + tiga uji manual (sandi salah, akses tanpa masuk, unggah berkas terlarang) | Belum | | |
| NF-09 | Bahasa Indonesia | Tinjau istilah layar dan pesan kesalahan bersama dinas | Belum | | |
| NF-10 | Dapat dilanjutkan pihak lain | Pasang ulang mengikuti panduan; catat waktu ≤ 30 menit | Belum | | |
| NF-11 | Tidak terkunci penyedia | Unduh data satu tahun dalam Excel dan PDF; buka hasilnya | Belum | | |

## Rekap

| Kelompok | Jumlah | Lulus | Gagal | Belum |
|---|---:|---:|---:|---:|
| Fungsional R-01…R-20 | 20 | 0 | 0 | 20 |
| Non-fungsional NF-01…NF-11 | 11 | 0 | 0 | 11 |
| **Total** | **31** | **0** | **0** | **31** |

Diperbarui: 21 Oktober 2026
