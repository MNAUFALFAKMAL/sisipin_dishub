@extends('layouts.app')

@section('title', 'Tambah Template')
@section('page-title', 'Tambah Template')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="{{ route('templates.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Manajemen Template</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-800 dark:text-gray-200">Tambah Template Baru</span>
    </div>

    <div class="max-w-3xl mx-auto">
        <x-card>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Upload Template .docx</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Unggah template naskah dinas untuk ditampilkan di katalog Tata Naskah Dinas.</p>

            <form action="{{ route('templates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Nama Template --}}
                <div>
                    <label class="form-label">Nama Template</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input w-full @error('name') border-red-500 @enderror" placeholder="Contoh: Surat Perintah Dinas" required>
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="form-label">Kategori Naskah</label>
                    <select name="category" class="form-input w-full @error('category') border-red-500 @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Sub Kategori --}}
                <div>
                    <label class="form-label">Sub Kategori (contoh: "1. Surat Perintah")</label>
                    <input type="text" name="sub_kategori" value="{{ old('sub_kategori') }}" class="form-input w-full @error('sub_kategori') border-red-500 @enderror" placeholder="Contoh: 1. Surat Perintah, 2. Surat Tugas">
                    @error('sub_kategori')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Google Drive Link --}}
                <div>
                    <label class="form-label">Link Google Drive (opsional)</label>
                    <input type="url" name="google_drive_link" value="{{ old('google_drive_link') }}" class="form-input w-full @error('google_drive_link') border-red-500 @enderror" placeholder="https://drive.google.com/drive/folders/...">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Link langsung ke folder Google Drive untuk template ini.</p>
                    @error('google_drive_link')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- File .docx --}}
                <div>
                    <label class="form-label">File Template (.docx)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 transition-colors cursor-pointer" data-upload-area="file-input">
                        <div class="space-y-1 text-center" id="upload-area-docx">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <div class="flex flex-col text-sm text-gray-600 dark:text-gray-400">
                                <span class="font-medium text-blue-600 dark:text-blue-400">Klik untuk upload</span>
                                <span class="text-xs">atau seret file ke sini</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-500">Format: .docx</p>
                        </div>
                        <input id="file-input" name="file" type="file" accept=".docx" class="sr-only" required data-file-input data-name-id="file-name" data-area-id="upload-area-docx">
                    </div>
                    <p id="file-name" class="text-sm text-green-600 dark:text-green-400 mt-2 hidden"></p>
                    @error('file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- File PDF (optional, untuk preview) --}}
                <div>
                    <label class="form-label">File PDF untuk Pratinjau (opsional)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-amber-500 dark:hover:border-amber-400 transition-colors cursor-pointer" data-upload-area="pdf-input">
                        <div class="space-y-1 text-center" id="upload-area-pdf">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <div class="flex flex-col text-sm text-gray-600 dark:text-gray-400">
                                <span class="font-medium text-amber-600 dark:text-amber-400">Klik untuk upload (opsional)</span>
                                <span class="text-xs">untuk pratinjau PDF di landing page</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-500">Format: .pdf</p>
                        </div>
                        <input id="pdf-input" name="file_pdf" type="file" accept=".pdf" class="sr-only" data-file-input data-name-id="pdf-name" data-area-id="upload-area-pdf">
                    </div>
                    <p id="pdf-name" class="text-sm text-amber-600 dark:text-amber-400 mt-2 hidden"></p>
                    @error('file_pdf')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Tombol --}}
                <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <x-button variant="primary" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Upload Template
                    </x-button>
                    <x-button variant="secondary" href="{{ route('templates.index') }}">Batal</x-button>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection

