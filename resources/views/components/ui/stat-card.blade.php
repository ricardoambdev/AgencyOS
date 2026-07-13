@props([
    'label' => null,
    'value' => null,
    'delta' => null,
    'trend' => null, // 'up' | 'down' | null
    'icon' => null,
    'href' => null,
])

@php
    $trendColor = $trend === 'up' ? 'text-emerald-600 dark:text-emerald-400' : ($trend === 'down' ? 'text-red-600 dark:text-red-400' : 'text-muted');
    $iconBg = 'bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300';
@endphp

<a href="{{ $href ?? '#' }}" {{ $attributes->merge(['class' => 'surface group block rounded-2xl p-5 shadow-soft transition-all duration-200 hover:shadow-card hover:-translate-y-0.5']) }}>
    <div class="flex items-start justify-between">
        <div>
            @if($label)<p class="text-xs font-medium uppercase tracking-wide text-muted">{{ $label }}</p>@endif
            @if($value !== null)<p class="mt-1 text-2xl font-bold text-neutral-900 dark:text-neutral-50">{{ $value }}</p>@endif
        </div>
        @if($icon)
            <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $iconBg }}">
                <i data-lucide="{{ $icon }}"></i>
            </span>
        @endif
    </div>
    @if($delta)
        <div class="mt-3 flex items-center gap-1 text-xs font-medium {{ $trendColor }}">
            @if($trend === 'up')<i data-lucide="trending-up" class="sm"></i>@elseif($trend === 'down')<i data-lucide="trending-down" class="sm"></i>@endif
            <span>{{ $delta }}</span>
        </div>
    @endif
</a>
