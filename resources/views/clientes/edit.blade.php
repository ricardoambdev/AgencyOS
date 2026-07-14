@extends('layouts.app')
@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-app">Editar Cliente</h1>
            <a href="{{ route('clientes.index') }}" class="text-sm text-muted hover:underline">Voltar</a>
        </div>
        <x-ui.card>
            <form method="POST" action="{{ route('clientes.update', $cliente) }}">@csrf @method('PUT')
                @include('clientes._form', ['cliente' => $cliente, 'owners' => $owners])
                <div class="mt-6 flex items-center gap-3">
                    <x-ui.button type="submit" icon="save">Atualizar</x-ui.button>
                    <a href="{{ route('clientes.index') }}" class="text-sm text-muted hover:underline">Cancelar</a>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
