@if($model->tags->isNotEmpty())
    <div class="flex flex-wrap gap-1">
        @foreach($model->tags as $tag)
            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium text-white"
                style="background-color: {{ $tag->color }}">
                {{ $tag->name }}
            </span>
        @endforeach
    </div>
@endif
