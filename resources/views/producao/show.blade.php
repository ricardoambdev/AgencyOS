@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('producao.index') }}" class="text-sm text-indigo-600">&larr; Produção</a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $entregavel->name }} <span class="text-base font-normal text-gray-400">v{{ $entregavel->version }}</span></h1>
            <p class="text-sm text-gray-500">{{ $entregavel->projeto->name ?? '' }} &middot; {{ App\Domains\Producao\Controllers\EntregavelController::tipos()[$entregavel->type] ?? $entregavel->type }}</p>
        </div>
        <div class="flex gap-2">
            @if($entregavel->status !== 'aprovado' && $entregavel->status !== 'entregue')
                <form method="POST" action="{{ route('producao.aprovar', ['entregavel' => $entregavel]) }}">@csrf
                    <button class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700">Aprovar</button>
                </form>
            @endif
            @if($entregavel->status !== 'entregue')
                <form method="POST" action="{{ route('producao.aprovar', ['entregavel' => $entregavel]) }}">@csrf<input type="hidden" name="deliver" value="1">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Marcar entregue</button>
                </form>
            @endif
            <form method="POST" action="{{ route('producao.nova-versao', ['entregavel' => $entregavel]) }}">@csrf
                <button class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">Nova versão</button>
            </form>
            <a href="{{ route('producao.edit', $entregavel) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Editar</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Anexos</h3>
                <div class="space-y-2">
                    @forelse($entregavel->attachments as $a)
                        <div class="flex items-center justify-between border rounded-md px-3 py-2">
                            <span class="text-sm">{{ $a->name }} <span class="text-xs text-gray-400">({{ number_format($a->size/1024, 1) }} KB)</span></span>
                            <a href="{{ route('producao.attachments.download', ['attachment' => $a]) }}" class="text-indigo-600 text-sm">Baixar</a>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">Nenhum anexo.</p>
                    @endforelse
                </div>
            </div>

            @if($entregavel->description)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Briefing / Descrição</h3>
                <p class="text-sm text-gray-600 whitespace-pre-line">{{ $entregavel->description }}</p>
            </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.comments', ['model' => $entregavel])
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd>{{ $statusOptions[$entregavel->status] ?? $entregavel->status }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Responsável</dt><dd>{{ $entregavel->owner->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Entrega</dt><dd>{{ $entregavel->due_date?->format('d/m/Y') ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Versão</dt><dd>v{{ $entregavel->version }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Portal</dt><dd>{{ $entregavel->client_visible ? 'Visível' : 'Oculto' }}</dd></div>
                </dl>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.timeline', ['model' => $entregavel])
            </div>
        </div>
    </div>
@endsection
