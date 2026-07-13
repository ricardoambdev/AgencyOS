@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('config.custom-fields.index') }}" class="text-sm text-indigo-600">&larr; Campos Personalizados</a>
        <h1 class="text-2xl font-bold text-gray-800">Editar Campo Personalizado</h1>
    </div>
    <form method="POST" action="{{ route('config.custom-fields.update', $customField) }}" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')
        @include('config.custom-fields._form', ['customField' => $customField, 'entityTypes' => $entityTypes, 'types' => $types])
        <div class="mt-6"><button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Salvar</button></div>
    </form>
@endsection
