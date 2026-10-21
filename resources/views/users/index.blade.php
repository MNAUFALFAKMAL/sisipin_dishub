@extends('layouts.app')
@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna')

@section('content')
<div class="space-y-4">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-bold text-neutral-800 dark:text-neutral-100">Kelola Pengguna</h2>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">Kelola akun pengguna, peran, dan akses sistem</p>
        </div>
        <div>
            <x-button variant="primary" href="{{ route('users.create') }}">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Tambah Pengguna
            </x-button>
        </div>
    </div>

    {{-- Saring & Cari --}}
    <x-card>
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama atau email..."
                   class="input max-w-xs flex-1" />
            <select name="peran" class="input w-44">
                <option value="">Semua Peran</option>
                <option value="pengelola_sistem" {{ request('peran') == 'pengelola_sistem' ? 'selected' : '' }}>Pengelola Sistem</option>
                <option value="kasubbag" {{ request('peran') == 'kasubbag' ? 'selected' : '' }}>Kasubbag</option>
                <option value="staff_subbagian" {{ request('peran') == 'staff_subbagian' ? 'selected' : '' }}>Staff Subbagian</option>
            </select>
            <select name="bidang_id" class="input w-44">
                <option value="">Semua Bidang</option>
                @foreach($bidangs as $bidang)
                    <option value="{{ $bidang->id }}" {{ request('bidang_id') == $bidang->id ? 'selected' : '' }}>{{ $bidang->nama_bidang }}</option>
                @endforeach
            </select>
            <select name="status" class="input w-36">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <x-button variant="primary" type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Saring
            </x-button>
            @if(request()->hasAny(['search', 'peran', 'bidang_id', 'status']))
                <x-button variant="secondary" href="{{ route('users.index') }}">Reset</x-button>
            @endif
        </form>
    </x-card>

    {{-- Tabel --}}
    <x-card class="p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50 dark:bg-neutral-800/40">
                    <tr>
                        <th class="table-th">Pengguna</th>
                        <th class="table-th">Peran & Bidang</th>
                        <th class="table-th">Status</th>
                        <th class="table-th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white dark:divide-neutral-800 dark:bg-neutral-950">
                    @forelse($users as $user)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/40 transition">
                        <td class="table-td">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-9 w-9 flex items-center justify-center rounded-full bg-primary-light text-primary font-bold text-sm dark:bg-primary/20 dark:text-primary-light">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">{{ $user->name }}</div>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400 font-mono">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="table-td">
                            <div class="text-sm font-semibold text-neutral-800 capitalize dark:text-neutral-100">{{ $user->peran_label }}</div>
                            <div class="text-xs text-neutral-500 dark:text-neutral-400">{{ $user->bidang ? $user->bidang->nama_bidang : 'Semua Bidang' }}</div>
                        </td>
                        <td class="table-td">
                            @if($user->is_active)
                                <x-badge type="green">Aktif</x-badge>
                            @else
                                <x-badge type="red">Nonaktif</x-badge>
                            @endif
                        </td>
                        <td class="table-td">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('users.edit', $user->id) }}"
                                   class="rounded p-1 text-primary hover:bg-primary-light dark:hover:bg-primary/20" title="Sunting">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @if($user->id !== auth()->id())
                                    @if($user->is_active)
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin menonaktifkan pengguna ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="rounded p-1 text-danger hover:bg-red-50 dark:hover:bg-red-900/20" title="Nonaktifkan">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('users.activate', $user->id) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin mengaktifkan pengguna ini?')">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    class="rounded p-1 text-success hover:bg-green-50 dark:hover:bg-green-900/20" title="Aktifkan">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-neutral-400 dark:text-neutral-500">
                            <svg class="mx-auto mb-3 h-10 w-10 text-neutral-300 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <p class="text-sm italic">Tidak ada data pengguna.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="w-full min-w-full flex justify-center py-3">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </x-card>

</div>
@endsection