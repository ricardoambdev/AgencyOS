<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    @include('partials.head')
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="h-full bg-app text-app antialiased">
    <div class="flex min-h-full">
        <!-- Painel lateral decorativo -->
        <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-primary-700 p-10 text-white lg:flex">
            <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-primary-500/40 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-10 h-72 w-72 rounded-full bg-primary-400/30 blur-3xl"></div>
            <div class="relative flex items-center gap-2.5">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15"><i data-lucide="leaf"></i></span>
                <span class="text-xl font-extrabold tracking-tight">Agency<span class="text-primary-100">OS</span></span>
            </div>
            <div class="relative">
                <h2 class="text-3xl font-bold leading-tight">A plataforma operacional da sua agência.</h2>
                <p class="mt-3 max-w-sm text-primary-50/90">CRM, projetos, financeiro e automações em um só lugar, com a identidade da Sentapúa.</p>
            </div>
            <p class="relative text-sm text-primary-100/70">© {{ date('Y') }} AgencyOS</p>
        </div>

        <!-- Conteúdo -->
        <div class="flex w-full items-center justify-center p-6 lg:w-1/2">
            <div class="w-full max-w-md" x-data="{ dark: document.documentElement.classList.contains('dark') }">
                <div class="mb-6 flex items-center justify-between lg:hidden">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary-600 text-white"><i data-lucide="leaf"></i></span>
                        <span class="text-lg font-extrabold text-neutral-900 dark:text-neutral-50">Agency<span class="text-primary-600 dark:text-primary-400">OS</span></span>
                    </div>
                    <button @click="dark = !dark; document.documentElement.classList.toggle('dark', dark); localStorage.setItem('theme', dark ? 'dark':'light')" class="rounded-lg p-2 text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800">
                        <i data-lucide="moon" x-show="!dark"></i><i data-lucide="sun" x-show="dark" x-cloak></i>
                    </button>
                </div>
                <div class="rounded-2xl surface p-8 shadow-card">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
    @livewireScripts
</body>
</html>
