@extends('layouts.app')
@section('title', 'Edit ' . $suratMasuk->jenis_surat . ' ' . $suratMasuk->tahun)
@section('page-title', 'Edit ' . $suratMasuk->jenis_surat . ' ' . $suratMasuk->tahun)

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Breadcrumbs --}}
    <div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('surat-masuk.index', ['jenis_surat' => $suratMasuk->jenis_surat, 'tahun' => $suratMasuk->tahun]) }}" class="hover:text-brand">
            {{ $suratMasuk->jenis_surat }} {{ $suratMasuk->tahun }}
        </a>
        <span>/</span>
        <span class="text-slate-800 font-medium">Edit</span>
    </div>

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <x-card>
        <h2 class="mb-6 text-lg font-bold text-slate-800 border-b dark:border-slate-700 pb-4">Form Edit {{ $suratMasuk->jenis_surat }} {{ $suratMasuk->tahun }}</h2>

        <form action="{{ route('surat-masuk.update', $suratMasuk) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Jenis Surat --}}
            <div>
                <label for="jenis_surat" class="block text-sm font-semibold text-slate-700 mb-1">Jenis Surat <span class="text-red-500">*</span></label>
                <select id="jenis_surat" name="jenis_surat" class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('jenis_surat') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror" required>
                    <option value="Surat Masuk" {{ $suratMasuk->jenis_surat === 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                    <option value="Undangan Masuk" {{ $suratMasuk->jenis_surat === 'Undangan Masuk' ? 'selected' : '' }}>Undangan Masuk</option>
                </select>
                @error('jenis_surat')
                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Grid Fields --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                {{-- 1. Nomor Urut (Agenda) --}}
                <div>
                    <label for="no_agenda" class="block text-sm font-semibold text-slate-700 mb-1">Nomor Urut (No. Agenda) <span class="text-red-500">*</span></label>
                    <input id="no_agenda" type="text" name="no_agenda" value="{{ old('no_agenda', $suratMasuk->no_agenda) }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('no_agenda') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        placeholder="Contoh: 1, 2, 3..." required>
                    @error('no_agenda')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 2. Tanggal Terima --}}
                <div>
                    <label for="tanggal_terima" class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Terima <span class="text-red-500">*</span></label>
                    <input id="tanggal_terima" type="date" name="tanggal_terima" value="{{ old('tanggal_terima', $suratMasuk->tanggal_terima ? $suratMasuk->tanggal_terima->format('Y-m-d') : '') }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('tanggal_terima') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        max="{{ date('Y-m-d') }}" required>
                    @error('tanggal_terima')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 3. Klasifikasi Arsip --}}
                <div>
                    <label for="klasifikasi_id" class="block text-sm font-semibold text-slate-700 mb-1">Klasifikasi Arsip <span class="text-red-500">*</span></label>
                    <select id="klasifikasi_id" name="klasifikasi_id"
                            class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium appearance-none bg-no-repeat bg-right @error('klasifikasi_id') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                            required>
                        <option value="">-- Pilih Kode Klasifikasi --</option>
                        @foreach($klasifikasiList as $klasifikasi)
                            <option value="{{ $klasifikasi->id }}" {{ old('klasifikasi_id', $suratMasuk->klasifikasi_id) == $klasifikasi->id ? 'selected' : '' }}>
                                {{ $klasifikasi->kode }} - {{ $klasifikasi->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('klasifikasi_id')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-slate-500">Ketik untuk mencari 2.985 kode JRA SKKAAD</p>
                </div>

                {{-- 4. Bidang Tujuan --}}
                <div>
                    <label for="bidang_tujuan_id" class="block text-sm font-semibold text-slate-700 mb-1">Bidang Tujuan <span class="text-red-500">*</span></label>
                    <select id="bidang_tujuan_id" name="bidang_tujuan_id"
                            class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium appearance-none bg-no-repeat bg-right @error('bidang_tujuan_id') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                            required>
                        <option value="">-- Pilih Bidang Tujuan --</option>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}" {{ old('bidang_tujuan_id', $suratMasuk->bidang_tujuan_id) == $bidang->id ? 'selected' : '' }}>
                                {{ $bidang->nama_bidang }} ({{ $bidang->kode_bidang }})
                            </option>
                        @endforeach
                    </select>
                    @error('bidang_tujuan_id')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 5. Nomor Surat --}}
                <div>
                    <label for="no_surat" class="block text-sm font-semibold text-slate-700 mb-1">Nomor Surat <span class="text-red-500">*</span></label>
                    <input id="no_surat" type="text" name="no_surat" value="{{ old('no_surat', $suratMasuk->no_surat) }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('no_surat') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        placeholder="Contoh: 001/DISHUB/VII/2026" required>
                    @error('no_surat')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 6. Tanggal Surat --}}
                <div>
                    <label for="tanggal_surat" class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Surat <span class="text-red-500">*</span></label>
                    <input id="tanggal_surat" type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $suratMasuk->tanggal_surat ? $suratMasuk->tanggal_surat->format('Y-m-d') : '') }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('tanggal_surat') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        max="{{ date('Y-m-d') }}" required>
                    @error('tanggal_surat')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 7. Pengirim / Asal Surat --}}
                <div>
                    <label for="pengirim" class="block text-sm font-semibold text-slate-700 mb-1">Pengirim / Asal Surat <span class="text-red-500">*</span></label>
                    <input id="pengirim" type="text" name="pengirim" value="{{ old('pengirim', $suratMasuk->pengirim) }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('pengirim') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        placeholder="Nama instansi / perorangan" required>
                    @error('pengirim')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 8. Perihal --}}
                <div class="sm:col-span-2">
                    <label for="perihal" class="block text-sm font-semibold text-slate-700 mb-1">Perihal / Uraian <span class="text-red-500">*</span></label>
                    <textarea id="perihal" name="perihal" rows="4"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('perihal') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        placeholder="Tuliskan uraian/perihal surat di sini..." required>{{ old('perihal', $suratMasuk->perihal) }}</textarea>
                    @error('perihal')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 9. Isi Ringkas --}}
                <div class="sm:col-span-2">
                    <label for="isi_ringkas" class="block text-sm font-semibold text-slate-700 mb-1">Isi Ringkas</label>
                    <textarea id="isi_ringkas" name="isi_ringkas" rows="3"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner">
                        {{ old('isi_ringkas', $suratMasuk->isi_ringkas) }}</textarea>
                </div>

                {{-- 10. Bidang Tujuan Tambahan (Multiple) --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Bidang Tujuan Tambahan (Multiple)</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 p-3 border border-slate-200 dark:border-slate-700 rounded-xl max-h-64 overflow-y-auto bg-slate-50/50 dark:bg-slate-900/40">
                        @foreach($bidangs as $bidang)
                            @php
                                $selectedIds = old('bidang_tujuan_ids', $suratMasuk->bidangTujuans->pluck('id')->toArray());
                                $checked = in_array($bidang->id, $selectedIds);
                            @endphp
                            <label class="flex items-center gap-2.5 p-2.5 bg-white dark:bg-slate-800 rounded-md border {{ $checked ? 'border-brand bg-brand/10 dark:bg-brand/20' : 'border-slate-100 hover:border-brand/50 dark:border-slate-700 dark:hover:border-brand/50' }} cursor-pointer transition select-none shadow-sm">
                                <input type="checkbox" name="bidang_tujuan_ids[]" value="{{ $bidang->id }}"
                                       {{ $checked ? 'checked' : '' }}
                                       class="rounded border-slate-300 text-brand focus:ring-brand/50 w-4 h-4 transition dark:border-slate-600 dark:bg-slate-700">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $bidang->nama_bidang }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- 11. Status Disposisi --}}
                <div>
                    <label for="status_disposisi" class="block text-sm font-semibold text-slate-700 mb-1">Status Disposisi</label>
                    <select id="status_disposisi" name="status_disposisi" class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium appearance-none bg-no-repeat bg-right">
                        <option value="belum" {{ old('status_disposisi', $suratMasuk->status_disposisi) == 'belum' ? 'selected' : '' }}>Belum</option>
                        <option value="diproses" {{ old('status_disposisi', $suratMasuk->status_disposisi) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ old('status_disposisi', $suratMasuk->status_disposisi) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                {{-- 12. File Surat (Existing) --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">File Surat Saat Ini</label>
                    <div id="existing-files" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2">
                        @if(!empty($suratMasuk->file_surat))
                            @foreach($suratMasuk->file_surat as $path)
                                @php
                                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                    $isPdf = $ext === 'pdf';
                                    $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                                    $fileUrl = route('file.serve', $path);
                                    $fileName = basename($path);
                                @endphp
                                <div class="relative group border border-slate-200 rounded-lg overflow-hidden bg-white dark:bg-slate-800 existing-file" data-path="{{ $path }}">
                                    <div class="aspect-square overflow-hidden">
                                        @if($isImage)
                                            <img src="{{ $fileUrl }}" alt="{{ $fileName }}" class="w-full h-24 object-cover">
                                        @else
                                            <div class="w-full h-24 flex items-center justify-center bg-slate-100 dark:bg-slate-700">
                                                <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-2">
                                        <p class="text-xs font-medium text-slate-700 dark:text-slate-300 truncate">{{ $fileName }}</p>
                                        <p class="text-[10px] text-slate-400">{{ strtoupper($ext) }}</p>
                                    </div>
                                    <div class="absolute bottom-1 right-1 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity p-1">
                                        <a href="{{ $fileUrl }}" target="_blank" class="px-2 py-1 text-[10px] bg-blue-500 text-white rounded hover:bg-blue-600">Lihat</a>
                                        <label class="px-2 py-1 text-[10px] bg-red-500 text-white rounded cursor-pointer">
                                            <input type="checkbox" name="hapus_file[]" value="{{ $path }}" class="sr-only peer" />
                                            <span class="peer-checked:bg-red-600 peer-checked:text-white">Hapus</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-slate-400 text-sm col-span-full text-center py-4">Tidak ada file yang diunggah</p>
                        @endif
                    </div>
                </div>

                {{-- 13. File Surat (New Upload) --}}
                <div class="sm:col-span-2">
                    <label for="file_surat" class="block text-sm font-semibold text-slate-700 mb-1">Tambah File Baru (Opsional, Maks 5 file, 5MB each)</label>
                    <div class="relative flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a2 2 0 012-2h6a2 2 0 012 2v2a2 2 0 01-2 2H9a2 2 0 01-2-2v-2zM7 10a2 2 0 012-2h6a2 2 0 012 2v2a2 2 0 01-2 2H9a2 2 0 01-2-2v-2zm0-4a2 2 0 012-2h6a2 2 0 012 2v2a2 2 0 01-2 2H9a2 2 0 01-2-2V6z" />
                            </svg>
                        </div>
                        <input type="file" id="file_surat" name="file_surat[]" multiple
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                            class="block w-full pl-10 pr-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('file_surat') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror">
                    </div>
                    <div id="file-preview" class="mt-3 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 min-h-[60px]"></div>
                    <p class="mt-1 text-xs text-slate-500">Maksimal 5 file, 5MB each. Format: PDF, JPG, PNG, DOC, DOCX. File baru akan ditambahkan ke file yang sudah ada.</p>
                    @error('file_surat')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 14. Keterangan --}}
                <div class="sm:col-span-2">
                    <label for="keterangan" class="block text-sm font-semibold text-slate-700 mb-1">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="2"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner">
                        {{ old('keterangan', $suratMasuk->keterangan) }}</textarea>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('surat-masuk.show', $suratMasuk) }}"
                    class="inline-flex items-center px-4 py-3 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-brand to-accent rounded-xl shadow-md hover:from-brand-dark hover:to-accent-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection

