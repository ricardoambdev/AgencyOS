<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nome *</label>
        <input type="text" name="name" value="{{ old('name', $cliente->name ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div><label class="block text-sm font-medium text-gray-700">E-mail</label>
            <input type="email" name="email" value="{{ old('email', $cliente->email ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></div>
        <div><label class="block text-sm font-medium text-gray-700">Telefone</label>
            <input type="text" name="phone" value="{{ old('phone', $cliente->phone ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div><label class="block text-sm font-medium text-gray-700">Documento (CNPJ/CPF)</label>
            <input type="text" name="document" value="{{ old('document', $cliente->document ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></div>
        <div><label class="block text-sm font-medium text-gray-700">Tipo</label>
            <select name="type" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                <option value="company" {{ ($cliente->type ?? 'company') == 'company' ? 'selected' : '' }}>Empresa</option>
                <option value="person" {{ ($cliente->type ?? '') == 'person' ? 'selected' : '' }}>Pessoa</option>
            </select></div>
    </div>
    <div><label class="block text-sm font-medium text-gray-700">Responsável</label>
        <select name="owner_id" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
            <option value="">-</option>
            @foreach($owners as $o)<option value="{{ $o->id }}" {{ ($cliente->owner_id ?? '') == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>@endforeach
        </select></div>
    <div><label class="block text-sm font-medium text-gray-700">Endereço</label>
        <textarea name="address" rows="2" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">{{ old('address', $cliente->address ?? '') }}</textarea></div>

    @include('partials.tags-input', ['model' => $cliente ?? null])
</div>
