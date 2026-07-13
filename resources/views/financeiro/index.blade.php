@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Financeiro</h1>
        <a href="{{ route('financeiro.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Nova Fatura</a>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white shadow rounded-lg p-4"><div class="text-sm text-gray-500">A receber</div><div class="text-xl font-bold text-gray-800">R$ {{ number_format($totals['aberto'], 2, ',', '.') }}</div></div>
        <div class="bg-white shadow rounded-lg p-4"><div class="text-sm text-gray-500">Recebido</div><div class="text-xl font-bold text-green-600">R$ {{ number_format($totals['pago'], 2, ',', '.') }}</div></div>
    </div>
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vencimento</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($invoices as $inv)
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-indigo-600">{{ $inv->number }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $inv->client->name ?? '-' }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">{{ $inv->status }}</span></td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $inv->due_at?->format('d/m/Y') ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">R$ {{ number_format($inv->total, 2, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Nenhuma fatura.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $invoices->links() }}</div>
@endsection
