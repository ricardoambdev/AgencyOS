@props(['striped' => true, 'hover' => true])

<div class="surface rounded-2xl shadow-soft overflow-hidden">
    <div class="overflow-x-auto">
        <table {{ $attributes->merge(['class' => 'w-full text-sm text-left text-neutral-700 dark:text-neutral-200']) }}>
            {{ $slot }}
        </table>
    </div>
</div>
