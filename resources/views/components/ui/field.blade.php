@props(['label' => null, 'name' => null, 'required' => false, 'hint' => null])

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    {{ $slot }}

    @if($hint && !$errors->has($name))
        <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="text-xs font-medium text-red-600 dark:text-red-400 flex items-center gap-1">
            <i data-lucide="alert-circle" class="sm"></i> {{ $message }}
        </p>
    @enderror
</div>
