<div class="mb-2">
    <div class="flex justify-between text-sm mb-1">
        <span class="text-gray-700">{{ $label }}</span>
        <span class="text-gray-500">
            {{ $count }}
            @if(! empty($value))
                · R$ {{ number_format($value, 2, ',', '.') }}
            @endif
        </span>
    </div>
    <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
        <div class="h-full bg-indigo-500" style="width: {{ $pct ?? 0 }}%"></div>
    </div>
</div>
