@props(['paginator' => null])

@if($paginator && $paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $window = collect(range(max(1, $current - 2), min($last, $current + 2)));
    @endphp
    <nav class="flex flex-wrap items-center justify-between gap-3" aria-label="Paginação">
        <span class="text-xs text-muted">Página {{ $current }} de {{ $last }}</span>
        <div class="flex items-center gap-1">
            @if($paginator->onFirstPage())
                <span class="rounded-lg px-3 py-1.5 text-sm text-neutral-300 dark:text-neutral-600">‹</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="rounded-lg px-3 py-1.5 text-sm text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800">‹</a>
            @endif

            @foreach($window as $page)
                @if($page == $current)
                    <span class="rounded-lg bg-primary-600 px-3 py-1.5 text-sm font-medium text-white">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="rounded-lg px-3 py-1.5 text-sm text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800">{{ $page }}</a>
                @endif
            @endforeach

            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="rounded-lg px-3 py-1.5 text-sm text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800">›</a>
            @else
                <span class="rounded-lg px-3 py-1.5 text-sm text-neutral-300 dark:text-neutral-600">›</span>
            @endif
        </div>
    </nav>
@endif
