<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Cliente</label>
            <select name="client_id" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                <option value="">—</option>
                @foreach($clientes as $c)
                    <option value="{{ $c->id }}" {{ old('client_id', optional($contrato)->client_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Responsável</label>
            <select name="responsavel_id" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                <option value="">—</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ old('responsavel_id', optional($contrato)->responsavel_id ?? auth()->id()) == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Título</label>
        <input type="text" name="title" value="{{ old('title', optional($contrato)->title) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Tipo</label>
            <select name="type" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @foreach(['fixed' => 'Preço fixo', 'hourly' => 'Por hora', 'retainer' => 'Retainer'] as $k => $v)
                    <option value="{{ $k }}" {{ old('type', optional($contrato)->type ?? 'fixed') == $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Valor</label>
            <input type="number" step="0.01" name="value" value="{{ old('value', optional($contrato)->value) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Moeda</label>
            <input type="text" name="currency" value="{{ old('currency', optional($contrato)->currency ?? 'BRL') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Início</label>
            <input type="date" name="start_date" value="{{ old('start_date', optional(optional($contrato)->start_date)->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Fim</label>
            <input type="date" name="end_date" value="{{ old('end_date', optional(optional($contrato)->end_date)->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Assinado em</label>
            <input type="date" name="signed_at" value="{{ old('signed_at', optional(optional($contrato)->signed_at)->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @foreach(['rascunho' => 'Rascunho', 'ativo' => 'Ativo', 'encerrado' => 'Encerrado', 'cancelado' => 'Cancelado'] as $k => $v)
                    <option value="{{ $k }}" {{ old('status', optional($contrato)->status ?? 'rascunho') == $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Renovação</label>
            <select name="renewal_type" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @foreach(['none' => 'Nenhuma', 'mensal' => 'Mensal', 'anual' => 'Anual'] as $k => $v)
                    <option value="{{ $k }}" {{ old('renewal_type', optional($contrato)->renewal_type ?? 'none') == $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Próx. renovação</label>
            <input type="date" name="renewal_date" value="{{ old('renewal_date', optional(optional($contrato)->renewal_date)->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Descrição</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">{{ old('description', optional($contrato)->description) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Documentos</label>
        <input type="file" name="files[]" multiple class="mt-1 w-full text-sm">
        @if(optional($contrato)->attachments)
            <ul class="mt-2 space-y-1 text-sm">
                @foreach($contrato->attachments as $a)
                    <li><a href="{{ route('comercial.attachments.download', [$contrato, $a]) }}" class="text-indigo-600">{{ $a->name }}</a></li>
                @endforeach
            </ul>
        @endif
    </div>

    @include('partials.tags-input', ['model' => $contrato ?? null])
</div>
