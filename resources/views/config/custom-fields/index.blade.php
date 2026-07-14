@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[var(--text)]">Campos Personalizados</h1>
        <a href="{{ route('config.custom-fields.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary-700">Novo Campo</a>
    </div>

    <div class="bg-[var(--surface)] shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-[var(--border)]">
            <thead class="bg-[var(--surface-2)]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[var(--text-muted)] uppercase">Rótulo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[var(--text-muted)] uppercase">Entidade</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[var(--text-muted)] uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[var(--text-muted)] uppercase">Filtro</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border)]">
                @forelse($fields as $f)
                <tr class="hover:bg-[var(--surface-2)]">
                    <td class="px-6 py-4"><a href="{{ route('config.custom-fields.edit', $f) }}" class="text-primary-700 dark:text-primary-300">{{ $f->label }}</a> <span class="text-[var(--text-muted)] text-xs">{{ $f->name }}</span></td>
                    <td class="px-6 py-4 text-sm text-[var(--text-muted)]">{{ class_basename($f->entity_type) }}</td>
                    <td class="px-6 py-4 text-sm text-[var(--text-muted)]">{{ $f->type }}</td>
                    <td class="px-6 py-4 text-sm text-[var(--text-muted)]">{{ $f->is_filterable ? 'Sim' : 'Não' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-[var(--text-muted)]">Nenhum campo personalizado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $fields->links() }}</div>
@endsection
