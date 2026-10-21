# SC-09: Profil & Kata Sandi

**Kode Layar:** SC-09  
**Nama Layar:** Profil & Kata Sandi  
**Rute:** `/profil` (`profil.show`), `/profil/kata-sandi` (`profil.password`)  
**Akses:** Terautentikasi  
**Peran:** Semua peran (Kasubbag, Staff Subbagian, Pengelola Sistem)

---

## Tujuan Layar

Mengelola informasi profil pengguna (data dari KP4) dan mengubah kata sandi. Halaman ini juga menampilkan ringkasan aktivitas pengguna dan preferensi tampilan.

---

## Elemen Utama

### A. Tab Navigasi Profil

| Tab | Ikon | Deskripsi |
|-----|------|-----------|
| **Profil Saya** | `user` | Data KP4, foto, kontak, preferensi |
| **Kata Sandi** | `lock` | Ubah kata sandi |
| **Aktivitas Saya** | `history` | Riwayat aku (surat dicatat, disposisi, tindak lanjut) |
| **Preferensi** | `settings` | Tema, notifikasi, bahasa, density |

---

### B. Tab 1: Profil Saya (`/profil`)

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Header Kartu Profil** | Avatar + info utama | - Avatar: Foto (fallback inisial nama, latar warna acak based on hash nama)<br>- Nama Lengkap (tebal, 20 px)<br>- NIP / NRP<br>- Peran (badge: Kasubbag / Staff Subbagian / Pengelola Sistem)<br>- Bidang / Unit Kerja<br>- Status: Aktif (badge hijau) |
| **Informasi KP4 (Kartu Pegawai)** | Grid 2 kolom, read-only | **Kiri:** NIP, Nama, Tempat/Tgl Lahir, Jenis Kelamin, Agama, Status Nikah<br>**Kanan:** Jabatan, Golongan/Pangkat, Bidang, Unit Kerja, No. Telepon, Surel Dinas |
| **Foto Profil** | Area unggah (hanya Pengelola & sendiri) | - Preview lingkaran 120 px<br>- Tombol "Ganti Foto" (sekunder) → dropzone (max 2 MB, JPG/PNG, aspect 1:1, crop preview)<br>- Tombol "Hapus Foto" (teks bahaya) → kembali ke inisial |
| **Kontak Darurat** | Formulir edit (opsional) | Nama, Hubungan, Telepon. Tombol "Simpan" (utama). |
| **Preferensi Tampilan** | Toggle & select | - Tema: Terang / Gelap / Sistem (default: Sistem)<br>- Kepadatan: Nyaman / Rapat (default: Nyaman — mempengaruhi padding tabel)<br>- Bahasa: Indonesia (default, tidak bisa ubah) |
| **Notifikasi** | Checkbox group | - Email saat mendapat disposisi<br>- Email saat disposisi selesai<br>- Browser notification (push) — minta izin `Notification.permission` |
| **Tombol Simpan Perubahan** | Footer kartu | "Simpan Perubahan" (utama), "Batal" (teks). |

---

### C. Tab 2: Kata Sandi (`/profil/kata-sandi`)

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Kartu Ubah Kata Sandi** | Formulir terpusat, lebar 480 px | **Field:**<br>1. Kata Sandi Saat Ini (password, wajib, `autocomplete="current-password"`)<br>2. Kata Sandi Baru (password, wajib, `autocomplete="new-password"`, indikator kekuatan)<br>3. Konfirmasi Kata Sandi Baru (password, wajib, `autocomplete="new-password"`) |
| **Indikator Kekuatan** | Visual bar + teks | 0-20%: Lemah (merah), 21-50%: Sedang (kuning), 51-80%: Kuat (hijau muda), 81-100%: Sangat Kuat (hijau). Kriteria: min 8 karakter, huruf besar, kecil, angka, simbol. |
| **Panduan Kata Sandi** | Daftar poin di bawah field | "Minimal 8 karakter", "Campur huruf besar & kecil", "Sertakan angka", "Sertakan simbol (!@#$%^&*)", "Jangan gunakan info pribadi (nama, tanggal lahir)". |
| **Tombol Aksi** | Rata kanan | "Batal" (teks), "Ubah Kata Sandi" (utama). |

---

### D. Tab 3: Aktivitas Saya (`/profil/aktivitas`)

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Filter Periode** | Dropdown + date range | "30 hari terakhir" (default), "3 bulan", "6 bulan", "Kustom". |
| **Tabel Aktivitas** | Tabel ringkas | Kolom: Tanggal & Waktu, Jenis (Surat Masuk, Surat Keluar, Disposisi, Tindak Lanjut), Objek (Nomor Agenda + Perihal, tautan), Peran Saya (Pencatat, Penerima Disposisi, Penindak Lanjut), Status. |
| **Paginasi** | 20 baris per halaman | |

---

### E. Tab 4: Preferensi (`/profil/preferensi`)

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Tema** | Radio card (3 opsi) | Kartu visual: Terang (☀️), Gelap (🌙), Sistem (💻). Pilihan tersimpan di `localStorage` + DB. |
| **Kepadatan** | Radio card | Nyaman (padding normal), Rapat (padding -25%). Preview mini tabel di samping. |
| **Notifikasi Browser** | Toggle + status | "Aktifkan notifikasi browser" → minta izin → status: "Diizinkan" / "Ditolak" / "Diblokir". |
| **Reset Preferensi** | Tombol bahaya kecil | "Kembalikan ke default" → konfirmasi modal. |

