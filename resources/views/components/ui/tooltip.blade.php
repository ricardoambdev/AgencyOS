@props(['text' => null, 'position' => 'top'])

@php
    $pos = match($position) {
        'bottom' => 'top-full mt-1', 'left' => 'right-full mr-1 top-1/2 -translate-y-1/2', 'right' => 'left-full ml-1 top-1/2 -translate-y-1/2', default => 'bottom-full mb-1'
    };
@endphp

<span x-data="{ show: false }" @mouseenter="show = true" @mouseleave="show = false" class="relative inline-flex">
    {{ $slot }}
    <span x-show="show" x-cloak x-transition
          class="pointer-events-none absolute {{ $pos }} z-50 whitespace-nowrap rounded-lg bg-neutral-900 px-2.5 py-1 text-xs font-medium text-white shadow-card dark:bg-neutral-700">
        {{ $text ?? $slot }}
    </span>
</span>
