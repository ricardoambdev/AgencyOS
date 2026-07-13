@extends('layouts.app')
@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Estados de Workflow</h1>
        <a href="{{ route('config.workflow-states.create', ['entity_type' => $selected]) }}"
           class="rounded-md bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-700">Novo estado</a>
    </div>

    <div class="mb-4 flex flex-wrap gap-2">
        @foreach($entityTypes as $type => $label)
            <a href="{{ route('config.workflow-states.index', ['entity_type' => $type]) }}"
               class="rounded-md px-3 py-1 text-sm {{ $type === $selected ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-200' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-semibold uppercase text-gray-500">
                    <th class="px-4 py-3">Ordem</th>
                    <th class="px-4 py-3">Slug</th>
                    <th class="px-4 py-3">Nome</th>
                    <th class="px-4 py-3">Cor</th>
                    <th class="px-4 py-3">Inicial</th>
                    <th class="px-4 py-3">Final</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($states as $state)
                    <tr>
                        <td class="px-4 py-3 text-sm">{{ $state->order }}</td>
                        <td class="px-4 py-3 text-sm font-mono">{{ $state->slug }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($state->color)
                                <span class="mr-2 inline-block h-3 w-3 rounded-full align-middle" style="background:{{ $state->color }}"></span>
                            @endif
                            {{ $state->name }}
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $state->color ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $state->is_initial ? 'Sim' : '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $state->is_final ? 'Sim' : '—' }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <a href="{{ route('config.workflow-states.edit', $state) }}" class="text-indigo-600 hover:underline">Editar</a>
                            <form method="POST" action="{{ route('config.workflow-states.destroy', $state) }}" class="inline" onsubmit="return confirm('Remover estado?')">
                                @csrf @method('DELETE')
                                <button class="ml-2 text-red-500 hover:underline">Remover</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-6 text-center text-sm text-gray-400">
                        Nenhum estado personalizado. Usando os estados padrão da plataforma.
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
