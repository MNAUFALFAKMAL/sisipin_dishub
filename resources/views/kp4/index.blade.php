@extends('layouts.app')
@section('title', 'Form KP4')
@section('page-title', 'Form KP4 — Surat Keterangan Tunjangan Keluarga')

@section('content')
<div class="max-w-4xl">

    <div class="mb-4 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
        <span>/</span>
        <span class="text-gray-800 dark:text-gray-200 font-medium">KP4</span>
    </div>

    <x-card>
        <h2 class="mb-6 text-base font-bold text-gray-800 dark:text-gray-100 border-b dark:border-gray-700 pb-4">
            Generate Dokumen KP4
        </h2>

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded text-sm text-red-700 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('kp4.generate') }}" method="POST" class="space-y-5">
            @csrf

            {{-- 1. Data Diri --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">Data Diri Pegawai</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="form-label">Nama lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" class="form-input" required value="{{ old('nama') }}" placeholder="YUSSUDARSO, S.IP">
                    </div>
                    <div>
                        <label class="form-label">NIP <span class="text-red-500">*</span></label>
                        <input type="text" name="nip" class="form-input" required value="{{ old('nip') }}" placeholder="19740828 200701 1 012">
                    </div>
                    <div>
                        <label class="form-label">Tempat / Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="text" name="tempat_lahir" class="form-input" required value="{{ old('tempat_lahir') }}" placeholder="Kenali, 28 Agustus 1974">
                    </div>
                    <div>
                        <label class="form-label">Jenis kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="Pria" {{ old('jenis_kelamin') == 'Pria' ? 'selected' : '' }}>Pria</option>
                            <option value="Wanita" {{ old('jenis_kelamin') == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Agama <span class="text-red-500">*</span></label>
                        <input type="text" name="agama" class="form-input" required value="{{ old('agama') }}" placeholder="Islam">
                    </div>
                    <div>
                        <label class="form-label">Kebangsaan <span class="text-red-500">*</span></label>
                        <input type="text" name="kebangsaan" class="form-input" required value="{{ old('kebangsaan', 'Indonesia') }}">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Pangkat / Golongan <span class="text-red-500">*</span></label>
                        <input type="text" name="pangkat_golongan" class="form-input" required value="{{ old('pangkat_golongan') }}" placeholder="Pembina Muda TK.I / III.b">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Jabatan Struktural/Fungsional <span class="text-red-500">*</span></label>
                        <input type="text" name="jabatan" class="form-input" required value="{{ old('jabatan') }}" placeholder="Pengatur Lalu Lintas Jalan">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Pada Instansi <span class="text-red-500">*</span></label>
                        <input type="text" name="instansi" class="form-input" required value="{{ old('instansi', 'Dinas Perhubungan Provinsi Lampung') }}">
                    </div>
                    <div>
                        <label class="form-label">Masa kerja golongan</label>
                        <input type="text" name="masa_kerja_golongan" class="form-input" value="{{ old('masa_kerja_golongan') }}" placeholder="22 Tahun">
                    </div>
                    <div>
                        <label class="form-label">Masa kerja tambahan</label>
                        <input type="text" name="masa_kerja_tambahan" class="form-input" value="{{ old('masa_kerja_tambahan') }}" placeholder="0 Th 9 bln">
                    </div>
                    <div>
                        <label class="form-label">Masa kerja keseluruhan</label>
                        <input type="text" name="masa_kerja_total" class="form-input" value="{{ old('masa_kerja_total') }}" placeholder="22 Tahun 9 Bulan">
                    </div>
                    <div>
                        <label class="form-label">Digaji menurut</label>
                        <input type="text" name="gaji_pp" class="form-input" value="{{ old('gaji_pp') }}" placeholder="PP 5 Tahun 2024">
                    </div>
                    <div>
                        <label class="form-label">Dengan gaji pokok Rp.</label>
                        <input type="text" name="gaji_pokok" class="form-input" value="{{ old('gaji_pokok') }}" placeholder="Rp. 4.083.900">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Alamat / tempat tinggal <span class="text-red-500">*</span></label>
                        <textarea name="alamat" rows="2" class="form-input" required placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>

            <hr class="dark:border-gray-700">

            {{-- 2. Pekerjaan Sampingan & Pensiun --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">Pekerjaan Sampingan & Pensiun</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="form-label">a. Di samping jabatan utama, bekerja sebagai</label>
                        <input type="text" name="pekerjaan_sampingan" class="form-input" value="{{ old('pekerjaan_sampingan') }}" placeholder="-">
                    </div>
                    <div>
                        <label class="form-label">Penghasilan sebesar Rp.</label>
                        <input type="text" name="penghasilan_sampingan" class="form-input" value="{{ old('penghasilan_sampingan') }}" placeholder="-">
                    </div>
                    <div></div>
                    <div class="md:col-span-2">
                        <label class="form-label">b. Mempunyai pensiun (pensiun janda/duda) Rp.</label>
                        <input type="text" name="pensiun" class="form-input" value="{{ old('pensiun') }}" placeholder="-">
                    </div>
                </div>
            </div>

            <hr class="dark:border-gray-700">

            {{-- 3. Pasangan --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">Data Pasangan (Isteri/Suami)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nama Isteri/Suami tanggungan</label>
                        <input type="text" name="pasangan_nama" class="form-input" value="{{ old('pasangan_nama') }}" placeholder="SUSIYATI">
                    </div>
                    <div>
                        <label class="form-label">Tanggal Kelahiran (umur)</label>
                        <input type="text" name="pasangan_tgl_lahir" class="form-input" value="{{ old('pasangan_tgl_lahir') }}" placeholder="23 Agustus 1978">
                    </div>
                    <div>
                        <label class="form-label">Tanggal Perkawinan</label>
                        <input type="text" name="pasangan_tgl_kawin" class="form-input" value="{{ old('pasangan_tgl_kawin') }}" placeholder="16 Juli 2005">
                    </div>
                    <div>
                        <label class="form-label">Pekerjaan</label>
                        <input type="text" name="pasangan_pekerjaan" class="form-input" value="{{ old('pasangan_pekerjaan') }}" placeholder="IRT">
                    </div>
                    <div>
                        <label class="form-label">Penghasilan sebulan Rp.</label>
                        <input type="text" name="pasangan_penghasilan" class="form-input" value="{{ old('pasangan_penghasilan') }}" placeholder="-">
                    </div>
                </div>
            </div>

            <hr class="dark:border-gray-700">

            {{-- 4. Anak --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">Data Anak</h3>

                <div class="mb-3">
                    <label class="form-label">Jumlah anak seluruhnya</label>
                    <div class="flex gap-4 items-center">
                        <input type="text" name="jumlah_anak" class="form-input w-20" value="{{ old('jumlah_anak') }}" placeholder="3">
                        <span class="text-sm text-gray-500">orang, yang masih dalam tanggungan</span>
                    </div>
                </div>

                {{-- Anak dalam Daftar Gaji --}}
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">I. Anak Kandung (AK), Anak Tiri (AT) dan Anak Angkat (AA) yang masih tanggungan, belum mempunyai penghasilan sendiri dan masuk dalam daftar gaji:</p>

                <div x-data="{
                    anak1: {{ old('anak_daftar_gaji') ?: "[{ nama: '', status: '', tgl_lahir: '', kawin: '', sekolah: '', beasiswa: '', ayah: '', ibu: '', meninggal: '', keterangan: '' }]" }},
                    add1() { this.anak1.push({ nama: '', status: '', tgl_lahir: '', kawin: '', sekolah: '', beasiswa: '', ayah: '', ibu: '', meninggal: '', keterangan: '' }) },
                    rm1(i) { if (this.anak1.length > 1) this.anak1.splice(i, 1) }
                }">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs text-gray-400">Anak dalam Daftar Gaji</span>
                        <button type="button" @click="add1()" class="btn-secondary text-xs">+ Tambah</button>
                    </div>
                    <template x-for="(a, i) in anak1" :key="'a1-'+i">
                        <div class="border dark:border-gray-600 rounded p-3 mb-2 relative">
                            <button type="button" @click="rm1(i)" class="absolute top-1 right-2 text-red-500 text-lg leading-none" x-show="anak1.length > 1">&times;</button>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                                <div><label class="text-xs text-gray-500">Nama</label><input type="text" x-model="a.nama" class="form-input text-sm" placeholder="Nama anak"></div>
                                <div><label class="text-xs text-gray-500">Status (ak/at/aa)</label>
                                    <select x-model="a.status" class="form-select text-sm">
                                        <option value="">-</option><option value="AK">AK</option><option value="AT">AT</option><option value="AA">AA</option>
                                    </select>
                                </div>
                                <div><label class="text-xs text-gray-500">Tanggal Lahir</label><input type="text" x-model="a.tgl_lahir" class="form-input text-sm" placeholder="16-01-2010"></div>
                                <div><label class="text-xs text-gray-500">Belum/Pernah kawin</label><input type="text" x-model="a.kawin" class="form-input text-sm" placeholder="-"></div>
                                <div><label class="text-xs text-gray-500">Bersekolah/kuliah pada</label><input type="text" x-model="a.sekolah" class="form-input text-sm" placeholder="-"></div>
                                <div><label class="text-xs text-gray-500">Tidak mendapat (beasiswa/darma/Ikatan)</label><input type="text" x-model="a.beasiswa" class="form-input text-sm" placeholder="-"></div>
                                <div><label class="text-xs text-gray-500">Nama Ayah</label><input type="text" x-model="a.ayah" class="form-input text-sm" placeholder="-"></div>
                                <div><label class="text-xs text-gray-500">Nama Ibu</label><input type="text" x-model="a.ibu" class="form-input text-sm" placeholder="-"></div>
                                <div><label class="text-xs text-gray-500">Tgl meninggal/dicerai ayah/ibu</label><input type="text" x-model="a.meninggal" class="form-input text-sm" placeholder="-"></div>
                                <div><label class="text-xs text-gray-500">Keterangan</label><input type="text" x-model="a.keterangan" class="form-input text-sm" placeholder="-"></div>
                            </div>
                        </div>
                    </template>
                    <input type="hidden" name="anak_daftar_gaji" :value="JSON.stringify(anak1)">
                </div>

                {{-- Anak Tidak dalam Daftar Gaji --}}
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mt-4 mb-2">II. Anak Kandung (AK), Anak Tiri (AT) dan Anak Angkat (AA) yang masih menjadi tanggungan tetapi tidak termasuk dalam Daftar Gaji:</p>

                <div x-data="{
                    anak2: {{ old('anak_luar_daftar_gaji') ?: "[{ nama: '', status: '', tgl_lahir: '', kawin: '', sekolah: '', beasiswa: '', ayah: '', ibu: '', meninggal: '', keterangan: '' }]" }},
                    add2() { this.anak2.push({ nama: '', status: '', tgl_lahir: '', kawin: '', sekolah: '', beasiswa: '', ayah: '', ibu: '', meninggal: '', keterangan: '' }) },
                    rm2(i) { if (this.anak2.length > 1) this.anak2.splice(i, 1) }
                }">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs text-gray-400">Anak di luar Daftar Gaji</span>
                        <button type="button" @click="add2()" class="btn-secondary text-xs">+ Tambah</button>
                    </div>
                    <template x-for="(a, i) in anak2" :key="'a2-'+i">
                        <div class="border dark:border-gray-600 rounded p-3 mb-2 relative">
                            <button type="button" @click="rm2(i)" class="absolute top-1 right-2 text-red-500 text-lg leading-none" x-show="anak2.length > 1">&times;</button>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                                <div><label class="text-xs text-gray-500">Nama</label><input type="text" x-model="a.nama" class="form-input text-sm" placeholder="Nama anak"></div>
                                <div><label class="text-xs text-gray-500">Status (ak/at/aa)</label>
                                    <select x-model="a.status" class="form-select text-sm">
                                        <option value="">-</option><option value="AK">AK</option><option value="AT">AT</option><option value="AA">AA</option>
                                    </select>
                                </div>
                                <div><label class="text-xs text-gray-500">Tanggal Lahir</label><input type="text" x-model="a.tgl_lahir" class="form-input text-sm" placeholder="14-02-2006"></div>
                                <div><label class="text-xs text-gray-500">Belum/Pernah kawin</label><input type="text" x-model="a.kawin" class="form-input text-sm" placeholder="-"></div>
                                <div><label class="text-xs text-gray-500">Bersekolah/kuliah pada</label><input type="text" x-model="a.sekolah" class="form-input text-sm" placeholder="-"></div>
                                <div><label class="text-xs text-gray-500">Tidak mendapat (beasiswa/darma/Ikatan)</label><input type="text" x-model="a.beasiswa" class="form-input text-sm" placeholder="-"></div>
                                <div><label class="text-xs text-gray-500">Nama Ayah</label><input type="text" x-model="a.ayah" class="form-input text-sm" placeholder="-"></div>
                                <div><label class="text-xs text-gray-500">Nama Ibu</label><input type="text" x-model="a.ibu" class="form-input text-sm" placeholder="-"></div>
                                <div><label class="text-xs text-gray-500">Tgl meninggal/dicerai ayah/ibu</label><input type="text" x-model="a.meninggal" class="form-input text-sm" placeholder="-"></div>
                                <div><label class="text-xs text-gray-500">Keterangan</label><input type="text" x-model="a.keterangan" class="form-input text-sm" placeholder="-"></div>
                            </div>
                        </div>
                    </template>
                    <input type="hidden" name="anak_luar_daftar_gaji" :value="JSON.stringify(anak2)">
                </div>
            </div>

            <hr class="dark:border-gray-700">

            {{-- 5. Tanda Tangan (auto) --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">Tanda Tangan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Yang Mengetahui (Atasan)</label>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            <span class="font-medium">Nama:</span> DR. Ir. M. SYAFRIZAL, ST. M.Si<br>
                            <span class="font-medium">NIP:</span> 19731213 200502 1 001
                        </p>
                        <p class="text-xs text-gray-400 mt-1">(Otomatis dari template)</p>
                    </div>
                    <div>
                        <label class="form-label">Yang Menerangkan</label>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            <span class="font-medium">Nama:</span> {{ old('nama', '(data diri)') }}<br>
                            <span class="font-medium">NIP:</span> {{ old('nip', '(data diri)') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">(Otomatis dari data diri pegawai)</p>
                    </div>
                    <div>
                        <label class="form-label">Tempat</label>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 font-medium">Bandar Lampung</p>
                    </div>
                    <div>
                        <label class="form-label">Tanggal</label>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 font-medium">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('j F Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Generate Dokumen .docx
                </button>
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Kembali</a>
            </div>
        </form>
    </x-card>
</div>
@endsection
