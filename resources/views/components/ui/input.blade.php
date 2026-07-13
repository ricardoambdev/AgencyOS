@props([
    'name' => null,
    'value' => null,
    'type' => 'text',
    'placeholder' => null,
    'disabled' => false,
    'required' => false,
    'error' => null,
])

@php
    $cls = 'w-full rounded-xl border bg-white px-3 py-2 text-sm text-neutral-800 placeholder-neutral-400 transition-colors focus-ring disabled:opacity-60 disabled:bg-neutral-100 '
        . 'dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500 '
        . ($error ? 'border-red-400 dark:border-red-500' : 'border-neutral-300 dark:border-neutral-600');
@endphp

<input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}"
    placeholder="{{ $placeholder }}" @if($disabled) disabled @endif @if($required) required @endif
    {{ $attributes->merge(['class' => $cls]) }}>
