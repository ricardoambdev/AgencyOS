<x-admin.layout :title="$def->label">
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('admin.resource.index', $resource) }}" class="mb-4 inline-flex items-center gap-1 text-sm text-muted hover:text-primary-700 dark:hover:text-primary-300">
            <i data-lucide="arrow-left" class="sm"></i> Voltar
        </a>

        <x-ui.card :title="$def->label">
            <dl class="divide-y divide-app">
                @foreach($model->getAttributes() as $key => $val)
                    @if(in_array($key, ['company_id','deleted_at','remember_token','two_factor_secret','two_factor_recovery_codes'])) @continue @endif
                    <div class="flex gap-4 py-2.5">
                        <dt class="w-48 shrink-0 text-sm font-medium text-muted">{{ $def->labelFor($key) }}</dt>
                        <dd class="text-sm text-neutral-800 dark:text-neutral-100">
                            @if(is_bool($val))
                                {{ $val ? 'Sim' : 'Não' }}
                            @elseif(is_array($val) || (is_string($val) && Str::startsWith($val, ['{','['])))
                                <pre class="max-h-48 overflow-auto rounded-lg bg-neutral-50 p-3 text-xs dark:bg-neutral-900">{{ is_array($val) ? json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $val }}</pre>
                            @elseif(Str::endsWith($key, '_at') && $val)
                                {{ \Carbon\Carbon::parse($val)->format('d/m/Y H:i') }}
                            @elseif($val === null || $val === '')
                                <span class="text-muted">—</span>
                            @else
                                {{ $val }}
                            @endif
                        </dd>
                    </div>
                @endforeach
            </dl>
        </x-ui.card>

        <div class="mt-4 flex justify-end gap-2">
            <a href="{{ route('admin.resource.edit', [$resource, $model->id]) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-soft hover:bg-primary-700">
                <i data-lucide="pencil" class="sm"></i> Editar
            </a>
        </div>
    </div>
</x-admin.layout>
