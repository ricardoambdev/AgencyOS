@php
    $isFav = $model->isFavoritedBy();
    $type = get_class($model);
    $id = $model->getKey();
@endphp

<button type="button"
    x-data="{ fav: {{ $isFav ? 'true' : 'false' }} }"
    @click="
        fav = !fav;
        fetch('{{ route('favoritos.toggle') }}', {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json'},
            body: JSON.stringify({entity_type: '{{ $type }}', entity_id: {{ $id }}})
        })
        .then(r => r.json())
        .then(d => { fav = d.favorited; })
        .catch(() => { fav = !fav; });
    "
    class="text-xl leading-none"
    :class="fav ? 'text-yellow-400' : 'text-gray-400'"
    title="Favoritar">
    <span x-text="fav ? '★' : '☆'"></span>
</button>
