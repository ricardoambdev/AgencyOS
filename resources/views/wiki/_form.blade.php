@php
    $categorias = App\Domains\Wiki\Controllers\ArtigoController::categorias();
    $status = App\Domains\Wiki\Controllers\ArtigoController::status();
@endphp

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Título</label>
        <input type="text" name="title" value="{{ old('title', optional($artigo)->title) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Categoria</label>
            <select name="category" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @foreach($categorias as $k => $l)
                    <option value="{{ $k }}" {{ old('category', optional($artigo)->category ?? 'geral') == $k ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @foreach($status as $k => $l)
                    <option value="{{ $k }}" {{ old('status', optional($artigo)->status ?? 'rascunho') == $k ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="client_visible" value="1" {{ old('client_visible', optional($artigo)->client_visible) ? 'checked' : '' }}>
                Visível no Portal do Cliente
            </label>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Conteúdo</label>
        <textarea name="body" rows="12" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 font-mono text-sm">{{ old('body', optional($artigo)->body) }}</textarea>
        <p class="text-xs text-gray-400 mt-1">Texto simples (quebras de linha preservadas).</p>
    </div>
</div>
