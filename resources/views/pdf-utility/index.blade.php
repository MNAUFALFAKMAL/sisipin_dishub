@extends('layouts.app')

@section('title', 'Utilitas PDF')
@section('page-title', 'Utilitas PDF')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Utilitas PDF</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pilih alat yang ingin digunakan</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Foto ke PDF --}}
        <a href="{{ route('pdf-utility.foto-ke-pdf') }}"
           class="card p-6 md:p-8 group transition-all hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Foto ke PDF</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">JPG, PNG, GIF, WEBP</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">Konversi beberapa gambar menjadi 1 file PDF.</p>
            <span class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 dark:text-blue-400 group-hover:gap-2 transition-all">
                Buka alat
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </span>
        </a>

        {{-- Word ke PDF --}}
        <a href="{{ route('pdf-utility.word-ke-pdf') }}"
           class="card p-6 md:p-8 group transition hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Word ke PDF</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">DOCX</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">Konversi dokumen Word (.docx) menjadi PDF.</p>
            <span class="inline-flex items-center gap-1 text-sm font-semibold text-green-600 dark:text-green-400 group-hover:gap-2 transition-all">
                Buka alat
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </span>
        </a>

        {{-- Gabung PDF --}}
        <a href="{{ route('pdf-utility.gabung-pdf') }}"
           class="card p-6 md:p-8 group transition hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Gabung PDF</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">PDF</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">Gabungkan beberapa file PDF menjadi satu file.</p>
            <span class="inline-flex items-center gap-1 text-sm font-semibold text-purple-600 dark:text-purple-400 group-hover:gap-2 transition-all">
                Buka alat
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </span>
        </a>
    </div>

    <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
        <div class="flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-blue-800 dark:text-blue-300">
                <p class="font-semibold mb-1">Catatan:</p>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>Foto (JPG/PNG/GIF/WEBP) & Word (.docx) dikonversi ke PDF — maks 20 MB per file</li>
                    <li>PDF digabung mengikuti urutan upload — maks 20 MB per file, hingga 20 file</li>
                    <li>Foto ke PDF hingga 20 foto sekaligus — hasil langsung ter-download</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection