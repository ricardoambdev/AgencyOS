@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[var(--text)]">{{ $state ? 'Editar estado' : 'Novo estado' }}</h1>
    </div>

    <form method="POST" action="{{ $state ? route('config.workflow-states.update', $state) : route('config.workflow-states.store') }}"
          class="max-w-lg space-y-4 rounded-lg bg-[var(--surface)] p-6 shadow">
        @csrf
        @if($state) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-[var(--text)]">Entidade</label>
            <select name="entity_type" class="mt-1 w-full rounded-md border border-[var(--border)] px-3 py-2">
                @foreach($entityTypes as $type => $label)
                    <option value="{{ $type }}" {{ $type === $selected ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text)]">Slug (identificador)</label>
            <input type="text" name="slug" value="{{ old('slug', $state->slug ?? '') }}"
                   class="mt-1 w-full rounded-md border border-[var(--border)] px-3 py-2 font-mono" placeholder="ex: em_negociacao">
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text)]">Nome</label>
            <input type="text" name="name" value="{{ old('name', $state->name ?? '') }}"
                   class="mt-1 w-full rounded-md border border-[var(--border)] px-3 py-2" placeholder="Em Negociação">
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text)]">Cor (hex)</label>
            <input type="text" name="color" value="{{ old('color', $state->color ?? '') }}"
                   class="mt-1 w-full rounded-md border border-[var(--border)] px-3 py-2 font-mono" placeholder="#10b981">
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text)]">Ordem</label>
            <input type="number" name="order" value="{{ old('order', $state->order ?? 0) }}"
                   class="mt-1 w-full rounded-md border border-[var(--border)] px-3 py-2">
        </div>

        <div class="flex gap-4">
            <label class="flex items-center gap-2 text-sm text-[var(--text)]">
                <input type="checkbox" name="is_initial" value="1" {{ old('is_initial', $state->is_initial ?? false) ? 'checked' : '' }}> Estado inicial
            </label>
            <label class="flex items-center gap-2 text-sm text-[var(--text)]">
                <input type="checkbox" name="is_final" value="1" {{ old('is_final', $state->is_final ?? false) ? 'checked' : '' }}> Estado final
            </label>
        </div>

        <div class="flex gap-2">
            <button class="rounded-md bg-primary-600 px-4 py-2 text-sm text-white hover:bg-primary-700">Salvar</button>
            <a href="{{ route('config.workflow-states.index', ['entity_type' => $selected]) }}" class="px-4 py-2 text-sm text-[var(--text-muted)]">Cancelar</a>
        </div>
    </form>
@endsection
