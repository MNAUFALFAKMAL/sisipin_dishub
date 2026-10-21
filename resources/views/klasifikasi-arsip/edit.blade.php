@extends('layouts.app')
@section('title', 'Edit Klasifikasi Arsip')
@section('page-title', 'Edit Klasifikasi Arsip')

@section('content')
<div class="max-w-2xl">
    <x-card>
        <form action="{{ route('klasifikasi-arsip.update', $klasifikasiArsip->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="kode" class="form-label">Kode Klasifikasi <span class="text-red-500">*</span></label>
                <input type="text" name="kode" id="kode" value="{{ old('kode', $klasifikasiArsip->kode) }}" required 
                       class="form-input" placeholder="Contoh: 000 atau HK.01.01">
                @error('kode') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="nama" class="form-label">Nama/Uraian Klasifikasi <span class="text-red-500">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $klasifikasiArsip->nama) }}" required 
                       class="form-input" placeholder="Contoh: UMUM">
                @error('nama') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="parent_id" class="form-label">Induk Klasifikasi (Parent)</label>
                <select name="parent_id" id="parent_id" class="form-select">
                    <option value="">-- Tidak Ada / Sebagai Induk Utama --</option>
                    @foreach($parents as $p)
                        <option value="{{ $p->id }}" {{ old('parent_id', $klasifikasiArsip->parent_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->kode }} - {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="retensi_aktif_tahun" class="form-label">Retensi Aktif (Tahun) <span class="text-red-500">*</span></label>
                    <input type="number" min="0" name="retensi_aktif_tahun" id="retensi_aktif_tahun" value="{{ old('retensi_aktif_tahun', $klasifikasiArsip->retensi_aktif_tahun) }}" required 
                           class="form-input">
                    @error('retensi_aktif_tahun') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="retensi_inaktif_tahun" class="form-label">Retensi Inaktif (Tahun) <span class="text-red-500">*</span></label>
                    <input type="number" min="0" name="retensi_inaktif_tahun" id="retensi_inaktif_tahun" value="{{ old('retensi_inaktif_tahun', $klasifikasiArsip->retensi_inaktif_tahun) }}" required 
                           class="form-input">
                    @error('retensi_inaktif_tahun') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="nasib_akhir" class="form-label">Nasib Akhir (JRA) <span class="text-red-500">*</span></label>
                <select name="nasib_akhir" id="nasib_akhir" class="form-select" required>
                    <option value="dinilai" {{ old('nasib_akhir', $klasifikasiArsip->nasib_akhir) == 'dinilai' ? 'selected' : '' }}>Dinilai Kembali</option>
                    <option value="musnah" {{ old('nasib_akhir', $klasifikasiArsip->nasib_akhir) == 'musnah' ? 'selected' : '' }}>Musnah</option>
                    <option value="permanen" {{ old('nasib_akhir', $klasifikasiArsip->nasib_akhir) == 'permanen' ? 'selected' : '' }}>Permanen</option>
                </select>
                @error('nasib_akhir') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="keamanan" class="form-label">Klasifikasi Keamanan (SKKAAD)</label>
                    <select name="keamanan" id="keamanan" class="form-select">
                        <option value="">-- Pilih Keamanan --</option>
                        <option value="Biasa" {{ old('keamanan', $klasifikasiArsip->keamanan) == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="Terbatas" {{ old('keamanan', $klasifikasiArsip->keamanan) == 'Terbatas' ? 'selected' : '' }}>Terbatas</option>
                        <option value="Rahasia" {{ old('keamanan', $klasifikasiArsip->keamanan) == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                        <option value="Sangat Rahasia" {{ old('keamanan', $klasifikasiArsip->keamanan) == 'Sangat Rahasia' ? 'selected' : '' }}>Sangat Rahasia</option>
                    </select>
                    @error('keamanan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="hak_akses" class="form-label">Hak Akses</label>
                    <input type="text" name="hak_akses" id="hak_akses" value="{{ old('hak_akses', $klasifikasiArsip->hak_akses) }}" 
                           class="form-input" placeholder="Contoh: Eselon III / Semua Pegawai">
                    @error('hak_akses') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="dasar_pertimbangan" class="form-label">Dasar Pertimbangan Keamanan / Regulasi</label>
                <textarea name="dasar_pertimbangan" id="dasar_pertimbangan" rows="3"
                          class="form-input" placeholder="Contoh: Tidak memiliki dampak yang mengganggu organisasi">{{ old('dasar_pertimbangan', $klasifikasiArsip->dasar_pertimbangan) }}</textarea>
                @error('dasar_pertimbangan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label for="unit_pengolah" class="form-label">Unit Pengolah</label>
                <input type="text" name="unit_pengolah" id="unit_pengolah" value="{{ old('unit_pengolah', $klasifikasiArsip->unit_pengolah) }}" 
                       class="form-input" placeholder="Contoh: Unit Kerja Terkait / Sekretariat">
                @error('unit_pengolah') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center gap-3">
                <x-button variant="primary" type="submit">Update</x-button>
                <x-button variant="secondary" href="{{ route('klasifikasi-arsip.index') }}">Batal</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
