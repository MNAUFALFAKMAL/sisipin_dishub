@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Tambah Pengguna</h2>
        <a href="{{ route('users.index') }}" class="text-sm text-primary hover:underline dark:text-primary-light">← Kembali ke Daftar</a>
    </div>

    <x-card>
        <form action="{{ route('users.store') }}" method="POST" class="space-y-6" autocomplete="off">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input @error('name') input-error @enderror" autofocus>
                    @error('name') <p class="mt-1.5 text-sm text-danger">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label">Surel <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="input @error('email') input-error @enderror">
                    @error('email') <p class="mt-1.5 text-sm text-danger">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label">Kata Sandi <span class="text-danger">*</span></label>
                    <input type="password" name="password" required autocomplete="new-password" class="input @error('password') input-error @enderror">
                    @error('password') <p class="mt-1.5 text-sm text-danger">{{ $message }}</p> @enderror
                    <p class="mt-1.5 text-sm text-neutral-500 dark:text-neutral-400">Minimal 8 karakter, huruf besar & kecil, angka, simbol</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label">Peran <span class="text-danger">*</span></label>
                    <select name="peran" required class="input @error('peran') input-error @enderror">
                        <option value="">Pilih Peran...</option>
                        <option value="pengelola_sistem" {{ old('peran') == 'pengelola_sistem' ? 'selected' : '' }}>Pengelola Sistem</option>
                        <option value="kasubbag" {{ old('peran') == 'kasubbag' ? 'selected' : '' }}>Kasubbag</option>
                        <option value="staff_subbagian" {{ old('peran') == 'staff_subbagian' ? 'selected' : '' }}>Staff Subbagian</option>
                    </select>
                    @error('peran') <p class="mt-1.5 text-sm text-danger">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label">Bidang (Opsional)</label>
                    <select name="bidang_id" class="input @error('bidang_id') input-error @enderror">
                        <option value="">Tidak ada bidang</option>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}" {{ old('bidang_id') == $bidang->id ? 'selected' : '' }}>{{ $bidang->nama_bidang }}</option>
                        @endforeach
                    </select>
                    @error('bidang_id') <p class="mt-1.5 text-sm text-danger">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="w-4 h-4 text-primary border-neutral-300 rounded focus:ring-primary focus:ring-2">
                <label for="is_active" class="ml-2 text-sm text-neutral-700 dark:text-neutral-300 cursor-pointer">Akun Aktif</label>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t dark:border-neutral-700">
                <a href="{{ route('users.index') }}" class="btn-secondary">Batal</a>
                <x-button variant="primary" type="submit">Simpan Pengguna</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection