<x-guest-layout>
    <div class="mx-auto max-w-md mt-16 bg-white p-8 rounded-lg shadow">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">Entrar no AgencyOS</h1>
        <p class="text-sm text-gray-500 mb-6">Acesse sua plataforma operacional.</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border-gray-300 border px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" name="password" class="mt-1 w-full rounded-md border-gray-300 border px-3 py-2" required>
            </div>
            <button class="w-full bg-indigo-600 text-white py-2 rounded-md font-medium hover:bg-indigo-700">Entrar</button>
        </form>
        <p class="mt-4 text-sm text-gray-600"><a href="{{ route('password.request') }}" class="text-indigo-600">Esqueci minha senha</a></p>
        <p class="mt-2 text-sm text-gray-600">Não tem conta? <a href="{{ route('register') }}" class="text-indigo-600">Criar empresa</a></p>
    </div>
</x-guest-layout>
