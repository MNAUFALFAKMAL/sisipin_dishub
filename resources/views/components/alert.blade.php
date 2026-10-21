@props(['type' => 'info', 'dismissible' => false])

@php
    $colors = [
        'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-950/40 dark:border-emerald-800/50 dark:text-emerald-200',
        'error'   => 'bg-red-50 border-red-200 text-red-800 dark:bg-red-950/40 dark:border-red-800/50 dark:text-red-200',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-950/40 dark:border-amber-800/50 dark:text-amber-200',
        'info'    => 'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-950/40 dark:border-blue-800/50 dark:text-blue-200',
    ];
    $color = $colors[$type] ?? $colors['info'];
@endphp

<div {{ $attributes->merge(['class' => "$color border rounded-[6px] p-4 text-sm flex items-start gap-3"]) }}
     role="alert">
    <div class="flex-1">{{ $slot }}</div>
    @if($dismissible)
        <button type="button" class="opacity-60 hover:opacity-100 transition-opacity cursor-pointer" onclick="this.parentElement.remove()" aria-label="Tutup">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    @endif
</div>
