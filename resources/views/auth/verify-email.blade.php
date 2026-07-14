<x-guest-layout>
    <h1 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-50">Confirme seu e-mail</h1>
    <p class="mb-4 text-sm text-muted">
        Enviamos um link de verificação para o seu endereço de e-mail. Clique no link para ativar sua conta.
    </p>
    <p class="mb-4 text-sm text-muted">
        Não recebeu? Você pode solicitar um novo link abaixo.
    </p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <x-ui.button type="submit" class="w-full" icon="mail-check">
            Reenviar link de verificação
        </x-ui.button>
    </form>

    <div class="mt-4 text-sm">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-muted hover:text-neutral-700 dark:hover:text-neutral-200">Sair</button>
        </form>
    </div>
</x-guest-layout>
