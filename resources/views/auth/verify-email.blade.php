<x-guest-layout>
    <div class="mx-auto max-w-md rounded-lg bg-white p-8 shadow">
        <h1 class="mb-4 text-lg font-semibold text-gray-800">Confirme seu e-mail</h1>
        <p class="mb-4 text-sm text-gray-600">
            Enviamos um link de verificação para o seu endereço de e-mail. Clique no link para ativar sua conta.
        </p>
        <p class="mb-4 text-sm text-gray-600">
            Não recebeu? Você pode solicitar um novo link abaixo.
        </p>
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Reenviar link de verificação
            </button>
        </form>
        <div class="mt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-xs text-gray-400 hover:text-gray-600">Sair</button>
            </form>
        </div>
    </div>
</x-guest-layout>
