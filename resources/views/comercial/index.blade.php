@extends('layouts.app')
@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-app">Comercial / Contratos</h1>
            <p class="text-sm text-muted">Contratos e pipeline comercial.</p>
        </div>
        <x-ui.button href="{{ route('comercial.create') }}" icon="plus">Novo Contrato</x-ui.button>
    </div>

    <x-ui.card>
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <x-ui.field label="Cliente" name="client_id" inline>
                <x-ui.select name="client_id" :options="['' => 'Todos'] + $clientes->pluck('name', 'id')->toArray()" :selected="request('client_id')" onchange="this.form.submit()" class="w-auto" />
            </x-ui.field>
            <x-ui.field label="Status" name="status" inline>
                <x-ui.select name="status" :options="['' => 'Todos', 'rascunho' => 'Rascunho', 'ativo' => 'Ativo', 'encerrado' => 'Encerrado', 'cancelado' => 'Cancelado']" :selected="request('status')" onchange="this.form.submit()" class="w-auto" />
            </x-ui.field>
            <x-ui.field label="Tipo" name="type" inline>
                <x-ui.select name="type" :options="['' => 'Todos', 'fixed' => 'Preço fixo', 'hourly' => 'Por hora', 'retainer' => 'Retainer']" :selected="request('type')" onchange="this.form.submit()" class="w-auto" />
            </x-ui.field>
            <x-ui.field label="Buscar" name="q" inline>
                <x-ui.input type="text" name="q" :value="request('q')" placeholder="Buscar..." class="w-auto" />
            </x-ui.field>
            <x-ui.button type="submit" icon="filter">Filtrar</x-ui.button>
        </form>
    </x-ui.card>

    <div class="mb-4 flex flex-wrap gap-4 text-sm">
        <div class="surface rounded-lg px-4 py-2">Ativos: <strong class="text-app">R$ {{ number_format($ativos, 2, ',', '.') }}</strong></div>
        <div class="surface rounded-lg px-4 py-2">Pipeline (rascunho): <strong class="text-app">R$ {{ number_format($pipeline, 2, ',', '.') }}</strong></div>
    </div>

    <x-ui.card>
        <x-ui.table>
            <x-slot name="head">
                <x-ui.th>Número</x-ui.th>
                <x-ui.th>Título</x-ui.th>
                <x-ui.th>Cliente</x-ui.th>
                <x-ui.th>Tipo</x-ui.th>
                <x-ui.th>Valor</x-ui.th>
                <x-ui.th>Status</x-ui.th>
            </x-slot>
            @forelse($contratos as $c)
                <x-ui.tr>
                    <x-ui.td class="text-sm text-muted">{{ $c->number ?? '-' }}</x-ui.td>
                    <x-ui.td><a href="{{ route('comercial.show', $c) }}" class="font-medium text-primary-700 hover:underline dark:text-primary-300">{{ $c->title }}</a></x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $c->cliente->name ?? '-' }}</x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $c->type }}</x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $c->currency }} {{ number_format($c->value, 2, ',', '.') }}</x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $c->status }}</x-ui.td>
                </x-ui.tr>
            @empty
                <x-ui.empty-state title="Nenhum contrato" description="Crie um novo contrato para começar." />
            @endforelse
        </x-ui.table>
    </x-ui.card>
    <div class="mt-4">{{ $contratos->links() }}</div>
@endsection
