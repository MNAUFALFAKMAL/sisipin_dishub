# SC-08: Laporan & Pratinjau Cetak PDF

**Kode Layar:** SC-08  
**Nama Layar:** Laporan & Pratinjau Cetak PDF  
**Rute:** `/laporan` (`laporan.index`), `/laporan/surat-masuk` (`laporan.surat-masuk`), `/laporan/surat-keluar` (`laporan.surat-keluar`), `/laporan/disposisi` (`laporan.disposisi`), `/surat-masuk/{id}/cetak` (`surat-masuk.cetak`), `/surat-keluar/{id}/cetak` (`surat-keluar.cetak`), `/disposisi/{id}/cetak` (`disposisi.cetak`)  
**Akses:** Terautentikasi  
**Peran:** Kasubbag (semua laporan), Staff Subbagian (laporan sendiri), Pengelola Sistem (semua)

---

## Tujuan Layar

Menyediakan antarmuka untuk membuat, melihat pratinjau (preview), dan mengunduh laporan PDF surat masuk, surat keluar, dan disposisi. Laporan dapat disaring berdasarkan rentang tanggal, jenis, klasifikasi, dan status.

---

## Elemen Utama

### A. Halaman Daftar Laporan (`/laporan`)

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Header** | Judul + breadcrumb | H1: "Laporan" |
| **Kartu Pilih Jenis Laporan** | Grid 3 kartu klikable | 1. **Laporan Surat Masuk** (ikon inbox, biru)<br>2. **Laporan Surat Keluar** (ikon send, hijau)<br>3. **Laporan Disposisi** (ikon forward, oranye)<br>Kartu hover: bayangan naik, border biru. Klik → ke halaman filter masing-masing. |
| **Laporan Cepat (Sidebar)** | 4 tombol laporan siap pakai | "Surat Masuk Bulan Ini", "Surat Keluar Bulan Ini", "Disposisi Menunggu", "Semua Surat Tahun Ini". Klik → langsung pratinjau PDF. |

### B. Halaman Filter Laporan (Contoh: `/laporan/surat-masuk`)

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Header** | Judul + tombol kembali | H1: "Laporan Surat Masuk", Tombol "Kembali" (teks). |
| **Formulir Filter** | Kartu, grid 2 kolom | **Kolom Kiri:**<br>- Rentang Tanggal (Date Range Picker: `dari` & `sampai`, default: bulan ini)<br>- Klasifikasi (multi-select, searchable)<br>- Sifat Surat (checkbox group: Biasa, Penting, Sangat Rahasia, Rahasia)<br>**Kolom Kanan:**<br>- Status Disposisi (radio: Semua, Belum, Sudah, Selesai)<br>- Urut Berdasarkan (select: Tanggal Terima, Nomor Agenda, Asal Surat)<br>- Arah Urutan (radio: Naik, Turun)<br>- Format Output (radio: PDF, Excel — Excel hanya Kasubbag/Pengelola) |
| **Tombol Aksi** | Rata kanan | "Reset Filter" (teks), "Pratinjau PDF" (sekunder, ikon mata), "Unduh PDF" (utama, ikon unduh). |
| **Area Pratinjau (Modal/Tab Baru)** | Iframe / embed PDF | Setelah klik "Pratinjau PDF": buka tab baru `/laporan/surat-masuk/pdf?{params}` dengan `#toolbar=0&navpanes=0&scrollbar=1`. Header pratinjau: "Pratinjau Laporan Surat Masuk" + tombol "Cetak" (print dialog) & "Unduh". |

### C. Pratinjau Cetak Surat Individual (dari SC-03, SC-05, SC-06)

| Elemen | Deskripsi | Detail |
|--------|-----------|--------|
| **Layout PDF (A4, Potret)** | Header Dinas | - Logo Dinas Perhubungan Provinsi Lampung (kiri)<br>- "PEMERINTAH PROVINSI LAMPUNG"<br>- "DINAS PERHUBUNGAN"<br>- Garis pemisah |
| **Judul Surat** | Tengah, tebal | "SURAT MASUK" / "SURAT KELUAR (SPT)" / "DISPOSISI" / "SURAT KELUAR (SK)" dll. |
| **Identitas Surat** | Tabel 2 kolom (label: nilai) | Nomor Agenda, Tanggal Surat, Tanggal Terima, Asal Surat, Nomor Surat, Perihal, Klasifikasi, Sifat, Lampiran (daftar nama). |
| **Bagian Disposisi** (jika laporan disposisi / detail surat) | Timeline ringkas | Tabel: No, Tanggal, Dari, Kepada, Instruksi, Status, Tindak Lanjut. |
| **Tanda Tangan** | Area kosong + nama/jabatan | **Kasubbag:** (ttd) + Nama + NIP<br>**Staff:** (ttd) + Nama + NIP |
| **Footer** | Nomor halaman + timestamp | "Halaman {page} dari {total}" | "Dicetak pada: {tanggal_waktu} oleh {nama_pengguna}" |

---

## Keadaan Kosong

