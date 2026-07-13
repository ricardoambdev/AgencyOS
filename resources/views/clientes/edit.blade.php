@extends('layouts.app')
@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Editar Cliente</h1>
    <div class="bg-white shadow rounded-lg p-6 max-w-2xl">
        <form method="POST" action="{{ route('clientes.update', $cliente) }}">@csrf @method('PUT')
            @include('clientes._form', ['cliente' => $cliente, 'owners' => $owners])
            <div class="mt-6"><button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Atualizar</button>
            <a href="{{ route('clientes.index') }}" class="ml-2 text-gray-600 text-sm">Cancelar</a></div>
        </form>
    </div>
@endsection