---

## Keadaan Kosong

- **Aktivitas kosong:** "Belum ada aktivitas tercatak pada periode ini."
- **Foto profil belum ada:** Avatar inisial (2 huruf pertama nama), latar warna deterministic.
- **Kontak darurat kosong:** "Belum ditambahkan. Klik 'Tambah Kontak' untuk mengisi."

---

## Keadaan Galat

| Kondisi | Tampilan | Pesan |
|---------|----------|-------|
| Kata sandi saat ini salah | Validasi inline merah | "Kata sandi saat ini tidak cocok." |
| Kata sandi baru lemah | Indikator merah + pesan | "Kata sandi terlalu lemah. Ikuti panduan di bawah." |
| Konfirmasi tidak cocok | Validasi inline merah | "Konfirmasi kata sandi tidak cocok." |
| Kata sandi baru sama dengan lama | Validasi inline | "Kata sandi baru tidak boleh sama dengan kata sandi saat ini." |
| Unggah foto > 2 MB | Toast galat | "Foto melebihi batas 2 MB. Kompres atau pilih foto lain." |
| Format foto tidak valid | Toast galat | "Format foto harus JPG atau PNG." |
| Gagal simpan profil | Toast galat | "Gagal menyimpan perubahan. Coba lagi." |
| Izin notifikasi ditolak | Info inline | "Izin notifikasi ditolak. Aktifkan di pengaturan browser untuk menerima notifikasi." |

---

## Catatan Responsif

### 360 px (Mobile)
- **Tab Navigasi:** Scroll horizontal (snap), indikator tab aktif.
- **Profil KP4:** Stack 1 kolom (label di atas nilai). Foto profil di atas (center).
- **Formulir Kata Sandi:** Lebar penuh, padding 16 px. Indikator kekuatan di bawah field.
- **Tabel Aktivitas:** Card list (sama pola SC-03).
- **Preferensi Tema/Kepadatan:** Radio card stack vertikal.

### 1366 px (Desktop)
- **Layout 2 Kolom:** Sidebar kiri (280 px) = Tab navigasi vertikal + info ringkas user. Kanan = Konten tab.
- **Profil KP4:** Grid 2 kolom rapi, label rata kanan, nilai rata kiri.
- **Foto Profil:** Preview 150 px, dropzone di samping.
- **Formulir Kata Sandi:** Kartu tengah lebar 480 px.
- **Tabel Aktivitas:** `<table>` penuh, hover highlight, sticky header.
- **Preferensi:** Grid 2 kolom (Tema | Kepadatan), Notifikasi full width.

---

## Aksesibilitas

- **Tab List:** `role="tablist"`, `aria-label="Menu profil"`. Tab: `role="tab"`, `aria-selected`, `aria-controls`.
- **Tab Panel:** `role="tabpanel"`, `aria-labelledby`, `hidden` pada non-aktif.
- **Formulir:** Label eksplisit, `autocomplete` benar, `aria-describedby` ke panduan/indikator.
- **Indikator Kekuatan:** `role="progressbar"`, `aria-valuenow`, `aria-valuemin="0"`, `aria-valuemax="100"`, `aria-label="Kekuatan kata sandi: {persen}%"`.
- **Color-only Status:** Tema/kepadatan radio card menggunakan border + teks + ikon, bukan hanya warna.
- **Avatar:** `alt="Foto profil {nama}"` atau `aria-label="Inisial {nama}"`.
- **Focus Trap:** Pada modal crop foto / konfirmasi reset.
- **Live Region:** Toast `aria-live="polite"`.

---

## Tautan Wireframe

- **Excalidraw:** [https://excalidraw.com/#json=SC09_ID](https://excalidraw.com/#json=SC09_ID)
- **Draw.io:** [https://app.diagrams.net/#GSC09_ID](https://app.diagrams.net/#GSC09_ID)
- **Figma:** [https://figma.com/file/SC09_ID](https://figma.com/file/SC09_ID)

---

## Catatan Implementasi

- **Model User:** Relasi `pegawai` (data KP4) via `pegawai_id`. Field profil tambahan: `tema`, `kepadatan`, `notif_email_disposisi`, `notif_email_selesai`, `notif_browser`, `foto_path`.
- **Controller:** `ProfilController` dengan `show()`, `update()`, `password()`, `updatePassword()`, `aktivitas()`, `preferensi()`, `updatePreferensi()`.
- **Validasi Kata Sandi:**
  ```php
  'current_password' => 'required|current_password',
  'password' => 'required|string|min:8|confirmed|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
  ```
- **Foto Profil:** Simpan ke `storage/app/public/profil/{id}.{ext}`, symlink `public/storage`. Resize/crop via `intervention/image` (max 400×400 px).
- **Aktivitas:** Query polimorfik ke `activity_log` (paket `spatie/laravel-activitylog`) atau tabel custom `riwayat_aktivitas`.
- **Tema:** Middleware `SetTheme` baca `user->tema` / `session` / `cookie` / `prefers-color-scheme` → set `data-theme` pada `<html>` + CSS custom properties.
- **Kepadatan:** CSS class `.density-comfortable` / `.density-compact` pada `<body>` atau container utama.