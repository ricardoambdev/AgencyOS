@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('config.roles.index') }}" class="text-sm text-primary-700 dark:text-primary-300">&larr; Funções</a>
        <h1 class="text-2xl font-bold text-[var(--text)]">Nova Função</h1>
    </div>
    <form method="POST" action="{{ route('config.roles.store') }}" class="bg-[var(--surface)] shadow rounded-lg p-6">
        @csrf
        @include('config.roles._form', ['role' => null, 'capabilities' => $capabilities, 'users' => $users])
        <div class="mt-6"><button class="bg-primary-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary-700">Salvar</button></div>
    </form>
@endsection
