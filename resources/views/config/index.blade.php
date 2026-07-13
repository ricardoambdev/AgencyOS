@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Configurações</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('config.settings.edit') }}" class="bg-white shadow rounded-lg p-5 hover:shadow-md">
            <h3 class="font-semibold text-gray-800">Gerais</h3>
            <p class="text-sm text-gray-500">Nome da empresa, fuso, moeda, idioma, portal.</p>
        </a>
        <a href="{{ route('config.roles.index') }}" class="bg-white shadow rounded-lg p-5 hover:shadow-md">
            <h3 class="font-semibold text-gray-800">Funções e Permissões</h3>
            <p class="text-sm text-gray-500">Controle de acesso baseado em capacidades.</p>
        </a>
        <a href="{{ route('config.custom-fields.index') }}" class="bg-white shadow rounded-lg p-5 hover:shadow-md">
            <h3 class="font-semibold text-gray-800">Campos Personalizados</h3>
            <p class="text-sm text-gray-500">Campos por entidade.</p>
        </a>
        <a href="{{ route('config.workflows.index') }}" class="bg-white shadow rounded-lg p-5 hover:shadow-md">
            <h3 class="font-semibold text-gray-800">Workflows</h3>
            <p class="text-sm text-gray-500">Máquinas de status.</p>
        </a>
        <a href="{{ route('config.automations.index') }}" class="bg-white shadow rounded-lg p-5 hover:shadow-md">
            <h3 class="font-semibold text-gray-800">Automações</h3>
            <p class="text-sm text-gray-500">Gatilhos e ações.</p>
        </a>
        <a href="{{ route('config.webhooks.index') }}" class="bg-white shadow rounded-lg p-5 hover:shadow-md">
            <h3 class="font-semibold text-gray-800">Webhooks</h3>
            <p class="text-sm text-gray-500">Integrações de saída.</p>
        </a>
    </div>
@endsection
