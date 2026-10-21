@extends('layouts.app')
@section('title', 'Panduan Pengguna (Pembelajaran)')
@section('page-title', 'Panduan Pengguna (Pembelajaran)')

@section('content')
<div class="space-y-6 text-slate-900 dark:text-slate-200">

    {{-- Header Banner --}}
    <div style="border-radius:14px;overflow:hidden;
          background:linear-gradient(135deg,#1A365D 0%,#1e4d8c 55%,#2563eb 100%);
          padding:24px 28px;position:relative;color:#fff">
        <div style="position:absolute;inset:0;
              background-image:radial-gradient(rgba(255,255,255,.05) 1px,transparent 1px);
              background-size:22px 22px;pointer-events:none"></div>
        <div style="position:relative;z-index:1;">
            <h2 style="margin:0 0 6px;font-size:20px;font-weight:800;">
                Pusat Pembelajaran &amp; Panduan Sistem
            </h2>
            <p style="margin:0;font-size:13px;color:rgba(255,255,255,.75);line-height:1.6">
                Pelajari cara menggunakan setiap modul SISIPIN DISHUB langkah demi langkah. Pilih modul di bawah.
            </p>
        </div>
    </div>

    {{-- Module Pills --}}
    <div class="module-pills bg-white dark:bg-slate-900 p-2 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 flex gap-2 overflow-x-auto">
        <button onclick="switchModule('surat-menyurat')" id="mod-btn-surat-menyurat" class="mod-btn active px-4 py-2.5 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200">Surat Menyurat</button>
        <button onclick="switchModule('dashboard')" id="mod-btn-dashboard" class="mod-btn px-4 py-2.5 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200">Dashboard</button>
        <button onclick="switchModule('klasifikasi')" id="mod-btn-klasifikasi" class="mod-btn px-4 py-2.5 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200">Klasifikasi Arsip</button>
        <button onclick="switchModule('template')" id="mod-btn-template" class="mod-btn px-4 py-2.5 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200">Template</button>
        <button onclick="switchModule('pdf-utility')" id="mod-btn-pdf-utility" class="mod-btn px-4 py-2.5 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200">Utilitas PDF</button>
        <button onclick="switchModule('kp4')" id="mod-btn-kp4" class="mod-btn px-4 py-2.5 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200">Generate KP4</button>
        <button onclick="switchModule('pengguna')" id="mod-btn-pengguna" class="mod-btn px-4 py-2.5 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200">Pengguna</button>
        <button onclick="switchModule('audit-log')" id="mod-btn-audit-log" class="mod-btn px-4 py-2.5 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200">Audit Log</button>
    </div>

    @php
        $modules = [
            'surat-menyurat' => [['tambah','Tambah & Edit Data'],['cari','Pencarian & Filter'],['ekspor','Ekspor Laporan'],['impor','Impor Data Excel']],
            'dashboard' => [['statistik','Statistik Surat'],['grafik','Grafik Tren'],['rekap','Ekspor Rekap']],
            'klasifikasi' => [['cari','Pencarian Kode'],['crud','Tambah & Edit'],['struktur','Struktur Kode JRA']],
            'template' => [['unduh','Mengunduh Template'],['guna','Menggunakan Template']],
            'pdf-utility' => [['word','Word ke PDF'],['gambar','Gambar ke PDF'],['gabung','Gabung PDF']],
            'kp4' => [['isi','Mengisi Form'],['hasil','Hasil Dokumen']],
            'pengguna' => [['tambah','Tambah Pengguna'],['edit','Edit & Hapus']],
            'audit-log' => [['lihat','Melihat Log'],['cari','Mencari & Memahami']],
        ];
    @endphp

    @foreach($modules as $mod => $tabs)
    <div id="tabs-{{ $mod }}" class="mod-tabs bg-white dark:bg-slate-900 p-2 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 flex gap-2 overflow-x-auto" style="display:{{ $mod === 'surat-menyurat' ? 'flex' : 'none' }}">
        @foreach($tabs as $i => $tab)
        <button onclick="switchTab('{{ $mod }}','{{ $tab[0] }}')" id="tab-btn-{{ $mod }}-{{ $tab[0] }}" class="tab-btn {{ $i === 0 ? 'active' : '' }} px-4 py-2.5 rounded-lg text-sm font-bold whitespace-nowrap transition-all duration-200">{{ $tab[1] }}</button>
        @endforeach
    </div>
    @endforeach

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- MODUL: SURAT MENYURAT --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    <div id="mod-content-surat-menyurat" class="mod-content">
        <x-card class="min-h-[400px] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">

            {{-- TAB: Tambah & Edit Data --}}
            <div id="tab-content-surat-menyurat-tambah" class="tab-content space-y-6">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Panduan Menambah &amp; Mengedit Data Surat</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-blue-50 dark:bg-blue-950/30 p-5 rounded-xl border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-blue-900 dark:text-blue-300 text-sm">Pilih Jenis Surat dari Sidebar</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Buka menu <strong>Surat Menyurat</strong> di sidebar kiri. Pilih jenis surat yang ingin Anda input: Surat Masuk, Undangan Masuk, Surat Keluar, SPPD, SK, SPT, atau Undangan Keluar. Setiap jenis memiliki halaman daftar dan form input tersendiri.</p>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-950/30 p-5 rounded-xl border border-amber-200 dark:border-amber-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-amber-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-amber-900 dark:text-amber-300 text-sm">Klik Tombol "Tambah"</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Pada halaman daftar surat, klik tombol <strong>Tambah</strong> berwarna biru di pojok kanan atas. Halaman akan berpindah ke form input data surat. Perhatikan bahwa jenis surat sudah otomatis terisi sesuai halaman asal Anda.</p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-950/30 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-green-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-green-900 dark:text-green-300 text-sm">Isi Form Data Surat</h4></div>
                            <ul class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed space-y-1.5 list-disc pl-4">
                                <li><strong>Nomor Urut/Agenda</strong> &ndash; Terisi otomatis (nomor terakhir +1). Bisa diedit manual. <span class="text-amber-600 dark:text-amber-400">Format: angka biasa (1, 2, 3, ...) atau sub-nomor (1.a, 2.b).</span></li>
                                <li><strong>Tanggal</strong> &ndash; Pilih tanggal terima/masuk dan tanggal surat via datepicker.</li>
                                <li><strong>Kode Klasifikasi</strong> &ndash; Cari dan pilih kode JRA dari dropdown searchable (mulai ketik untuk mencari).</li>
                                <li><strong>Pengirim/Tujuan</strong> &ndash; Isi nama instansi atau pejabat pengirim (Surat Masuk) atau tujuan (Surat Keluar).</li>
                                <li><strong>Perihal/Uraian</strong> &ndash; Tulis isi ringkas surat. Akan ditampilkan di tabel dengan pemotongan tengah jika terlalu panjang.</li>
                                <li><strong>Nomor Surat</strong> &ndash; (Surat Keluar) Terisi otomatis setelah kode klasifikasi, nomor urut, dan tahun diisi. Format: <code class="text-[10px] font-mono bg-white dark:bg-slate-800 px-1 rounded">kode/no_urut/V.13/tahun</code>.</li>
                                <li><strong>Disposisi</strong> &ndash; (Surat Masuk) Pilih satu atau lebih bidang dalam bentuk grid checkbox.</li>
                                <li><strong>Upload File</strong> &ndash; Lampirkan scan surat dalam format PDF maksimal 5 MB.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-purple-50 dark:bg-purple-950/30 p-5 rounded-xl border border-purple-200 dark:border-purple-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-purple-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">4</span><h4 class="font-bold text-purple-900 dark:text-purple-300 text-sm">Simpan Data</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Setelah semua data terisi, klik tombol <strong>Simpan</strong> di bagian bawah form. Sistem akan memvalidasi data, menyimpan ke database, dan mencatat riwayat ke Audit Log. Anda akan dikembalikan ke halaman daftar dengan data terbaru.</p>
                        </div>
                        <div class="bg-rose-50 dark:bg-rose-950/30 p-5 rounded-xl border border-rose-200 dark:border-rose-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-rose-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">5</span><h4 class="font-bold text-rose-900 dark:text-rose-300 text-sm">Mengedit Data yang Sudah Ada</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Klik ikon <strong>pensil</strong> pada baris data di tabel untuk mengedit. Form akan terbuka dengan data yang sudah terisi. Ubah bagian yang perlu, lalu klik <strong>Simpan</strong>. Jika ingin menghapus file scan lama, centang kotak "Hapus file ini".</p>
                        </div>
                        <div class="bg-indigo-50 dark:bg-indigo-950/30 p-5 rounded-xl border border-indigo-200 dark:border-indigo-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">6</span><h4 class="font-bold text-indigo-900 dark:text-indigo-300 text-sm">Auto-Creation Gap (Placeholder)</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Jika Anda mengisi nomor urut yang melompat (misal dari 1 ke 12), sistem otomatis membuat record kosong untuk nomor 2&ndash;11. Record ini bisa diisi kapan saja atau diedit. <span class="text-amber-600 dark:text-amber-400">Nomor yang sudah ada datanya tidak akan ditimpa.</span></p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 space-y-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Simulasi: Proses Input Surat Keluar dengan Nomor Otomatis</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-slate-100 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl p-5">
                            <div class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-3">Langkah 1&ndash;3: Isi Form</div>
                            <div class="space-y-2.5">
                                <div class="flex justify-between items-center bg-white dark:bg-slate-800 p-3 rounded-lg border border-slate-300 dark:border-slate-700">
                                    <span class="font-bold text-slate-700 dark:text-slate-300 text-xs">Kode Klasifikasi:</span>
                                    <span class="bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 font-mono text-xs px-2 py-0.5 rounded font-bold">500.11</span>
                                </div>
                                <div class="flex justify-between items-center bg-white dark:bg-slate-800 p-3 rounded-lg border border-slate-300 dark:border-slate-700">
                                    <span class="font-bold text-slate-700 dark:text-slate-300 text-xs">Nomor Urut:</span>
                                    <span class="bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300 font-mono text-xs px-2 py-0.5 rounded font-bold">305</span>
                                </div>
                                <div class="flex justify-between items-center bg-white dark:bg-slate-800 p-3 rounded-lg border border-slate-300 dark:border-slate-700">
                                    <span class="font-bold text-slate-700 dark:text-slate-300 text-xs">Tahun Surat:</span>
                                    <span class="bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-300 font-mono text-xs px-2 py-0.5 rounded font-bold">2026</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-100 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl p-5">
                            <div class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-3">Hasil: Nomor Surat Terbentuk Otomatis</div>
                            <div class="bg-green-100 dark:bg-green-950/30 border border-green-300 dark:border-green-800 rounded-lg p-4 text-center">
                                <span class="block text-[11px] text-green-700 dark:text-green-400 font-bold uppercase mb-2">Format Resmi</span>
                                <span class="font-mono text-base font-bold text-green-800 dark:text-green-300 tracking-wide">500.11/305/V.13/2026</span>
                                <span class="block text-[10px] text-green-600 dark:text-green-500 mt-2">Kode / No.Urut / Kode Dishub / Tahun</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: Pencarian & Filter --}}
            <div id="tab-content-surat-menyurat-cari" class="tab-content space-y-6" style="display:none">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Panduan Mencari &amp; Menyaring Data Surat</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-indigo-50 dark:bg-indigo-950/30 p-5 rounded-xl border border-indigo-200 dark:border-indigo-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-indigo-900 dark:text-indigo-300 text-sm">Pencarian Kata Kunci</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Ketik kata kunci pada kolom pencarian di atas tabel. Sistem akan mencari secara simultan ke empat kolom:</p>
                            <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1 mt-2 list-disc pl-4">
                                <li><strong>Nomor Urut / No. Agenda</strong> &ndash; Cari angka seperti "12" atau "1.a"</li>
                                <li><strong>Nomor Surat</strong> &ndash; Cari nomor resmi surat</li>
                                <li><strong>Nama Pengirim / Tujuan</strong> &ndash; Cari nama instansi atau pejabat</li>
                                <li><strong>Perihal / Uraian</strong> &ndash; Cari kata dalam isi ringkas surat</li>
                            </ul>
                        </div>
                        <div class="bg-teal-50 dark:bg-teal-950/30 p-5 rounded-xl border border-teal-200 dark:border-teal-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-teal-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-teal-900 dark:text-teal-300 text-sm">Filter Tipe Periode</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Gunakan dropdown <strong>Tipe Periode</strong> untuk membatasi data berdasarkan waktu:</p>
                            <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1 mt-2 list-disc pl-4">
                                <li><strong>Semua Waktu</strong> &ndash; Tampilkan seluruh data tanpa batas tanggal</li>
                                <li><strong>Per Bulan</strong> &ndash; Pilih bulan dan tahun spesifik (misal: Juli 2026)</li>
                                <li><strong>Per Tahun</strong> &ndash; Tampilkan data satu tahun penuh</li>
                                <li><strong>Rentang Tanggal</strong> &ndash; Tentukan tanggal awal dan akhir manual</li>
                            </ul>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-cyan-50 dark:bg-cyan-950/30 p-5 rounded-xl border border-cyan-200 dark:border-cyan-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-cyan-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-cyan-900 dark:text-cyan-300 text-sm">Filter Terintegrasi dengan Ekspor</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Filter periode yang Anda pilih juga akan digunakan saat mengekspor laporan. Pastikan filter sudah sesuai sebelum mengklik tombol Ekspor agar data yang diunduh tepat.</p>
                        </div>
                        <div class="bg-emerald-50 dark:bg-emerald-950/30 p-5 rounded-xl border border-emerald-200 dark:border-emerald-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-emerald-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">4</span><h4 class="font-bold text-emerald-900 dark:text-emerald-300 text-sm">Navigasi &amp; Urutan Data</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Data diurutkan berdasarkan <strong>Nomor Urut</strong> dari besar ke kecil (terbaru di atas). Gunakan paginasi di bagian bawah tabel untuk berpindah halaman. Tampilan paginasi menyesuaikan antara desktop (ikon chevron) dan mobile (tombol label "Sebelumnya/Selanjutnya").</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: Ekspor Laporan --}}
            <div id="tab-content-surat-menyurat-ekspor" class="tab-content space-y-6" style="display:none">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Panduan Mengekspor Laporan Excel &amp; PDF</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-emerald-50 dark:bg-emerald-950/30 p-5 rounded-xl border border-emerald-200 dark:border-emerald-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-emerald-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-emerald-900 dark:text-emerald-300 text-sm">Ekspor dari Halaman Daftar Surat</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Klik tombol <strong>Ekspor</strong> di pojok kanan atas halaman daftar surat. Akan muncul modal pilihan format dan periode. Pilih format yang diinginkan:</p>
                            <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1 mt-2 list-disc pl-4">
                                <li><strong>Excel (.xlsx)</strong> &ndash; Spreadsheet dengan format tabel, border, dan wrap text otomatis</li>
                                <li><strong>PDF (.pdf)</strong> &ndash; Dokumen siap cetak dengan format portrait dan header kop surat</li>
                            </ul>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-950/30 p-5 rounded-xl border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-blue-900 dark:text-blue-300 text-sm">Ekspor Rekap dari Dashboard</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Di halaman <strong>Dashboard</strong>, tersedia tombol Excel dan PDF yang akan mengunduh rekap seluruh 7 jenis surat sekaligus. File Excel memiliki sheet terpisah per kategori. PDF dicetak landscape dengan pemisahan per halaman.</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-cyan-50 dark:bg-cyan-950/30 p-5 rounded-xl border border-cyan-200 dark:border-cyan-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-cyan-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-cyan-900 dark:text-cyan-300 text-sm">Format &amp; Tata Letak</h4></div>
                            <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1.5 list-disc pl-4">
                                <li>Nama file otomatis: <code class="text-[10px] font-mono bg-slate-100 dark:bg-slate-800 px-1 rounded">[Jenis Surat] [Periode].xlsx</code></li>
                                <li>Kolom rata tengah (center) + vertical align middle</li>
                                <li>Kolom Uraian rata kiri dengan indent untuk keterbacaan</li>
                                <li>Data diurutkan dari nomor besar ke kecil (terbaru dulu)</li>
                                <li>File siap cetak dan distribusi</li>
                            </ul>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-950/30 p-5 rounded-xl border border-amber-200 dark:border-amber-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-amber-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">4</span><h4 class="font-bold text-amber-900 dark:text-amber-300 text-sm">Kustomisasi Periode Ekspor</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Pada modal ekspor, pilih tipe periode: <strong>Bulan</strong> (pilih bulan + tahun), <strong>Tahun</strong> (isi tahun), <strong>Rentang</strong> (isi tanggal awal &amp; akhir), atau <strong>Semua</strong> (tanpa batas). Pastikan periode sudah tepat sebelum mengunduh.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: Impor Data Excel --}}
            <div id="tab-content-surat-menyurat-impor" class="tab-content space-y-6" style="display:none">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Panduan Mengimpor Data dari Excel</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-red-50 dark:bg-red-950/30 p-5 rounded-xl border border-red-200 dark:border-red-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-red-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-red-900 dark:text-red-300 text-sm">Unduh Template Resmi</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Klik tombol <strong>Impor</strong> di halaman daftar surat. Di dalam modal, klik tautan <strong>Template Excel</strong> untuk mengunduh file template kosong. Template ini memiliki struktur kolom yang sudah sesuai dengan sistem. <span class="text-red-600 dark:text-red-400 font-bold">Jangan gunakan file Excel buatan sendiri tanpa template &mdash; impor akan gagal.</span></p>
                        </div>
                        <div class="bg-slate-100 dark:bg-slate-800/60 p-5 rounded-xl border border-slate-300 dark:border-slate-700">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-slate-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm">Isi Data di Template</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Buka template yang sudah diunduh menggunakan Excel atau LibreOffice. Isi data surat pada baris-baris yang tersedia. Kolom wajib diisi:</p>
                            <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1 mt-2 list-disc pl-4">
                                <li><strong>A (Nomor Urut)</strong> &ndash; Angka biasa, contoh: 305. <span class="text-red-500">Jangan gunakan format 00305.</span></li>
                                <li><strong>B (Tanggal)</strong> &ndash; Format YYYY-MM-DD (contoh: 2026-07-01)</li>
                                <li><strong>C (Kode)</strong> &ndash; Kode klasifikasi JRA, contoh: 500.11.13</li>
                                <li><strong>D (Pengirim/Tujuan)</strong> &ndash; Nama instansi atau pejabat</li>
                                <li><strong>E (Nomor Surat)</strong> &ndash; Nomor resmi surat</li>
                                <li><strong>F (Tanggal Surat)</strong> &ndash; Format YYYY-MM-DD</li>
                                <li><strong>G (Uraian)</strong> &ndash; Perihal atau isi ringkas</li>
                                <li><strong>H (Disposisi)</strong> &ndash; Singkatan bidang (TSP, LLA, dll.)</li>
                                <li><strong>I (Keterangan)</strong> &ndash; Opsional</li>
                            </ul>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-amber-50 dark:bg-amber-950/30 p-5 rounded-xl border border-amber-200 dark:border-amber-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-amber-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-amber-900 dark:text-amber-300 text-sm">Upload &amp; Proses</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Kembali ke modal Impor, klik tombol <strong>Pilih File</strong> dan pilih file Excel yang sudah diisi. Pastikan format file .xlsx. Klik <strong>Import</strong> untuk memulai proses. Sistem akan membaca, memvalidasi, dan menyimpan data ke database.</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-2">Setelah impor selesai, halaman akan otomatis merefresh dan menampilkan data terbaru. Periksa kembali data yang berhasil diimpor.</p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-950/30 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-green-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">4</span><h4 class="font-bold text-green-900 dark:text-green-300 text-sm">Tips &amp; Peringatan</h4></div>
                            <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1.5 list-disc pl-4">
                                <li>Pastikan tidak ada baris kosong di tengah data</li>
                                <li>Nomor urut tidak boleh duplikat dalam satu jenis surat</li>
                                <li>Kode klasifikasi harus sudah ada di database</li>
                                <li>File maksimal 2 MB</li>
                                <li>Gunakan template yang sesuai dengan jenis surat yang sedang diimpor</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </x-card>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- MODUL: DASHBOARD --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    <div id="mod-content-dashboard" class="mod-content" style="display:none">
        <x-card class="min-h-[400px] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">

            {{-- TAB: Statistik Surat --}}
            <div id="tab-content-dashboard-statistik" class="tab-content space-y-6">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Statistik Surat di Dashboard</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-blue-50 dark:bg-blue-950/30 p-5 rounded-xl border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-blue-900 dark:text-blue-300 text-sm">7 Kartu Statistik</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Halaman dashboard menampilkan 7 kartu statistik yang mewakili masing-masing jenis surat: Surat Masuk, Undangan Masuk, Surat Keluar, SPPD, SK, SPT, dan Undangan Keluar. Setiap kartu menunjukkan jumlah total surat yang tersimpan di database.</p>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-950/30 p-5 rounded-xl border border-amber-200 dark:border-amber-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-amber-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-amber-900 dark:text-amber-300 text-sm">Data Langsung dari Database</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Setiap kali ada penambahan, perubahan, atau penghapusan data surat, angka pada kartu statistik akan berubah secara otomatis. Tidak perlu melakukan refresh halaman untuk mendapatkan data terkini.</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-green-50 dark:bg-green-950/30 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-green-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-green-900 dark:text-green-300 text-sm">Visual Warna &amp; Ikon</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Setiap jenis surat memiliki kombinasi warna dan ikon yang berbeda pada kartunya. Ini memudahkan identifikasi visual &mdash; Anda bisa langsung mengenali jenis surat hanya dari warna kartu tanpa perlu membaca label.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: Grafik Tren --}}
            <div id="tab-content-dashboard-grafik" class="tab-content space-y-6" style="display:none">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Grafik Tren Surat 6 Bulan</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-indigo-50 dark:bg-indigo-950/30 p-5 rounded-xl border border-indigo-200 dark:border-indigo-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-indigo-900 dark:text-indigo-300 text-sm">Bar Chart Perbandingan</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Grafik batang (bar chart) di dashboard menampilkan perbandingan jumlah surat selama 6 bulan terakhir. Terdapat 7 dataset berbeda &mdash; masing-masing jenis surat &mdash; yang divisualisasikan dengan warna kontras agar mudah dibedakan.</p>
                        </div>
                        <div class="bg-teal-50 dark:bg-teal-950/30 p-5 rounded-xl border border-teal-200 dark:border-teal-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-teal-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-teal-900 dark:text-teal-300 text-sm">Interaksi Grafik</h4></div>
                            <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1.5 list-disc pl-4">
                                <li><strong>Hover</strong> di atas batang untuk melihat detail jumlah</li>
                                <li><strong>Klik legenda</strong> untuk menyembunyikan/munculkan dataset tertentu</li>
                                <li>Analisis tren peningkatan atau penurunan dari bulan ke bulan</li>
                            </ul>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-cyan-50 dark:bg-cyan-950/30 p-5 rounded-xl border border-cyan-200 dark:border-cyan-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-cyan-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-cyan-900 dark:text-cyan-300 text-sm">Tampilan Full Width</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Grafik ditampilkan dalam lebar penuh (full width) di bawah kartu statistik. Ukurannya responsif &mdash; menyesuaikan secara proporsional di layar desktop maupun mobile tanpa perlu scroll horizontal.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: Ekspor Rekap --}}
            <div id="tab-content-dashboard-rekap" class="tab-content space-y-6" style="display:none">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Ekspor Rekap dari Dashboard</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-emerald-50 dark:bg-emerald-950/30 p-5 rounded-xl border border-emerald-200 dark:border-emerald-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-emerald-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-emerald-900 dark:text-emerald-300 text-sm">Tombol Ekspor</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Di pojok kanan atas dashboard, terdapat dua tombol: <strong>Excel (.xlsx)</strong> dan <strong>PDF (.pdf)</strong>. Keduanya akan mengunduh rekap data seluruh 7 jenis surat sekaligus dalam satu berkas. Tidak perlu mengekspor satu per satu.</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-950/30 p-5 rounded-xl border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-blue-900 dark:text-blue-300 text-sm">Excel Multi-Sheet</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">File Excel yang dihasilkan memiliki beberapa sheet (tab): satu sheet <strong>Rekapitulasi</strong> berisi ringkasan jumlah per jenis, dan sheet terpisah untuk masing-masing kategori surat dengan data lengkap.</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-rose-50 dark:bg-rose-950/30 p-5 rounded-xl border border-rose-200 dark:border-rose-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-rose-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-rose-900 dark:text-rose-300 text-sm">PDF Landscape Terpadu</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Dokumen PDF dicetak dalam orientasi <strong>landscape</strong> (mendatar). Setiap kategori surat dipisah per halaman. Header memuat kop surat Dishub Lampung, footer memuat tanggal cetak. Siap untuk diserahkan sebagai laporan resmi.</p>
                        </div>
                    </div>
                </div>
            </div>

        </x-card>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- MODUL: KLASIFIKASI ARSIP --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    <div id="mod-content-klasifikasi" class="mod-content" style="display:none">
        <x-card class="min-h-[400px] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">

            <div id="tab-content-klasifikasi-cari" class="tab-content space-y-6">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Pencarian Kode Klasifikasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-indigo-50 dark:bg-indigo-950/30 p-5 rounded-xl border border-indigo-200 dark:border-indigo-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-indigo-900 dark:text-indigo-300 text-sm">Cari Berdasarkan Kata Kunci</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Ketik kata kunci pada kolom pencarian. Sistem akan mencari keempat kolom sekaligus: <strong>Kode Klasifikasi</strong>, <strong>Nama Klasifikasi</strong>, <strong>Unit Pengolah</strong>, dan <strong>Uraian</strong>. Hasil ditampilkan dalam tabel responsif dengan zebra-striping.</p>
                        </div>
                        <div class="bg-teal-50 dark:bg-teal-950/30 p-5 rounded-xl border border-teal-200 dark:border-teal-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-teal-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-teal-900 dark:text-teal-300 text-sm">Filter Nasib Akhir &amp; Tingkat</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Gunakan dropdown filter untuk mempersempit hasil: <strong>Nasib Akhir</strong> (Musnah, Permanen, atau Dinilai) dan <strong>Tingkat Perkembangan</strong> (asli, salinan, fotokopi). Filter dapat dikombinasikan dengan pencarian kata kunci.</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-amber-50 dark:bg-amber-950/30 p-5 rounded-xl border border-amber-200 dark:border-amber-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-amber-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-amber-900 dark:text-amber-300 text-sm">Akses Publik Tanpa Login</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Halaman pencarian kode klasifikasi juga dapat diakses oleh masyarakat umum melalui tautan <strong>"Kode Klasifikasi"</strong> di landing page (halaman beranda sebelum login). Pengguna tidak perlu login untuk mencari kode JRA.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-content-klasifikasi-crud" class="tab-content space-y-6" style="display:none">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Tambah &amp; Edit Kode Klasifikasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-blue-50 dark:bg-blue-950/30 p-5 rounded-xl border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-blue-900 dark:text-blue-300 text-sm">Menambah Kode Baru</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Klik tombol <strong>Tambah</strong> untuk membuka form input. Isi kode klasifikasi (format hierarki: XXX, XXX.XX, atau XXX.XX.XX), nama klasifikasi, uraian, masa retensi aktif dan inaktif, nasib akhir, unit pengolah, dan tingkat keamanan SKKAAD.</p>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-950/30 p-5 rounded-xl border border-amber-200 dark:border-amber-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-amber-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-amber-900 dark:text-amber-300 text-sm">SKKAAD Lampung</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Setiap kode klasifikasi dilengkapi informasi SKKAAD (Sistem Klasifikasi Keamanan dan Akses Arsip Dinamis) sesuai Pergub Lampung. Atur tingkat keamanan (biasa/terbatas/rahasia) dan hak akses untuk setiap kode.</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-green-50 dark:bg-green-950/30 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-green-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-green-900 dark:text-green-300 text-sm">Edit &amp; Hapus</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Gunakan ikon <strong>pensil</strong> untuk mengedit kode yang sudah ada. Ikon <strong>tempat sampah</strong> untuk menghapus. Setiap perubahan tercatat di Audit Log. <span class="text-red-600 dark:text-red-400">Periksa apakah kode masih digunakan oleh data surat sebelum menghapus.</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-content-klasifikasi-struktur" class="tab-content space-y-6" style="display:none">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Memahami Struktur Kode JRA</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-purple-50 dark:bg-purple-950/30 p-5 rounded-xl border border-purple-200 dark:border-purple-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-purple-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-purple-900 dark:text-purple-300 text-sm">Hierarki 3 Tingkat</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Kode klasifikasi JRA menggunakan format hierarki bertingkat. Semakin panjang kode, semakin spesifik jenis arsipnya:</p>
                            <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1 mt-2 list-disc pl-4">
                                <li><strong>Tingkat 1</strong> (XXX) &ndash; Kategori umum, contoh: <code class="text-[10px] font-mono">500</code> (Urusan Pemerintahan)</li>
                                <li><strong>Tingkat 2</strong> (XXX.XX) &ndash; Sub-kategori, contoh: <code class="text-[10px] font-mono">500.11</code> (Perhubungan)</li>
                                <li><strong>Tingkat 3</strong> (XXX.XX.XX) &ndash; Jenis spesifik, contoh: <code class="text-[10px] font-mono">500.11.13</code> (Pemasangan LPJU)</li>
                            </ul>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-rose-50 dark:bg-rose-950/30 p-5 rounded-xl border border-rose-200 dark:border-rose-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-rose-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-rose-900 dark:text-rose-300 text-sm">JRA &amp; Nasib Akhir</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Jadwal Retensi Arsip (JRA) menentukan berapa lama arsip harus disimpan: <strong>Masa Aktif</strong> (di unit kerja) dan <strong>Masa Inaktif</strong> (di pusat arsip). Setelah masa retensi habis, nasib akhir arsip bisa:</p>
                            <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1 mt-2 list-disc pl-4">
                                <li><strong>Musnah</strong> &ndash; Dimusnahkan karena sudah tidak bernilai guna</li>
                                <li><strong>Permanen</strong> &ndash; Disimpan selamanya sebagai arsip statis</li>
                                <li><strong>Dinilai</strong> &ndash; Perlu dinilai ulang oleh tim penilai arsip</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-4">Visualisasi Hierarki Kode JRA:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gradient-to-b from-purple-50 to-white dark:from-purple-950/30 dark:to-slate-800 border border-purple-200 dark:border-purple-800 rounded-xl p-5 text-center">
                            <div class="bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 font-mono text-xl font-bold p-4 rounded-xl mb-3 shadow-sm">500</div>
                            <div class="text-xs font-bold text-purple-800 dark:text-purple-300 uppercase tracking-wider">Tingkat 1</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Urusan Pemerintahan</div>
                            <div class="mt-3 text-[10px] text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-950/50 rounded-lg px-2 py-1">Kategori Umum</div>
                        </div>
                        <div class="bg-gradient-to-b from-purple-50 to-white dark:from-purple-950/30 dark:to-slate-800 border border-purple-200 dark:border-purple-800 rounded-xl p-5 text-center">
                            <div class="bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 font-mono text-xl font-bold p-4 rounded-xl mb-3 shadow-sm">500.11</div>
                            <div class="text-xs font-bold text-purple-800 dark:text-purple-300 uppercase tracking-wider">Tingkat 2</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Perhubungan</div>
                            <div class="mt-3 text-[10px] text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-950/50 rounded-lg px-2 py-1">Sub-Kategori</div>
                        </div>
                        <div class="bg-gradient-to-b from-purple-50 to-white dark:from-purple-950/30 dark:to-slate-800 border border-purple-200 dark:border-purple-800 rounded-xl p-5 text-center">
                            <div class="bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 font-mono text-xl font-bold p-4 rounded-xl mb-3 shadow-sm">500.11.13</div>
                            <div class="text-xs font-bold text-purple-800 dark:text-purple-300 uppercase tracking-wider">Tingkat 3</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Pemasangan LPJU</div>
                            <div class="mt-3 text-[10px] text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-950/50 rounded-lg px-2 py-1">Jenis Spesifik</div>
                        </div>
                    </div>
                </div>
            </div>

        </x-card>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- MODUL: GENERATE KP4 --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    <div id="mod-content-kp4" class="mod-content" style="display:none">
        <x-card class="min-h-[400px] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">

            <div id="tab-content-kp4-isi" class="tab-content space-y-6">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Mengisi Form KP4</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-blue-50 dark:bg-blue-950/30 p-5 rounded-xl border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-blue-900 dark:text-blue-300 text-sm">Buka Halaman Generate KP4</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Menu <strong>Generate KP4</strong> berada di seksi <strong>Kearsipan Dinamis (SIKD)</strong> pada sidebar. KP4 adalah Surat Keterangan Tunjangan Keluarga bagi PNS. Halaman ini menyediakan satu form lengkap untuk seluruh data yang dibutuhkan.</p>
                        </div>
                        <div class="bg-teal-50 dark:bg-teal-950/30 p-5 rounded-xl border border-teal-200 dark:border-teal-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-teal-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-teal-900 dark:text-teal-300 text-sm">Data Diri &amp; Pekerjaan</h4></div>
                            <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1 mt-2 list-disc pl-4">
                                <li><strong>Data Diri</strong> &ndash; Nama, NIP, tempat/tanggal lahir, jenis kelamin, agama, kebangsaan</li>
                                <li><strong>Pangkat &amp; Jabatan</strong> &ndash; Pangkat/golongan, jabatan, instansi, masa kerja</li>
                                <li><strong>Gaji</strong> &ndash; Gaji pokok per PP (Peraturan Pemerintah) yang berlaku</li>
                                <li><strong>Pekerjaan Sampingan</strong> &ndash; Bersifat opsional; isi hanya jika ada</li>
                            </ul>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-purple-50 dark:bg-purple-950/30 p-5 rounded-xl border border-purple-200 dark:border-purple-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-purple-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-purple-900 dark:text-purple-300 text-sm">Pasangan</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Isi data pasangan (Istri/Suami) meliputi nama, tanggal lahir, tanggal kawin, dan pekerjaan. Label <strong>Istri/Suami</strong> di dokumen terisi otomatis sesuai jenis kelamin pegawai yang dipilih di form.</p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-950/30 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-green-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">4</span><h4 class="font-bold text-green-900 dark:text-green-300 text-sm">Data Anak (Dinamis)</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Tabel anak tersedia dalam dua bagian: <strong>Anak dalam Daftar Gaji</strong> dan <strong>Anak Luar Daftar Gaji</strong>. Gunakan tombol <strong>+ Tambah Baris</strong> untuk menambah anak dan ikon tempat sampah untuk menghapus baris.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-content-kp4-hasil" class="tab-content space-y-6" style="display:none">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Hasil Dokumen KP4</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-emerald-50 dark:bg-emerald-950/30 p-5 rounded-xl border border-emerald-200 dark:border-emerald-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-emerald-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-emerald-900 dark:text-emerald-300 text-sm">Format .docx Otomatis</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Klik tombol <strong>Generate Dokumen</strong> di bagian bawah form. Sistem akan mengisi otomatis semua field ke dalam template resmi KP4 dan mengunduh hasilnya dalam format <strong>.docx</strong> yang siap diedit atau dicetak.</p>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-950/30 p-5 rounded-xl border border-amber-200 dark:border-amber-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-amber-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-amber-900 dark:text-amber-300 text-sm">Formatting Dipertahankan</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Sistem mempertahankan format tebal (bold), jenis huruf, dan ukuran font pada template. Data angka anak dikonversi otomatis ke <strong>terbilang</strong> (number-to-words). Tempat &amp; tanggal ditetapkan otomatis: <strong>Bandar Lampung</strong> + tanggal hari ini.</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-cyan-50 dark:bg-cyan-950/30 p-5 rounded-xl border border-cyan-200 dark:border-cyan-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-cyan-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-cyan-900 dark:text-cyan-300 text-sm">Nama File Hasil</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">File yang diunduh diberi nama mengikuti format yang jelas, contoh: <code class="text-[10px] font-mono bg-white dark:bg-slate-800 px-1 rounded border border-slate-200 dark:border-slate-700">KP4_[Nama Pegawai].docx</code>, sehingga mudah dikenali saat disimpan.</p>
                        </div>
                    </div>
                </div>
            </div>

        </x-card>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- MODUL: MANAJEMEN TEMPLATE --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    <div id="mod-content-template" class="mod-content" style="display:none">
        <x-card class="min-h-[400px] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">

            <div id="tab-content-template-unduh" class="tab-content space-y-6">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Mengunduh Template Dokumen</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-blue-50 dark:bg-blue-950/30 p-5 rounded-xl border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-blue-900 dark:text-blue-300 text-sm">Jenis Template Tersedia</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Halaman Manajemen Template menampilkan katalog naskah dinas resmi Dishub Lampung dalam format <strong>.docx</strong> dan <strong>.pdf</strong> (pratinjau). Template tersusun berdasarkan kategori:</p>
                            <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1 mt-2 list-disc pl-4">
                                <li><strong>A. Naskah Dinas Penugasan</strong> &ndash; Surat Perintah, Surat Tugas, Perjalanan Dinas</li>
                                <li><strong>B. Naskah Dinas Korespondensi Internal</strong> &ndash; Nota Dinas, Memo, Disposisi</li>
                                <li><strong>C. Naskah Dinas Korespondensi Eksternal</strong> &ndash; Surat Dinas Eksternal</li>
                                <li><strong>D. Naskah Dinas Khusus</strong> &ndash; Surat Edaran, Kuasa, Berita Acara, Keterangan, Pengantar, Pengumuman, Laporan, Telaahan Staf, Notula, Undangan, Panggilan, Izin, Rekomendasi, dll.</li>
                            </ul>
                        </div>
                        <div class="bg-teal-50 dark:bg-teal-950/30 p-5 rounded-xl border border-teal-200 dark:border-teal-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-teal-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-teal-900 dark:text-teal-300 text-sm">Download &amp; Pratinjau</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Setiap template dilengkapi tombol <strong>Download</strong> untuk mengunduh file .docx (dapat diedit di Word/LibreOffice/Google Docs) dan pratinjau PDF untuk melihat tampilan tanpa mengunduh.</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-amber-50 dark:bg-amber-950/30 p-5 rounded-xl border border-amber-200 dark:border-amber-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-amber-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-amber-900 dark:text-amber-300 text-sm">Upload Template Baru</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Pengguna dengan role <strong>Super Admin</strong> dapat menambahkan template baru. Klik tombol <strong>Tambah</strong>, isi nama template, pilih kategori &amp; sub-kategori, upload file .docx dan (opsional) file PDF pratinjau, serta link Google Drive. Template aktif akan langsung muncul di katalog <strong>Tata Naskah Dinas</strong> yang dapat diakses publik tanpa login.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-content-template-guna" class="tab-content space-y-6" style="display:none">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Menggunakan Template</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-green-50 dark:bg-green-950/30 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-green-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-green-900 dark:text-green-300 text-sm">Buka di Word Processor</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Setelah template diunduh, buka file .docx menggunakan Microsoft Word, LibreOffice Writer, atau Google Docs. Template sudah dilengkapi dengan kop surat, header, dan footer resmi Dishub Provinsi Lampung.</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-950/30 p-5 rounded-xl border border-purple-200 dark:border-purple-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-purple-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-purple-900 dark:text-purple-300 text-sm">Isi Placeholder</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Template dilengkapi placeholder yang ditandai dengan kurung siku, contoh: <code class="text-[10px] font-mono bg-white dark:bg-slate-800 px-1 rounded">[Nama_Pegawai]</code>, <code class="text-[10px] font-mono bg-white dark:bg-slate-800 px-1 rounded">[Nomor_Surat]</code>, <code class="text-[10px] font-mono bg-white dark:bg-slate-800 px-1 rounded">[Tanggal]</code>. Ganti semua placeholder dengan data yang sesuai.</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-cyan-50 dark:bg-cyan-950/30 p-5 rounded-xl border border-cyan-200 dark:border-cyan-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-cyan-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-cyan-900 dark:text-cyan-300 text-sm">Export ke PDF</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Setelah mengisi data, Anda dapat mengekspor dokumen ke PDF untuk keperluan distribusi dan pengarsipan. Gunakan fitur "Export to PDF" di Word/LibreOffice, atau gunakan modul <strong>Utilitas PDF</strong> untuk konversi langsung melalui sistem.</p>
                        </div>
                    </div>
                </div>
            </div>

        </x-card>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- MODUL: UTILITAS PDF --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    <div id="mod-content-pdf-utility" class="mod-content" style="display:none">
        <x-card class="min-h-[400px] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">

            <div id="tab-content-pdf-utility-word" class="tab-content space-y-6">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Konversi Word ke PDF</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-blue-50 dark:bg-blue-950/30 p-5 rounded-xl border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-blue-900 dark:text-blue-300 text-sm">Upload File .docx</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Buka halaman Utilitas PDF, pilih tab <strong>Word ke PDF</strong>. Klik tombol upload dan pilih file Microsoft Word (.docx) dari komputer Anda. Sistem akan memproses file dan mengonversinya ke format PDF dengan mempertahankan tata letak asli.</p>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-950/30 p-5 rounded-xl border border-amber-200 dark:border-amber-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-amber-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-amber-900 dark:text-amber-300 text-sm">Batas Ukuran File</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">File .docx yang diupload maksimal berukuran <strong>10 MB</strong>. Hindari menyisipkan gambar beresolusi sangat tinggi ke dalam dokumen agar proses konversi berjalan cepat dan hasil PDF tidak membengkak.</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-green-50 dark:bg-green-950/30 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-green-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-green-900 dark:text-green-300 text-sm">Hasil Konversi</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Setelah konversi selesai, pratinjau PDF akan langsung ditampilkan di browser. Anda dapat mengklik <strong>Unduh</strong> untuk menyimpan file PDF, atau <strong>Lihat</strong> untuk membuka di tab baru. File hasil konversi bisa langsung digunakan untuk keperluan pengarsipan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-content-pdf-utility-gambar" class="tab-content space-y-6" style="display:none">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Konversi Gambar ke PDF</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-indigo-50 dark:bg-indigo-950/30 p-5 rounded-xl border border-indigo-200 dark:border-indigo-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-indigo-900 dark:text-indigo-300 text-sm">Upload Gambar</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Pilih tab <strong>Gambar ke PDF</strong>, upload file gambar (format JPG, PNG, atau JPEG). Sistem akan secara otomatis mengatur ukuran dan posisi gambar agar pas dalam halaman PDF orientasi portrait tanpa terpotong.</p>
                        </div>
                        <div class="bg-teal-50 dark:bg-teal-950/30 p-5 rounded-xl border border-teal-200 dark:border-teal-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-teal-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-teal-900 dark:text-teal-300 text-sm">Multi-Halaman</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Anda dapat menggabungkan beberapa gambar menjadi satu file PDF. Upload gambar satu per satu secara berurutan &mdash; setiap gambar akan menjadi halaman terpisah dalam dokumen PDF yang sama.</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-rose-50 dark:bg-rose-950/30 p-5 rounded-xl border border-rose-200 dark:border-rose-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-rose-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-rose-900 dark:text-rose-300 text-sm">Kualitas Hasil</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Gambar dikonversi dengan kualitas asli tanpa kompresi berlebih. Untuk hasil optimal, gunakan gambar dengan resolusi minimal <strong>150 DPI</strong> dan rasio aspek yang mendekati kertas A4 (210mm x 297mm).</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-content-pdf-utility-gabung" class="tab-content space-y-6" style="display:none">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Menggabungkan Beberapa PDF</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-cyan-50 dark:bg-cyan-950/30 p-5 rounded-xl border border-cyan-200 dark:border-cyan-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-cyan-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-cyan-900 dark:text-cyan-300 text-sm">Upload Beberapa File PDF</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Pilih tab <strong>Gabung PDF</strong>, lalu upload lebih dari satu file PDF (maksimal <strong>10 MB</strong> per file). File dapat dipilih sekaligus secara bersamaan dari komputer Anda.</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-950/30 p-5 rounded-xl border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-blue-900 dark:text-blue-300 text-sm">Urutan Sesuai Pilihan</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Halaman penggabungan mengikuti urutan file yang Anda pilih. File pertama akan menjadi halaman pertama dokumen gabungan. Periksa kembali urutannya sebelum proses dilakukan.</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-green-50 dark:bg-green-950/30 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-green-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-green-900 dark:text-green-300 text-sm">Hasil Gabungan</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Klik <strong>Gabungkan</strong> untuk memproses. Hasilnya satu file PDF berisi gabungan seluruh halaman yang dapat diunduh langsung. Fitur ini berguna untuk menggabungkan scan surat multi-halaman yang terpisah menjadi satu dokumen.</p>
                        </div>
                    </div>
                </div>
            </div>

        </x-card>
    </div>
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    <div id="mod-content-pengguna" class="mod-content" style="display:none">
        <x-card class="min-h-[400px] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">

            <div id="tab-content-pengguna-tambah" class="tab-content space-y-6">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Menambah Pengguna Baru</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-blue-50 dark:bg-blue-950/30 p-5 rounded-xl border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-blue-900 dark:text-blue-300 text-sm">Akses Khusus Super Admin</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Halaman Manajemen Pengguna hanya dapat diakses oleh pengguna dengan role <strong>Super Admin</strong>. Menu ini berada di bagian <strong>Administrasi</strong> pada sidebar dan tidak akan muncul untuk role lainnya.</p>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-950/30 p-5 rounded-xl border border-amber-200 dark:border-amber-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-amber-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-amber-900 dark:text-amber-300 text-sm">Form Pendaftaran</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Klik tombol <strong>Tambah Pengguna</strong>. Isi formulir:</p>
                            <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1 mt-2 list-disc pl-4">
                                <li><strong>Nama Lengkap</strong> &ndash; Nama sesuai identitas</li>
                                <li><strong>Username</strong> &ndash; Nama pengguna untuk login (disimpan di kolom email database)</li>
                                <li><strong>Password</strong> &ndash; Minimal 8 karakter</li>
                                <li><strong>Role</strong> &ndash; Pilih salah satu role yang tersedia</li>
                            </ul>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-green-50 dark:bg-green-950/30 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-green-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-green-900 dark:text-green-300 text-sm">Pilihan Role</h4></div>
                            <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1.5 list-disc pl-4">
                                <li><strong>Super Admin</strong> &ndash; Akses penuh ke semua modul, termasuk manajemen pengguna dan audit log</li>
                                <li><strong>Kepala Dinas</strong> &ndash; Dashboard dan laporan, view semua data</li>
                                <li><strong>Kepala Bidang</strong> &ndash; Surat di bidangnya masing-masing</li>
                                <li><strong>Tata Usaha</strong> &ndash; Operasional surat menyurat dan SIKD</li>
                                <li><strong>Staff</strong> &ndash; Input surat dan melihat data</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-content-pengguna-edit" class="tab-content space-y-6" style="display:none">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Mengedit &amp; Menghapus Pengguna</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-cyan-50 dark:bg-cyan-950/30 p-5 rounded-xl border border-cyan-200 dark:border-cyan-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-cyan-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-cyan-900 dark:text-cyan-300 text-sm">Edit Data Pengguna</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Klik ikon <strong>pensil</strong> pada baris pengguna yang ingin diedit. Anda dapat mengubah Nama, Username, Password (kosongkan jika tidak ingin mengubah), dan Role. Klik Simpan untuk menyimpan perubahan.</p>
                        </div>
                        <div class="bg-red-50 dark:bg-red-950/30 p-5 rounded-xl border border-red-200 dark:border-red-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-red-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-red-900 dark:text-red-300 text-sm">Hapus Pengguna</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Klik ikon <strong>tempat sampah</strong> untuk menghapus pengguna. Akan muncul dialog konfirmasi sebelum penghapusan dilakukan. <span class="text-red-600 dark:text-red-400 font-bold">Perhatian: Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan.</span></p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-purple-50 dark:bg-purple-950/30 p-5 rounded-xl border border-purple-200 dark:border-purple-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-purple-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-purple-900 dark:text-purple-300 text-sm">Pencarian &amp; Filter</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Tabel daftar pengguna dilengkapi kolom pencarian untuk mencari berdasarkan <strong>Nama</strong> atau <strong>Username</strong>. Filter berdasarkan role juga tersedia untuk memudahkan pengelolaan banyak pengguna.</p>
                        </div>
                    </div>
                </div>
            </div>

        </x-card>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- MODUL: AUDIT LOG --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    <div id="mod-content-audit-log" class="mod-content" style="display:none">
        <x-card class="min-h-[400px] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">

            <div id="tab-content-audit-log-lihat" class="tab-content space-y-6">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Melihat Riwayat Audit Log</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-slate-100 dark:bg-slate-800/60 p-5 rounded-xl border border-slate-300 dark:border-slate-700">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-slate-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm">Pencatatan Otomatis</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Sistem secara otomatis mencatat setiap aksi <strong>Create</strong> (tambah), <strong>Update</strong> (ubah), dan <strong>Delete</strong> (hapus) yang dilakukan pada modul Surat Masuk, Surat Keluar, dan Manajemen Pengguna. Tidak perlu mengaktifkan pencatatan secara manual.</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-950/30 p-5 rounded-xl border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-blue-900 dark:text-blue-300 text-sm">Informasi yang Tercatat</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Setiap log mencatat informasi detail:</p>
                            <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1 mt-2 list-disc pl-4">
                                <li><strong>Pengguna</strong> &ndash; Nama pengguna yang melakukan aksi</li>
                                <li><strong>Aksi</strong> &ndash; Create, Update, atau Delete</li>
                                <li><strong>Tabel</strong> &ndash; Modul tempat aksi dilakukan</li>
                                <li><strong>Data Lama</strong> &ndash; Kondisi data sebelum perubahan (JSON)</li>
                                <li><strong>Data Baru</strong> &ndash; Kondisi data setelah perubahan (JSON)</li>
                                <li><strong>Waktu</strong> &ndash; Timestamp lengkap</li>
                            </ul>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-amber-50 dark:bg-amber-950/30 p-5 rounded-xl border border-amber-200 dark:border-amber-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-amber-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-amber-900 dark:text-amber-300 text-sm">Akses Super Admin</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Halaman Audit Log hanya dapat diakses oleh role <strong>Super Admin</strong>. Menu ini berada di bagian <strong>Administrasi</strong> pada sidebar dan tidak terlihat oleh role lainnya. Data log bersifat read-only (tidak bisa diedit atau dihapus).</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-content-audit-log-cari" class="tab-content space-y-6" style="display:none">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2">Mencari &amp; Memahami Audit Log</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-indigo-50 dark:bg-indigo-950/30 p-5 rounded-xl border border-indigo-200 dark:border-indigo-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span><h4 class="font-bold text-indigo-900 dark:text-indigo-300 text-sm">Pencarian Log</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Gunakan kolom pencarian untuk mencari log berdasarkan <strong>nama pengguna</strong>, <strong>jenis aksi</strong> (create/update/delete), atau <strong>nama tabel</strong>. Filter waktu juga tersedia untuk membatasi pencarian dalam periode tertentu.</p>
                        </div>
                        <div class="bg-teal-50 dark:bg-teal-950/30 p-5 rounded-xl border border-teal-200 dark:border-teal-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-teal-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span><h4 class="font-bold text-teal-900 dark:text-teal-300 text-sm">Memahami Data JSON</h4></div>
                            <ul class="text-xs text-slate-600 dark:text-slate-400 space-y-1.5 list-disc pl-4">
                                <li><strong>Create</strong> &ndash; Data Lama kosong, Data Baru berisi data yang baru dibuat</li>
                                <li><strong>Update</strong> &ndash; Data Lama berisi kondisi sebelum, Data Baru berisi kondisi setelah</li>
                                <li><strong>Delete</strong> &ndash; Data Lama berisi data yang dihapus, Data Baru kosong</li>
                                <li>JSON format bisa dibaca: <code class="text-[10px] font-mono bg-white dark:bg-slate-800 px-1 rounded">{"kolom":"nilai"}</code></li>
                            </ul>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-green-50 dark:bg-green-950/30 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <div class="flex items-center gap-2 mb-3"><span class="bg-green-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span><h4 class="font-bold text-green-900 dark:text-green-300 text-sm">Audit Trail untuk Akuntabilitas</h4></div>
                            <p class="text-xs text-slate-700 dark:text-slate-400 leading-relaxed">Fitur Audit Log memenuhi standar akuntabilitas kearsipan. Setiap perubahan data dapat dilacak: <strong>siapa</strong> yang melakukannya, <strong>kapan</strong>, <strong>apa</strong> yang berubah. Data log bersifat <strong>read-only</strong> &mdash; tidak dapat diedit atau dihapus oleh siapapun, termasuk Super Admin &mdash; untuk menjaga integritas.</p>
                        </div>
                    </div>
                </div>
            </div>

        </x-card>
    </div>

