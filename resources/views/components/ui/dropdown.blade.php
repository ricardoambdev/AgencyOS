@props(['align' => 'right'])

@php
    $alignCls = $align === 'left' ? 'left-0' : 'right-0';
@endphp

<div x-data="{ open: false }" @keydown.escape.window="open = false" class="relative inline-block">
    <div @click="open = !open" @click.away="open = false">{{ $trigger }}</div>
    <div x-show="open" x-transition x-cloak
         class="absolute z-40 mt-2 w-56 rounded-xl surface shadow-card border border-app py-1.5 {{ $alignCls }}">
        {{ $slot }}
    </div>
</div>
