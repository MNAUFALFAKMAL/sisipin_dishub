@extends('layouts.app')

@section('title', 'Gabung PDF')
@section('page-title', 'Gabung PDF')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('pdf-utility.index') }}" class="flex items-center gap-1 text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Utilitas PDF
        </a>
    </div>

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 rounded-lg">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <div class="card p-6 md:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Gabung PDF</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Gabungkan beberapa file PDF menjadi satu</p>
            </div>
        </div>

        <form action="{{ route('pdf-utility.merge-pdfs') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="form-merge">
            @csrf
            <div>
                <label class="form-label">Judul Dokumen (opsional)</label>
                <input type="text" name="title" class="form-input w-full" placeholder="Contoh: Berkas Lengkap Pansel">
            </div>
            <div>
                <label class="form-label">Upload File PDF</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-purple-500 dark:hover:border-purple-400 transition-colors cursor-pointer" onclick="document.getElementById('pdf-input').click()">
                    <div class="space-y-1 text-center" id="upload-area-pdf-merge">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="flex flex-col text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-medium text-purple-600 dark:text-purple-400">Klik untuk upload PDF</span>
                            <span class="text-xs">PDF only — maks 20 MB per file, bisa pilih banyak sekaligus</span>
                        </div>
                    </div>
                    <input id="pdf-input" name="pdfs[]" type="file" accept=".pdf" multiple class="sr-only">
                </div>
                <div id="pdf-list" class="mt-3 space-y-1"></div>
                @error('pdfs.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="btn-primary w-full" style="background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z"/><path d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM15 11h2a1 1 0 100-2h-2a1 1 0 100 2z"/></svg>
                Gabungkan PDF
            </button>
        </form>
    </div>
</div>
@include('pdf-utility.partials.js-upload')
@include('pdf-utility.partials.js-submit-merge')
@endsection