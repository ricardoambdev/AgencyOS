@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Comercial / Contratos</h1>
        <a href="{{ route('comercial.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Novo Contrato</a>
    </div>

    <form method="GET" class="bg-white shadow rounded-lg p-4 mb-4 flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500">Cliente</label>
            <select name="client_id" onchange="this.form.submit()" class="mt-1 rounded-md border border-gray-300 px-2 py-1 text-sm">
                <option value="">Todos</option>
                @foreach($clientes as $c)<option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500">Status</label>
            <select name="status" onchange="this.form.submit()" class="mt-1 rounded-md border border-gray-300 px-2 py-1 text-sm">
                <option value="">Todos</option>
                @foreach(['rascunho' => 'Rascunho', 'ativo' => 'Ativo', 'encerrado' => 'Encerrado', 'cancelado' => 'Cancelado'] as $k => $v)
                    <option value="{{ $k }}" {{ request('status') == $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500">Tipo</label>
            <select name="type" onchange="this.form.submit()" class="mt-1 rounded-md border border-gray-300 px-2 py-1 text-sm">
                <option value="">Todos</option>
                @foreach(['fixed' => 'Preço fixo', 'hourly' => 'Por hora', 'retainer' => 'Retainer'] as $k => $v)
                    <option value="{{ $k }}" {{ request('type') == $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div><input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar..." class="mt-1 rounded-md border border-gray-300 px-2 py-1 text-sm"></div>
        <button class="bg-gray-700 text-white px-3 py-2 rounded-md text-sm">Filtrar</button>
    </form>

    <div class="mb-4 flex gap-4 text-sm">
        <span class="bg-white shadow rounded-lg px-4 py-2">Ativos: <strong>R$ {{ number_format($ativos, 2, ',', '.') }}</strong></span>
        <span class="bg-white shadow rounded-lg px-4 py-2">Pipeline (rascunho): <strong>R$ {{ number_format($pipeline, 2, ',', '.') }}</strong></span>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($contratos as $c)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $c->number ?? '-' }}</td>
                    <td class="px-6 py-4"><a href="{{ route('comercial.show', $c) }}" class="text-indigo-600">{{ $c->title }}</a></td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $c->cliente->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $c->type }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $c->currency }} {{ number_format($c->value, 2, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm">{{ $c->status }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Nenhum contrato.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $contratos->links() }}</div>
@endsection
