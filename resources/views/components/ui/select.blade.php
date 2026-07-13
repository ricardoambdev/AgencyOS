@props([
    'name' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'disabled' => false,
    'required' => false,
    'multiple' => false,
    'error' => null,
])

@php
    $cls = 'w-full rounded-xl border bg-white px-3 py-2 text-sm text-neutral-800 transition-colors focus-ring disabled:opacity-60 disabled:bg-neutral-100 '
        . 'dark:bg-neutral-900 dark:text-neutral-100 '
        . ($error ? 'border-red-400 dark:border-red-500' : 'border-neutral-300 dark:border-neutral-600');
@endphp

<select name="{{ $name }}" @if($multiple) multiple @endif
    @if($disabled) disabled @endif @if($required) required @endif
    {{ $attributes->merge(['class' => $cls]) }}>
    @if($placeholder)<option value="">{{ $placeholder }}</option>@endif
    @foreach($options as $value => $label)
        <option value="{{ $value }}" @selected((is_array($selected) ? in_array($value, $selected) : (string) old($name, $selected) === (string) $value))>{{ $label }}</option>
    @endforeach
    {{ $slot }}
</select>
