@extends('portal.layout')

@section('title', $projeto->name)

@section('content')
    <a href="{{ route('portal.dashboard', $token) }}" class="text-sm text-primary-600 hover:underline">&larr; Meus projetos</a>

    <div class="flex items-start justify-between mt-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold">{{ $projeto->name }}</h1>
            <p class="text-neutral-500 dark:text-neutral-400">{{ $projeto->status_label }} · Início {{ $projeto->start_date?->format('d/m/Y') }}</p>
        </div>
        <form method="POST" action="{{ route('portal.approve', $token) }}">
            @csrf
            <input type="hidden" name="entity_type" value="App\Domains\Projeto\Models\Projeto">
            <input type="hidden" name="entity_id" value="{{ $projeto->ulid }}">
            <button class="bg-primary-600 hover:bg-primary-700 text-white text-sm px-4 py-2 rounded-lg">Aprovar projeto</button>
        </form>
    </div>

    @if($projeto->description)
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 mb-6 prose dark:prose-invert max-w-none">
            {!! nl2br(e($projeto->description)) !!}
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-6">
        <section>
            <h2 class="font-semibold mb-3">Tarefas</h2>
            <div class="space-y-3">
                @forelse($projeto->tasks as $task)
                    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
                        <div class="flex items-center justify-between">
                            <span class="font-medium">{{ $task->title }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-300">{{ $task->status_label }}</span>
                        </div>
                        @if($task->due_date)
                            <p class="text-xs text-neutral-400 mt-1">Entrega: {{ $task->due_date->format('d/m/Y') }}</p>
                        @endif
                        @if($task->status !== 'done')
                            <form method="POST" action="{{ route('portal.approve', $token) }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="entity_type" value="App\Domains\Projeto\Models\Task">
                                <input type="hidden" name="entity_id" value="{{ $task->ulid }}">
                                <button class="text-sm border border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white px-3 py-1 rounded-lg">Aprovar</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-neutral-400">Sem tarefas.</p>
                @endforelse
            </div>

            @if($projeto->attachments->count())
                <h2 class="font-semibold mt-6 mb-3">Arquivos</h2>
                <ul class="space-y-2">
                    @foreach($projeto->attachments as $att)
                        <li>
                            <a href="{{ route('portal.download', ['token' => $token, 'attachment' => $att->id]) }}"
                               class="text-sm text-primary-600 hover:underline">⬇ {{ $att->name }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <section>
            <h2 class="font-semibold mb-3">Comentários</h2>

            <form method="POST" action="{{ route('portal.comment', $token) }}" class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 mb-4">
                @csrf
                <input type="hidden" name="entity_type" value="App\Domains\Projeto\Models\Projeto">
                <input type="hidden" name="entity_id" value="{{ $projeto->ulid }}">
                <textarea name="body" rows="3" required placeholder="Escreva um comentário para o time..."
                          class="w-full rounded-lg border border-neutral-300 dark:border-neutral-600 bg-transparent p-2 text-sm"></textarea>
                <button class="mt-2 bg-primary-600 hover:bg-primary-700 text-white text-sm px-4 py-2 rounded-lg">Enviar</button>
            </form>

            <div class="space-y-3">
                @forelse($projeto->comments as $c)
                    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
                        <p class="text-sm">{{ $c->body }}</p>
                        <p class="text-xs text-neutral-400 mt-1">{{ $c->created_at->format('d/m/Y H:i') }} · {{ $c->visibility === 'client' ? 'Você' : 'Equipe' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-neutral-400">Nenhum comentário ainda.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
