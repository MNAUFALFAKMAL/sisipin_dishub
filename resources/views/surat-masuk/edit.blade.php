@php
    $jenisActive = $suratMasuk->jenis_surat;
@endphp
@extends('layouts.app')
@section('title', 'Edit ' . $jenisActive . ' ' . $suratMasuk->tahun)
@section('page-title', 'Edit ' . $jenisActive . ' ' . $suratMasuk->tahun)

@section('content')
<div class="max-w-3xl">

    <div class="mb-4 flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('surat-masuk.index', ['jenis_surat' => $jenisActive, 'tahun' => $suratMasuk->tahun]) }}" class="hover:text-indigo-600">{{ $jenisActive }} {{ $suratMasuk->tahun }}</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Edit</span>
    </div>

    <x-card>
        <h2 class="mb-6 text-base font-bold text-gray-800 border-b pb-4">Form Edit {{ $jenisActive }} {{ $suratMasuk->tahun }}</h2>

        <form action="{{ route('surat-masuk.update', $suratMasuk->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            <input type="hidden" name="jenis_surat_active" value="{{ request('jenis_surat_active') }}" />
            <input type="hidden" name="tahun" value="{{ old('tahun', $suratMasuk->tahun) }}" />

            <div>
                <label for="jenis_surat" class="form-label">Jenis Surat <span class="text-red-500">*</span></label>
                <select id="jenis_surat" name="jenis_surat" class="form-select @error('jenis_surat') border-red-500 @enderror" required>
                    <option value="Surat Masuk" {{ old('jenis_surat', $suratMasuk->jenis_surat) == 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                    <option value="Undangan Masuk" {{ old('jenis_surat', $suratMasuk->jenis_surat) == 'Undangan Masuk' ? 'selected' : '' }}>Undangan Masuk</option>
                </select>
                @error('jenis_surat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- 1. Nomor Urut -->
                <div>
                    <label for="no_agenda" class="form-label">Nomor Urut (No. Agenda)</label>
                    <input id="no_agenda" type="text" name="no_agenda" value="{{ old('no_agenda', $suratMasuk->no_agenda) }}" 
                           class="form-input @error('no_agenda') border-red-500 @enderror" placeholder="Contoh: 12 atau 1.a" />
                    @error('no_agenda')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <!-- 2. Tanggal -->
                <div>
                    <label for="tanggal_terima" class="form-label">Tanggal Masuk</label>
                    <input id="tanggal_terima" type="date" name="tanggal_terima" value="{{ old('tanggal_terima', $suratMasuk->tanggal_terima ? $suratMasuk->tanggal_terima->format('Y-m-d') : '') }}"
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
                        @if(old('klasifikasi_id', $suratMasuk->klasifikasi_id))
                            <option value="{{ old('klasifikasi_id', $suratMasuk->klasifikasi_id) }}" selected>
                                {{ $suratMasuk->klasifikasi ? $suratMasuk->klasifikasi->kode . ' - ' . $suratMasuk->klasifikasi->nama : 'Kode Terpilih' }}
                            </option>
                        @endif
                    </select>
                    @error('klasifikasi_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <!-- 4. Tujuan / Asal -->
                <div>
                    <label for="pengirim" class="form-label">Tujuan / Asal Surat</label>
                    <input id="pengirim" type="text" name="pengirim" value="{{ old('pengirim', $suratMasuk->pengirim) }}"
                           class="form-input @error('pengirim') border-red-500 @enderror" />
                    @error('pengirim')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <!-- 5. Nomor Surat -->
                <div>
                    <label for="no_surat" class="form-label">Nomor Surat</label>
                    <input id="no_surat" type="text" name="no_surat" value="{{ old('no_surat', $suratMasuk->no_surat) }}"
                           class="form-input @error('no_surat') border-red-500 @enderror" />
                    @error('no_surat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <!-- 6. Tanggal Surat -->
                <div>
                    <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                    <input id="tanggal_surat" type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $suratMasuk->tanggal_surat ? $suratMasuk->tanggal_surat->format('Y-m-d') : '') }}"
                           class="form-input @error('tanggal_surat') border-red-500 @enderror" />
                    @error('tanggal_surat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- 7. Uraian -->
            <div>
                <label for="perihal" class="form-label">Uraian (Perihal/Isi Ringkas)</label>
                <textarea id="perihal" name="perihal" rows="3"
                          class="form-textarea @error('perihal') border-red-500 @enderror">{{ old('perihal', $suratMasuk->perihal) }}</textarea>
                @error('perihal')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- 8. Disposisi -->
                <div>
                    <label class="form-label">Disposisi</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 p-3 border border-gray-200 dark:border-[#1e2d45] rounded-xl max-h-64 overflow-y-auto bg-gray-50/50 dark:bg-gray-900/40">
                        @foreach($bidangs as $bidang)
                            @php
                                $checked = is_array(old('bidang_tujuan_ids', $suratMasuk->bidangTujuans->pluck('id')->toArray())) 
                                    && in_array($bidang->id, old('bidang_tujuan_ids', $suratMasuk->bidangTujuans->pluck('id')->toArray()));
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

                <div>
                    <label for="status_disposisi" class="form-label">Status Disposisi</label>
                    <select id="status_disposisi" name="status_disposisi" class="form-select">
                        <option value="belum" {{ old('status_disposisi', $suratMasuk->status_disposisi) == 'belum' ? 'selected' : '' }}>Belum</option>
                        <option value="diproses" {{ old('status_disposisi', $suratMasuk->status_disposisi) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ old('status_disposisi', $suratMasuk->status_disposisi) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
            </div>

            <!-- 9. Keterangan -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="keterangan" class="form-label">Keterangan Text</label>
                    <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan', $suratMasuk->keterangan) }}"
                           class="form-input mb-3" placeholder="Masukkan keterangan tambahan jika ada..." />
                </div>

                <div>
                    <label class="form-label">Keterangan (File Surat)</label>
                    @foreach($suratMasuk->file_surat as $path)
                        @php
                            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                            $isPdf = $ext === 'pdf';
                            $isImage = in_array($ext, ['jpg','jpeg','png']);
                            $fileType = $isPdf ? 'pdf' : ($isImage ? 'image' : 'file');
                            $fileUrl = route('file.serve', $path);
                            $fileName = basename($path);
                        @endphp
                        <div class="existing-file-card mt-1 mb-2" onclick="openFilePreview({url:'{{ $fileUrl }}',type:'{{ $fileType }}',name:'{{ $fileName }}'})">
                            <div class="existing-file-icon">
                                @if($isPdf)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6"/></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @endif
                            </div>
                            <div class="existing-file-info">
                                <span class="existing-file-name">{{ $fileName }}</span>
                                <span class="existing-file-size">File saat ini</span>
                            </div>
                            <div class="existing-file-actions">
                                <button type="button" class="existing-file-btn existing-file-btn-preview" onclick="event.stopPropagation();openFilePreview({url:'{{ $fileUrl }}',type:'{{ $fileType }}',name:'{{ $fileName }}'})">Lihat</button>
                                <label class="existing-file-check" onclick="event.stopPropagation()">
                                    <input type="checkbox" name="hapus_file[]" value="{{ $path }}" />
                                    Hapus
                                </label>
                            </div>
                        </div>
                    @endforeach
                    <label for="file_surat" class="form-label mt-2">Tambah File Baru (opsional)</label>
                    <input type="file" id="file_surat" name="file_surat[]" multiple
                           accept="image/*,.pdf"
                           class="mt-1 block w-full text-sm text-gray-700 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100" />
                    <div class="file-preview-grid"></div>
                    <p class="mt-1 text-xs text-gray-400">File baru akan ditambahkan ke file yang sudah ada — file lama tetap tersimpan. Pilih beberapa foto/PDF sekaligus untuk digabung jadi 1 PDF. Maksimal 10 file per unggahan. Biarkan kosong jika tidak menambah file.</p>
                    @error('file_surat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    @error('file_surat.*')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 border-t pt-4">
                <x-button variant="secondary" href="{{ route('surat-masuk.index', ['jenis_surat' => $jenisActive, 'tahun' => $suratMasuk->tahun]) }}" class="w-full sm:w-auto">Batal</x-button>
                <x-button variant="primary" type="submit" class="w-full sm:w-auto justify-center">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </x-card>

<div id="confirmHapusModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 dark:bg-black/60" style="display:none;">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-start gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-slate-100">Hapus File</h3>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Apakah Anda yakin ingin menghapus file scan surat ini?</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 dark:text-slate-300 mb-6 ml-13 pl-13">Tindakan ini akan menghapus file dari server dan <strong>tidak dapat dibatalkan</strong>.</p>
        <div class="flex justify-end gap-3">
            <button type="button" id="batalHapusBtn" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-slate-300 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 rounded-lg transition">Batal</button>
            <button type="button" id="konfirmasiHapusBtn" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-800 rounded-lg transition">Ya, Hapus</button>
        </div>
    </div>
</div>


@endsection
