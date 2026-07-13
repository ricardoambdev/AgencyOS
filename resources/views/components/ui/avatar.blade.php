@props(['name' => null, 'src' => null, 'size' => 'md', 'status' => false])

@php
    $sizeCls = match($size) {
        'xs' => 'h-6 w-6 text-[10px]', 'sm' => 'h-8 w-8 text-xs', 'lg' => 'h-12 w-12 text-base', 'xl' => 'h-16 w-16 text-lg', default => 'h-10 w-10 text-sm'
    };
    $initials = $name ? collect(explode(' ', $name))->take(2)->map(fn($w) => mb_substr($w, 0, 1))->join('') : '?';
@endphp

<span class="relative inline-flex shrink-0">
    @if($src)
        <img src="{{ $src }}" alt="{{ $name }}" class="rounded-full object-cover {{ $sizeCls }} ring-2 ring-white dark:ring-neutral-700">
    @else
        <span class="inline-flex items-center justify-center rounded-full bg-primary-100 font-semibold text-primary-800 dark:bg-primary-900/40 dark:text-primary-200 {{ $sizeCls }}">
            {{ strtoupper($initials) }}
        </span>
    @endif
    @if($status)
        <span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-neutral-700"></span>
    @endif
</span>
