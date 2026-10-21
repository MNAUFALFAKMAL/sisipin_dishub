# Checklist Serah Terima Akun dan Dokumen

**Paket:** WBS 8.6 · **Issue:** #56
**Pemilik:** Naufal (PM & QA)
**Waktu pelaksanaan:** 26–27 November 2026
**Pihak:** Tim pengembang (Naufal, Nabila, Arta) dan Subbagian Umum dan Kepegawaian

## Prinsip

1. Yang diserahkan adalah **akses, data, kode, dan dokumen** — bukan hanya tautan.
2. Kata sandi tidak pernah ditulis di repositori, dokumen, atau grup obrolan.
3. Setiap butir harus dapat diperiksa ulang oleh penerima tanpa bertanya kepada pengembang.
4. Serah terima dinyatakan selesai setelah berita acara ditandatangani kedua pihak.

## A. Akun dan akses

| No | Butir | Diserahkan oleh | Status | Catatan |
|---|---|---|---|---|
| A1 | Akun pengelola aplikasi (nama pengguna dan kata sandi awal) | Naufal | | Disampaikan langsung, bukan lewat chat |
| A2 | Akses panel hosting (alamat, nama pengguna, kata sandi) | Naufal | | Ganti kata sandi setelah serah terima |
| A3 | Akses pengelola domain dan pengaturan DNS | Naufal | | |
| A4 | Sertifikat HTTPS dan tanggal berlaku | Nabila | | Catat tanggal perpanjangan |
| A5 | Akses basis data (nama basis data, pengguna, kata sandi) | Nabila | | Simpan di pengelola kata sandi dinas |
| A6 | Surel pemulihan akun | Naufal | | Gunakan surel dinas, bukan pribadi |
| A7 | Akses repositori GitHub (pemilik atau admin) | Naufal | | Tambahkan akun dinas sebagai pemilik |
| A8 | Daftar akun Kasubbag dan Staff yang aktif | Naufal | | Sesuai data kepegawaian terbaru |

## B. Kode dan infrastruktur

| No | Butir | Diserahkan oleh | Status | Catatan |
|---|---|---|---|---|
| B1 | Repositori kode lengkap dengan riwayat perubahan | Naufal | | Tautan dan cara mengunduh |
| B2 | Tag rilis `v1.0.0-M5` dan catatan rilis | Naufal | | |
| B3 | Petunjuk pemasangan ulang (prasyarat, perintah, konfigurasi) | Nabila | | Diuji ulang maksimal 30 menit |
| B4 | Berkas contoh `.env.example` tanpa rahasia | Nabila | | |
| B5 | Daftar paket pihak ketiga dan lisensinya | Nabila | | Semua open source dan gratis |
| B6 | Prosedur pencadangan dan pemulihan | Nabila | | Sudah diuji satu kali |
| B7 | Prosedur pembaruan aplikasi | Nabila | | Termasuk cara membuat tag rilis |

## C. Data

| No | Butir | Diserahkan oleh | Status | Catatan |
|---|---|---|---|---|
| C1 | Data awal kode klasifikasi arsip (Pergub 16/2023) | Nabila | | Jumlah baris dicatat |
| C2 | Data contoh pelatihan dihapus dari lingkungan produksi | Naufal | | Periksa daftar surat |
| C3 | Ekspor seluruh data dalam Excel dan PDF | Naufal | | Bukti bahwa data tidak terkunci pada penyedia |
| C4 | Cadangan terakhir sebelum serah terima | Nabila | | Simpan di dua tempat |
| C5 | Catatan jumlah surat, akun, dan lampiran per tanggal serah terima | Naufal | | Menjadi titik awal pemantauan |

## D. Dokumen

| No | Butir | Status | Catatan |
|---|---|---|---|
| D1 | Project Charter | | Sudah ada, perbarui tanggal |
| D2 | Spesifikasi kebutuhan (SKPL) | | |
| D3 | Lampiran teknis | | |
| D4 | WBS dan jadwal | | |
| D5 | Rencana alur kerja (opsional untuk dinas) | | |
| D6 | Matriks uji terisi dan berita acara UAT | | Lampirkan bukti |
| D7 | Buku panduan pengguna | | Versi cetak dan digital |
| D8 | Materi dan daftar hadir pelatihan | | |
| D9 | Daftar akun dan hak akses | | Tanpa kata sandi |
| D10 | Berita acara serah terima (dokumen ini) | | Ditandatangani kedua pihak |

## E. Dukungan setelah serah terima

| No | Butir | Status | Catatan |
|---|---|---|---|
| E1 | Kontak pengembang untuk gangguan | | Nama, nomor, surel |
| E2 | Masa pendampingan dan batas waktunya | | Sesuai kesepakatan |
| E3 | Cara melaporkan gangguan (kanal dan format) | | Sertakan tangkapan layar dan waktu |
| E4 | Jadwal peninjauan pertama setelah 30 hari | | |

## Berita acara serah terima

Pada hari ini, ______ tanggal ______ bulan ______ tahun ______, bertempat di
__________________________, telah dilaksanakan serah terima hasil pekerjaan
**SISIPIN DISHUB** sebagai berikut.

**Pihak yang menyerahkan:**

| Nama | Peran |
|---|---|
| Muhammad Naufal Fikri Akmal | Project Manager & QA |
| Nabila Ramadhani Mujahidin | Backend |
| Arta Eka Yuly Rajagukguk | Frontend |

**Pihak yang menerima:**

| Nama | Jabatan |
|---|---|
| | Kepala Subbagian Umum dan Kepegawaian |
| | Pengelola teknis |

**Yang diserahkan:**

1. Aplikasi SISIPIN DISHUB versi `v1.0.0-M5` pada alamat ______________________
2. Kode sumber dan riwayat perubahan
3. Data awal kode klasifikasi arsip
4. Akun dan akses sebagaimana bagian A
5. Dokumen sebagaimana bagian D
6. Pendampingan sebagaimana bagian E

**Catatan:**

1.
2.

Serah terima dinyatakan selesai dan berlaku sejak tanggal di atas.

| Menyerahkan | Menerima |
|---|---|
| ______________________ | ______________________ |
| Nama: | Nama: |
| Jabatan: | Jabatan: |
| Tanggal: | Tanggal: |

## Setelah berita acara

- [ ] Ganti seluruh kata sandi yang pernah dipakai selama pengembangan.
- [ ] Hapus akun uji dan data contoh.
- [ ] Pastikan akses pengembang dapat dicabut bila tidak lagi diperlukan.
- [ ] Simpan salinan berita acara pada arsip dinas dan repositori dokumen internal.
- [ ] Catat tanggal peninjauan 30 hari pada kalender.
