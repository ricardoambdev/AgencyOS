@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('comercial.index') }}" class="text-sm text-indigo-600">&larr; Comercial</a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $contrato->title }}</h1>
            <p class="text-sm text-gray-500">{{ $contrato->number }} &middot; {{ $contrato->status }}</p>
        </div>
        <a href="{{ route('comercial.edit', $contrato) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Editar</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            @if($contrato->description)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Descrição</h3>
                <p class="text-sm text-gray-600 whitespace-pre-line">{{ $contrato->description }}</p>
            </div>
            @endif

            @if($contrato->attachments->count())
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Documentos</h3>
                <ul class="space-y-1 text-sm">
                    @foreach($contrato->attachments as $a)
                        <li><a href="{{ route('comercial.attachments.download', [$contrato, $a]) }}" class="text-indigo-600">{{ $a->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.comments', ['model' => $contrato])
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="mb-3 flex items-center gap-2">
                    @include('partials.favorite-button', ['model' => $contrato])
                    <span class="text-sm text-gray-500">Favoritar</span>
                </div>
                @include('partials.tags-display', ['model' => $contrato])
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Cliente</dt><dd>{{ $contrato->cliente->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Responsável</dt><dd>{{ $contrato->responsavel->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Tipo</dt><dd>{{ $contrato->type }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Valor</dt><dd>{{ $contrato->currency }} {{ number_format($contrato->value, 2, ',', '.') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Início</dt><dd>{{ $contrato->start_date?->format('d/m/Y') ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Fim</dt><dd>{{ $contrato->end_date?->format('d/m/Y') ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Renovação</dt><dd>{{ $contrato->renewal_type }} @if($contrato->renewal_date)/ {{ $contrato->renewal_date->format('d/m/Y') }}@endif</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Assinado em</dt><dd>{{ $contrato->signed_at?->format('d/m/Y') ?? '-' }}</dd></div>
                </dl>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                @include('partials.timeline', ['model' => $contrato])
            </div>
        </div>
    </div>
@endsection
