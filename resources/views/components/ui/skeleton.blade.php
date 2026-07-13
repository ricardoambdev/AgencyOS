@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'animate-pulse rounded-md bg-neutral-200 dark:bg-neutral-700 ' . $class]) }}></div>
