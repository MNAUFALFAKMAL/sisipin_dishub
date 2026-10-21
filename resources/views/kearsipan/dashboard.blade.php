@extends('layouts.app')
@section('title', 'Dashboard — SISIPIN DISHUB (Sistem Informasi Arsip Internal Dishub Provinsi Lampung)')
@section('page-title', 'Dashboard')

@section('content')
<div class="flex flex-col gap-5">

    {{-- GREETING BANNER --}}
    <div class="rounded-xl overflow-hidden bg-[#0F1C3F] bg-linear-to-br from-[#0F1C3F] via-[#1A365D] to-[#1e4d8c] p-6 md:p-7 relative">
        <div class="absolute inset-0" style="background-image:radial-gradient(rgba(255,255,255,.06) 1px,transparent 1px);background-size:22px 22px;pointer-events:none"></div>
        <div class="absolute -right-10 -top-10 w-[180px] h-[180px] rounded-full" style="background:rgba(255,255,255,.04);pointer-events:none"></div>

        <div class="relative z-10 flex items-center justify-between flex-wrap gap-3">
            <div>
                <p class="m-0 mb-1 text-xs text-blue-200 tracking-wider uppercase font-semibold">
                    Sistem Informasi Arsip Internal
                </p>
                <h2 class="m-0 mb-1.5 text-xl md:text-2xl font-extrabold text-white">
                    Selamat datang, {{ Auth::user()->name }} 👋
                </h2>
                <p class="m-0 text-sm text-blue-200">
                    Dinas Perhubungan Provinsi Lampung · Modul SIKD Kearsipan Dinamis
                </p>
            </div>
            <div class="text-right">
                <p class="m-0 text-sm" style="color:rgba(255,255,255,.8)">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <p class="mt-1 text-xs" style="color:rgba(255,255,255,.5)">WIB</p>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         STAT CARDS — SURAT
    ═══════════════════════════════════════════ --}}
    <div>
        <h3 class="m-0 mb-2.5 text-xs font-bold tracking-wide uppercase text-slate-500 dark:text-slate-400">
            Statistik Surat Menyurat
        </h3>
        <div class="stat-grid-5">
            {{-- Surat Masuk --}}
            <a href="{{ route('surat-masuk.index', ['jenis_surat'=>'Surat Masuk']) }}" class="stat-card bg-white dark:bg-slate-800 rounded-xl p-4 border border-gray-200 dark:border-slate-700 no-underline text-inherit">
                <div class="flex items-center justify-between">
                    <div class="w-[42px] h-[42px] rounded-xl flex items-center justify-center shrink-0 bg-blue-100 dark:bg-blue-900/40">
                        <svg width="20" height="20" fill="none" stroke-width="2" viewBox="0 0 24 24" class="stroke-blue-700 dark:stroke-blue-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="stat-number">{{ $suratStats['surat_masuk'] }}</div>
                    <div class="stat-label">Surat Masuk</div>
                </div>
            </a>

            {{-- Undangan Masuk --}}
            <a href="{{ route('surat-masuk.index', ['jenis_surat'=>'Undangan Masuk']) }}" class="stat-card bg-white dark:bg-slate-800 rounded-xl p-4 border border-gray-200 dark:border-slate-700 no-underline text-inherit">
                <div class="flex items-center justify-between">
                    <div class="w-[42px] h-[42px] rounded-xl flex items-center justify-center shrink-0 bg-sky-100 dark:bg-sky-900/40">
                        <svg width="20" height="20" fill="none" stroke-width="2" viewBox="0 0 24 24" class="stroke-sky-700 dark:stroke-sky-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="stat-number">{{ $suratStats['undangan_masuk'] }}</div>
                    <div class="stat-label">Undangan Masuk</div>
                </div>
            </a>

            {{-- Surat Keluar --}}
            <a href="{{ route('surat-keluar.index', ['jenis_surat'=>'Surat Keluar']) }}" class="stat-card bg-white dark:bg-slate-800 rounded-xl p-4 border border-gray-200 dark:border-slate-700 no-underline text-inherit">
                <div class="flex items-center justify-between">
                    <div class="w-[42px] h-[42px] rounded-xl flex items-center justify-center shrink-0 bg-emerald-100 dark:bg-emerald-900/40">
                        <svg width="20" height="20" fill="none" stroke-width="2" viewBox="0 0 24 24" class="stroke-emerald-700 dark:stroke-emerald-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="stat-number">{{ $suratStats['surat_keluar'] }}</div>
                    <div class="stat-label">Surat Keluar</div>
                </div>
            </a>

            {{-- Undangan Keluar --}}
            <a href="{{ route('surat-keluar.index', ['jenis_surat'=>'Undangan Keluar']) }}" class="stat-card bg-white dark:bg-slate-800 rounded-xl p-4 border border-gray-200 dark:border-slate-700 no-underline text-inherit">
                <div class="flex items-center justify-between">
                    <div class="w-[42px] h-[42px] rounded-xl flex items-center justify-center shrink-0 bg-green-100 dark:bg-green-900/40">
                        <svg width="20" height="20" fill="none" stroke-width="2" viewBox="0 0 24 24" class="stroke-green-700 dark:stroke-green-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="stat-number">{{ $suratStats['undangan_keluar'] }}</div>
                    <div class="stat-label">Undangan Keluar</div>
                </div>
            </a>

            {{-- SPT --}}
            <a href="{{ route('surat-keluar.index', ['jenis_surat'=>'SPT']) }}" class="stat-card bg-white dark:bg-slate-800 rounded-xl p-4 border border-gray-200 dark:border-slate-700 no-underline text-inherit">
                <div class="flex items-center justify-between">
                    <div class="w-[42px] h-[42px] rounded-xl flex items-center justify-center shrink-0 bg-amber-100 dark:bg-amber-900/40">
                        <svg width="20" height="20" fill="none" stroke-width="2" viewBox="0 0 24 24" class="stroke-amber-700 dark:stroke-amber-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="stat-number">{{ $suratStats['spt'] }}</div>
                    <div class="stat-label">SPT</div>
                </div>
            </a>

            {{-- SPPD --}}
            <a href="{{ route('surat-keluar.index', ['jenis_surat'=>'SPPD']) }}" class="stat-card bg-white dark:bg-slate-800 rounded-xl p-4 border border-gray-200 dark:border-slate-700 no-underline text-inherit">
                <div class="flex items-center justify-between">
                    <div class="w-[42px] h-[42px] rounded-xl flex items-center justify-center shrink-0 bg-violet-100 dark:bg-violet-900/40">
                        <svg width="20" height="20" fill="none" stroke-width="2" viewBox="0 0 24 24" class="stroke-violet-700 dark:stroke-violet-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10h10z M21 16V10l-3-4h-5v10h8z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="stat-number">{{ $suratStats['sppd'] }}</div>
                    <div class="stat-label">SPPD</div>
                </div>
            </a>

            {{-- SK --}}
            <a href="{{ route('surat-keluar.index', ['jenis_surat'=>'SK (Surat Keterangan)']) }}" class="stat-card bg-white dark:bg-slate-800 rounded-xl p-4 border border-gray-200 dark:border-slate-700 no-underline text-inherit">
                <div class="flex items-center justify-between">
                    <div class="w-[42px] h-[42px] rounded-xl flex items-center justify-center shrink-0 bg-fuchsia-100 dark:bg-fuchsia-900/40">
                        <svg width="20" height="20" fill="none" stroke-width="2" viewBox="0 0 24 24" class="stroke-fuchsia-700 dark:stroke-fuchsia-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="stat-number">{{ $suratStats['sk'] }}</div>
                    <div class="stat-label">SK (Surat Keterangan)</div>
                </div>
            </a>

            {{-- Belum Disposisi --}}
            <a href="{{ route('surat-masuk.index', ['status'=>'belum']) }}" class="stat-card bg-white dark:bg-slate-800 rounded-xl p-4 border border-gray-200 dark:border-slate-700 no-underline text-inherit">
                <div class="flex items-center justify-between">
                    <div class="w-[42px] h-[42px] rounded-xl flex items-center justify-center shrink-0 bg-red-100 dark:bg-red-900/40">
                        <svg width="20" height="20" fill="none" stroke-width="2" viewBox="0 0 24 24" class="stroke-red-700 dark:stroke-red-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="stat-number">{{ $suratStats['belum_disposisi'] }}</div>
                    <div class="stat-label">Belum Disposisi</div>
                </div>
            </a>

            {{-- Sedang Diproses --}}
            <a href="{{ route('surat-masuk.index', ['status'=>'diproses']) }}" class="stat-card bg-white dark:bg-slate-800 rounded-xl p-4 border border-gray-200 dark:border-slate-700 no-underline text-inherit">
                <div class="flex items-center justify-between">
                    <div class="w-[42px] h-[42px] rounded-xl flex items-center justify-center shrink-0 bg-purple-100 dark:bg-purple-900/40">
                        <svg width="20" height="20" fill="none" stroke-width="2" viewBox="0 0 24 24" class="stroke-purple-700 dark:stroke-purple-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="stat-number">{{ $suratStats['diproses'] }}</div>
                    <div class="stat-label">Sedang Diproses</div>
                </div>
            </a>
        </div>
    </div>

    {{-- Grafik Tren Surat per Jenis --}}
    <x-card>
        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
            <h3 class="card-heading">Tren Surat per Jenis (6 Bulan Terakhir)</h3>
            <span class="text-[11px] text-slate-400 dark:text-slate-500">Surat Masuk, Undangan, Keluar, SPT, SPPD, SK</span>
        </div>
        <div class="w-full">
            <canvas id="grafikSurat" class="w-full max-w-full" style="height:200px;max-height:200px"
                data-chart="{{ json_encode([
                    'labels' => collect($grafikSurat)->pluck('label'),
                    'datasets' => [
                        ['label' => 'Surat Masuk', 'data' => collect($grafikSurat)->pluck('surat_masuk'), 'backgroundColor' => 'rgba(29,78,216,0.80)', 'borderRadius' => 4],
                        ['label' => 'Undangan Masuk', 'data' => collect($grafikSurat)->pluck('undangan_masuk'), 'backgroundColor' => 'rgba(2,132,199,0.80)', 'borderRadius' => 4],
                        ['label' => 'Surat Keluar', 'data' => collect($grafikSurat)->pluck('surat_keluar'), 'backgroundColor' => 'rgba(5,150,105,0.80)', 'borderRadius' => 4],
                        ['label' => 'SPT', 'data' => collect($grafikSurat)->pluck('spt'), 'backgroundColor' => 'rgba(217,119,6,0.80)', 'borderRadius' => 4],
                        ['label' => 'SPPD', 'data' => collect($grafikSurat)->pluck('sppd'), 'backgroundColor' => 'rgba(124,58,237,0.80)', 'borderRadius' => 4],
                        ['label' => 'SK', 'data' => collect($grafikSurat)->pluck('sk'), 'backgroundColor' => 'rgba(192,132,252,0.80)', 'borderRadius' => 4],
                        ['label' => 'Undangan Keluar', 'data' => collect($grafikSurat)->pluck('undangan_keluar'), 'backgroundColor' => 'rgba(22,163,74,0.80)', 'borderRadius' => 4],
                    ],
                ], JSON_HEX_QUOT) }}">
            </canvas>
        </div>
        </x-card>

    <x-card>
        <div class="flex items-center justify-between mb-3.5">
            <h3 class="card-heading">📥 Ekspor Rekap Semua Surat</h3>
            <span class="text-[11px] text-slate-400 dark:text-slate-500">Format Excel (.xlsx) &amp; PDF (.pdf)</span>
        </div>
        <div class="bg-gray-50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 rounded-xl p-5 flex flex-col gap-4">
            <p class="m-0 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                Gunakan tombol di bawah ini untuk mengunduh rekapitulasi data seluruh jenis surat (Surat Masuk, Undangan Masuk, Surat Keluar, Undangan Keluar, SPT, SPPD, dan SK). Anda dapat mengekspornya ke dalam format Excel (lembaran/tab terpisah per jenis) maupun format PDF landscape gabungan.
            </p>
            <div>
                <button type="button" id="buka-modal-export-rekap" class="btn-primary inline-flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    Buka Pilihan Ekspor Rekap
                </button>
            </div>
        </div>
    </x-card>

    {{-- Modal Export Rekap --}}
    <div id="modal-export-rekap" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl max-w-lg w-[90%] p-6 max-h-[80vh] overflow-y-auto">
            <h3 class="mt-0 text-base font-bold text-slate-800 dark:text-slate-100 mb-4">Ekspor Rekap Semua Surat</h3>
            <form method="GET" action="" onsubmit="prepareExportFormRekap()">
                <input type="hidden" name="start_date" id="export_start_date_rekap">
                <input type="hidden" name="end_date" id="export_end_date_rekap">
                
                <div class="mb-5">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300 block mb-2">Pilih Tipe Periode</label>
                    <select id="export_tipe_rekap" onchange="toggleExportTypeRekap(this.value)" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm outline-none mb-3 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                        <option value="semua">Semua Waktu</option>
                        <option value="bulan">Per Bulan</option>
                        <option value="tahun">Per Tahun</option>
                        <option value="rentang">Rentang Tanggal</option>
                    </select>
                    
                    <div id="export_bulan_rekap" class="hidden mb-3">
                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400 block mb-1">Bulan</label>
                                <select id="export_val_bulan_rekap" class="w-full px-2 py-2 border border-slate-300 dark:border-slate-600 rounded-md text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                    <option value="01">Januari</option><option value="02">Februari</option>
                                    <option value="03">Maret</option><option value="04">April</option>
                                    <option value="05">Mei</option><option value="06">Juni</option>
                                    <option value="07">Juli</option><option value="08">Agustus</option>
                                    <option value="09">September</option><option value="10">Oktober</option>
                                    <option value="11">November</option><option value="12">Desember</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400 block mb-1">Tahun</label>
                                <select id="export_val_tahun_rekap" class="w-full px-2 py-2 border border-slate-300 dark:border-slate-600 rounded-md text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                    @for($y=date('Y'); $y>=2020; $y--) <option value="{{ $y }}">{{ $y }}</option> @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="export_tahun_rekap" class="hidden mb-3">
                        <label class="text-xs text-slate-500 dark:text-slate-400 block mb-1">Tahun</label>
                        <select id="export_val_tahunonly_rekap" class="w-full px-2 py-2 border border-slate-300 dark:border-slate-600 rounded-md text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                            @for($y=date('Y'); $y>=2020; $y--) <option value="{{ $y }}">{{ $y }}</option> @endfor
                        </select>
                    </div>

                    <div id="export_rentang_rekap" class="hidden mb-3">
                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400 block mb-1">Dari Tanggal</label>
                                <input type="date" id="export_val_start_rekap" class="w-full px-2 py-1.5 border border-slate-300 dark:border-slate-600 rounded-md text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400 block mb-1">Sampai Tanggal</label>
                                <input type="date" id="export_val_end_rekap" class="w-full px-2 py-1.5 border border-slate-300 dark:border-slate-600 rounded-md text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col gap-2 mt-5 border-t border-slate-100 dark:border-slate-700 pt-4">
                    <button type="submit" formaction="{{ route('dashboard.export.excel') }}" class="btn-primary w-full justify-center flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                        Download Excel (.xlsx)
                    </button>
                    <button type="submit" formtarget="_blank" formaction="{{ route('dashboard.export.pdf') }}" class="btn-secondary w-full justify-center flex items-center gap-1.5 text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                        Download PDF (.pdf)
                    </button>
                    <button type="button" data-modal-close class="bg-transparent border-none text-slate-500 dark:text-slate-400 text-sm p-2 cursor-pointer text-center hover:text-slate-700 dark:hover:text-slate-200">Batal</button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
    .stat-grid-5 {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(2, 1fr);
    }
    @media (min-width: 640px)  { .stat-grid-5 { grid-template-columns: repeat(3, 1fr); } }
    @media (min-width: 1024px) { .stat-grid-5 { grid-template-columns: repeat(5, 1fr); } }

    .stat-number {
        font-size: 30px;
        font-weight: 800;
        line-height: 1;
        margin-top: 10px;
        color: #1e293b;
    }
    .stat-label {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
    }
    .dark .stat-number {
        color: #f1f5f9 !important;
    }
    .dark .stat-label {
        color: #94a3b8 !important;
    }
    .dark .card-heading {
        color: #f1f5f9 !important;
    }
    .card-heading {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
    }
    .stat-card:hover { border-color: rgba(26,54,93,.25) !important; transform: translateY(-1px); }
    .stat-card { transition: all .15s ease; }
</style>
@endsection



