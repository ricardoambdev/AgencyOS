@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">CRM &middot; Leads</h1>
        <a href="{{ route('leads.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Novo Lead</a>
        <a href="{{ route('leads.import') }}" class="text-indigo-600 px-3 py-2 text-sm hover:underline">Importar</a>
        <a href="{{ route('leads.export') }}" class="text-indigo-600 px-3 py-2 text-sm hover:underline">Exportar</a>
    </div>

    <form method="GET" class="mb-4 flex items-center gap-2">
        <label class="text-sm text-gray-600">Status</label>
        <select name="status" onchange="this.form.submit()" class="rounded-md border border-gray-300 px-2 py-1 text-sm">
            <option value="">Todos</option>
            @foreach($statuses as $k => $v)
                <option value="{{ $k }}" {{ request('status') == $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
    </form>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Empresa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Responsável</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($leads as $lead)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4"><a href="{{ route('leads.show', $lead) }}" class="text-indigo-600 font-medium">{{ $lead->name }}</a>
                        <div class="text-xs text-gray-500">{{ $lead->email }}</div></td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $lead->company_name }}</td>
                    <td class="px-6 py-4">@include('partials.status-badge', ['entityType' => \App\Domains\Crm\Models\Lead::class, 'status' => $lead->status])</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $lead->owner->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">R$ {{ number_format($lead->value, 2, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right text-sm">
                        <a href="{{ route('leads.edit', $lead) }}" class="text-indigo-600">Editar</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Nenhum lead encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $leads->links() }}</div>
@endsection
