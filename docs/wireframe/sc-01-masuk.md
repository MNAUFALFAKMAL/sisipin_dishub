# SC-01: Masuk (Halaman Autentikasi)

**Kode Layar:** SC-01  
**Nama Layar:** Masuk  
**Rute:** `/masuk` (`login`)  
**Akses:** Publik (tanpa autentikasi)  
**Peran:** Semua peran (Kasubbag, Staff Subbagian, Pengelola Sistem)

---

## Tujuan Layar

Menyediakan antarmuka bagi pengguna untuk memasukkan kredensial (surel dan kata sandi) guna mengakses sistem SISIPIN DISHUB. Halaman ini juga menampilkan identitas aplikasi dan tautan bantuan.

---

## Elemen Utama

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Logo & Judul Aplikasi** | Identitas visual SISIPIN DISHUB | Logo Dinas Perhubungan Provinsi Lampung + teks "SISIPIN DISHUB" di tengah |
| **Kartu Masuk** | Kontainer putih berbayang, lebar maksimal 400 px | Radius 12 px, bayangan `0 4px 20px rgba(0,0,0,0.08)` |
| **Label Surel** | `<label for="surel">Surel</label>` | Tebal 500, ukuran 14 px, warna `#212121` |
| **Input Surel** | `<input type="email" id="surel" name="surel" autocomplete="email">` | Placeholder: `nama@dishub.lampungprov.go.id`, validasi format surel |
| **Label Kata Sandi** | `<label for="kata_sandi">Kata Sandi</label>` | Tebal 500, ukuran 14 px |
| **Input Kata Sandi** | `<input type="password" id="kata_sandi" name="password" autocomplete="current-password">` | Tombol tampilkan/sembunyikan (ikon mata), placeholder: `Masukkan kata sandi` |
| **Checkbox Ingat Saya** | `<input type="checkbox" id="ingat_saya" name="remember">` | Label: "Ingat saya pada perangkat ini" |
| **Tautan Lupa Kata Sandi** | `<a href="/kata-sandi/lupa">Lupa kata sandi?</a>` | Rata kanan, ukuran 13 px, warna `#1B4F72` |
| **Tombol Masuk** | `<button type="submit">Masuk</button>` | Variasi utama, lebar penuh, tinggi 44 px, ikon panah kanan opsional |
| **Pesan Galat Server** | Area notifikasi merah di atas formulir | Muncul bila kredensial salah / akun tidak aktif / terlalu banyak percobaan |
| **Footer** | Informasi versi & hak cipta | "SISIPIN DISHUB v0.1.0 — Dinas Perhubungan Provinsi Lampung 2026" |

---

## Keadaan Kosong

- Input surel dan kata sandi kosong saat halaman dimuat.
- Tombol "Masuk" dinonaktifkan (`disabled`) hingga kedua input terisi minimal 1 karakter.
- Placeholder terlihat sebagai petunjuk format.

---

## Keadaan Galat

| Kondisi | Tampilan | Pesan (Bahasa Indonesia) |
|---------|----------|--------------------------|
| Surel kosong | Border merah `#C62828`, ikon peringatan, teks bantuan merah | "Surel wajib diisi." |
| Format surel tidak valid | Border merah, teks bantuan merah | "Format surel tidak valid. Gunakan surel dinas." |
| Kata sandi kosong | Border merah, teks bantuan merah | "Kata sandi wajib diisi." |
| Kredensial salah (server) | Notifikasi galat di atas kartu, `aria-live="assertive"` | "Surel atau kata sandi tidak cocok. Periksa kembali, lalu coba lagi." |
| Akun tidak aktif | Notifikasi galat | "Akun Anda dinonaktifkan. Hubungi Pengelola Sistem." |
| Terlalu banyak percobaan | Notifikasi galat, tombol Masuk dinonaktifkan 15 menit | "Terlalu banyak percobaan gagal. Coba lagi dalam 15 menit." |
| Koneksi gagal | Toast galat | "Tidak dapat terhubung ke server. Periksa jaringan, lalu coba lagi." |

---

## Catatan Responsif

### 360 px (Mobile)
- Kartu masuk lebar penuh (margin 16 px kiri-kanan).
- Padding kartu: 24 px.
- Judul aplikasi di atas kartu, ukuran 20 px.
- Input tinggi 44 px (area sentuh nyaman).
- Tautan "Lupa kata sandi?" di baris terpisah di bawah checkbox.
- Footer tetap di bawah layar (jika konten pendek) atau alami.

### 1366 px (Desktop)
- Kartu masuk lebar tetap 400 px, tengah horizontal & vertikal (flex center).
- Padding kartu: 32 px.
- Logo & judul lebih besar (28 px).
- Checkbox & tautan lupa kata sandi sejajar horizontal (flex between).
- Latar belakang halaman: gradasi halus `#E8F1F8` ke `#FFFFFF` atau ilustrasi SVG ringan.
- Animasi masuk kartu: `fade-in-up` 300 ms ease-out.

---

## Aksesibilitas

- Urutan `Tab`: Surel → Kata Sandi → Checkbox → Tautan Lupa Kata Sandi → Tombol Masuk.
- `autocomplete` atribut benar untuk manajer kata sandi peramban.
- `aria-describedby` pada input menghubungkan ke elemen pesan galat.
- Fokus terlihat: ring 3 px `#1B4F72`.
- `lang="id"` pada `<html>`.
- Kontras teks putih pada tombol biru: 5.2:1 (lulus AA).

---

## Tautan Wireframe

- **Excalidraw:** [https://excalidraw.com/#json=SC01_ID](https://excalidraw.com/#json=SC01_ID)
- **Draw.io:** [https://app.diagrams.net/#GSC01_ID](https://app.diagrams.net/#GSC01_ID)
- **Figma:** [https://figma.com/file/SC01_ID](https://figma.com/file/SC01_ID)

---

## Catatan Implementasi

- Gunakan Laravel Breeze / Fortify sebagai dasar, kustomisasi tampilan Blade.
- Rute: `GET /masuk` (tampilkan), `POST /masuk` (proses), `POST /keluar` (logout).
- Validasi server-side wajib; client-side hanya UX tambahan.
- CSRF token pada formulir.
- Rate limiting: 5 percobaan per menit per IP.
- Setelah masuk, arahkan ke rute `dasbor` (atau `intended` URL).