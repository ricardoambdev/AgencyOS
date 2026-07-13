<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Entidade</label>
            <select name="entity_type" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
                @foreach($entityTypes as $class => $label)
                    <option value="{{ $class }}" {{ old('entity_type', optional($customField)->entity_type) == $class ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tipo</label>
            <select name="type" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
                @foreach($types as $t)
                    <option value="{{ $t }}" {{ old('type', optional($customField)->type ?? 'text') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nome (chave, snake_case)</label>
            <input type="text" name="name" value="{{ old('name', optional($customField)->name) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Rótulo</label>
            <input type="text" name="label" value="{{ old('label', optional($customField)->label) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
        </div>
    </div>

    @php
        $opts = [];
        if ($customField && $customField->options) {
            foreach ($customField->options as $k => $v) {
                $opts[] = ['value' => $k, 'label' => $v];
            }
        }
    @endphp
    <div x-data="{ opts: @json($opts) }">
        <label class="block text-sm font-medium text-gray-700">Opções (para select / multiselect / radio)</label>
        <div class="space-y-2">
            <template x-for="(opt, i) in (opts.length ? opts : [{'value':'','label':''}])" :key="i">
                <div class="flex gap-2">
                    <input type="text" x-model="opts[i].value" :name="'options['+i+'][value]'" placeholder="valor" class="w-1/2 rounded-md border border-gray-300 px-2 py-1 text-sm">
                    <input type="text" x-model="opts[i].label" :name="'options['+i+'][label]'" placeholder="rótulo" class="w-1/2 rounded-md border border-gray-300 px-2 py-1 text-sm">
                    <button type="button" @click="opts.splice(i, 1)" class="text-red-500 text-sm">x</button>
                </div>
            </template>
            <button type="button" @click="opts.push({value:'', label:''})" class="text-sm text-indigo-600">+ Adicionar opção</button>
        </div>
        <p class="text-xs text-gray-400 mt-1">Preencha apenas se o tipo for de seleção.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_filterable" value="1" {{ old('is_filterable', optional($customField)->is_filterable ?? false) ? 'checked' : '' }}>
                Filtravel
            </label>
        </div>
        <div>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_required" value="1" {{ old('is_required', optional($customField)->is_required ?? false) ? 'checked' : '' }}>
                Obrigatório
            </label>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Ordem</label>
            <input type="number" name="order" value="{{ old('order', optional($customField)->order ?? 0) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
        </div>
    </div>
</div>
