<x-admin.layout :title="($model ? 'Editar ' : 'Novo ') . $def->label">
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('admin.resource.index', $resource) }}" class="mb-4 inline-flex items-center gap-1 text-sm text-muted hover:text-primary-700 dark:hover:text-primary-300">
            <i data-lucide="arrow-left" class="sm"></i> Voltar
        </a>

        <x-ui.card>
            <form method="POST" action="{{ $model ? route('admin.resource.update', [$resource, $model->id]) : route('admin.resource.store', $resource) }}">
                @csrf
                @if($model) @method('PUT') @endif

                <div class="space-y-4">
                    @foreach($fields as $name => $cfg)
                        @php
                            $value = old($name, $model ? $model->$name : null);
                            if ($cfg['type'] === 'datetime' && $value) {
                                $value = \Carbon\Carbon::parse($value)->format('Y-m-d\TH:i');
                            }
                        @endphp

                        <x-ui.field :label="$cfg['label']" :name="$name">
                            @if($cfg['type'] === 'relation')
                                <x-ui.select :name="$name" :options="$options[$name] ?? []" :selected="$value" placeholder="Selecione..." />
                            @elseif($cfg['type'] === 'select' && !empty($cfg['options']))
                                <x-ui.select :name="$name" :options="$cfg['options']" :selected="$value" placeholder="Selecione..." />
                            @elseif($cfg['type'] === 'textarea')
                                <x-ui.textarea :name="$name" :value="$value" rows="5" />
                            @elseif($cfg['type'] === 'boolean')
                                <x-ui.checkbox :name="$name" :checked="$value" :label="$cfg['label']" />
                            @elseif($cfg['type'] === 'password')
                                <x-ui.input :name="$name" type="password" />
                            @elseif($cfg['type'] === 'number')
                                <x-ui.input :name="$name" type="number" :value="$value" step="0.01" />
                            @elseif($cfg['type'] === 'email')
                                <x-ui.input :name="$name" type="email" :value="$value" />
                            @elseif($cfg['type'] === 'datetime')
                                <x-ui.input :name="$name" type="datetime-local" :value="$value" />
                            @elseif($cfg['type'] === 'url')
                                <x-ui.input :name="$name" type="url" :value="$value" />
                            @else
                                <x-ui.input :name="$name" :value="$value" />
                            @endif
                        </x-ui.field>
                    @endforeach
                </div>

                <div class="mt-6 flex justify-end gap-2 border-t border-app pt-4">
                    <a href="{{ route('admin.resource.index', $resource) }}" class="rounded-xl px-4 py-2 text-sm font-medium text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800">Cancelar</a>
                    <x-ui.button type="submit" icon="check">Salvar</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-admin.layout>
