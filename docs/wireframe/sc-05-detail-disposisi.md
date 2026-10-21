# SC-05: Detail Surat & Disposisi

**Kode Layar:** SC-05  
**Nama Layar:** Detail Surat Masuk & Disposisi  
**Rute:** `/surat-masuk/{id}` (`surat-masuk.show`), `/surat-masuk/{id}/disposisi` (`surat-masuk.disposisi`)  
**Akses:** Terautentikasi  
**Peran:** Kasubbag (disposisi), Staff Subbagian (lihat & tindak lanjut), Pengelola Sistem (kelola)

---

## Tujuan Layar

Menampilkan detail lengkap surat masuk beserta lampiran, riwayat disposisi, dan formulir untuk membuat disposisi baru (Kasubbag) atau mencatat tindak lanjut (Staff). Halaman ini adalah jantung alur kerja surat.

---

## Elemen Utama

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Header Halaman** | Judul kontekstual + aksi | H1: "Detail Surat Masuk" + Nomor Agenda (badge). Tombol: "Kembali" (teks), "Cetak" (sekunder, ikon printer), "Sunting" (sekunder — hanya Staff/Pengelola, jika status konsep/belum didisposisikan). |
| **Kartu Identitas Surat** | Panel ringkasan (sticky di mobile) | Grid 2 kolom: Nomor Agenda, Tanggal Surat, Tanggal Terima, Asal Surat, Nomor Surat, Perihal, Kode Klasifikasi (badge warna), Sifat Surat (badge), Status Disposisi (badge besar), Jumlah Lampiran. |
| **Daftar Lampiran** | Daftar berkas terunduh | Kolom: Ikon tipe, Nama Berkas, Ukuran, Tombol "Unduh" (utama kecil), "Hapus" (bahaya — hanya Pengelola/pemilik). |
| **Riwayat Disposisi** | Timeline vertikal | Urutan: Terbaru di atas. Setiap item:<br>- Avatar/Inisial Pengirim (Kasubbag)<br>- Nama Pengirim & Peran<br>- Tanggal & Waktu<br>- **Instruksi** (teks utama, dikutip)<br>- Penerima (Staff Subbagian) — badge<br>- Status: `Menunggu` (kuning), `Diproses` (biru), `Selesai` (hijau), `Ditolak` (merah)<br>- Tindak Lanjut (jika ada): isi, tanggal, oleh siapa |
| **Formulir Disposisi Baru** (Hanya Kasubbag) | Panel terpisah / modal | **Trigger:** Tombol "Buat Disposisi" (utama) di header kartu identitas / akhir timeline.<br>Field:<br>- Penerima (Select multiple Staff Subbagian, wajib, searchable)<br>- Instruksi (Textarea, wajib, maks 1000 karakter, placeholder: "Tulis instruksi disposisi...")<br>- Batas Waktu (Date, opsional, default +7 hari)<br>Tombol: "Batal" (teks), "Kirim Disposisi" (utama) |
| **Formulir Tindak Lanjut** (Hanya Staff Penerima) | Inline pada item disposisi `Menunggu`/`Diproses` | Trigger: Tombol "Tindak Lanjut" pada item disposisi miliknya.<br>Field:<br>- Isi Tindak Lanjut (Textarea, wajib, maks 1000 karakter)<br>- Lampiran Tindak Lanjut (Dropzone, opsional, max 3 berkas)<br>Tombol: "Batal", "Kirim Tindak Lanjut" (utama) |
| **Notifikasi Toast** | Feedback aksi | "Disposisi berhasil dikirim", "Tindak lanjut dicatat", "Surat dicetak", dll. |

---

## Keadaan Kosong

- **Belum ada disposisi:** Timeline menampilkan "Belum ada disposisi untuk surat ini." + Tombol "Buat Disposisi" (utama, besar) di tengah.
- **Belum ada tindak lanjut:** Item disposisi menampilkan "Menunggu tindak lanjut dari [Nama Staff]".
- **Tidak ada lampiran:** Area lampiran menampilkan "Tidak ada lampiran pada surat ini."

---

## Keadaan Galat

| Kondisi | Tampilan | Pesan |
|---------|----------|-------|
| Surat tidak ditemukan | Halaman 404 | "Surat masuk tidak ditemukan." |
| Gagal memuat detail | Skeleton → toast galat | "Gagal memuat detail surat. Coba muat ulang." |
| Disposisi gagal dikirim | Toast galat, formulir tetap terbuka | "Gagal mengirim disposisi. Periksa penerima dan instruksi, lalu coba lagi." |
| Penerima tidak dipilih | Validasi inline merah | "Pilih minimal satu penerima disposisi." |
| Instruksi kosong | Validasi inline merah | "Instruksi disposisi wajib diisi." |
| Tindak lanjut gagal | Toast galat | "Gagal mencatat tindak lanjut. Coba lagi." |
| Batas waktu lewat | Badge "Terlambat" merah pada item disposisi | Otomatis dihitung dari `batas_waktu` vs `now()`. |
| Cetak gagal | Toast galat | "Gagal membuat PDF. Coba lagi nanti." |

