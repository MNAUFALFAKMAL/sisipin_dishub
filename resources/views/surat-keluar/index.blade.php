@php
    $jenisSurat = request('jenis_surat', 'SPT');
    $klasifikasiList = \App\Models\KlasifikasiArsip::whereNull('parent_id')->orderBy('kode')->get(['id', 'kode', 'nama']);
    $bidangList = \App\Models\Bidang::orderBy('nama_bidang')->get(['id', 'nama_bidang', 'kode_bidang']);
    $tahunList = \App\Models\TahunArsip::orderByDesc('tahun')->pluck('tahun');
    $statusList = ['draft', 'dikirim', 'selesai'];
    $jenisSuratList = ['SPT', 'SPPD', 'SK', 'Undangan'];
@endphp
@extends('layouts.app')
@section('title', 'Surat Keluar (' . $jenisSurat . ') ' . (request('tahun') ? ' ' . request('tahun') : ''))
@section('page-title', 'Surat Keluar (' . $jenisSurat . ') ' . (request('tahun') ? ' ' . request('tahun') : ''))

@section('content')
<div class="space-y-4" x-data="suratSearch({ searchEndpoint: '{{ route('surat-keluar.search') }} })">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Surat Keluar ({{ $jenisSurat }}) {{ request('tahun') ? ' ' . request('tahun') : '' }}</h2>
            <p class="text-sm text-slate-500">Kelola seluruh Surat Keluar ({{ $jenisSurat }}) Dishub Lampung</p>
        </div>
        <div class="action-group">
            <button type="button" onclick="document.getElementById('modal-export-keluar').style.display='flex'" class="btn-secondary">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Ekspor
            </button>
            <button type="button" onclick="document.getElementById('modal-import-keluar').style.display='flex'" class="btn-secondary">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import
            </button>
            <x-button variant="primary" :href="route('surat-keluar.create', array_merge(request()->has('tahun') ? ['jenis_surat' => request('jenis_surat'), 'tahun' => request('tahun')] : ['jenis_surat' => request('jenis_surat')]))">
                Tambah Surat Keluar
            </x-button>
        </div>
    </div>

    {{-- Filter & Search --}}
    <x-card>
        <form method="GET" action="{{ route('surat-keluar.index') }}" class="space-y-4" x-data="suratForm()">
            @if(request()->has('jenis_surat'))
                <input type="hidden" name="jenis_surat" value="{{ request('jenis_surat') }}" />
            @endif
            @if(request()->has('tahun'))
                <input type="hidden" name="tahun" value="{{ request('tahun') }}" />
            @endif
            
            {{-- Search Bar --}}
            <div class="relative">
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Cari (Real-time)</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" 
                           x-model="search" 
                           @input.debounce.300ms="fetch()"
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari no agenda, nomor surat, perihal, tujuan, penandatangan..."
                           class="form-input w-full pl-10 py-2.5 text-sm"
                           placeholder="Ketik untuk mencari... (real-time)">
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" x-show="search" @click="search = ''; fetch()" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>

            {{-- Filter Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Status</label>
                    <select x-model="filters.status" @change="fetch()" class="form-select w-full py-2 text-sm">
                        <option value="">Semua Status</option>
                        @foreach($statusList as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Jenis Surat</label>
                    <select x-model="filters.jenis" @change="fetch()" class="form-select w-full py-2 text-sm">
                        <option value="">Semua Jenis</option>
                        @foreach(['SPT', 'SPPD', 'SK', 'Undangan'] as $jenis)
                            <option value="{{ $jenis }}">{{ $jenis }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Bidang</label>
                    <select x-model="filters.bidang" @change="fetch()" class="form-select w-full py-2 text-sm">
                        <option value="">Semua Bidang</option>
                        @foreach($bidangList as $bidang)
                            <option value="{{ $bidang->id }}">{{ $bidang->kode_bidang }} - {{ Str::limit($bidang->nama_bidang, 25) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Klasifikasi</label>
                    <select x-model="filters.klasifikasi" @change="fetch()" class="form-select w-full py-2 text-sm">
                        <option value="">Semua Klasifikasi</option>
                        @foreach($klasifikasiList as $klasifikasi)
                            <option value="{{ $klasifikasi->id }}">{{ $klasifikasi->kode }} - {{ Str::limit($klasifikasi->nama, 30) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Tipe Periode</label>
                    <select x-model="filters.filter_type" @change="fetch()" class="form-select w-full py-2 text-sm">
                        <option value="semua">Semua Waktu</option>
                        <option value="bulan">Per Bulan</option>
                        <option value="tahun">Per Tahun</option>
                        <option value="rentang">Rentang Tanggal</option>
                    </select>
                </div>

                <div x-show="filters.filter_type === 'bulan'" x-transition>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Bulan</label>
                            <select x-model="filters.filter_bulan" @change="fetch()" class="form-select w-full py-2 text-sm">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ sprintf('%02d', $m) }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('M') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Tahun</label>
                            <select x-model="filters.filter_tahun" @change="fetch()" class="form-select w-full py-2 text-sm">
                                @for($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div x-show="filters.filter_type === 'tahun'" x-transition>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Tahun</label>
                    <select x-model="filters.filter_tahun_only" @change="fetch()" class="form-select w-full py-2 text-sm">
                        @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div x-show="filters.filter_type === 'rentang'" x-transition>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Dari</label>
                            <input type="date" x-model="filters.date_from" @change="fetch()" class="form-input w-full py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Sampai</label>
                            <input type="date" x-model="filters.date_to" @change="fetch()" class="form-input w-full py-2 text-sm">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1 py-2 text-sm flex items-center justify-center gap-1.5 shadow-sm dark:shadow-none">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari
                </button>
                <button type="button" @click="resetFilters()" class="btn-secondary" :class="{ hidden: !hasActiveFilters() }">Reset</button>
            </div>
        </form>
    </x-card>

    {{-- Quick Filter Tabs / Navigation Pills --}}
    <div class="mb-5 bg-white dark:bg-slate-800 p-2 rounded-xl shadow-sm dark:shadow-none border border-slate-100 dark:border-slate-700">
        <div class="flex gap-2 overflow-x-auto no-scrollbar py-1 scroll-smooth">
            @foreach(['SPT', 'SPPD', 'SK', 'Undangan'] as $jenis)
                <a href="{{ route('surat-keluar.index', array_merge(request()->except('page'), ['jenis_surat' => $jenis])) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap transition-all duration-200 shadow-sm dark:shadow-none border{{ request('jenis_surat') == $jenis ? ' nav-pill-active' : ' nav-pill-inactive' }}">
                    {{ $jenis }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Table --}}
    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No.</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tgl Surat</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Agenda</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tujuan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No Surat</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tgl Keluar</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Perihal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Bidang</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Penandatangan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                    @forelse($suratKeluars as $index => $surat)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                        <td class="px-4 py-3 text-center font-medium">{{ $suratKeluars->firstItem() + $index }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs sm:text-sm">{{ $surat->tanggal_surat ? \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-3 font-mono text-xs">{{ $surat->no_agenda }}</td>
                        <td class="px-4 py-3 text-xs font-semibold">{{ $surat->klasifikasi ? $surat->klasifikasi->kode : '-' }}</td>
                        <td class="px-4 py-3 max-w-[150px] sm:max-w-none truncate">{{ $surat->tujuan ?? '-' }}</td>
                        <td class="px-4 py-3 font-mono text-xs">{{ $surat->no_surat ?: '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $surat->tanggal_terima ? \Carbon\Carbon::parse($surat->tanggal_terima)->translatedFormat('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-3 max-w-xs break-words" title="{{ $surat->perihal }}">{{ Str::limit($surat->perihal ?? '', 60) }}</td>
                        <td class="px-4 py-3">{{ $surat->bidang ? $surat->bidang->kode_bidang : '-' }}</td>
                        <td class="px-4 py-3 text-xs">{{ $surat->penandatangan ?: '-' }}</td>
                        <td class="px-4 py-3">
                            @if($surat->status == 'selesai')
                                <x-badge type="green">Selesai</x-badge>
                            @elseif($surat->status == 'dikirim')
                                <x-badge type="blue">Dikirim</x-badge>
                            @else
                                <x-badge type="yellow">Draft</x-badge>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1 sm:gap-2">
                                <a href="{{ route('surat-keluar.show', $surat->id) }}"
                                   class="rounded-lg p-2.5 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors" title="Lihat">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @can('update', $surat)
                                    <a href="{{ route('surat-keluar.edit', array_merge([$surat->id], request()->has('tahun') ? ['jenis_surat' => request('jenis_surat'), 'tahun' => request('tahun')] : ['jenis_surat' => request('jenis_surat')])) }}"
                                       class="rounded-lg p-2.5 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 transition-colors" title="Edit">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                @endcan
                                <form action="{{ route('surat-keluar.destroy', array_merge([$surat->id], request()->has('tahun') ? ['jenis_surat' => request('jenis_surat'), 'tahun' => request('tahun')] : ['jenis_surat' => request('jenis_surat')])) }}" method="POST"
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
                        <td colspan="12" class="py-12 text-center text-slate-400 dark:text-slate-500">
                            <svg class="mx-auto mb-3 h-10 w-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm italic">Tidak ada data surat keluar ({{ $jenisSurat }}).</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="w-full min-w-full flex justify-center py-3">
                {{ $suratKeluars->withQueryString()->links() }}
            </div>
        </div>
    </x-card>

</div>

{{-- Modal Import Excel --}}
<div id="modal-import-keluar" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6">
        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4">Import Data Surat Keluar ({{ $jenisSurat }})</h3>
        <form action="{{ route('surat-keluar.import.excel') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis_surat" value="{{ request('jenis_surat') }}">
            <div class="mb-4 bg-slate-50 dark:bg-slate-700/50 p-3.5 rounded-xl border border-slate-100 dark:border-slate-700">
                <p class="text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">💡 Petunjuk Penggunaan:</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-2.5">
                    Harap gunakan template resmi agar kolom penulisan dikenali oleh sistem. Hanya berkas Excel (.xlsx) yang diperbolehkan.
                </p>
                <a href="{{ route('surat-keluar.export.template') }}" class="inline-flex items-center text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                    <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download Template Excel
                </a>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Pilih File Excel (.xlsx)</label>
                <input type="file" name="file_excel" accept=".xlsx" class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-blue-900/30 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/50" required>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-import-keluar').style.display='none'" class="btn-secondary">Batal</button>
                <x-button variant="primary" type="submit">Import Sekarang</x-button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Export --}}
<div id="modal-export-keluar" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6">
        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4">Export Data Surat Keluar ({{ request('jenis_surat') }})</h3>
        <form method="GET" action="" onsubmit="prepareExportFormKeluar()">
            <input type="hidden" name="jenis_surat" value="{{ request('jenis_surat') }}">
            <input type="hidden" name="tahun" value="{{ request('tahun') }}">
            <input type="hidden" name="start_date" id="export_start_date_keluar">
            <input type="hidden" name="end_date" id="export_end_date_keluar">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Pilih Tipe Periode</label>
                <select id="export_tipe_keluar" class="form-select w-full mb-3" onchange="toggleExportTypeKeluar(this.value)">
                    <option value="semua">Semua Waktu</option>
                    <option value="bulan">Per Bulan</option>
                    <option value="tahun">Per Tahun</option>
                    <option value="rentang">Rentang Tanggal</option>
                </select>
                
                <div id="export_bulan_keluar" style="display:none;" class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Bulan</label>
                        <select id="export_val_bulan_keluar" class="form-select w-full">
                            <option value="01">Januari</option><option value="02">Februari</option>
                            <option value="03">Maret</option><option value="04">April</option>
                            <option value="05">Mei</option><option value="06">Juni</option>
                            <option value="07">Juli</option><option value="08">Agustus</option>
                            <option value="09">September</option><option value="10">Oktober</option>
                            <option value="11">November</option><option value="12">Desember</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Tahun</label>
                        <select id="export_val_tahun_keluar" class="form-select w-full">
                            @for($y=date('Y'); $y>=2020; $y--) <option value="{{ $y }}">{{ $y }}</option> @endfor
                        </select>
                    </div>
                </div>

                <div id="export_tahun_keluar" style="display:none;">
                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Tahun</label>
                    <select id="export_val_tahunonly_keluar" class="form-select w-full">
                        @for($y=date('Y'); $y>=2020; $y--) <option value="{{ $y }}">{{ $y }}</option> @endfor
                    </select>
                </div>

                <div id="export_rentang_keluar" style="display:none;" class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Dari Tanggal</label>
                        <input type="date" id="export_val_start_keluar" class="form-input w-full py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Sampai Tanggal</label>
                        <input type="date" id="export_val_end_keluar" class="form-input w-full py-2 text-sm">
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col gap-2 mt-6 border-t pt-4">
                <button type="submit" formaction="{{ route('surat-keluar.export.excel') }}" class="btn-secondary w-full flex justify-center items-center text-green-700 dark:text-green-300 border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    Download Excel (.xlsx)
                </button>
                <button type="submit" formtarget="_blank" formaction="{{ route('surat-keluar.export.pdf') }}" class="btn-secondary w-full flex justify-center items-center text-red-700 dark:text-red-300 border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    Download PDF
                </button>
                <x-button variant="secondary" class="mt-2 w-full" onclick="document.getElementById('modal-export-keluar').style.display='none'">Batal</x-button>
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
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Hapus Surat Keluar</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Apakah Anda yakin ingin menghapus surat ini?</p>
            </div>
        </div>
        <p class="text-sm text-slate-600 dark:text-slate-300 mb-6 ml-13 pl-13">Data yang dihapus <strong>tidak dapat dikembalikan</strong>.</p>
        <div class="flex justify-end gap-3">
            <button type="button" id="batalHapusModal" class="px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-lg transition">Batal</button>
            <button type="button" id="konfirmasiHapusModal" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 rounded-lg transition">Ya, Hapus</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Global Alpine component for search form
    document.addEventListener('alpine:init', () => {
        Alpine.data('suratForm', () => ({
            search: '{{ request('search') }}',
            filters: {
                status: '{{ request('status') }}',
                jenis: '{{ request('jenis_surat_filter') }}',
                bidang: '{{ request('bidang_id') }}',
                klasifikasi: '{{ request('klasifikasi_id') }}',
                filter_type: '{{ request('filter_type', 'semua') }}',
                filter_bulan: '{{ request('filter_bulan') }}',
                filter_tahun: '{{ request('filter_tahun') }}',
                filter_tahun_only: '{{ request('filter_tahun_only') }}',
                date_from: '{{ request('start_date') }}',
                date_to: '{{ request('end_date') }}',
            },
            loading: false,
            results: [],
            total: 0,
            debounceTimer: null,

            init() {
                // Sync URL params with component state
                const params = new URLSearchParams(window.location.search);
                this.search = params.get('search') || '';
                this.filters.status = params.get('status') || '';
                this.filters.jenis = params.get('jenis_surat_filter') || '';
                this.filters.bidang = params.get('bidang_id') || '';
                this.filters.klasifikasi = params.get('klasifikasi_id') || '';
                this.filters.filter_type = params.get('filter_type') || 'semua';
                this.filters.filter_bulan = params.get('filter_bulan') || '';
                this.filters.filter_tahun = params.get('filter_tahun') || '';
                this.filters.filter_tahun_only = params.get('filter_tahun_only') || '';
                this.filters.date_from = params.get('filter_start_date') || '';
                this.filters.date_to = params.get('filter_end_date') || '';
            },

            async fetch() {
                clearTimeout(this.debounceTimer);
                this.debounceTimer = setTimeout(async () => {
                    this.loading = true;
                    
                    const params = new URLSearchParams();
                    if (this.search) params.set('search', this.search);
                    Object.entries(this.filters).forEach(([key, value]) => {
                        if (value) params.set(this.getParamName(key), value);
                    });
                    
                    // Preserve jenis_surat and tahun from URL
                    const urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.has('jenis_surat')) params.set('jenis_surat', urlParams.get('jenis_surat'));
                    if (urlParams.has('tahun')) params.set('tahun', urlParams.get('tahun'));
                    
                    try {
                        const response = await fetch(`{{ route('surat-keluar.search') }}?${params.toString()}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });
                        
                        if (!response.ok) throw new Error('Search failed');
                        
                        const data = await response.json();
                        this.results = data.data || [];
                        this.total = data.total || 0;
                        
                        // Update URL without reload
                        const newUrl = `${window.location.pathname}?${new URLSearchParams({
                            search: this.search || undefined,
                            ...this.getActiveFilters(),
                            tahun: urlParams.get('tahun') || undefined,
                            jenis_surat: urlParams.get('jenis_surat') || undefined,
                        }).toString()}`;
                        
                        window.history.replaceState({}, '', this.search || this.hasActiveFilters() ? newUrl : window.location.pathname);
                        
                    } catch (error) {
                        console.error('Search error:', error);
                    } finally {
                        this.loading = false;
                    }
                }, 300);
            },
            
            getParamName(key) {
                const map = {
                    'status': 'status',
                    'jenis': 'jenis_surat_filter',
                    'bidang': 'bidang_id',
                    'klasifikasi': 'klasifikasi_id',
                    'filter_type': 'filter_type',
                    'filter_bulan': 'filter_bulan',
                    'filter_tahun': 'filter_tahun',
                    'filter_tahun_only': 'filter_tahun_only',
                    'date_from': 'start_date',
                    'date_to': 'end_date',
                };
                return map[key] || key;
            },
            
            getActiveFilters() {
                const filters = {};
                Object.entries(this.filters).forEach(([key, value]) => {
                    if (value) filters[this.getParamName(key)] = value;
                });
                return filters;
            },
            
            hasActiveFilters() {
                return Object.values(this.filters).some(v => v);
            },
            
            resetFilters() {
                this.filters = {
                    status: '',
                    jenis: '',
                    bidang: '',
                    klasifikasi: '',
                    filter_type: 'semua',
                    filter_bulan: '',
                    filter_tahun: '',
                    filter_tahun_only: '',
                    date_from: '',
                    date_to: '',
                };
                this.search = '';
                this.fetch();
            },
        }));
    });
</script>
@endpush