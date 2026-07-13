@extends('layouts.app')
@section('content')
    @auth
        @unless(auth()->user()->hasVerifiedEmail())
            <x-ui.alert variant="warning" title="Confirme seu e-mail" class="mb-6">
                Confirme seu e-mail para garantir o acesso completo. Verifique sua caixa de entrada.
                <form method="POST" action="{{ route('verification.send') }}" class="mt-2">
                    @csrf
                    <button class="font-semibold underline">Reenviar link</button>
                </form>
            </x-ui.alert>
        @endunless
    @endauth

    @php
        $companyId = app(\App\Core\Support\CompanyContext::class)->id();
        $revenue = \App\Domains\Financeiro\Models\Invoice::where('company_id', $companyId)
            ->where('status', 'paid')
            ->where('issued_at', '>=', now()->subMonths(5)->startOfMonth())
            ->get()
            ->groupBy(fn ($i) => $i->issued_at->format('Y-m'))
            ->sortKeys()
            ->map(fn ($g) => $g->sum('total'));
        $revLabels = $revenue->keys()->map(fn ($m) => \Carbon\Carbon::createFromFormat('Y-m', $m)->format('M'))->toArray();
        $revValues = $revenue->values()->toArray();
        $projByStatus = \App\Domains\Projeto\Models\Projeto::where('company_id', $companyId)
            ->selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');
    @endphp

    <!-- Cabeçalho -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-50">Olá, {{ auth()->user()->name }} 👋</h1>
            <p class="text-sm text-muted">Aqui está o resumo da sua operação hoje.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <x-ui.button href="{{ route('leads.create') }}" icon="user-plus">Novo lead</x-ui.button>
            <x-ui.button href="{{ route('projetos.create') }}" variant="secondary" icon="folder-plus">Projeto</x-ui.button>
            <x-ui.button href="{{ route('relatorios.index') }}" variant="ghost" icon="download">Relatório</x-ui.button>
        </div>
    </div>

    <!-- Stat cards -->
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <x-ui.stat-card label="Leads" :value="$stats['leads']" icon="users" href="{{ route('leads.index') }}" trend="up" delta="Ativos" />
        <x-ui.stat-card label="Clientes" :value="$stats['clientes']" icon="briefcase" href="{{ route('clientes.index') }}" trend="up" delta="Ativos" />
        <x-ui.stat-card label="Projetos" :value="$stats['projetos']" icon="folder-kanban" href="{{ route('projetos.index') }}" />
        <x-ui.stat-card label="Tarefas abertas" :value="$stats['tasks']" icon="list-todo" />
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Coluna principal -->
        <div class="space-y-6 lg:col-span-2">
            <x-ui.card title="Receita (6 meses)" subtitle="Faturações pagas">
                <div class="h-64"><canvas id="revenueChart" data-labels='@json($revLabels)' data-values='@json($revValues)'></canvas></div>
            </x-ui.card>

            <x-ui.card title="Minhas tarefas" header="<a href='{{ route('horas.index') }}' class='text-xs text-primary-700 hover:underline dark:text-primary-300'>Ver horas</a>">
                <div class="space-y-2">
                    @forelse($myTasks as $task)
                        <div class="flex items-center justify-between gap-3 rounded-xl border border-app px-3 py-2.5 transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-800/60">
                            <div class="min-w-0">
                                <div class="truncate text-sm font-medium text-neutral-800 dark:text-neutral-100">{{ $task->title }}</div>
                                <div class="truncate text-xs text-muted">{{ $task->project->name ?? '' }}</div>
                            </div>
                            <x-ui.badge>{{ $task->status }}</x-ui.badge>
                        </div>
                    @empty
                        <x-ui.empty-state icon="list-todo" title="Nenhuma tarefa atribuída" />
                    @endforelse
                </div>
            </x-ui.card>

            <x-ui.card title="Próximos eventos">
                <div class="space-y-3">
                    @forelse($events as $event)
                        <div class="flex items-center gap-3 border-b border-app pb-3 last:border-0 last:pb-0">
                            <div class="flex h-10 w-10 shrink-0 flex-col items-center justify-center rounded-xl bg-primary-100 text-primary-800 dark:bg-primary-900/40 dark:text-primary-200">
                                <span class="text-xs font-bold leading-none">{{ $event->start_at->format('d') }}</span>
                                <span class="text-[10px] uppercase">{{ $event->start_at->format('M') }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="truncate text-sm font-medium text-neutral-800 dark:text-neutral-100">{{ $event->title }}</div>
                                <div class="text-xs text-muted">{{ $event->start_at->format('H:i') }}</div>
                            </div>
                        </div>
                    @empty
                        <x-ui.empty-state icon="calendar" title="Nenhum evento" />
                    @endforelse
                </div>
            </x-ui.card>
        </div>

        <!-- Coluna lateral -->
        <div class="space-y-6">
            <x-ui.card title="Financeiro">
                <div class="mb-4 h-44"><canvas id="financeChart" data-receber="{{ $financial['receber'] }}" data-recebido="{{ $financial['recebido'] }}"></canvas></div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-muted">A receber</span><span class="font-semibold text-neutral-800 dark:text-neutral-100">R$ {{ number_format($financial['receber'], 2, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span class="text-muted">Recebido</span><span class="font-semibold text-emerald-600 dark:text-emerald-400">R$ {{ number_format($financial['recebido'], 2, ',', '.') }}</span></div>
                </div>
            </x-ui.card>

            <x-ui.card title="Pipeline de projetos">
                <div class="h-48"><canvas id="pipelineChart" data-status='@json($projByStatus)'></canvas></div>
            </x-ui.card>

            <x-ui.card title="Atividade recente" subtitle="Últimas movimentações">
                <ol class="relative ml-3 border-l border-app">
                    @foreach($timeline as $item)
                        <li class="mb-4 ml-4">
                            <span class="absolute -left-[5px] mt-1 h-2.5 w-2.5 rounded-full bg-primary-500 ring-4 ring-app"></span>
                            <div class="text-sm font-medium text-neutral-800 dark:text-neutral-100">{{ $item->title }}</div>
                            <div class="text-xs text-muted">{{ $item->created_at->format('d/m H:i') }}</div>
                        </li>
                    @endforeach
                </ol>
            </x-ui.card>
        </div>
    </div>

    @if(! empty($widgets))
        <div class="mt-6">
            <h2 class="mb-3 text-lg font-semibold text-neutral-800 dark:text-neutral-100">Meus Widgets</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($widgets as $key)
                    @include('dashboard.widget', ['key' => $key])
                @endforeach
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        function renderDashboardCharts() {
            if (!window.Chart) return;
            var isDark = document.documentElement.classList.contains('dark');
            var grid = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(46,49,45,0.06)';
            var txt = isDark ? '#B5CBAA' : '#7D8479';

            var rc = document.getElementById('revenueChart');
            if (rc && !rc.dataset.done) {
                new Chart(rc, {
                    type: 'line',
                    data: { labels: JSON.parse(rc.dataset.labels || '[]'),
                        datasets: [{ label: 'Receita', data: JSON.parse(rc.dataset.values || '[]'),
                            borderColor: '#73A61A', backgroundColor: 'rgba(138,201,31,0.15)',
                            fill: true, tension: 0.4, pointRadius: 3, borderWidth: 2 }] },
                    options: { responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { x: { grid: { color: grid }, ticks: { color: txt } }, y: { grid: { color: grid }, ticks: { color: txt, callback: v => 'R$ ' + v } } } }
                });
                rc.dataset.done = '1';
            }

            var fc = document.getElementById('financeChart');
            if (fc && !fc.dataset.done) {
                new Chart(fc, {
                    type: 'doughnut',
                    data: { labels: ['A receber', 'Recebido'],
                        datasets: [{ data: [parseFloat(fc.dataset.receber), parseFloat(fc.dataset.recebido)],
                            backgroundColor: ['#B5CBAA', '#73A61A'], borderWidth: 0 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '68%',
                        plugins: { legend: { position: 'bottom', labels: { color: txt } } } }
                });
                fc.dataset.done = '1';
            }

            var pc = document.getElementById('pipelineChart');
            if (pc && !pc.dataset.done) {
                var st = JSON.parse(pc.dataset.status || '{}');
                new Chart(pc, {
                    type: 'bar',
                    data: { labels: Object.keys(st), datasets: [{ label: 'Projetos', data: Object.values(st),
                        backgroundColor: '#8EC91F', borderRadius: 6 }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } },
                        scales: { x: { grid: { display: false }, ticks: { color: txt } }, y: { grid: { color: grid }, ticks: { color: txt, precision: 0 } } } }
                });
                pc.dataset.done = '1';
            }
        }
        document.addEventListener('DOMContentLoaded', renderDashboardCharts);
        document.addEventListener('livewire:navigated', renderDashboardCharts);
    </script>
@endsection
