@extends('layouts.app')
@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-app">Editar Projeto</h1>
            <a href="{{ route('projetos.index') }}" class="text-sm text-muted hover:underline">Voltar</a>
        </div>
        <x-ui.card>
            <form method="POST" action="{{ route('projetos.update', $projeto) }}" enctype="multipart/form-data">@csrf @method('PUT')
                @include('projetos._form', ['projeto' => $projeto, 'clientes' => $clientes, 'owners' => $owners])
                @include('partials.custom-fields-form', ['model' => $projeto])
                <div class="mt-6 flex items-center gap-3">
                    <x-ui.button type="submit" icon="save">Atualizar</x-ui.button>
                    <a href="{{ route('projetos.index') }}" class="text-sm text-muted hover:underline">Cancelar</a>
                </div>
            </form>
            <form method="POST" action="{{ route('projetos.destroy', $projeto) }}" class="mt-4" onsubmit="return confirm('Remover projeto?')">@csrf @method('DELETE')
                <x-ui.button type="submit" variant="danger" icon="trash-2">Remover projeto</x-ui.button>
            </form>
        </x-ui.card>
    </div>
@endsection
