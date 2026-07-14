@extends('layouts.app')
@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-app">Projetos</h1>
            <p class="text-sm text-muted">Acompanhe a entrega e o orçamento dos projetos.</p>
        </div>
        <x-ui.button href="{{ route('projetos.create') }}" icon="plus">Novo Projeto</x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.table>
            <x-slot name="head">
                <x-ui.th>Projeto</x-ui.th>
                <x-ui.th>Cliente</x-ui.th>
                <x-ui.th>Status</x-ui.th>
                <x-ui.th>Responsável</x-ui.th>
                <x-ui.th>Orçamento</x-ui.th>
            </x-slot>
            @forelse($projetos as $projeto)
                <x-ui.tr>
                    <x-ui.td><a href="{{ route('projetos.show', $projeto) }}" class="font-medium text-primary-700 hover:underline dark:text-primary-300">{{ $projeto->name }}</a></x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $projeto->client->name ?? '-' }}</x-ui.td>
                    <x-ui.td><x-ui.badge>{{ $projeto->status }}</x-ui.badge></x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $projeto->owner->name ?? '-' }}</x-ui.td>
                    <x-ui.td class="text-sm text-muted">R$ {{ number_format($projeto->budget, 2, ',', '.') }}</x-ui.td>
                </x-ui.tr>
            @empty
                <x-ui.empty-state title="Nenhum projeto" description="Crie um novo projeto para começar." />
            @endforelse
        </x-ui.table>
    </x-ui.card>
    <div class="mt-4">{{ $projetos->links() }}</div>
@endsection
