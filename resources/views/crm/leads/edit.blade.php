@extends('layouts.app')
@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-app">Editar Lead</h1>
            <a href="{{ route('leads.index') }}" class="text-sm text-muted hover:underline">Voltar</a>
        </div>
        <x-ui.card>
            <form method="POST" action="{{ route('leads.update', $lead) }}">
                @csrf @method('PUT')
                @include('crm.leads._form', ['lead' => $lead, 'owners' => $owners])
                <div class="mt-6 flex items-center gap-3">
                    <x-ui.button type="submit" icon="save">Atualizar</x-ui.button>
                    <a href="{{ route('leads.index') }}" class="text-sm text-muted hover:underline">Cancelar</a>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
