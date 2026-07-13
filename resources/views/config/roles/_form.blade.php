<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nome</label>
            <input type="text" name="name" value="{{ old('name', optional($role)->name) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', optional($role)->slug) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Permissões</label>
        <div class="space-y-4">
            @foreach($capabilities as $group => $caps)
                <fieldset class="border border-gray-200 rounded p-3">
                    <legend class="text-xs font-semibold text-gray-500 px-1">{{ $group }}</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach($caps as $key => $label)
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="capabilities[]" value="{{ $key }}"
                                    {{ in_array($key, old('capabilities', optional($role)->capabilities ?? [])) ? 'checked' : '' }}>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            @endforeach
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Membros com esta função</label>
        <select name="users[]" multiple class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" size="5">
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ (isset($assigned) && in_array($u->id, $assigned)) ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
            @endforeach
        </select>
        <p class="text-xs text-gray-400 mt-1">Segure Ctrl/Cmd para selecionar múltiplos.</p>
    </div>
</div>
