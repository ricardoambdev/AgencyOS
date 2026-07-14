@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('equipamentos.index') }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">&larr; Equipamentos</a>
        <h1 class="text-2xl font-bold tracking-tight text-app">Novo Equipamento</h1>
    </div>
    <x-ui.card>
        <form method="POST" action="{{ route('equipamentos.store') }}">@csrf
            @include('equipamentos._form', ['equipamento' => null, 'owners' => $owners])
            <div class="mt-6">
                <x-ui.button type="submit" icon="save">Salvar</x-ui.button>
            </div>
        </form>
    </x-ui.card>
@endsection
