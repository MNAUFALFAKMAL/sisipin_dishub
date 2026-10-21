# SC-10: Halaman Publik (Klasifikasi & Tata Naskah)

**Kode Layar:** SC-10  
**Nama Layar:** Halaman Publik — Klasifikasi Arsip & Tata Naskah  
**Rute:** `/` (Beranda Publik), `/klasifikasi` (`publik.klasifikasi`), `/tata-naskah` (`publik.tata-naskah`), `/tata-naskah/{kategori}` (`publik.tata-naskah.kategori`), `/tata-naskah/{kategori}/{slug}` (`publik.tata-naskah.detail`)  
**Akses:** Publik (tanpa autentikasi)  
**Peran:** Tamu, Masyarakat, Pemangku Kepentingan

---

## Tujuan Layar

Menyediakan akses terbuka bagi publik untuk melihat daftar kode klasifikasi arsip (referensi Pergub Lampung 16/2023) dan contoh naskah dinas (tata naskah) tanpa perlu masuk ke sistem. Halaman ini berfungsi sebagai portal transparansi dan layanan informasi kearsipan Dinas Perhubungan Provinsi Lampung.

---

## Elemen Utama

### A. Beranda Publik (`/`)

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Header Publik** | Identitas dinas + navigasi minimal | - Logo Dinas Perhubungan Provinsi Lampung<br>- Nama: "DINAS PERHUBUNGAN PROVINSI LAMPUNG"<br>- Tagline: "SISIPIN — Sistem Informasi Arsip Dinamis"<br>- Navigasi: **Klasifikasi Arsip** | **Tata Naskah** | **Masuk** (kanan, tombol utama) |
| **Hero Section** | Visual utama + CTA | - Gambar/ilustrasi arsip digital (SVG, ringan)<br>- H1: "Arsip Terbuka, Layanan Lebih Cepat"<br>- P: "Jelajahi klasifikasi arsip dan contoh naskah dinas resmi Dinas Perhubungan Provinsi Lampung."<br>- Dua tombol CTA berdampingan:<br>  1. "Lihat Klasifikasi Arsip" (utama, ikon `folder`)<br>  2. "Lihat Tata Naskah" (sekunder, ikon `file-text`) |
| **Statistik Publik** | 3 kartu kecil | - Total Kode Klasifikasi: {jumlah}<br>- Total Contoh Naskah: {jumlah}<br>- Terakhir Diperbarui: {tanggal} |
| **Kategori Tata Naskah Populer** | Grid 4 kartu | Kartu per kategori: Ikon + Nama + Jumlah Contoh + Tombol "Lihat". Kategori: Surat Edaran, Surat Keputusan, Surat Perintah, Undangan, Nota Dinas, Berita Acara, dll. |
| **Footer Publik** | Informasi kontak & tautan | - Alamat Dinas, Telepon, Email<br>- Tautan: Kebijakan Privasi, Syarat Penggunaan, Peta Situs<br>- Hak Cipta: "© 2026 Dinas Perhubungan Provinsi Lampung" |

---

### B. Halaman Klasifikasi Arsip (`/klasifikasi`)

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Header Halaman** | Judul + breadcrumb | H1: "Klasifikasi Arsip", Breadcrumb: Beranda / Klasifikasi Arsip |
| **Bilah Pencarian** | Input cari full-width | Placeholder: "Cari kode, nama, atau deskripsi klasifikasi...". Debounce 300ms. Ikon lupu di dalam input. |
| **Saring Level** | Segmented control / Pill tabs | "Semua" | "Level 1 (Utama)" | "Level 2 (Sub)" | "Level 3 (Detail)" |
| **Daftar Klasifikasi (Tree View)** | Hierarki visual | - Level 1: Kode besar, nama tebal, warna badge kiri, expand/collapse chevron<br>- Level 2: Indent 24 px, kode monospace, nama normal<br>- Level 3: Indent 48 px, kode monospace, nama normal, retensi inline<br>- Setiap baris: Kode | Nama | Retensi (Aktif X thn, Inaktif Y thn, Musnah) |
| **Detail Samping (Desktop)** | Panel kanan saat klik item | Tampilkan: Kode, Nama, Level, Induk, Deskripsi, Retensi Lengkap, Warna, Status Aktif, Tanggal Dibuat. |
| **Tidak Ada Hasil** | State kosong pencarian | "Tidak ditemukan klasifikasi untuk '{kata_kunci}'. Coba kata kunci lain." |

