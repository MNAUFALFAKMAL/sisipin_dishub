# SC-06: Daftar Surat Keluar per Jenis

**Kode Layar:** SC-06  
**Nama Layar:** Daftar Surat Keluar (SPT, SPPD, SK, Undangan)  
**Rute:** `/surat-keluar` (`surat-keluar.index`), `/surat-keluar/{jenis}` (`surat-keluar.jenis`)  
**Akses:** Terautentikasi  
**Peran:** Kasubbag, Staff Subbagian, Pengelola Sistem

---

## Tujuan Layar

Menampilkan daftar surat keluar yang dikelompokkan per jenis (SPT, SPPD, SK, Undangan) dengan tab navigasi. Setiap tab memiliki tabel dengan kolom yang relevan untuk jenis surat tersebut, plus pencarian, saring, dan aksi standar.

---

## Elemen Utama

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Header Halaman** | Judul + tombol tambah | H1: "Surat Keluar". Tombol "Catat Surat Keluar" (utama, ikon `plus`, dropdown pilih jenis: SPT, SPPD, SK, Undangan). |
| **Navigasi Tab Jenis** | 4 tab horizontal | Tab: **SPT** (Surat Perintah Tugas), **SPPD** (Surat Perintah Perjalanan Dinas), **SK** (Surat Keputusan), **Undangan**. Tab aktif: garis bawah biru `#1B4F72` 3 px, teks tebal. Badge jumlah pada setiap tab (contoh: "SPT (12)"). |
| **Bilah Pencarian & Saring (per Tab)** | Input cari + saring kontekstual | - Input Cari: placeholder berubah per tab (contoh: "Cari nomor, tujuan, nama pegawai...")<br>- Saring Umum: Tahun (dropdown), Bulan (dropdown)<br>- Saring Khusus per Tab:<br>  - **SPT/SPPD:** Tujuan (select), Status Biaya (Belum/Diproses/Selesai)<br>  - **SK:** Kategori Keputusan (select), Tingkat (Dinas/Provinsi)<br>  - **Undangan:** Acara (select), Status Kehadiran (Hadir/Tidak/Mungkin) |
| **Tabel Surat Keluar (per Tab)** | Tabel dengan kolom spesifik | **Kolom Umum:** Checkbox, Nomor Agenda (tautan), Tanggal Surat, Perihal, Status, Lampiran, Aksi.<br>**Kolom Khusus:**<br>- **SPT:** Nomor SPT, Nama Pegawai, Jabatan, Tujuan, Tanggal Mulai–Selesai<br>- **SPPD:** Nomor SPPD, Nama Pegawai, Tujuan, Transport, Tanggal, Estimasi Biaya<br>- **SK:** Nomor SK, Kategori, Tingkat, Tentang (potong), Tgl Berlaku<br>- **Undangan:** Nomor, Acara, Penyelenggara, Tempat, Tanggal, Waktu, Status Kehadiran |
| **Paginasi** | Per tab, independen | "Menampilkan 1–15 dari X", navigasi standar. |
| **Aksi Massal** | Footer tabel saat checkbox dicentang | "Hapus terpilih" (Pengelola Sistem), "Ekspor PDF terpilih", "Cetak Massal" (masing-masing PDF). |

---

## Keadaan Kosong

- **Belum ada surat keluar (semua tab):** Tab aktif menampilkan ilustrasi + "Belum ada surat keluar jenis ini. Klik 'Catat Surat Keluar' untuk menambahkan."
- **Tab lain kosong:** Badge jumlah `0`, area tabel: "Belum ada data."
- **Hasil saring/pencarian kosong:** "Tidak ada surat sesuai kriteria pada tab ini."

---

## Keadaan Galat

| Kondisi | Tampilan | Pesan |
|---------|----------|-------|
| Gagal memuat tab | Skeleton → toast galat | "Gagal memuat daftar surat keluar. Coba muat ulang." |
| Gagal pindah tab | Toast galat | "Tidak dapat memuat tab {jenis}. Coba lagi." |
| Hapus gagal | Toast galat | "Surat tidak dapat dihapus. Mungkin sudah memiliki referensi." |
| Cetak massal gagal | Toast galat per item | "Beberapa surat gagal dicetak. Coba cetak per item." |

---

## Catatan Responsif

