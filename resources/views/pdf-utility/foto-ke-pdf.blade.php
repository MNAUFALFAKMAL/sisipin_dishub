@extends('layouts.app')

@section('title', 'Foto ke PDF')
@section('page-title', 'Foto ke PDF')

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
            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Foto ke PDF</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Konversi gambar menjadi 1 file PDF</p>
            </div>
        </div>

        <form action="{{ route('pdf-utility.images-to-pdf') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="form-img">
            @csrf
            <div>
                <label class="form-label">Judul Dokumen (opsional)</label>
                <input type="text" name="title" class="form-input w-full" placeholder="Contoh: Scan Dokumen Rapat">
            </div>
            <div>
                <label class="form-label">Upload Foto (multiple)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 transition-colors cursor-pointer" onclick="document.getElementById('img-input').click()">
                    <div class="space-y-1 text-center" id="upload-area-img">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div class="flex flex-col text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-medium text-blue-600 dark:text-blue-400">Klik untuk upload foto</span>
                            <span class="text-xs">JPG, PNG, GIF, WEBP — maks 20 MB per foto, hingga 20 foto</span>
                        </div>
                    </div>
                    <input id="img-input" name="images[]" type="file" accept="image/*" multiple class="sr-only">
                </div>
                <div id="img-list" class="mt-3 space-y-1"></div>
                @error('images.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="btn-primary w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/></svg>
                Konversi ke PDF
            </button>
        </form>
    </div>
</div>
@include('pdf-utility.partials.js-upload')
@include('pdf-utility.partials.js-submit-photo')
@endsection