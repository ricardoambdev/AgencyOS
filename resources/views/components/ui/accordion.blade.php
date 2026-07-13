@props(['active' => null])

<div x-data="{ open: '{{ $active ?? '' }}' }" {{ $attributes }}>{{ $slot }}</div>
