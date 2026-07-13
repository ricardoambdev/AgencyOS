@props(['entityType', 'status'])
@php
    $meta = \App\Core\Models\WorkflowState::meta($entityType);
    $info = $meta[$status] ?? ['name' => ucfirst(str_replace('_', ' ', $status ?? '')), 'color' => null];
    $color = $info['color'] ?? '#94a3b8';
    if (! $color) {
        $palette = ['#ef4444','#f59e0b','#10b981','#3b82f6','#8b5cf6','#ec4899','#14b8a6','#f97316'];
        $color = $palette[crc32($status ?? 'x') % count($palette)];
    }
@endphp
<span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium text-white" style="background-color: {{ $color }}">
    {{ $info['name'] }}
</span>
