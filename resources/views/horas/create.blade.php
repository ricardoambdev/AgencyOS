@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('horas.index') }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">&larr; Controle de Horas</a>
        <h1 class="text-2xl font-bold tracking-tight text-app">Lançar Horas</h1>
    </div>
    <x-ui.card>
        <form method="POST" action="{{ route('horas.store') }}">@csrf
            @include('horas._form', ['lancamento' => null, 'projetos' => $projetos, 'users' => $users])
            <div class="mt-6">
                <x-ui.button type="submit" icon="save">Salvar</x-ui.button>
            </div>
        </form>
    </x-ui.card>
@endsection
