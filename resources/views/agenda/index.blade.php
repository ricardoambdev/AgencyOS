@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--text)]">Agenda</h1>
            <p class="text-sm text-[var(--text-muted)]">Eventos e compromissos da equipe</p>
        </div>
        <x-ui.button href="{{ route('agenda.create') }}" icon="plus">
            Novo Evento
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.list>
            @forelse($events as $event)
                <x-ui.list-item>
                    <div>
                        <div class="font-medium text-[var(--text)]">{{ $event->title }}</div>
                        <div class="text-xs text-[var(--text-muted)]">
                            {{ $event->start_at->format('d/m/Y H:i') }}
                            @if($event->location) &middot; {{ $event->location }} @endif
                            @if($event->owner->name ?? null) &middot; {{ $event->owner->name }} @endif
                        </div>
                    </div>
                    <x-ui.form
                        method="POST"
                        action="{{ route('agenda.destroy', $event) }}"
                        :confirm="'Remover este evento?'"
                        class="shrink-0"
                    >
                        @method('DELETE')
                        <x-ui.button type="submit" variant="danger" size="sm" icon="trash-2">
                            Remover
                        </x-ui.button>
                    </x-ui.form>
                </x-ui.list-item>
            @empty
                <x-ui.empty-state icon="calendar" title="Nenhum evento" description="Crie um novo evento para começar." />
            @endforelse
        </x-ui.list>
    </x-ui.card>

    <div class="mt-4">{{ $events->links() }}</div>
@endsection
