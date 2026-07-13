<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Cliente *</label>
        <select name="client_id" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
            <option value="">Selecione...</option>
            @foreach($clientes as $c)<option value="{{ $c->id }}" {{ ($projeto->client_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
        </select>
    </div>
    <div><label class="block text-sm font-medium text-gray-700">Nome *</label>
        <input type="text" name="name" value="{{ old('name', $projeto->name ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required></div>
    <div class="grid grid-cols-2 gap-4">
        <div><label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @foreach(['briefing'=>'Briefing','planejamento'=>'Planejamento','producao'=>'Produção','revisao'=>'Revisão','cliente'=>'Com Cliente','finalizado'=>'Finalizado'] as $v=>$l)
                    <option value="{{ $v }}" {{ ($projeto->status ?? 'briefing') == $v ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select></div>
        <div><label class="block text-sm font-medium text-gray-700">Responsável</label>
            <select name="owner_id" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                <option value="">-</option>
                @foreach($owners as $o)<option value="{{ $o->id }}" {{ ($projeto->owner_id ?? '') == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>@endforeach
            </select></div>
    </div>
    <div class="grid grid-cols-3 gap-4">
        <div><label class="block text-sm font-medium text-gray-700">Início</label>
            <input type="date" name="start_date" value="{{ old('start_date', $projeto->start_date ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></div>
        <div><label class="block text-sm font-medium text-gray-700">Término</label>
            <input type="date" name="end_date" value="{{ old('end_date', $projeto->end_date ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></div>
        <div><label class="block text-sm font-medium text-gray-700">Orçamento (R$)</label>
            <input type="number" step="0.01" name="budget" value="{{ old('budget', $projeto->budget ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></div>
    </div>
    <div><label class="block text-sm font-medium text-gray-700">Descrição</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">{{ old('description', $projeto->description ?? '') }}</textarea></div>
</div>
