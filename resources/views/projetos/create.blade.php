@extends('layouts.app')
@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Novo Projeto</h1>
    <div class="bg-white shadow rounded-lg p-6 max-w-2xl">
        <form method="POST" action="{{ route('projetos.store') }}" enctype="multipart/form-data">@csrf
            @include('projetos._form', ['projeto' => null, 'clientes' => $clientes, 'owners' => $owners])
            @include('partials.custom-fields-form', ['model' => new \App\Domains\Projeto\Models\Projeto])
            <div class="mt-6"><button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Salvar</button>
            <a href="{{ route('projetos.index') }}" class="ml-2 text-gray-600 text-sm">Cancelar</a></div>
        </form>
    </div>
@endsection
