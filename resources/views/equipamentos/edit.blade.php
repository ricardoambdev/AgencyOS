@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('equipamentos.show', $equipamento) }}" class="text-sm text-indigo-600">&larr; Voltar</a>
        <h1 class="text-2xl font-bold text-gray-800">Editar Equipamento</h1>
    </div>
    <form method="POST" action="{{ route('equipamentos.update', $equipamento) }}" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')
        @include('equipamentos._form', ['equipamento' => $equipamento, 'owners' => $owners])
        <div class="mt-6"><button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Salvar</button></div>
    </form>
@endsection
