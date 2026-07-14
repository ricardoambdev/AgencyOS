@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('config.automations.index') }}" class="text-sm text-primary-700 dark:text-primary-300">&larr; Automações</a>
        <h1 class="text-2xl font-bold text-[var(--text)]">{{ $automation ? 'Editar' : 'Nova' }} Automação</h1>
    </div>

    <form method="POST" action="{{ $automation ? route('config.automations.update', $automation) : route('config.automations.store') }}"
          x-data="autoForm()" class="bg-[var(--surface)] shadow rounded-lg p-6 space-y-6">
        @csrf
        @if($automation) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-[var(--text)]">Nome</label>
            <input type="text" name="name" value="{{ old('name', $automation->name ?? '') }}"
                   class="mt-1 w-full rounded-md border border-[var(--border)] px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text)]">Evento gatilho</label>
            <select name="event" x-model="event" class="mt-1 w-full rounded-md border border-[var(--border)] px-3 py-2" required>
                <option value="">Selecione...</option>
                @foreach($events as $ev => $lbl)
                    <option value="{{ $ev }}" {{ old('event', $automation->event ?? '') == $ev ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text)] mb-2">Condições (opcional — todas devem ser satisfeitas)</label>
            <div class="space-y-2">
                <template x-for="(c, i) in conditions" :key="i">
                    <div class="flex gap-2 items-center">
                        <select x-model="c.field" :name="`conditions[${i}][field]`" class="w-1/3 rounded-md border border-[var(--border)] px-2 py-1 text-sm">
                            <option value="">Campo...</option>
                            <template x-for="(lbl, f) in fields[event]" :key="f">
                                <option :value="f" x-text="lbl"></option>
                            </template>
                        </select>
                        <select x-model="c.operator" :name="`conditions[${i}][operator]`" class="w-24 rounded-md border border-[var(--border)] px-2 py-1 text-sm">
                            <option value="=">=</option>
                            <option value="!=">!=</option>
                            <option value=">">&gt;</option>
                            <option value="<">&lt;</option>
                        </select>
                        <input type="text" x-model="c.value" :name="`conditions[${i}][value]`" placeholder="valor" class="flex-1 rounded-md border border-[var(--border)] px-2 py-1 text-sm">
                        <button type="button" @click="removeCondition(i)" class="text-red-600 text-sm">Remover</button>
                    </div>
                </template>
            </div>
            <button type="button" @click="addCondition()" class="mt-2 text-sm text-primary-700 dark:text-primary-300">+ Adicionar condição</button>
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text)] mb-2">Ações</label>
            <div class="space-y-3">
                <template x-for="(a, i) in actions" :key="i">
                    <div class="border border-[var(--border)] rounded-lg p-3 space-y-2">
                        <div class="flex items-center gap-2">
                            <select x-model="a.type" :name="`actions[${i}][type]`" class="w-1/3 rounded-md border border-[var(--border)] px-2 py-1 text-sm">
                                @foreach($actionTypes as $t => $lbl)
                                    <option value="{{ $t }}">{{ $lbl }}</option>
                                @endforeach
                            </select>
                            <button type="button" @click="removeAction(i)" class="text-red-600 text-sm ml-auto">Remover</button>
                        </div>
                        <div x-show="a.type === 'notify' || a.type === 'timeline'" class="grid grid-cols-2 gap-2">
                            <input type="text" x-model="a.title" :name="`actions[${i}][title]`" placeholder="Título / mensagem" class="rounded-md border border-[var(--border)] px-2 py-1 text-sm">
                            <input type="text" x-model="a.body" :name="`actions[${i}][body]`" placeholder="Corpo (notify)" class="rounded-md border border-[var(--border)] px-2 py-1 text-sm">
                            <input type="text" x-model="a.link" :name="`actions[${i}][link]`" placeholder="Link (opcional)" class="rounded-md border border-[var(--border)] px-2 py-1 text-sm">
                            <input type="number" x-model="a.user_id" :name="`actions[${i}][user_id]`" placeholder="ID usuário (vazio=responsável)" class="rounded-md border border-[var(--border)] px-2 py-1 text-sm">
                        </div>
                        <div x-show="a.type === 'webhook'" class="grid grid-cols-1 gap-2">
                            <input type="url" x-model="a.url" :name="`actions[${i}][url]`" placeholder="https://..." class="rounded-md border border-[var(--border)] px-2 py-1 text-sm">
                        </div>
                    </div>
                </template>
            </div>
            <button type="button" @click="addAction()" class="mt-2 text-sm text-primary-700 dark:text-primary-300">+ Adicionar ação</button>
        </div>

        <label class="flex items-center gap-2 text-sm text-[var(--text)]">
            <input type="checkbox" name="active" value="1" {{ old('active', $automation ? $automation->active : true) ? 'checked' : '' }}>
            Ativo
        </label>

        <div>
            <button class="bg-primary-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary-700">Salvar</button>
        </div>
    </form>

    <script>
        function autoForm() {
            return {
                event: @json(old('event', $automation->event ?? '')),
                fields: @json($fieldCatalog),
                conditions: [],
                actions: [],
                init() {
                    const def = @json($automation ? ['conditions' => $automation->conditions, 'actions' => $automation->actions] : ['conditions' => [], 'actions' => []]);
                    this.conditions = def.conditions && def.conditions.length ? def.conditions : [];
                    this.actions = def.actions && def.actions.length ? def.actions.map(a => ({ type: a.type, title: a.title ?? '', body: a.body ?? '', link: a.link ?? '', user_id: a.user_id ?? '', url: a.url ?? '' })) : [];
                },
                addCondition() { this.conditions.push({ field: '', operator: '=', value: '' }); },
                removeCondition(i) { this.conditions.splice(i, 1); },
                addAction() { this.actions.push({ type: 'notify', title: '', body: '', link: '', user_id: '', url: '' }); },
                removeAction(i) { this.actions.splice(i, 1); },
            };
        }
    </script>
@endsection
