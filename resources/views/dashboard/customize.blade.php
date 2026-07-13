@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600">&larr; Dashboard</a>
        <h1 class="text-2xl font-bold text-gray-800">Personalizar Dashboard</h1>
        <p class="text-sm text-gray-500">Marque os widgets que deseja exibir (na ordem desejada).</p>
    </div>

    <form method="POST" action="{{ route('dashboard.customize.update') }}" class="bg-white shadow rounded-lg p-6">
        @csrf
        <div class="space-y-2">
            @foreach($available as $key => $info)
                <label class="flex items-center gap-3 border rounded-md px-3 py-2">
                    <input type="checkbox" name="widgets[]" value="{{ $key }}"
                        {{ in_array($key, $selected) ? 'checked' : '' }}>
                    <span>
                        <span class="font-medium text-gray-800">{{ $info['label'] }}</span>
                        <span class="text-xs text-gray-400 block">{{ $info['description'] }}</span>
                    </span>
                </label>
            @endforeach
        </div>
        <div class="mt-6"><button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Salvar</button></div>
    </form>
@endsection
