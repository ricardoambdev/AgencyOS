@php
    $nav = \App\Core\Models\MenuItem::tree();
    $currentLabel = 'Dashboard';
    $parentLabel = null;
    foreach ($nav as $item) {
        if (!empty($item->children) && count($item->children)) {
            foreach ($item->children as $child) {
                if ($child->isActive()) { $currentLabel = $child->label; $parentLabel = $item->label; break 2; }
            }
        } elseif ($item->isActive()) {
            $currentLabel = $item->label; break;
        }
    }
@endphp

<header class="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-app bg-app/80 px-4 backdrop-blur-md sm:px-6 lg:px-8">
    <button type="button" @click="mobileOpen = true" class="rounded-lg p-2 text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800 lg:hidden">
        <i data-lucide="menu"></i>
    </button>

    <!-- Breadcrumb -->
    <nav class="hidden min-w-0 items-center gap-1.5 text-sm sm:flex" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}" class="text-muted transition-colors hover:text-primary-700 dark:hover:text-primary-300">
            <i data-lucide="house" class="sm"></i>
        </a>
        @if($parentLabel)
            <i data-lucide="chevron-right" class="sm text-muted"></i>
            <span class="font-medium text-muted">{{ $parentLabel }}</span>
        @endif
        <i data-lucide="chevron-right" class="sm text-muted"></i>
        <span class="truncate font-semibold text-neutral-800 dark:text-neutral-100">{{ $currentLabel }}</span>
    </nav>

    <!-- Busca global -->
    <form method="GET" action="{{ route('search.index') }}" class="ml-auto hidden items-center md:flex">
        <div class="relative">
            <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"></i>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Buscar..."
                class="w-56 rounded-xl border border-neutral-300 bg-white py-2 pl-9 pr-3 text-sm text-neutral-800 placeholder-neutral-400 focus-ring dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100 lg:w-72">
        </div>
    </form>

    <div class="flex items-center gap-1">
        <!-- Tema -->
        <button type="button"
            @click="dark = !dark; document.documentElement.classList.toggle('dark', dark); localStorage.setItem('theme', dark ? 'dark' : 'light')"
            class="rounded-lg p-2 text-neutral-600 transition-colors hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800"
            title="Alternar tema">
            <i data-lucide="moon" x-show="!dark"></i>
            <i data-lucide="sun" x-show="dark" x-cloak></i>
        </button>

        <!-- Notificações -->
        <div x-data="{ open: false }" @keydown.escape.window="open = false" class="relative">
            <button type="button" @click="open = !open" @click.away="open = false" class="relative rounded-lg p-2 text-neutral-600 transition-colors hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800">
                <i data-lucide="bell"></i>
                @php $unread = app(\App\Core\Engines\NotificationEngine::class)->unreadFor(auth()->id())->count(); @endphp
                @if($unread) <span class="absolute right-1.5 top-1.5 flex h-2 w-2"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-primary-500 opacity-75"></span><span class="relative inline-flex h-2 w-2 rounded-full bg-primary-600"></span></span> @endif
            </button>
            <div x-show="open" x-transition x-cloak class="absolute right-0 mt-2 w-80 overflow-hidden rounded-2xl surface shadow-card border border-app">
                <div class="flex items-center justify-between border-b border-app px-4 py-3">
                    <span class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">Notificações</span>
                    <a href="{{ route('notifications.index') }}" class="text-xs text-primary-700 hover:underline dark:text-primary-300">Ver todas</a>
                </div>
                <div class="max-h-80 overflow-y-auto">
                    @foreach(app(\App\Core\Engines\NotificationEngine::class)->allFor(auth()->id())->take(8)->get() as $n)
                        <a href="{{ $n->link ?? '#' }}" class="block border-b border-app px-4 py-3 text-sm transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-800/60 {{ $n->read_at ? '' : 'bg-primary-50/50 dark:bg-primary-900/10' }}">
                            <div class="font-medium text-neutral-800 dark:text-neutral-100">{{ $n->title }}</div>
                            <div class="text-xs text-muted">{{ $n->body }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Trocar empresa -->
        <select onchange="window.location='?switch_company='+this.value"
            class="hidden rounded-xl border border-neutral-300 bg-white px-2 py-1.5 text-sm text-neutral-700 focus-ring lg:block dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-200">
            @foreach(auth()->user()->companies as $c)
                <option value="{{ $c->pivot->company_id ?? $c->id }}" {{ (app(\App\Core\Support\CompanyContext::class)->id() == ($c->pivot->company_id ?? $c->id)) ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select>

        <!-- Perfil -->
        <x-ui.dropdown align="right">
            <x-slot:trigger>
                <button class="flex items-center gap-2 rounded-xl p-1 pr-2 transition-colors hover:bg-neutral-100 dark:hover:bg-neutral-800">
                    <x-ui.avatar name="{{ auth()->user()->name }}" size="sm" status />
                    <span class="hidden text-sm font-medium text-neutral-700 dark:text-neutral-200 sm:block">{{ auth()->user()->name }}</span>
                    <i data-lucide="chevron-down" class="sm text-muted"></i>
                </button>
            </x-slot:trigger>

            <a href="{{ route('config.index') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-neutral-700 transition-colors hover:bg-neutral-100 dark:text-neutral-200 dark:hover:bg-neutral-800">
                <i data-lucide="settings" class="sm text-muted"></i> Configurações
            </a>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-neutral-700 transition-colors hover:bg-neutral-100 dark:text-neutral-200 dark:hover:bg-neutral-800">
                <i data-lucide="layout-dashboard" class="sm text-muted"></i> Meu painel
            </a>
            <div class="my-1 border-t border-app"></div>
            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm text-red-600 transition-colors hover:bg-red-50 dark:hover:bg-red-900/20">
                    <i data-lucide="log-out" class="sm"></i> Sair
                </button>
            </form>
        </x-ui.dropdown>
    </div>
</header>
