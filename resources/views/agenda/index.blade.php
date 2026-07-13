@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Agenda</h1>
        <a href="{{ route('agenda.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Novo Evento</a>
    </div>
    <div class="bg-white shadow rounded-lg divide-y divide-gray-100">
        @forelse($events as $event)
        <div class="p-4 flex items-center justify-between">
            <div>
                <div class="font-medium text-gray-800">{{ $event->title }}</div>
                <div class="text-xs text-gray-500">{{ $event->start_at->format('d/m/Y H:i') }} @if($event->location) &middot; {{ $event->location }} @endif &middot; {{ $event->owner->name ?? '' }}</div>
            </div>
            <form method="POST" action="{{ route('agenda.destroy', $event) }}" onsubmit="return confirm('Remover?')">@csrf @method('DELETE')
                <button class="text-red-600 text-sm">Remover</button>
            </form>
        </div>
        @empty
        <div class="p-8 text-center text-gray-500">Nenhum evento.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $events->links() }}</div>
@endsection
