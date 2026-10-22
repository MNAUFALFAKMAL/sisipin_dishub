@extends('layouts.app')
@section('title', 'Manajemen Disposisi Surat Masuk')
@section('page-title', 'Manajemen Disposisi Surat Masuk')

@section('content')
<div class="space-y-4">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Daftar Disposisi Surat Masuk</h2>
            <p class="text-sm text-slate-500">Kelola dan lacak seluruh alur disposisi surat masuk</p>
        </div>
        <div class="action-group">
            <a href="{{ route('disposisi.create') }}"
                class="btn-primary">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Buat Disposisi
            </a>
        </div>
    </div>

    {{-- Filter & Search --}}
    <x-card>
        <form method="GET" action="{{ route('disposisi.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Cari Kata Kunci</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nomor surat, perihal, instruksi..."
                       class="form-input w-full py-2 text-sm" />
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Status</label>
                <select name="status" class="form-select w-full py-2 text-sm">
                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
                    <option value="baru" {{ request('status') == 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="dibaca" {{ request('status') == 'dibaca' ? 'selected' : '' }}>Dibaca</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Prioritas</label>
                <select name="prioritas" class="form-select w-full py-2 text-sm">
                    <option value="" {{ request('prioritas') == '' ? 'selected' : '' }}>Semua Prioritas</option>
                    <option value="biasa" {{ request('prioritas') == 'biasa' ? 'selected' : '' }}>Biasa</option>
                    <option value="segera" {{ request('prioritas') == 'segera' ? 'selected' : '' }}>Segera</option>
                    <option value="mendesak" {{ request('prioritas') == 'mendesak' ? 'selected' : '' }}>Mendesak</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">User</label>
                <select name="user_id" class="form-select w-full py-2 text-sm">
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->peran?->nama ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Bidang</label>
                <select name="bidang_id" class="form-select w-full py-2 text-sm">
                    <option value="">Semua Bidang</option>
                    @foreach($bidangs as $bidang)
                        <option value="{{ $bidang->id }}" {{ request('bidang_id') == $bidang->id ? 'selected' : '' }}>
                            {{ $bidang->kode_bidang }} - {{ $bidang->nama_bidang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1 py-2 text-sm flex items-center justify-center gap-1.5 shadow-sm dark:shadow-none">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari
                </button>
                @if(request()->hasAny(['search','status','prioritas','user_id','bidang_id','start_date','end_date']))
                <a href="{{ route('disposisi.index') }}" class="btn-secondary">Reset</a>
                @endif
            </div>
        </form>
    </x-card>

    {{-- Table --}}
    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No.</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Surat Masuk</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Dari</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kepada</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Instruksi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Prioritas</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Batas Waktu</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                    @forelse($disposisis as $index => $disposisi)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                        <td class="px-4 py-3 text-center font-medium">{{ $disposisis->firstItem() + $index }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('surat-masuk.show', $disposisi->suratMasuk) }}"
                               class="font-medium text-blue-600 hover:underline">
                                {{ $disposisi->suratMasuk->no_surat }} / {{ $disposisi->suratMasuk->no_agenda }}
                            </a>
                            <p class="text-xs text-slate-500 truncate max-w-xs">{{ $disposisi->suratMasuk->perihal }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $disposisi->dariUser->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            {{ $disposisi->kepadaUser->name ?? '-' }}
                            @if($disposisi->kepadaBidang)
                                <span class="text-xs text-slate-400 ml-1">({{ $disposisi->kepadaBidang->kode_bidang }})</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 max-w-xs truncate">{{ $disposisi->instruksi }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                @if($disposisi->prioritas == 'mendesak') bg-red-100 text-red-800
                                @elseif($disposisi->prioritas == 'segera') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ $disposisi->prioritas_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                @if($disposisi->status == 'selesai') bg-green-100 text-green-800
                                @elseif($disposisi->status == 'diproses') bg-blue-100 text-blue-800
                                @elseif($disposisi->status == 'dibaca') bg-yellow-100 text-yellow-800
                                @elseif($disposisi->status == 'ditolak') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $disposisi->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-600">
                            {{ $disposisi->batas_waktu ? \Carbon\Carbon::parse($disposisi->batas_waktu)->translatedFormat('d/m/Y H:i') : '-' }}
                            @if($disposisi->is_overdue)
                                <span class="ml-1 text-red-600 text-xs font-bold">(TERLAMBAT)</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('disposisi.show', $disposisi) }}"
                               class="rounded-lg p-2.5 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors" title="Detail">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            @can('update', $disposisi)
                                <a href="{{ route('disposisi.edit', $disposisi) }}"
                                   class="rounded-lg p-2.5 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 transition-colors" title="Edit">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            @endcan
                            @can('delete', $disposisi)
                                <form action="{{ route('disposisi.destroy', $disposisi) }}" method="POST"
                                      class="delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                            class="delete-trigger rounded-lg p-2.5 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="Hapus">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-12 text-center text-slate-400 dark:text-slate-500">
                            <svg class="mx-auto mb-3 h-10 w-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <p class="text-sm italic">Belum ada data disposisi.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="w-full min-w-full flex justify-center py-3">
                {{ $disposisis->withQueryString()->links() }}
            </div>
        </div>
    </x-card>
</div>
@endsection