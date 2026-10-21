@extends('layouts.app')
@section('title', 'Klasifikasi Arsip')
@section('page-title', 'Katalog Klasifikasi Arsip')

@section('content')
<div class="space-y-4" x-data="klasifikasiIndex()">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-bold text-neutral-800 dark:text-neutral-100">Katalog Klasifikasi Arsip</h2>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">Pencarian dan katalog kode klasifikasi sesuai Pergub Lampung 16/2023</p>
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

    {{-- Bilah Pencarian & Saring --}}
    <x-card>
        <form method="GET" action="{{ route('klasifikasi-arsip.index') }}" class="space-y-3 sm:flex sm:items-center sm:justify-between sm:space-y-0" x-on:submit.prevent="submitForm">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto flex-1">
                <div class="relative flex-1 max-w-xs sm:max-w-md">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input 
                        type="text" 
                        name="search" 
                        x-model="searchQuery"
                        placeholder="Cari kode, nama, atau deskripsi..." 
                        class="input pl-10"
                        @input.debounce.300ms="debouncedSearch()"
                        @keydown.enter.prevent="debouncedSearch()"
                    >
                </div>
                <select name="tingkat" x-model="selectedTingkat" class="input w-40" @change="debouncedSearch()">
                    <option value="">Semua Tingkat</option>
                    <option value="1">Tingkat 1 (Utama)</option>
                    <option value="2">Tingkat 2 (Sub)</option>
                    <option value="3">Tingkat 3 (Detail)</option>
                    <option value="4">Tingkat 4</option>
                </select>
                <select name="masa_simpan" x-model="selectedMasaSimpan" class="input w-40" @change="debouncedSearch()">
                    <option value="">Semua Masa Simpan</option>
                    <option value="pendek">Pendek (< 5 thn)</option>
                    <option value="sedang">Sedang (5-10 thn)</option>
                    <option value="panjang">Panjang (10-25 thn)</option>
                    <option value="sangat_panjang">Sangat Panjang (> 25 thn)</option>
                </select>
                <select name="nasib_akhir" x-model="selectedNasibAkhir" class="input w-40" @change="debouncedSearch()">
                    <option value="">Semua Nasib Akhir</option>
                    <option value="musnah">Musnah</option>
                    <option value="permanen">Permanen</option>
                    <option value="dinilai">Dinilai</option>
                </select>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="btn-primary whitespace-nowrap">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Saring
                </button>
                <a href="{{ route('klasifikasi-arsip.index') }}" class="btn-secondary whitespace-nowrap" @click.prevent="resetFilters()">Reset</a>
            </div>
        </form>
    </x-card>

    {{-- Tabel Hierarkis Klasifikasi --}}
    <x-card class="p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700" role="treegrid" aria-label="Katalog klasifikasi arsip">
                <thead class="bg-neutral-50 dark:bg-neutral-800/50">
                    <tr>
                        <th class="table-th w-28" scope="col">Kode</th>
                        <th class="table-th" scope="col">Nama Klasifikasi</th>
                        <th class="table-th w-24 text-center" scope="col">Tingkat</th>
                        <th class="table-th w-40 text-center" scope="col">Masa Simpan</th>
                        <th class="table-th w-32 text-center" scope="col">Nasib Akhir</th>
                        <th class="table-th w-36 text-center" scope="col">Keamanan</th>
                        <th class="table-th text-right w-28" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700 bg-white dark:bg-neutral-950" x-ref="tableBody">
                    @if ($klasifikasis->isEmpty())
                        <tr>
                            <td colspan="7" class="py-12 text-center text-neutral-400 dark:text-neutral-500">
                                <svg class="mx-auto mb-3 h-10 w-10 text-neutral-300 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <p class="text-sm italic">Belum ada data klasifikasi arsip.</p>
                            </td>
                        </tr>
                    @else
                        @foreach ($klasifikasis as $klasifikasi)
                            @include('klasifikasi-arsip.partials.row', ['klasifikasi' => $klasifikasi, 'level' => 0])
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="w-full min-w-full flex justify-center py-3">
                {{ $klasifikasis->withQueryString()->links() }}
            </div>
        </div>
    </x-card>

</div>

<script>
function klasifikasiIndex() {
    return {
        searchQuery: '{{ request('search', '') }}',
        selectedTingkat: '{{ request('tingkat', '') }}',
        selectedMasaSimpan: '{{ request('masa_simpan', '') }}',
        selectedNasibAkhir: '{{ request('nasib_akhir', '') }}',
        debounceTimer: null,

        init() {
            // Initialize from URL params if needed
        },

        debouncedSearch() {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.submitForm();
            }, 300);
        },

        submitForm() {
            const params = new URLSearchParams();
            if (this.searchQuery) params.set('search', this.searchQuery);
            if (this.selectedTingkat) params.set('tingkat', this.selectedTingkat);
            if (this.selectedMasaSimpan) params.set('masa_simpan', this.selectedMasaSimpan);
            if (this.selectedNasibAkhir) params.set('nasib_akhir', this.selectedNasibAkhir);
            
            window.location.href = '{{ route('klasifikasi-arsip.index') }}?' + params.toString();
        },

        resetFilters() {
            this.searchQuery = '';
            this.selectedTingkat = '';
            this.selectedMasaSimpan = '';
            this.selectedNasibAkhir = '';
            window.location.href = '{{ route('klasifikasi-arsip.index') }}';
        }
    }
}
</script>
@endsection