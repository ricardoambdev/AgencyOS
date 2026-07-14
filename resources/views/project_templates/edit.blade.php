@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <a href="{{ route('templates.show', $template) }}" class="text-sm text-primary-700 dark:text-primary-300">&larr; Voltar</a>
        <h1 class="text-2xl font-bold text-[var(--text)]">Editar Template</h1>
    </div>

    <x-ui.card>
        <x-ui.form method="PUT" action="{{ route('templates.update', $template) }}">
            @include('project_templates._form', ['template' => $template])
            <x-ui.form-footer>
                <x-ui.button type="submit" icon="save">Salvar</x-ui.button>
            </x-ui.form-footer>
        </x-ui.form>
    </x-ui.card>
@endsection
