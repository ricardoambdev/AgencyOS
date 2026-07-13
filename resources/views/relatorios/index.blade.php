@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600">&larr; Painel</a>
            <h1 class="text-2xl font-bold text-gray-800">Relatórios</h1>
            <p class="text-sm text-gray-500">Métricas da empresa ativa. Exporte em CSV quando necessário.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white shadow rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Leads</div>
            <div class="text-2xl font-bold text-gray-800">{{ $kpis['leads'] }}</div>
            <div class="text-xs text-gray-400">R$ {{ number_format($kpis['leads_value'], 2, ',', '.') }} em potencial</div>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Clientes</div>
            <div class="text-2xl font-bold text-gray-800">{{ $kpis['clientes'] }}</div>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Projetos ativos</div>
            <div class="text-2xl font-bold text-gray-800">{{ $kpis['projetos_ativos'] }}</div>
            <div class="text-xs text-gray-400">{{ $kpis['tarefas_abertas'] }} tarefas em aberto</div>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Faturamento</div>
            <div class="text-2xl font-bold text-gray-800">R$ {{ number_format($kpis['faturamento'], 2, ',', '.') }}</div>
            <div class="text-xs text-gray-400">R$ {{ number_format($kpis['a_receber'], 2, ',', '.') }} a receber</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <section class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-gray-800">Funil do CRM</h2>
                <div class="flex gap-2 text-xs"><a href="{{ route('relatorios.export', 'crm') }}" class="text-indigo-600 hover:underline">CSV</a><a href="{{ route('relatorios.export', 'crm') }}?format=xlsx" class="text-indigo-600 hover:underline">Excel</a><a href="{{ route('relatorios.export', 'crm') }}?format=pdf" class="text-indigo-600 hover:underline">PDF</a></div>
            </div>
            @foreach($crm as $r)
                @include('relatorios._bar', ['label' => $r['label'], 'count' => $r['count'], 'value' => $r['value'], 'pct' => $r['pct']])
            @endforeach
        </section>

        <section class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-gray-800">Projetos por status</h2>
                <div class="flex gap-2 text-xs"><a href="{{ route('relatorios.export', 'projetos') }}" class="text-indigo-600 hover:underline">CSV</a><a href="{{ route('relatorios.export', 'projetos') }}?format=xlsx" class="text-indigo-600 hover:underline">Excel</a><a href="{{ route('relatorios.export', 'projetos') }}?format=pdf" class="text-indigo-600 hover:underline">PDF</a></div>
            </div>
            @foreach($projetos as $r)
                @include('relatorios._bar', ['label' => $r['label'], 'count' => $r['count'], 'value' => $r['value'], 'pct' => $r['pct']])
            @endforeach
        </section>

        <section class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-gray-800">Faturamento</h2>
                <div class="flex gap-2 text-xs"><a href="{{ route('relatorios.export', 'faturamento') }}" class="text-indigo-600 hover:underline">CSV</a><a href="{{ route('relatorios.export', 'faturamento') }}?format=xlsx" class="text-indigo-600 hover:underline">Excel</a><a href="{{ route('relatorios.export', 'faturamento') }}?format=pdf" class="text-indigo-600 hover:underline">PDF</a></div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                <div class="bg-gray-50 rounded p-2"><span class="text-gray-500">Emitido:</span> R$ {{ number_format($faturamento['total_emitido'], 2, ',', '.') }}</div>
                <div class="bg-gray-50 rounded p-2"><span class="text-gray-500">Recebido:</span> R$ {{ number_format($faturamento['recebido'], 2, ',', '.') }}</div>
                <div class="bg-gray-50 rounded p-2"><span class="text-gray-500">A receber:</span> R$ {{ number_format($faturamento['a_receber'], 2, ',', '.') }}</div>
                <div class="bg-gray-50 rounded p-2"><span class="text-gray-500">Atrasado:</span> R$ {{ number_format($faturamento['atrasado'], 2, ',', '.') }}</div>
            </div>
            @foreach($faturamento['by_status'] as $r)
                @include('relatorios._bar', ['label' => $r['label'], 'count' => $r['count'], 'value' => $r['value'], 'pct' => $r['pct']])
            @endforeach
        </section>

        <section class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-gray-800">Faturamento por mês</h2>
            </div>
            @php $maxMes = collect($faturamento_mes)->max('total') ?: 1; @endphp
            @foreach($faturamento_mes as $m)
                <div class="mb-2">
                    <div class="flex justify-between text-sm mb-1"><span>{{ $m['label'] }}</span><span class="text-gray-500">R$ {{ number_format($m['total'], 2, ',', '.') }}</span></div>
                    <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-emerald-500" style="width: {{ $maxMes ? round($m['total'] / $maxMes * 100) : 0 }}%"></div></div>
                </div>
            @endforeach
        </section>

        <section class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-gray-800">Tarefas por status</h2>
                <div class="flex gap-2 text-xs"><a href="{{ route('relatorios.export', 'tarefas') }}" class="text-indigo-600 hover:underline">CSV</a><a href="{{ route('relatorios.export', 'tarefas') }}?format=xlsx" class="text-indigo-600 hover:underline">Excel</a><a href="{{ route('relatorios.export', 'tarefas') }}?format=pdf" class="text-indigo-600 hover:underline">PDF</a></div>
            </div>
            @foreach($tarefas as $r)
                @include('relatorios._bar', ['label' => $r['label'], 'count' => $r['count'], 'pct' => $r['pct']])
            @endforeach
        </section>

        <section class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-gray-800">Tarefas por prioridade</h2>
            </div>
            @php $maxP = collect($prioridades)->max('count') ?: 1; @endphp
            @foreach($prioridades as $r)
                <div class="mb-2">
                    <div class="flex justify-between text-sm mb-1"><span>{{ $r['label'] }}</span><span class="text-gray-500">{{ $r['count'] }}</span></div>
                    <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-amber-500" style="width: {{ $maxP ? round($r['count'] / $maxP * 100) : 0 }}%"></div></div>
                </div>
            @endforeach
        </section>

        <section class="bg-white shadow rounded-lg p-6 lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-gray-800">Carga de trabalho</h2>
                <div class="flex gap-2 text-xs"><a href="{{ route('relatorios.export', 'carga') }}" class="text-indigo-600 hover:underline">CSV</a><a href="{{ route('relatorios.export', 'carga') }}?format=xlsx" class="text-indigo-600 hover:underline">Excel</a><a href="{{ route('relatorios.export', 'carga') }}?format=pdf" class="text-indigo-600 hover:underline">PDF</a></div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="text-left text-xs text-gray-500 uppercase"><th class="py-2">Usuário</th><th>Total</th><th>Em aberto</th></tr></thead>
                    <tbody>
                        @forelse($carga as $w)
                            <tr class="border-t"><td class="py-2">{{ $w['user'] }}</td><td>{{ $w['total'] }}</td><td>{{ $w['open'] }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="py-2 text-gray-400">Nenhum usuário.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
