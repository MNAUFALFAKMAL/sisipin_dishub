@php
    $jenisSurat = request('jenis_surat', 'Surat Masuk');
@endphp
@extends('layouts.app')
@section('title', $jenisSurat . (request('tahun') ? ' ' . request('tahun') : ''))
@section('page-title', $jenisSurat . (request('tahun') ? ' ' . request('tahun') : ''))

@section('content')
<div class="space-y-4">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100">Daftar {{ $jenisSurat }}</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400">Kelola seluruh {{ strtolower($jenisSurat) }} Dishub Lampung</p>
        </div>
        <div class="action-group">
            <button type="button" onclick="document.getElementById('modal-export-masuk').style.display='flex'" class="btn-secondary">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Ekspor
            </button>
            <button type="button" onclick="document.getElementById('modal-import-masuk').style.display='flex'" class="btn-secondary">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import
            </button>
            <x-button variant="primary" :href="route('surat-masuk.create', array_merge(request()->has('tahun') ? ['jenis_surat_active' => request('jenis_surat'), 'tahun' => request('tahun')] : ['jenis_surat_active' => request('jenis_surat')]))">
                Tambah {{ $jenisSurat }}
            </x-button>
        </div>
    </div>

    {{-- Filter & Search --}}
    <x-card>
        <form method="GET" action="{{ route('surat-masuk.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            @if(request()->has('jenis_surat'))
                <input type="hidden" name="jenis_surat" value="{{ request('jenis_surat') }}" />
            @endif
            @if(request()->has('tahun'))
                <input type="hidden" name="tahun" value="{{ request('tahun') }}" />
            @endif
            
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Cari Kata Kunci</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nomor, pengirim, perihal..."
                       class="form-input w-full py-2 text-sm" />
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Status Disposisi</label>
                <select name="status" class="form-select w-full py-2 text-sm">
                    <option value="" {{ request('status') == '' || !request()->has('status') ? 'selected' : '' }}>Semua Status</option>
                    <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Disposisi</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Tipe Periode</label>
                <select name="filter_type" id="main_filter_type_masuk" class="form-select w-full py-2 text-sm" onchange="toggleMainFilterMasuk(this.value)">
                    <option value="semua" {{ request('filter_type', 'semua') == 'semua' ? 'selected' : '' }}>Semua Waktu</option>
                    <option value="bulan" {{ request('filter_type') == 'bulan' ? 'selected' : '' }}>Per Bulan</option>
                    <option value="tahun" {{ request('filter_type') == 'tahun' ? 'selected' : '' }}>Per Tahun</option>
                    <option value="rentang" {{ request('filter_type') == 'rentang' ? 'selected' : '' }}>Rentang Tanggal</option>
                </select>
            </div>
            
            <div id="main_filter_bulan_masuk" style="display: {{ request('filter_type') == 'bulan' ? 'block' : 'none' }};">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Bulan</label>
                        <select name="filter_bulan" class="form-select w-full py-2 text-sm">
                            <option value="01" {{ request('filter_bulan') == '01' ? 'selected' : '' }}>Jan</option>
                            <option value="02" {{ request('filter_bulan') == '02' ? 'selected' : '' }}>Feb</option>
                            <option value="03" {{ request('filter_bulan') == '03' ? 'selected' : '' }}>Mar</option>
                            <option value="04" {{ request('filter_bulan') == '04' ? 'selected' : '' }}>Apr</option>
                            <option value="05" {{ request('filter_bulan') == '05' ? 'selected' : '' }}>Mei</option>
                            <option value="06" {{ request('filter_bulan') == '06' ? 'selected' : '' }}>Jun</option>
                            <option value="07" {{ request('filter_bulan') == '07' ? 'selected' : '' }}>Jul</option>
                            <option value="08" {{ request('filter_bulan') == '08' ? 'selected' : '' }}>Ags</option>
                            <option value="09" {{ request('filter_bulan') == '09' ? 'selected' : '' }}>Sep</option>
                            <option value="10" {{ request('filter_bulan') == '10' ? 'selected' : '' }}>Okt</option>
                            <option value="11" {{ request('filter_bulan') == '11' ? 'selected' : '' }}>Nov</option>
                            <option value="12" {{ request('filter_bulan') == '12' ? 'selected' : '' }}>Des</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Tahun</label>
                        <select name="filter_tahun" class="form-select w-full py-2 text-sm">
                            @for($y=date('Y'); $y>=2020; $y--) <option value="{{ $y }}" {{ request('filter_tahun') == $y ? 'selected' : '' }}>{{ $y }}</option> @endfor
                        </select>
                    </div>
                </div>
            </div>

            <div id="main_filter_tahun_masuk" style="display: {{ request('filter_type') == 'tahun' ? 'block' : 'none' }};">
                <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Tahun</label>
                <select name="filter_tahun_only" class="form-select w-full py-2 text-sm">
                    @for($y=date('Y'); $y>=2020; $y--) <option value="{{ $y }}" {{ request('filter_tahun_only') == $y ? 'selected' : '' }}>{{ $y }}</option> @endfor
                </select>
            </div>

            <div id="main_filter_rentang_masuk" style="display: {{ request('filter_type') == 'rentang' ? 'block' : 'none' }};">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Dari</label>
                        <input type="date" name="filter_start_date" value="{{ request('filter_start_date') }}" class="form-input w-full py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Sampai</label>
                        <input type="date" name="filter_end_date" value="{{ request('filter_end_date') }}" class="form-input w-full py-2 text-sm">
                    </div>
                </div>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1 py-2 text-sm flex items-center justify-center gap-1.5 shadow-sm dark:shadow-none">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari
                </button>
                @if(request()->hasAny(['search','status','filter_type','filter_bulan','filter_tahun','filter_tahun_only','filter_start_date','filter_end_date','start_date','end_date']))
                <x-button variant="secondary" href="{{ route('surat-masuk.index', ['jenis_surat' => request('jenis_surat'), 'tahun' => request('tahun')]) }}">Reset</x-button>
                @endif
            </div>
        </form>
    </x-card>

    {{-- Quick Filter Tabs / Navigation Pills --}}
    <div class="mb-5 bg-white dark:bg-slate-800 p-2 rounded-xl shadow-sm dark:shadow-none border border-gray-100 dark:border-slate-700">
        <div class="flex gap-2 overflow-x-auto no-scrollbar py-1 scroll-smooth">

            <a href="{{ route('surat-masuk.index', array_merge(request()->except('page'), ['jenis_surat' => 'Surat Masuk'])) }}" 
               class="px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap transition-all duration-200 shadow-sm dark:shadow-none border{{ request('jenis_surat') == 'Surat Masuk' ? ' nav-pill-active' : ' nav-pill-inactive' }}">
                Surat Masuk
            </a>
            <a href="{{ route('surat-masuk.index', array_merge(request()->except('page'), ['jenis_surat' => 'Undangan Masuk'])) }}" 
               class="px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap transition-all duration-200 shadow-sm dark:shadow-none border{{ request('jenis_surat') == 'Undangan Masuk' ? ' nav-pill-active' : ' nav-pill-inactive' }}">
                Undangan Masuk
            </a>
        </div>
    </div>

