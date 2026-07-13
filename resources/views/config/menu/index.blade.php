@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Menu do Workspace</h1>
        <a href="{{ route('config.menu.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">Novo item</a>
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Ordem</th>
                    <th class="px-4 py-3">Rótulo</th>
                    <th class="px-4 py-3">Rota</th>
                    <th class="px-4 py-3">URL</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $item)
                    <tr>
                        <td class="px-4 py-3 text-sm">{{ $item->order }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->label }}</td>
                        <td class="px-4 py-3 text-sm font-mono">{{ $item->route ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->url ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <a href="{{ route('config.menu.edit', $item) }}" class="text-indigo-600 hover:underline">Editar</a>
                            <form method="POST" action="{{ route('config.menu.destroy', $item) }}" class="inline" onsubmit="return confirm('Remover item?')">
                                @csrf @method('DELETE')
                                <button class="ml-2 text-red-500 hover:underline">Remover</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-sm text-gray-400">
                        Menu padrão da plataforma em uso. Adicione itens para personalizar.
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
