@extends('layouts.app')
@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-app">Controle de Horas</h1>
            <p class="text-sm text-muted">Registre e filtre os lançamentos de horas.</p>
        </div>
        <x-ui.button href="{{ route('horas.create') }}" icon="plus">Lançar Horas</x-ui.button>
    </div>

    <x-ui.card>
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <x-ui.field label="Projeto" name="project_id" inline>
                <x-ui.select name="project_id" :options="['' => 'Todos'] + $projetos->pluck('name', 'id')->toArray()" :selected="request('project_id')" onchange="this.form.submit()" class="w-auto" />
            </x-ui.field>
            <x-ui.field label="Colaborador" name="user_id" inline>
                <x-ui.select name="user_id" :options="['' => 'Todos'] + $users->pluck('name', 'id')->toArray()" :selected="request('user_id')" onchange="this.form.submit()" class="w-auto" />
            </x-ui.field>
            <x-ui.field label="De" name="from" inline>
                <x-ui.input type="date" name="from" :value="request('from')" class="w-auto" />
            </x-ui.field>
            <x-ui.field label="Até" name="to" inline>
                <x-ui.input type="date" name="to" :value="request('to')" class="w-auto" />
            </x-ui.field>
            <x-ui.field label="Buscar" name="q" inline>
                <x-ui.input type="text" name="q" :value="request('q')" placeholder="Buscar..." class="w-auto" />
            </x-ui.field>
            <x-ui.button type="submit" icon="filter">Filtrar</x-ui.button>
        </form>
    </x-ui.card>

    <div class="mb-4 flex flex-wrap gap-4 text-sm">
        <div class="surface rounded-lg px-4 py-2">Total: <strong class="text-app">{{ number_format($totais['horas'], 2, ',', '.') }}h</strong></div>
        <div class="surface rounded-lg px-4 py-2">Faturáveis: <strong class="text-app">{{ number_format($totais['faturar'], 2, ',', '.') }}h</strong></div>
    </div>

    <x-ui.card>
        <x-ui.table>
            <x-slot name="head">
                <x-ui.th>Data</x-ui.th>
                <x-ui.th>Colaborador</x-ui.th>
                <x-ui.th>Projeto</x-ui.th>
                <x-ui.th>Horas</x-ui.th>
                <x-ui.th>Faturável</x-ui.th>
                <x-ui.th>Descrição</x-ui.th>
            </x-slot>
            @forelse($lancamentos as $l)
                <x-ui.tr>
                    <x-ui.td class="text-sm text-muted">{{ $l->date->format('d/m/Y') }}</x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $l->user->name ?? '-' }}</x-ui.td>
                    <x-ui.td><a href="{{ route('horas.show', $l) }}" class="text-primary-700 hover:underline dark:text-primary-300">{{ $l->project->name ?? '-' }}</a></x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ number_format($l->hours, 2, ',', '.') }}h</x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $l->billable ? 'Sim' : 'Não' }}</x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $l->description ?? '-' }}</x-ui.td>
                </x-ui.tr>
            @empty
                <x-ui.empty-state title="Nenhum lançamento" description="Lance suas horas para começar." />
            @endforelse
        </x-ui.table>
    </x-ui.card>
    <div class="mt-4">{{ $lancamentos->links() }}</div>
@endsection
