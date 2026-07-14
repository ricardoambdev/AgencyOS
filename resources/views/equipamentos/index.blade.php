@extends('layouts.app')
@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-app">Equipamentos</h1>
            <p class="text-sm text-muted">Controle de equipamentos e ativos.</p>
        </div>
        <x-ui.button href="{{ route('equipamentos.create') }}" icon="plus">Novo Equipamento</x-ui.button>
    </div>

    <x-ui.card>
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <x-ui.field label="Tipo" name="type" inline>
                <x-ui.select name="type" :options="['' => 'Todos'] + App\Domains\Equipamento\Controllers\EquipamentoController::tipos()" :selected="request('type')" onchange="this.form.submit()" class="w-auto" />
            </x-ui.field>
            <x-ui.field label="Status" name="status" inline>
                <x-ui.select name="status" :options="['' => 'Todos'] + App\Domains\Equipamento\Controllers\EquipamentoController::status()" :selected="request('status')" onchange="this.form.submit()" class="w-auto" />
            </x-ui.field>
            <x-ui.field label="Buscar" name="q" inline>
                <x-ui.input type="text" name="q" :value="request('q')" placeholder="Buscar..." class="w-auto" />
            </x-ui.field>
            <x-ui.button type="submit" icon="filter">Filtrar</x-ui.button>
        </form>
    </x-ui.card>

    <x-ui.card>
        <x-ui.table>
            <x-slot name="head">
                <x-ui.th>Equipamento</x-ui.th>
                <x-ui.th>Tipo</x-ui.th>
                <x-ui.th>Status</x-ui.th>
                <x-ui.th>Responsável</x-ui.th>
                <x-ui.th>Série</x-ui.th>
            </x-slot>
            @forelse($equipamentos as $e)
                <x-ui.tr>
                    <x-ui.td><a href="{{ route('equipamentos.show', $e) }}" class="font-medium text-primary-700 hover:underline dark:text-primary-300">{{ $e->name }}</a></x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ App\Domains\Equipamento\Controllers\EquipamentoController::tipos()[$e->type] ?? $e->type }}</x-ui.td>
                    <x-ui.td><x-ui.badge>{{ App\Domains\Equipamento\Controllers\EquipamentoController::status()[$e->status] ?? $e->status }}</x-ui.badge></x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $e->owner->name ?? '-' }}</x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $e->serial ?? '-' }}</x-ui.td>
                </x-ui.tr>
            @empty
                <x-ui.empty-state title="Nenhum equipamento" description="Cadastre um novo equipamento para começar." />
            @endforelse
        </x-ui.table>
    </x-ui.card>
    <div class="mt-4">{{ $equipamentos->links() }}</div>
@endsection
