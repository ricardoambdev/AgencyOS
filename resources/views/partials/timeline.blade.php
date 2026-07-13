<h3 class="text-sm font-semibold text-gray-700 mb-3">Timeline</h3>
@if($model->timeline->isEmpty())
    <p class="text-sm text-gray-400">Nenhum evento registrado.</p>
@else
    <ol class="relative border-l border-gray-200 ml-3">
        @foreach($model->timeline as $item)
        <li class="mb-4 ml-4">
            <div class="absolute -left-1.5 w-3 h-3 bg-indigo-500 rounded-full"></div>
            <div class="text-sm font-medium text-gray-800">{{ $item->title }}</div>
            @if($item->description)<div class="text-xs text-gray-500">{{ $item->description }}</div>@endif
            <div class="text-xs text-gray-400">{{ $item->created_at->format('d/m/Y H:i') }} &middot; {{ $item->user->name ?? 'Sistema' }}</div>
        </li>
        @endforeach
    </ol>
@endif