---

### C. Halaman Tata Naskah (`/tata-naskah`)

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Header Halaman** | Judul + breadcrumb | H1: "Tata Naskah Dinas", Breadcrumb: Beranda / Tata Naskah |
| **Deskripsi Singkat** | Paragraf penjelasan | "Kumpulan contoh naskah dinas resmi sesuai standar tata naskah kelembagaan. Gunakan sebagai referensi penyusunan surat dinas." |
| **Kategori Naskah** | Accordion / Card Grid | Setiap kategori (mis. Surat Edaran, Surat Keputusan, Surat Perintah Tugas, SPPD, SK, Undangan, Nota Dinas, Berita Acara, Surat Tugas, Surat Keterangan):<br>- Ikon kategori<br>- Nama Kategori<br>- Jumlah Contoh<br>- Tombol "Lihat Contoh" → ke `/tata-naskah/{kategori}` |
| **Pencarian Cepat** | Input di atas kategori | "Cari contoh naskah..." (filter real-time nama file/judul). |

---

### D. Daftar Contoh per Kategori (`/tata-naskah/{kategori}`)

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Header** | Judul kategori + breadcrumb | H1: "Contoh {Nama Kategori}", Breadcrumb: Beranda / Tata Naskah / {Kategori} |
| **Bilah Pencarian & Saring** | Input cari + saring tahun | Placeholder: "Cari judul, nomor, atau tahun...". Saring Tahun (dropdown). |
| **Daftar Contoh** | Tabel / Card Grid | **Desktop (Tabel):** Kolom: Judul (tautan), Nomor Contoh, Tahun, Versi, Unduh (ikon PDF), Pratinjau (ikon mata).<br>**Mobile (Card):** Kartu: Judul + Tahun + Badge Versi + Tombol "Unduh PDF" + "Pratinjau". |
| **Paginasi** | 10 item per halaman | |

---

### E. Detail & Pratinjau Naskah (`/tata-naskah/{kategori}/{slug}`)

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Header** | Judul naskah + breadcrumb + aksi | H1: Judul Naskah. Breadcrumb lengkap. Tombol: "Kembali" (teks), "Unduh PDF" (utama, ikon unduh), "Cetak" (sekunder, ikon printer). |
| **Metadata Naskah** | Grid 2 kolom | Nomor Contoh, Kategori, Tahun, Versi, Tanggal Diperbarui, Disetujui Oleh (jabatan), Ukuran Berkas. |
| **Pratinjau PDF** | Iframe PDF.js / `<embed>` | - Toolbar: Zoom, Fit Width, Halaman, Unduh, Cetak<br>- Fullscreen toggle<br>- Fallback: "Peramban Anda tidak mendukung pratinjau PDF. [Unduh di sini]" |
| **Versi Sebelumnya** | Accordion (jika ada) | Daftar versi lama dengan tanggal & unduh. |
| **Naskah Terkait** | Sidebar / Bawah | 3-5 naskah lain dari kategori yang sama. |

---

## Keadaan Kosong

- **Klasifikasi kosong:** "Belum ada data klasifikasi arsip. Data akan segera tersedia."
- **Kategori tata naskah kosong:** "Belum ada contoh naskah untuk kategori ini."
- **Pencarian tidak ditemukan:** "Tidak ada hasil untuk '{kata_kunci}'."

---

## Keadaan Galat

| Kondisi | Tampilan | Pesan |
|---------|----------|-------|
| Gagal memuat klasifikasi | Toast/area galat | "Gagal memuat data klasifikasi. Coba muat ulang halaman." |
| PDF tidak ditemukan | Halaman 404 custom | "Contoh naskah tidak ditemukan atau telah dihapus." |
| Pratinjau PDF gagal | Area iframe → pesan galat + tombol unduh | "Tidak dapat menampilkan pratinjau. Silakan unduh berkas PDF." |
| Koneksi gagal | Toast/banner atas | "Tidak dapat terhubung ke server. Periksa koneksi internet Anda." |

---

## Catatan Responsif

