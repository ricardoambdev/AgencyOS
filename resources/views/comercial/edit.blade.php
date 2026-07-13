@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('comercial.show', $contrato) }}" class="text-sm text-indigo-600">&larr; Voltar</a>
        <h1 class="text-2xl font-bold text-gray-800">Editar Contrato</h1>
    </div>
    <form method="POST" action="{{ route('comercial.update', $contrato) }}" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')
        @include('comercial._form', ['contrato' => $contrato, 'clientes' => $clientes, 'users' => $users])
        <div class="mt-6"><button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Salvar</button></div>
    </form>
@endsection
