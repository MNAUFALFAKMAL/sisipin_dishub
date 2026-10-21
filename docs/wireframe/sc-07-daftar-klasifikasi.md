# SC-07: Daftar Kode Klasifikasi

**Kode Layar:** SC-07  
**Nama Layar:** Daftar Kode Klasifikasi  
**Rute:** `/klasifikasi` (`klasifikasi.index`)  
**Akses:** Terautentikasi (kelola), Publik (lihat saja di SC-10)  
**Peran:** Pengelola Sistem (CRUD), Kasubbag & Staff (Baca)

---

## Tujuan Layar

Mengelola master data kode klasifikasi arsip sesuai Pergub Lampung 16/2023. Pengelola Sistem dapat menambah, mengedit, menghapus, dan mengurutkan kode klasifikasi. Kasubbag & Staff hanya melihat untuk referensi saat mencatat surat.

---

## Elemen Utama

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Header Halaman** | Judul + tombol tambah | H1: "Kode Klasifikasi". Tombol "Tambah Klasifikasi" (utama, ikon `plus`) — hanya Pengelola Sistem. |
| **Bilah Pencarian & Saring** | Input cari + saring level | - Input Cari: placeholder "Cari kode, nama, atau deskripsi..."<br>- Saring Level: Semua, Level 1 (Utama), Level 2 (Sub), Level 3 (Detail)<br>- Saring Status: Aktif / Tidak Aktif |
| **Tabel Klasifikasi** | Tabel hierarkis (tree table) | Kolom:<br>1. **Kode** (tautan ke edit, monospace, indent visual per level)<br>2. **Nama Klasifikasi**<br>3. **Level** (badge: `Utama`, `Sub`, `Detail`)<br>4. **Retensi** (tahun, format: "Aktif: X thn, Inaktif: Y thn, Musnah")<br>5. **Warna** (kotak warna 24×24 px + kode hex)<br>6. **Status** (toggle switch Aktif/Tidak Aktif — hanya Pengelola)<br>7. **Urutan** (input number kecil, drag-handle ikon — hanya Pengelola)<br>8. **Aksi** (Lihat, Sunting, Hapus — hanya Pengelola) |
| **Paginasi** | Standar | 15 baris per halaman (default). |
| **Modal Tambah / Sunting** | Formulir di modal | Field:<br>- Kode Induk (select, optional, hanya level > 1)<br>- Kode (input, wajib, format: `xx.xx.xx`, unik)<br>- Nama Klasifikasi (input, wajib, maks 255 karakter)<br>- Deskripsi (textarea, opsional, maks 500 karakter)<br>- Retensi Aktif (number, wajib, tahun)<br>- Retensi Inaktif (number, wajib, tahun)<br>- Warna (color picker + input hex, default `#1B4F72`)<br>- Status (checkbox Aktif, default centang)<br>- Urutan (number, default auto) |
| **Konfirmasi Hapus** | Modal konfirmasi | "Hapus klasifikasi '{nama}'? Sub-klasifikasi (jika ada) akan ikut terhapus. Tindakan ini tidak bisa dibatalkan." |

---

## Keadaan Kosong

- **Belum ada klasifikasi:** Tabel diganti ilustrasi + "Belum ada kode klasifikasi. Klik 'Tambah Klasifikasi' untuk memulai." (hanya Pengelola). Untuk Kasubbag/Staff: "Belum ada kode klasifikasi. Hubungi Pengelola Sistem."

---

## Keadaan Galat

| Kondisi | Tampilan | Pesan |
|---------|----------|-------|
| Kode duplikat | Validasi inline merah pada field Kode | "Kode klasifikasi sudah digunakan. Gunakan kode lain." |
| Format kode tidak valid | Validasi inline | "Format kode tidak valid. Gunakan format xx.xx.xx (contoh: 01.01.01)." |
| Hapus gagal (masih dipakai surat) | Toast galat | "Klasifikasi tidak dapat dihapus karena masih digunakan pada surat. Nonaktifkan sebagai gantinya." |
| Retensi tidak logis | Validasi inline | "Retensi inaktif harus lebih besar dari retensi aktif." |
| Warna kontras rendah | Peringatan (bukan galat) di color picker | "Peringatan: Warna ini mungkin sulit dibaca pada latar putih. Pertimbangkan warna lebih gelap." |

