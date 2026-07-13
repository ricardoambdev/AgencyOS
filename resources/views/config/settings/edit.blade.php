@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('config.index') }}" class="text-sm text-indigo-600">&larr; Configurações</a>
        <h1 class="text-2xl font-bold text-gray-800">Configurações Gerais</h1>
    </div>

    <form method="POST" action="{{ route('config.settings.update') }}" class="bg-white shadow rounded-lg p-6 max-w-2xl">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            @foreach($keys as $key => $label)
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                    <input type="text" name="{{ $key }}" value="{{ old($key, $values[$key] ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                </div>
            @endforeach
        </div>
        <div class="mt-6"><button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Salvar</button></div>
    </form>
@endsection
