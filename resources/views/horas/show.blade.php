@extends('layouts.app')
@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
        <div>
            <a href="{{ route('horas.index') }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">&larr; Controle de Horas</a>
            <h1 class="text-2xl font-bold tracking-tight text-app">Lançamento de {{ $lancamento->date->format('d/m/Y') }}</h1>
            <p class="text-sm text-muted">{{ $lancamento->user->name ?? '' }} &middot; {{ $lancamento->project->name ?? 'sem projeto' }} &middot; {{ number_format($lancamento->hours, 2, ',', '.') }}h</p>
        </div>
        <x-ui.button href="{{ route('horas.edit', $lancamento) }}" icon="pencil">Editar</x-ui.button>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            @if($lancamento->description)
            <x-ui.card>
                <h3 class="mb-3 font-semibold text-app">Descrição</h3>
                <p class="whitespace-pre-line text-sm text-muted">{{ $lancamento->description }}</p>
            </x-ui.card>
            @endif
            <x-ui.card>
                @include('partials.comments', ['model' => $lancamento])
            </x-ui.card>
        </div>
        <div class="space-y-6">
            <x-ui.card>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-muted">Colaborador</dt><dd class="text-app">{{ $lancamento->user->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Projeto</dt><dd class="text-app">{{ $lancamento->project->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Tarefa</dt><dd class="text-app">{{ $lancamento->task->title ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Horas</dt><dd class="text-app">{{ number_format($lancamento->hours, 2, ',', '.') }}h</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Faturável</dt><dd class="text-app">{{ $lancamento->billable ? 'Sim' : 'Não' }}</dd></div>
                </dl>
            </x-ui.card>
            <x-ui.card>
                @include('partials.timeline', ['model' => $lancamento])
            </x-ui.card>
        </div>
    </div>
@endsection
