@extends('layouts.app')
@section('title', 'Tambah Surat Keluar ' . $tahun)
@section('page-title', 'Tambah Surat Keluar ' . $tahun)

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Breadcrumbs --}}
    <div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('surat-keluar.index', ['jenis_surat' => $jenisSurat, 'tahun' => $tahun]) }}" class="hover:text-brand">
            Surat Keluar ({{ $jenisSurat }}) {{ $tahun }}
        </a>
        <span>/</span>
        <span class="text-slate-800 font-medium">Tambah</span>
    </div>

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <x-card>
        <h2 class="mb-6 text-lg font-bold text-slate-800 border-b dark:border-slate-700 pb-4">Form Surat Keluar ({{ $jenisSurat }}) {{ $tahun }} Baru</h2>

        <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="jenis_surat" value="{{ $jenisSurat }}" />
            <input type="hidden" name="tahun" value="{{ $tahun }}" />

            {{-- Jenis Surat --}}
            <div>
                <label for="jenis_surat" class="block text-sm font-semibold text-slate-700 mb-1">Jenis Surat <span class="text-red-500">*</span></label>
                <select id="jenis_surat" name="jenis_surat" class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('jenis_surat') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror" required>
                    <option value="SPT" {{ $jenisSurat === 'SPT' ? 'selected' : '' }}>SPT (Surat Perintah Tugas)</option>
                    <option value="SPPD" {{ $jenisSurat === 'SPPD' ? 'selected' : '' }}>SPPD (Surat Perintah Perjalanan Dinas)</option>
                    <option value="SK" {{ $jenisSurat === 'SK' ? 'selected' : '' }}>SK (Surat Keputusan)</option>
                    <option value="Undangan" {{ $jenisSurat === 'Undangan' ? 'selected' : '' }}>Undangan</option>
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
                    <input id="no_agenda" type="text" name="no_agenda" value="{{ old('no_agenda', $suggestedNoAgenda) }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('no_agenda') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        placeholder="Contoh: 1, 2, 3..." required>
                    @error('no_agenda')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 2. Tahun --}}
                <div>
                    <label for="tahun" class="block text-sm font-semibold text-slate-700 mb-1">Tahun <span class="text-red-500">*</span></label>
                    <input id="tahun" type="number" name="tahun" value="{{ old('tahun', $tahun) }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('tahun') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        min="2020" max="2030" required>
                    @error('tahun')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 3. Nomor Surat --}}
                <div>
                    <label for="no_surat" class="block text-sm font-semibold text-slate-700 mb-1">Nomor Surat <span class="text-red-500">*</span></label>
                    <input id="no_surat" type="text" name="no_surat" value="{{ old('no_surat') }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('no_surat') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        placeholder="Contoh: 800/1/V.13/2026" required>
                    @error('no_surat')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 4. Tanggal Surat --}}
                <div>
                    <label for="tanggal_surat" class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Surat <span class="text-red-500">*</span></label>
                    <input id="tanggal_surat" type="date" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('tanggal_surat') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        max="{{ date('Y-m-d') }}" required>
                    @error('tanggal_surat')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 5. Tanggal Keluar --}}
                <div>
                    <label for="tanggal_terima" class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Keluar <span class="text-red-500">*</span></label>
                    <input id="tanggal_terima" type="date" name="tanggal_terima" value="{{ old('tanggal_terima', date('Y-m-d')) }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('tanggal_terima') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        max="{{ date('Y-m-d') }}" required>
                    @error('tanggal_terima')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 6. Tujuan --}}
                <div>
                    <label for="tujuan" class="block text-sm font-semibold text-slate-700 mb-1">Tujuan Surat <span class="text-red-500">*</span></label>
                    <input id="tujuan" type="text" name="tujuan" value="{{ old('tujuan') }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('tujuan') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        placeholder="Instansi / perorangan tujuan" required>
                    @error('tujuan')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 7. Klasifikasi Arsip (TomSelect) --}}
                <div>
                    <label for="klasifikasi_id" class="block text-sm font-semibold text-slate-700 mb-1">Klasifikasi Arsip <span class="text-red-500">*</span></label>
                    <select id="klasifikasi_id" name="klasifikasi_id"
                            class="tomselect w-full"
                            data-placeholder="Cari kode atau nama klasifikasi..."
                            required>
                        <option value="">-- Pilih Kode Klasifikasi --</option>
                        @foreach($klasifikasiList as $klasifikasi)
                            <option value="{{ $klasifikasi->id }}" {{ old('klasifikasi_id') == $klasifikasi->id ? 'selected' : '' }}>
                                {{ $klasifikasi->kode }} - {{ $klasifikasi->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('klasifikasi_id')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-slate-500">Ketik untuk mencari 2.985 kode JRA SKKAAD</p>
                </div>

                {{-- 8. Bidang --}}
                <div>
                    <label for="bidang_id" class="block text-sm font-semibold text-slate-700 mb-1">Bidang <span class="text-red-500">*</span></label>
                    <select id="bidang_id" name="bidang_id"
                            class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium appearance-none bg-no-repeat bg-right @error('bidang_id') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                            required>
                        <option value="">-- Pilih Bidang --</option>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}" {{ old('bidang_id') == $bidang->id ? 'selected' : '' }}>
                                {{ $bidang->nama_bidang }} ({{ $bidang->kode_bidang }})
                            </option>
                        @endforeach
                    </select>
                    @error('bidang_id')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 9. Penandatangan --}}
                <div>
                    <label for="penandatangan" class="block text-sm font-semibold text-slate-700 mb-1">Penandatangan <span class="text-red-500">*</span></label>
                    <input id="penandatangan" type="text" name="penandatangan" value="{{ old('penandatangan') }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('penandatangan') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        placeholder="Nama penandatangan (contoh: Kepala Dinas Perhubungan)" required>
                    @error('penandatangan')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 10. Perihal --}}
                <div class="sm:col-span-2">
                    <label for="perihal" class="block text-sm font-semibold text-slate-700 mb-1">Perihal / Uraian <span class="text-red-500">*</span></label>
                    <textarea id="perihal" name="perihal" rows="4"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('perihal') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        placeholder="Tuliskan uraian/perihal surat di sini..." required>{{ old('perihal') }}</textarea>
                    @error('perihal')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 11. Isi Ringkas --}}
                <div class="sm:col-span-2">
                    <label for="isi_ringkas" class="block text-sm font-semibold text-slate-700 mb-1">Isi Ringkas</label>
                    <textarea id="isi_ringkas" name="isi_ringkas" rows="3"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner">
                        {{ old('isi_ringkas') }}</textarea>
                </div>

                {{-- 12. File Surat --}}
                <div class="sm:col-span-2">
                    <label for="file_surat" class="block text-sm font-semibold text-slate-700 mb-1">File Surat (Scan/Unggah) <span class="text-slate-400">(Opsional, Maks 5 file, 5MB each)</span></label>
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
                    <p class="mt-1 text-xs text-slate-500">Maksimal 5 file, 5MB each. Format: PDF, JPG, PNG, DOC, DOCX</p>
                    @error('file_surat')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 13. Keterangan --}}
                <div class="sm:col-span-2">
                    <label for="keterangan" class="block text-sm font-semibold text-slate-700 mb-1">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="2"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner">
                        {{ old('keterangan') }}</textarea>
                </div>

                {{-- 14. Indeks Berkas --}}
                <div>
                    <label for="indeks_berkas" class="block text-sm font-semibold text-slate-700 mb-1">Indeks Berkas</label>
                    <input id="indeks_berkas" type="text" name="indeks_berkas" value="{{ old('indeks_berkas') }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner"
                        placeholder="Contoh: 001/DISHUB/2026">
                </div>

                {{-- 15. Tingkat Keamanan --}}
                <div>
                    <label for="tingkat_keamanan" class="block text-sm font-semibold text-slate-700 mb-1">Tingkat Keamanan</label>
                    <select id="tingkat_keamanan" name="tingkat_keamanan"
                            class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium appearance-none bg-no-repeat bg-right">
                        <option value="">-- Pilih Tingkat Keamanan --</option>
                        <option value="Biasa" {{ old('tingkat_keamanan') == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="Rahasia" {{ old('tingkat_keamanan') == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                        <option value="Sangat Rahasia" {{ old('tingkat_keamanan') == 'Sangat Rahasia' ? 'selected' : '' }}>Sangat Rahasia</option>
                        <option value="Terbatas" {{ old('tingkat_keamanan') == 'Terbatas' ? 'selected' : '' }}>Terbatas</option>
                    </select>
                </div>

                {{-- 16. Masa Simpan --}}
                <div>
                    <label for="masa_simpan" class="block text-sm font-semibold text-slate-700 mb-1">Masa Simpan</label>
                    <input id="masa_simpan" type="text" name="masa_simpan" value="{{ old('masa_simpan') }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner"
                        placeholder="Contoh: 5 Tahun, Permanen, Musnah">
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('surat-keluar.index', ['jenis_surat' => $jenisSurat, 'tahun' => $tahun]) }}"
                    class="inline-flex items-center px-4 py-3 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
                    Batal
                </a>
                <button type="submit" name="action" value="draft"
                    class="inline-flex items-center px-4 py-3 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Konsep
                </button>
                <button type="submit" name="action" value="kirim"
                    class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-brand to-accent rounded-xl shadow-md hover:from-brand-dark hover:to-accent-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan & Kirim
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection

@push('scripts')
<script>
    // Initialize TomSelect for klasifikasi
    document.addEventListener('alpine:init', () => {
        Alpine.data('suratKeluarForm', () => ({
            init() {
                this.$nextTick(() => {
                    if (typeof TomSelect !== 'undefined') {
                        new TomSelect('#klasifikasi_id', {
                            plugins: ['remove_button'],
                            valueField: 'id',
                            labelField: 'nama',
                            searchField: ['kode', 'nama'],
                            create: false,
                            render: {
                                option: function(data, escape) {
                                    return '<div class="option">' +
                                        '<span class="kode">' + escape(data.kode) + '</span>' +
                                        '<span class="nama">' + escape(data.nama) + '</span>' +
                                        '</div>';
                                }
                            },
                            onChange: function(value) {
                                this.$dispatch('change', { target: { name: 'klasifikasi_id', value: value } });
                            }
                        });
                    }
                }
            }));
        }
    }));

    // File preview functionality
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
@push('styles')
<style>
    /* TomSelect styling */
    .ts-control {
        @apply bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner;
    }
    .ts-control:focus {
        @apply bg-white focus:border-brand focus:ring-4 focus:ring-brand/10;
    }
    .ts-dropdown .option {
        @apply px-3 py-2;
    }
    .ts-dropdown .option .kode {
        @apply font-mono font-semibold text-primary;
    }
    .ts-dropdown .option .nama {
        @apply ml-2 text-slate-700 dark:text-slate-300;
    }
    .ts-dropdown .active {
        @apply bg-primary-light dark:bg-primary/20;
    }
    .ts-control .item {
        @apply bg-primary text-white rounded;
    }
</style>
@endpush