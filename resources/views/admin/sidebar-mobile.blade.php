<div class="flex h-full flex-col">
    <div class="flex h-16 items-center gap-2.5 border-b border-app px-4">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary-600 text-white shadow-glow">
            <i data-lucide="leaf"></i>
        </span>
        <span class="text-lg font-extrabold tracking-tight text-neutral-900 dark:text-neutral-50">Agency<span class="text-primary-600 dark:text-primary-400">OS</span> <span class="text-xs font-medium text-muted">Admin</span></span>
        <button @click="mobileOpen = false" class="ml-auto rounded-lg p-1.5 text-muted hover:bg-neutral-100 dark:hover:bg-neutral-800">
            <i data-lucide="x"></i>
        </button>
    </div>

    <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 py-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-600 transition-colors hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800">
            <i data-lucide="layout-dashboard" class="shrink-0 text-muted"></i> Dashboard
        </a>
        <div class="px-3 pb-1 pt-3 text-xs font-semibold uppercase tracking-wide text-muted">Recursos</div>
        @foreach(\App\Admin\Registry::all() as $slug => $cfg)
            <a href="{{ route('admin.resource.index', $slug) }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-600 transition-colors hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800">
                <i data-lucide="{{ $cfg['icon'] ?? 'box' }}" class="shrink-0 text-muted"></i> {{ $cfg['label'] }}
            </a>
        @endforeach
        <div class="my-2 border-t border-app"></div>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-600 transition-colors hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800">
            <i data-lucide="arrow-left" class="shrink-0 text-muted"></i> Voltar ao app
        </a>
    </nav>
</div>
