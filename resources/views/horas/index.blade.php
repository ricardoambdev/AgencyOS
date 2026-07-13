@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Controle de Horas</h1>
        <a href="{{ route('horas.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Lançar Horas</a>
    </div>

    <form method="GET" class="bg-white shadow rounded-lg p-4 mb-4 flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500">Projeto</label>
            <select name="project_id" onchange="this.form.submit()" class="mt-1 rounded-md border border-gray-300 px-2 py-1 text-sm">
                <option value="">Todos</option>
                @foreach($projetos as $p)<option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500">Colaborador</label>
            <select name="user_id" onchange="this.form.submit()" class="mt-1 rounded-md border border-gray-300 px-2 py-1 text-sm">
                <option value="">Todos</option>
                @foreach($users as $u)<option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>@endforeach
            </select>
        </div>
        <div><input type="date" name="from" value="{{ request('from') }}" class="mt-1 rounded-md border border-gray-300 px-2 py-1 text-sm"></div>
        <div><input type="date" name="to" value="{{ request('to') }}" class="mt-1 rounded-md border border-gray-300 px-2 py-1 text-sm"></div>
        <div><input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar..." class="mt-1 rounded-md border border-gray-300 px-2 py-1 text-sm"></div>
        <button class="bg-gray-700 text-white px-3 py-2 rounded-md text-sm">Filtrar</button>
    </form>

    <div class="mb-4 flex gap-4 text-sm">
        <span class="bg-white shadow rounded-lg px-4 py-2">Total: <strong>{{ number_format($totais['horas'], 2, ',', '.') }}h</strong></span>
        <span class="bg-white shadow rounded-lg px-4 py-2">Faturáveis: <strong>{{ number_format($totais['faturar'], 2, ',', '.') }}h</strong></span>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Colaborador</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Projeto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Horas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Faturável</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descrição</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($lancamentos as $l)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $l->date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $l->user->name ?? '-' }}</td>
                    <td class="px-6 py-4"><a href="{{ route('horas.show', $l) }}" class="text-indigo-600">{{ $l->project->name ?? '-' }}</a></td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ number_format($l->hours, 2, ',', '.') }}h</td>
                    <td class="px-6 py-4 text-sm">{{ $l->billable ? 'Sim' : 'Não' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $l->description ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Nenhum lançamento.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $lancamentos->links() }}</div>
@endsection
