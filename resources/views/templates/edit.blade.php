@extends('layouts.app')

@section('title', 'Edit Template')
@section('page-title', 'Edit Template')

@push('styles')
<style>[x-cloak] { display: none !important; }</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="{{ route('templates.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Manajemen Template</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-800 dark:text-gray-200 truncate max-w-sm">Edit: {{ $template->name }}</span>
    </div>

    <div class="max-w-4xl mx-auto" x-data="{ deleteOpen: false, deleteAction: '', deleteLabel: '' }">


        <form action="{{ route('templates.update', $template->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <x-card class="mb-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Detail Template</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Nama Template</label>
                        <input type="text" name="name" value="{{ old('name', $template->name) }}" class="form-input w-full @error('name') border-red-500 @enderror" required>
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Kategori Naskah</label>
                        <select name="category" class="form-input w-full @error('category') border-red-500 @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $template->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Sub Kategori</label>
                        <input type="text" name="sub_kategori" value="{{ old('sub_kategori', $template->sub_kategori) }}" class="form-input w-full @error('sub_kategori') border-red-500 @enderror" placeholder="Contoh: 1. Surat Perintah">
                        @error('sub_kategori')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Link Google Drive (opsional)</label>
                        <input type="url" name="google_drive_link" value="{{ old('google_drive_link', $template->google_drive_link) }}" class="form-input w-full @error('google_drive_link') border-red-500 @enderror" placeholder="https://drive.google.com/drive/folders/...">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Link langsung ke folder template ini di Google Drive.</p>
                        @error('google_drive_link')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Status</label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="is_active" value="1" class="form-radio" {{ old('is_active', $template->is_active) == 1 ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                            </label>
                            <label class="inline-flex items-center ml-6">
                                <input type="radio" name="is_active" value="0" class="form-radio" {{ old('is_active', $template->is_active) == 0 ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Nonaktif</span>
                            </label>
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card class="mb-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">File Template</h3>
                
                {{-- File DOCX --}}
                <div class="mb-6">
                    <label class="form-label">File .docx (Word)</label>
                    <div class="flex flex-wrap items-center gap-3">
                        @if($template->file_path)
                        <a href="{{ route('file.serve', $template->serve_file_path) }}" target="_blank" class="text-blue-600 dark:text-blue-400 text-sm hover:underline flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ basename($template->file_path) }}
                        </a>
                        <button type="button" @click="deleteAction = '{{ route('templates.remove-file', $template->id) }}'; deleteLabel = 'file .docx'; deleteOpen = true" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-xs font-medium hover:underline transition-colors">Hapus</button>
                        @else
                        <span class="text-sm text-gray-500">Belum ada file</span>
                        @endif
                    </div>
                    <div class="mt-2">
                        <div class="inline-flex items-stretch gap-0 rounded-lg border border-dashed border-gray-300 dark:border-gray-600 hover:border-blue-500 dark:hover:border-blue-400 transition-colors cursor-pointer" data-upload-area="file-input-docx">
                            <div class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300" id="upload-area-docx">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 dark:text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <span class="text-blue-600 dark:text-blue-400 font-medium">Pilih file .docx</span>
                            </div>
                        </div>
                        <input id="file-input-docx" type="file" name="file" accept=".docx" class="sr-only" data-file-input data-name-id="file-name-docx" data-area-id="upload-area-docx">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti file.</p>
                    </div>
                </div>

                {{-- File PDF --}}
                <div>
                    <label class="form-label">File PDF untuk Pratinjau</label>
                    <div class="flex flex-wrap items-center gap-3">
                        @if($template->file_pdf)
                        <a href="{{ route('file.serve', $template->serve_file_pdf) }}" target="_blank" class="text-amber-600 dark:text-amber-400 text-sm hover:underline flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            {{ basename($template->file_pdf) }}
                        </a>
                        <button type="button" @click="deleteAction = '{{ route('templates.remove-file-pdf', $template->id) }}'; deleteLabel = 'file PDF'; deleteOpen = true" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-xs font-medium hover:underline transition-colors">Hapus</button>
                        @else
                        <span class="text-sm text-gray-500">Belum ada file</span>
                        @endif
                    </div>
                    <div class="mt-2">
                        <div class="inline-flex items-stretch gap-0 rounded-lg border border-dashed border-gray-300 dark:border-gray-600 hover:border-amber-500 dark:hover:border-amber-400 transition-colors cursor-pointer" data-upload-area="file-input-pdf">
                            <div class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300" id="upload-area-pdf">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 dark:text-amber-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                <span class="text-amber-600 dark:text-amber-400 font-medium">Pilih file .pdf</span>
                            </div>
                        </div>
                        <input id="file-input-pdf" type="file" name="file_pdf" accept=".pdf" class="sr-only" data-file-input data-name-id="file-name-pdf" data-area-id="upload-area-pdf">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti file PDF.</p>
                    </div>
                </div>
            </x-card>

            <div class="flex items-center gap-3 mt-6">
                <x-button variant="primary" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Simpan Perubahan
                </x-button>
                <x-button variant="secondary" href="{{ route('templates.index') }}">Batal</x-button>
            </div>
        </form>

        {{-- Modal Konfirmasi Hapus File --}}
        <div x-cloak x-show="deleteOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" @keydown.escape.window="deleteOpen = false">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-md w-full p-6">
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-slate-100">Hapus File</h3>
                        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Hapus <span x-text="deleteLabel"></span> dari template ini?</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-slate-300 mb-6">File akan dihapus permanen dan <strong>tidak dapat dikembalikan</strong>.</p>
                <form method="POST" :action="deleteAction" x-ref="deleteForm" class="hidden">
                    @csrf
                </form>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="deleteOpen = false" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-slate-300 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-600 rounded-lg transition">Batal</button>
                    <button type="button" @click="$refs.deleteForm.submit()" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 rounded-lg transition">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection