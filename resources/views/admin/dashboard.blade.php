<x-admin.layout title="Painel Administrativo">
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-50">Painel Administrativo</h1>
        <p class="text-sm text-muted">Visão geral da operação e acesso rápido aos recursos.</p>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <x-ui.stat-card label="Leads" :value="$stats['leads']" icon="users" href="{{ route('admin.resource.index', 'leads') }}" />
        <x-ui.stat-card label="Clientes" :value="$stats['clientes']" icon="briefcase" href="{{ route('admin.resource.index', 'clientes') }}" />
        <x-ui.stat-card label="Projetos" :value="$stats['projetos']" icon="folder-kanban" href="{{ route('admin.resource.index', 'projetos') }}" />
        <x-ui.stat-card label="Usuários" :value="$stats['usuarios']" icon="user" href="{{ route('admin.resource.index', 'usuarios') }}" />
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-ui.card title="Recursos">
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @foreach($resources as $slug => $cfg)
                        <a href="{{ route('admin.resource.index', $slug) }}" class="flex items-center gap-3 rounded-xl border border-app px-3 py-3 transition-colors hover:border-primary-300 hover:bg-primary-50/40 dark:hover:bg-primary-900/10">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-neutral-100 text-muted dark:bg-neutral-800"><i data-lucide="{{ $cfg['icon'] ?? 'box' }}" class="sm"></i></span>
                            <span class="text-sm font-medium text-neutral-800 dark:text-neutral-100">{{ $cfg['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </x-ui.card>
        </div>

        <div>
            <x-ui.card title="Atividade recente" subtitle="Timeline da empresa">
                <ol class="relative ml-3 border-l border-app">
                    @forelse($recent as $item)
                        <li class="mb-4 ml-4">
                            <span class="absolute -left-[5px] mt-1 h-2.5 w-2.5 rounded-full bg-primary-500 ring-4 ring-app"></span>
                            <div class="text-sm font-medium text-neutral-800 dark:text-neutral-100">{{ $item->title }}</div>
                            <div class="text-xs text-muted">{{ $item->created_at->format('d/m H:i') }}</div>
                        </li>
                    @empty
                        <x-ui.empty-state icon="activity" title="Sem atividade" />
                    @endforelse
                </ol>
            </x-ui.card>
        </div>
    </div>
</x-admin.layout>
