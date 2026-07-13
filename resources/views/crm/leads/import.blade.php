@extends('layouts.app')
@section('content')
    <div class="mx-auto max-w-xl">
        <a href="{{ route('leads.index') }}" class="text-sm text-indigo-600">&larr; Leads</a>
        <h1 class="mb-4 text-2xl font-bold text-gray-800">Importar Leads (CSV)</h1>

        <div class="mb-4 rounded-md bg-gray-50 p-4 text-sm text-gray-600">
            Cabeçalhos aceitos: <code>nome/name</code>, <code>email</code>, <code>empresa/company_name</code>,
            <code>telefone/phone</code>, <code>valor/value</code>, <code>status</code>.
        </div>

        <form method="POST" action="{{ route('leads.import.store') }}" enctype="multipart/form-data"
              class="space-y-4 rounded-lg bg-white p-6 shadow">
            @csrf
            <input type="file" name="file" accept=".csv,.txt" class="block w-full text-sm" required>
            <button class="rounded-md bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">Importar</button>
        </form>
    </div>
@endsection
