@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('equipamentos.index') }}" class="text-sm text-indigo-600">&larr; Equipamentos</a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $equipamento->name }}</h1>
            <p class="text-sm text-gray-500">{{ App\Domains\Equipamento\Controllers\EquipamentoController::tipos()[$equipamento->type] ?? $equipamento->type }} &middot; {{ App\Domains\Equipamento\Controllers\EquipamentoController::status()[$equipamento->status] ?? $equipamento->status }}</p>
        </div>
        <a href="{{ route('equipamentos.edit', $equipamento) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Editar</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            @if($equipamento->description)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Descrição</h3>
                <p class="text-sm text-gray-600 whitespace-pre-line">{{ $equipamento->description }}</p>
            </div>
            @endif
            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.comments', ['model' => $equipamento])
                @include('partials.entity-activity', ['model' => $equipamento])
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd>{{ App\Domains\Equipamento\Controllers\EquipamentoController::status()[$equipamento->status] ?? $equipamento->status }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Responsável</dt><dd>{{ $equipamento->owner->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Nº série</dt><dd>{{ $equipamento->serial ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Compra</dt><dd>{{ $equipamento->purchase_date?->format('d/m/Y') ?? '-' }}</dd></div>
                </dl>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.timeline', ['model' => $equipamento])
            </div>
        </div>
    </div>
@endsection
