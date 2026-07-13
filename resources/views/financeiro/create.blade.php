@extends('layouts.app')
@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Nova Fatura</h1>
    <div class="bg-white shadow rounded-lg p-6 max-w-2xl">
        <form method="POST" action="{{ route('financeiro.store') }}">@csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-medium text-gray-700">Cliente *</label>
                    <select name="client_id" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
                        <option value="">Selecione...</option>
                        @foreach($clientes as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                    </select></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Número *</label>
                        <input type="text" name="number" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required></div>
                    <div><label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                            <option value="draft">Rascunho</option>
                            <option value="sent">Enviada</option>
                            <option value="paid">Paga</option>
                            <option value="overdue">Vencida</option>
                        </select></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Emissão</label>
                        <input type="date" name="issued_at" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></div>
                    <div><label class="block text-sm font-medium text-gray-700">Vencimento</label>
                        <input type="date" name="due_at" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Subtotal</label>
                        <input type="number" step="0.01" name="subtotal" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></div>
                    <div><label class="block text-sm font-medium text-gray-700">Imposto</label>
                        <input type="number" step="0.01" name="tax" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></div>
                    <div><label class="block text-sm font-medium text-gray-700">Total</label>
                        <input type="number" step="0.01" name="total" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></div>
                </div>
            </div>
            <div class="mt-6"><button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Salvar</button>
            <a href="{{ route('financeiro.index') }}" class="ml-2 text-gray-600 text-sm">Cancelar</a></div>
        </form>
    </div>
@endsection