---

## Catatan Responsif

### 360 px (Mobile)
- **Header:** Tombol "Tambah Klasifikasi" lebar penuh di bawah judul.
- **Tabel:** Mode *card list* (bukan table). Setiap kartu:
  - Baris 1: Kode (monospace, indent via padding-left) + Badge Level + Toggle Status
  - Baris 2: Nama Klasifikasi (tebal)
  - Baris 3: Retensi (ikon jam + teks) + Kotak Warna
  - Baris 4: Aksi (ikon horizontal)
- **Indent Level:** Padding kiri: Level 1 = 0, Level 2 = 16 px, Level 3 = 32 px.
- **Modal Formulir:** Full-screen bottom sheet.
- **Drag Urutan:** Tidak tersedia di mobile (pakai input number urutan).

### 1366 px (Desktop)
- **Tabel:** `<table>` tree-table dengan baris bisa expand/collapse (ikon chevron pada Level 1 & 2).
- **Indent:** Visual via padding-left pada sel Kode (CSS `padding-left: calc(var(--level) * 24px)`).
- **Drag & Drop Urutan:** `SortableJS` / Alpine.js drag handle (ikon `grip-vertical`), hanya Pengelola. Auto-save via AJAX `PATCH /klasifikasi/{id}/urut`.
- **Toggle Status:** Switch component (aksesibel, `role="switch"`, `aria-checked`).
- **Color Picker:** Native `<input type="color">` + input hex sinkron dua arah.
- **Modal:** Tengah layar, lebar 560 px, max-height 90vh, scroll internal.

---

## Aksesibilitas

- **Tree Table:** `role="treegrid"`, `aria-rowindex`, `aria-level`, `aria-expanded` pada baris parent. `aria-controls` menunjuk ke baris anak.
- **Expand/Collapse:** Tombol `aria-expanded`, `aria-label="Tampilkan sub-klasifikasi 01.01"`.
- **Toggle Status:** `<input type="checkbox" role="switch" aria-checked="true">` + label visual.
- **Color Picker:** `aria-label="Warna klasifikasi, nilai hex #{hex}"`. Input hex: `pattern="^#[0-9A-Fa-f]{6}$"`.
- **Drag Handle:** `tabindex="0"`, `role="button"`, `aria-label="Seret untuk mengubah urutan {kode}"`. Keyboard: `Space` ambil, `ArrowUp/Down` pindah, `Space` lepas.
- **Modal:** Focus trap, `Escape` tutup, `aria-labelledby` ke judul modal.
- **Live Region:** Toast `aria-live="polite"`.

---

## Tautan Wireframe

- **Excalidraw:** [https://excalidraw.com/#json=SC07_ID](https://excalidraw.com/#json=SC07_ID)
- **Draw.io:** [https://app.diagrams.net/#GSC07_ID](https://app.diagrams.net/#GSC07_ID)
- **Figma:** [https://figma.com/file/SC07_ID](https://figma.com/file/SC07_ID)

---

## Catatan Implementasi

- **Model:** `Klasifikasi` dengan `parent_id` (self-referencing), `level` (computed: 1, 2, 3), `urutan`, `warna`, `aktif`.
- **Validasi Kode:**
  ```php
  'kode' => 'required|regex:/^\d{2}(\.\d{2}){0,2}$/|unique:klasifikasi,kode,' . $id,
  'parent_id' => 'nullable|exists:klasifikasi,id',
  'retensi_aktif' => 'required|integer|min:1|max:100',
  'retensi_inaktif' => 'required|integer|gt:retensi_aktif|max:100',
  'warna' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
  ```
- **Hierarki:** Level 1 (kode `xx`), Level 2 (`xx.xx`), Level 3 (`xx.xx.xx`). Validasi: parent harus level < child level.
- **Soft Delete:** Pakai `SoftDeletes` tapi hapus fisik hanya jika tidak dipakai surat. Jika dipakai → hanya `aktif = false`.
- **Seeding:** Data awal dari Pergub Lampung 16/2023 dimuat via seeder `KlasifikasiSeeder`.
- **API Publik (untuk SC-10):** `GET /api/publik/klasifikasi?aktif=1` → JSON tree untuk halaman publik.