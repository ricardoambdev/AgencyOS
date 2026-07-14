<x-guest-layout>
    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-50">Redefinir senha</h1>
    <p class="mb-6 mt-1 text-sm text-muted">Crie uma nova senha para sua conta.</p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="space-y-4">
            <x-ui.field label="E-mail" name="email" required>
                <x-ui.input name="email" type="email" :value="old('email', $email)" placeholder="voce@empresa.com" />
            </x-ui.field>
            <x-ui.field label="Nova senha" name="password" required>
                <x-ui.input name="password" type="password" placeholder="••••••••" />
            </x-ui.field>
            <x-ui.field label="Confirmar senha" name="password_confirmation" required>
                <x-ui.input name="password_confirmation" type="password" placeholder="••••••••" />
            </x-ui.field>
            <x-ui.button type="submit" class="w-full" icon="key-round">Redefinir senha</x-ui.button>
        </div>
    </form>

    <div class="mt-5 text-sm">
        <a href="{{ route('login') }}" class="text-primary-700 hover:underline dark:text-primary-300">Voltar ao login</a>
    </div>
</x-guest-layout>
