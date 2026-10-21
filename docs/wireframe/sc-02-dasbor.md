# SC-02: Dasbor (Halaman Utama Setelah Masuk)

**Kode Layar:** SC-02  
**Nama Layar:** Dasbor  
**Rute:** `/dasbor` (`dashboard`)  
**Akses:** Terautentikasi  
**Peran:** Kasubbag, Staff Subbagian, Pengelola Sistem

---

## Tujuan Layar

Menampilkan ringkasan statistik surat, grafik tren, dan akses cepat ke tugas-tugas utama. Dasbor berfungsi sebagai titik awal kerja harian bagi semua peran.

---

## Elemen Utama

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Header Halaman** | Judul "Dasbor" + breadcrumb | H1: "Dasbor", breadcrumb: Beranda / Dasbor |
| **Kartu Statistik (4 kartu)** | Ringkasan angka kunci | 1. Surat Masuk Bulan Ini<br>2. Surat Keluar Bulan Ini<br>3. Disposisi Menunggu Tindak Lanjut<br>4. Surat Menunggu Disposisi (hanya Kasubbag) |
| **Grafik Tren Surat** | Batang/garis 6 bulan terakhir | Sumbu X: Bulan, Sumbu Y: Jumlah. Dua seri: Masuk & Keluar. Tooltip detail. |
| **Daftar Disposisi Saya** | Tabel 5 baris terbaru | Kolom: Nomor Agenda, Perihal, Dari, Tanggal, Status, Tindakan. Hanya untuk Staff & Kasubbag. |
| **Surat Masuk Terbaru** | Tabel 5 baris terbaru | Kolom: Nomor Agenda, Tanggal Terima, Asal Surat, Perihal, Klasifikasi, Status. |
| **Akses Cepat** | Kumpulan tombol aksi utama | "Catat Surat Masuk", "Catat Surat Keluar", "Kelola Klasifikasi", "Lihat Laporan" |
| **Notifikasi / Peringatan** | Area toast / banner atas | Batas retensi, surat belum didisposisikan > 3 hari, dll. |

### Detail Kartu Statistik

| Kartu | Ikon | Warna Aksen | Nilai | Sublabel |
|-------|------|-------------|-------|----------|
| Surat Masuk | `inbox` | `#1B4F72` | Angka | "Bulan ini" |
| Surat Keluar | `send` | `#2E7D32` | Angka | "Bulan ini" |
| Disposisi Menunggu | `pending` | `#F9A825` | Angka | "Perlu tindak lanjut" |
| Menunggu Disposisi | `alert` | `#C62828` | Angka | "Belum didisposisikan" (Kasubbag) |

---

## Keadaan Kosong

- **Belum ada data surat:** Kartu statistik menampilkan `0`, grafik menampilkan pesan "Belum ada data surat untuk ditampilkan", tabel menampilkan baris tunggal "Tidak ada surat" di tengah.
- **Staff tanpa disposisi:** Area "Disposisi Saya" menampilkan "Anda tidak memiliki disposisi menunggu."
- **Kasubbag tanpa surat menunggu:** Kartu ke-4 menampilkan `0` dengan label "Semua surat sudah didisposisikan."

---

## Keadaan Galat

| Kondisi | Tampilan | Pesan |
|---------|----------|-------|
| Gagal memuat statistik | Kartu menampilkan skeleton loader → galat inline | "Gagal memuat statistik. Coba muat ulang halaman." |
| Grafik gagal dimuat | Area grafik menampilkan ikon galat + tombol "Coba Lagi" | "Grafik tidak dapat dimuat. Periksa koneksi." |
| Tabel gagal dimuat | Baris galat di tempat tabel | "Daftar surat tidak dapat dimuat." |
| Tidak berhak akses | Halaman 403 / redirect ke `/masuk` | "Anda tidak memiliki akses ke halaman ini." |

---

## Catatan Responsif

### 360 px (Mobile)
- **Tata letak:** Satu kolom vertikal (stack).
- **Kartu statistik:** 2 kolom (grid 2×2), lebar ~152 px masing-masing, padding 12 px.
- **Grafik:** Tinggi 250 px, label sumbu X disingkat (Jan, Feb, ...), legend di bawah.
- **Tabel:** Scroll horizontal dengan *sticky column* pertama (Nomor Agenda). Header tetap terlihat.
- **Akses Cepat:** 2 kolom grid, tombol tinggi 44 px, ikon + teks.
- **Header:** Judul H1 ukuran 24 px, breadcrumb disembunyikan (hanya ikon "Beranda").

### 1366 px (Desktop)
- **Tata letak:** Grid 12 kolom (Tailwind).
  - Kartu statistik: 4 kolom (col-span-3 masing-masing).
  - Grafik: col-span-8 (kiri), Disposisi Saya: col-span-4 (kanan).
  - Surat Masuk Terbaru: col-span-8, Akses Cepat: col-span-4.
- **Kartu statistik:** Padding 24 px, ikon 32 px, angka 28 px.
- **Grafik:** Tinggi 350 px, legend kanan atas, tooltip kaya.
- **Tabel:** Lebar penuh, hover highlight, paginasi terlihat.
- **Sidebar navigasi:** Tetap terlihat (collapsed/expanded).

---

## Aksesibilitas

- **Heading hierarchy:** H1 (Dasbor) → H2 (Statistik, Grafik, Disposisi Saya, Surat Terbaru, Akses Cepat) → H3 (judul kartu).
- **ARIA:** `role="region" aria-label="Statistik surat"` pada kartu; `aria-live="polite"` pada notifikasi.
- **Warna tidak saja:** Status disposisi menggunakan ikon + teks, bukan hanya warna.
- **Fokus:** Semua tombol & tautan memiliki focus ring.
- **Reduced motion:** Animasi grafik dimatikan jika `prefers-reduced-motion`.

---

## Tautan Wireframe

- **Excalidraw:** [https://excalidraw.com/#json=SC02_ID](https://excalidraw.com/#json=SC02_ID)
- **Draw.io:** [https://app.diagrams.net/#GSC02_ID](https://app.diagrams.net/#GSC02_ID)
- **Figma:** [https://figma.com/file/SC02_ID](https://figma.com/file/SC02_ID)

---

## Catatan Implementasi

- **Data statistik:** Endpoint API `/api/dashboard/stats` (GET) mengembalikan JSON 4 angka.
- **Grafik:** `/api/dashboard/trend?months=6` → array `{bulan, masuk, keluar}`.
- **Disposisi Saya:** `/api/disposisi/saya?limit=5` → paginasi server-side.
- **Surat Terbaru:** `/api/surat-masuk/terbaru?limit=5`.
- **Peran:** Logika tampilkan/sembunyikan kartu ke-4 & "Disposisi Saya" di sisi backend (controller) atau frontend (Alpine.js `x-show`).
- **Chart library:** Chart.js atau ApexCharts via Alpine.js component.
- **Polling:** Opsional - auto-refresh statistik tiap 60 detik (hentikan saat tab tidak aktif).