@push('scripts')
<script>
    // File preview functionality for new uploads
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file_surat');
        const previewContainer = document.getElementById('file-preview');

        if (fileInput && previewContainer) {
            fileInput.addEventListener('change', function() {
                previewContainer.innerHTML = '';
                const files = Array.from(this.files).slice(0, 5);

                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group border border-slate-200 rounded-lg overflow-hidden bg-white dark:bg-slate-800';
                        div.innerHTML = `
                            <div class="aspect-square overflow-hidden">
                                ${file.type.startsWith('image/') 
                                    ? `<img src="${e.target.result}" alt="${file.name}" class="w-full h-full object-cover">`
                                    : `<div class="w-full h-full flex items-center justify-center bg-slate-100 dark:bg-slate-700">
                                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>`
                                }
                            </div>
                            <div class="p-2">
                                <p class="text-xs font-medium text-slate-700 dark:text-slate-300 truncate">${file.name}</p>
                                <p class="text-[10px] text-slate-400">${(file.size / 1024).toFixed(1)} KB</p>
                            </div>
                            <button type="button" onclick="this.closest('.relative').remove();"
                                class="absolute top-1 right-1 w-5 h-5 rounded-full bg-red-500/90 text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600">
                                ×
                            </button>
                        `;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            });
        }
    });
</script>
@endpush