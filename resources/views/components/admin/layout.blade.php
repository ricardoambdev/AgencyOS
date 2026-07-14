<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    @include('partials.head')
</head>
<body class="h-full bg-app text-app antialiased">
    <div x-data="{ collapsed: localStorage.getItem('sidebar_collapsed') === '1', mobileOpen: false, dark: document.documentElement.classList.contains('dark') }"
         x-init="$watch('collapsed', v => localStorage.setItem('sidebar_collapsed', v ? '1' : '0'))"
         class="min-h-full">

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-40 hidden flex-col border-r border-app bg-app transition-[width] duration-300 lg:flex"
               :class="collapsed ? 'w-20' : 'w-64'">
            <div class="flex h-16 items-center gap-2.5 border-b border-app px-4">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary-600 text-white shadow-glow">
                    <i data-lucide="leaf"></i>
                </span>
                <span x-show="!collapsed" class="text-lg font-extrabold tracking-tight text-neutral-900 dark:text-neutral-50">Agency<span class="text-primary-600 dark:text-primary-400">OS</span> <span class="text-xs font-medium text-muted">Admin</span></span>
            </div>

            <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 py-4">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-200' : 'text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800' }}">
                    <i data-lucide="layout-dashboard" class="shrink-0 text-muted"></i>
                    <span x-show="!collapsed">Dashboard</span>
                </a>

                <div x-show="!collapsed" class="px-3 pb-1 pt-3 text-xs font-semibold uppercase tracking-wide text-muted">Recursos</div>

                @foreach(\App\Admin\Registry::all() as $slug => $cfg)
                    <a href="{{ route('admin.resource.index', $slug) }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors
                       {{ (request()->route('resource') === $slug) ? 'bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-200' : 'text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800' }}">
                        <i data-lucide="{{ $cfg['icon'] ?? 'box' }}" class="shrink-0 text-muted"></i>
                        <span x-show="!collapsed">{{ $cfg['label'] }}</span>
                    </a>
                @endforeach

                <div class="my-2 border-t border-app"></div>
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-600 transition-colors hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800">
                    <i data-lucide="arrow-left" class="shrink-0 text-muted"></i>
                    <span x-show="!collapsed">Voltar ao app</span>
                </a>
            </nav>

            @unless(isset($mobile))
                <div class="border-t border-app p-3">
                    <button type="button" @click="collapsed = !collapsed"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-500 transition-colors hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-800">
                        <i data-lucide="panel-left-close" x-show="!collapsed"></i>
                        <i data-lucide="panel-left-open" x-show="collapsed" x-cloak></i>
                        <span x-show="!collapsed">Recolher</span>
                    </button>
                </div>
            @endunless
        </aside>

        <!-- Mobile drawer -->
        <div x-show="mobileOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
            <div x-show="mobileOpen" x-transition.opacity @click="mobileOpen = false" class="absolute inset-0 bg-neutral-900/50 backdrop-blur-sm"></div>
            <aside x-show="mobileOpen" x-transition class="absolute inset-y-0 left-0 flex w-72 flex-col border-r border-app bg-app shadow-card">
                @include('admin.sidebar-mobile')
            </aside>
        </div>

        <div class="flex min-h-full flex-col transition-[padding] duration-300 lg:pl-64" :class="collapsed ? 'lg:pl-20' : 'lg:pl-64'">
            <header class="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-app bg-app/80 px-4 backdrop-blur-md sm:px-6 lg:px-8">
                <button type="button" @click="mobileOpen = true" class="rounded-lg p-2 text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800 lg:hidden">
                    <i data-lucide="menu"></i>
                </button>
                <nav class="flex items-center gap-1.5 text-sm" aria-label="Breadcrumb">
                    <a href="{{ route('admin.dashboard') }}" class="text-muted hover:text-primary-700 dark:hover:text-primary-300"><i data-lucide="layout-dashboard" class="sm"></i></a>
                    <i data-lucide="chevron-right" class="sm text-muted"></i>
                    <span class="font-semibold text-neutral-800 dark:text-neutral-100">{{ $title ?? 'Admin' }}</span>
                </nav>

                <div class="ml-auto flex items-center gap-1">
                    <button type="button"
                        @click="dark = !dark; document.documentElement.classList.toggle('dark', dark); localStorage.setItem('theme', dark ? 'dark' : 'light')"
                        class="rounded-lg p-2 text-neutral-600 transition-colors hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800" title="Tema">
                        <i data-lucide="moon" x-show="!dark"></i>
                        <i data-lucide="sun" x-show="dark" x-cloak></i>
                    </button>

                    <x-ui.dropdown align="right">
                        <x-slot:trigger>
                            <button class="flex items-center gap-2 rounded-xl p-1 pr-2 transition-colors hover:bg-neutral-100 dark:hover:bg-neutral-800">
                                <x-ui.avatar name="{{ auth()->user()->name }}" size="sm" />
                                <span class="hidden text-sm font-medium text-neutral-700 dark:text-neutral-200 sm:block">{{ auth()->user()->name }}</span>
                            </button>
                        </x-slot:trigger>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-neutral-700 transition-colors hover:bg-neutral-100 dark:text-neutral-200 dark:hover:bg-neutral-800">
                            <i data-lucide="arrow-left" class="sm text-muted"></i> Voltar ao app
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm text-red-600 transition-colors hover:bg-red-50 dark:hover:bg-red-900/20">
                                <i data-lucide="log-out" class="sm"></i> Sair
                            </button>
                        </form>
                    </x-ui.dropdown>
                </div>
            </header>

            <main id="ao-page" class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                @if($errors->any())
                    <x-ui.alert variant="danger" title="Verifique os campos" class="mb-4">
                        <ul class="list-disc space-y-0.5 pl-4">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </x-ui.alert>
                @endif
                {{ $slot ?? '' }}
            </main>
        </div>

        <x-ui.toast />
    </div>
    @livewireScripts
</body>
</html>
