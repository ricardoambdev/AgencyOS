@props([
    'title' => null,
    'subtitle' => null,
    'header' => null,
    'footer' => null,
    'padding' => true,
    'hover' => false,
])

@php
    $cls = 'surface rounded-2xl shadow-soft overflow-hidden '
        . ($hover ? 'transition-all duration-200 hover:shadow-card hover:-translate-y-0.5 ' : '')
        . ($attributes->get('class') ?? '');
@endphp

<div {{ $attributes->merge(['class' => $cls]) }}>
    @if($header || $title || $subtitle !== null || $slot->hasActualContent() === false)
        @if($title || $subtitle || $header)
            <div class="flex items-start justify-between gap-3 border-b border-app px-5 py-4">
                <div>
                    @if($title)<h3 class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">{{ $title }}</h3>@endif
                    @if($subtitle)<p class="text-xs text-muted mt-0.5">{{ $subtitle }}</p>@endif
                </div>
                @if($header)<div class="shrink-0">{{ $header }}</div>@endif
            </div>
        @endif
    @endif

    <div class="{{ $padding ? 'p-5' : '' }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="border-t border-app px-5 py-3 bg-app">{{ $footer }}</div>
    @endif
</div>
