<!DOCTYPE html>
<html lang="pt-BR" class="{{ session('theme','light') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal do Cliente') · AgencyOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class', theme: { extend: { colors: { brand: { 500: '#4f46e5', 600: '#4338ca' } } } } };
        if (localStorage.theme === 'dark') document.documentElement.classList.add('dark');
    </script>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 min-h-screen">
    <header class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <span class="font-bold text-lg">AgencyOS <span class="text-brand-500">· Área do Cliente</span></span>
            @isset($cliente)
                <span class="text-sm text-slate-500 dark:text-slate-400">{{ $cliente->name }}</span>
            @endisset
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-8">
        @if(session('status'))
            <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="max-w-4xl mx-auto px-6 py-8 text-center text-xs text-slate-400">
        Acesso seguro via link exclusivo. Não compartilhe este endereço.
    </footer>
</body>
</html>
