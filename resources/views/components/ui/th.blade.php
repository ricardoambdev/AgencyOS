@props(['sortable' => false, 'align' => 'left'])

@php
    $alignCls = match($align) {
        'right' => 'text-right', 'center' => 'text-center', default => 'text-left'
    };
@endphp

<th scope="col" {{ $attributes->merge(['class' => "px-4 py-3 text-xs font-semibold uppercase tracking-wide text-muted $alignCls whitespace-nowrap"]) }}>
    @if($sortable)
        <button class="inline-flex items-center gap-1 hover:text-neutral-800 dark:hover:text-neutral-100">
            {{ $slot }}
            <i data-lucide="chevrons-up-down" class="sm opacity-50"></i>
        </button>
    @else
        {{ $slot }}
    @endif
</th>
