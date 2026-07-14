<x-admin.layout :title="'Importar ' . $def->label">
    <div class="mx-auto max-w-xl">
        <a href="{{ route('admin.resource.index', $resource) }}" class="mb-4 inline-flex items-center gap-1 text-sm text-muted hover:text-primary-700 dark:hover:text-primary-300">
            <i data-lucide="arrow-left" class="sm"></i> Voltar
        </a>

        <x-ui.card title="Importar {{ $def->label }}">
            <p class="mb-4 text-sm text-muted">Envie um CSV com cabeçalhos correspondentes às colunas do recurso. O <code>company_id</code> é preenchido automaticamente.</p>
            <form method="POST" action="{{ route('admin.resource.import.store', $resource) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <x-ui.field label="Arquivo CSV" name="file">
                    <input type="file" name="file" accept=".csv,.txt" class="block w-full text-sm text-neutral-600 file:mr-3 file:rounded-xl file:border-0 file:bg-primary-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-primary-800 hover:file:bg-primary-200" required>
                </x-ui.field>
                <div class="flex justify-end">
                    <x-ui.button type="submit" icon="upload">Importar</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-admin.layout>
