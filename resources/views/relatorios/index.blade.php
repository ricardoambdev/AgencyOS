@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('dashboard') }}" class="text-sm text-primary-700 dark:text-primary-300">&larr; Painel</a>
            <h1 class="text-2xl font-bold text-[var(--text)]">Relatórios</h1>
            <p class="text-sm text-[var(--text-muted)]">Métricas da empresa ativa. Exporte em CSV quando necessário.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-[var(--surface)] shadow rounded-lg p-4">
            <div class="text-xs text-[var(--text-muted)] uppercase">Leads</div>
            <div class="text-2xl font-bold text-[var(--text)]">{{ $kpis['leads'] }}</div>
            <div class="text-xs text-[var(--text-muted)]">R$ {{ number_format($kpis['leads_value'], 2, ',', '.') }} em potencial</div>
        </div>
        <div class="bg-[var(--surface)] shadow rounded-lg p-4">
            <div class="text-xs text-[var(--text-muted)] uppercase">Clientes</div>
            <div class="text-2xl font-bold text-[var(--text)]">{{ $kpis['clientes'] }}</div>
        </div>
        <div class="bg-[var(--surface)] shadow rounded-lg p-4">
            <div class="text-xs text-[var(--text-muted)] uppercase">Projetos ativos</div>
            <div class="text-2xl font-bold text-[var(--text)]">{{ $kpis['projetos_ativos'] }}</div>
            <div class="text-xs text-[var(--text-muted)]">{{ $kpis['tarefas_abertas'] }} tarefas em aberto</div>
        </div>
        <div class="bg-[var(--surface)] shadow rounded-lg p-4">
            <div class="text-xs text-[var(--text-muted)] uppercase">Faturamento</div>
            <div class="text-2xl font-bold text-[var(--text)]">R$ {{ number_format($kpis['faturamento'], 2, ',', '.') }}</div>
            <div class="text-xs text-[var(--text-muted)]">R$ {{ number_format($kpis['a_receber'], 2, ',', '.') }} a receber</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <section class="bg-[var(--surface)] shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-[var(--text)]">Funil do CRM</h2>
                <div class="flex gap-2 text-xs"><a href="{{ route('relatorios.export', 'crm') }}" class="text-primary-700 dark:text-primary-300 hover:underline">CSV</a><a href="{{ route('relatorios.export', 'crm') }}?format=xlsx" class="text-primary-700 dark:text-primary-300 hover:underline">Excel</a><a href="{{ route('relatorios.export', 'crm') }}?format=pdf" class="text-primary-700 dark:text-primary-300 hover:underline">PDF</a></div>
            </div>
            @foreach($crm as $r)
                @include('relatorios._bar', ['label' => $r['label'], 'count' => $r['count'], 'value' => $r['value'], 'pct' => $r['pct']])
            @endforeach
        </section>

        <section class="bg-[var(--surface)] shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-[var(--text)]">Projetos por status</h2>
                <div class="flex gap-2 text-xs"><a href="{{ route('relatorios.export', 'projetos') }}" class="text-primary-700 dark:text-primary-300 hover:underline">CSV</a><a href="{{ route('relatorios.export', 'projetos') }}?format=xlsx" class="text-primary-700 dark:text-primary-300 hover:underline">Excel</a><a href="{{ route('relatorios.export', 'projetos') }}?format=pdf" class="text-primary-700 dark:text-primary-300 hover:underline">PDF</a></div>
            </div>
            @foreach($projetos as $r)
                @include('relatorios._bar', ['label' => $r['label'], 'count' => $r['count'], 'value' => $r['value'], 'pct' => $r['pct']])
            @endforeach
        </section>

        <section class="bg-[var(--surface)] shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-[var(--text)]">Faturamento</h2>
                <div class="flex gap-2 text-xs"><a href="{{ route('relatorios.export', 'faturamento') }}" class="text-primary-700 dark:text-primary-300 hover:underline">CSV</a><a href="{{ route('relatorios.export', 'faturamento') }}?format=xlsx" class="text-primary-700 dark:text-primary-300 hover:underline">Excel</a><a href="{{ route('relatorios.export', 'faturamento') }}?format=pdf" class="text-primary-700 dark:text-primary-300 hover:underline">PDF</a></div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                <div class="bg-[var(--surface-2)] rounded p-2"><span class="text-[var(--text-muted)]">Emitido:</span> R$ {{ number_format($faturamento['total_emitido'], 2, ',', '.') }}</div>
                <div class="bg-[var(--surface-2)] rounded p-2"><span class="text-[var(--text-muted)]">Recebido:</span> R$ {{ number_format($faturamento['recebido'], 2, ',', '.') }}</div>
                <div class="bg-[var(--surface-2)] rounded p-2"><span class="text-[var(--text-muted)]">A receber:</span> R$ {{ number_format($faturamento['a_receber'], 2, ',', '.') }}</div>
                <div class="bg-[var(--surface-2)] rounded p-2"><span class="text-[var(--text-muted)]">Atrasado:</span> R$ {{ number_format($faturamento['atrasado'], 2, ',', '.') }}</div>
            </div>
            @foreach($faturamento['by_status'] as $r)
                @include('relatorios._bar', ['label' => $r['label'], 'count' => $r['count'], 'value' => $r['value'], 'pct' => $r['pct']])
            @endforeach
        </section>

        <section class="bg-[var(--surface)] shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-[var(--text)]">Faturamento por mês</h2>
            </div>
            @php $maxMes = collect($faturamento_mes)->max('total') ?: 1; @endphp
            @foreach($faturamento_mes as $m)
                <div class="mb-2">
                    <div class="flex justify-between text-sm mb-1"><span>{{ $m['label'] }}</span><span class="text-[var(--text-muted)]">R$ {{ number_format($m['total'], 2, ',', '.') }}</span></div>
                    <div class="w-full h-2.5 bg-[var(--surface-2)] rounded-full overflow-hidden"><div class="h-full bg-[var(--brand)]" style="width: {{ $maxMes ? round($m['total'] / $maxMes * 100) : 0 }}%"></div></div>
                </div>
            @endforeach
        </section>

        <section class="bg-[var(--surface)] shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-[var(--text)]">Tarefas por status</h2>
                <div class="flex gap-2 text-xs"><a href="{{ route('relatorios.export', 'tarefas') }}" class="text-primary-700 dark:text-primary-300 hover:underline">CSV</a><a href="{{ route('relatorios.export', 'tarefas') }}?format=xlsx" class="text-primary-700 dark:text-primary-300 hover:underline">Excel</a><a href="{{ route('relatorios.export', 'tarefas') }}?format=pdf" class="text-primary-700 dark:text-primary-300 hover:underline">PDF</a></div>
            </div>
            @foreach($tarefas as $r)
                @include('relatorios._bar', ['label' => $r['label'], 'count' => $r['count'], 'pct' => $r['pct']])
            @endforeach
        </section>

        <section class="bg-[var(--surface)] shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-[var(--text)]">Tarefas por prioridade</h2>
            </div>
            @php $maxP = collect($prioridades)->max('count') ?: 1; @endphp
            @foreach($prioridades as $r)
                <div class="mb-2">
                    <div class="flex justify-between text-sm mb-1"><span>{{ $r['label'] }}</span><span class="text-[var(--text-muted)]">{{ $r['count'] }}</span></div>
                    <div class="w-full h-2.5 bg-[var(--surface-2)] rounded-full overflow-hidden"><div class="h-full bg-amber-500" style="width: {{ $maxP ? round($r['count'] / $maxP * 100) : 0 }}%"></div></div>
                </div>
            @endforeach
        </section>

        <section class="bg-[var(--surface)] shadow rounded-lg p-6 lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-[var(--text)]">Carga de trabalho</h2>
                <div class="flex gap-2 text-xs"><a href="{{ route('relatorios.export', 'carga') }}" class="text-primary-700 dark:text-primary-300 hover:underline">CSV</a><a href="{{ route('relatorios.export', 'carga') }}?format=xlsx" class="text-primary-700 dark:text-primary-300 hover:underline">Excel</a><a href="{{ route('relatorios.export', 'carga') }}?format=pdf" class="text-primary-700 dark:text-primary-300 hover:underline">PDF</a></div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="text-left text-xs text-[var(--text-muted)] uppercase"><th class="py-2">Usuário</th><th>Total</th><th>Em aberto</th></tr></thead>
                    <tbody>
                        @forelse($carga as $w)
                            <tr class="border-t"><td class="py-2">{{ $w['user'] }}</td><td>{{ $w['total'] }}</td><td>{{ $w['open'] }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="py-2 text-[var(--text-muted)]">Nenhum usuário.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
