@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--text)]">Templates de Projeto</h1>
            <p class="text-sm text-[var(--text-muted)]">Modelos reutilizáveis de projetos e tarefas</p>
        </div>
        <x-ui.button href="{{ route('templates.create') }}" icon="plus">
            Novo Template
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.table>
            <x-slot name="head">
                <x-ui.th>Nome</x-ui.th>
                <x-ui.th>Tarefas</x-ui.th>
                <x-ui.th>Ativo</x-ui.th>
            </x-slot>
            @forelse($templates as $t)
                <x-ui.tr>
                    <x-ui.td><a href="{{ route('templates.show', $t) }}" class="text-primary-700 dark:text-primary-300 font-medium">{{ $t->name }}</a></x-ui.td>
                    <x-ui.td class="text-[var(--text-muted)]">{{ $t->template_tasks_count }}</x-ui.td>
                    <x-ui.td>
                        @if($t->is_active)
                            <x-ui.badge tone="success">Sim</x-ui.badge>
                        @else
                            <x-ui.badge tone="neutral">Não</x-ui.badge>
                        @endif
                    </x-ui.td>
                </x-ui.tr>
            @empty
                <x-ui.tr><x-ui.td colspan="3"><x-ui.empty-state icon="layout-template" title="Nenhum template" description="Crie um modelo de projeto para começar." /></x-ui.td></x-ui.tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>

    <div class="mt-4">{{ $templates->links() }}</div>
@endsection
