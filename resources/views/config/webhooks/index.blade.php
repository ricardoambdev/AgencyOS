@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('dashboard') }}" class="text-sm text-primary-700 dark:text-primary-300">&larr; Painel</a>
            <h1 class="text-2xl font-bold text-[var(--text)]">Webhooks de saída</h1>
            <p class="text-sm text-[var(--text-muted)]">Envie eventos do AgencyOS para URLs externas (Zapier, Make, Slack, sistemas próprios).</p>
        </div>
        <a href="{{ route('config.webhooks.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary-700">Novo Webhook</a>
    </div>

    <div class="bg-[var(--surface)] shadow rounded-lg divide-y">
        @forelse($webhooks as $hook)
            <div class="p-4 flex items-center justify-between">
                <div class="min-w-0">
                    <div class="font-semibold text-[var(--text)]">{{ $hook->name }}</div>
                    <div class="text-xs text-[var(--text-muted)] truncate max-w-md">{{ $hook->url }}</div>
                    <div class="text-xs text-[var(--text-muted)] mt-1">
                        Eventos: {{ implode(', ', $hook->events ?: ['—']) }}
                        @if($hook->secret) · assinado @endif
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $hook->active ? 'bg-[var(--surface-2)] text-primary-700 dark:text-primary-300' : 'bg-[var(--surface-2)] text-[var(--text-muted)]' }}">
                        {{ $hook->active ? 'Ativo' : 'Inativo' }}
                    </span>
                    <a href="{{ route('config.webhooks.edit', $hook) }}" class="text-sm text-primary-700 dark:text-primary-300">Editar</a>
                    <form method="POST" action="{{ route('config.webhooks.destroy', $hook) }}" onsubmit="return confirm('Remover?')">
                        @csrf @method('DELETE')
                        <button class="text-sm text-red-600">Excluir</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="p-6 text-sm text-[var(--text-muted)]">Nenhum webhook cadastrado.</p>
        @endforelse
    </div>
@endsection
