# SC-04: Formulir Surat Masuk + Unggah Lampiran

**Kode Layar:** SC-04  
**Nama Layar:** Formulir Surat Masuk (Tambah / Edit)  
**Rute:** `/surat-masuk/buat` (`surat-masuk.create`), `/surat-masuk/{id}/edit` (`surat-masuk.edit`)  
**Akses:** Terautentikasi  
**Peran:** Staff Subbagian, Pengelola Sistem (Kasubbag hanya baca)

---

## Tujuan Layar

Menyediakan formulir untuk mencatat surat masuk baru atau mengedit surat yang sudah ada, termasuk unggah lampiran (maksimal 5 berkas, 5 MB per berkas, tipe: PDF, JPG, PNG, DOC, DOCX).

---

## Elemen Utama

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Header Halaman** | Judul + tombol kembali | H1: "Catat Surat Masuk" / "Sunting Surat Masuk", Tombol "Kembali" (variasi teks, ikon `arrow-left`) |
| **Kartu Formulir Utama** | Kontainer putih, padding 24 px | Lebar maksimal 800 px, tengah horizontal |
| **Bagian Identitas Surat** | Fieldset grup | Legend: "Identitas Surat" |
| **Nomor Agenda** | Input teks (hanya baca saat edit) | Format otomatis: `SM/{tahun}/{nomor_urut}` (contoh: `SM/2026/001`). Saat buat: ditampilkan sebagai pratinjau, tidak diedit. |
| **Tanggal Surat** | Input date (`type="date"`) | Wajib. Maksimal hari ini. Label: "Tanggal Surat" |
| **Tanggal Terima** | Input date | Wajib. Default: hari ini. Tidak boleh sebelum Tanggal Surat. Label: "Tanggal Terima" |
| **Asal Surat** | Input teks | Wajib. Maks 255 karakter. Placeholder: "Nama instansi / pengirim" |
| **Nomor Surat** | Input teks | Wajib. Maks 100 karakter. Placeholder: "Nomor surat pengirim" |
| **Perihal** | Textarea | Wajib. Maks 500 karakter. Baris 3. Placeholder: "Ringkasan isi surat" |
| **Kode Klasifikasi** | Select (searchable) | Wajib. Ambil dari master klasifikasi. Tampilkan: "Kode - Nama" (contoh: "01.01 - Surat Keputusan"). Placeholder: "Pilih klasifikasi" |
| **Sifat Surat** | Radio group | Pilihan: Biasa, Penting, Sangat Rahasia, Rahasia. Default: Biasa. Legend: "Sifat Surat" |
| **Bagian Lampiran** | Fieldset grup | Legend: "Lampiran (Maksimal 5 berkas, 5 MB per berkas)" |
| **Zona Unggah** | Dropzone + daftar berkas | - Area tarik-lepas (border putus-putus, ikon awan naik)<br>- Tombol "Pilih Berkas" (sekunder)<br>- Daftar berkas terpilih: nama, ukuran, ikon hapus<br>- Indikator progress saat unggah |
| **Catatan Internal** | Textarea (opsional) | Placeholder: "Catatan internal untuk disposisi..." Maks 1000 karakter. |
| **Tombol Aksi** | Grup tombol kanan bawah | "Batal" (teks, kembali ke daftar), "Simpan Konsep" (sekunder), "Simpan & Kirim Disposisi" (utama — hanya Staff) / "Simpan" (utama — Pengelola Sistem) |

---

## Keadaan Kosong

- **Mode Tambah:** Semua input kosong kecuali Tanggal Terima (hari ini), Sifat Surat (Biasa), Nomor Agenda (pratinjau otomatis).
- **Zona Unggah:** Menampilkan "Taruh berkas di sini atau klik 'Pilih Berkas'". Tidak ada daftar berkas.

---

## Keadaan Galat

| Kondisi | Tampilan | Pesan |
|---------|----------|-------|
| Validasi client-side | Border merah pada field, pesan di bawah | "Tanggal Terima tidak boleh sebelum Tanggal Surat." |
| Validasi server-side | Toast galat + fokus ke field pertama bermasalah | "Data tidak valid. Periksa kolom yang ditandai." |
| Unggah melebihi 5 berkas | Toast peringatan, berkas ke-6 ditolak | "Maksimal 5 lampiran. Hapus berkas lain untuk menambah yang baru." |
| Berkas > 5 MB | Toast galat, berkas ditolak | "Berkas '{nama}' melebihi batas 5 MB." |
| Tipe berkas tidak diizinkan | Toast galat, berkas ditolak | "Tipe berkas '{ekstensi}' tidak diizinkan. Gunakan PDF, JPG, PNG, DOC, DOCX." |
| Total ukuran > 25 MB | Toast peringatan | "Total lampiran melebihi 25 MB. Kurangi ukuran berkas." |
| Koneksi gagal saat simpan | Toast galat, data tetap di formulir | "Gagal menyimpan. Periksa jaringan, lalu coba lagi." |
| Nomor agenda duplikat (race condition) | Toast galat | "Nomor agenda sudah terpakai. Coba simpan ulang." |

