@props(['class' => ''])
<tr {{ $attributes->merge(['class' => 'hover:bg-surface-2 transition-colors ' . $class]) }}>
    {{ $slot }}
</tr>
