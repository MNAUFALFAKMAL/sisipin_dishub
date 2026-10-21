@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tambah Pengguna</h2>
        <a href="{{ route('users.index') }}" class="text-sm text-indigo-600 hover:underline dark:text-indigo-400">← Kembali ke Daftar</a>
    </div>

    <x-card class="bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-[#161b27] dark:border-[#1e2d45]">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-6" autocomplete="off">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-[#0f172a] dark:border-[#1e2d45] dark:text-gray-100">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username') }}" required autocomplete="new-username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-[#0f172a] dark:border-[#1e2d45] dark:text-gray-100">
                    @error('username') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required autocomplete="new-password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-[#0f172a] dark:border-[#1e2d45] dark:text-gray-100">
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role <span class="text-red-500">*</span></label>
                    <select name="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-[#0f172a] dark:border-[#1e2d45] dark:text-gray-100">
                        <option value="">Pilih Role...</option>
                        <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="kepala_dinas" {{ old('role') == 'kepala_dinas' ? 'selected' : '' }}>Kepala Dinas</option>
                        <option value="kepala_bidang" {{ old('role') == 'kepala_bidang' ? 'selected' : '' }}>Kepala Bidang</option>
                        <option value="tata_usaha" {{ old('role') == 'tata_usaha' ? 'selected' : '' }}>Tata Usaha</option>
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                    @error('role') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bidang (Opsional)</label>
                    <select name="bidang_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-[#0f172a] dark:border-[#1e2d45] dark:text-gray-100">
                        <option value="">Tidak ada bidang</option>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}" {{ old('bidang_id') == $bidang->id ? 'selected' : '' }}>{{ $bidang->nama_bidang }}</option>
                        @endforeach
                    </select>
                    @error('bidang_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-[#1e2d45] dark:bg-[#0f172a]">
                <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Akun Aktif</label>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t dark:border-[#1e2d45]">
                <a href="{{ route('users.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-[#1e2d45] dark:border-[#1e2d45] dark:text-gray-200 dark:hover:bg-[#253552]">Batal</a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-[#161b27]">Simpan Pengguna</button>
            </div>
        </form>
    </x-card>
</div>
@endsection