### 360 px (Mobile)
- **Header:** Judul + tombol "Catat Surat Keluar" lebar penuh (stack).
- **Tab:** Scroll horizontal (overflow-x auto), snap ke tab aktif. Indikator scroll (fade tepi kanan).
- **Bilah Pencarian & Saring:** Bottom sheet / modal saat ikon "Saring" diklik. Input cari tetap di atas.
- **Tabel:** Mode *card list* per tab (sama seperti SC-03). Setiap kartu menampilkan field utama jenis tersebut.
  - **SPT Card:** Nomor Agenda + Status | Nomor SPT | Nama Pegawai | Tujuan | Tanggal | Aksi
  - **SPPD Card:** Nomor Agenda + Status | Nomor SPPD | Nama Pegawai | Tujuan | Transport | Estimasi Biaya | Aksi
  - **SK Card:** Nomor Agenda + Status | Nomor SK | Kategori | Tentang | Tgl Berlaku | Aksi
  - **Undangan Card:** Nomor Agenda + Status | Acara | Penyelenggara | Tempat & Tanggal | Status Kehadiran | Aksi
- **Paginasi:** Simpel (Prev/Next + halaman aktif).
- **Aksi Massal:** Bottom bar fixed.

### 1366 px (Desktop)
- **Header:** Flex between (judul kiri, tombol kanan).
- **Tab:** Horizontal, lebar penuh, hover effect, keyboard navigable (ArrowLeft/Right).
- **Bilah Pencarian & Saring:** Horizontal flex wrap, gap 12 px. Input cari min 300 px. Dropdown native.
- **Tabel:** `<table>` penuh per tab. Kolom khusus terlihat. Sticky header. Hover highlight.
- **Kolom Aksi:** 3 ikon (Lihat, Sunting, Hapus) + dropdown "Lainnya" (Cetak, Duplikat).
- **Paginasi:** Lengkap dengan opsi per halaman.
- **Aksi Massal:** Sticky bar di atas paginasi.

---

## Aksesibilitas

- **Tab List:** `role="tablist"`, `aria-label="Jenis surat keluar"`. Setiap tab: `role="tab"`, `aria-selected`, `aria-controls="panel-{jenis}"`, `tabindex="0"` (aktif) / `-1` (non-aktif).
- **Tab Panel:** `role="tabpanel"`, `aria-labelledby="tab-{jenis}"`, `hidden` pada non-aktif.
- **Keyboard:** `ArrowLeft/Right` pindah tab, `Home/End` ke ujung, `Enter/Space` aktifkan.
- **Heading:** H1 (Surat Keluar) → H2 (tab aktif sebagai heading visual, `aria-level="2"`).
- **Tabel:** Semantic `<table>` dengan `<th scope="col">`. Kolom khusus memiliki `abbr` untuk header singkat.
- **Badge Jumlah:** `aria-label="Jumlah SPT: 12"`.
- **Focus:** Ring pada tab, input, tombol, baris tabel (jika actionable).

---

## Tautan Wireframe

- **Excalidraw:** [https://excalidraw.com/#json=SC06_ID](https://excalidraw.com/#json=SC06_ID)
- **Draw.io:** [https://app.diagrams.net/#GSC06_ID](https://app.diagrams.net/#GSC06_ID)
- **Figma:** [https://figma.com/file/SC06_ID](https://figma.com/file/SC06_ID)

---

## Catatan Implementasi

- **Struktur Data:** Satu tabel `surat_keluar` dengan kolom `jenis` (enum: `spt`, `sppd`, `sk`, `undangan`) + JSON `detail_jenis` untuk field spesifik.
- **Endpoint:**
  - `GET /surat-keluar?jenis=spt&page=1&cari=...&tahun=2026`
  - `POST /surat-keluar` (store, dengan `jenis` di body)
  - `GET /surat-keluar/{id}/cetak` (PDF per jenis)
  - `POST /surat-keluar/ekspor-pdf` (massal)
- **Validasi per Jenis (Request Class terpisah):**
  - **SPT:** `nomor_spt`, `pegawai_id`, `tujuan`, `tanggal_mulai`, `tanggal_selesai`, `dasar_surat`
  - **SPPD:** `nomor_sppd`, `pegawai_id`, `tujuan`, `transportasi`, `tanggal_berangkat`, `tanggal_kembali`, `estimasi_biaya`
  - **SK:** `nomor_sk`, `kategori`, `tingkat`, `tentang`, `tanggal_berlaku`, `dasar_hukum`
  - **Undangan:** `nomor_undangan`, `acara`, `penyelenggara`, `tempat`, `tanggal`, `waktu_mulai`, `waktu_selesai`
- **Nomor Agenda:** Otomatis `SK/{tahun}/{urut}`, `UND/{tahun}/{urut}`, dst. (prefix per jenis).
- **Relasi Pegawai:** `pegawai_id` foreign key ke tabel `pegawai` (data KP4).
- **Export PDF:** Template Blade per jenis (`surat-keluar.spt.pdf`, `surat-keluar.sppd.pdf`, dll.).