<style>
.nav-pill-active { background-color: #1A365D !important; color: #ffffff !important; border-color: #1A365D !important; }
.dark .nav-pill-active { background-color: #1A365D !important; color: #ffffff !important; border-color: #1A365D !important; }
.nav-pill-inactive { background-color: #F7FAFC !important; color: #4A5568 !important; border-color: #E2E8F0 !important; }
.dark .nav-pill-inactive { background-color: #1e293b !important; color: #cbd5e1 !important; border-color: #334155 !important; }
</style>

    {{-- Table --}}
    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                <thead class="bg-gray-50 dark:bg-slate-700/50">
                    <tr>
                        <th class="table-th text-center">No.</th>
                        <th class="table-th">Tgl Masuk</th>
                        <th class="table-th">Kode</th>
                        <th class="table-th">Pengirim</th>
                        <th class="table-th">No Surat</th>
                        <th class="table-th">Tgl Surat</th>
                        <th class="table-th">Uraian</th>
                        <th class="table-th">Disposisi</th>
                        <th class="table-th">Status</th>
                        <th class="table-th">Keterangan</th>
                        <th class="table-th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                    @forelse($suratMasuks as $index => $surat)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 dark:bg-slate-700/50 transition">
                        <td class="table-td text-center font-medium">
                            {{ $surat->no_agenda }}
                        </td>
                        <td class="table-td whitespace-nowrap text-xs sm:text-sm">
                            {{ $surat->tanggal_terima ? \Carbon\Carbon::parse($surat->tanggal_terima)->translatedFormat('d/m/Y') : '-' }}
                        </td>
                        <td class="table-td text-xs font-semibold">{{ $surat->klasifikasi ? $surat->klasifikasi->kode : '-' }}</td>
                        <td class="table-td max-w-[120px] sm:max-w-none truncate">{{ $surat->pengirim ?? '-' }}</td>
                        <td class="table-td font-mono text-xs">{{ $surat->no_surat ?: '-' }}</td>
                        <td class="table-td whitespace-nowrap">
                            {{ $surat->tanggal_surat ? \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d/m/Y') : '-' }}
                        </td>
                        <td class="table-td max-w-xs break-words" title="{{ $surat->perihal }}">
                            {{ strlen($surat->perihal ?? '') > 80 ? substr($surat->perihal, 0, 38) . '...' . substr($surat->perihal, -38) : ($surat->perihal ?? '-') }}
                        </td>
                        <td class="table-td">
                            <div class="flex flex-wrap gap-1">
                                @forelse($surat->bidangTujuans as $bid)
                                    <x-badge type="blue">{{ $bid->kode_bidang }}</x-badge>
                                @empty
                                    <span class="text-xs text-gray-400 dark:text-slate-500">-</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="table-td">
                            @if(empty($surat->perihal))
                                <span class="text-gray-400 dark:text-slate-500">-</span>
                            @else
                                @if($surat->status_disposisi == 'selesai')
                                    <x-badge type="green">Selesai</x-badge>
                                @elseif($surat->status_disposisi == 'diproses')
                                    <x-badge type="yellow">Diproses</x-badge>
                                @else
                                    <x-badge type="red">Belum</x-badge>
                                @endif
                            @endif
                        </td>
                        <td class="table-td">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-semibold text-gray-700 dark:text-slate-300">{{ $surat->keterangan ?: '-' }}</span>
                                @if(!empty($surat->file_surat))
                                    <a href="{{ route('file.serve', $surat->file_surat[0]) }}" target="_blank" class="inline-flex items-center text-[10px] text-blue-600 dark:text-blue-400 hover:underline">
                                        <svg class="h-3 w-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>{{ count($surat->file_surat) > 1 ? 'File (' . count($surat->file_surat) . ')' : 'File' }}</span>
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td class="table-td">
                            <div class="flex items-center justify-end gap-1 sm:gap-2">
                                <a href="{{ route('surat-masuk.show', $surat->id) }}"
                                   class="rounded-lg p-2.5 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors" title="Lihat">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('surat-masuk.edit', array_merge([$surat->id], request()->has('tahun') ? ['jenis_surat_active' => request('jenis_surat'), 'tahun' => request('tahun')] : ['jenis_surat_active' => request('jenis_surat')])) }}"
                                   class="rounded-lg p-2.5 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 transition-colors" title="Edit">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('surat-masuk.destroy', array_merge([$surat->id], request()->has('tahun') ? ['jenis_surat_active' => request('jenis_surat'), 'tahun' => request('tahun')] : ['jenis_surat_active' => request('jenis_surat')])) }}" method="POST"
                                      class="delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                            class="delete-trigger rounded-lg p-2.5 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="Hapus">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="py-12 text-center text-gray-400 dark:text-slate-500">
                            <svg class="mx-auto mb-3 h-10 w-10 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm italic">Tidak ada data surat masuk.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="w-full min-w-full flex justify-center py-3">
                {{ $suratMasuks->withQueryString()->links() }}
            </div>
        </div>
    </x-card>

</div>

{{-- Modal Import Excel --}}
<div id="modal-import-masuk" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6">
        <h3 class="text-lg font-bold text-gray-800 dark:text-slate-100 mb-4">Import Data {{ $jenisSurat }}</h3>
        <form action="{{ route('surat-masuk.import.excel') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis_surat" value="{{ request('jenis_surat') }}">
            <div class="mb-4 bg-gray-50 dark:bg-slate-700/50 p-3.5 rounded-xl border border-gray-100 dark:border-slate-700">
                <p class="text-xs font-bold text-gray-700 dark:text-slate-300 mb-1">💡 Petunjuk Penggunaan:</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 leading-relaxed mb-2.5">
                    Harap gunakan template resmi agar kolom penulisan dikenali oleh sistem. Hanya berkas Excel (.xlsx) yang diperbolehkan.
                </p>
                <a href="{{ route('surat-masuk.export.template') }}" class="inline-flex items-center text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                    <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download Template Excel
                </a>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Pilih File Excel (.xlsx)</label>
                <input type="file" name="file_excel" accept=".xlsx" class="w-full text-sm text-gray-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-blue-900/30 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/50" required>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-import-masuk').style.display='none'" class="btn-secondary">Batal</button>
                <x-button variant="primary" type="submit">Import Sekarang</x-button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Export --}}
