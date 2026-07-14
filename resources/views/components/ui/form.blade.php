@props([
    'action' => '',
    'method' => 'POST',
    'confirm' => null,
])

@php
    $verb = strtoupper($method);
    $spoof = in_array($verb, ['PUT', 'PATCH', 'DELETE']) ? $verb : null;
@endphp

<form
    {{ $attributes->merge([
        'action' => $action,
        'method' => $spoof ? 'POST' : $verb,
        'class' => '',
    ]) }}
    @if($confirm) x-data x-on:submit.prevent="(confirm('{{ addslashes($confirm) }}') ? $el.submit() : null)" @endif
>
    @csrf
    @if($spoof) @method($spoof) @endif
    {{ $slot }}
</form>
