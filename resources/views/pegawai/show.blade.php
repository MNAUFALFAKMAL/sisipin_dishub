@extends('layouts.app')
@section('title', 'Detail Pegawai')
@section('page-title', 'Detail Pegawai')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Detail Pegawai</h2>
        <a href="{{ route('pegawai.index') }}" class="text-sm text-indigo-600 hover:underline dark:text-indigo-400">← Kembali ke Daftar</a>
    </div>

    <x-card class="bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-[#161b27] dark:border-[#1e2d45]">
        <div class="p-6">
            <div class="flex items-center gap-4 mb-6">
                <div class="flex-shrink-0 h-16 w-16 flex items-center justify-center rounded-full bg-blue-100 text-blue-800 font-bold text-2xl dark:bg-blue-900/50 dark:text-blue-200">
                    {{ strtoupper(substr($pegawai->nama, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $pegawai->nama }}</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $pegawai->status === 'aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                        {{ ucfirst($pegawai->status) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">NIP</label>
                    <p class="mt-1 text-sm text-gray-800 dark:text-gray-100 font-mono">{{ $pegawai->nip ?? '—' }}</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Unit / Bidang</label>
                    <p class="mt-1 text-sm text-gray-800 dark:text-gray-100">{{ $pegawai->unit_bidang ?? '—' }}</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pangkat</label>
                    <p class="mt-1 text-sm text-gray-800 dark:text-gray-100">{{ $pegawai->pangkat ?? '—' }}</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Golongan</label>
                    <p class="mt-1 text-sm text-gray-800 dark:text-gray-100">{{ $pegawai->golongan ?? '—' }}</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jabatan</label>
                    <p class="mt-1 text-sm text-gray-800 dark:text-gray-100">{{ $pegawai->jabatan ?? '—' }}</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Urutan Excel</label>
                    <p class="mt-1 text-sm text-gray-800 dark:text-gray-100">{{ $pegawai->urutan_excel ?? '—' }}</p>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t dark:border-[#1e2d45] flex justify-end gap-3">
                <a href="{{ route('pegawai.edit', $pegawai->id) }}"
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-[#1e2d45] dark:border-[#1e2d45] dark:text-gray-200 dark:hover:bg-[#253552]">
                    Edit
                </a>
            </div>
        </div>
    </x-card>
</div>
@endsection