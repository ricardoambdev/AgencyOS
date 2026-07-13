@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600">&larr; Painel</a>
            <h1 class="text-2xl font-bold text-gray-800">Workflows (Máquina de Estados)</h1>
            <p class="text-sm text-gray-500">Defina os status e as transições permitidas por tipo de entidade.</p>
        </div>
        <a href="{{ route('config.workflows.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Novo Workflow</a>
    </div>

    <div class="bg-white shadow rounded-lg divide-y">
        @forelse($workflows as $wf)
            <div class="p-4 flex items-center justify-between">
                <div>
                    <div class="font-semibold text-gray-800">{{ $wf->name }}</div>
                    <div class="text-xs text-gray-500">
                        {{ class_basename($wf->entity_type) }}
                        · {{ count($wf->definition['states'] ?? []) }} status
                        · {{ count($wf->definition['transitions'] ?? []) }} transições
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $wf->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $wf->active ? 'Ativo' : 'Inativo' }}
                    </span>
                    <a href="{{ route('config.workflows.edit', $wf) }}" class="text-sm text-indigo-600">Editar</a>
                    <form method="POST" action="{{ route('config.workflows.destroy', $wf) }}" onsubmit="return confirm('Remover?')">
                        @csrf @method('DELETE')
                        <button class="text-sm text-red-600">Excluir</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="p-6 text-sm text-gray-400">Nenhum workflow cadastrado.</p>
        @endforelse
    </div>
@endsection
