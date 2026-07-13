@extends('layouts.app')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-gray-800">{{ $item ? 'Editar item' : 'Novo item de menu' }}</h1>

    <form method="POST" action="{{ $item ? route('config.menu.update', $item) : route('config.menu.store') }}" class="max-w-lg space-y-4 rounded-lg bg-white p-6 shadow">
        @csrf
        @if($item) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-gray-700">Rótulo</label>
            <input type="text" name="label" value="{{ old('label', $item->label ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Rota (nome da rota)</label>
            <input type="text" name="route" value="{{ old('route', $item->route ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 font-mono" placeholder="ex: leads.index">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Padrão de ativo (routeIs)</label>
            <input type="text" name="match" value="{{ old('match', $item->match ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 font-mono" placeholder="ex: leads.*">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">URL externa (opcional)</label>
            <input type="text" name="url" value="{{ old('url', $item->url ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" placeholder="https://...">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Ordem</label>
            <input type="number" name="order" value="{{ old('order', $item->order ?? 0) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
        </div>

        <div class="flex gap-2">
            <button class="rounded-md bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">Salvar</button>
            <a href="{{ route('config.menu.index') }}" class="px-4 py-2 text-sm text-gray-500">Cancelar</a>
        </div>
    </form>
@endsection
