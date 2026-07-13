@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('clientes.index') }}" class="text-sm text-indigo-600">&larr; Clientes</a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $cliente->name }}</h1>
        </div>
        <div class="space-x-2">
            <a href="{{ route('clientes.edit', $cliente) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Editar</a>
            <button type="button" onclick="navigator.clipboard.writeText('{{ $cliente->portalUrl() }}')"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-emerald-700">Copiar link do portal</button>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Projetos</h3>
                @forelse($cliente->projetos as $p)
                    <a href="{{ route('projetos.show', $p) }}" class="block border-b py-2 text-indigo-600">{{ $p->name }} <span class="text-xs text-gray-500">- {{ $p->status }}</span></a>
                @empty
                    <p class="text-sm text-gray-400">Nenhum projeto.</p>
                @endforelse
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.comments', ['model' => $cliente])
                @include('partials.entity-activity', ['model' => $cliente])
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            @include('partials.timeline', ['model' => $cliente])
        </div>
    </div>
@endsection
