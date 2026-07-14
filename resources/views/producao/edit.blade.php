@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('producao.show', $entregavel) }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">&larr; Voltar</a>
        <h1 class="text-2xl font-bold tracking-tight text-app">Editar Entregável</h1>
    </div>
    <x-ui.card>
        <form method="POST" action="{{ route('producao.update', $entregavel) }}" enctype="multipart/form-data">@csrf @method('PUT')
            @include('producao._form', ['entregavel' => $entregavel, 'projetos' => $projetos, 'owners' => $owners])
            <div class="mt-6">
                <x-ui.button type="submit" icon="save">Salvar</x-ui.button>
            </div>
        </form>
    </x-ui.card>
@endsection
