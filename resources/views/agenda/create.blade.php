@extends('layouts.app')
@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Novo Evento</h1>
    <div class="bg-white shadow rounded-lg p-6 max-w-2xl">
        <form method="POST" action="{{ route('agenda.store') }}">@csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-medium text-gray-700">Título *</label>
                    <input type="text" name="title" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Início *</label>
                        <input type="datetime-local" name="start_at" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required></div>
                    <div><label class="block text-sm font-medium text-gray-700">Término</label>
                        <input type="datetime-local" name="end_at" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700">Local</label>
                    <input type="text" name="location" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></div>
                <div><label class="block text-sm font-medium text-gray-700">Responsável</label>
                    <select name="user_id" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                        <option value="">-</option>
                        @foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach
                    </select></div>
            </div>
            <div class="mt-6"><button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Salvar</button>
            <a href="{{ route('agenda.index') }}" class="ml-2 text-gray-600 text-sm">Cancelar</a></div>
        </form>
    </div>
@endsection
