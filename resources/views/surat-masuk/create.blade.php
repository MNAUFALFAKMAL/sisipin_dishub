@php
    $jenisActive = request('jenis_surat_active', 'Surat Masuk');
@endphp
@extends('layouts.app')
@section('title', 'Tambah ' . $jenisActive . ' ' . $tahunAktif)
@section('page-title', 'Tambah ' . $jenisActive . ' ' . $tahunAktif)

@section('content')
<div class="max-w-3xl">

    <div class="mb-4 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('surat-masuk.index', ['jenis_surat' => $jenisActive, 'tahun' => $tahunAktif]) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $jenisActive }} {{ $tahunAktif }}</a>
        <span>/</span>
        <span class="text-gray-800 dark:text-gray-100 font-medium">Tambah</span>
    </div>

    <x-card>
        <h2 class="mb-6 text-base font-bold text-gray-800 dark:text-gray-100 border-b dark:border-[#1e2d45] pb-4">Form {{ $jenisActive }} {{ $tahunAktif }} Baru</h2>

        <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <input type="hidden" name="jenis_surat_active" value="{{ request('jenis_surat_active') }}" />
            <input type="hidden" name="tahun" value="{{ $tahunAktif }}" />

            <div>
                <label for="jenis_surat" class="form-label">Jenis Surat <span class="text-red-500">*</span></label>
                <select id="jenis_surat" name="jenis_surat" class="form-select @error('jenis_surat') border-red-500 @enderror" required>
                    <option value="Surat Masuk" {{ old('jenis_surat', request('jenis_surat_active')) == 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                    <option value="Undangan Masuk" {{ old('jenis_surat', request('jenis_surat_active')) == 'Undangan Masuk' ? 'selected' : '' }}>Undangan Masuk</option>
                </select>
                @error('jenis_surat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- 1. Nomor Urut -->
                <div>
                    <label for="no_agenda" class="form-label">Nomor Urut (No. Agenda)</label>
                    <input id="no_agenda" type="text" name="no_agenda" value="{{ old('no_agenda', $suggestedNoAgenda ?? '') }}" 
                           class="form-input @error('no_agenda') border-red-500 @enderror" placeholder="Contoh: 12 atau 1.a" />
                    @error('no_agenda')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <!-- 2. Tanggal -->
                <div>
                    <label for="tanggal_terima" class="form-label">Tanggal Masuk</label>
                    <input id="tanggal_terima" type="date" name="tanggal_terima" value="{{ old('tanggal_terima', date('Y-m-d')) }}"
                           class="form-input @error('tanggal_terima') border-red-500 @enderror" />
                    @error('tanggal_terima')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <!-- 3. Kode (Klasifikasi) -->
                <div>
                    <label for="klasifikasi_id" class="form-label">Kode (Klasifikasi Arsip)</label>
                    <select id="klasifikasi_id" name="klasifikasi_id"
                            class="form-select tomselect-ajax @error('klasifikasi_id') border-red-500 @enderror"
                            data-url="{{ route('klasifikasi-arsip.search') }}">
                        <option value="">-- Ketik Kode/Nama --</option>
                        @if(old('klasifikasi_id'))
                            <option value="{{ old('klasifikasi_id') }}" selected>Kode Terpilih</option>
                        @endif
                    </select>
                    @error('klasifikasi_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <!-- 4. Tujuan / Asal -->
                <div>
                    <label for="pengirim" class="form-label">Tujuan / Asal Surat</label>
                    <input id="pengirim" type="text" name="pengirim" value="{{ old('pengirim') }}"
                           class="form-input @error('pengirim') border-red-500 @enderror"
                           placeholder="Nama instansi / perorangan" />
                    @error('pengirim')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <!-- 5. Nomor Surat -->
                <div>
                    <label for="no_surat" class="form-label">Nomor Surat</label>
                    <input id="no_surat" type="text" name="no_surat" value="{{ old('no_surat') }}"
                           class="form-input @error('no_surat') border-red-500 @enderror"
                           placeholder="Contoh: 001/DISHUB/VII/2026" />
                    @error('no_surat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <!-- 6. Tanggal Surat -->
                <div>
                    <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                    <input id="tanggal_surat" type="date" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}"
                           class="form-input @error('tanggal_surat') border-red-500 @enderror" />
                    @error('tanggal_surat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- 7. Uraian -->
            <div>
                <label for="perihal" class="form-label">Uraian (Perihal/Isi Ringkas)</label>
                <textarea id="perihal" name="perihal" rows="3"
                          class="form-textarea @error('perihal') border-red-500 @enderror"
                          placeholder="Tuliskan uraian surat di sini...">{{ old('perihal') }}</textarea>
                @error('perihal')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- 8. Disposisi -->
                <div>
                    <label class="form-label">Disposisi</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 p-3 border border-gray-200 dark:border-[#1e2d45] rounded-xl max-h-64 overflow-y-auto bg-gray-50/50 dark:bg-gray-900/40">
                        @foreach($bidangs as $bidang)
                            @php
                                $checked = is_array(old('bidang_tujuan_ids')) && in_array($bidang->id, old('bidang_tujuan_ids'));
                            @endphp
                            <label class="flex items-center gap-2.5 p-2.5 bg-white dark:bg-[#161b27] rounded-md border {{ $checked ? 'border-indigo-500 bg-indigo-50/30 dark:bg-indigo-900/20' : 'border-gray-100 hover:border-indigo-200 dark:border-[#1e2d45] dark:hover:border-indigo-500/60' }} cursor-pointer transition select-none shadow-sm relative overflow-hidden group">
                                <input type="checkbox" name="bidang_tujuan_ids[]" value="{{ $bidang->id }}"
                                       {{ $checked ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 transition dark:border-[#334155] dark:bg-[#0f172a]"
                                       onchange="this.parentElement.classList.toggle('border-indigo-500', this.checked); this.parentElement.classList.toggle('bg-indigo-50/30', this.checked); this.parentElement.classList.toggle('dark:bg-indigo-900/20', this.checked); this.parentElement.classList.toggle('border-gray-100', !this.checked); this.parentElement.classList.toggle('dark:border-[#1e2d45]', !this.checked);">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-indigo-900 dark:group-hover:text-indigo-300 transition">{{ $bidang->nama_bidang }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('bidang_tujuan_ids')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <!-- 9. Keterangan -->
                <div>
                    <label for="keterangan" class="form-label">Keterangan Text</label>
                    <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan') }}"
                           class="form-input mb-3" placeholder="Masukkan keterangan tambahan jika ada..." />

                    <label for="file_surat" class="form-label">Keterangan (File Surat)</label>
                    <input type="file" id="file_surat" name="file_surat[]" multiple
                           accept="image/*,.pdf"
                           class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/40 dark:file:text-indigo-300 dark:hover:file:bg-indigo-900/60" />
                    <div class="file-preview-grid"></div>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Opsional — Pilih beberapa foto/PDF dalam satu unggahan, semua digabung jadi 1 PDF (foto otomatis dikonversi & dikompres, maksimal 10 file). File lain bisa ditambahkan lagi saat edit tanpa menghapus file lama.</p>
                    @error('file_surat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    @error('file_surat.*')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 border-t pt-4">
                <x-button variant="secondary" href="{{ route('surat-masuk.index', ['jenis_surat' => $jenisActive, 'tahun' => $tahunAktif]) }}" class="w-full sm:w-auto">Batal</x-button>
                <x-button variant="primary" type="submit" class="w-full sm:w-auto justify-center">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection