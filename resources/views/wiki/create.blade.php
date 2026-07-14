@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('wiki.index') }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">&larr; Base de Conhecimento</a>
        <h1 class="text-2xl font-bold tracking-tight text-app">Novo Artigo</h1>
    </div>
    <x-ui.card>
        <form method="POST" action="{{ route('wiki.store') }}">@csrf
            @include('wiki._form', ['artigo' => null])
            <div class="mt-6">
                <x-ui.button type="submit" icon="save">Salvar</x-ui.button>
            </div>
        </form>
    </x-ui.card>
@endsection
