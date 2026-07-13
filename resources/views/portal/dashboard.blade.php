@extends('portal.layout')

@section('title', 'Meus Projetos')

@section('content')
    <h1 class="text-2xl font-bold mb-1">Olá, {{ $cliente->name }} 👋</h1>
    <p class="text-slate-500 dark:text-slate-400 mb-8">Acompanhe o andamento dos seus projetos e aprove materiais.</p>

    <div class="space-y-4">
        @forelse($cliente->projetos as $projeto)
            @php
                $total = $projeto->tasks_count ?? 0;
                $done = $projeto->done_tasks_count ?? 0;
                $pct = $total ? round($done / $total * 100) : 0;
            @endphp
            <a href="{{ route('portal.project', ['token' => $token, 'projeto' => $projeto->ulid]) }}"
               class="block bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5 hover:border-brand-500 transition">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2 class="font-semibold text-lg">{{ $projeto->name }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $projeto->status_label }}</p>
                    </div>
                    <span class="text-sm text-slate-400">{{ $done }}/{{ $total }} tarefas</span>
                </div>
                <div class="w-full h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-brand-500" style="width: {{ $pct }}%"></div>
                </div>
            </a>
        @empty
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-8 text-center text-slate-500">
                Nenhum projeto ativo no momento.
            </div>
        @endforelse
    </div>
@endsection
