@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[var(--text)]">Configurações</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('config.settings.edit') }}" class="bg-[var(--surface)] shadow rounded-lg p-5 hover:shadow-md">
            <h3 class="font-semibold text-[var(--text)]">Gerais</h3>
            <p class="text-sm text-[var(--text-muted)]">Nome da empresa, fuso, moeda, idioma, portal.</p>
        </a>
        <a href="{{ route('config.roles.index') }}" class="bg-[var(--surface)] shadow rounded-lg p-5 hover:shadow-md">
            <h3 class="font-semibold text-[var(--text)]">Funções e Permissões</h3>
            <p class="text-sm text-[var(--text-muted)]">Controle de acesso baseado em capacidades.</p>
        </a>
        <a href="{{ route('config.custom-fields.index') }}" class="bg-[var(--surface)] shadow rounded-lg p-5 hover:shadow-md">
            <h3 class="font-semibold text-[var(--text)]">Campos Personalizados</h3>
            <p class="text-sm text-[var(--text-muted)]">Campos por entidade.</p>
        </a>
        <a href="{{ route('config.workflows.index') }}" class="bg-[var(--surface)] shadow rounded-lg p-5 hover:shadow-md">
            <h3 class="font-semibold text-[var(--text)]">Workflows</h3>
            <p class="text-sm text-[var(--text-muted)]">Máquinas de status.</p>
        </a>
        <a href="{{ route('config.workflow-states.index') }}" class="bg-[var(--surface)] shadow rounded-lg p-5 hover:shadow-md">
            <h3 class="font-semibold text-[var(--text)]">Estados por Entidade</h3>
            <p class="text-sm text-[var(--text-muted)]">Configure os status de cada entidade.</p>
        </a>
        <a href="{{ route('config.menu.index') }}" class="bg-[var(--surface)] shadow rounded-lg p-5 hover:shadow-md">
            <h3 class="font-semibold text-[var(--text)]">Menu do Workspace</h3>
            <p class="text-sm text-[var(--text-muted)]">Personalize os itens de navegação.</p>
        </a>
        <a href="{{ route('config.automations.index') }}" class="bg-[var(--surface)] shadow rounded-lg p-5 hover:shadow-md">
            <h3 class="font-semibold text-[var(--text)]">Automações</h3>
            <p class="text-sm text-[var(--text-muted)]">Gatilhos e ações.</p>
        </a>
        <a href="{{ route('config.webhooks.index') }}" class="bg-[var(--surface)] shadow rounded-lg p-5 hover:shadow-md">
            <h3 class="font-semibold text-[var(--text)]">Webhooks</h3>
            <p class="text-sm text-[var(--text-muted)]">Integrações de saída.</p>
        </a>
    </div>
@endsection
