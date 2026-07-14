@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[var(--text)]">Funções e Permissões</h1>
        <a href="{{ route('config.roles.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary-700">Nova Função</a>
    </div>

    <div class="bg-[var(--surface)] shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-[var(--border)]">
            <thead class="bg-[var(--surface-2)]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[var(--text-muted)] uppercase">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[var(--text-muted)] uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[var(--text-muted)] uppercase">Permissões</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[var(--text-muted)] uppercase">Usuários</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border)]">
                @forelse($roles as $role)
                <tr class="hover:bg-[var(--surface-2)]">
                    <td class="px-6 py-4"><a href="{{ route('config.roles.edit', $role) }}" class="text-primary-700 dark:text-primary-300">{{ $role->name }}</a></td>
                    <td class="px-6 py-4 text-sm text-[var(--text-muted)]">{{ $role->slug }}</td>
                    <td class="px-6 py-4 text-sm text-[var(--text-muted)]">{{ $role->capabilities ? count($role->capabilities) : 0 }}</td>
                    <td class="px-6 py-4 text-sm text-[var(--text-muted)]">{{ $role->users_count }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-[var(--text-muted)]">Nenhuma função.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
