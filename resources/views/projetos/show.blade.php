@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('projetos.index') }}" class="text-sm text-indigo-600">&larr; Projetos</a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $projeto->name }}</h1>
            <p class="text-sm text-gray-500">{{ $projeto->client->name ?? '' }} &middot; {{ $projeto->status }}</p>
        </div>
        <a href="{{ route('projetos.edit', $projeto) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Editar</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Tarefas</h3>
                <form method="POST" action="{{ route('projetos.tasks.store', $projeto) }}" class="mb-4 flex gap-2">
                    @csrf
                    <input type="text" name="title" placeholder="Nova tarefa..." class="flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm" required>
                    <select name="assignee_id" class="rounded-md border border-gray-300 px-2 py-1 text-sm">
                        <option value="">Responsável</option>
                        @foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach
                    </select>
                    <button class="bg-indigo-600 text-white px-3 py-2 rounded-md text-sm">Add</button>
                </form>
                <div class="space-y-2">
                    @forelse($tasks as $task)
                    <div class="flex items-center justify-between border rounded-md px-3 py-2">
                        <div>
                            <span class="text-sm font-medium">{{ $task->title }}</span>
                            <span class="text-xs text-gray-500"> - {{ $task->assignee->name ?? 'sem responsável' }}</span>
                        </div>
                        <form method="POST" action="{{ route('tasks.update', $task) }}">@csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()" class="text-xs rounded border border-gray-300 px-2 py-1">
                                @foreach(['todo'=>'A fazer','doing'=>'Em andamento','done'=>'Concluída'] as $v=>$l)
                                    <option value="{{ $v }}" {{ $task->status == $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    @empty
                        <p class="text-sm text-gray-400">Nenhuma tarefa.</p>
                    @endforelse
                </div>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.comments', ['model' => $projeto])
                @include('partials.entity-activity', ['model' => $projeto])
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Responsável</dt><dd>{{ $projeto->owner->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Orçamento</dt><dd>R$ {{ number_format($projeto->budget, 2, ',', '.') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Início</dt><dd>{{ $projeto->start_date?->format('d/m/Y') ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Término</dt><dd>{{ $projeto->end_date?->format('d/m/Y') ?? '-' }}</dd></div>
                </dl>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.timeline', ['model' => $projeto])
            </div>
            @include('partials.custom-fields-values', ['model' => $projeto])
        </div>
    </div>
@endsection
