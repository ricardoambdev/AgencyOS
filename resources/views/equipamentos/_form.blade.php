@php
    $tipos = App\Domains\Equipamento\Controllers\EquipamentoController::tipos();
    $status = App\Domains\Equipamento\Controllers\EquipamentoController::status();
@endphp

<div class="space-y-4">
    <x-ui.field label="Nome" name="name" required>
        <x-ui.input name="name" :value="old('name', optional($equipamento)->name)" />
    </x-ui.field>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <x-ui.field label="Tipo" name="type">
            <x-ui.select name="type" :options="$tipos" :selected="old('type', optional($equipamento)->type ?? 'outro')" />
        </x-ui.field>
        <x-ui.field label="Status" name="status">
            <x-ui.select name="status" :options="$status" :selected="old('status', optional($equipamento)->status ?? 'disponivel')" />
        </x-ui.field>
        <x-ui.field label="Responsável" name="owner_id">
            <x-ui.select name="owner_id" :options="['' => '—'] + $owners->pluck('name', 'id')->toArray()" :selected="old('owner_id', optional($equipamento)->owner_id)" />
        </x-ui.field>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <x-ui.field label="Número de série" name="serial">
            <x-ui.input name="serial" :value="old('serial', optional($equipamento)->serial)" />
        </x-ui.field>
        <x-ui.field label="Data de compra" name="purchase_date">
            <x-ui.input type="date" name="purchase_date" :value="old('purchase_date', optional(optional($equipamento)->purchase_date)->format('Y-m-d'))" />
        </x-ui.field>
    </div>

    <x-ui.field label="Descrição / Observações" name="description">
        <x-ui.textarea name="description" :value="old('description', optional($equipamento)->description)" rows="3" />
    </x-ui.field>

    @include('partials.tags-input', ['model' => $equipamento ?? null])
</div>
