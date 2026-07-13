@php
    $tipos = App\Domains\Producao\Controllers\EntregavelController::tipos();
    $status = App\Domains\Producao\Controllers\EntregavelController::status();
@endphp

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Projeto</label>
        <select name="project_id" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
            <option value="">Selecione</option>
                @foreach($projetos as $p)
                    <option value="{{ $p->id }}" {{ old('project_id', optional($entregavel)->project_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Nome do entregável</label>
            <input type="text" name="name" value="{{ old('name', optional($entregavel)->name) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Tipo</label>
            <select name="type" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @foreach($tipos as $k => $l)
                    <option value="{{ $k }}" {{ old('type', optional($entregavel)->type ?? 'outro') == $k ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @foreach($status as $k => $l)
                    <option value="{{ $k }}" {{ old('status', optional($entregavel)->status ?? 'briefing') == $k ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Responsável</label>
            <select name="owner_id" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                <option value="">—</option>
                @foreach($owners as $o)
                    <option value="{{ $o->id }}" {{ old('owner_id', optional($entregavel)->owner_id) == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Data de entrega</label>
        <input type="date" name="due_date" value="{{ old('due_date', optional(optional($entregavel)->due_date)->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Descrição / Briefing</label>
        <textarea name="description" rows="4" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">{{ old('description', optional($entregavel)->description) }}</textarea>
    </div>

    <label class="flex items-center gap-2 text-sm text-gray-700">
        <input type="checkbox" name="client_visible" value="1" {{ old('client_visible', optional($entregavel)->client_visible) ? 'checked' : '' }}>
        Visível no Portal do Cliente
    </label>

    <div>
        <label class="block text-sm font-medium text-gray-700">Anexos</label>
        <input type="file" name="files[]" multiple class="mt-1 w-full text-sm">
        <p class="text-xs text-gray-400 mt-1">Opcionais. Até 10MB por arquivo.</p>
    </div>
</div>
