@props(['variant' => 'info', 'title' => null, 'icon' => null])

@php
    $variants = [
        'info'    => 'bg-sky-50 border-sky-200 text-sky-800 dark:bg-sky-900/30 dark:border-sky-800 dark:text-sky-200',
        'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-200',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-900/30 dark:border-amber-800 dark:text-amber-200',
        'danger'  => 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/30 dark:border-red-800 dark:text-red-200',
        'primary' => 'bg-primary-50 border-primary-200 text-primary-800 dark:bg-primary-900/30 dark:border-primary-800 dark:text-primary-200',
    ];
    $iconDefault = ['info' => 'info', 'success' => 'check-circle', 'warning' => 'alert-triangle', 'danger' => 'alert-octagon', 'primary' => 'sparkles'][$variant] ?? 'info';
@endphp

<div {{ $attributes->merge(['class' => 'flex gap-3 rounded-xl border px-4 py-3 text-sm ' . ($variants[$variant] ?? $variants['info'])]) }}>
    <i data-lucide="{{ $icon ?? $iconDefault }}" class="mt-0.5 shrink-0"></i>
    <div class="space-y-0.5">
        @if($title)<p class="font-semibold">{{ $title }}</p>@endif
        <div class="opacity-90">{{ $slot }}</div>
    </div>
</div>
