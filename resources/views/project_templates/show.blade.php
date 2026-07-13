@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('templates.index') }}" class="text-sm text-indigo-600">&larr; Templates</a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $template->name }}</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('templates.apply', $template) }}" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700">Aplicar</a>
            <a href="{{ route('templates.edit', $template) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Editar</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            @if($template->description)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Descrição</h3>
                <p class="text-sm text-gray-600 whitespace-pre-line">{{ $template->description }}</p>
            </div>
            @endif
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Tarefas ({{ $template->templateTasks->count() }})</h3>
                <ul class="divide-y divide-gray-100">
                    @forelse($template->templateTasks as $task)
                    <li class="py-2 flex justify-between text-sm">
                        <span>{{ $task->title }} <span class="text-gray-400">— {{ $task->priority ?? 'sem prioridade' }}</span></span>
                        <span class="text-gray-500">{{ number_format($task->estimated_hours, 2, ',', '.') }}h</span>
                    </li>
                    @empty
                    <li class="text-gray-500">Nenhuma tarefa definida.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.timeline', ['model' => $template])
            </div>
        </div>
    </div>
@endsection
