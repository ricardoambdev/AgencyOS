<div class="space-y-4">
    <x-ui.field label="Nome" for="name" required>
        <x-ui.input id="name" name="name" :value="old('name', optional($template)->name)" required />
    </x-ui.field>

    <x-ui.field label="Descrição" for="description">
        <x-ui.textarea id="description" name="description" rows="3">{{ old('description', optional($template)->description) }}</x-ui.textarea>
    </x-ui.field>

    <x-ui.field label="Template ativo">
        <x-ui.checkbox name="is_active" value="1" :checked="old('is_active', optional($template)->is_active ?? true)" label="Template ativo" />
    </x-ui.field>

    <div class="border-t border-[var(--border)] pt-4">
        <h3 class="text-sm font-semibold text-[var(--text)] mb-3">Tarefas do Template</h3>
        @php
            $taskRows = $template
                ? $template->templateTasks->map(fn($t) => ['title' => $t->title, 'description' => $t->description, 'priority' => $t->priority, 'estimated_hours' => $t->estimated_hours])->toArray()
                : [];
        @endphp
        <div class="space-y-3" x-data="{ rows: @js($taskRows) }">
            <template x-for="(row, i) in rows" :key="i">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-2 items-end border border-[var(--border)] p-2 rounded">
                    <div><label class="text-xs text-[var(--text-muted)]">Título</label>
                        <input type="text" x-model="row.title" :name="'tasks['+i+'][title]'" class="mt-1 w-full rounded-md border border-[var(--border)] bg-[var(--surface)] px-2 py-1 text-sm text-[var(--text)]"></div>
                    <div><label class="text-xs text-[var(--text-muted)]">Descrição</label>
                        <input type="text" x-model="row.description" :name="'tasks['+i+'][description]'" class="mt-1 w-full rounded-md border border-[var(--border)] bg-[var(--surface)] px-2 py-1 text-sm text-[var(--text)]"></div>
                    <div><label class="text-xs text-[var(--text-muted)]">Prioridade</label>
                        <input type="text" x-model="row.priority" :name="'tasks['+i+'][priority]'" class="mt-1 w-full rounded-md border border-[var(--border)] bg-[var(--surface)] px-2 py-1 text-sm text-[var(--text)]"></div>
                    <div class="flex items-end gap-2">
                        <div class="flex-1"><label class="text-xs text-[var(--text-muted)]">Horas est.</label>
                            <input type="number" step="0.25" x-model="row.estimated_hours" :name="'tasks['+i+'][estimated_hours]'" class="mt-1 w-full rounded-md border border-[var(--border)] bg-[var(--surface)] px-2 py-1 text-sm text-[var(--text)]"></div>
                        <button type="button" @click="rows.splice(i, 1)" class="text-red-500 text-sm pb-2">x</button>
                    </div>
                </div>
            </template>
            <button type="button" @click="rows.push({title:'', description:'', priority:'', estimated_hours:0})" class="text-sm text-primary-700 dark:text-primary-300">+ Adicionar tarefa</button>
        </div>
    </div>

    <div class="border-t border-[var(--border)] pt-4">
        <h3 class="text-sm font-semibold text-[var(--text)] mb-3">Checklist do Template</h3>
        @php
            $checklistRows = $template && is_array($template->checklist)
                ? array_map(fn($c) => ['label' => $c], $template->checklist)
                : [];
        @endphp
        <div class="space-y-2" x-data="{ items: @js($checklistRows) }">
            <template x-for="(item, i) in items" :key="i">
                <div class="flex items-center gap-2 border border-[var(--border)] p-2 rounded">
                    <input type="text" x-model="item.label" :name="'checklist['+i+']'" placeholder="Item do checklist" class="mt-1 w-full rounded-md border border-[var(--border)] bg-[var(--surface)] px-2 py-1 text-sm text-[var(--text)]">
                    <button type="button" @click="items.splice(i, 1)" class="text-red-500 text-sm">x</button>
                </div>
            </template>
            <button type="button" @click="items.push({label:''})" class="text-sm text-primary-700 dark:text-primary-300">+ Adicionar item</button>
        </div>
    </div>
</div>
