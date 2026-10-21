@php
    $jenisActive = $suratMasuk->jenis_surat;
@endphp
@extends('layouts.app')
@section('title', 'Detail ' . $jenisActive)
@section('page-title', 'Detail ' . $jenisActive)

@section('content')
<div class="max-w-3xl">

    <div class="mb-4 flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('surat-masuk.index', ['jenis_surat' => $jenisActive]) }}" class="hover:text-indigo-600">{{ $jenisActive }}</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Detail</span>
    </div>

    <x-card>

        {{-- Header --}}
        <div class="flex items-center justify-between border-b pb-4">
            <h2 class="text-base font-bold text-gray-800">Informasi Detail {{ $jenisActive }}</h2>
            <div class="flex gap-2">
                <x-button variant="secondary" href="{{ route('surat-masuk.edit', $suratMasuk->id, ['tahun' => $suratMasuk->tahun]) }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </x-button>
                <x-button variant="secondary" href="{{ route('surat-masuk.index', ['jenis_surat' => $jenisActive, 'tahun' => $suratMasuk->tahun]) }}">← Kembali</x-button>
            </div>
        </div>

        {{-- Info List stacked in Excel/PDF header order --}}
        <div class="space-y-6">
            {{-- 1. Nomor Urut (Agenda) --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Nomor Urut (No. Agenda)</p>
                <p class="mt-1 text-lg font-bold text-indigo-700">{{ $suratMasuk->no_agenda ?: '-' }}</p>
            </div>

            {{-- 2. Tanggal (Terima) --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tanggal Masuk (Terima)</p>
                <p class="mt-1 text-gray-800 font-semibold">{{ $suratMasuk->tanggal_terima ? \Carbon\Carbon::parse($suratMasuk->tanggal_terima)->translatedFormat('d F Y') : '-' }}</p>
            </div>

            {{-- 3. Kode --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Kode Klasifikasi</p>
                <p class="mt-1 text-gray-800 font-semibold">
                    @if($suratMasuk->klasifikasi)
                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 ring-1 ring-inset ring-blue-700/10 mr-1.5">{{ $suratMasuk->klasifikasi->kode }}</span>
                        <span>{{ $suratMasuk->klasifikasi->nama }}</span>
                    @else
                        -
                    @endif
                </p>
            </div>

            {{-- 4. Tujuan (Pengirim) --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tujuan (Pengirim)</p>
                <p class="mt-1 text-gray-800 font-semibold">{{ $suratMasuk->pengirim ?? '-' }}</p>
            </div>

            {{-- 5. Nomor Surat --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Nomor Surat</p>
                <p class="mt-1 font-mono text-gray-800 font-semibold">{{ $suratMasuk->no_surat ?: '-' }}</p>
            </div>

            {{-- 6. Tanggal Surat --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tanggal Surat</p>
                <p class="mt-1 text-gray-800 font-semibold">{{ $suratMasuk->tanggal_surat ? \Carbon\Carbon::parse($suratMasuk->tanggal_surat)->translatedFormat('d F Y') : '-' }}</p>
            </div>

            {{-- 7. Uraian (Perihal) --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Uraian (Perihal)</p>
                <p class="mt-1 rounded-lg bg-gray-50 p-4 text-sm text-gray-700 whitespace-pre-wrap font-semibold">{{ $suratMasuk->perihal ?? '-' }}</p>
            </div>

            {{-- 8. Disposisi --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Disposisi</p>
                <div class="flex flex-wrap gap-1.5 mt-1">
                    @forelse($suratMasuk->bidangTujuans as $bid)
                        <x-badge type="blue">{{ $bid->nama_bidang }}</x-badge>
                    @empty
                        <span class="text-gray-500">-</span>
                    @endforelse
                </div>
            </div>

            {{-- Status Disposisi --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Status Disposisi</p>
                <p class="mt-1 font-semibold">
                    @if($suratMasuk->status_disposisi == 'selesai')
                        <x-badge type="green">Selesai</x-badge>
                    @elseif($suratMasuk->status_disposisi == 'diproses')
                        <x-badge type="yellow">Diproses</x-badge>
                    @else
                        <x-badge type="red">Belum</x-badge>
                    @endif
                </p>
            </div>

            {{-- 9. Keterangan --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Keterangan</p>
                <p class="mt-1 text-gray-800 font-semibold">{{ $suratMasuk->keterangan ?: '-' }}</p>
            </div>

            {{-- 10. Berkas Scan Surat --}}
            <div x-data="{ open: false, activePath: null, activeUrl: null, activeName: null, activeType: null }">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Berkas Scan Surat</p>
                <div class="mt-2">
                    @if(!empty($suratMasuk->file_surat))
                        @foreach($suratMasuk->file_surat as $path)
                            @php
                                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                $isPdf = $ext === 'pdf';
                                $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                                $fileType = $isPdf ? 'pdf' : ($isImage ? 'image' : 'file');
                                $fileUrl = route('file.serve', $path);
                                $fileName = basename($path);
                            @endphp
                            <button @click="activePath='{{ $path }}'; activeUrl='{{ $fileUrl }}'; activeName='{{ $fileName }}'; activeType='{{ $fileType }}'; open=true"
                                    class="inline-flex items-center gap-2.5 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-700 hover:border-indigo-300 hover:bg-indigo-50 transition-all dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-indigo-500 dark:hover:bg-indigo-900/20 mb-2">
                                <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $fileName }}</span>
                                <span class="text-xs text-gray-400 dark:text-gray-500">({{ strtoupper($ext) }})</span>
                            </button>
                        @endforeach

                        <div x-show="open"
                             x-cloak
                             class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             @click.self="open = false">
                            <div class="relative flex w-full max-w-4xl flex-col rounded-xl bg-white shadow-2xl dark:bg-gray-900 max-h-[90vh]"
                                 @click.stop>
                                <div class="flex items-center justify-between border-b px-6 py-3 dark:border-gray-700">
                                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200" x-text="activeName">Pratinjau Dokumen</h3>
                                    <div class="flex items-center gap-2">
                                        <a :href="activeUrl"
                                           target="_blank"
                                           class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-100 transition-colors dark:bg-indigo-900/30 dark:text-indigo-400">
                                            Buka di Tab Baru
                                        </a>
                                        <button @click="open = false"
                                                class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300">
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
                                        <div class="flex items-center justify-center h-[75vh] text-gray-500">
                                            <p>Pratinjau tidak tersedia untuk tipe file ini. <a :href="activeUrl" target="_blank" class="text-indigo-600 underline">Buka di tab baru</a></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    @else
                        <span class="text-gray-400 italic">Tidak ada berkas scan surat.</span>
                    @endif
                </div>
            </div>
        </div>

</x-card>
</div>
@endsection
