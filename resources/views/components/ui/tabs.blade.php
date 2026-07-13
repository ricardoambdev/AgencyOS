@props(['active' => null])

<div x-data="{ tab: '{{ $active ?? '' }}' }" {{ $attributes }}>{{ $slot }}</div>
