@extends('layouts.app')
@section('title', 'Detail Klasifikasi Arsip')
@section('page-title', 'Detail Klasifikasi Arsip')

@section('content')
<div class="max-w-3xl">
    {{-- Breadcrumb Hierarki --}}
    @php
        $ancestors = [];
        $current = $klasifikasiArsip->parent;
        while ($current) {
            $ancestors[] = $current;
            $current = $current->parent;
        }
        $ancestors = array_reverse($ancestors);
    @endphp

    <nav class="mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2 text-sm text-neutral-500 dark:text-neutral-400 flex-wrap" role="list">
            <li>
                <a href="{{ route('klasifikasi-arsip.index') }}" class="hover:text-primary hover:underline">Katalog Klasifikasi Arsip</a>
            </li>
            @foreach ($ancestors as $ancestor)
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-neutral-300 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="{{ route('klasifikasi-arsip.show', $ancestor->id) }}" class="hover:text-primary hover:underline">{{ $ancestor->kode }} - {{ Str::limit($ancestor->nama, 30) }}</a>
                </li>
            @endforeach
            @if (!empty($ancestors))
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-neutral-300 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
            @endif
            <li class="text-neutral-800 dark:text-neutral-100 font-medium" aria-current="page">
                {{ $klasifikasiArsip->kode }} - {{ Str::limit($klasifikasiArsip->nama, 40) }}
            </li>
        </ol>
    </nav>

    <x-card>
        <header class="mb-6 pb-4 border-b border-neutral-200 dark:border-neutral-700">
            <div class="flex flex-wrap items-baseline gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-primary-light text-primary font-mono font-bold text-sm">
                    {{ $klasifikasiArsip->kode }}
                </span>
                <h2 class="text-xl font-bold text-neutral-800 dark:text-white">{{ $klasifikasiArsip->nama }}</h2>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Kolom Kiri --}}
            <div class="space-y-4">
                <div>
                    <label class="label text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Induk Klasifikasi</label>
                    <div class="text-sm text-neutral-800 dark:text-white">
                        @if($klasifikasiArsip->parent)
                            <a href="{{ route('klasifikasi-arsip.show', $klasifikasiArsip->parent->id) }}" class="text-primary hover:underline flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                {{ $klasifikasiArsip->parent->kode }} - {{ $klasifikasiArsip->parent->nama }}
                            </a>
                        @else
                            <span class="text-neutral-400 dark:text-neutral-500 italic">Tidak ada induk (Tingkat Utama)</span>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="label text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Tingkat Klasifikasi</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-lg font-medium text-sm
                        {{ $klasifikasiArsip->tingkat === 1 ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 
                           ($klasifikasiArsip->tingkat === 2 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 
                           ($klasifikasiArsip->tingkat === 3 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300')) }}">
                        {{ $klasifikasiArsip->tingkat_label }}
                    </span>
                </div>

                <div>
                    <label class="label text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Klasifikasi Keamanan (SKKAAD)</label>
                    @if($klasifikasiArsip->keamanan)
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800 font-medium text-sm">
                            {{ $klasifikasiArsip->keamanan_label }}
                        </span>
                    @else
                        <span class="text-neutral-400 dark:text-neutral-500 italic">Belum ditentukan</span>
                    @endif
                </div>

                <div>
                    <label class="label text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Hak Akses</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300 dark:border-indigo-800 font-mono font-semibold text-sm">
                        {{ $klasifikasiArsip->hak_akses ?: '-' }}
                    </span>
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <div class="space-y-4">
                <div class="bg-neutral-50 dark:bg-neutral-800/50 rounded-lg p-4">
                    <label class="label text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400 mb-2">Masa Simpan</label>
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="bg-white dark:bg-neutral-900 rounded-lg p-3 border border-neutral-200 dark:border-neutral-700">
                            <div class="text-2xl font-bold text-primary dark:text-primary-light">{{ $klasifikasiArsip->retensi_aktif_tahun }}</div>
                            <div class="text-xs text-neutral-500 dark:text-neutral-400">Aktif (Tahun)</div>
                        </div>
                        <div class="bg-white dark:bg-neutral-900 rounded-lg p-3 border border-neutral-200 dark:border-neutral-700">
                            <div class="text-2xl font-bold text-neutral-600 dark:text-neutral-400">{{ $klasifikasiArsip->retensi_inaktif_tahun }}</div>
                            <div class="text-xs text-neutral-500 dark:text-neutral-400">Inaktif (Tahun)</div>
                        </div>
                    </div>
                    <div class="mt-2 text-center text-sm text-neutral-600 dark:text-neutral-400">
                        Total Masa Simpan: <span class="font-semibold text-neutral-800 dark:text-white">{{ $klasifikasiArsip->total_masa_simpan }} Tahun</span>
                    </div>
                </div>

                <div>
                    <label class="label text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Nasib Akhir</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-lg font-medium text-sm {{ $klasifikasiArsip->nasib_akhir_badge_class }}">
                        {{ $klasifikasiArsip->nasib_akhir_label }}
                    </span>
                </div>

                <div>
                    <label class="label text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Unit Pengolah</label>
                    <span class="text-sm text-neutral-800 dark:text-white">{{ $klasifikasiArsip->unit_pengolah ?: '-' }}</span>
                </div>

                <div>
                    <label class="label text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Dasar Pertimbangan</label>
                    <div class="text-sm text-neutral-600 dark:text-neutral-400 whitespace-pre-wrap bg-neutral-50 dark:bg-neutral-800/50 p-3 rounded-lg border border-neutral-200 dark:border-neutral-700 min-h-[80px]">
                        {{ $klasifikasiArsip->dasar_pertimbangan ?: '<span class="italic text-neutral-400">Belum diisi</span>' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Sub-Klasifikasi (Anak) --}}
        @if ($klasifikasiArsip->children && $klasifikasiArsip->children->count() > 0)
            <div class="mt-6 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-white mb-4">Sub-Klasifikasi ({{ $klasifikasiArsip->children->count() }})</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                        <thead class="bg-neutral-50 dark:bg-neutral-800/50">
                            <tr>
                                <th class="table-th w-28" scope="col">Kode</th>
                                <th class="table-th" scope="col">Nama Klasifikasi</th>
                                <th class="table-th w-24 text-center" scope="col">Tingkat</th>
                                <th class="table-th w-40 text-center" scope="col">Masa Simpan</th>
                                <th class="table-th w-32 text-center" scope="col">Nasib Akhir</th>
                                <th class="table-th text-right w-24" scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700 bg-white dark:bg-neutral-950">
                            @foreach ($klasifikasiArsip->children as $child)
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/40 transition">
                                    <td class="table-td font-mono font-bold text-neutral-800 dark:text-neutral-100">
                                        <a href="{{ route('klasifikasi-arsip.show', $child->id) }}" class="text-primary hover:underline hover:text-primary-dark">
                                            {{ $child->kode }}
                                        </a>
                                    </td>
                                    <td class="table-td text-neutral-700 dark:text-neutral-300">
                                        <div class="max-w-xs md:max-w-md line-clamp-2" title="{{ $child->nama }}">
                                            {{ $child->nama }}
                                        </div>
                                    </td>
                                    <td class="table-td text-center">
                                        @php
                                            $childTingkat = $child->tingkat;
                                            $tingkatLabels = [1 => 'Utama', 2 => 'Sub', 3 => 'Detail', 4 => 'Sub-Detail'];
                                        @endphp
                                        <x-badge type="{{ $childTingkat === 1 ? 'blue' : ($childTingkat === 2 ? 'green' : ($childTingkat === 3 ? 'yellow' : 'purple')) }}">
                                            {{ $tingkatLabels[$childTingkat] ?? "Tingkat {$childTingkat}" }}
                                        </x-badge>
                                    </td>
                                    <td class="table-td text-center text-sm">
                                        <span class="inline-flex items-center gap-1">
                                            <x-badge type="blue">{{ $child->retensi_aktif_tahun }} th</x-badge> / 
                                            <x-badge type="gray">{{ $child->retensi_inaktif_tahun }} th</x-badge>
                                        </span>
                                    </td>
                                    <td class="table-td text-center">
                                        <x-badge type="{{ $child->nasib_akhir_badge_class }}">
                                            {{ $child->nasib_akhir_label }}
                                        </x-badge>
                                    </td>
                                    <td class="table-td">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('klasifikasi-arsip.show', $child->id) }}"
                                               class="rounded p-1 text-primary hover:bg-primary-light dark:hover:bg-primary/20" title="Lihat Detail">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </a>
                                            @can('klasifikasi-arsip.edit', $child)
                                                <a href="{{ route('klasifikasi-arsip.edit', $child->id) }}"
                                                   class="rounded p-1 text-warning hover:bg-yellow-50 dark:hover:bg-yellow-900/20" title="Sunting">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Tombol Aksi --}}
        <div class="mt-6 pt-6 border-t border-neutral-200 dark:border-neutral-700 flex flex-wrap gap-3">
            @can('klasifikasi-arsip.edit', $klasifikasiArsip)
                <x-button variant="primary" href="{{ route('klasifikasi-arsip.edit', $klasifikasiArsip->id) }}">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Sunting Data
                </x-button>
            @endcan
            <x-button variant="secondary" href="{{ route('klasifikasi-arsip.index') }}">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Katalog
            </x-button>
        </div>
    </x-card>
</div>
@endsection