<x-guest-layout>
    <div class="mx-auto max-w-md mt-16 bg-white p-8 rounded-lg shadow">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">Criar nova empresa</h1>
        <p class="text-sm text-gray-500 mb-6">Crie sua empresa e comece a usar o AgencyOS.</p>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Seu nome</label>
                <input type="text" name="name" value="{{ old('name') }}" class="mt-1 w-full rounded-md border-gray-300 border px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border-gray-300 border px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Empresa</label>
                <input type="text" name="company" value="{{ old('company') }}" class="mt-1 w-full rounded-md border-gray-300 border px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Segmento</label>
                <select name="workspace_template" class="mt-1 w-full rounded-md border-gray-300 border px-3 py-2">
                    @foreach(\App\Core\Support\WorkspaceStarter::available() as $key => $label)
                        <option value="{{ $key }}" {{ old('workspace_template', 'agency') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" name="password" class="mt-1 w-full rounded-md border-gray-300 border px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Confirmar senha</label>
                <input type="password" name="password_confirmation" class="mt-1 w-full rounded-md border-gray-300 border px-3 py-2" required>
            </div>
            <button class="w-full bg-indigo-600 text-white py-2 rounded-md font-medium hover:bg-indigo-700">Criar empresa</button>
        </form>
        <p class="mt-4 text-sm text-gray-600">Já tem conta? <a href="{{ route('login') }}" class="text-indigo-600">Entrar</a></p>
    </div>
</x-guest-layout>
