@props(['icon' => 'inbox', 'title' => 'Nada por aqui', 'description' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center rounded-2xl border border-dashed border-neutral-300 dark:border-neutral-600 px-6 py-12 text-center']) }}>
    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-neutral-100 text-muted dark:bg-neutral-800">
        <i data-lucide="{{ $icon }}"></i>
    </div>
    <p class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">{{ $title }}</p>
    @if($description)<p class="mt-1 max-w-sm text-xs text-muted">{{ $description }}</p>@endif
    @if($slot->isNotEmpty())
        <div class="mt-4">{{ $slot }}</div>
    @endif
</div>
