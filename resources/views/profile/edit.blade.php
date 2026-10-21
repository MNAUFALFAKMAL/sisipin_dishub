@extends('layouts.app')
@section('title', 'Profil Saya')
@section('page-title', 'Pengaturan Profil')

@section('content')
<div class="max-w-2xl space-y-6">

    <div class="mb-4 flex items-center gap-2 text-sm text-neutral-500">
        <a href="{{ route('dashboard') }}" class="hover:text-primary">Dasbor</a>
        <span>/</span>
        <span class="text-neutral-800 font-medium">Profil</span>
    </div>

    {{-- Profil Header Card --}}
    <x-card class="flex flex-col md:flex-row items-center gap-5">
        <div class="w-20 h-20 rounded-full bg-primary flex items-center justify-center text-white text-2xl font-bold border-4 border-primary-light shadow-inner">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="text-center md:text-left space-y-1">
            <h2 class="text-xl font-extrabold text-neutral-800 dark:text-white">{{ Auth::user()->name }}</h2>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-primary-light text-primary capitalize">
                {{ Auth::user()->peran_label }}
            </span>
        </div>
    </x-card>

    {{-- Form Informasi Profil --}}
    <x-card>
        <h3 class="text-base font-bold text-neutral-800 dark:text-white mb-1">Informasi Profil</h3>
        <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-6">Perbarui nama dan alamat surel akun Anda.</p>

        <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('patch')

            <div>
                <label for="name" class="label">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name', Auth::user()->name) }}" class="input @error('name') input-error @enderror" required autofocus />
                @error('name')<p class="mt-1.5 text-xs text-danger">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="label">Surel</label>
                <input id="email" name="email" type="email" value="{{ old('email', Auth::user()->email) }}" class="input @error('email') input-error @enderror" required />
                @error('email')<p class="mt-1.5 text-xs text-danger">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-4 pt-2">
                <x-button variant="primary" type="submit">Simpan Profil</x-button>
                @if (session('status') === 'profile-updated')
                    <p class="text-sm text-success font-medium">✓ Berhasil disimpan.</p>
                @endif
            </div>
        </form>
    </x-card>

    {{-- Form Ubah Kata Sandi --}}
    <x-card>
        <h3 class="text-base font-bold text-neutral-800 dark:text-white mb-1">Ubah Kata Sandi</h3>
        <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-6">Pastikan akun Anda menggunakan kata sandi yang kuat dan unik.</p>

        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('put')

            <div>
                <label for="current_password" class="label">Kata Sandi Saat Ini</label>
                <input id="current_password" name="current_password" type="password" class="input @error('current_password') input-error @enderror" autocomplete="current-password" required />
                @error('current_password')<p class="mt-1.5 text-xs text-danger">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password" class="label">Kata Sandi Baru</label>
                <input id="password" name="password" type="password" class="input @error('password') input-error @enderror" autocomplete="new-password" required />
                @error('password')<p class="mt-1.5 text-xs text-danger">{{ $message }}</p>@enderror
                <p class="mt-1.5 text-xs text-neutral-500 dark:text-neutral-400">Minimal 8 karakter, huruf besar & kecil, angka, simbol</p>
            </div>

            <div>
                <label for="password_confirmation" class="label">Konfirmasi Kata Sandi Baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="input @error('password_confirmation') input-error @enderror" autocomplete="new-password" required />
                @error('password_confirmation')<p class="mt-1.5 text-xs text-danger">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-4 pt-2">
                <x-button variant="primary" type="submit">Simpan Kata Sandi</x-button>
                @if (session('status') === 'password-updated')
                    <p class="text-sm text-success font-medium">✓ Berhasil disimpan.</p>
                @endif
            </div>
        </form>
    </x-card>

</div>
@endsection