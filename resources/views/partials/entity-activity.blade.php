<div class="space-y-4">
    <div class="flex items-center gap-2">
        @include('partials.favorite-button', ['model' => $model])
        <span class="text-sm text-gray-500">Favoritar</span>
    </div>

    <div>
        <div class="mb-1 text-xs font-semibold uppercase tracking-wide text-gray-400">Tags</div>
        @include('partials.tags-display', ['model' => $model])
    </div>

    @include('partials.attachments', ['model' => $model])
</div>
