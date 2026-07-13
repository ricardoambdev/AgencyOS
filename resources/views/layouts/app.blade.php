<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    @include('partials.head')
</head>
<body class="h-full bg-app text-app antialiased">
    <div x-data="{ collapsed: localStorage.getItem('sidebar_collapsed') === '1', mobileOpen: false, dark: document.documentElement.classList.contains('dark') }"
         x-init="$watch('collapsed', v => localStorage.setItem('sidebar_collapsed', v ? '1' : '0'))"
         class="min-h-full">

        <!-- Sidebar (desktop) -->
        <aside class="fixed inset-y-0 left-0 z-40 hidden flex-col border-r border-app bg-app transition-[width] duration-300 lg:flex"
               :class="collapsed ? 'w-20' : 'w-64'">
            @include('components.layout.sidebar')
        </aside>

        <!-- Sidebar (mobile drawer) -->
        <div x-show="mobileOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
            <div x-show="mobileOpen" x-transition.opacity @click="mobileOpen = false" class="absolute inset-0 bg-neutral-900/50 backdrop-blur-sm"></div>
            <aside x-show="mobileOpen" x-transition class="absolute inset-y-0 left-0 flex w-72 flex-col border-r border-app bg-app shadow-card">
                @include('components.layout.sidebar', ['mobile' => true])
            </aside>
        </div>

        <!-- Main column -->
        <div class="flex min-h-full flex-col transition-[padding] duration-300 lg:pl-64"
             :class="collapsed ? 'lg:pl-20' : 'lg:pl-64'">
            @include('components.layout.header')

            <main id="ao-page" class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                @if($errors->any())
                    <x-ui.alert variant="danger" title="Verifique os campos" class="mb-4">
                        <ul class="list-disc space-y-0.5 pl-4">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </x-ui.alert>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>

        <x-ui.toast />
    </div>

    @livewireScripts
</body>
</html>