---

## Catatan Responsif

### 360 px (Mobile)
- **Layout:** Satu kolom, padding halaman 16 px.
- **Fieldset:** Legend sebagai H3 (ukuran 16 px, tebal 600).
- **Input & Textarea:** Lebar penuh, tinggi 44 px (input), min-height 120 px (textarea).
- **Tanggal:** Gunakan native date picker (mobile browser optimized).
- **Select Klasifikasi:** Native select (full screen picker di mobile) atau bottom sheet custom.
- **Radio Sifat Surat:** Vertikal stack, label lebar penuh (area klik besar).
- **Zona Unggah:** Tinggi 120 px, teks tengah. Daftar berkas: kartu horizontal (nama kiri, ukuran & hapus kanan).
- **Tombol Aksi:** Lebar penuh, vertikal stack (Simpan & Kirim di atas, Simpan Konsep di tengah, Batal di bawah). Gap 8 px.

### 1366 px (Desktop)
- **Layout:** Kartu tengah, lebar 800 px, padding 32 px.
- **Grid 2 kolom untuk identitas:**
  - Kolom kiri: Tanggal Surat, Asal Surat, Kode Klasifikasi
  - Kolom kanan: Tanggal Terima, Nomor Surat, Sifat Surat
- **Perihal & Catatan:** Lebar penuh (col-span-2).
- **Zona Unggah:** Lebar penuh, tinggi 160 px. Daftar berkas: tabel ringkas (Nama, Ukuran, Hapus).
- **Tombol Aksi:** Horizontal, rata kanan (Batal | Simpan Konsep | Simpan & Kirim). Gap 12 px.
- **Validasi inline:** Pesan galat muncul di bawah field saat blur, hilang saat input valid.

---

## Aksesibilitas

- **Fieldset + Legend:** Setiap grup (Identitas Surat, Lampiran) menggunakan `<fieldset><legend>`.
- **Label eksplisit:** Setiap input memiliki `<label for="id">` dengan teks jelas.
- **Required:** `aria-required="true"` + atribut `required` pada field wajib.
- **Deskripsi field:** `aria-describedby` menghubungkan ke teks bantuan / pesan galat.
- **Dropzone:** `role="button"`, `tabindex="0"`, keyboard accessible (Enter/Space buka file picker). `aria-label="Zona unggah lampiran, tarik lepas berkas di sini"`.
- **Progress unggah:** `role="progressbar"`, `aria-valuenow`, `aria-valuemin="0"`, `aria-valuemax="100"`, `aria-label="Progres unggah {nama_berkas}"`.
- **Radio group:** `role="radiogroup"`, `aria-labelledby="legend-sifat-surat"`.
- **Focus management:** Setelah validasi gagal, fokus ke field pertama bermasalah (`autofocus` pada field error pertama).
- **Live region:** Toast menggunakan `aria-live="polite"`.

---

## Tautan Wireframe

- **Excalidraw:** [https://excalidraw.com/#json=SC04_ID](https://excalidraw.com/#json=SC04_ID)
- **Draw.io:** [https://app.diagrams.net/#GSC04_ID](https://app.diagrams.net/#GSC04_ID)
- **Figma:** [https://figma.com/file/SC04_ID](https://figma.com/file/SC04_ID)

---

## Catatan Implementasi

- **Endpoint:** `POST /surat-masuk` (store), `PUT /surat-masuk/{id}` (update).
- **Validasi Laravel Request:**
  ```php
  'tanggal_surat' => 'required|date|before_or_equal:today',
  'tanggal_terima' => 'required|date|after_or_equal:tanggal_surat|before_or_equal:today',
  'asal_surat' => 'required|string|max:255',
  'nomor_surat' => 'required|string|max:100',
  'perihal' => 'required|string|max:500',
  'kode_klasifikasi' => 'required|exists:klasifikasi,kode',
  'sifat_surat' => 'required|in:biasa,penting,sangat_rahasia,rahasia',
  'lampiran.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
  'lampiran' => 'nullable|array|max:5',
  'catatan_internal' => 'nullable|string|max:1000',
  ```
- **Nomor agenda:** Dihasilkan otomatis di service/controller via transaksi DB (lock tabel tahunan).
- **Unggah:** Simpan ke `storage/app/surat-masuk/{tahun}/{nomor_agenda}/`. Catat di tabel `lampiran` (path, nama_asli, mime, ukuran).
- **Simpan & Kirim Disposisi:** Redirect ke `/surat-masuk/{id}/disposisi/buat` dengan data surat terisi.
- **Simpan Konsep:** Status `konsep` (bukan `diterima`), tidak muncul di daftar disposisi Kasubbag.
- **Alpine.js component:** `x-data="suratMasukForm()"` untuk dropzone, pratinjau nomor agenda, validasi tanggal.