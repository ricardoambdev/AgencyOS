@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('wiki.index') }}" class="text-sm text-indigo-600">&larr; Base de Conhecimento</a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $artigo->title }}</h1>
            <p class="text-sm text-gray-500">
                {{ App\Domains\Wiki\Controllers\ArtigoController::categorias()[$artigo->category] ?? $artigo->category }}
                &middot; {{ App\Domains\Wiki\Controllers\ArtigoController::status()[$artigo->status] ?? $artigo->status }}
                &middot; {{ $artigo->author->name ?? '—' }}
            </p>
        </div>
        <a href="{{ route('wiki.edit', $artigo) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Editar</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="prose max-w-none text-sm text-gray-700 whitespace-pre-line">{{ $artigo->body }}</div>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.comments', ['model' => $artigo])
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Categoria</dt><dd>{{ App\Domains\Wiki\Controllers\ArtigoController::categorias()[$artigo->category] ?? $artigo->category }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd>{{ App\Domains\Wiki\Controllers\ArtigoController::status()[$artigo->status] ?? $artigo->status }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Portal</dt><dd>{{ $artigo->client_visible ? 'Visível' : 'Oculto' }}</dd></div>
                </dl>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.timeline', ['model' => $artigo])
            </div>
        </div>
    </div>
@endsection
