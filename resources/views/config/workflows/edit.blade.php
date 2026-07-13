@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('config.workflows.index') }}" class="text-sm text-indigo-600">&larr; Workflows</a>
        <h1 class="text-2xl font-bold text-gray-800">{{ $workflow ? 'Editar' : 'Novo' }} Workflow</h1>
    </div>

    <form method="POST" action="{{ $workflow ? route('config.workflows.update', $workflow) : route('config.workflows.store') }}"
          x-data="wfForm()" class="bg-white shadow rounded-lg p-6 space-y-6">
        @csrf
        @if($workflow) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-gray-700">Nome</label>
            <input type="text" name="name" value="{{ old('name', $workflow->name ?? '') }}"
                   class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tipo de entidade</label>
            <select name="entity_type" x-model="entityType" @change="onEntityChange()"
                    class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
                <option value="">Selecione...</option>
                @foreach($entityTypes as $type => $label)
                    <option value="{{ $type }}" {{ $entityType == $type ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status (estados)</label>
            <div class="space-y-2">
                <template x-for="(s, i) in states" :key="i">
                    <div class="flex gap-2 items-center">
                        <input type="text" x-model="s.key" :name="`states[${i}][key]`" placeholder="chave (ex: producao)" class="w-1/3 rounded-md border border-gray-300 px-2 py-1 text-sm">
                        <input type="text" x-model="s.label" :name="`states[${i}][label]`" placeholder="Rótulo (ex: Produção)" class="flex-1 rounded-md border border-gray-300 px-2 py-1 text-sm">
                        <button type="button" @click="removeState(i)" class="text-red-600 text-sm">Remover</button>
                    </div>
                </template>
            </div>
            <button type="button" @click="addState()" class="mt-2 text-sm text-indigo-600">+ Adicionar status</button>
            <p class="text-xs text-gray-400 mt-1">Ao trocar o tipo de entidade, os status padrão são pré-carregados.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Transições permitidas</label>
            <div class="space-y-2">
                <template x-for="(t, i) in transitions" :key="i">
                    <div class="flex gap-2 items-center">
                        <select x-model="t.from" :name="`transitions[${i}][from]`" class="w-1/3 rounded-md border border-gray-300 px-2 py-1 text-sm">
                            <option value="*">* (qualquer)</option>
                            <template x-for="s in states" :key="s.key">
                                <option :value="s.key" x-text="s.key"></option>
                            </template>
                        </select>
                        <span class="text-gray-400">&rarr;</span>
                        <select x-model="t.to" :name="`transitions[${i}][to]`" class="w-1/3 rounded-md border border-gray-300 px-2 py-1 text-sm">
                            <template x-for="s in states" :key="s.key">
                                <option :value="s.key" x-text="s.key"></option>
                            </template>
                        </select>
                        <button type="button" @click="removeTransition(i)" class="text-red-600 text-sm">Remover</button>
                    </div>
                </template>
            </div>
            <button type="button" @click="addTransition()" class="mt-2 text-sm text-indigo-600">+ Adicionar transição</button>
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" name="active" value="1" {{ old('active', $workflow ? $workflow->active : true) ? 'checked' : '' }}>
            Ativo
        </label>

        <div>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Salvar</button>
        </div>
    </form>

    <script>
        function wfForm() {
            return {
                entityType: @json($entityType),
                statuses: @json($allStatuses),
                states: [],
                transitions: [],
                init() {
                    const def = @json($definition);
                    if (def.states && Object.keys(def.states).length) {
                        this.states = Object.entries(def.states).map(([k, l]) => ({ key: k, label: l }));
                    } else if (this.entityType && this.statuses[this.entityType]) {
                        this.states = Object.entries(this.statuses[this.entityType]).map(([k, l]) => ({ key: k, label: l }));
                    }
                    if (def.transitions) {
                        this.transitions = def.transitions.map(t => ({ from: t.from, to: t.to }));
                    }
                },
                onEntityChange() {
                    if (this.states.length === 0 && this.statuses[this.entityType]) {
                        this.states = Object.entries(this.statuses[this.entityType]).map(([k, l]) => ({ key: k, label: l }));
                    }
                },
                addState() { this.states.push({ key: '', label: '' }); },
                removeState(i) { this.states.splice(i, 1); },
                addTransition() { this.transitions.push({ from: '*', to: '' }); },
                removeTransition(i) { this.transitions.splice(i, 1); },
            };
        }
    </script>
@endsection
