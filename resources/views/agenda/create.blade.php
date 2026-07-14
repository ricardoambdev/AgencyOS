@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[var(--text)]">Novo Evento</h1>
        <p class="text-sm text-[var(--text-muted)]">Agende um compromisso na agenda da equipe</p>
    </div>

    <x-ui.card class="max-w-2xl">
        <x-ui.form method="POST" action="{{ route('agenda.store') }}">
            <div class="space-y-4">
                <x-ui.field label="Título" for="title" required>
                    <x-ui.input id="title" name="title" required />
                </x-ui.field>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-ui.field label="Início" for="start_at" required>
                        <x-ui.input id="start_at" name="start_at" type="datetime-local" required />
                    </x-ui.field>
                    <x-ui.field label="Término" for="end_at">
                        <x-ui.input id="end_at" name="end_at" type="datetime-local" />
                    </x-ui.field>
                </div>

                <x-ui.field label="Local" for="location">
                    <x-ui.input id="location" name="location" />
                </x-ui.field>

                <x-ui.field label="Responsável" for="user_id">
                    <x-ui.select id="user_id" name="user_id">
                        <option value="">—</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </x-ui.select>
                </x-ui.field>
            </div>

            <x-ui.form-footer>
                <x-ui.button type="submit" icon="save">Salvar</x-ui.button>
                <x-ui.button href="{{ route('agenda.index') }}" variant="ghost">Cancelar</x-ui.button>
            </x-ui.form-footer>
        </x-ui.form>
    </x-ui.card>
@endsection
