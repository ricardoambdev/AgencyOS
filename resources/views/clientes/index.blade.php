@extends('layouts.app')
@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-app">Clientes</h1>
            <p class="text-sm text-muted">Clientes ativos da sua agência.</p>
        </div>
        <x-ui.button href="{{ route('clientes.create') }}" icon="plus">Novo Cliente</x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.table>
            <x-slot name="head">
                <x-ui.th>Nome</x-ui.th>
                <x-ui.th>E-mail</x-ui.th>
                <x-ui.th>Responsável</x-ui.th>
                <x-ui.th class="text-right">Ações</x-ui.th>
            </x-slot>
            @forelse($clientes as $cliente)
                <x-ui.tr>
                    <x-ui.td><a href="{{ route('clientes.show', $cliente) }}" class="font-medium text-primary-700 hover:underline dark:text-primary-300">{{ $cliente->name }}</a></x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $cliente->email }}</x-ui.td>
                    <x-ui.td class="text-sm text-muted">{{ $cliente->owner->name ?? '-' }}</x-ui.td>
                    <x-ui.td class="text-right text-sm"><a href="{{ route('clientes.edit', $cliente) }}" class="text-primary-700 hover:underline dark:text-primary-300">Editar</a></x-ui.td>
                </x-ui.tr>
            @empty
                <x-ui.empty-state title="Nenhum cliente" description="Cadastre um novo cliente para começar." />
            @endforelse
        </x-ui.table>
    </x-ui.card>
    <div class="mt-4">{{ $clientes->links() }}</div>
@endsection
