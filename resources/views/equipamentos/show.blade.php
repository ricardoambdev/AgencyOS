@extends('layouts.app')
@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
        <div>
            <a href="{{ route('equipamentos.index') }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">&larr; Equipamentos</a>
            <h1 class="text-2xl font-bold tracking-tight text-app">{{ $equipamento->name }}</h1>
            <p class="flex flex-wrap items-center gap-2 text-sm text-muted">
                {{ App\Domains\Equipamento\Controllers\EquipamentoController::tipos()[$equipamento->type] ?? $equipamento->type }}
                &middot; {{ App\Domains\Equipamento\Controllers\EquipamentoController::status()[$equipamento->status] ?? $equipamento->status }}
                <x-ui.badge variant="{{ App\Domains\Equipamento\Models\Equipamento::situacaoVariant($equipamento->situacao) }}" dot>{{ App\Domains\Equipamento\Models\Equipamento::situacoes()[$equipamento->situacao] ?? $equipamento->situacao }}</x-ui.badge>
            </p>
        </div>
        <x-ui.button href="{{ route('equipamentos.edit', $equipamento) }}" icon="pencil">Editar</x-ui.button>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            @if($equipamento->description)
            <x-ui.card>
                <h3 class="mb-3 font-semibold text-app">Descrição</h3>
                <p class="whitespace-pre-line text-sm text-muted">{{ $equipamento->description }}</p>
            </x-ui.card>
            @endif
            <x-ui.card>
                @include('partials.comments', ['model' => $equipamento])
                @include('partials.entity-activity', ['model' => $equipamento])
            </x-ui.card>
        </div>
        <div class="space-y-6">
            <x-ui.card>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-muted">Status</dt><dd class="text-app">{{ App\Domains\Equipamento\Controllers\EquipamentoController::status()[$equipamento->status] ?? $equipamento->status }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Situação</dt><dd class="text-app">{{ App\Domains\Equipamento\Models\Equipamento::situacoes()[$equipamento->situacao] ?? $equipamento->situacao }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Valor</dt><dd class="text-app">R$ {{ number_format($equipamento->valor, 2, ',', '.') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Seguro</dt><dd class="text-app">{{ $equipamento->tem_seguro ? 'Sim · R$ '.number_format($equipamento->valor_seguro, 2, ',', '.') : 'Não' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Responsável</dt><dd class="text-app">{{ $equipamento->owner->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Nº série</dt><dd class="text-app">{{ $equipamento->serial ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Data de aquisição</dt><dd class="text-app">{{ $equipamento->purchase_date?->format('d/m/Y') ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Tempo de uso</dt><dd class="text-app">{{ $equipamento->tempoDeUso() ?? '-' }}</dd></div>
                </dl>
            </x-ui.card>
            <x-ui.card>
                @include('partials.timeline', ['model' => $equipamento])
            </x-ui.card>
        </div>
    </div>
@endsection