<div id="modal-export-masuk" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6">
        <h3 class="text-lg font-bold text-gray-800 dark:text-slate-100 mb-4">Export Data {{ request('jenis_surat') ?: 'Surat Masuk' }}</h3>
        <form method="GET" action="" onsubmit="prepareExportFormMasuk()">
            <input type="hidden" name="jenis_surat" value="{{ request('jenis_surat') }}">
            <input type="hidden" name="tahun" value="{{ request('tahun') }}">
            <input type="hidden" name="start_date" id="export_start_date_masuk">
            <input type="hidden" name="end_date" id="export_end_date_masuk">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Pilih Tipe Periode</label>
                <select id="export_tipe_masuk" class="form-select w-full mb-3" onchange="toggleExportTypeMasuk(this.value)">
                    <option value="semua">Semua Waktu</option>
                    <option value="bulan">Per Bulan</option>
                    <option value="tahun">Per Tahun</option>
                    <option value="rentang">Rentang Tanggal</option>
                </select>
                
                <div id="export_bulan_masuk" style="display:none;" class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Bulan</label>
                        <select id="export_val_bulan_masuk" class="form-select w-full">
                            <option value="01">Januari</option><option value="02">Februari</option>
                            <option value="03">Maret</option><option value="04">April</option>
                            <option value="05">Mei</option><option value="06">Juni</option>
                            <option value="07">Juli</option><option value="08">Agustus</option>
                            <option value="09">September</option><option value="10">Oktober</option>
                            <option value="11">November</option><option value="12">Desember</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Tahun</label>
                        <select id="export_val_tahun_masuk" class="form-select w-full">
                            @for($y=date('Y'); $y>=2020; $y--) <option value="{{ $y }}">{{ $y }}</option> @endfor
                        </select>
                    </div>
                </div>

                <div id="export_tahun_masuk" style="display:none;">
                    <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Tahun</label>
                    <select id="export_val_tahunonly_masuk" class="form-select w-full">
                        @for($y=date('Y'); $y>=2020; $y--) <option value="{{ $y }}">{{ $y }}</option> @endfor
                    </select>
                </div>

                <div id="export_rentang_masuk" style="display:none;" class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Dari Tanggal</label>
                        <input type="date" id="export_val_start_masuk" class="form-input w-full py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Sampai Tanggal</label>
                        <input type="date" id="export_val_end_masuk" class="form-input w-full py-2 text-sm">
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col gap-2 mt-6 border-t pt-4">
                <button type="submit" formaction="{{ route('surat-masuk.export.excel') }}" class="btn-secondary w-full flex justify-center items-center text-green-700 dark:text-green-300 border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    Download Excel (.xlsx)
                </button>
                <button type="submit" formtarget="_blank" formaction="{{ route('surat-masuk.export.pdf') }}" class="btn-secondary w-full flex justify-center items-center text-red-700 dark:text-red-300 border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    Download PDF
                </button>
                <x-button variant="secondary" class="mt-2 w-full" onclick="document.getElementById('modal-export-masuk').style.display='none'">Batal</x-button>
            </div>
        </form>
    </div>
</div>


<div id="hapusModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-start gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-slate-100">Hapus Surat</h3>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Apakah Anda yakin ingin menghapus surat ini?</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 dark:text-slate-300 mb-6 ml-13 pl-13">Data yang dihapus <strong>tidak dapat dikembalikan</strong>.</p>
        <div class="flex justify-end gap-3">
            <button type="button" id="batalHapusModal" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-slate-300 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-600 rounded-lg transition">Batal</button>
            <button type="button" id="konfirmasiHapusModal" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 rounded-lg transition">Ya, Hapus</button>
        </div>
    </div>
</div>


@endsection
