@extends('layouts.app')
@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-app">Nova Fatura</h1>
            <a href="{{ route('financeiro.index') }}" class="text-sm text-muted hover:underline">Voltar</a>
        </div>
        <x-ui.card>
            <form method="POST" action="{{ route('financeiro.store') }}">@csrf
                <div class="space-y-4">
                    <x-ui.field label="Cliente" name="client_id" required>
                        <x-ui.select name="client_id" :options="['' => 'Selecione...'] + $clientes->pluck('name', 'id')->toArray()" />
                    </x-ui.field>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-ui.field label="Número" name="number" required>
                            <x-ui.input name="number" />
                        </x-ui.field>
                        <x-ui.field label="Status" name="status">
                            <x-ui.select name="status" :options="['draft' => 'Rascunho', 'sent' => 'Enviada', 'paid' => 'Paga', 'overdue' => 'Vencida']" :selected="old('status', 'draft')" />
                        </x-ui.field>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-ui.field label="Emissão" name="issued_at">
                            <x-ui.input type="date" name="issued_at" :value="old('issued_at')" />
                        </x-ui.field>
                        <x-ui.field label="Vencimento" name="due_at">
                            <x-ui.input type="date" name="due_at" :value="old('due_at')" />
                        </x-ui.field>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <x-ui.field label="Subtotal" name="subtotal">
                            <x-ui.input type="number" step="0.01" name="subtotal" :value="old('subtotal')" />
                        </x-ui.field>
                        <x-ui.field label="Imposto" name="tax">
                            <x-ui.input type="number" step="0.01" name="tax" :value="old('tax')" />
                        </x-ui.field>
                        <x-ui.field label="Total" name="total">
                            <x-ui.input type="number" step="0.01" name="total" :value="old('total')" />
                        </x-ui.field>
                    </div>
                </div>
                <div class="mt-6 flex items-center gap-3">
                    <x-ui.button type="submit" icon="save">Salvar</x-ui.button>
                    <a href="{{ route('financeiro.index') }}" class="text-sm text-muted hover:underline">Cancelar</a>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
