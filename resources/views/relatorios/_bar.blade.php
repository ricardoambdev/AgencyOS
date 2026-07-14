<div class="mb-2">
    <div class="flex justify-between text-sm mb-1">
        <span class="text-[var(--text)]">{{ $label }}</span>
        <span class="text-[var(--text-muted)]">
            {{ $count }}
            @if(! empty($value))
                · R$ {{ number_format($value, 2, ',', '.') }}
            @endif
        </span>
    </div>
    <div class="w-full h-2.5 bg-[var(--surface-2)] rounded-full overflow-hidden">
        <div class="h-full bg-[var(--brand)]" style="width: {{ $pct ?? 0 }}%"></div>
    </div>
</div>
