@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('config.roles.index') }}" class="text-sm text-indigo-600">&larr; Funções</a>
        <h1 class="text-2xl font-bold text-gray-800">Editar Função</h1>
    </div>
    <form method="POST" action="{{ route('config.roles.update', $role) }}" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')
        @include('config.roles._form', ['role' => $role, 'capabilities' => $capabilities, 'users' => $users, 'assigned' => $assigned])
        <div class="mt-6 flex gap-2">
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Salvar</button>
            @if($role->slug !== 'owner')
                <button type="button" onclick="if(confirm('Remover função?')) document.getElementById('del-form').submit()" class="text-red-600 text-sm">Remover</button>
                <form id="del-form" method="POST" action="{{ route('config.roles.destroy', $role) }}" class="hidden">@csrf @method('DELETE')</form>
            @endif
        </div>
    </form>
@endsection
