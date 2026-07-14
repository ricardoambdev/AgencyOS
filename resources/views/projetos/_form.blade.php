@php
    $projStatuses = \App\Core\Models\WorkflowState::resolve(\App\Domains\Projeto\Models\Projeto::class);
    $projMeta = \App\Core\Models\WorkflowState::meta(\App\Domains\Projeto\Models\Projeto::class);
    $initial = collect($projMeta)->filter(fn ($m) => $m['is_initial'])->keys()->first() ?? array_key_first($projStatuses);
    $current = old('status', $projeto->status ?? $initial);
@endphp

<div class="space-y-4">
    <x-ui.field label="Cliente" name="client_id" required>
        <x-ui.select name="client_id" :options="['' => 'Selecione...'] + $clientes->pluck('name', 'id')->toArray()" :selected="old('client_id', $projeto->client_id ?? '')" />
    </x-ui.field>

    <x-ui.field label="Nome" name="name" required>
        <x-ui.input name="name" :value="old('name', $projeto->name ?? '')" />
    </x-ui.field>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-ui.field label="Status" name="status">
            <x-ui.select name="status" :options="$projStatuses" :selected="$current" />
        </x-ui.field>
        <x-ui.field label="Responsável" name="owner_id">
            <x-ui.select name="owner_id" :options="['' => '-'] + $owners->pluck('name', 'id')->toArray()" :selected="old('owner_id', $projeto->owner_id ?? '')" />
        </x-ui.field>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-ui.field label="Início" name="start_date">
            <x-ui.input type="date" name="start_date" :value="old('start_date', $projeto->start_date ?? '')" />
        </x-ui.field>
        <x-ui.field label="Término" name="end_date">
            <x-ui.input type="date" name="end_date" :value="old('end_date', $projeto->end_date ?? '')" />
        </x-ui.field>
        <x-ui.field label="Orçamento (R$)" name="budget">
            <x-ui.input type="number" step="0.01" name="budget" :value="old('budget', $projeto->budget ?? '')" />
        </x-ui.field>
    </div>

    <x-ui.field label="Descrição" name="description">
        <x-ui.textarea name="description" :value="old('description', $projeto->description ?? '')" rows="3" />
    </x-ui.field>
</div>
