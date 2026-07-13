@props([
    'name' => null,
    'value' => null,
    'placeholder' => null,
    'disabled' => false,
    'required' => false,
    'rows' => 4,
    'error' => null,
])

@php
    $cls = 'w-full rounded-xl border bg-white px-3 py-2 text-sm text-neutral-800 placeholder-neutral-400 transition-colors focus-ring disabled:opacity-60 disabled:bg-neutral-100 '
        . 'dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500 '
        . ($error ? 'border-red-400 dark:border-red-500' : 'border-neutral-300 dark:border-neutral-600');
@endphp

<textarea name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
    @if($disabled) disabled @endif @if($required) required @endif
    {{ $attributes->merge(['class' => $cls]) }}>{{ old($name, $value) }}</textarea>
