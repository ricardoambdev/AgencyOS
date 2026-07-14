@extends('layouts.app')
@section('content')
    <div class="mx-auto max-w-xl">
        <a href="{{ route('leads.index') }}" class="text-sm text-primary-700 dark:text-primary-300">&larr; Leads</a>
        <h1 class="mb-4 text-2xl font-bold text-[var(--text)]">Importar Leads (CSV)</h1>

        <x-ui.alert variant="info" class="mb-4">
            Cabeçalhos aceitos: <code>nome/name</code>, <code>email</code>, <code>empresa/company_name</code>,
            <code>telefone/phone</code>, <code>valor/value</code>, <code>status</code>.
        </x-ui.alert>

        <x-ui.card>
            <x-ui.form method="POST" action="{{ route('leads.import.store') }}" enctype="multipart/form-data">
                <x-ui.field label="Arquivo CSV" name="file" required>
                    <input type="file" name="file" accept=".csv,.txt" class="block w-full text-sm text-[var(--text)]" required>
                </x-ui.field>
                <x-ui.form-footer>
                    <x-ui.button type="submit" icon="upload">Importar</x-ui.button>
                </x-ui.form-footer>
            </x-ui.form>
        </x-ui.card>
    </div>
@endsection
