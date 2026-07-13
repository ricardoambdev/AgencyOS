@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('horas.index') }}" class="text-sm text-indigo-600">&larr; Controle de Horas</a>
        <h1 class="text-2xl font-bold text-gray-800">Lançar Horas</h1>
    </div>
    <form method="POST" action="{{ route('horas.store') }}" class="bg-white shadow rounded-lg p-6">
        @csrf
        @include('horas._form', ['lancamento' => null, 'projetos' => $projetos, 'users' => $users])
        <div class="mt-6"><button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Salvar</button></div>
    </form>
@endsection
