@php
    $indent = $level * 24;
    
    $tingkatLabels = [
        1 => 'Utama',
        2 => 'Sub',
        3 => 'Detail',
        4 => 'Sub-Detail',
    ];
    
    $nasibAkhirLabels = [
        'musnah' => ['label' => 'Musnah', 'class' => 'badge-danger'],
        'permanen' => ['label' => 'Permanen', 'class' => 'badge-success'],
        'dinilai' => ['label' => 'Dinilai', 'class' => 'badge-warning'],
    ];
    
    $keamananLabels = [
        'sangat_rahasia' => 'Sangat Rahasia',
        'rahasia' => 'Rahasia',
        'terbatas' => 'Terbatas',
        'umum' => 'Umum',
    ];
    
    $nasibAkhir = $nasibAkhirLabels[$klasifikasi->nasib_akhir] ?? ['label' => $klasifikasi->nasib_akhir_label, 'class' => 'badge-neutral'];
@endphp

<tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/40 transition" style="padding-left: {{ $indent }}px;" aria-level="{{ $klasifikasi->tingkat }}" role="row">
    <td class="table-td font-mono font-bold text-neutral-800 dark:text-neutral-100">
        <a href="{{ route('klasifikasi-arsip.show', $klasifikasi->id) }}" class="text-primary hover:underline hover:text-primary-dark">
            {{ $klasifikasi->kode }}
        </a>
    </td>
    <td class="table-td text-neutral-700 dark:text-neutral-300">
        <div class="max-w-xs md:max-w-md line-clamp-2" title="{{ $klasifikasi->nama }}">
            {{ $klasifikasi->nama }}
        </div>
    </td>
    <td class="table-td text-center">
        <x-badge type="{{ $klasifikasi->tingkat === 1 ? 'blue' : ($klasifikasi->tingkat === 2 ? 'green' : ($klasifikasi->tingkat === 3 ? 'yellow' : 'purple')) }}">
            {{ $klasifikasi->tingkat_label }}
        </x-badge>
    </td>
    <td class="table-td text-center text-sm">
        <span class="inline-flex items-center gap-1">
            <x-badge type="blue">{{ $klasifikasi->retensi_aktif_tahun }} th</x-badge>
            /
            <x-badge type="gray">{{ $klasifikasi->retensi_inaktif_tahun }} th</x-badge>
        </span>
        <div class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5">Total: {{ $klasifikasi->total_masa_simpan }} thn</div>
    </td>
    <td class="table-td text-center">
        <x-badge type="{{ $klasifikasi->nasib_akhir_badge_class }}">
            {{ $klasifikasi->nasib_akhir_label }}
        </x-badge>
    </td>
    <td class="table-td text-center">
        @if($klasifikasi->keamanan)
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full border bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800">
                {{ $klasifikasi->keamanan_label }}
            </span>
        @else
            <span class="text-neutral-400 text-xs">-</span>
        @endif
    </td>
    <td class="table-td">
        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('klasifikasi-arsip.show', $klasifikasi->id) }}"
               class="rounded p-1 text-primary hover:bg-primary-light dark:hover:bg-primary/20" title="Lihat Detail">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </a>
            @can('klasifikasi-arsip.edit', $klasifikasi)
                <a href="{{ route('klasifikasi-arsip.edit', $klasifikasi->id) }}"
                   class="rounded p-1 text-warning hover:bg-yellow-50 dark:hover:bg-yellow-900/20" title="Sunting">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
            @endcan
            @can('klasifikasi-arsip.destroy', $klasifikasi)
                <form action="{{ route('klasifikasi-arsip.destroy', $klasifikasi->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="button"
                            class="delete-trigger rounded p-1 text-danger hover:bg-red-50 dark:hover:bg-red-900/20" title="Hapus"
                            onclick="if(confirm('Hapus klasifikasi \'{{ addslashes($klasifikasi->nama) }}\'? Sub-klasifikasi (jika ada) akan ikut terhapus. Tindakan ini tidak bisa dibatalkan.')) this.closest('form').submit();">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            @endcan
        </div>
    </td>
</tr>

@if ($klasifikasi->children && $klasifikasi->children->count() > 0)
    @foreach ($klasifikasi->children as $child)
        @include('klasifikasi-arsip.partials.row', ['klasifikasi' => $child, 'level' => $level + 1])
    @endforeach
@endif