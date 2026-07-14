@extends('layouts.app')
@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-app">CRM &middot; Leads</h1>
            <p class="text-sm text-muted">Gerencie seus leads e oportunidades.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('leads.import') }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">Importar</a>
            <a href="{{ route('leads.export') }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">Exportar</a>
            <x-ui.button href="{{ route('leads.create') }}" icon="plus">Novo Lead</x-ui.button>
        </div>
    </div>

    <form method="GET" class="mb-4 flex items-center gap-2">
        <label class="text-sm text-muted">Status</label>
        <x-ui.select name="status" :options="['' => 'Todos'] + $statuses" :selected="request('status')" onchange="this.form.submit()" class="w-auto" />
    </form>

    <x-ui.card>
        <x-ui.table>
            <x-slot name="head">
                <x-ui.th>Nome</x-ui.th>
                <x-ui.th>Empresa</x-ui.th>
                <x-ui.th>Status</x-ui.th>
                <x-ui.th>Responsável</x-ui.th>
                <x-ui.th>Valor</x-ui.th>
                <x-ui.th class="text-right">Ações</x-ui.th>
            </x-slot>
            @forelse($leads as $lead)
                <x-ui.tr>
                    <x-ui.td>
                        <a href="{{ route('leads.show', $lead) }}" class="font-medium text-primary-700 hover:underline dark:text-primary-300">{{ $lead->name }}</a>
                        <div class="text-xs text-muted">{{ $lead->email }}</div>
                    </x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $lead->company_name }}</x-ui.td>
                    <x-ui.td>@include('partials.status-badge', ['entityType' => \App\Domains\Crm\Models\Lead::class, 'status' => $lead->status])</x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $lead->owner->name ?? '-' }}</x-ui.td>
                    <x-ui.td class="text-sm text-muted">R$ {{ number_format($lead->value, 2, ',', '.') }}</x-ui.td>
                    <x-ui.td class="text-right text-sm">
                        <a href="{{ route('leads.edit', $lead) }}" class="text-primary-700 hover:underline dark:text-primary-300">Editar</a>
                    </x-ui.td>
                </x-ui.tr>
            @empty
                <x-ui.empty-state title="Nenhum lead encontrado" description="Crie um novo lead para começar." />
            @endforelse
        </x-ui.table>
    </x-ui.card>
    <div class="mt-4">{{ $leads->links() }}</div>
@endsection
