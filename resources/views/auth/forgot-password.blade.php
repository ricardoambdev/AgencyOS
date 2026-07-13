<x-guest-layout>
    <div class="mx-auto max-w-md mt-16 bg-white p-8 rounded-lg shadow">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">Recuperar senha</h1>
        <p class="text-sm text-gray-500 mb-6">Informe seu e-mail para receber o link de redefinição.</p>

        @if(session('status'))
            <div class="mb-4 rounded-md bg-green-100 px-4 py-2 text-sm text-green-800">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border-gray-300 border px-3 py-2" required>
            </div>
            <button class="w-full bg-indigo-600 text-white py-2 rounded-md font-medium hover:bg-indigo-700">Enviar link</button>
        </form>
        <p class="mt-4 text-sm text-gray-600"><a href="{{ route('login') }}" class="text-indigo-600">Voltar ao login</a></p>
    </div>
</x-guest-layout>
