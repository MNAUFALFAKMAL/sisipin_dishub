@extends('layouts.app')

@section('title', 'Manajemen Template')
@section('page-title', 'Manajemen Template')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Manajemen Template</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Kelola template .docx untuk Tata Naskah Dinas</p>
        </div>
        <x-button variant="primary" href="{{ route('templates.create') }}" class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Tambah Template
        </x-button>
    </div>

    <!-- Search & Filter -->
    <x-card class="mb-6">
        <form action="{{ route('templates.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-grow">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama template atau kategori..." class="form-input w-full">
            </div>
            <x-button variant="primary" type="submit" class="px-8">Cari</x-button>
            <x-button variant="secondary" href="{{ route('templates.index') }}" class="px-8 text-center">Reset</x-button>
        </form>
    </x-card>

    <!-- Table -->
    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Nomor</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Nama Template</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Kategori</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Sub Kategori</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Status</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700 dark:text-gray-200 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($templates as $index => $template)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ $templates->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $template->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-500 truncate max-w-xs">{{ basename($template->file_path) }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 rounded text-xs">
                                {{ $template->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ $template->sub_kategori ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($template->is_active)
                                <span class="flex items-center gap-1.5 text-green-600 dark:text-green-400 text-xs font-medium">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-600 dark:bg-green-400"></span> Aktif
                                </span>
                            @else
                                <span class="flex items-center gap-1.5 text-gray-500 dark:text-gray-500 text-xs font-medium">
                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-500"></span> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('templates.edit', $template->id) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-all" title="Edit Mapping">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('templates.destroy', $template->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="delete-trigger rounded-lg p-2.5 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p>Belum ada template. Silakan tambah template baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($templates->hasPages())
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
            {{ $templates->links() }}
        </div>
        @endif
    </x-card>
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
