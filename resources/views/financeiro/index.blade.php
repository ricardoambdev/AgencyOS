@extends('layouts.app')
@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-app">Financeiro</h1>
            <p class="text-sm text-muted">Controle de faturas e recebimentos.</p>
        </div>
        <x-ui.button href="{{ route('financeiro.create') }}" icon="plus">Nova Fatura</x-ui.button>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-ui.stat-card label="A receber" value="R$ {{ number_format($totals['aberto'], 2, ',', '.') }}" tone="primary" icon="trending-up" />
        <x-ui.stat-card label="Recebido" value="R$ {{ number_format($totals['pago'], 2, ',', '.') }}" tone="success" icon="check-circle" />
    </div>

    <x-ui.card>
        <x-ui.table>
            <x-slot name="head">
                <x-ui.th>Número</x-ui.th>
                <x-ui.th>Cliente</x-ui.th>
                <x-ui.th>Status</x-ui.th>
                <x-ui.th>Vencimento</x-ui.th>
                <x-ui.th>Total</x-ui.th>
            </x-slot>
            @forelse($invoices as $inv)
                <x-ui.tr>
                    <x-ui.td class="font-medium text-primary-700 dark:text-primary-300">{{ $inv->number }}</x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $inv->client->name ?? '-' }}</x-ui.td>
                    <x-ui.td><x-ui.badge>{{ $inv->status }}</x-ui.badge></x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $inv->due_at?->format('d/m/Y') ?? '-' }}</x-ui.td>
                    <x-ui.td class="text-sm text-muted">R$ {{ number_format($inv->total, 2, ',', '.') }}</x-ui.td>
                </x-ui.tr>
            @empty
                <x-ui.empty-state title="Nenhuma fatura" description="Emita uma nova fatura para começar." />
            @endforelse
        </x-ui.table>
    </x-ui.card>
    <div class="mt-4">{{ $invoices->links() }}</div>
@endsection
