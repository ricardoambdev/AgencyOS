@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600">&larr; Painel</a>
            <h1 class="text-2xl font-bold text-gray-800">Automações (Regras)</h1>
            <p class="text-sm text-gray-500">Disparem ações quando eventos ocorrem (Lead criado, Projeto criado etc.).</p>
        </div>
        <a href="{{ route('config.automations.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Nova Automação</a>
    </div>

    <div class="bg-white shadow rounded-lg divide-y">
        @forelse($automations as $a)
            <div class="p-4 flex items-center justify-between">
                <div>
                    <div class="font-semibold text-gray-800">{{ $a->name }}</div>
                    <div class="text-xs text-gray-500">
                        Evento: {{ $a->event }}
                        · {{ count($a->conditions) }} condição(ões)
                        · {{ count($a->actions) }} ação(ões)
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $a->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $a->active ? 'Ativo' : 'Inativo' }}
                    </span>
                    <a href="{{ route('config.automations.edit', $a) }}" class="text-sm text-indigo-600">Editar</a>
                    <form method="POST" action="{{ route('config.automations.destroy', $a) }}" onsubmit="return confirm('Remover?')">
                        @csrf @method('DELETE')
                        <button class="text-sm text-red-600">Excluir</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="p-6 text-sm text-gray-400">Nenhuma automação cadastrada.</p>
        @endforelse
    </div>
@endsection
