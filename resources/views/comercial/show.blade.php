@extends('layouts.app')
@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
        <div>
            <a href="{{ route('comercial.index') }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">&larr; Comercial</a>
            <h1 class="text-2xl font-bold tracking-tight text-app">{{ $contrato->title }}</h1>
            <p class="text-sm text-muted">{{ $contrato->number }} &middot; {{ $contrato->status }}</p>
        </div>
        <x-ui.button href="{{ route('comercial.edit', $contrato) }}" icon="pencil">Editar</x-ui.button>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            @if($contrato->description)
            <x-ui.card>
                <h3 class="mb-3 font-semibold text-app">Descrição</h3>
                <p class="whitespace-pre-line text-sm text-muted">{{ $contrato->description }}</p>
            </x-ui.card>
            @endif

            @if($contrato->attachments->count())
            <x-ui.card>
                <h3 class="mb-3 font-semibold text-app">Documentos</h3>
                <ul class="space-y-1 text-sm">
                    @foreach($contrato->attachments as $a)
                        <li><a href="{{ route('comercial.attachments.download', [$contrato, $a]) }}" class="text-primary-700 hover:underline dark:text-primary-300">{{ $a->name }}</a></li>
                    @endforeach
                </ul>
            </x-ui.card>
            @endif

            <x-ui.card>
                @include('partials.comments', ['model' => $contrato])
            </x-ui.card>
        </div>
        <div class="space-y-6">
            <x-ui.card>
                <div class="mb-3 flex items-center gap-2">
                    @include('partials.favorite-button', ['model' => $contrato])
                    <span class="text-sm text-muted">Favoritar</span>
                </div>
                @include('partials.tags-display', ['model' => $contrato])
            </x-ui.card>
            <x-ui.card>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-muted">Cliente</dt><dd class="text-app">{{ $contrato->cliente->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Responsável</dt><dd class="text-app">{{ $contrato->responsavel->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Tipo</dt><dd class="text-app">{{ $contrato->type }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Valor</dt><dd class="text-app">{{ $contrato->currency }} {{ number_format($contrato->value, 2, ',', '.') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Início</dt><dd class="text-app">{{ $contrato->start_date?->format('d/m/Y') ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Fim</dt><dd class="text-app">{{ $contrato->end_date?->format('d/m/Y') ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Renovação</dt><dd class="text-app">{{ $contrato->renewal_type }} @if($contrato->renewal_date)/ {{ $contrato->renewal_date->format('d/m/Y') }}@endif</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Assinado em</dt><dd class="text-app">{{ $contrato->signed_at?->format('d/m/Y') ?? '-' }}</dd></div>
                </dl>
            </x-ui.card>
            <x-ui.card>
                @include('partials.timeline', ['model' => $contrato])
            </x-ui.card>
        </div>
    </div>
@endsection
