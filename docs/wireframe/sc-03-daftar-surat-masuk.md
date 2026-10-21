# SC-03: Daftar Surat Masuk

**Kode Layar:** SC-03  
**Nama Layar:** Daftar Surat Masuk  
**Rute:** `/surat-masuk` (`surat-masuk.index`)  
**Akses:** Terautentikasi  
**Peran:** Kasubbag, Staff Subbagian, Pengelola Sistem

---

## Tujuan Layar

Menampilkan daftar semua surat masuk dengan kemampuan pencarian, penyaringan, pengurutan, dan paginasi. Pengguna dapat melihat ringkasan surat, mengakses detail, membuat surat baru, dan melakukan aksi massal (hanya Pengelola Sistem).

---

## Elemen Utama

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Header Halaman** | Judul + tombol tambah | H1: "Surat Masuk", Tombol "Catat Surat Masuk" (variasi utama, ikon `plus`) |
| **Bilah Pencarian & Saring** | Input cari + dropdown saring | - Input Cari: placeholder "Cari nomor agenda, perihal, asal surat..."<br>- Saring: Tahun (dropdown), Klasifikasi (dropdown), Status (dropdown: Semua, Belum Didisposisikan, Sudah Didisposisikan, Arsip)<br>- Tombol "Saring" (sekunder) & "Reset" (teks) |
| **Tabel Surat Masuk** | Tabel responsif | Kolom (kiri→kanan):<br>1. Checkbox (untuk aksi massal)<br>2. Nomor Agenda (tautan ke detail)<br>3. Tanggal Terima<br>4. Asal Surat<br>5. Perihal (potong 60 karakter, tooltip penuh)<br>6. Kode Klasifikasi (badge warna)<br>7. Status Disposisi (badge: `Belum` kuning, `Sudah` hijau, `Arsip` abu)<br>8. Lampiran (ikon kertasclip, tooltip jumlah)<br>9. Aksi (ikon: Lihat, Edit, Hapus — tergantung hak akses) |
| **Paginasi** | Navigasi halaman | "Menampilkan 1–15 dari 127", Previous/Next, angka halaman, opsi per halaman (10, 15, 25, 50) |
| **Aksi Massal (Footer Tabel)** | Terlihat saat ≥1 checkbox dicentang | "Hapus terpilih" (hanya Pengelola Sistem), "Ekspor PDF terpilih" |
| **Notifikasi Toast** | Feedback aksi | "Surat berhasil dihapus", "Data diekspor", dll. |

---

## Keadaan Kosong

- **Belum ada surat masuk:** Tabel diganti ilustrasi SVG + teks "Belum ada surat masuk. Klik 'Catat Surat Masuk' untuk menambahkan." Tombol CTA di tengah.
- **Hasil pencarian/saring kosong:** "Tidak ada surat sesuai kriteria. Coba ubah kata kunci atau saringan."

---

## Keadaan Galat

| Kondisi | Tampilan | Pesan |
|---------|----------|-------|
| Gagal memuat daftar | Skeleton loader → baris galat | "Gagal memuat daftar surat. Coba muat ulang." |
| Pencarian timeout | Toast peringatan | "Pencarian terlalu lama. Coba kata kunci lebih spesifik." |
| Hapus gagal | Toast galat | "Surat tidak dapat dihapus. Mungkin sudah memiliki disposisi." |
| Ekspor gagal | Toast galat | "Gagal mengekspor PDF. Coba lagi nanti." |
| Tidak berhak akses | Halaman 403 | "Anda tidak memiliki izin melihat daftar surat masuk." |

---

## Catatan Responsif

### 360 px (Mobile)
- **Header:** Tombol "Catat Surat Masuk" lebar penuh, di bawah judul (flex column, gap 12 px).
- **Bilah Pencarian & Saring:**
  - Input Cari lebar penuh.
  - Dropdown Tahun, Klasifikasi, Status: *bottom sheet* / modal saat diklik (bukan dropdown native).
  - Tombol "Saring" & "Reset" sejajar bawah (2 kolom).
- **Tabel:** Mode *card list* (bukan `<table>`). Setiap baris = kartu vertikal:
  - Baris 1: Nomor Agenda (tautan) + Badge Status
  - Baris 2: Tanggal Terima + Ikon Lampiran
  - Baris 3: Asal Surat
  - Baris 4: Perihal (2 baris, clamp)
  - Baris 5: Kode Klasifikasi (badge)
  - Baris 6: Aksi (ikon horizontal, area klik 44 px)
- **Paginasi:** Hanya Previous / Next + angka halaman aktif (simpel).
- **Aksi Massal:** Bottom bar fixed (position: fixed bottom), sembunyi saat scroll ke bawah.

### 1366 px (Desktop)
- **Header:** Judul kiri, tombol kanan (flex between).
- **Bilah Pencarian & Saring:** Sejajar horizontal (flex wrap, gap 12 px). Input Cari lebar fleksibel (min 280 px). Dropdown native.
- **Tabel:** `<table>` penuh dengan sticky header. Kolom Aksi lebar 120 px (3 ikon). Hover baris highlight.
- **Paginasi:** Lengkap dengan angka halaman, opsi per halaman.
- **Aksi Massal:** Baris sticky di atas paginasi saat scroll.

---

## Aksesibilitas

- **Heading:** H1 (Surat Masuk) → H2 (Daftar Surat Masuk).
- **Tabel:** `<thead>` dengan `<th scope="col">`, `<tbody>`, `<td>` data. `aria-sort` pada header yang bisa diurutkan.
- **Pencarian:** `<label for="cari">Cari surat masuk</label>` + `aria-describedby` untuk petunjuk.
- **Dropdown saring:** `aria-haspopup="listbox"`, `aria-expanded`, keyboard navigable (ArrowDown/Up, Enter, Escape).
- **Checkbox:** `aria-label="Pilih surat nomor agenda XXX"`.
- **Bada status:** Badge menggunakan `aria-label` (contoh: `aria-label="Status: Belum Didisposisikan"`).
- **Focus order:** Cari → Tahun → Klasifikasi → Status → Saring → Reset → Tabel (baris pertama) → Paginasi.

---

## Tautan Wireframe

- **Excalidraw:** [https://excalidraw.com/#json=SC03_ID](https://excalidraw.com/#json=SC03_ID)
- **Draw.io:** [https://app.diagrams.net/#GSC03_ID](https://app.diagrams.net/#GSC03_ID)
- **Figma:** [https://figma.com/file/SC03_ID](https://figma.com/file/SC03_ID)

---

## Catatan Implementasi

- **Server-side processing:** Pencarian, saring, urut, paginasi semua di server (Laravel Scout / query builder).
- **Debounce pencarian:** 300 ms client-side sebelum request.
- **URL state:** Parameter query (`?cari=...&tahun=2026&klasifikasi=1&status=belum&page=2&per_page=15`) agar bisa dibagikan/bookmark.
- **Hak akses kolom Aksi:**
  - Staff: Lihat, Edit (hanya milik sendiri & belum didisposisikan)
  - Kasubbag: Lihat, Disposisi
  - Pengelola Sistem: Lihat, Edit, Hapus (semua)
- **Ekspor PDF:** Gunakan endpoint `/surat-masuk/ekspor-pdf?ids[]=1&ids[]=2` (POST).
- **Klasifikasi badge:** Warna dari field `warna` pada tabel `klasifikasi` (fallback `#1B4F72`).