@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Templates de Projeto</h1>
        <a href="{{ route('templates.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Novo Template</a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tarefas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ativo</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($templates as $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4"><a href="{{ route('templates.show', $t) }}" class="text-indigo-600">{{ $t->name }}</a></td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $t->template_tasks_count }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $t->is_active ? 'Sim' : 'Não' }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500">Nenhum template.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $templates->links() }}</div>
@endsection