</div>

<style>
    .tab-btn, .mod-btn {
        background-color: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
    }
    .tab-btn:hover, .mod-btn:hover {
        background-color: #e2e8f0;
        color: #1e293b;
    }
    .dark .tab-btn, .dark .mod-btn {
        background-color: #1e293b;
        color: #94a3b8;
        border-color: #334155;
    }
    .dark .tab-btn:hover, .dark .mod-btn:hover {
        background-color: #334155;
        color: #f1f5f9;
    }
    .tab-btn.active, .mod-btn.active {
        background-color: #1A365D !important;
        color: #ffffff !important;
        border-color: #1A365D !important;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.15), 0 2px 4px -1px rgba(0,0,0,0.08);
    }
    .dark .tab-btn.active, .dark .mod-btn.active {
        background-color: #3b82f6 !important;
        color: #ffffff !important;
        border-color: #3b82f6 !important;
    }
</style>

<script>
var currentMod = 'surat-menyurat';
var currentTab = 'tambah';

function switchModule(mod) {
    document.querySelectorAll('.mod-btn').forEach(function(b) { b.classList.remove('active'); });
    document.getElementById('mod-btn-' + mod).classList.add('active');
    document.querySelectorAll('.mod-content').forEach(function(e) { e.style.display = 'none'; });
    document.getElementById('mod-content-' + mod).style.display = '';
    document.querySelectorAll('.mod-tabs').forEach(function(e) { e.style.display = 'none'; });
    document.getElementById('tabs-' + mod).style.display = 'flex';
    currentMod = mod;
    var first = document.querySelector('#tabs-' + mod + ' .tab-btn');
    if (first) switchTab(mod, first.id.replace('tab-btn-' + mod + '-', ''));
}

function switchTab(mod, tab) {
    currentTab = tab;
    document.querySelectorAll('#tabs-' + mod + ' .tab-btn').forEach(function(b) { b.classList.remove('active'); });
    document.getElementById('tab-btn-' + mod + '-' + tab).classList.add('active');
    document.querySelectorAll('#mod-content-' + mod + ' .tab-content').forEach(function(e) { e.style.display = 'none'; });
    document.getElementById('tab-content-' + mod + '-' + tab).style.display = '';
}
</script>

@endsection
