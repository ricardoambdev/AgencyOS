@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <a href="{{ route('templates.show', $template) }}" class="text-sm text-primary-700 dark:text-primary-300">&larr; {{ $template->name }}</a>
        <h1 class="text-2xl font-bold text-[var(--text)]">Aplicar Template: {{ $template->name }}</h1>
        <p class="text-sm text-[var(--text-muted)]">Cria um projeto com {{ $template->templateTasks->count() }} tarefa(s) pré-definida(s).</p>
    </div>

    <x-ui.card>
        <x-ui.form method="POST" action="{{ route('templates.apply.store', $template) }}" enctype="multipart/form-data">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-ui.field label="Cliente" for="client_id" required>
                        <x-ui.select id="client_id" name="client_id" required>
                            <option value="">—</option>
                            @foreach($clientes as $c)<option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
                        </x-ui.select>
                    </x-ui.field>
                    <x-ui.field label="Responsável" for="owner_id">
                        <x-ui.select id="owner_id" name="owner_id">
                            <option value="">—</option>
                            @foreach($owners as $u)<option value="{{ $u->id }}" {{ old('owner_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>@endforeach
                        </x-ui.select>
                    </x-ui.field>
                </div>

                <x-ui.field label="Nome do Projeto" for="name" required>
                    <x-ui.input id="name" name="name" :value="old('name', $template->name)" required />
                </x-ui.field>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-ui.field label="Status" for="status">
                        <x-ui.select id="status" name="status">
                            @foreach(['briefing' => 'Briefing', 'planejamento' => 'Planejamento', 'producao' => 'Produção', 'revisao' => 'Revisão', 'cliente' => 'Com Cliente', 'finalizado' => 'Finalizado'] as $k => $v)
                                <option value="{{ $k }}" {{ old('status', 'briefing') == $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </x-ui.select>
                    </x-ui.field>
                    <x-ui.field label="Início" for="start_date">
                        <x-ui.input id="start_date" name="start_date" type="date" :value="old('start_date')" />
                    </x-ui.field>
                    <x-ui.field label="Fim" for="end_date">
                        <x-ui.input id="end_date" name="end_date" type="date" :value="old('end_date')" />
                    </x-ui.field>
                </div>

                <x-ui.field label="Orçamento" for="budget">
                    <x-ui.input id="budget" name="budget" type="number" step="0.01" :value="old('budget')" />
                </x-ui.field>

                <x-ui.field label="Descrição" for="description">
                    <x-ui.textarea id="description" name="description" rows="3">{{ old('description', $template->description) }}</x-ui.textarea>
                </x-ui.field>

                @include('partials.custom-fields-form', ['model' => new \App\Domains\Projeto\Models\Projeto])
            </div>

            <x-ui.form-footer>
                <x-ui.button type="submit" variant="success" icon="play">Criar Projeto</x-ui.button>
            </x-ui.form-footer>
        </x-ui.form>
    </x-ui.card>
@endsection
