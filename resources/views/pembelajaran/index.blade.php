@extends('layouts.app')

@section('title', 'Panduan Pengguna Baru – SISIPIN DISHUB')

@section('page-title', 'Panduan Pengguna Baru')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Glossary Section --}}
    <section class="mb-12">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 mb-6">
            <h2 class="text-xl font-semibold text-primary-dark dark:text-primary-light mb-4">Istilah Kearsipan</h2>
            <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4">
               Berikut istilah singkat yang sering digunakan di SISIPIN DISHUB:
            </p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="font-medium text-primary-dark dark:text-primary-light">Disposisi</p>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Alur surat dari penerima keangkat ke penandatangan dan seterusnya.</p>
                </div>
                <div>
                    <p class="font-medium text-primary-dark dark:text-primary-light">Klasifikasi</p>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Pengelompokan surat berdasarkan kode dan tingkat kepentingan.</p>
                </div>
                <div>
                    <p class="font-medium text-primary-dark dark:text-primary-light">SKKAAD</p>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Surat Keterangan Keputusan Atasan Atasan Daerah.</p>
                </div>
                <div>
                    <p class="font-medium text-primary-dark dark:text-primary-light">Retensi</p>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Aturan bertapa penampungan dan pemusnahan surat setelah waktu tertentu.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Role-Based Guides --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        {{-- Super Admin Guide --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-start gap-3">
                <div class="w-12 h-12 rounded-xl bg-red-600/10 flex items-center justify-center flex-shrink-0">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12c2-3 5-3 7 0s5 3 7 0s5-3 7 0 3 5 0 3-5 0-7zM8 4c2 0 4 2 4 4v4h4v-4c0-2-2-4-4-4zm8 8c-2 0-4 2-4 4v4h4v-4c0-2-2-4-4-4zm-9-5c-2 0-4 2-4 4v2h4v-2c0-2 2-4 4-4s4 2 4-2c0-3.87-3.13-7-7-7z"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-primary-dark dark:text-primary-light">Super Admin</h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Memiliki akses penuh ke seluruh sistem.</p>
                </div>
            </div>
            <ol class="list-decimal list-inside space-y-4">
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Tambah Surat Masuk</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Menu: Kearsipan → Surat Masuk → Tambah</p>
                    </div>
                </li>
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Lakukan Disposisi</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Menu: Kearsipan → Disposisi → Pilih surat → Tindak lanjut</p>
                    </div>
                </li>
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Search & Ekspor</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Gunakan filter tanggal + ekspor ke PDF/Excel</p>
                    </div>
                </li>
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Tambah User</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Menu: Admin → User Management → Tambah pengguna + peran</p>
                    </div>
                </li>
                <li class="text-sm text-red-600 dark:text-red-400">
                    Kalau macet: hubungi Tim Kearsipat (internal) atau support@dishub.lampung.go.id
                </li>
            </ol>
        </div>

        {{-- Tata Usaha Guide --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-start gap-3">
                <div class="w-12 h-12 rounded-xl bg-orange-600/10 flex items-center justify-center flex-shrink-0">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 6v6l4 4v6H6zm5.5-4.5c0-1.05-.85-2-2-2S10 4.95 10 6c0 1.05.85 2 2 2s2-.95 2-2c0-93 53-119 89-119c16 0 33 12 33 33c0 5.5-2 9.5-5.5 11.5L17.5 15.5c.03-.2.06-.4.06-.6z"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-primary-dark dark:text-primary-light">Tata Usaha</h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Bertanggung jawab atas pelaksanaan kearsipan harian.</p>
                </div>
            </div>
            <ol class="list-decimal list-inside space-y-4">
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Tambah Surat Masuk</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Menu: Kearsipan → Surat Masuk → Isi form + unggah berkas</p>
                    </div>
                </li>
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Lakukan Disposisi</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Menu: Kearsipan → Disposisi → Pilih surat → Setujui/ Tolak</p>
                    </div>
                </li>
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Cari Surat</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Gunakan filter bidang + tanggal + kolom pencarian</p>
                    </div>
                </li>
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Lihat Laporan</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Ekspor ke Excel untuk reporting bulanan</p>
                    </div>
                </li>
                <li class="text-sm text-red-600 dark:text-red-400">
                    Kalau macet: hubungi Kepala Bidang atau user management admin
                </li>
            </ol>
        </div>

        {{-- Pegawai Guide --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-start gap-3">
                <div class="w-12 h-12 rounded-xl bg-yellow-600/10 flex items-center justify-center flex-shrink-0">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v2m0 12v2m0 0h2m-2 0h-2m0 0l7.5 7.5L19 21l-3.5-3.5L14 17l5.5-5.5L9 12l-2.5 2.5L3 7l5.5-5.5L3.5 3.5 9 2.5l2.5 2.5zm0 0L8 8"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-primary-dark dark:text-primary-light">Pegawai</h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Menyalurkan surat dan menginput data kearsipan.</p>
                </div>
            </div>
            <ol class="list-decimal list-inside space-y-4">
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Input Surat Masuk</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Menu: Kearsipan → Surat Masuk → Isi data dasar surat</p>
                    </div>
                </li>
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Lihat Disposisi</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Menu: Kearsipan → Masuk → Lihat status disposisi</p>
                    </div>
                </li>
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Cari Surat</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Gunakan kolom kode/nomor/tahun untuk pencarian</p>
                    </div>
                </li>
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Export Data</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Export ringkasan surat untuk laporan sendiri</p>
                    </div>
                </li>
                <li class="text-sm text-red-600 dark:text-red-400">
                    Kalau macet: tanyakan ke supervisor atau ketik di kolom bantu AI
                </li>
            </ol>
        </div>

        {{-- Kepala Bidang Guide --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-start gap-3">
                <div class="w-12 h-12 rounded-xl bg-green-600/10 flex items-center justify-center flex-shrink-0">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v2m0 12v2m0 0h2m-2 0h-2m0 0l7.5 7.5L19 21l-3.5-3.5L14 17l5.5-5.5L9 12l-2.5 2.5L3 7l5.5-5.5L3.5 3.5 9 2.5l2.5 2.5zm0 0L8 8"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-primary-dark dark:text-primary-light">Kepala Bidang</h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Mendorong implementasi kearsipan dan memastikan KPI terpenuhi.</p>
                </div>
            </div>
            <ol class="list-decimal list-inside space-y-4">
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Validasi Disposisi</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Review surat sebelum dikirim ke atasan atas</p>
                    </div>
                </li>
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Atur Retensi</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Set waktu simpan + pemusnahan sesuai SKKAAD</p>
                    </div>
                </li>
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Monitor Kinerja</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Lihat dashboard statistik + target kearsipan</p>
                    </div>
                </li>
                <li>
                    <p class="font-medium text-gray-700 dark:text-gray-300">Laporan Bulanan</p>
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Ekspor seluruh data + kirim ke atasan tingkat daerah</p>
                    </div>
                </li>
                <li class="text-sm text-red-600 dark:text-red-400">
                    Kalau macet: koordinasi dengan Tim Kearsipat tingkat Provinsi
                </li>
            </ol>
        </div>
    </div>

</div>

@endsection