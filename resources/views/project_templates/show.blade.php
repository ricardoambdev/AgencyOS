@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('templates.index') }}" class="text-sm text-primary-700 dark:text-primary-300">&larr; Templates</a>
            <h1 class="text-2xl font-bold text-[var(--text)]">{{ $template->name }}</h1>
        </div>
        <div class="flex gap-2">
            <x-ui.button href="{{ route('templates.apply', $template) }}" variant="success" icon="play">
                Aplicar
            </x-ui.button>
            <x-ui.button href="{{ route('templates.edit', $template) }}" icon="pencil">
                Editar
            </x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            @if($template->description)
                <x-ui.card>
                    <h3 class="font-semibold text-[var(--text)] mb-3">Descrição</h3>
                    <p class="text-sm text-[var(--text-muted)] whitespace-pre-line">{{ $template->description }}</p>
                </x-ui.card>
            @endif

            <x-ui.card>
                <h3 class="font-semibold text-[var(--text)] mb-3">Tarefas ({{ $template->templateTasks->count() }})</h3>
                <ul class="divide-y divide-[var(--border)]">
                    @forelse($template->templateTasks as $task)
                        <li class="py-2 flex justify-between text-sm">
                            <span class="text-[var(--text)]">{{ $task->title }} <span class="text-[var(--text-muted)]">— {{ $task->priority ?? 'sem prioridade' }}</span></span>
                            <span class="text-[var(--text-muted)]">{{ number_format($task->estimated_hours, 2, ',', '.') }}h</span>
                        </li>
                    @empty
                        <li class="text-[var(--text-muted)]">Nenhuma tarefa definida.</li>
                    @endforelse
                </ul>
            </x-ui.card>

            <x-ui.card>
                <h3 class="font-semibold text-[var(--text)] mb-3">Checklist ({{ count($template->checklist ?? []) }})</h3>
                <ul class="divide-y divide-[var(--border)]">
                    @forelse(($template->checklist ?? []) as $item)
                        <li class="py-2 text-sm text-[var(--text)]">{{ $item }}</li>
                    @empty
                        <li class="text-[var(--text-muted)]">Nenhum item de checklist.</li>
                    @endforelse
                </ul>
            </x-ui.card>
        </div>

        <div class="space-y-6">
            <x-ui.card>
                @include('partials.timeline', ['model' => $template])
            </x-ui.card>
        </div>
    </div>
@endsection
