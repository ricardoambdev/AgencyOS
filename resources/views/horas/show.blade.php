@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('horas.index') }}" class="text-sm text-indigo-600">&larr; Controle de Horas</a>
            <h1 class="text-2xl font-bold text-gray-800">Lançamento de {{ $lancamento->date->format('d/m/Y') }}</h1>
            <p class="text-sm text-gray-500">{{ $lancamento->user->name ?? '' }} &middot; {{ $lancamento->project->name ?? 'sem projeto' }} &middot; {{ number_format($lancamento->hours, 2, ',', '.') }}h</p>
        </div>
        <a href="{{ route('horas.edit', $lancamento) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Editar</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            @if($lancamento->description)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Descrição</h3>
                <p class="text-sm text-gray-600 whitespace-pre-line">{{ $lancamento->description }}</p>
            </div>
            @endif
            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.comments', ['model' => $lancamento])
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Colaborador</dt><dd>{{ $lancamento->user->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Projeto</dt><dd>{{ $lancamento->project->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Tarefa</dt><dd>{{ $lancamento->task->title ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Horas</dt><dd>{{ number_format($lancamento->hours, 2, ',', '.') }}h</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Faturável</dt><dd>{{ $lancamento->billable ? 'Sim' : 'Não' }}</dd></div>
                </dl>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.timeline', ['model' => $lancamento])
            </div>
        </div>
    </div>
@endsection
