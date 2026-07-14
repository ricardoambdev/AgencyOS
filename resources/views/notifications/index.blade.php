@extends('layouts.app')
@section('content')
    <h1 class="text-2xl font-bold text-[var(--text)] mb-6">Notificações</h1>
    <div class="bg-[var(--surface)] shadow rounded-lg divide-y divide-[var(--border)]">
        @forelse($notifications as $n)
        <div class="p-4 flex items-center justify-between {{ $n->read_at ? '' : 'bg-[var(--surface-2)]' }}">
            <div>
                <div class="font-medium text-[var(--text)]">{{ $n->title }}</div>
                <div class="text-sm text-[var(--text-muted)]">{{ $n->body }}</div>
                <div class="text-xs text-[var(--text-muted)]">{{ $n->created_at->format('d/m/Y H:i') }}</div>
            </div>
            @if(!$n->read_at)
            <form method="POST" action="{{ route('notifications.read', $n) }}">@csrf
                <button class="text-primary-700 dark:text-primary-300 text-sm">Marcar lida</button>
            </form>
            @endif
        </div>
        @empty
        <div class="p-8 text-center text-[var(--text-muted)]">Nenhuma notificação.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $notifications->links() }}</div>
@endsection
