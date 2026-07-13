<div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
    <h4 class="mb-2 text-sm font-semibold text-gray-700">Anexos</h4>

    <ul class="mb-3 space-y-1">
        @forelse($model->attachments as $att)
            <li class="flex items-center justify-between text-sm">
                <a href="{{ route('attachments.download', $att) }}"
                    class="text-indigo-600 hover:underline">{{ $att->name }}</a>
                <form method="POST" action="{{ route('attachments.destroy', $att) }}" class="inline"
                    onsubmit="return confirm('Remover anexo?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-500 hover:underline">remover</button>
                </form>
            </li>
        @empty
            <li class="text-sm text-gray-400">Nenhum anexo.</li>
        @endforelse
    </ul>

    <form method="POST" action="{{ route('attachments.store') }}" enctype="multipart/form-data"
        class="flex items-center gap-2">
        @csrf
        <input type="hidden" name="entity_type" value="{{ get_class($model) }}">
        <input type="hidden" name="entity_id" value="{{ $model->getKey() }}">
        <input type="file" name="file" class="text-sm">
        <button class="rounded bg-indigo-600 px-3 py-1 text-sm text-white">Enviar</button>
    </form>
</div>
