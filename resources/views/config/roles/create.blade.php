@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('config.roles.index') }}" class="text-sm text-indigo-600">&larr; Funções</a>
        <h1 class="text-2xl font-bold text-gray-800">Nova Função</h1>
    </div>
    <form method="POST" action="{{ route('config.roles.store') }}" class="bg-white shadow rounded-lg p-6">
        @csrf
        @include('config.roles._form', ['role' => null, 'capabilities' => $capabilities, 'users' => $users])
        <div class="mt-6"><button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Salvar</button></div>
    </form>
@endsection
