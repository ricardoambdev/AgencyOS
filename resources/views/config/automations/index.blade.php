@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('dashboard') }}" class="text-sm text-primary-700 dark:text-primary-300">&larr; Painel</a>
            <h1 class="text-2xl font-bold text-[var(--text)]">Automações (Regras)</h1>
            <p class="text-sm text-[var(--text-muted)]">Disparem ações quando eventos ocorrem (Lead criado, Projeto criado etc.).</p>
        </div>
        <a href="{{ route('config.automations.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary-700">Nova Automação</a>
    </div>

    <div class="bg-[var(--surface)] shadow rounded-lg divide-y">
        @forelse($automations as $a)
            <div class="p-4 flex items-center justify-between">
                <div>
                    <div class="font-semibold text-[var(--text)]">{{ $a->name }}</div>
                    <div class="text-xs text-[var(--text-muted)]">
                        Evento: {{ $a->event }}
                        · {{ count($a->conditions) }} condição(ões)
                        · {{ count($a->actions) }} ação(ões)
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $a->active ? 'bg-[var(--surface-2)] text-primary-700 dark:text-primary-300' : 'bg-[var(--surface-2)] text-[var(--text-muted)]' }}">
                        {{ $a->active ? 'Ativo' : 'Inativo' }}
                    </span>
                    <a href="{{ route('config.automations.edit', $a) }}" class="text-sm text-primary-700 dark:text-primary-300">Editar</a>
                    <form method="POST" action="{{ route('config.automations.destroy', $a) }}" onsubmit="return confirm('Remover?')">
                        @csrf @method('DELETE')
                        <button class="text-sm text-red-600">Excluir</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="p-6 text-sm text-[var(--text-muted)]">Nenhuma automação cadastrada.</p>
        @endforelse
    </div>
@endsection
