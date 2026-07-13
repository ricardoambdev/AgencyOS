@props(['title' => null, 'id' => null])

@php $id = $id ?? md5($title ?? uniqid()); @endphp

<div class="border-b border-app last:border-0">
    <button type="button" @click="open = open === '{{ $id }}' ? null : '{{ $id }}'"
        class="flex w-full items-center justify-between gap-3 px-5 py-4 text-left text-sm font-medium text-neutral-800 dark:text-neutral-100 hover:bg-neutral-50 dark:hover:bg-neutral-800/60">
        <span>{{ $title }}</span>
        <i data-lucide="chevron-down"
           :class="open === '{{ $id }}' ? 'rotate-180' : ''"
           class="text-muted transition-transform"></i>
    </button>
    <div x-show="open === '{{ $id }}'" x-collapse x-cloak class="px-5 pb-4 text-sm text-muted">
        {{ $slot }}
    </div>
</div>
