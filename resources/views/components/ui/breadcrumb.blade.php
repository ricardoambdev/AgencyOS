@props(['items' => []])

<nav aria-label="Breadcrumb" {{ $attributes }}>
    <ol class="flex items-center gap-1.5 text-sm">
        @foreach($items as $i => $item)
            <li class="flex items-center gap-1.5">
                @if(isset($item['href']))
                    <a href="{{ $item['href'] }}" class="text-muted hover:text-primary-700 dark:hover:text-primary-300 transition-colors">{{ $item['label'] }}</a>
                @else
                    <span class="font-medium text-neutral-800 dark:text-neutral-100">{{ $item['label'] }}</span>
                @endif
            </li>
            @if(!$loop->last)<li class="text-muted"><i data-lucide="chevron-right" class="sm"></i></li>@endif
        @endforeach
    </ol>
</nav>
