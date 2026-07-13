@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'icon' => null,
    'block' => false,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 font-medium rounded-xl transition-all duration-200 focus-ring disabled:opacity-50 disabled:cursor-not-allowed select-none whitespace-nowrap';
    $sizes = [
        'sm' => 'text-xs px-3 py-1.5',
        'md' => 'text-sm px-4 py-2',
        'lg' => 'text-sm px-5 py-2.5',
    ];
    $variants = [
        'primary'  => 'bg-primary-600 text-white hover:bg-primary-700 active:bg-primary-800 shadow-soft',
        'secondary'=> 'bg-neutral-100 text-neutral-800 hover:bg-neutral-200 border border-neutral-200 dark:bg-neutral-800 dark:text-neutral-100 dark:border-neutral-700 dark:hover:bg-neutral-700',
        'outline'  => 'border border-neutral-300 text-neutral-700 hover:bg-neutral-100 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800',
        'ghost'    => 'text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800',
        'danger'   => 'bg-red-600 text-white hover:bg-red-700 active:bg-red-800 shadow-soft',
        'success'  => 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-soft',
    ];
    $cls = $base.' '.($sizes[$size] ?? $sizes['md']).' '.($variants[$variant] ?? $variants['primary']).($block ? ' w-full' : '');
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $cls]) }}>
        @if($icon)<i data-lucide="{{ $icon }}"></i>@endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $cls]) }}>
        @if($icon)<i data-lucide="{{ $icon }}"></i>@endif
        {{ $slot }}
    </button>
@endif
