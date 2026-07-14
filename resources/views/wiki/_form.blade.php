@php
    $categorias = App\Domains\Wiki\Controllers\ArtigoController::categorias();
    $status = App\Domains\Wiki\Controllers\ArtigoController::status();
@endphp

<div class="space-y-4">
    <x-ui.field label="Título" name="title" required>
        <x-ui.input name="title" :value="old('title', optional($artigo)->title)" />
    </x-ui.field>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <x-ui.field label="Categoria" name="category">
            <x-ui.select name="category" :options="$categorias" :selected="old('category', optional($artigo)->category ?? 'geral')" />
        </x-ui.field>
        <x-ui.field label="Status" name="status">
            <x-ui.select name="status" :options="$status" :selected="old('status', optional($artigo)->status ?? 'rascunho')" />
        </x-ui.field>
        <div class="flex items-end">
            <label class="flex items-center gap-2 text-sm text-app">
                <input type="checkbox" name="client_visible" value="1" {{ old('client_visible', optional($artigo)->client_visible) ? 'checked' : '' }} class="h-4 w-4 rounded border-border text-brand focus:ring-[var(--ring)]">
                Visível no Portal do Cliente
            </label>
        </div>
    </div>

    <x-ui.field label="Conteúdo" name="body">
        <x-ui.textarea name="body" :value="old('body', optional($artigo)->body)" rows="12" class="font-mono text-sm" />
        <p class="mt-1 text-xs text-muted">Texto simples (quebras de linha preservadas).</p>
    </x-ui.field>
</div>
