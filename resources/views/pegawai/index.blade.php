@extends('layouts.app')
@section('title', 'Data Pegawai')
@section('page-title', 'Data Pegawai')

@section('content')
<div class="space-y-4">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Data Pegawai</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Daftar pegawai Dishub Lampung (sumber daftar Tujuan SPT)</p>
        </div>
        <div>
            <x-button variant="primary" href="{{ route('pegawai.create') }}">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Pegawai
            </x-button>
        </div>
    </div>

    {{-- Filter Bidang Pills --}}
    @if(!empty($bidangList))
    <div class="mb-4">
        <div class="flex flex-wrap gap-2 pb-1 border-b border-gray-200 dark:border-slate-700 overflow-y-auto no-scrollbar">
            <a href="{{ route('pegawai.index', array_merge(request()->except('bidang'), ['status' => request('status')])) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap flex items-center gap-1.5 transition-all duration-200 shadow-sm dark:shadow-none border
               {{ !request()->filled('bidang') ? 'nav-pill-active' : 'nav-pill-inactive' }}"
               aria-current="{{ !request()->filled('bidang') ? 'page' : 'false' }}">
                <span>Semua</span>
                <span class="text-[10px] opacity-70">({{ array_sum($bidangCount) }})</span>
            </a>
            @foreach($bidangList as $bd)
                <a href="{{ route('pegawai.index', array_merge(request()->except('bidang'), ['bidang' => $bd, 'status' => request('status')])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap flex items-center gap-1.5 transition-all duration-200 shadow-sm dark:shadow-none border
                   {{ request('bidang') === $bd ? 'nav-pill-active' : 'nav-pill-inactive' }}"
                   title="{{ $bd }}"
                   aria-label="{{ $bd }} ({{ $bidangCount[$bd] ?? 0 }} pegawai)"
                   aria-current="{{ request('bidang') === $bd ? 'page' : 'false' }}">
                    <span class="truncate max-w-[200px] block">{{ $bd }}</span>
                    <span class="text-[10px] opacity-70">({{ $bidangCount[$bd] ?? 0 }})</span>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Filter & Search --}}
    <x-card>
        <form method="GET" action="{{ route('pegawai.index') }}" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, NIP, jabatan, atau unit/bidang..."
                   class="form-input max-w-xs flex-1" />
            <select name="status" class="form-select w-44">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
            <x-button variant="primary" type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Filter
            </x-button>
            @if(request()->hasAny(['search', 'status']))
                <x-button variant="secondary" href="{{ route('pegawai.index') }}">Reset</x-button>
            @endif
        </form>
    </x-card>

    {{-- Table --}}
    <x-card class="p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 dark:bg-gray-800/40">
                    <tr>
                        <th class="table-th">Pegawai</th>
                        <th class="table-th hidden md:table-cell">Pangkat & Golongan</th>
                        <th class="table-th hidden lg:table-cell">Jabatan</th>
                        <th class="table-th hidden lg:table-cell">Unit / Bidang</th>
                        <th class="table-th">Status</th>
                        <th class="table-th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-[#161b27]">
                    @forelse($pegawais as $pegawai)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                        <td class="table-td">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-9 w-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-800 font-bold text-sm dark:bg-blue-900/50 dark:text-blue-200">
                                    {{ strtoupper(substr($pegawai->nama, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $pegawai->nama }}</div>
                                    @if($pegawai->nip)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $pegawai->nip }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="table-td hidden md:table-cell">
                            <div class="text-sm text-gray-700 dark:text-gray-300">{{ $pegawai->pangkat ?? '—' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $pegawai->golongan ?? '' }}</div>
                        </td>
                        <td class="table-td hidden lg:table-cell">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $pegawai->jabatan ?? '—' }}</span>
                        </td>
                        <td class="table-td hidden lg:table-cell">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $pegawai->unit_bidang ?? '—' }}</span>
                        </td>
                        <td class="table-td">
                            @if($pegawai->status === 'aktif')
                                <x-badge type="green">Aktif</x-badge>
                            @else
                                <x-badge type="red">Tidak Aktif</x-badge>
                            @endif
                        </td>
                        <td class="table-td">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('pegawai.show', $pegawai->id) }}"
                                   class="rounded p-1 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/30" title="Lihat Detail">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('pegawai.edit', $pegawai->id) }}"
                                   class="rounded p-1 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/30" title="Edit">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @if($pegawai->status === 'aktif')
                                    <form action="{{ route('pegawai.destroy', $pegawai->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menonaktifkan pegawai ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="rounded p-1 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30" title="Nonaktifkan">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('pegawai.activate', $pegawai->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin mengaktifkan pegawai ini?')">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="rounded p-1 text-green-600 hover:bg-green-50 dark:text-green-400 dark:hover:bg-green-900/30" title="Aktifkan">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('pegawai.force-delete', $pegawai->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus permanent pegawai ini? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="rounded p-1 text-orange-600 hover:bg-orange-50 dark:text-orange-400 dark:hover:bg-orange-900/30" title="Hapus Permanent">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400 dark:text-gray-500">
                            <svg class="mx-auto mb-3 h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <p class="text-sm italic">Tidak ada data pegawai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="w-full min-w-full flex justify-center py-3">
                {{ $pegawais->withQueryString()->links() }}
            </div>
        </div>
    </x-card>

</div>
@endsection
