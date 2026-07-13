@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Funções e Permissões</h1>
        <a href="{{ route('config.roles.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Nova Função</a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Permissões</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuários</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($roles as $role)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4"><a href="{{ route('config.roles.edit', $role) }}" class="text-indigo-600">{{ $role->name }}</a></td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $role->slug }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $role->capabilities ? count($role->capabilities) : 0 }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $role->users_count }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Nenhuma função.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
