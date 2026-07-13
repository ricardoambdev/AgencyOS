@props(['size' => 'md'])

@php
    $sizeCls = match($size) {
        'sm' => 'h-4 w-4', 'lg' => 'h-8 w-8', 'xl' => 'h-10 w-10', default => 'h-6 w-6'
    };
@endphp

<i data-lucide="loader-2" class="{{ $sizeCls }} ao-spin text-primary-500"></i>
