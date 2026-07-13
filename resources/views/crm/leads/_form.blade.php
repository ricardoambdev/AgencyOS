<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nome *</label>
        <input type="text" name="name" value="{{ old('name', $lead->name ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">E-mail</label>
            <input type="email" name="email" value="{{ old('email', $lead->email ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Telefone</label>
            <input type="text" name="phone" value="{{ old('phone', $lead->phone ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Empresa</label>
            <input type="text" name="company_name" value="{{ old('company_name', $lead->company_name ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Origem</label>
            <input type="text" name="source" value="{{ old('source', $lead->source ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" placeholder="Site, Indicação...">
        </div>
    </div>
    <div class="grid grid-cols-3 gap-4">
        <div>
            @php
                $leadStatuses = \App\Core\Models\WorkflowState::resolve(\App\Domains\Crm\Models\Lead::class);
                $leadMeta = \App\Core\Models\WorkflowState::meta(\App\Domains\Crm\Models\Lead::class);
                $initial = collect($leadMeta)->filter(fn ($m) => $m['is_initial'])->keys()->first()
                    ?? array_key_first($leadStatuses);
                $current = old('status', $lead->status ?? $initial);
            @endphp
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @foreach($leadStatuses as $v => $l)
                    <option value="{{ $v }}" {{ $current == $v ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Valor (R$)</label>
            <input type="number" step="0.01" name="value" value="{{ old('value', $lead->value ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Responsável</label>
            <select name="owner_id" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                <option value="">-</option>
                @foreach($owners as $o)
                    <option value="{{ $o->id }}" {{ ($lead->owner_id ?? '') == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Observações</label>
        <textarea name="notes" rows="3" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">{{ old('notes', $lead->notes ?? '') }}</textarea>
    </div>

    @include('partials.tags-input', ['model' => $lead ?? null])
</div>
