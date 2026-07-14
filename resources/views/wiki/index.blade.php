@extends('layouts.app')
@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-app">Base de Conhecimento</h1>
            <p class="text-sm text-muted">Artigos e documentação interna.</p>
        </div>
        <x-ui.button href="{{ route('wiki.create') }}" icon="plus">Novo Artigo</x-ui.button>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <div class="lg:col-span-1">
            <x-ui.card>
                <h3 class="mb-3 font-semibold text-app">Categorias</h3>
                <ul class="space-y-1 text-sm">
                    <li><a href="{{ route('wiki.index') }}" class="text-primary-700 hover:underline dark:text-primary-300">Todas</a></li>
                    @foreach(App\Domains\Wiki\Controllers\ArtigoController::categorias() as $k => $l)
                        <li class="flex justify-between">
                            <a href="{{ route('wiki.index', ['category' => $k]) }}" class="text-app hover:text-primary-700 dark:hover:text-primary-300">{{ $l }}</a>
                            <span class="text-muted">{{ $porCategoria[$k] ?? 0 }}</span>
                        </li>
                    @endforeach
                </ul>
            </x-ui.card>
        </div>

        <div class="lg:col-span-3">
            <form method="GET" class="mb-4 flex gap-2">
                <x-ui.input type="text" name="q" :value="request('q')" placeholder="Buscar artigos..." class="flex-1" />
                <x-ui.button type="submit" icon="search">Buscar</x-ui.button>
            </form>

            <div class="space-y-3">
                @forelse($artigos as $a)
                    <x-ui.card class="flex items-center justify-between">
                        <div>
                            <a href="{{ route('wiki.show', $a) }}" class="font-medium text-primary-700 hover:underline dark:text-primary-300">{{ $a->title }}</a>
                            <div class="mt-1 text-xs text-muted">
                                {{ App\Domains\Wiki\Controllers\ArtigoController::categorias()[$a->category] ?? $a->category }}
                                &middot; {{ App\Domains\Wiki\Controllers\ArtigoController::status()[$a->status] ?? $a->status }}
                                &middot; {{ $a->author->name ?? '—' }}
                                @if($a->client_visible) &middot; <span class="text-green-600">Portal</span> @endif
                            </div>
                        </div>
                        <span class="text-xs text-muted">{{ $a->created_at->format('d/m/Y') }}</span>
                    </x-ui.card>
                @empty
                    <p class="text-sm text-muted">Nenhum artigo.</p>
                @endforelse
            </div>
            <div class="mt-4">{{ $artigos->links() }}</div>
        </div>
    </div>
@endsection
