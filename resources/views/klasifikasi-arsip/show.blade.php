@extends('layouts.app')
@section('title', 'Detail Klasifikasi Arsip')
@section('page-title', 'Detail Klasifikasi Arsip')

@section('content')
<div class="max-w-2xl">
    <x-card>
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Informasi Klasifikasi</h3>
        
        <table class="w-full text-sm text-left text-gray-700">
            <tbody>
                <tr class="border-b">
                    <th class="py-3 font-medium w-1/3">Kode Klasifikasi</th>
                    <td class="py-3">{{ $klasifikasiArsip->kode }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-3 font-medium">Nama/Uraian</th>
                    <td class="py-3">{{ $klasifikasiArsip->nama }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-3 font-medium">Induk Klasifikasi</th>
                    <td class="py-3">
                        @if($klasifikasiArsip->parent)
                            {{ $klasifikasiArsip->parent->kode }} - {{ $klasifikasiArsip->parent->nama }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr class="border-b">
                    <th class="py-3 font-medium">Retensi Aktif</th>
                    <td class="py-3">{{ $klasifikasiArsip->retensi_aktif_tahun }} Tahun</td>
                </tr>
                <tr class="border-b">
                    <th class="py-3 font-medium">Retensi Inaktif</th>
                    <td class="py-3">{{ $klasifikasiArsip->retensi_inaktif_tahun }} Tahun</td>
                </tr>
                <tr class="border-b">
                    <th class="py-3 font-medium">Nasib Akhir</th>
                    <td class="py-3 capitalize">{{ $klasifikasiArsip->nasib_akhir }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-3 font-medium">Klasifikasi Keamanan (SKKAAD)</th>
                    <td class="py-3">
                        @if($klasifikasiArsip->keamanan)
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full border bg-purple-50 text-purple-700 border-purple-200">
                                {{ $klasifikasiArsip->keamanan }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr class="border-b">
                    <th class="py-3 font-medium">Hak Akses</th>
                    <td class="py-3 text-indigo-700 font-semibold">{{ $klasifikasiArsip->hak_akses ?: '-' }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-3 font-medium">Dasar Pertimbangan</th>
                    <td class="py-3 text-gray-600 text-sm whitespace-pre-wrap">{{ $klasifikasiArsip->dasar_pertimbangan ?: '-' }}</td>
                </tr>
                <tr>
                    <th class="py-3 font-medium">Unit Pengolah</th>
                    <td class="py-3">{{ $klasifikasiArsip->unit_pengolah ?: '-' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-6 flex items-center gap-3">
            <x-button variant="primary" href="{{ route('klasifikasi-arsip.edit', $klasifikasiArsip->id) }}">Edit Data</x-button>
            <x-button variant="secondary" href="{{ route('klasifikasi-arsip.index') }}">Kembali</x-button>
        </div>
    </x-card>
</div>
@endsection
