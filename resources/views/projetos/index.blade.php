@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Projetos</h1>
        <a href="{{ route('projetos.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Novo Projeto</a>
    </div>
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Projeto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Responsável</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orçamento</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($projetos as $projeto)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4"><a href="{{ route('projetos.show', $projeto) }}" class="text-indigo-600 font-medium">{{ $projeto->name }}</a></td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $projeto->client->name ?? '-' }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">{{ $projeto->status }}</span></td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $projeto->owner->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">R$ {{ number_format($projeto->budget, 2, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Nenhum projeto.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $projetos->links() }}</div>
@endsection
