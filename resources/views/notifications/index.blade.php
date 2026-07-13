@extends('layouts.app')
@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Notificações</h1>
    <div class="bg-white shadow rounded-lg divide-y divide-gray-100">
        @forelse($notifications as $n)
        <div class="p-4 flex items-center justify-between {{ $n->read_at ? '' : 'bg-blue-50' }}">
            <div>
                <div class="font-medium text-gray-800">{{ $n->title }}</div>
                <div class="text-sm text-gray-500">{{ $n->body }}</div>
                <div class="text-xs text-gray-400">{{ $n->created_at->format('d/m/Y H:i') }}</div>
            </div>
            @if(!$n->read_at)
            <form method="POST" action="{{ route('notifications.read', $n) }}">@csrf
                <button class="text-indigo-600 text-sm">Marcar lida</button>
            </form>
            @endif
        </div>
        @empty
        <div class="p-8 text-center text-gray-500">Nenhuma notificação.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $notifications->links() }}</div>
@endsection
