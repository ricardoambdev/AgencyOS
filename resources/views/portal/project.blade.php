@extends('portal.layout')

@section('title', $projeto->name)

@section('content')
    <a href="{{ route('portal.dashboard', $token) }}" class="text-sm text-brand-500 hover:underline">&larr; Meus projetos</a>

    <div class="flex items-start justify-between mt-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold">{{ $projeto->name }}</h1>
            <p class="text-slate-500 dark:text-slate-400">{{ $projeto->status_label }} · Início {{ $projeto->start_date?->format('d/m/Y') }}</p>
        </div>
        <form method="POST" action="{{ route('portal.approve', $token) }}">
            @csrf
            <input type="hidden" name="entity_type" value="App\Domains\Projeto\Models\Projeto">
            <input type="hidden" name="entity_id" value="{{ $projeto->ulid }}">
            <button class="bg-brand-500 hover:bg-brand-600 text-white text-sm px-4 py-2 rounded-lg">Aprovar projeto</button>
        </form>
    </div>

    @if($projeto->description)
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5 mb-6 prose dark:prose-invert max-w-none">
            {!! nl2br(e($projeto->description)) !!}
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-6">
        <section>
            <h2 class="font-semibold mb-3">Tarefas</h2>
            <div class="space-y-3">
                @forelse($projeto->tasks as $task)
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4">
                        <div class="flex items-center justify-between">
                            <span class="font-medium">{{ $task->title }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">{{ $task->status_label }}</span>
                        </div>
                        @if($task->due_date)
                            <p class="text-xs text-slate-400 mt-1">Entrega: {{ $task->due_date->format('d/m/Y') }}</p>
                        @endif
                        @if($task->status !== 'done')
                            <form method="POST" action="{{ route('portal.approve', $token) }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="entity_type" value="App\Domains\Projeto\Models\Task">
                                <input type="hidden" name="entity_id" value="{{ $task->ulid }}">
                                <button class="text-sm border border-brand-500 text-brand-500 hover:bg-brand-500 hover:text-white px-3 py-1 rounded-lg">Aprovar</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-400">Sem tarefas.</p>
                @endforelse
            </div>

            @if($projeto->attachments->count())
                <h2 class="font-semibold mt-6 mb-3">Arquivos</h2>
                <ul class="space-y-2">
                    @foreach($projeto->attachments as $att)
                        <li>
                            <a href="{{ route('portal.download', ['token' => $token, 'attachment' => $att->id]) }}"
                               class="text-sm text-brand-500 hover:underline">⬇ {{ $att->name }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <section>
            <h2 class="font-semibold mb-3">Comentários</h2>

            <form method="POST" action="{{ route('portal.comment', $token) }}" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 mb-4">
                @csrf
                <input type="hidden" name="entity_type" value="App\Domains\Projeto\Models\Projeto">
                <input type="hidden" name="entity_id" value="{{ $projeto->ulid }}">
                <textarea name="body" rows="3" required placeholder="Escreva um comentário para o time..."
                          class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-transparent p-2 text-sm"></textarea>
                <button class="mt-2 bg-brand-500 hover:bg-brand-600 text-white text-sm px-4 py-2 rounded-lg">Enviar</button>
            </form>

            <div class="space-y-3">
                @forelse($projeto->comments as $c)
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4">
                        <p class="text-sm">{{ $c->body }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $c->created_at->format('d/m/Y H:i') }} · {{ $c->visibility === 'client' ? 'Você' : 'Equipe' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">Nenhum comentário ainda.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
