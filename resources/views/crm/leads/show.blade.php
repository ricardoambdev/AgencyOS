@extends('layouts.app')
@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
        <div>
            <a href="{{ route('leads.index') }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">&larr; Leads</a>
            <h1 class="text-2xl font-bold tracking-tight text-app">{{ $lead->name }}</h1>
            <p class="text-sm text-muted">{{ $lead->company_name }} &middot; {{ $lead->email }}</p>
        </div>
        <div class="flex items-center gap-2">
            <x-ui.button href="{{ route('leads.edit', $lead) }}" icon="pencil">Editar</x-ui.button>
            <form method="POST" action="{{ route('leads.destroy', $lead) }}" class="inline" onsubmit="return confirm('Remover lead?')">
                @csrf @method('DELETE')
                <x-ui.button type="submit" variant="danger" icon="trash-2">Remover</x-ui.button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <x-ui.card>
                @include('partials.comments', ['model' => $lead])
                @include('partials.entity-activity', ['model' => $lead])
            </x-ui.card>
        </div>
        <div class="space-y-6">
            <x-ui.card>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-muted">Status</dt><dd class="font-medium text-app">{{ $lead->status }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Valor</dt><dd class="font-medium text-app">R$ {{ number_format($lead->value, 2, ',', '.') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Origem</dt><dd class="font-medium text-app">{{ $lead->source ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Responsável</dt><dd class="font-medium text-app">{{ $lead->owner->name ?? '-' }}</dd></div>
                </dl>
            </x-ui.card>
            <x-ui.card>
                @include('partials.timeline', ['model' => $lead])
            </x-ui.card>
        </div>
    </div>
@endsection
