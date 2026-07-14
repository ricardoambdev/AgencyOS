@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <a href="{{ route('templates.index') }}" class="text-sm text-primary-700 dark:text-primary-300">&larr; Templates</a>
        <h1 class="text-2xl font-bold text-[var(--text)]">Novo Template</h1>
    </div>

    <x-ui.card>
        <x-ui.form method="POST" action="{{ route('templates.store') }}">
            @include('project_templates._form', ['template' => null])
            <x-ui.form-footer>
                <x-ui.button type="submit" icon="save">Salvar</x-ui.button>
            </x-ui.form-footer>
        </x-ui.form>
    </x-ui.card>
@endsection
