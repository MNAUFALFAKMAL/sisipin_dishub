@extends('layouts.app')
@section('title', 'Profil Saya')
@section('page-title', 'Pengaturan Profil')

@section('content')
<div class="max-w-2xl space-y-6">

    <div class="mb-4 flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Profil</span>
    </div>

    {{-- Profil Header Card --}}
    <x-card class="flex flex-col md:flex-row items-center gap-5">
        <div class="w-20 h-20 rounded-full bg-indigo-900 flex items-center justify-center text-white text-2xl font-bold border-4 border-indigo-50 shadow-inner">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="text-center md:text-left space-y-1">
            <h2 class="text-xl font-extrabold text-slate-800">{{ Auth::user()->name }}</h2>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 capitalize">
                {{ Auth::user()->role_label }}
            </span>
        </div>
    </x-card>

    {{-- Update Profile Info Form --}}
    <x-card>
        <h3 class="text-base font-bold text-slate-800 mb-1">Informasi Profil</h3>
        <p class="text-xs text-slate-500 mb-6">Perbarui nama dan alamat email akun Anda.</p>

        <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('patch')

            <div>
                <label for="name" class="form-label">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name', Auth::user()->name) }}" class="form-input" required autofocus />
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="form-label">Alamat Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', Auth::user()->email) }}" class="form-input" required />
                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-4 pt-2">
                <x-button variant="primary" type="submit">Simpan Profil</x-button>
                @if (session('status') === 'profile-updated')
                    <p class="text-sm text-emerald-600 font-medium">✓ Berhasil disimpan.</p>
                @endif
            </div>
        </form>
    </x-card>

</div>
@endsection
