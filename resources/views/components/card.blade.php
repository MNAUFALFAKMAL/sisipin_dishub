<div {{ $attributes->merge(['class' => 'card']) }}>
    @if(isset($header))
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-800">{{ $header }}</h3>
            @if(isset($action))
                <div>{{ $action }}</div>
            @endif
        </div>
    @endif
    {{ $slot }}
</div>
