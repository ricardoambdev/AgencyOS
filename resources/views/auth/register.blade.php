<x-guest-layout>
    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-50">Criar nova empresa</h1>
    <p class="mb-6 mt-1 text-sm text-muted">Crie sua empresa e comece a usar o AgencyOS.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="space-y-4">
            <x-ui.field label="Seu nome" name="name" required>
                <x-ui.input name="name" :value="old('name')" placeholder="Seu nome" />
            </x-ui.field>
            <x-ui.field label="E-mail" name="email" required>
                <x-ui.input name="email" type="email" :value="old('email')" placeholder="voce@empresa.com" />
            </x-ui.field>
            <x-ui.field label="Empresa" name="company" required>
                <x-ui.input name="company" :value="old('company')" placeholder="Nome da empresa" />
            </x-ui.field>
            <x-ui.field label="Segmento" name="workspace_template">
                <x-ui.select name="workspace_template" :options="\App\Core\Support\WorkspaceStarter::available()" :selected="old('workspace_template', 'agency')" />
            </x-ui.field>
            <x-ui.field label="Senha" name="password" required>
                <x-ui.input name="password" type="password" placeholder="••••••••" />
            </x-ui.field>
            <x-ui.field label="Confirmar senha" name="password_confirmation" required>
                <x-ui.input name="password_confirmation" type="password" placeholder="••••••••" />
            </x-ui.field>
            <x-ui.button type="submit" class="w-full" icon="building-2">Criar empresa</x-ui-button>
        </div>
    </form>

    <p class="mt-5 text-sm text-muted">Já tem conta? <a href="{{ route('login') }}" class="text-primary-700 hover:underline dark:text-primary-300">Entrar</a></p>
</x-guest-layout>
