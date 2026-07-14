@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-[var(--text)]">Favoritos</h1>
    </div>

    <div class="overflow-hidden rounded-lg border border-[var(--border)] bg-[var(--surface)] shadow-sm">
        <table class="min-w-full divide-y divide-[var(--border)]">
            <thead class="bg-[var(--surface-2)]">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-[var(--text-muted)]">Tipo</th>
                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-[var(--text-muted)]">Item</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border)]">
                @forelse($items as $item)
                    @php
                        $entity = $item->entity;
                        $route = null;
                        if ($entity) {
                            $class = get_class($entity);
                            $route = match ($class) {
                                \App\Domains\Crm\Models\Lead::class => route('leads.show', $entity),
                                \App\Domains\Cliente\Models\Cliente::class => route('clientes.show', $entity),
                                \App\Domains\Projeto\Models\Projeto::class => route('projetos.show', $entity),
                                \App\Domains\Comercial\Models\Contrato::class => route('comercial.show', $entity),
                                \App\Domains\Equipamento\Models\Equipamento::class => route('equipamentos.show', $entity),
                                default => null,
                            };
                        }
                    @endphp
                    <tr>
                        <td class="px-4 py-2 text-sm text-[var(--text-muted)]">{{ class_basename($item->entity_type) }}</td>
                        <td class="px-4 py-2 text-sm">
                            @if($route)
                                <a href="{{ $route }}" class="text-primary-700 dark:text-primary-300 hover:underline">
                                    {{ $entity->name ?? ('#' . $item->entity_id) }}
                                </a>
                            @else
                                <span class="text-[var(--text)]">{{ $entity->name ?? ('#' . $item->entity_id) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right">
                            <form method="POST" action="{{ route('favoritos.toggle') }}" class="inline">
                                @csrf
                                <input type="hidden" name="entity_type" value="{{ $item->entity_type }}">
                                <input type="hidden" name="entity_id" value="{{ $item->entity_id }}">
                                <button class="text-red-500 hover:underline">remover</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-sm text-[var(--text-muted)]">
                            Nenhum item favoritado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
