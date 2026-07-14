@extends('layouts.app')
@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-app">Produção / Ativos</h1>
            <p class="text-sm text-muted">Entregáveis e ativos de produção.</p>
        </div>
        <x-ui.button href="{{ route('producao.create') }}" icon="plus">Novo Entregável</x-ui.button>
    </div>

    <x-ui.card>
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <x-ui.field label="Projeto" name="project_id" inline>
                <x-ui.select name="project_id" :options="['' => 'Todos'] + $projetos->pluck('name', 'id')->toArray()" :selected="request('project_id')" onchange="this.form.submit()" class="w-auto" />
            </x-ui.field>
            <x-ui.field label="Status" name="status" inline>
                <x-ui.select name="status" :options="['' => 'Todos'] + App\Domains\Producao\Controllers\EntregavelController::status()" :selected="request('status')" onchange="this.form.submit()" class="w-auto" />
            </x-ui.field>
            <x-ui.field label="Tipo" name="tipo" inline>
                <x-ui.select name="tipo" :options="['' => 'Todos'] + App\Domains\Producao\Controllers\EntregavelController::tipos()" :selected="request('tipo')" onchange="this.form.submit()" class="w-auto" />
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
                <x-ui.th>Entregável</x-ui.th>
                <x-ui.th>Projeto</x-ui.th>
                <x-ui.th>Tipo</x-ui.th>
                <x-ui.th>Status</x-ui.th>
                <x-ui.th>Responsável</x-ui.th>
                <x-ui.th>Entrega</x-ui.th>
            </x-slot>
            @forelse($entregaveis as $e)
                <x-ui.tr>
                    <x-ui.td><a href="{{ route('producao.show', $e) }}" class="font-medium text-primary-700 hover:underline dark:text-primary-300">{{ $e->name }}</a> <span class="text-xs text-muted">v{{ $e->version }}</span></x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $e->projeto->name ?? '-' }}</x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ App\Domains\Producao\Controllers\EntregavelController::tipos()[$e->type] ?? $e->type }}</x-ui.td>
                    <x-ui.td><x-ui.badge>{{ App\Domains\Producao\Controllers\EntregavelController::status()[$e->status] ?? $e->status }}</x-ui.badge></x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $e->owner->name ?? '-' }}</x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $e->due_date?->format('d/m/Y') ?? '-' }}</x-ui.td>
                </x-ui.tr>
            @empty
                <x-ui.empty-state title="Nenhum entregável" description="Crie um novo entregável para começar." />
            @endforelse
        </x-ui.table>
    </x-ui.card>
    <div class="mt-4">{{ $entregaveis->links() }}</div>
@endsection
