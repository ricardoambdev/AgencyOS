@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-sm text-primary-700 dark:text-primary-300">&larr; Dashboard</a>
        <h1 class="text-2xl font-bold text-[var(--text)]">Personalizar Dashboard</h1>
        <p class="text-sm text-[var(--text-muted)]">Marque os widgets que deseja exibir (na ordem desejada).</p>
    </div>

    <x-ui.card>
        <x-ui.form method="POST" action="{{ route('dashboard.customize.update') }}">
            <div class="space-y-2">
                @foreach($available as $key => $info)
                    <label class="flex items-center gap-3 border border-[var(--border)] rounded-md px-3 py-2">
                        <input type="checkbox" name="widgets[]" value="{{ $key }}"
                            {{ in_array($key, $selected) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-[var(--border)] text-primary-600 focus-ring dark:border-neutral-600 dark:bg-neutral-900">
                        <span>
                            <span class="font-medium text-[var(--text)]">{{ $info['label'] }}</span>
                            <span class="text-xs text-[var(--text-muted)] block">{{ $info['description'] }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
            <x-ui.form-footer>
                <x-ui.button type="submit" icon="save">Salvar</x-ui.button>
            </x-ui.form-footer>
        </x-ui.form>
    </x-ui.card>
@endsection
