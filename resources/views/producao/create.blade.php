@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('producao.index') }}" class="text-sm text-indigo-600">&larr; Produção</a>
        <h1 class="text-2xl font-bold text-gray-800">Novo Entregável</h1>
    </div>
    <form method="POST" action="{{ route('producao.store') }}" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6">
        @csrf
        @include('producao._form', ['entregavel' => null, 'projetos' => $projetos, 'owners' => $owners])
        <div class="mt-6"><button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Salvar</button></div>
    </form>
@endsection
