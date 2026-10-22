@extends('layouts.app')
@section('title', 'Detail Disposisi')
@section('page-title', 'Detail Disposisi')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Breadcrumbs --}}
    <div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('disposisi.index') }}" class="hover:text-brand">Disposisi</a>
        <span>/</span>
        <span class="text-slate-800 font-medium">Detail</span>
    </div>

    <x-card>

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b pb-4">
            <div>
                <h2 class="text-base font-bold text-slate-800">Disposisi Surat Masuk</h2>
                <p class="text-sm text-slate-500">No. Agenda: {{ $disposisi->suratMasuk->no_agenda }} | {{ $disposisi->suratMasuk->no_surat }}</p>
            </div>
            <div class="flex gap-2">
                <x-button variant="secondary" href="{{ route('surat-masuk.show', $disposisi->suratMasuk) }}">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Lihat Surat
                </x-button>
                <x-button variant="secondary" href="{{ route('disposisi.index') }}">← Kembali</x-button>
            </div>
        </div>

        {{-- Info Disposisi --}}
        <div class="space-y-6 mt-6">
            {{-- 1. Surat Masuk --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Surat Masuk</p>
                <a href="{{ route('surat-masuk.show', $disposisi->suratMasuk) }}" class="font-semibold text-indigo-700 hover:underline mt-1 block">
                    {{ $disposisi->suratMasuk->no_surat }} - {{ Str::limit($disposisi->suratMasuk->perihal, 80) }}
                </a>
                <p class="mt-1 text-sm text-slate-500">No. Agenda: {{ $disposisi->suratMasuk->no_agenda }} | Tanggal: {{ $disposisi->suratMasuk->tanggal_terima ? \Carbon\Carbon::parse($disposisi->suratMasuk->tanggal_terima)->translatedFormat('d F Y') : '-' }}</p>
            </div>

            {{-- 2. Dari --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Disposisi Dari</p>
                <p class="mt-1 text-slate-800 font-semibold">{{ $disposisi->dariUser->name ?? '-' }}</p>
                <p class="text-sm text-slate-500">{{ $disposisi->dariUser->peran?->nama ?? '-' }}</p>
            </div>

            {{-- 3. Kepada --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Disposisi Kepada</p>
                <p class="mt-1 text-slate-800 font-semibold">{{ $disposisi->kepadaUser->name ?? '-' }}</p>
                @if($disposisi->kepadaBidang)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 mt-1 inline-block">
                        {{ $disposisi->kepadaBidang->kode_bidang }} - {{ $disposisi->kepadaBidang->nama_bidang }}
                    </span>
                @endif
            </div>

            {{-- 4. Instruksi --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Instruksi</p>
                <p class="mt-1 rounded-lg bg-slate-50 p-4 text-sm text-slate-700 whitespace-pre-wrap">{{ $disposisi->instruksi }}</p>
            </div>

            {{-- 5. Prioritas & Sifat --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b pb-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Prioritas</p>
                    <p class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($disposisi->prioritas == 'mendesak') bg-red-100 text-red-800
                            @elseif($disposisi->prioritas == 'segera') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ $disposisi->prioritas_label }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Sifat</p>
                    <p class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($disposisi->sifat == 'rahasia') bg-yellow-100 text-yellow-800
                            @elseif($disposisi->sifat == 'terbatas') bg-purple-100 text-purple-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ $disposisi->sifat_label }}
                        </span>
                    </p>
                </div>
            </div>

            {{-- 5. Batas Waktu --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Batas Waktu</p>
                <p class="mt-1 text-slate-800 font-semibold">
                    {{ $disposisi->batas_waktu ? \Carbon\Carbon::parse($disposisi->batas_waktu)->translatedFormat('d F Y H:i') : '-' }}
                    @if($disposisi->is_overdue)
                        <span class="ml-2 text-red-600 text-sm font-bold">(TERLAMBAT)</span>
                    @endif
                    @if($disposisi->remaining_time)
                        <span class="ml-2 text-sm text-slate-500">({{ $disposisi->remaining_time }})</span>
                    @endif
                </p>
            </div>

            {{-- 6. Status --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Status</p>
                <p class="mt-1 font-semibold">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($disposisi->status == 'selesai') bg-green-100 text-green-800
                        @elseif($disposisi->status == 'diproses') bg-blue-100 text-blue-800
                        @elseif($disposisi->status == 'dibaca') bg-yellow-100 text-yellow-800
                        @elseif($disposisi->status == 'ditolak') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $disposisi->status_label }}
                    </span>
                    <span class="ml-2 text-sm text-slate-500">Progress: {{ $disposisi->progress_percentage }}%</span>
                </p>
            </div>

            {{-- 6. Catatan --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Catatan Instruksi</p>
                <p class="mt-1 text-slate-700 whitespace-pre-wrap">{{ $disposisi->catatan ?? '-' }}</p>
            </div>

            {{-- 7. Timeline --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Timeline</p>
                <div class="mt-3 space-y-2 text-sm">
                    <div class="flex items-center gap-2 text-slate-600">
                        <span class="w-32 font-medium">Dibuat:</span>
                        <span>{{ $disposisi->tanggal_disposisi ? \Carbon\Carbon::parse($disposisi->tanggal_disposisi)->translatedFormat('d F Y H:i') : '-' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-600">
                        <span class="w-32 font-medium">Dibaca:</span>
                        <span>{{ $disposisi->tanggal_baca ? \Carbon\Carbon::parse($disposisi->tanggal_baca)->translatedFormat('d F Y H:i') : 'Belum dibaca' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-600">
                        <span class="w-32 font-medium">Diproses:</span>
                        <span>{{ $disposisi->tanggal_selesai && in_array($disposisi->status, ['diproses','selesai','ditolak']) ? \Carbon\Carbon::parse($disposisi->tanggal_selesai)->translatedFormat('d F Y H:i') : 'Belum diproses' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-600">
                        <span class="w-32 font-medium">Selesai:</span>
                        <span>{{ $disposisi->tanggal_selesai ? \Carbon\Carbon::parse($disposisi->tanggal_selesai)->translatedFormat('d F Y H:i') : 'Belum selesai' }}</span>
                    </div>
                </div>
            </div>

            {{-- 8. Catatan / Hasil --}}
            <div class="border-b pb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Catatan / Hasil</p>
                <p class="mt-1 text-slate-700 whitespace-pre-wrap">{{ $disposisi->catatan ?? '-' }}</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-4 border-t">
            @can('update', $disposisi)
                <a href="{{ route('disposisi.edit', $disposisi) }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Instruksi
                </a>
            @endcan

            @can('delete', $disposisi)
                <form action="{{ route('disposisi.destroy', $disposisi) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors"
                        onclick="return confirm('Yakin hapus disposisi ini?')">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
            @endcan

            @can('proses', $disposisi)
                @if($disposisi->status !== 'selesai' && $disposisi->status !== 'ditolak')
                    <div x-data="{ showProses: false }" class="flex-1">
                        <button @click="showProses = true"
                            class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Proses Disposisi
                        </button>

                        {{-- Modal Proses --}}
                        <div x-show="showProses" x-transition x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
                            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl max-w-md w-full p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-slate-800">Proses Disposisi</h3>
                                    <button @click="showProses = false" class="text-slate-400 hover:text-slate-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <form action="{{ route('disposisi.proses', $disposisi) }}" method="POST">
                                    @csrf @method('POST')
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                                        <select name="status_disposisi" required
                                            class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium appearance-none bg-no-repeat bg-right">
                                            <option value="diproses">Diproses</option>
                                            <option value="selesai">Selesai</option>
                                            <option value="ditolak">Ditolak</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Catatan Hasil <span class="text-red-500">*</span></label>
                                        <textarea name="catatan_hasil" rows="4" required
                                            class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner"
                                            placeholder="Tuliskan catatan/hasil tindak lanjut..."></textarea>
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="showProses = false"
                                            class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-colors">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endcan
        </div>

    </x-card>
</div>
@endsection