### 360 px (Mobile)
- **Beranda:** Hero section padding 24 px. CTA tombol stack vertikal (lebar penuh). Statistik: 1 kolom stack.
- **Klasifikasi:** Tree view sebagai card list (indent via left border warna + padding). Expand/collapse per item. Detail: bottom sheet slide up.
- **Tata Naskah Kategori:** Accordion (native `<details><summary>`) untuk hemat ruang.
- **Daftar Contoh:** Card list. Pratinjau PDF: tab baru (mobile browser PDF viewer).
- **Navigasi Header:** Hamburger menu (drawer) untuk Klasifikasi | Tata Naskah | Masuk.

### 1366 px (Desktop)
- **Beranda:** Hero 2 kolom (teks kiri 60%, ilustrasi kanan 40%). CTA horizontal. Statistik: 3 kolom grid.
- **Klasifikasi:** Layout 2 kolom (Kiri 70% = Tree View, Kanan 30% = Detail Panel sticky). Tree view dengan hover highlight, smooth expand.
- **Tata Naskah:** Grid 4 kolom kategori kartu. Hover: naik, bayangan.
- **Daftar Contoh:** Tabel penuh dengan sticky header. Pratinjau: Modal besar (90vw × 90vh) dengan PDF.js viewer.
- **Header Publik:** Navigasi horizontal, tombol "Masuk" kanan.

---

## Aksesibilitas

- **Semantic HTML:** `<header>`, `<main>`, `<nav>`, `<section>`, `<article>`, `<footer>`, `<aside>`.
- **Heading Hierarchy:** H1 (judul halaman) → H2 (seksi) → H3 (sub-seksi/kategori).
- **Tree View Klasifikasi:** `role="tree"`, `role="treeitem"`, `aria-level`, `aria-expanded`, `aria-setsize`, `aria-posinset`. Keyboard: `ArrowRight` expand, `ArrowLeft` collapse, `ArrowDown/Up` navigasi, `Enter` pilih.
- **Accordion Tata Naskah:** Native `<details><summary>` (aksesibel bawaan).
- **PDF Iframe:** `title="Pratinjau {judul_naskah}"`, `aria-label="Pratinjau PDF"`. Link unduh sebagai fallback wajib.
- **Color-only:** Badge warna klasifikasi memiliki teks kode + nama, tidak hanya warna.
- **Kontras:** Semua teks pada latar memenuhi 4.5:1. Hero illustration dekoratif (`aria-hidden="true"`).
- **Skip Link:** "Lewati ke konten utama" di awal halaman (fokus terlihat).
- **Language:** `lang="id"` pada `<html>`.
- **Reduced Motion:** Animasi expand/collapse, modal, drawer dihormati.

---

## Tautan Wireframe

- **Excalidraw:** [https://excalidraw.com/#json=SC10_ID](https://excalidraw.com/#json=SC10_ID)
- **Draw.io:** [https://app.diagrams.net/#GSC10_ID](https://app.diagrams.net/#GSC10_ID)
- **Figma:** [https://figma.com/file/SC10_ID](https://figma.com/file/SC10_ID)

---

## Catatan Implementasi

- **Routing Publik:** Group route dengan prefix `/` tanpa middleware `auth`. Controller: `PublikController`.
- **Data Klasifikasi:** Query `Klasifikasi::where('aktif', true)->with('children')->get()` → transform ke tree JSON. Cache 1 jam.
- **Data Tata Naskah:** Model `TataNaskah` (judul, slug, kategori, tahun, versi, file_path, metadata JSON). Scope `publik()` filter `status = 'publik'`.
- **PDF Storage:** `storage/app/public/tata-naskah/{kategori}/{slug}.pdf`. Symlink `public/storage`.
- **PDF Viewer:** PDF.js (static files di `public/vendor/pdfjs/`) atau native `<embed type="application/pdf">` + fallback.
- **SEO:** Meta tags Open Graph, Twitter Card, JSON-LD `WebPage` / `DataCatalog` untuk klasifikasi. Sitemap.xml include publik routes.
- **Rate Limiting:** 60 req/menit per IP pada route publik (hindari scraping berlebihan).
- **Analytics:** Event klik "Unduh PDF", "Pratinjau", "Lihat Klasifikasi" (opsional, Matomo/GA4).
- **Maintenance Mode:** Jika `app:down`, halaman publik tetap tampil dengan banner "Sistem dalam pemeliharaan, beberapa fitur terbatas."