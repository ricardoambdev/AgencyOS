<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nome</label>
        <input type="text" name="name" value="{{ old('name', optional($template)->name) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Descrição</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">{{ old('description', optional($template)->description) }}</textarea>
    </div>
    <div>
        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', optional($template)->is_active ?? true) ? 'checked' : '' }}>
            Template ativo
        </label>
    </div>

    <div class="border-t border-gray-200 pt-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Tarefas do Template</h3>
        @php
            $taskRows = $template
                ? $template->templateTasks->map(fn($t) => ['title' => $t->title, 'description' => $t->description, 'priority' => $t->priority, 'estimated_hours' => $t->estimated_hours])->toArray()
                : [];
        @endphp
        <div class="space-y-3" x-data="{ rows: @json($taskRows) }">
            <template x-for="(row, i) in rows" :key="i">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-2 items-end border border-gray-100 p-2 rounded">
                    <div><label class="text-xs text-gray-500">Título</label><input type="text" x-model="row.title" :name="'tasks['+i+'][title]'" class="mt-1 w-full rounded-md border border-gray-300 px-2 py-1 text-sm"></div>
                    <div><label class="text-xs text-gray-500">Descrição</label><input type="text" x-model="row.description" :name="'tasks['+i+'][description]'" class="mt-1 w-full rounded-md border border-gray-300 px-2 py-1 text-sm"></div>
                    <div><label class="text-xs text-gray-500">Prioridade</label><input type="text" x-model="row.priority" :name="'tasks['+i+'][priority]'" class="mt-1 w-full rounded-md border border-gray-300 px-2 py-1 text-sm"></div>
                    <div class="flex items-end gap-2">
                        <div class="flex-1"><label class="text-xs text-gray-500">Horas est.</label><input type="number" step="0.25" x-model="row.estimated_hours" :name="'tasks['+i+'][estimated_hours]'" class="mt-1 w-full rounded-md border border-gray-300 px-2 py-1 text-sm"></div>
                        <button type="button" @click="rows.splice(i, 1)" class="text-red-500 text-sm pb-2">x</button>
                    </div>
                </div>
            </template>
            <button type="button" @click="rows.push({title:'', description:'', priority:'', estimated_hours:0})" class="text-sm text-indigo-600">+ Adicionar tarefa</button>
        </div>
    </div>

    <div class="border-t border-gray-200 pt-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Checklist do Template</h3>
        @php
            $checklistRows = $template && is_array($template->checklist)
                ? array_map(fn($c) => ['label' => $c], $template->checklist)
                : [];
        @endphp
        <div class="space-y-2" x-data="{ items: @json($checklistRows) }">
            <template x-for="(item, i) in items" :key="i">
                <div class="flex items-center gap-2 border border-gray-100 p-2 rounded">
                    <input type="text" x-model="item.label" :name="'checklist['+i+']'" class="mt-1 w-full rounded-md border border-gray-300 px-2 py-1 text-sm" placeholder="Item do checklist">
                    <button type="button" @click="items.splice(i, 1)" class="text-red-500 text-sm">x</button>
                </div>
            </template>
            <button type="button" @click="items.push({label:''})" class="text-sm text-indigo-600">+ Adicionar item</button>
        </div>
    </div>
</div>
