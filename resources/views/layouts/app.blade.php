<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'AgencyOS' }}</title>
    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme');
                if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' };</script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
        .nav-link { @apply px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white; }
        .nav-link-active { @apply bg-gray-900 text-white; }

        html.dark body { background-color: #0f172a !important; }
        html.dark .bg-white { background-color: #1e293b !important; color: #e2e8f0 !important; }
        html.dark .text-gray-800 { color: #e2e8f0 !important; }
        html.dark .text-gray-700 { color: #cbd5e1 !important; }
        html.dark .text-gray-600 { color: #cbd5e1 !important; }
        html.dark .text-gray-500 { color: #94a3b8 !important; }
        html.dark .text-gray-400 { color: #94a3b8 !important; }
        html.dark .bg-gray-100 { background-color: #0f172a !important; }
        html.dark .bg-gray-50 { background-color: #1e293b !important; }
        html.dark .bg-gray-800 { background-color: #0f172a !important; }
        html.dark .bg-gray-900 { background-color: #020617 !important; }
        html.dark .border-gray-200 { border-color: #334155 !important; }
        html.dark .nav-link { color: #cbd5e1 !important; }
        html.dark .nav-link:hover { background-color: #334155 !important; color: #ffffff !important; }
        html.dark input, html.dark textarea, html.dark select {
            background-color: #0f172a !important; color: #e2e8f0 !important; border-color: #334155 !important;
        }
        html.dark .shadow, html.dark .shadow-sm, html.dark .shadow-md { box-shadow: none !important; }
    </style>
</head>
<body class="h-full bg-gray-100">
    <div class="min-h-full">
        @auth
        <nav class="bg-gray-800" x-data="{ open: false }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="shrink-0 text-white font-bold text-lg">AgencyOS</div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-1">
                                @foreach(\App\Core\Models\MenuItem::forCompany() as $menuItem)
                                    <a href="{{ $menuItem->href() }}" class="nav-link {{ $menuItem->isActive() ? 'nav-link-active' : '' }}">{{ $menuItem->label }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <form method="GET" action="{{ route('search.index') }}" class="hidden md:block">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Pesquisar..." class="rounded-md bg-gray-700 text-white px-3 py-1 text-sm focus:outline-none">
                        </form>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="relative text-gray-300 hover:text-white">
                                <span class="text-xl">🔔</span>
                                @php $unread = app(\App\Core\Engines\NotificationEngine::class)->unreadFor(auth()->id())->count(); @endphp
                                @if($unread) <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-xs px-1">{{ $unread }}</span> @endif
                            </button>
                            <div x-show="open" @click.away="open=false" x-cloak class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg z-50">
                                <div class="p-3 border-b font-semibold text-gray-700">Notificações</div>
                                @foreach(app(\App\Core\Engines\NotificationEngine::class)->allFor(auth()->id())->take(8)->get() as $n)
                                    <a href="{{ $n->link ?? '#' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-b {{ $n->read_at ? '' : 'bg-blue-50' }}">
                                        <div class="font-medium">{{ $n->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $n->body }}</div>
                                    </a>
                                @endforeach
                                <a href="{{ route('notifications.index') }}" class="block text-center text-sm text-blue-600 py-2">Ver todas</a>
                            </div>
                        </div>
                        <button type="button"
                            x-data="{ dark: document.documentElement.classList.contains('dark') }"
                            @click="dark = !dark; document.documentElement.classList.toggle('dark', dark); try { localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch(e) {}"
                            class="text-gray-300 hover:text-white text-lg leading-none" title="Alternar tema">
                            <span x-text="dark ? '☀️' : '🌙'"></span>
                        </button>
                        <select onchange="window.location='?switch_company='+this.value" class="rounded-md bg-gray-700 text-white text-sm px-2 py-1">
                            @foreach(auth()->user()->companies as $c)
                                <option value="{{ $c->pivot->company_id ?? $c->id }}" {{ (app(\App\Core\Support\CompanyContext::class)->id() == ($c->pivot->company_id ?? $c->id)) ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-gray-300 text-sm">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-gray-300 hover:text-white text-sm">Sair</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        @endauth

        <main>
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                @if(session('status'))
                    <div class="mb-4 rounded-md bg-green-100 px-4 py-2 text-sm text-green-800">{{ session('status') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-4 rounded-md bg-red-100 px-4 py-2 text-sm text-red-800">
                        @foreach($errors->all() as $error) <div>{{ $error }}</div> @endforeach
                    </div>
                @endif
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>
    </div>
    @livewireScripts
</body>
</html>
