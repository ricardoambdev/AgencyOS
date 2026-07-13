@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('templates.show', $template) }}" class="text-sm text-indigo-600">&larr; {{ $template->name }}</a>
        <h1 class="text-2xl font-bold text-gray-800">Aplicar Template: {{ $template->name }}</h1>
        <p class="text-sm text-gray-500">Cria um projeto com {{ $template->templateTasks->count() }} tarefa(s) pré-definida(s).</p>
    </div>

    <form method="POST" action="{{ route('templates.apply.store', $template) }}" class="bg-white shadow rounded-lg p-6" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cliente</label>
                    <select name="client_id" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
                        <option value="">—</option>
                        @foreach($clientes as $c)<option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Responsável</label>
                    <select name="owner_id" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                        <option value="">—</option>
                        @foreach($owners as $u)<option value="{{ $u->id }}" {{ old('owner_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nome do Projeto</label>
                <input type="text" name="name" value="{{ old('name', $template->name) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                        @foreach(['briefing' => 'Briefing', 'planejamento' => 'Planejamento', 'producao' => 'Produção', 'revisao' => 'Revisão', 'cliente' => 'Com Cliente', 'finalizado' => 'Finalizado'] as $k => $v)
                            <option value="{{ $k }}" {{ old('status', 'briefing') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium text-gray-700">Início</label><input type="date" name="start_date" value="{{ old('start_date') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></div>
                <div><label class="block text-sm font-medium text-gray-700">Fim</label><input type="date" name="end_date" value="{{ old('end_date') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Orçamento</label>
                <input type="number" step="0.01" name="budget" value="{{ old('budget') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Descrição</label>
                <textarea name="description" rows="3" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">{{ old('description', $template->description) }}</textarea>
            </div>

            @include('partials.custom-fields-form', ['model' => new \App\Domains\Projeto\Models\Projeto])
        </div>
        <div class="mt-6"><button class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700">Criar Projeto</button></div>
    </form>
@endsection
