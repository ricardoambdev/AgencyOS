@props(['name' => null, 'value' => null, 'checked' => false, 'disabled' => false, 'label' => null])

@php
    $id = $id ?? ('chk_'.($name ?? '').'_'.uniqid());
    $checked = $checked || old($name, $value) === '1' || old($name) === $value;
@endphp

<label for="{{ $id }}" class="inline-flex items-center gap-2 cursor-pointer select-none text-sm text-neutral-700 dark:text-neutral-200">
    <input type="checkbox" id="{{ $id }}" name="{{ $name }}" value="1"
        @checked($checked) @if($disabled) disabled @endif
        {{ $attributes->merge(['class' => 'h-4 w-4 rounded border-neutral-300 text-primary-600 focus-ring dark:border-neutral-600 dark:bg-neutral-900']) }}>
    @if($label)<span>{{ $label }}</span>@endif
</label>
