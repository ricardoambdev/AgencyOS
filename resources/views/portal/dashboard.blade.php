@extends('portal.layout')

@section('title', 'Meus Projetos')

@section('content')
    <h1 class="text-2xl font-bold mb-1">Olá, {{ $cliente->name }} 👋</h1>
    <p class="text-neutral-500 dark:text-neutral-400 mb-8">Acompanhe o andamento dos seus projetos e aprove materiais.</p>

    <div class="space-y-4">
        @forelse($cliente->projetos as $projeto)
            @php
                $total = $projeto->tasks_count ?? 0;
                $done = $projeto->done_tasks_count ?? 0;
                $pct = $total ? round($done / $total * 100) : 0;
            @endphp
            <a href="{{ route('portal.project', ['token' => $token, 'projeto' => $projeto->ulid]) }}"
               class="block bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 hover:border-primary-600 transition">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2 class="font-semibold text-lg">{{ $projeto->name }}</h2>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $projeto->status_label }}</p>
                    </div>
                    <span class="text-sm text-neutral-400">{{ $done }}/{{ $total }} tarefas</span>
                </div>
                <div class="w-full h-2 bg-neutral-100 dark:bg-neutral-700 rounded-full overflow-hidden">
                    <div class="h-full bg-primary-600" style="width: {{ $pct }}%"></div>
                </div>
            </a>
        @empty
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-8 text-center text-neutral-500">
                Nenhum projeto ativo no momento.
            </div>
        @endforelse
    </div>
@endsection
