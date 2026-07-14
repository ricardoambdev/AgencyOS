@extends('layouts.app')
@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
        <div>
            <a href="{{ route('producao.index') }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">&larr; Produção</a>
            <h1 class="text-2xl font-bold tracking-tight text-app">{{ $entregavel->name }} <span class="text-base font-normal text-muted">v{{ $entregavel->version }}</span></h1>
            <p class="text-sm text-muted">{{ $entregavel->projeto->name ?? '' }} &middot; {{ App\Domains\Producao\Controllers\EntregavelController::tipos()[$entregavel->type] ?? $entregavel->type }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($entregavel->status !== 'aprovado' && $entregavel->status !== 'entregue')
                <form method="POST" action="{{ route('producao.aprovar', ['entregavel' => $entregavel]) }}">@csrf
                    <x-ui.button type="submit" variant="success" icon="check">Aprovar</x-ui.button>
                </form>
            @endif
            @if($entregavel->status !== 'entregue')
                <form method="POST" action="{{ route('producao.aprovar', ['entregavel' => $entregavel]) }}">@csrf<input type="hidden" name="deliver" value="1">
                    <x-ui.button type="submit" variant="info" icon="send">Marcar entregue</x-ui.button>
                </form>
            @endif
            <form method="POST" action="{{ route('producao.nova-versao', ['entregavel' => $entregavel]) }}">@csrf
                <x-ui.button type="submit" variant="neutral" icon="copy">Nova versão</x-ui.button>
            </form>
            <x-ui.button href="{{ route('producao.edit', $entregavel) }}" icon="pencil">Editar</x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-ui.card>
                <h3 class="mb-3 font-semibold text-app">Anexos</h3>
                <div class="space-y-2">
                    @forelse($entregavel->attachments as $a)
                        <div class="flex items-center justify-between rounded-lg border border-border px-3 py-2">
                            <span class="text-sm text-app">{{ $a->name }} <span class="text-xs text-muted">({{ number_format($a->size/1024, 1) }} KB)</span></span>
                            <a href="{{ route('producao.attachments.download', ['attachment' => $a]) }}" class="text-sm text-primary-700 hover:underline dark:text-primary-300">Baixar</a>
                        </div>
                    @empty
                        <p class="text-sm text-muted">Nenhum anexo.</p>
                    @endforelse
                </div>
            </x-ui.card>

            @if($entregavel->description)
            <x-ui.card>
                <h3 class="mb-3 font-semibold text-app">Briefing / Descrição</h3>
                <p class="whitespace-pre-line text-sm text-muted">{{ $entregavel->description }}</p>
            </x-ui.card>
            @endif

            <x-ui.card>
                @include('partials.comments', ['model' => $entregavel])
            </x-ui.card>
        </div>

        <div class="space-y-6">
            <x-ui.card>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-muted">Status</dt><dd class="text-app">{{ $statusOptions[$entregavel->status] ?? $entregavel->status }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Responsável</dt><dd class="text-app">{{ $entregavel->owner->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Entrega</dt><dd class="text-app">{{ $entregavel->due_date?->format('d/m/Y') ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Versão</dt><dd class="text-app">v{{ $entregavel->version }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Portal</dt><dd class="text-app">{{ $entregavel->client_visible ? 'Visível' : 'Oculto' }}</dd></div>
                </dl>
            </x-ui.card>
            <x-ui.card>
                @include('partials.timeline', ['model' => $entregavel])
            </x-ui.card>
        </div>
    </div>
@endsection
