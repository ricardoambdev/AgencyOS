<h3 class="text-sm font-semibold text-gray-700 mb-3">Comentários</h3>
<form method="POST" action="{{ route('comments.store') }}" class="mb-4">
    @csrf
    <input type="hidden" name="entity_type" value="{{ get_class($model) }}">
    <input type="hidden" name="entity_id" value="{{ $model->ulid }}">
    <input type="hidden" name="redirect" value="{{ request()->url() }}">
    <textarea name="body" rows="2" placeholder="Escreva um comentário..." class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"></textarea>
    <div class="mt-2 flex items-center justify-between">
        <select name="visibility" class="rounded-md border border-gray-300 px-2 py-1 text-xs">
            <option value="internal">Interno</option>
            <option value="public">Público</option>
            <option value="client">Cliente</option>
        </select>
        <button class="bg-indigo-600 text-white px-3 py-1 rounded-md text-sm hover:bg-indigo-700">Comentar</button>
    </div>
</form>
<div class="space-y-3">
    @foreach($model->comments as $comment)
    <div class="border-b border-gray-100 pb-3"
         x-data="{ state: {{ json_encode(collect(['👍','❤️','🎉'])->mapWithKeys(fn ($e) => [$e => ['count' => count($comment->reactions[$e] ?? []), 'reacted' => in_array(auth()->id(), $comment->reactions[$e] ?? [], true)]])) }} }">
        <div class="flex justify-between items-center">
            <span class="text-sm font-medium text-gray-800">{{ $comment->user->name ?? 'Usuário' }}</span>
            <span class="text-xs text-gray-400">{{ $comment->created_at->format('d/m/Y H:i') }} &middot; {{ ucfirst($comment->visibility) }}</span>
        </div>
        <p class="text-sm text-gray-600 mt-1">{!! nl2br(preg_replace('/@([\p{L}\p{N}_.]+)/u', '<span class="font-semibold text-indigo-600">@$1</span>', e($comment->body))) !!}</p>
        <div class="mt-2 flex flex-wrap items-center gap-1">
            @foreach(['👍','❤️','🎉'] as $emoji)
                <form method="POST" action="{{ route('comments.react', $comment) }}" class="inline"
                      @submit.prevent="
                        fetch('{{ route('comments.react', $comment) }}', {
                            method: 'POST',
                            headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/json', 'Accept': 'application/json'},
                            body: JSON.stringify({emoji: '{{ $emoji }}'})
                        }).then(r => r.json()).then(d => {
                            d.reactions.forEach(x => state[x.emoji] = {count: x.count, reacted: x.reacted});
                        });">
                    @csrf
                    <input type="hidden" name="emoji" value="{{ $emoji }}">
                    <button type="submit"
                        class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-xs transition"
                        :class="state['{{ $emoji }}'].reacted ? 'border-indigo-300 bg-indigo-50 text-indigo-700' : 'border-gray-200 text-gray-500 hover:bg-gray-50'">
                        <span>{{ $emoji }}</span>
                        <span x-text="state['{{ $emoji }}'].count"></span>
                    </button>
                </form>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
