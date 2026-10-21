# Glosarium Istilah

**Pemilik:** Naufal (PM & QA)
**Dipakai oleh:** Backend (nama field dan pesan), Frontend (teks layar), dokumen mitra
**Tujuan:** satu istilah untuk satu hal, agar layar, dokumen, dan percakapan dinas tidak berbeda.

## Aturan pemakaian

1. Teks pada layar memakai istilah pada tabel ini, bukan padanan Inggris.
2. Nama field di basis data tetap Bahasa Inggris (konvensi Laravel), tetapi label yang dilihat pengguna memakai istilah ini.
3. Pesan kesalahan ditulis dalam Bahasa Indonesia yang menjelaskan tindakan berikutnya.
4. Istilah baru harus ditambahkan ke dokumen ini lewat PR sebelum dipakai di layar.

## Istilah dinas

| Istilah | Arti | Jangan dipakai |
|---|---|---|
| Agenda surat | Nomor urut pencatatan surat dalam satu tahun | Nomor indeks |
| Arsip | Dokumen yang disimpan karena masih diperlukan | File, dokumen arsip |
| Arsip aktif | Arsip yang masih sering dipakai | Arsip hidup |
| Arsip inaktif | Arsip yang jarang dipakai tetapi belum dimusnahkan | Arsip mati |
| Bidang | Unit kerja di lingkungan dinas | Departemen, divisi |
| Disposisi | Penerusan surat dari Kasubbag kepada Staff beserta instruksi | Forward, teruskan surat |
| Instruksi | Perintah atau catatan pada disposisi | Komentar, note |
| Kasubbag | Kepala Subbagian Umum dan Kepegawaian | Atasan, admin |
| Kode klasifikasi | Kode pengelompokan arsip sesuai Pergub Lampung 16/2023 | Kategori, tagging |
| KP4 | Kartu pegawai yang datanya diisi dari sistem | Kartu pegawai (saja) |
| Lampiran | Berkas yang disertakan pada surat | Attachment, file |
| Naskah dinas | Dokumen resmi dinas (surat, nota, undangan, dan sejenisnya) | Dokumen resmi |
| Pengelola sistem | Akun teknis yang mengelola akun dan konfigurasi | Super admin, root |
| Retensi | Masa simpan arsip sebelum ditinjau | Kadaluarsa |
| Staff Subbagian | Pengguna yang mencatat dan meneruskan surat | User, pegawai |
| Surat masuk | Surat yang diterima dinas dan dicatat | Inbox, surat datang |
| Surat keluar | Surat yang dikirim dinas dan dicatat | Outbox, surat pergi |
| Tata naskah | Kumpulan contoh dan template naskah dinas | Template (saja) |
| Tindak lanjut | Tindakan atas disposisi yang sudah diterima | Follow up |
| Unit kearsipan | Bagian yang mengelola arsip dinas | Gudang arsip |

## Istilah jenis surat keluar

| Istilah | Kepanjangan | Dipakai untuk |
|---|---|---|
| SPT | Surat Perintah Tugas | Penugasan perjalanan atau pekerjaan |
| SPPD | Surat Perintah Perjalanan Dinas | Perjalanan dinas beserta biayanya |
| SK | Surat Keputusan | Keputusan resmi kepala dinas |
| Undangan | Surat undangan | Kegiatan, rapat, atau acara |

## Padanan istilah teknis pada layar

| Di kode program | Di layar |
|---|---|
| login / logout | masuk / keluar |
| dashboard | dasbor |
| upload | unggah |
| download | unduh |
| file | berkas |
| user | pengguna |
| password | kata sandi |
| search | cari |
| filter | saring |
| report | laporan |
| activity log | riwayat aktivitas |
| role | peran |

## Istilah yang tidak boleh muncul di layar

Kata teknis berikut hanya untuk pengembang: `migration`, `seeder`, `controller`, `endpoint`,
`payload`, `token`, `exception`, `stack trace`, `query`, `repository`, `middleware`,
`env`, `database`. Bila terjadi kesalahan, tampilkan kalimat seperti
"Data belum dapat disimpan. Periksa kembali isian, lalu coba lagi."

## Rujukan

- Peraturan Gubernur Lampung Nomor 16 Tahun 2023 tentang Klasifikasi Arsip
- `02-Spesifikasi-Kebutuhan-SISIPIN-DISHUB.md` (R-01…R-20)
- [`docs/rencana-git-flow.md`](rencana-git-flow.md) bagian kontrak kerja antar peran
