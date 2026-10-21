@props(['type' => 'gray'])

<span {{ $attributes->merge(['class' => 'badge-' . $type]) }}>
    {{ $slot }}
</span>
