<!DOCTYPE html>
<html lang="pt-BR" class="{{ session('theme','light') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal do Cliente') · AgencyOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class', theme: { extend: { colors: {
            primary: {
                50:'#F6FFF2',100:'#D9FAC9',200:'#D1FAB8',300:'#C2F269',400:'#BDF04D',
                500:'#A3D825',600:'#8EC91F',700:'#73A61A',800:'#5A7F16',900:'#3E5910'
            },
            neutral: {
                50:'#FFFFFF',100:'#F6FFF2',200:'#E8F2E3',300:'#D5DDD1',400:'#B5CBAA',
                500:'#9BAA94',600:'#7D8479',700:'#5F645C',800:'#4E514C',900:'#2E312D'
            }
        } } } };
        if (localStorage.theme === 'dark') document.documentElement.classList.add('dark');
    </script>
</head>
<body class="bg-neutral-50 dark:bg-neutral-900 text-neutral-800 dark:text-neutral-100 min-h-screen">
    <header class="bg-white dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-700">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <span class="font-bold text-lg">AgencyOS <span class="text-primary-600">· Área do Cliente</span></span>
            @isset($cliente)
                <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ $cliente->name }}</span>
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

    <footer class="max-w-4xl mx-auto px-6 py-8 text-center text-xs text-neutral-400">
        Acesso seguro via link exclusivo. Não compartilhe este endereço.
    </footer>
</body>
</html>
