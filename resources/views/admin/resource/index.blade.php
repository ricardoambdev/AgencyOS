<x-admin.layout :title="$def->label">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-50">{{ $def->label }}</h1>
            <p class="text-sm text-muted">{{ $rows->total() }} registro(s)</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <form method="GET" class="flex items-center">
                <div class="relative">
                    <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"></i>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Buscar..." class="w-44 rounded-xl border border-neutral-300 bg-white py-2 pl-9 pr-3 text-sm focus-ring dark:border-neutral-600 dark:bg-neutral-900">
                </div>
            </form>
            <x-ui.button :href="route('admin.resource.import', $resource)" variant="ghost" icon="upload">Importar</x-ui.button>
            <x-ui.button :href="route('admin.resource.export', $resource)" variant="secondary" icon="download">Exportar</x-ui.button>
            <x-ui.button :href="route('admin.resource.create', $resource)" icon="plus">Novo</x-ui.button>
        </div>
    </div>

    <x-ui.card padding="false">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-neutral-700 dark:text-neutral-200">
                <thead>
                    <tr class="border-b border-app">
                        @foreach($columns as $col => $cfg)
                            @php $next = request('sort') === $col && request('dir') !== 'desc' ? 'desc' : 'asc'; @endphp
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-muted">
                                <a href="{{ route('admin.resource.index', array_merge([$resource], request()->except('page'), ['sort' => $col, 'dir' => $next])) }}"
                                   class="inline-flex items-center gap-1 hover:text-neutral-800 dark:hover:text-neutral-100">
                                    {{ $cfg['label'] }}
                                    @if(request('sort') === $col)<i data-lucide="arrow-up" class="sm"></i>@endif
                                </a>
                            </th>
                        @endforeach
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-muted">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr class="border-b border-app transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-800/60">
                            @foreach($columns as $col => $cfg)
                                <td class="px-4 py-3 align-middle">
                                    @if($cfg['type'] === 'relation')
                                        {{ $relationDisplays[$col][$row->$col] ?? $row->$col ?? '-' }}
                                    @elseif($cfg['type'] === 'boolean')
                                        @if($row->$col)<x-ui.badge variant="success" dot>Sim</x-ui.badge>@else<x-ui.badge variant="neutral" dot>Não</x-ui.badge>@endif
                                    @elseif($cfg['type'] === 'badge')
                                        <x-ui.badge>{{ $row->$col }}</x-ui.badge>
                                    @elseif($cfg['type'] === 'datetime')
                                        {{ $row->$col ? \Carbon\Carbon::parse($row->$col)->format('d/m/Y H:i') : '-' }}
                                    @elseif($col === 'id')
                                        <span class="font-mono text-xs text-muted">#{{ $row->$col }}</span>
                                    @else
                                        {{ Str::limit(is_scalar($row->$col) ? (string) $row->$col : '', 60) ?: '-' }}
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('admin.resource.show', [$resource, $row->id]) }}" class="rounded-lg p-1.5 text-muted transition-colors hover:bg-neutral-100 hover:text-primary-700 dark:hover:bg-neutral-800"><i data-lucide="eye" class="sm"></i></a>
                                    <a href="{{ route('admin.resource.edit', [$resource, $row->id]) }}" class="rounded-lg p-1.5 text-muted transition-colors hover:bg-neutral-100 hover:text-primary-700 dark:hover:bg-neutral-800"><i data-lucide="pencil" class="sm"></i></a>
                                    <form method="POST" action="{{ route('admin.resource.destroy', [$resource, $row->id]) }}" onsubmit="return confirm('Excluir este registro?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded-lg p-1.5 text-muted transition-colors hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20"><i data-lucide="trash-2" class="sm"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ count($columns) + 1 }}" class="px-4 py-10 text-center">
                            <x-ui.empty-state icon="inbox" title="Nenhum registro" />
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-app px-4 py-3">
            <x-ui.pagination :paginator="$rows" />
        </div>
    </x-ui.card>
</x-admin.layout>
