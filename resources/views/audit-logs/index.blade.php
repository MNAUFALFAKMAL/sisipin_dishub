@extends('layouts.app')
@section('title', 'Audit Log')
@section('page-title', 'Audit Log')

@section('content')
<div class="space-y-4">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Audit Log</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Pantau aktivitas pembuatan, pembaruan, dan penghapusan data surat oleh pengguna</p>
        </div>
    </div>

    {{-- Filter & Search --}}
    <x-card>
        <form method="GET" action="{{ route('audit-logs.index') }}" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari aktivitas, tipe model, IP, atau pengguna..."
                   class="form-input max-w-xs flex-1" />
            <x-button variant="primary" type="submit">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari
            </x-button>
            @if(request()->has('search'))
                <x-button variant="secondary" href="{{ route('audit-logs.index') }}">Reset</x-button>
            @endif
        </form>
    </x-card>

    {{-- Table --}}
    <x-card class="p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full" style="vertical-align: middle; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th class="table-th text-center">Tanggal &amp; Waktu</th>
                        <th class="table-th text-center">Pengguna</th>
                        <th class="table-th text-center">Aktivitas</th>
                        <th class="table-th text-center">Modul / ID</th>
                        <th class="table-th text-center">Detail Perubahan</th>
                        <th class="table-th text-center">IP / Browser</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr class="transition-colors">
                        <td class="table-td text-center text-slate-600 dark:text-slate-400 whitespace-nowrap">
                            {{ $log->created_at ? $log->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s') . ' WIB' : '-' }}
                        </td>
                        <td class="table-td text-center font-semibold text-slate-800 dark:text-slate-200">
                            {{ $log->user->name ?? 'Sistem/Guest' }}
                        </td>
                        <td class="table-td text-center">
                            @if($log->activity === 'created')
                                <x-badge type="green">CREATED</x-badge>
                            @elseif($log->activity === 'updated')
                                <x-badge type="blue">UPDATED</x-badge>
                            @elseif($log->activity === 'deleted')
                                <x-badge type="red">DELETED</x-badge>
                            @else
                                <x-badge type="gray">{{ strtoupper($log->activity) }}</x-badge>
                            @endif
                        </td>
                        <td class="table-td text-center">
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ class_basename($log->model_type) }}</span>
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-bold ml-1">ID: {{ $log->model_id ?? '-' }}</span>
                        </td>
                        <td class="table-td text-left min-w-[300px]">
                            {{-- Detail cell: plain, no background box, clean typography --}}
                            @if($log->activity === 'updated')
                                <p class="text-[11px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wide mb-1">Perubahan Kolom:</p>
                                @if(is_array($log->new_values))
                                    @foreach($log->new_values as $key => $val)
                                        @php
                                            $oldVal = $log->old_values[$key] ?? 'NULL';
                                            if (is_bool($oldVal)) $oldVal = $oldVal ? 'true' : 'false';
                                            if (is_array($oldVal)) $oldVal = json_encode($oldVal);
                                            $newVal = $val ?? 'NULL';
                                            if (is_bool($newVal)) $newVal = $newVal ? 'true' : 'false';
                                            if (is_array($newVal)) $newVal = json_encode($newVal);
                                        @endphp
                                        <div class="text-xs font-mono leading-relaxed break-all mb-0.5">
                                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $key }}:</span>
                                            <span class="text-red-500 dark:text-red-400 line-through"> {{ $oldVal }}</span>
                                            <span class="text-slate-400 mx-0.5">→</span>
                                            <span class="text-green-600 dark:text-green-400 font-semibold">{{ $newVal }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            @elseif($log->activity === 'created')
                                <p class="text-[11px] font-bold text-green-600 dark:text-green-400 uppercase tracking-wide mb-1">Nilai Awal:</p>
                                @if(is_array($log->new_values))
                                    @foreach($log->new_values as $key => $val)
                                        @if($val !== null)
                                        <div class="text-xs font-mono leading-relaxed break-all mb-0.5">
                                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $key }}:</span>
                                            <span class="text-slate-600 dark:text-slate-400"> {{ is_array($val) ? json_encode($val) : $val }}</span>
                                        </div>
                                        @endif
                                    @endforeach
                                @endif
                            @elseif($log->activity === 'deleted')
                                <p class="text-[11px] font-bold text-red-600 dark:text-red-400 uppercase tracking-wide mb-1">Nilai Sebelum Dihapus:</p>
                                @if(is_array($log->old_values))
                                    @foreach($log->old_values as $key => $val)
                                        @if($val !== null)
                                        <div class="text-xs font-mono leading-relaxed break-all mb-0.5">
                                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $key }}:</span>
                                            <span class="text-slate-600 dark:text-slate-400"> {{ is_array($val) ? json_encode($val) : $val }}</span>
                                        </div>
                                        @endif
                                    @endforeach
                                @endif
                            @else
                                <span class="text-xs text-slate-400 dark:text-slate-500">Tidak ada detail</span>
                            @endif
                        </td>
                        <td class="table-td text-center whitespace-nowrap">
                            <div class="font-medium text-slate-800 dark:text-slate-200">{{ $log->ip_address ?? '-' }}</div>
                            <div class="text-xs text-slate-400 dark:text-slate-500 truncate max-w-xs" title="{{ $log->user_agent }}">{{ $log->user_agent ? \Illuminate\Support\Str::limit($log->user_agent, 30) : '-' }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="table-td text-center text-slate-500 dark:text-slate-400 py-10">
                            Tidak ditemukan riwayat log aktivitas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="w-full min-w-full flex justify-center py-3">
                {{ $logs->links() }}
            </div>
        </div>
    </x-card>

</div>
@endsection
