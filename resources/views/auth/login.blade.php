<x-guest-layout>
    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-50">Entrar no AgencyOS</h1>
    <p class="mb-6 mt-1 text-sm text-muted">Acesse sua plataforma operacional.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="space-y-4">
            <x-ui.field label="E-mail" name="email" required>
                <x-ui.input name="email" type="email" :value="old('email')" placeholder="voce@empresa.com" />
            </x-ui.field>
            <x-ui.field label="Senha" name="password" required>
                <x-ui.input name="password" type="password" placeholder="••••••••" />
            </x-ui.field>
            <x-ui.button type="submit" class="w-full" icon="log-in">Entrar</x-ui.button>
        </div>
    </form>

    <div class="mt-5 flex justify-between text-sm">
        <a href="{{ route('password.request') }}" class="text-primary-700 hover:underline dark:text-primary-300">Esqueci minha senha</a>
        <a href="{{ route('register') }}" class="text-primary-700 hover:underline dark:text-primary-300">Criar empresa</a>
    </div>
</x-guest-layout>
