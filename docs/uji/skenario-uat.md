# Skenario dan Formulir Uji Penerimaan

**Paket:** WBS 7.5 · **Issue:** #47
**Pemilik:** Naufal (PM & QA)
**Peserta:** Kasubbag, Staff Subbagian, pengelola teknis
**Perkiraan waktu:** 17–21 November 2026 (fase 7)
**Rujukan:** [`matriks-uji.md`](../matriks-uji.md), `02-Spesifikasi-Kebutuhan-SISIPIN-DISHUB.md`

## Tujuan

Membuktikan bahwa sistem dapat dipakai untuk pekerjaan sehari-hari oleh orang dinas, bukan
hanya oleh pengembang. Setiap skenario diuji dengan data contoh yang mirip data nyata.

## Persiapan

| Hal | Keterangan |
|---|---|
| Lingkungan | Salinan `develop` terbaru pada alamat uji (bukan produksi) |
| Basis data | Data contoh: 20 surat masuk, 15 surat keluar, 5 akun, 1 template naskah |
| Akun uji | `kasubbag.uji`, `staff.uji`, `pengelola.uji` |
| Berkas uji | 3 PDF lampiran, 1 foto, 1 dokumen Word, 1 Excel 50 baris |
| Alat | Komputer 1366 px, ponsel 360 px, pencatat waktu |
| Pencatat | Satu orang mencatat hasil; penguji lain menjalankan langkah |

Aturan pelaksanaan:

1. Penguji tidak dibantu kecuali pada skenario pertama (pemanasan).
2. Setiap langkah yang gagal dicatat apa adanya, termasuk pesan di layar.
3. Temuan langsung dibuat issue baru berlabel `jenis:fix` dan ditautkan ke skenario.
4. Skenario dinyatakan **Lulus** hanya bila seluruh hasil harapan terpenuhi.

## Skenario

| ID | Skenario | Kebutuhan | Langkah ringkas | Hasil yang diharapkan | Status |
|---|---|---|---|---|---|
| S-01 | Masuk dan keluar | R-01 | Masuk sebagai Staff, keluar, buka menu terbatas | Berhasil masuk, keluar mengakhiri sesi, halaman terbatas meminta masuk | |
| S-02 | Hak akses antar peran | R-03 | Masuk sebagai Staff, coba buka menu Kasubbag | Ditolak, tercatat pada riwayat aktivitas | |
| S-03 | Catat surat masuk | R-04 | Catat surat baru dengan 3 lampiran | Surat tersimpan, nomor agenda terbentuk, 3 lampiran terbuka | |
| S-04 | Disposisi surat | R-04 | Kasubbag meneruskan surat ke Staff dengan instruksi | Status berubah menjadi diteruskan, instruksi terlihat oleh Staff | |
| S-05 | Tindak lanjut disposisi | R-04 | Staff menandai disposisi selesai | Status menjadi selesai, waktu tindak lanjut tercatat | |
| S-06 | Surat keluar per jenis | R-05 | Catat SPT, SPPD, SK, dan undangan; saring per jenis | Empat surat tersimpan, saringan menampilkan jenis yang dipilih | |
| S-07 | Klasifikasi arsip | R-08 | Cari kode "005" dan pilih kode pada formulir surat | Kode dan uraian muncul, terpasang pada surat | |
| S-08 | Pencarian surat | R-16 | Cari nomor, perihal, pengirim, dan tanggal | Hasil tepat, waktu tampil di bawah 2 detik | |
| S-09 | Impor dan ekspor Excel | R-06 | Impor berkas 50 baris, lalu ekspor | 50 baris masuk tanpa kesalahan, hasil ekspor terbuka di Excel | |
| S-10 | Laporan PDF | R-07 | Unduh daftar dan rekap periode satu bulan | PDF terbuka, data sesuai periode, ada nomor halaman | |
| S-11 | Dasbor dan saringan tahun | R-09, R-17 | Ganti tahun arsip pada dasbor | Angka dan grafik berubah sesuai tahun | |
| S-12 | Utilitas dokumen | R-12 | Tiga foto menjadi PDF; gabungkan dua PDF | Satu PDF dari tiga foto; satu PDF gabungan | |
| S-13 | KP4 | R-13 | Pilih pegawai, buat KP4, unduh | Data pegawai terisi otomatis, hasil dapat dibuka | |
| S-14 | Halaman publik | R-15, R-19 | Buka tanpa masuk; buka `sitemap.xml` | Hanya klasifikasi dan tata naskah tampil; peta situs terbuka | |
| S-15 | Profil dan kata sandi | R-20 | Ganti kata sandi lalu masuk ulang | Sandi baru berlaku, sandi lama ditolak | |

## Formulir hasil per skenario

Diisi satu lembar untuk setiap skenario.

| Kolom | Isi |
|---|---|
| ID dan nama skenario | |
| Tanggal dan waktu | |
| Penguji | |
| Peramban dan ukuran layar | |
| Langkah yang dijalankan | |
| Hasil yang diperoleh | |
| Status | Lulus / Gagal |
| Temuan (nomor issue) | |
| Bukti (nama berkas) | |

## Berita acara uji penerimaan

**Hari, tanggal:**
**Tempat:**
**Aplikasi yang diuji:** SISIPIN DISHUB, versi `________`
**Alamat lingkungan uji:**

| No | Nama | Peran | Hadir |
|---|---|---|---|
| 1 | | Kasubbag | |
| 2 | | Staff | |
| 3 | | Pengelola teknis | |
| 4 | | PM & QA | |

Rekap hasil:

| Kelompok | Jumlah | Lulus | Gagal |
|---|---:|---:|---:|
| Skenario S-01…S-15 | 15 | | |
| Kebutuhan non-fungsional NF-01…NF-11 | 11 | | |

Catatan temuan penting:

1.
2.
3.

Kesimpulan: **diterima / diterima dengan catatan / belum diterima**

## Setelah uji

- [ ] Semua temuan menjadi issue `jenis:fix` dengan milestone M5.
- [ ] Matriks uji diperbarui (status dan bukti).
- [ ] Hasil dimasukkan ke berita acara dan dilampirkan pada dokumen serah terima.
- [ ] Bila ada temuan besar, jadwalkan uji ulang sebelum 27 November 2026.
