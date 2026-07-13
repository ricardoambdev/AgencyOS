@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('leads.index') }}" class="text-sm text-indigo-600">&larr; Leads</a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $lead->name }}</h1>
            <p class="text-sm text-gray-500">{{ $lead->company_name }} &middot; {{ $lead->email }}</p>
        </div>
        <div class="space-x-2">
            <a href="{{ route('leads.edit', $lead) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Editar</a>
            <form method="POST" action="{{ route('leads.destroy', $lead) }}" class="inline" onsubmit="return confirm('Remover lead?')">
                @csrf @method('DELETE')
                <button class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700">Remover</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.comments', ['model' => $lead])
                @include('partials.entity-activity', ['model' => $lead])
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd class="font-medium">{{ $lead->status }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Valor</dt><dd class="font-medium">R$ {{ number_format($lead->value, 2, ',', '.') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Origem</dt><dd class="font-medium">{{ $lead->source ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Responsável</dt><dd class="font-medium">{{ $lead->owner->name ?? '-' }}</dd></div>
                </dl>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.timeline', ['model' => $lead])
            </div>
        </div>
    </div>
@endsection
