@php
    $jenisActive = $suratKeluar->jenis_surat;
@endphp
@extends('layouts.app')
@section('title', 'Detail Surat Keluar (' . $jenisActive . ')')
@section('page-title', 'Detail Surat Keluar (' . $jenisActive . ')')

@section('content')
<div class="max-w-3xl">

    <div class="mb-4 flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('surat-keluar.index', ['jenis_surat' => $jenisActive]) }}" class="hover:text-brand">
            Surat Keluar ({{ $jenisActive }})
        </a>
        <span>/</span>
        <span class="text-slate-800 font-medium">Detail</span>
    </div>

    <x-card>

        {{-- Header --}}
        <div class="flex items-center justify-between border-b pb-4">
            <h2 class="text-base font-bold text-slate-800">Informasi Detail Surat Keluar ({{ $jenisActive }})</h2>
            <div class="flex gap-2">
                @can('update', $suratKeluar)
                    <x-button variant="secondary" href="{{ route('surat-keluar.edit', $suratKeluar->id, ['tahun' => $suratKeluar->tahun]) }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </x-button>
                @endcan
                <x-button variant="secondary" href="{{ route('surat-keluar.index', ['jenis_surat' => $jenisActive, 'tahun' => $suratKeluar->tahun]) }}">← Kembali</x-button>
            </div>
        </div>

        {{-- Info List --}}
        <div class="space-y-6">
            {{-- 1. Nomor Urut (Agenda) --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nomor Urut (No. Agenda)</p>
                <p class="mt-1 text-lg font-bold text-indigo-700">{{ $suratKeluar->no_agenda ?: '-' }}</p>
            </div>

            {{-- 2. Tahun --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tahun</p>
                <p class="mt-1 text-slate-800 font-semibold">{{ $suratKeluar->tahun ?: '-' }}</p>
            </div>

            {{-- 3. Jenis Surat --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Jenis Surat</p>
                <p class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">{{ $suratKeluar->jenis_surat }}</span>
                </p>
            </div>

            {{-- 4. Nomor Surat --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nomor Surat</p>
                <p class="mt-1 font-mono text-slate-800 font-semibold">{{ $suratKeluar->no_surat ?: '-' }}</p>
            </div>

            {{-- 5. Tanggal Surat --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tanggal Surat</p>
                <p class="mt-1 text-slate-800 font-semibold">{{ $suratKeluar->tanggal_surat ? \Carbon\Carbon::parse($suratKeluar->tanggal_surat)->translatedFormat('d F Y') : '-' }}</p>
            </div>

            {{-- 6. Tanggal Keluar --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tanggal Keluar</p>
                <p class="mt-1 text-slate-800 font-semibold">{{ $suratKeluar->tanggal_terima ? \Carbon\Carbon::parse($suratKeluar->tanggal_terima)->translatedFormat('d F Y') : '-' }}</p>
            </div>

            {{-- 7. Tujuan --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tujuan Surat</p>
                <p class="mt-1 text-slate-800 font-semibold">{{ $suratKeluar->tujuan ?? '-' }}</p>
            </div>

            {{-- 8. Klasifikasi --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kode Klasifikasi</p>
                <p class="mt-1 text-slate-800 font-semibold">
                    @if($suratKeluar->klasifikasi)
                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 ring-1 ring-inset ring-blue-700/10 mr-1.5">{{ $suratKeluar->klasifikasi->kode }}</span>
                        <span>{{ $suratKeluar->klasifikasi->nama }}</span>
                    @else
                        -
                    @endif
                </p>
            </div>

            {{-- 9. Bidang --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Bidang</p>
                <p class="mt-1 text-slate-800 font-semibold">
                    @if($suratKeluar->bidang)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-green-50 text-green-700 ring-1 ring-inset ring-green-700/10 mr-1.5">{{ $suratKeluar->bidang->kode_bidang }}</span>
                        <span>{{ $suratKeluar->bidang->nama_bidang }}</span>
                    @else
                        -
                    @endif
                </p>
            </div>

            {{-- 10. Penandatangan --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Penandatangan</p>
                <p class="mt-1 text-slate-800 font-semibold">{{ $suratKeluar->penandatangan ?: '-' }}</p>
            </div>

            {{-- 11. Perihal --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Perihal / Uraian</p>
                <p class="mt-1 rounded-lg bg-slate-50 p-4 text-sm text-slate-700 whitespace-pre-wrap font-semibold">{{ $suratKeluar->perihal ?? '-' }}</p>
            </div>

            {{-- 12. Isi Ringkas --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Isi Ringkas</p>
                <p class="mt-1 rounded-lg bg-slate-50 p-4 text-sm text-slate-700 whitespace-pre-wrap">{{ $suratKeluar->isi_ringkas ?? '-' }}</p>
            </div>

            {{-- 13. Status --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Status</p>
                <p class="mt-1 font-semibold">
                    @if($suratKeluar->status == 'selesai')
                        <x-badge type="green">Selesai</x-badge>
                    @elseif($suratKeluar->status == 'dikirim')
                        <x-badge type="blue">Dikirim</x-badge>
                    @else
                        <x-badge type="yellow">Draft</x-badge>
                    @endif
                </p>
            </div>

            {{-- 14. Indeks Berkas --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Indeks Berkas</p>
                <p class="mt-1 text-slate-800 font-semibold">{{ $suratKeluar->indeks_berkas ?: '-' }}</p>
            </div>

            {{-- 15. Tingkat Keamanan --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tingkat Keamanan</p>
                <p class="mt-1 text-slate-800 font-semibold">
                    @if($suratKeluar->tingkat_keamanan)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($suratKeluar->tingkat_keamanan == 'Rahasia') bg-yellow-100 text-yellow-800
                            @elseif($suratKeluar->tingkat_keamanan == 'Sangat Rahasia') bg-red-100 text-red-800
                            @elseif($suratKeluar->tingkat_keamanan == 'Terbatas') bg-purple-100 text-purple-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ $suratKeluar->tingkat_keamanan }}
                        </span>
                    @else
                        -
                    @endif
                </p>
            </div>

            {{-- 16. Masa Simpan --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Masa Simpan</p>
                <p class="mt-1 text-slate-800 font-semibold">{{ $suratKeluar->masa_simpan ?: '-' }}</p>
            </div>

            {{-- 17. Keterangan --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Keterangan</p>
                <p class="mt-1 text-slate-800 font-semibold">{{ $suratKeluar->keterangan ?: '-' }}</p>
            </div>

            {{-- 18. Berkas Scan Surat --}}
            <div x-data="{ open: false, activePath: null, activeUrl: null, activeName: null, activeType: null }">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Berkas Scan Surat</p>
                <div class="mt-2">
                    @if(!empty($suratKeluar->file_surat))
                        @foreach($suratKeluar->file_surat as $path)
                            @php
                                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                $isPdf = $ext === 'pdf';
                                $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                                $fileType = $isPdf ? 'pdf' : ($isImage ? 'image' : 'file');
                                $fileUrl = route('file.serve', $path);
                                $fileName = basename($path);
                            @endphp
                            <button @click="activePath='{{ $path }}'; activeUrl='{{ $fileUrl }}'; activeName='{{ $fileName }}'; activeType='{{ $fileType }}'; open=true"
                                    class="inline-flex items-center gap-2.5 rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-700 hover:border-indigo-300 hover:bg-indigo-50 transition-all dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-indigo-500 dark:hover:bg-indigo-900/20 mb-2">
                                <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $fileName }}</span>
                                <span class="text-xs text-slate-400 dark:text-slate-500">({{ strtoupper($ext) }})</span>
                            </button>
                        @endforeach

                        <div x-show="open"
                             x-cloak
                             class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             @click.self="open = false">
                            <div class="relative flex w-full max-w-4xl flex-col rounded-xl bg-white shadow-2xl dark:bg-slate-900 max-h-[90vh]"
                                 @click.stop>
                                <div class="flex items-center justify-between border-b px-6 py-3 dark:border-slate-700">
                                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200" x-text="activeName">Pratinjau Dokumen</h3>
                                    <div class="flex items-center gap-2">
                                        <a :href="activeUrl"
                                           target="_blank"
                                           class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-100 transition-colors dark:bg-indigo-900/30 dark:text-indigo-400">
                                            Buka di Tab Baru
                                        </a>
                                        <button @click="open = false"
                                                class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex-1 overflow-auto p-2">
                                    <template x-if="activeType === 'pdf'">
                                        <embed :src="activeUrl + '#toolbar=1'"
                                               type="application/pdf"
                                               class="w-full border-0"
                                               style="height: 75vh;" />
                                    </template>
                                    <template x-if="activeType === 'image'">
                                        <img :src="activeUrl"
                                             :alt="activeName"
                                             class="w-full h-auto max-h-[75vh] object-contain" />
                                    </template>
                                    <template x-if="activeType === 'file'">
                                        <div class="flex items-center justify-center h-[75vh] text-slate-500">
                                            <p>Pratinjau tidak tersedia untuk tipe file ini. <a :href="activeUrl" target="_blank" class="text-indigo-600 underline">Buka di tab baru</a></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    @else
                        <span class="text-slate-400 italic">Tidak ada berkas scan surat.</span>
                    @endif
                </div>
            </div>
        </div>

    </x-card>
</div>
@endsection