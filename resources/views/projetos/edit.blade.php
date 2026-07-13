@extends('layouts.app')
@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Editar Projeto</h1>
    <div class="bg-white shadow rounded-lg p-6 max-w-2xl">
        <form method="POST" action="{{ route('projetos.update', $projeto) }}" enctype="multipart/form-data">@csrf @method('PUT')
            @include('projetos._form', ['projeto' => $projeto, 'clientes' => $clientes, 'owners' => $owners])
            @include('partials.custom-fields-form', ['model' => $projeto])
            <div class="mt-6"><button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Atualizar</button>
            <a href="{{ route('projetos.index') }}" class="ml-2 text-gray-600 text-sm">Cancelar</a></div>
        </form>
        <form method="POST" action="{{ route('projetos.destroy', $projeto) }}" class="mt-4" onsubmit="return confirm('Remover projeto?')">@csrf @method('DELETE')
            <button class="text-red-600 text-sm">Remover projeto</button>
        </form>
    </div>
@endsection
