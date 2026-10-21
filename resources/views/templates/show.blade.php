@extends('layouts.app')

@section('title', 'Detail Template')
@section('page-title', 'Detail Template')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="{{ route('templates.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Manajemen Template</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-800 dark:text-gray-200 truncate max-w-sm">Detail: {{ $template->name }}</span>
    </div>

    <div class="max-w-3xl mx-auto">
        <x-card>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Detail Template</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Template</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $template->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $template->category }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">File</label>
                    @if($template->file_path)
                        <a href="{{ route('file.serve', $template->serve_file_path) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
                            {{ basename($template->file_path) }}
                        </a>
                    @else
                        <p class="mt-1 text-sm text-gray-500">Belum ada file</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Google Drive</label>
                    @if($template->google_drive_link)
                        <a href="{{ $template->google_drive_link }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 dark:text-blue-400 hover:underline">
                            Buka Link Google Drive
                        </a>
                    @else
                        <p class="mt-1 text-sm text-gray-500">Tidak ada link</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $template->is_active ? 'Aktif' : 'Nonaktif' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dibuat Pada</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $template->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Diperbarui Pada</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $template->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Info Katalog</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    Template ini ditampilkan di halaman publik <strong>Tata Naskah Dinas</strong>
                    (kategori <strong>{{ $template->category }}</strong>
                    @if($template->sub_kategori) — <strong>{{ $template->sub_kategori }}</strong>@endif)
                    dan dapat diunduh oleh pengunjung tanpa login.
                </p>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-button variant="primary" href="{{ route('templates.edit', $template->id) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.232 5.232z" />
                    </svg>
                    Edit Template
                </x-button>
                <form action="{{ route('templates.destroy', $template->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="delete-trigger btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Template
                    </button>
                </form>
                <x-button variant="secondary" href="{{ route('templates.index') }}">Kembali</x-button>
            </div>
        </x-card>
    </div>
</div>

<div id="hapusModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-start gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-slate-100">Hapus Template</h3>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Apakah Anda yakin ingin menghapus template ini?</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 dark:text-slate-300 mb-6 ml-13 pl-13">Data dan berkas template <strong>tidak dapat dikembalikan</strong>.</p>
        <div class="flex justify-end gap-3">
            <button type="button" id="batalHapusModal" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-slate-300 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-600 rounded-lg transition">Batal</button>
            <button type="button" id="konfirmasiHapusModal" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 rounded-lg transition">Ya, Hapus</button>
        </div>
    </div>
</div>
@endsection
