@props(['variant' => 'primary', 'dot' => false, 'icon' => null, 'outline' => false])

@php
    $variants = [
        'primary' => 'bg-primary-100 text-primary-800 dark:bg-primary-900/40 dark:text-primary-200',
        'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
        'warning' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
        'danger'  => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
        'info'    => 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300',
        'neutral' => 'bg-neutral-100 text-neutral-600 dark:bg-neutral-700 dark:text-neutral-200',
    ];
    $dotColors = [
        'primary' => 'bg-primary-500', 'success' => 'bg-emerald-500', 'warning' => 'bg-amber-500',
        'danger' => 'bg-red-500', 'info' => 'bg-sky-500', 'neutral' => 'bg-neutral-400'
    ];
    $cls = 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<span {{ $attributes->merge(['class' => $cls]) }}>
    @if($dot)<span class="h-1.5 w-1.5 rounded-full {{ $dotColors[$variant] ?? 'bg-primary-500' }}"></span>@endif
    @if($icon)<i data-lucide="{{ $icon }}" class="sm"></i>@endif
    {{ $slot }}
</span>
