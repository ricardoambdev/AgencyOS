@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('comercial.index') }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">&larr; Comercial</a>
        <h1 class="text-2xl font-bold tracking-tight text-app">Novo Contrato</h1>
    </div>
    <x-ui.card>
        <form method="POST" action="{{ route('comercial.store') }}" enctype="multipart/form-data">@csrf
            @include('comercial._form', ['contrato' => null, 'clientes' => $clientes, 'users' => $users])
            <div class="mt-6">
                <x-ui.button type="submit" icon="save">Salvar</x-ui.button>
            </div>
        </form>
    </x-ui.card>
@endsection