---

## Catatan Responsif

### 360 px (Mobile)
- **Header:** Judul 2 baris (H1 + Nomor Agenda badge). Tombol aksi: bottom bar fixed (Cetak | Sunting | Kembali) atau dropdown "Lainnya".
- **Kartu Identitas:** Stack vertikal, label di atas nilai (bukan 2 kolom). Sticky top saat scroll (position: sticky, top: 0, z-index 10).
- **Lampiran:** Daftar kartu horizontal (ikon kiri, nama tengah, unduh kanan).
- **Timeline Disposisi:** Kartu bertumpuk, padding 16 px. Instruksi teks penuh (tidak clamp). Avatar lingkaran 40 px.
- **Formulir Disposisi:** Modal full-screen (bottom sheet slide up).
  - Penerima: Chip selection (pilih → chip muncul di atas, tap chip untuk hapus).
  - Instruksi: Textarea full width, min-height 120 px.
  - Batas Waktu: Native date picker.
- **Formulir Tindak Lanjut:** Bottom sheet / inline expand di bawah item disposisi.

### 1366 px (Desktop)
- **Layout 2 kolom (Sidebar kanan 350 px):**
  - **Kiri (col-span-8):** Timeline Disposisi (lebar penuh), Formulir Tindak Lanjut (inline expand).
  - **Kanan (col-span-4, sticky top):** Kartu Identitas Surat + Lampiran + Formulir Disposisi Baru (panel collapse/expand).
- **Timeline:** Lebar penuh, avatar kiri, konten kanan (seperti chat). Garis vertikal penghubung.
- **Formulir Disposisi Baru:** Panel di sidebar kanan, tidak modal. Bisa dibiarkan terbuka sambil scroll timeline.
- **Penerima:** Select2-style multi-select dengan checkbox, searchable.
- **Cetak:** Buka tab baru `/surat-masuk/{id}/cetak` (layout cetak khusus).

---

## Aksesibilitas

- **Heading:** H1 (Detail Surat Masuk) → H2 (Identitas Surat, Lampiran, Riwayat Disposisi, Buat Disposisi Baru).
- **Timeline:** `<ol>` dengan `<li>` per item, `aria-label="Riwayat disposisi"`. Garis penghubung via CSS `::before` (tidak mengganggu screen reader).
- **Status badge:** `aria-label="Status: Menunggu tindak lanjut"`.
- **Formulir Disposisi:** Fieldset + Legend. Multi-select: `aria-multiselectable="true"`, `aria-label="Penerima disposisi"`. Pilihan dipilih: `aria-selected="true"`.
- **Focus trap:** Pada modal (mobile), fokus tertahan di dalam modal. `Escape` menutup.
- **Live region:** Toast `aria-live="polite"`. Validasi inline `aria-live="assertive"`.
- **Keyboard:** `Tab` navigasi logis. `Enter` pada tombol utama submit. `Escape` batal/close.

---

## Tautan Wireframe

- **Excalidraw:** [https://excalidraw.com/#json=SC05_ID](https://excalidraw.com/#json=SC05_ID)
- **Draw.io:** [https://app.diagrams.net/#GSC05_ID](https://app.diagrams.net/#GSC05_ID)
- **Figma:** [https://figma.com/file/SC05_ID](https://figma.com/file/SC05_ID)

---

## Catatan Implementasi

- **Rute terpisah untuk disposisi:**
  - `POST /surat-masuk/{id}/disposisi` → simpan disposisi baru (Kasubbag)
  - `POST /disposisi/{id}/tindak-lanjut` → catat tindak lanjut (Staff)
  - `GET /surat-masuk/{id}/cetak` → generate PDF (dompdf / snappy)
- **Validasi Disposisi:**
  ```php
  'penerima_ids' => 'required|array|min:1',
  'penerima_ids.*' => 'exists:users,id|role:staff_subbagian',
  'instruksi' => 'required|string|max:1000',
  'batas_waktu' => 'nullable|date|after_or_equal:today',
  ```
- **Validasi Tindak Lanjut:**
  ```php
  'isi_tindak_lanjut' => 'required|string|max:1000',
  'lampiran_tindak_lanjut.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
  'lampiran_tindak_lanjut' => 'nullable|array|max:3',
  ```
- **Status Otomatis:**
  - Surat: `konsep` → `diterima` (saat disimpan) → `didisposisikan` (saat disposisi dibuat) → `selesai` (semua tindak lanjut selesai).
  - Disposisi item: `menunggu` → `diproses` (staff buka/form) → `selesai` / `ditolak`.
- **Notifikasi Real-time:** Opsional - Laravel Echo / Pusher untuk update status tanpa refresh.
- **PDF Cetak:** Layout A4, header dinas, identitas surat, timeline disposisi, tanda tangan Kasubbag & Staff.