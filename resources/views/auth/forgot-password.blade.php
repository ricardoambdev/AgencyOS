<x-guest-layout>
    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-50">Recuperar senha</h1>
    <p class="mb-6 mt-1 text-sm text-muted">Informe seu e-mail para receber o link de redefinição.</p>

    @if(session('status'))
        <x-ui.alert variant="success" class="mb-4">{{ session('status') }}</x-ui.alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="space-y-4">
            <x-ui.field label="E-mail" name="email" required>
                <x-ui.input name="email" type="email" :value="old('email')" placeholder="voce@empresa.com" />
            </x-ui.field>
            <x-ui.button type="submit" class="w-full" icon="send">Enviar link</x-ui.button>
        </div>
    </form>

    <div class="mt-5 text-sm">
        <a href="{{ route('login') }}" class="text-primary-700 hover:underline dark:text-primary-300">Voltar ao login</a>
    </div>
</x-guest-layout>
