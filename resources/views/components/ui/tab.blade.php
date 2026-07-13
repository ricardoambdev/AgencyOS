@props(['name' => null])

<button type="button" @click="tab = '{{ $name }}'"
    :class="tab === '{{ $name }}' ? 'border-primary-600 text-primary-700 dark:text-primary-300' : 'border-transparent text-muted hover:text-neutral-700 dark:hover:text-neutral-200'"
    class="whitespace-nowrap border-b-2 px-4 py-2.5 text-sm font-medium transition-colors"
    {{ $attributes }}>{{ $slot }}</button>
