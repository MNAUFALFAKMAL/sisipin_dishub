@extends('layouts.app')
@section('title', 'Klasifikasi Arsip')
@section('page-title', 'Pola Klasifikasi Arsip')

@section('content')
<div class="space-y-4">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100">Klasifikasi Arsip & JRA</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400">Kelola master data klasifikasi, jadwal retensi arsip (JRA), dan hak akses keamanan (SKKAAD)</p>
        </div>
        <div>
            <x-button variant="primary" href="{{ route('klasifikasi-arsip.create') }}">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Klasifikasi
            </x-button>
        </div>
    </div>

    {{-- Dasar Hukum Regulasi --}}
    <x-card class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 dark:from-slate-800 dark:to-slate-900 dark:border-slate-700">
        <div class="flex items-start gap-3">
            <svg class="h-6 w-6 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <div>
                <h4 class="text-sm font-bold text-gray-800 dark:text-slate-100">Dasar Hukum & Acuan Regulasi SIKD Provinsi Lampung:</h4>
                <ul class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-3 text-xs text-gray-600 dark:text-slate-300">
                    <li class="bg-white dark:bg-slate-800/80 p-2 rounded border border-blue-100">
                        <strong>1. Pola Klasifikasi:</strong><br>
                        Pergub Lampung No. 16 Tahun 2023
                    </li>
                    <li class="bg-white dark:bg-slate-800/80 p-2 rounded border border-blue-100">
                        <strong>2. Jadwal Retensi Arsip:</strong><br>
                        Pergub Lampung No. 41 Tahun 2025
                    </li>
                    <li class="bg-white dark:bg-slate-800/80 p-2 rounded border border-blue-100">
                        <strong>3. Keamanan & Hak Akses:</strong><br>
                        Pergub Lampung No. 17 Tahun 2023 (SKKAAD)
                    </li>
                </ul>
            </div>
        </div>
    </x-card>

    {{-- Filter & Search --}}
    <x-card>
        <form method="GET" action="{{ route('klasifikasi-arsip.index') }}" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari kode atau nama klasifikasi..."
                   class="form-input max-w-xs flex-1" />
            <x-button variant="primary" type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari
            </x-button>
            @if(request()->has('search'))
                <x-button variant="secondary" href="{{ route('klasifikasi-arsip.index') }}">Reset</x-button>
            @endif
        </form>
    </x-card>

    {{-- Table --}}
    <x-card class="p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="table-th w-24">Kode</th>
                        <th class="table-th w-1/3">Nama/Uraian Klasifikasi</th>
                        <th class="table-th">Induk (Parent)</th>
                        <th class="table-th text-center">Retensi (Aktif/Inaktif)</th>
                        <th class="table-th text-center">Nasib Akhir</th>
                        <th class="table-th text-center">Keamanan</th>
                        <th class="table-th text-center">Hak Akses</th>
                        <th class="table-th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white dark:bg-slate-800">
                    @forelse($klasifikasis as $k)
                    <tr class="hover:bg-gray-50 dark:bg-slate-800/50 transition">
                        <td class="table-td font-bold text-gray-800 dark:text-slate-100">
                            <a href="{{ route('klasifikasi-arsip.show', $k->id) }}" class="text-blue-600 hover:underline hover:text-blue-800">
                                {{ $k->kode }}
                            </a>
                        </td>
                        <td class="table-td text-gray-700">
                            <div class="max-w-xs md:max-w-md line-clamp-2" title="{{ $k->nama }}">
                                {{ $k->nama }}
                            </div>
                        </td>
                        <td class="table-td text-gray-500 dark:text-slate-400 text-sm">
                            {{ $k->parent ? $k->parent->kode . ' - ' . \Illuminate\Support\Str::limit($k->parent->nama, 30) : '-' }}
                        </td>
                        <td class="table-td text-center text-sm">
                            <x-badge type="blue">{{ $k->retensi_aktif_tahun }} th</x-badge> / 
                            <x-badge type="gray">{{ $k->retensi_inaktif_tahun }} th</x-badge>
                        </td>
                        <td class="table-td text-center">
                            @if($k->nasib_akhir == 'musnah')
                                <x-badge type="red">Musnah</x-badge>
                            @elseif($k->nasib_akhir == 'permanen')
                                <x-badge type="green">Permanen</x-badge>
                            @else
                                <x-badge type="yellow">Dinilai</x-badge>
                            @endif
                        </td>
                        <td class="table-td text-center">
                            @if($k->keamanan)
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full border bg-purple-50 text-purple-700 border-purple-200">
                                    {{ $k->keamanan }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="table-td text-center font-medium text-xs text-indigo-700">
                            {{ $k->hak_akses ?: '-' }}
                        </td>
                        <td class="table-td">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('klasifikasi-arsip.show', $k->id) }}"
                                   class="rounded p-1 text-blue-600 hover:bg-blue-50" title="Detail">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('klasifikasi-arsip.edit', $k->id) }}"
                                   class="rounded p-1 text-yellow-600 hover:bg-yellow-50" title="Edit">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('klasifikasi-arsip.destroy', $k->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                            class="delete-trigger rounded p-1 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30" title="Hapus">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-400">
                            <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <p class="text-sm italic">Belum ada data klasifikasi arsip.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="w-full min-w-full flex justify-center py-3">
                {{ $klasifikasis->withQueryString()->links() }}
            </div>
        </div>
    </x-card>
</div>

<div id="hapusModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-start gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-slate-100">Hapus Klasifikasi Arsip</h3>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Apakah Anda yakin ingin menghapus klasifikasi ini?</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 dark:text-slate-300 mb-6 ml-13 pl-13">Data arsip yang terhubung mungkin akan <strong>kehilangan referensi</strong>. Tindakan ini tidak dapat dikembalikan.</p>
        <div class="flex justify-end gap-3">
            <button type="button" id="batalHapusModal" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-slate-300 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-600 rounded-lg transition">Batal</button>
            <button type="button" id="konfirmasiHapusModal" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 rounded-lg transition">Ya, Hapus</button>
        </div>
    </div>
</div>
@endsection
