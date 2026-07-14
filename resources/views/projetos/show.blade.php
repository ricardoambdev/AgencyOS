@extends('layouts.app')
@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
        <div>
            <a href="{{ route('projetos.index') }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">&larr; Projetos</a>
            <h1 class="text-2xl font-bold tracking-tight text-app">{{ $projeto->name }}</h1>
            <p class="text-sm text-muted">{{ $projeto->client->name ?? '' }} &middot; {{ $projeto->status }}</p>
        </div>
        <x-ui.button href="{{ route('projetos.edit', $projeto) }}" icon="pencil">Editar</x-ui.button>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-ui.card>
                <h3 class="mb-3 font-semibold text-app">Tarefas</h3>
                <form method="POST" action="{{ route('projetos.tasks.store', $projeto) }}" class="mb-4 flex flex-wrap gap-2">
                    @csrf
                    <input type="text" name="title" placeholder="Nova tarefa..." class="flex-1 rounded-lg border border-border bg-surface px-3 py-2 text-sm text-app" required>
                    <x-ui.select name="assignee_id" :options="['' => 'Responsável'] + $users->pluck('name', 'id')->toArray()" />
                    <x-ui.button type="submit" icon="plus">Add</x-ui.button>
                </form>
                <div class="space-y-2">
                    @forelse($tasks as $task)
                    <div class="flex items-center justify-between rounded-lg border border-border px-3 py-2">
                        <div>
                            <span class="text-sm font-medium text-app">{{ $task->title }}</span>
                            <span class="text-xs text-muted"> - {{ $task->assignee->name ?? 'sem responsável' }}</span>
                        </div>
                        <form method="POST" action="{{ route('tasks.update', $task) }}">@csrf @method('PATCH')
                            <x-ui.select name="status" :options="\App\Core\Models\WorkflowState::resolve(\App\Domains\Projeto\Models\Task::class)" :selected="$task->status" onchange="this.form.submit()" class="w-auto" />
                        </form>
                    </div>
                    @empty
                        <p class="text-sm text-muted">Nenhuma tarefa.</p>
                    @endforelse
                </div>
            </x-ui.card>
            <x-ui.card>
                @include('partials.comments', ['model' => $projeto])
                @include('partials.entity-activity', ['model' => $projeto])
            </x-ui.card>

            <x-ui.card>
                <h3 class="mb-3 font-semibold text-app">Checklist</h3>
                @if($checklist->isEmpty())
                    <p class="text-sm text-muted">Nenhum item de checklist.</p>
                @else
                    <ul class="space-y-1">
                        @foreach($checklist as $item)
                        <li class="flex items-center gap-2 text-sm">
                            <form method="POST" action="{{ route('projetos.checklist.toggle', $item) }}" class="inline">
                                @csrf
                                <button type="submit" class="mr-1 {{ $item->done ? 'text-green-600' : 'text-muted' }}" title="Alternar">
                                    {{ $item->done ? '☑' : '☐' }}
                                </button>
                            </form>
                            <span class="{{ $item->done ? 'text-muted line-through' : 'text-app' }}">{{ $item->label }}</span>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>
        </div>
        <div class="space-y-6">
            <x-ui.card>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-muted">Responsável</dt><dd class="text-app">{{ $projeto->owner->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Orçamento</dt><dd class="text-app">R$ {{ number_format($projeto->budget, 2, ',', '.') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Início</dt><dd class="text-app">{{ $projeto->start_date?->format('d/m/Y') ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Término</dt><dd class="text-app">{{ $projeto->end_date?->format('d/m/Y') ?? '-' }}</dd></div>
                </dl>
            </x-ui.card>
            <x-ui.card>
                @include('partials.timeline', ['model' => $projeto])
            </x-ui.card>
            @include('partials.custom-fields-values', ['model' => $projeto])
        </div>
    </div>
@endsection
