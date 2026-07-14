@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('config.webhooks.index') }}" class="text-sm text-primary-700 dark:text-primary-300">&larr; Webhooks</a>
        <h1 class="text-2xl font-bold text-[var(--text)]">{{ $webhook ? 'Editar' : 'Novo' }} Webhook</h1>
    </div>

    <form method="POST" action="{{ $webhook ? route('config.webhooks.update', $webhook) : route('config.webhooks.store') }}"
          class="bg-[var(--surface)] shadow rounded-lg p-6 space-y-6">
        @csrf
        @if($webhook) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-[var(--text)]">Nome</label>
            <input type="text" name="name" value="{{ old('name', $webhook->name ?? '') }}"
                   class="mt-1 w-full rounded-md border border-[var(--border)] px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text)]">URL de destino</label>
            <input type="url" name="url" value="{{ old('url', $webhook->url ?? '') }}" placeholder="https://hooks.zapier.com/..."
                   class="mt-1 w-full rounded-md border border-[var(--border)] px-3 py-2" required>
            <p class="text-xs text-[var(--text-muted)] mt-1">Receberá um POST JSON com <code>event</code> e <code>subject</code>.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text)]">Segredo (opcional, para assinar)</label>
            <input type="text" name="secret" value="{{ old('secret', $webhook->secret ?? '') }}"
                   class="mt-1 w-full rounded-md border border-[var(--border)] px-3 py-2 font-mono text-sm"
                   placeholder="gera assinatura HMAC-SHA256 no header X-AgencyOS-Signature">
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text)] mb-2">Eventos</label>
            <div class="space-y-1">
                @foreach($events as $ev => $label)
                    <label class="flex items-center gap-2 text-sm text-[var(--text)]">
                        <input type="checkbox" name="events[]" value="{{ $ev }}"
                               {{ in_array($ev, old('events', $webhook->events ?? [])) ? 'checked' : '' }}>
                        {{ $label }} <span class="text-[var(--text-muted)]">({{ $ev }})</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text)]">Headers customizados (opcional)</label>
            <textarea name="headers" rows="3"
                      class="mt-1 w-full rounded-md border border-[var(--border)] px-3 py-2 font-mono text-sm"
                      placeholder="X-Api-Key: abc123&#10;X-Custom: valor">{{ old('headers', $webhook && $webhook->headers ? collect($webhook->headers)->map(fn($v,$k)=>"$k: $v")->implode("\n") : '') }}</textarea>
            <p class="text-xs text-[var(--text-muted)] mt-1">Um por linha, formato <code>Chave: Valor</code>.</p>
        </div>

        <label class="flex items-center gap-2 text-sm text-[var(--text)]">
            <input type="checkbox" name="active" value="1" {{ old('active', $webhook ? $webhook->active : true) ? 'checked' : '' }}>
            Ativo
        </label>

        <div>
            <button class="bg-primary-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary-700">Salvar</button>
        </div>
    </form>
@endsection
