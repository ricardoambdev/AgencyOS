@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Equipamentos</h1>
        <a href="{{ route('equipamentos.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Novo Equipamento</a>
    </div>

    <form method="GET" class="bg-white shadow rounded-lg p-4 mb-4 flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500">Tipo</label>
            <select name="type" onchange="this.form.submit()" class="mt-1 rounded-md border border-gray-300 px-2 py-1 text-sm">
                <option value="">Todos</option>
                @foreach(App\Domains\Equipamento\Controllers\EquipamentoController::tipos() as $k=>$l)<option value="{{ $k }}" {{ request('type') == $k ? 'selected' : '' }}>{{ $l }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500">Status</label>
            <select name="status" onchange="this.form.submit()" class="mt-1 rounded-md border border-gray-300 px-2 py-1 text-sm">
                <option value="">Todos</option>
                @foreach(App\Domains\Equipamento\Controllers\EquipamentoController::status() as $k=>$l)<option value="{{ $k }}" {{ request('status') == $k ? 'selected' : '' }}>{{ $l }}</option>@endforeach
            </select>
        </div>
        <div>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar..." class="mt-1 rounded-md border border-gray-300 px-2 py-1 text-sm">
        </div>
        <button class="bg-gray-700 text-white px-3 py-2 rounded-md text-sm">Filtrar</button>
    </form>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Equipamento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Responsável</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Série</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($equipamentos as $e)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4"><a href="{{ route('equipamentos.show', $e) }}" class="text-indigo-600 font-medium">{{ $e->name }}</a></td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ App\Domains\Equipamento\Controllers\EquipamentoController::tipos()[$e->type] ?? $e->type }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">{{ App\Domains\Equipamento\Controllers\EquipamentoController::status()[$e->status] ?? $e->status }}</span></td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $e->owner->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $e->serial ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Nenhum equipamento.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $equipamentos->links() }}</div>
@endsection
