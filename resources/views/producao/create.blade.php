@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('producao.index') }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">&larr; Produção</a>
        <h1 class="text-2xl font-bold tracking-tight text-app">Novo Entregável</h1>
    </div>
    <x-ui.card>
        <form method="POST" action="{{ route('producao.store') }}" enctype="multipart/form-data">@csrf
            @include('producao._form', ['entregavel' => null, 'projetos' => $projetos, 'owners' => $owners])
            <div class="mt-6">
                <x-ui.button type="submit" icon="save">Salvar</x-ui.button>
            </div>
        </form>
    </x-ui.card>
@endsection
