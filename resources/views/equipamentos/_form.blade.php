@php
    $tipos = App\Domains\Equipamento\Controllers\EquipamentoController::tipos();
    $status = App\Domains\Equipamento\Controllers\EquipamentoController::status();
@endphp

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nome</label>
        <input type="text" name="name" value="{{ old('name', optional($equipamento)->name) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Tipo</label>
            <select name="type" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @foreach($tipos as $k => $l)
                    <option value="{{ $k }}" {{ old('type', optional($equipamento)->type ?? 'outro') == $k ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @foreach($status as $k => $l)
                    <option value="{{ $k }}" {{ old('status', optional($equipamento)->status ?? 'disponivel') == $k ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Responsável</label>
            <select name="owner_id" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                <option value="">—</option>
                @foreach($owners as $o)
                    <option value="{{ $o->id }}" {{ old('owner_id', optional($equipamento)->owner_id) == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Número de série</label>
            <input type="text" name="serial" value="{{ old('serial', optional($equipamento)->serial) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Data de compra</label>
            <input type="date" name="purchase_date" value="{{ old('purchase_date', optional(optional($equipamento)->purchase_date)->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Descrição / Observações</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">{{ old('description', optional($equipamento)->description) }}</textarea>
    </div>

    @include('partials.tags-input', ['model' => $equipamento ?? null])
</div>
