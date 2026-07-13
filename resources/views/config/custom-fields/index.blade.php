@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Campos Personalizados</h1>
        <a href="{{ route('config.custom-fields.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Novo Campo</a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rótulo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entidade</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Filtro</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($fields as $f)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4"><a href="{{ route('config.custom-fields.edit', $f) }}" class="text-indigo-600">{{ $f->label }}</a> <span class="text-gray-400 text-xs">{{ $f->name }}</span></td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ class_basename($f->entity_type) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $f->type }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $f->is_filterable ? 'Sim' : 'Não' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Nenhum campo personalizado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $fields->links() }}</div>
@endsection
