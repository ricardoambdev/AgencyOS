@php
    $tipos = App\Domains\Producao\Controllers\EntregavelController::tipos();
    $status = App\Domains\Producao\Controllers\EntregavelController::status();
@endphp

<div class="space-y-4">
    <x-ui.field label="Projeto" name="project_id" required>
        <x-ui.select name="project_id" :options="['' => 'Selecione'] + $projetos->pluck('name', 'id')->toArray()" :selected="old('project_id', optional($entregavel)->project_id)" />
    </x-ui.field>

    <x-ui.field label="Nome do entregável" name="name" required>
        <x-ui.input name="name" :value="old('name', optional($entregavel)->name)" />
    </x-ui.field>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <x-ui.field label="Tipo" name="type">
            <x-ui.select name="type" :options="$tipos" :selected="old('type', optional($entregavel)->type ?? 'outro')" />
        </x-ui.field>
        <x-ui.field label="Status" name="status">
            <x-ui.select name="status" :options="$status" :selected="old('status', optional($entregavel)->status ?? 'briefing')" />
        </x-ui.field>
        <x-ui.field label="Responsável" name="owner_id">
            <x-ui.select name="owner_id" :options="['' => '—'] + $owners->pluck('name', 'id')->toArray()" :selected="old('owner_id', optional($entregavel)->owner_id)" />
        </x-ui.field>
    </div>

    <x-ui.field label="Data de entrega" name="due_date">
        <x-ui.input type="date" name="due_date" :value="old('due_date', optional(optional($entregavel)->due_date)->format('Y-m-d'))" />
    </x-ui.field>

    <x-ui.field label="Descrição / Briefing" name="description">
        <x-ui.textarea name="description" :value="old('description', optional($entregavel)->description)" rows="4" />
    </x-ui.field>

    <label class="flex items-center gap-2 text-sm text-app">
        <input type="checkbox" name="client_visible" value="1" {{ old('client_visible', optional($entregavel)->client_visible) ? 'checked' : '' }} class="h-4 w-4 rounded border-border text-brand focus:ring-[var(--ring)]">
        Visível no Portal do Cliente
    </label>

    <x-ui.field label="Anexos" name="files">
        <input type="file" name="files[]" multiple class="mt-1 w-full text-sm text-app">
        <p class="mt-1 text-xs text-muted">Opcionais. Até 10MB por arquivo.</p>
    </x-ui.field>
</div>
