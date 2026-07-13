@extends('layouts.app')
@section('content')
    <div class="mb-6">
        <a href="{{ route('templates.index') }}" class="text-sm text-indigo-600">&larr; Templates</a>
        <h1 class="text-2xl font-bold text-gray-800">Novo Template</h1>
    </div>
    <form method="POST" action="{{ route('templates.store') }}" class="bg-white shadow rounded-lg p-6" x-data="{}">
        @csrf
        @include('project_templates._form', ['template' => null])
        <div class="mt-6"><button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Salvar</button></div>
    </form>
@endsection
