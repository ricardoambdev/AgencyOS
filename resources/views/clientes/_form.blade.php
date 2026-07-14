<div class="space-y-4">
    <x-ui.field label="Nome" name="name" required>
        <x-ui.input name="name" :value="old('name', $cliente->name ?? '')" />
    </x-ui.field>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-ui.field label="E-mail" name="email">
            <x-ui.input name="email" type="email" :value="old('email', $cliente->email ?? '')" />
        </x-ui.field>
        <x-ui.field label="Telefone" name="phone">
            <x-ui.input name="phone" :value="old('phone', $cliente->phone ?? '')" />
        </x-ui.field>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-ui.field label="Documento (CNPJ/CPF)" name="document">
            <x-ui.input name="document" :value="old('document', $cliente->document ?? '')" />
        </x-ui.field>
        <x-ui.field label="Tipo" name="type">
            <x-ui.select name="type" :options="['company' => 'Empresa', 'person' => 'Pessoa']" :selected="old('type', $cliente->type ?? 'company')" />
        </x-ui.field>
    </div>

    <x-ui.field label="Responsável" name="owner_id">
        <x-ui.select name="owner_id" :options="['' => '-'] + $owners->pluck('name', 'id')->toArray()" :selected="old('owner_id', $cliente->owner_id ?? '')" />
    </x-ui.field>

    <x-ui.field label="Endereço" name="address">
        <x-ui.textarea name="address" :value="old('address', $cliente->address ?? '')" rows="2" />
    </x-ui.field>

    @include('partials.tags-input', ['model' => $cliente ?? null])
</div>
