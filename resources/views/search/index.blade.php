@extends('layouts.app')
@section('content')
    <h1 class="text-2xl font-bold text-[var(--text)] mb-6">Pesquisa Global</h1>
    <form method="GET" action="{{ route('search.index') }}" class="mb-6">
        <input type="text" name="q" value="{{ $term }}" placeholder="Digite para pesquisar..." class="w-full rounded-md border border-gray-300 px-4 py-2" autofocus>
    </form>
    @if($term)
        @forelse($results as $type => $items)
            <div class="mb-6">
                <h2 class="text-sm font-semibold text-[var(--text-muted)] uppercase mb-2">{{ ucfirst($type) }} ({{ $items->count() }})</h2>
                <div class="bg-[var(--surface)] shadow rounded-lg divide-y divide-[var(--border)]">
                    @foreach($items as $item)
                        @php
                            $link = match($type) {
                                'leads' => route('leads.show', $item),
                                'clients' => route('clientes.show', $item),
                                'projects' => route('projetos.show', $item),
                                'events' => route('agenda.index'),
                                default => '#',
                            };
                        @endphp
                        <div class="p-3"><a href="{{ $link }}" class="text-primary-700 dark:text-primary-300">{{ $item->name ?? $item->title ?? $item->email ?? 'Item' }}</a></div>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-[var(--text-muted)]">Nenhum resultado para "{{ $term }}".</p>
        @endforelse
    @endif
@endsection
