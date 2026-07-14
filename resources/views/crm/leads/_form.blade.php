@php
    $leadStatuses = \App\Core\Models\WorkflowState::resolve(\App\Domains\Crm\Models\Lead::class);
    $leadMeta = \App\Core\Models\WorkflowState::meta(\App\Domains\Crm\Models\Lead::class);
    $initial = collect($leadMeta)->filter(fn ($m) => $m['is_initial'])->keys()->first()
        ?? array_key_first($leadStatuses);
    $current = old('status', $lead->status ?? $initial);
@endphp

<div class="space-y-4">
    <x-ui.field label="Nome" name="name" required>
        <x-ui.input name="name" :value="old('name', $lead->name ?? '')" placeholder="Nome do lead" />
    </x-ui.field>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-ui.field label="E-mail" name="email">
            <x-ui.input name="email" type="email" :value="old('email', $lead->email ?? '')" placeholder="email@empresa.com" />
        </x-ui.field>
        <x-ui.field label="Telefone" name="phone">
            <x-ui.input name="phone" :value="old('phone', $lead->phone ?? '')" placeholder="(00) 00000-0000" />
        </x-ui.field>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-ui.field label="Empresa" name="company_name">
            <x-ui.input name="company_name" :value="old('company_name', $lead->company_name ?? '')" />
        </x-ui.field>
        <x-ui.field label="Origem" name="source">
            <x-ui.input name="source" :value="old('source', $lead->source ?? '')" placeholder="Site, Indicação..." />
        </x-ui.field>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-ui.field label="Status" name="status">
            <x-ui.select name="status" :options="$leadStatuses" :selected="$current" />
        </x-ui.field>
        <x-ui.field label="Valor (R$)" name="value">
            <x-ui.input name="value" type="number" step="0.01" :value="old('value', $lead->value ?? '')" />
        </x-ui.field>
        <x-ui.field label="Responsável" name="owner_id">
            <x-ui.select name="owner_id" :options="['' => '-'] + $owners->pluck('name', 'id')->toArray()" :selected="old('owner_id', $lead->owner_id ?? '')" />
        </x-ui.field>
    </div>

    <x-ui.field label="Observações" name="notes">
        <x-ui.textarea name="notes" :value="old('notes', $lead->notes ?? '')" rows="3" />
    </x-ui.field>

    @include('partials.tags-input', ['model' => $lead ?? null])
</div>
