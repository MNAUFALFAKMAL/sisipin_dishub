@extends('layouts.app')
@section('title', 'Edit Pegawai')
@section('page-title', 'Edit Pegawai')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Pegawai</h2>
        <a href="{{ route('pegawai.index') }}" class="text-sm text-indigo-600 hover:underline dark:text-indigo-400">← Kembali ke Daftar</a>
    </div>

    <x-card class="bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-[#161b27] dark:border-[#1e2d45]">
        <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" class="space-y-6" autocomplete="off">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $pegawai->nama) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-[#0f172a] dark:border-[#1e2d45] dark:text-gray-100">
                    @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip', $pegawai->nip) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-[#0f172a] dark:border-[#1e2d45] dark:text-gray-100">
                    @error('nip') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pangkat</label>
                    <input type="text" name="pangkat" value="{{ old('pangkat', $pegawai->pangkat) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-[#0f172a] dark:border-[#1e2d45] dark:text-gray-100">
                    @error('pangkat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Golongan</label>
                    <input type="text" name="golongan" value="{{ old('golongan', $pegawai->golongan) }}" placeholder="Contoh: III/a" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-[#0f172a] dark:border-[#1e2d45] dark:text-gray-100">
                    @error('golongan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $pegawai->jabatan) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-[#0f172a] dark:border-[#1e2d45] dark:text-gray-100">
                    @error('jabatan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit / Bidang <span class="text-red-500">*</span></label>
                    <select name="unit_bidang" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-[#0f172a] dark:border-[#1e2d45] dark:text-gray-100">
                        <option value="">-- Pilih Bidang --</option>
                        <option value="BIDANG TEKNIK SARANA DAN PRASARANA" {{ old('unit_bidang', $pegawai->unit_bidang) == 'BIDANG TEKNIK SARANA DAN PRASARANA' ? 'selected' : '' }}>BIDANG TEKNIK SARANA DAN PRASARANA</option>
                        <option value="SEKRETARIAT" {{ old('unit_bidang', $pegawai->unit_bidang) == 'SEKRETARIAT' ? 'selected' : '' }}>SEKRETARIAT</option>
                        <option value="BIDANG LALU LINTAS DAN ANGKUTAN" {{ old('unit_bidang', $pegawai->unit_bidang) == 'BIDANG LALU LINTAS DAN ANGKUTAN' ? 'selected' : '' }}>BIDANG LALU LINTAS DAN ANGKUTAN</option>
                        <option value="BIDANG PEMBINAAN KESELAMATAN TRANSPORTASI" {{ old('unit_bidang', $pegawai->unit_bidang) == 'BIDANG PEMBINAAN KESELAMATAN TRANSPORTASI' ? 'selected' : '' }}>BIDANG PEMBINAAN KESELAMATAN TRANSPORTASI</option>
                        <option value="BIDANG PENGEMBANGAN TRANSPORTASI" {{ old('unit_bidang', $pegawai->unit_bidang) == 'BIDANG PENGEMBANGAN TRANSPORTASI' ? 'selected' : '' }}>BIDANG PENGEMBANGAN TRANSPORTASI</option>
                        <option value="UPTD PENGELOLA PRASARANA PERHUBUNGAN LALU LINTAS DAN ANGKUTAN JALAN" {{ old('unit_bidang', $pegawai->unit_bidang) == 'UPTD PENGELOLA PRASARANA PERHUBUNGAN LALU LINTAS DAN ANGKUTAN JALAN' ? 'selected' : '' }}>UPTD PENGELOLA PRASARANA PERHUBUNGAN LALU LINTAS DAN ANGKUTAN JALAN</option>
                        <option value="UPTD TERMINAL" {{ old('unit_bidang', $pegawai->unit_bidang) == 'UPTD TERMINAL' ? 'selected' : '' }}>UPTD TERMINAL</option>
                        <option value="UPTD KEPELABUHAN" {{ old('unit_bidang', $pegawai->unit_bidang) == 'UPTD KEPELABUHAN' ? 'selected' : '' }}>UPTD KEPELABUHAN</option>
                    </select>
                    @error('unit_bidang') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
                <select name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-[#0f172a] dark:border-[#1e2d45] dark:text-gray-100">
                    <option value="aktif" {{ old('status', $pegawai->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak_aktif" {{ old('status', $pegawai->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t dark:border-[#1e2d45]">
                <a href="{{ route('pegawai.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-[#1e2d45] dark:border-[#1e2d45] dark:text-gray-200 dark:hover:bg-[#253552]">Batal</a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-[#161b27]">Simpan Perubahan</button>
            </div>
        </form>
    </x-card>
</div>
@endsection