- **Filter tidak menghasilkan data:** Area pratinjau menampilkan "Tidak ada data sesuai filter. Ubah kriteria pencarian." Tombol "Kembali ke Filter".
- **Surat individual tidak ditemukan:** Halaman 404 / redirect dengan toast galat.

---

## Keadaan Galat

| Kondisi | Tampilan | Pesan |
|---------|----------|-------|
| Gagal generate PDF (timeout/memori) | Toast galat di halaman filter | "Gagal membuat PDF. Coba kurangi rentang tanggal atau pilih format Excel." |
| PDF kosong (bug template) | Toast galat | "Template laporan kosong. Hubungi Pengelola Sistem." |
| Cetak dari browser gagal | Toast info | "Gunakan tombol 'Unduh PDF' lalu cetak dari aplikasi PDF untuk hasil terbaik." |
| Rentang tanggal tidak logis | Validasi inline | "Tanggal 'sampai' tidak boleh sebelum 'dari'." |
| Rentang terlalu besar (> 1 tahun) | Peringatan (bukan galat) | "Rentang tanggal sangat besar. Proses PDF mungkin memakan waktu lama." |

---

## Catatan Responsif

### 360 px (Mobile)
- **Halaman Daftar Laporan:** 3 kartu stack vertikal (lebar penuh).
- **Formulir Filter:** Stack 1 kolom. Date Range Picker: native dua input date (Dari / Sampai) bertumpuk.
- **Multi-select Klasifikasi:** Bottom sheet / modal full-screen.
- **Pratinjau PDF:** Buka tab baru (browser mobile handle PDF viewer). Tombol "Cetak" & "Unduh" sticky bottom di halaman pratinjau.
- **Layout PDF:** A4 tetap (tidak responsif) — ini cetakan fisik/digital standar.

### 1366 px (Desktop)
- **Halaman Daftar Laporan:** Grid 3 kolom kartu.
- **Formulir Filter:** Grid 2 kolom, sticky saat scroll (position: sticky, top: 80px).
- **Pratinjau PDF:** Modal besar (90% lebar, 90% tinggi) dengan iframe PDF.js viewer, atau tab baru.
- **Toolbar PDF:** Print, Download, Zoom, Fit Width, Rotate, Page Navigation.
- **Sidebar Laporan Cepat:** Tetap terlihat di kiri (collapsible).

---

## Aksesibilitas

- **Date Range Picker:** Dua `<input type="date">` dengan label jelas ("Tanggal mulai", "Tanggal akhir"). `aria-describedby` untuk bantuan format.
- **Multi-select:** `aria-multiselectable="true"`, chip selection dengan `aria-label="Hapus {nama_klasifikasi} dari filter"`.
- **Radio Group Format Output:** `role="radiogroup"`, `aria-labelledby="label-format-output"`.
- **Pratinjau PDF (Iframe):** `title="Pratinjau Laporan Surat Masuk"`. Fallback link "Unduh PDF" jika iframe tidak didukung.
- **Keyboard:** Tab navigasi logis pada filter. `Enter` pada tombol "Pratinjau" / "Unduh" memicu aksi.
- **Live Region:** Status generate PDF: `aria-live="polite"` ("Menyiapkan PDF...", "PDF siap", "Gagal").

---

## Tautan Wireframe

- **Excalidraw:** [https://excalidraw.com/#json=SC08_ID](https://excalidraw.com/#json=SC08_ID)
- **Draw.io:** [https://app.diagrams.net/#GSC08_ID](https://app.diagrams.net/#GSC08_ID)
- **Figma:** [https://figma.com/file/SC08_ID](https://figma.com/file/SC08_ID)

---

## Catatan Implementasi

- **Library PDF:** `barryvdh/laravel-dompdf` (dompdf) atau `knplabs/knp-snappy` (wkhtmltopdf). Rekomendasi: dompdf untuk simplicitas, wkhtmltopdf untuk rendering CSS lebih akurat.
- **Template Blade PDF:** Disimpan di `resources/views/laporan/`:
  - `surat-masuk.blade.php` (daftar)
  - `surat-keluar.blade.php` (daftar per jenis)
  - `disposisi.blade.php` (daftar & detail)
  - `partials/header-dinas.blade.php`, `partials/footer-dinas.blade.php`, `partials/ttd.blade.php`
- **Controller:** `LaporanController` dengan method per jenis (`suratMasuk`, `suratKeluar`, `disposisi`) + `preview()` & `download()`.
- **Query Builder:** Scope `filterLaporan($query, $request)` reusable.
- **Chunking untuk Data Besar:** Gunakan `chunkById(500)` saat generate PDF > 1000 baris, gabungkan PDF (pdfmerger) atau stream.
- **Cache:** Cache hasil filter 5 menit (`Cache::remember`) untuk pratinjau berulang.
- **Filename PDF:** `laporan-surat-masuk-{dari}-{sampai}-{timestamp}.pdf`.
- **Print CSS:** `@media print` untuk sembunyikan tombol navigasi, header web, dll. pada halaman pratinjau HTML (jika pakai HTML-to-PDF).