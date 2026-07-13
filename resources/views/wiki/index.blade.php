@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Base de Conhecimento</h1>
        <a href="{{ route('wiki.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Novo Artigo</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="font-semibold text-gray-700 mb-3">Categorias</h3>
                <ul class="space-y-1 text-sm">
                    <li><a href="{{ route('wiki.index') }}" class="text-indigo-600">Todas</a></li>
                    @foreach(App\Domains\Wiki\Controllers\ArtigoController::categorias() as $k => $l)
                        <li class="flex justify-between">
                            <a href="{{ route('wiki.index', ['category' => $k]) }}" class="text-gray-700 hover:text-indigo-600">{{ $l }}</a>
                            <span class="text-gray-400">{{ $porCategoria[$k] ?? 0 }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="lg:col-span-3">
            <form method="GET" class="mb-4 flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar artigos..." class="flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm">
                <button class="bg-gray-700 text-white px-3 py-2 rounded-md text-sm">Buscar</button>
            </form>

            <div class="space-y-3">
                @forelse($artigos as $a)
                <div class="bg-white shadow rounded-lg p-4 flex justify-between items-center">
                    <div>
                        <a href="{{ route('wiki.show', $a) }}" class="font-medium text-indigo-600">{{ $a->title }}</a>
                        <div class="text-xs text-gray-400 mt-1">
                            {{ App\Domains\Wiki\Controllers\ArtigoController::categorias()[$a->category] ?? $a->category }}
                            &middot; {{ App\Domains\Wiki\Controllers\ArtigoController::status()[$a->status] ?? $a->status }}
                            &middot; {{ $a->author->name ?? '—' }}
                            @if($a->client_visible) &middot; <span class="text-green-600">Portal</span> @endif
                        </div>
                    </div>
                    <span class="text-xs text-gray-400">{{ $a->created_at->format('d/m/Y') }}</span>
                </div>
                @empty
                    <p class="text-gray-400 text-sm">Nenhum artigo.</p>
                @endforelse
            </div>
            <div class="mt-4">{{ $artigos->links() }}</div>
        </div>
    </div>
@endsection
