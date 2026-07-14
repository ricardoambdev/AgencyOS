<div class="space-y-4">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <x-ui.field label="Cliente" name="client_id">
            <x-ui.select name="client_id" :options="['' => '—'] + $clientes->pluck('name', 'id')->toArray()" :selected="old('client_id', optional($contrato)->client_id)" />
        </x-ui.field>
        <x-ui.field label="Responsável" name="responsavel_id">
            <x-ui.select name="responsavel_id" :options="['' => '—'] + $users->pluck('name', 'id')->toArray()" :selected="old('responsavel_id', optional($contrato)->responsavel_id ?? auth()->id())" />
        </x-ui.field>
    </div>

    <x-ui.field label="Título" name="title" required>
        <x-ui.input name="title" :value="old('title', optional($contrato)->title)" />
    </x-ui.field>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <x-ui.field label="Tipo" name="type">
            <x-ui.select name="type" :options="['fixed' => 'Preço fixo', 'hourly' => 'Por hora', 'retainer' => 'Retainer']" :selected="old('type', optional($contrato)->type ?? 'fixed')" />
        </x-ui.field>
        <x-ui.field label="Valor" name="value">
            <x-ui.input type="number" step="0.01" name="value" :value="old('value', optional($contrato)->value)" />
        </x-ui.field>
        <x-ui.field label="Moeda" name="currency">
            <x-ui.input name="currency" :value="old('currency', optional($contrato)->currency ?? 'BRL')" />
        </x-ui.field>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <x-ui.field label="Início" name="start_date">
            <x-ui.input type="date" name="start_date" :value="old('start_date', optional(optional($contrato)->start_date)->format('Y-m-d'))" />
        </x-ui.field>
        <x-ui.field label="Fim" name="end_date">
            <x-ui.input type="date" name="end_date" :value="old('end_date', optional(optional($contrato)->end_date)->format('Y-m-d'))" />
        </x-ui.field>
        <x-ui.field label="Assinado em" name="signed_at">
            <x-ui.input type="date" name="signed_at" :value="old('signed_at', optional(optional($contrato)->signed_at)->format('Y-m-d'))" />
        </x-ui.field>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <x-ui.field label="Status" name="status">
            <x-ui.select name="status" :options="['rascunho' => 'Rascunho', 'ativo' => 'Ativo', 'encerrado' => 'Encerrado', 'cancelado' => 'Cancelado']" :selected="old('status', optional($contrato)->status ?? 'rascunho')" />
        </x-ui.field>
        <x-ui.field label="Renovação" name="renewal_type">
            <x-ui.select name="renewal_type" :options="['none' => 'Nenhuma', 'mensal' => 'Mensal', 'anual' => 'Anual']" :selected="old('renewal_type', optional($contrato)->renewal_type ?? 'none')" />
        </x-ui.field>
        <x-ui.field label="Próx. renovação" name="renewal_date">
            <x-ui.input type="date" name="renewal_date" :value="old('renewal_date', optional(optional($contrato)->renewal_date)->format('Y-m-d'))" />
        </x-ui.field>
    </div>

    <x-ui.field label="Descrição" name="description">
        <x-ui.textarea name="description" :value="old('description', optional($contrato)->description)" rows="3" />
    </x-ui.field>

    <x-ui.field label="Documentos" name="files">
        <input type="file" name="files[]" multiple class="mt-1 w-full text-sm text-app">
        @if(optional($contrato)->attachments)
            <ul class="mt-2 space-y-1 text-sm">
                @foreach($contrato->attachments as $a)
                    <li><a href="{{ route('comercial.attachments.download', [$contrato, $a]) }}" class="text-primary-700 hover:underline dark:text-primary-300">{{ $a->name }}</a></li>
                @endforeach
            </ul>
        @endif
    </x-ui.field>

    @include('partials.tags-input', ['model' => $contrato ?? null])
</div>
