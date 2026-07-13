@props(['value' => 0, 'max' => 100, 'label' => null, 'showValue' => true])

@php $pct = $max > 0 ? min(100, round($value / $max * 100)) : 0; @endphp

<div {{ $attributes }}>
    @if($label || $showValue)
        <div class="mb-1 flex items-center justify-between text-xs">
            @if($label)<span class="font-medium text-neutral-700 dark:text-neutral-200">{{ $label }}</span>@endif
            @if($showValue)<span class="text-muted">{{ $pct }}%</span>@endif
        </div>
    @endif
    <div class="h-2 w-full overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
        <div class="h-full rounded-full bg-primary-500 transition-all duration-500" style="width: {{ $pct }}%"></div>
    </div>
</div>
