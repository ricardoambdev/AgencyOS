@props(['title' => null, 'size' => 'md'])

@php
    $sizeCls = match($size) {
        'lg' => 'max-w-2xl', 'xl' => 'max-w-4xl', 'sm' => 'max-w-sm', default => 'max-w-lg'
    };
@endphp

<div x-data="{ open: false }" {{ $attributes }}>
    @if(isset($trigger))
        <div @click="open = true" class="contents cursor-pointer">{{ $trigger }}</div>
    @endif

    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="open" x-transition.opacity @click="open = false"
             class="absolute inset-0 bg-neutral-900/50 backdrop-blur-sm"></div>

        <div x-show="open" x-transition
             class="relative w-full {{ $sizeCls }} rounded-2xl surface shadow-card ao-scale max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-app px-5 py-4">
                <h3 class="text-base font-semibold text-neutral-800 dark:text-neutral-100">
                    @if($title){{ $title }}@else{{ $slot->isEmpty() ? '' : '' }}@endif
                </h3>
                <button type="button" @click="open = false" class="rounded-lg p-1.5 text-muted hover:bg-neutral-100 dark:hover:bg-neutral-800">
                    <i data-lucide="x"></i>
                </button>
            </div>

            <div class="p-5">{{ $slot }}</div>

            @if(isset($footer))
                <div class="flex justify-end gap-2 border-t border-app px-5 py-4 bg-app">{{ $footer }}</div>
            @endif
        </div>
    </div>
</div>
