@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('config.custom-fields.index') }}" class="text-sm text-primary-700 dark:text-primary-300">&larr; Campos Personalizados</a>
        <h1 class="text-2xl font-bold text-[var(--text)]">Novo Campo Personalizado</h1>
    </div>
    <form method="POST" action="{{ route('config.custom-fields.store') }}" class="bg-[var(--surface)] shadow rounded-lg p-6">
        @csrf
        @include('config.custom-fields._form', ['customField' => null, 'entityTypes' => $entityTypes, 'types' => $types])
        <div class="mt-6"><button class="bg-primary-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary-700">Salvar</button></div>
    </form>
@endsection
