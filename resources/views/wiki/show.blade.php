@extends('layouts.app')
@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
        <div>
            <a href="{{ route('wiki.index') }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">&larr; Base de Conhecimento</a>
            <h1 class="text-2xl font-bold tracking-tight text-app">{{ $artigo->title }}</h1>
            <p class="text-sm text-muted">
                {{ App\Domains\Wiki\Controllers\ArtigoController::categorias()[$artigo->category] ?? $artigo->category }}
                &middot; {{ App\Domains\Wiki\Controllers\ArtigoController::status()[$artigo->status] ?? $artigo->status }}
                &middot; {{ $artigo->author->name ?? '—' }}
            </p>
        </div>
        <x-ui.button href="{{ route('wiki.edit', $artigo) }}" icon="pencil">Editar</x-ui.button>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-ui.card>
                <div class="prose max-w-none text-sm text-app whitespace-pre-line">{{ $artigo->body }}</div>
            </x-ui.card>
            <x-ui.card>
                @include('partials.comments', ['model' => $artigo])
            </x-ui.card>
        </div>
        <div class="space-y-6">
            <x-ui.card>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-muted">Categoria</dt><dd class="text-app">{{ App\Domains\Wiki\Controllers\ArtigoController::categorias()[$artigo->category] ?? $artigo->category }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Status</dt><dd class="text-app">{{ App\Domains\Wiki\Controllers\ArtigoController::status()[$artigo->status] ?? $artigo->status }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Portal</dt><dd class="text-app">{{ $artigo->client_visible ? 'Visível' : 'Oculto' }}</dd></div>
                </dl>
            </x-ui.card>
            <x-ui.card>
                @include('partials.timeline', ['model' => $artigo])
            </x-ui.card>
        </div>
    </div>
@endsection
