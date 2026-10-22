@extends('layouts.app')
@section('title', 'Edit Disposisi')
@section('page-title', 'Edit Disposisi')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Breadcrumbs --}}
    <div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('disposisi.index') }}" class="hover:text-brand">Disposisi</a>
        <span>/</span>
        <span class="text-slate-800 font-medium">Edit</span>
    </div>

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <x-card>
        <h2 class="mb-6 text-lg font-bold text-slate-800 border-b dark:border-slate-700 pb-4">Edit Disposisi</h2>

        <form method="POST" action="{{ route('disposisi.update', $disposisi) }}" class="space-y-6">
            @csrf
            @method('PUT')

            @if($disposisi->status !== 'baru')
                <div class="p-4 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-800" role="alert">
                    <p class="font-medium">Perhatian:</p>
                    <p class="mt-1 text-sm">Disposisi ini tidak dapat diedit karena statusnya sudah <strong>{{ $disposisi->status_label }}</strong>.</p>
                </div>
            @endif

            {{-- Penerima (User) --}}
            <div>
                <label for="kepada_user_id" class="block text-sm font-semibold text-slate-700 mb-1">Kepada (User) <span class="text-red-500">*</span></label>
                <select name="kepada_user_id" id="kepada_user_id"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium appearance-none bg-no-repeat bg-right @error('kepada_user_id') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        @if($disposisi->status !== 'baru') disabled @endif
                        required>
                    <option value="">-- Pilih Penerima --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('kepada_user_id', $disposisi->kepada_user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->peran?->nama ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @error('kepada_user_id')
                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Bidang Tujuan (Optional) --}}
            <div>
                <label for="kepada_bidang_id" class="block text-sm font-semibold text-slate-700 mb-1">Bidang Tujuan (Opsional)</label>
                <select name="kepada_bidang_id" id="kepada_bidang_id"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium appearance-none bg-no-repeat bg-right"
                        @if($disposisi->status !== 'baru') disabled @endif>
                    <option value="">-- Pilih Bidang (Opsional) --</option>
                    @foreach($bidangs as $bidang)
                        <option value="{{ $bidang->id }}" {{ old('kepada_bidang_id', $disposisi->kepada_bidang_id) == $bidang->id ? 'selected' : '' }}>
                            {{ $bidang->nama_bidang }} ({{ $bidang->kode_bidang }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Instruksi --}}
            <div class="sm:col-span-2">
                <label for="instruksi" class="block text-sm font-semibold text-slate-700 mb-1">Instruksi Disposisi <span class="text-red-500">*</span></label>
                <textarea name="instruksi" id="instruksi" rows="4"
                    class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('instruksi') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                    @if($disposisi->status !== 'baru') readonly @endif
                    placeholder="Tuliskan instruksi/tindakan yang harus dilakukan...">{{ old('instruksi', $disposisi->instruksi) }}</textarea>
                @error('instruksi')
                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Grid for Prioritas, Sifat, Batas Waktu --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                {{-- Prioritas --}}
                <div>
                    <label for="prioritas" class="block text-sm font-semibold text-slate-700 mb-1">Prioritas <span class="text-red-500">*</span></label>
                    <select name="prioritas" id="prioritas"
                            class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium appearance-none bg-no-repeat bg-right @error('prioritas') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                            @if($disposisi->status !== 'baru') disabled @endif
                            required>
                        <option value="biasa" {{ old('prioritas', $disposisi->prioritas) == 'biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="segera" {{ old('prioritas', $disposisi->prioritas) == 'segera' ? 'selected' : '' }}>Segera</option>
                        <option value="mendesak" {{ old('prioritas', $disposisi->prioritas) == 'mendesak' ? 'selected' : '' }}>Mendesak</option>
                    </select>
                    @error('prioritas')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sifat --}}
                <div>
                    <label for="sifat" class="block text-sm font-semibold text-slate-700 mb-1">Sifat <span class="text-red-500">*</span></label>
                    <select name="sifat" id="sifat"
                            class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium appearance-none bg-no-repeat bg-right @error('sifat') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                            @if($disposisi->status !== 'baru') disabled @endif
                            required>
                        <option value="biasa" {{ old('sifat', $disposisi->sifat) == 'biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="rahasia" {{ old('sifat', $disposisi->sifat) == 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                        <option value="terbatas" {{ old('sifat', $disposisi->sifat) == 'terbatas' ? 'selected' : '' }}>Terbatas</option>
                    </select>
                    @error('sifat')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Batas Waktu --}}
                <div>
                    <label for="batas_waktu" class="block text-sm font-semibold text-slate-700 mb-1">Batas Waktu <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="batas_waktu" id="batas_waktu"
                        value="{{ old('batas_waktu', $disposisi->batas_waktu ? $disposisi->batas_waktu->format('Y-m-d\TH:i') : '') }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('batas_waktu') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        @if($disposisi->status !== 'baru') readonly @endif
                        required>
                    @error('batas_waktu')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Catatan --}}
            <div class="sm:col-span-2">
                <label for="catatan" class="block text-sm font-semibold text-slate-700 mb-1">Catatan Tambahan</label>
                <textarea name="catatan" id="catatan" rows="3"
                    class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner"
                    @if($disposisi->status !== 'baru') readonly @endif>{{ old('catatan', $disposisi->catatan) }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('disposisi.show', $disposisi) }}"
                    class="inline-flex items-center px-4 py-3 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
                    Batal
                </a>
                @if($disposisi->status === 'baru')
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-brand to-accent rounded-xl shadow-md hover:from-brand-dark hover:to-accent-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                @endif
            </div>
        </form>
    </x-card>
</div>
@endsection