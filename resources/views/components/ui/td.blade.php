@props(['align' => 'left'])

@php
    $alignCls = match($align) {
        'right' => 'text-right', 'center' => 'text-center', default => 'text-left'
    };
@endphp

<td {{ $attributes->merge(['class' => "px-4 py-3 align-middle $alignCls border-t border-app"]) }}>
    {{ $slot }}
</td>
