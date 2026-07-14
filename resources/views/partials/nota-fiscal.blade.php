@php
    $anexos = $model->attachments;
@endphp

<x-ui.card>
    <div class="mb-3 flex items-center justify-between">
        <h3 class="font-semibold text-app">Nota Fiscal</h3>
        <span class="text-xs text-muted">{{ $anexos->count() }} anexo(s)</span>
    </div>

    <ul class="mb-4 space-y-2">
        @forelse($anexos as $att)
            <li class="flex items-center justify-between gap-2 rounded-lg border border-[var(--border)] bg-[var(--surface-2)] px-3 py-2">
                <a href="{{ route('attachments.download', $att) }}" class="flex items-center gap-2 text-sm text-primary-700 hover:underline dark:text-primary-300">
                    <i data-lucide="file-text" class="sm"></i> {{ $att->name }}
                </a>
                <form method="POST" action="{{ route('attachments.destroy', $att) }}" class="inline" onsubmit="return confirm('Remover nota fiscal?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-sm text-red-500 hover:underline">Remover</button>
                </form>
            </li>
        @empty
            <li class="text-sm text-muted">Nenhuma nota fiscal anexada.</li>
        @endforelse
    </ul>

    <form method="POST" action="{{ route('attachments.store') }}" enctype="multipart/form-data" class="space-y-2">
        @csrf
        <input type="hidden" name="entity_type" value="{{ get_class($model) }}">
        <input type="hidden" name="entity_id" value="{{ $model->getKey() }}">
        <input type="file" name="file" accept=".pdf,image/*"
            class="block w-full text-sm text-muted file:mr-3 file:rounded-lg file:border-0 file:bg-primary-600 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-white hover:file:bg-primary-700" />
        <x-ui.button type="submit" icon="upload" block>Anexar nota fiscal</x-ui.button>
    </form>
</x-ui.card>
