@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Clientes</h1>
        <a href="{{ route('clientes.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Novo Cliente</a>
    </div>
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">E-mail</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Responsável</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($clientes as $cliente)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4"><a href="{{ route('clientes.show', $cliente) }}" class="text-indigo-600 font-medium">{{ $cliente->name }}</a></td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $cliente->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $cliente->owner->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-right text-sm"><a href="{{ route('clientes.edit', $cliente) }}" class="text-indigo-600">Editar</a></td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Nenhum cliente.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $clientes->links() }}</div>
@endsection
