@extends('layouts.app')
@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
        <div>
            <a href="{{ route('clientes.index') }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">&larr; Clientes</a>
            <h1 class="text-2xl font-bold tracking-tight text-app">{{ $cliente->name }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <x-ui.button href="{{ route('clientes.edit', $cliente) }}" icon="pencil">Editar</x-ui.button>
            <x-ui.button variant="success" icon="link" onclick="navigator.clipboard.writeText('{{ $cliente->portalUrl() }}')">Copiar link do portal</x-ui.button>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-ui.card>
                <h3 class="mb-3 font-semibold text-app">Projetos</h3>
                @forelse($cliente->projetos as $p)
                    <a href="{{ route('projetos.show', $p) }}" class="block border-b border-app py-2 text-primary-700 hover:underline dark:text-primary-300">{{ $p->name }} <span class="text-xs text-muted">- {{ $p->status }}</span></a>
                @empty
                    <p class="text-sm text-muted">Nenhum projeto.</p>
                @endforelse
            </x-ui.card>
            <x-ui.card>
                @include('partials.comments', ['model' => $cliente])
                @include('partials.entity-activity', ['model' => $cliente])
            </x-ui.card>
        </div>
        <x-ui.card>
            @include('partials.timeline', ['model' => $cliente])
        </x-ui.card>
    </div>
@endsection
