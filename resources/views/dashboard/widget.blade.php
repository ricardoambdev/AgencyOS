@php
    $title = \App\Core\Support\DashboardWidget::available()[$key]['label'] ?? $key;
@endphp

<x-ui.card>
    <h3 class="font-semibold text-[var(--text)] mb-3">{{ $title }}</h3>

    @switch($key)
        @case('meus_projetos')
            @php
                $items = \App\Domains\Projeto\Models\Projeto::where('owner_id', auth()->id())
                    ->latest()->take(6)->get();
            @endphp
            <ul class="divide-y divide-[var(--border)] text-sm">
                @forelse($items as $p)
                    <li class="py-2 flex justify-between"><a href="{{ route('projetos.show', $p) }}" class="text-primary-700 dark:text-primary-300">{{ $p->name }}</a><span class="text-[var(--text-muted)]">{{ $p->status }}</span></li>
                @empty
                    <li class="text-[var(--text-muted)]">Nenhum projeto.</li>
                @endforelse
            </ul>
            @break

        @case('minhas_tarefas')
            @php
                $items = \App\Domains\Projeto\Models\Task::where('assignee_id', auth()->id())
                    ->where('status', '!=', 'done')->with('project')->take(6)->get();
            @endphp
            <ul class="divide-y divide-[var(--border)] text-sm">
                @forelse($items as $t)
                    <li class="py-2"><span class="text-[var(--text)]">{{ $t->title }}</span> <span class="text-xs text-[var(--text-muted)]">- {{ $t->project->name ?? '' }}</span></li>
                @empty
                    <li class="text-[var(--text-muted)]">Nenhuma tarefa.</li>
                @endforelse
            </ul>
            @break

        @case('faturamento_mes')
            @php
                $valor = \App\Domains\Financeiro\Models\Invoice::where('status', 'paid')
                    ->whereBetween('issued_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('total');
            @endphp
            <div class="text-2xl font-bold text-[var(--brand)]">R$ {{ number_format($valor ?? 0, 2, ',', '.') }}</div>
            <p class="text-xs text-[var(--text-muted)]">Faturas pagas em {{ now()->format('m/Y') }}</p>
            @break

        @case('horas_mes')
            @php
                $horas = \App\Domains\Horas\Models\LancamentoHora::whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])->sum('hours');
            @endphp
            <div class="text-2xl font-bold text-[var(--text)]">{{ number_format($horas, 2, ',', '.') }}h</div>
            <p class="text-xs text-[var(--text-muted)]">Lançadas em {{ now()->format('m/Y') }}</p>
            @break

        @case('contratos_ativos')
            @php
                $total = \App\Domains\Comercial\Models\Contrato::where('status', 'ativo')->count();
            @endphp
            <div class="text-2xl font-bold text-[var(--text)]">{{ $total }}</div>
            <p class="text-xs text-[var(--text-muted)]">Contratos ativos</p>
            @break

        @case('pipeline_crm')
            @php
                $total = \App\Domains\Crm\Models\Lead::whereIn('status', ['qualificado', 'proposta'])->count();
            @endphp
            <div class="text-2xl font-bold text-[var(--text)]">{{ $total }}</div>
            <p class="text-xs text-[var(--text-muted)]">Leads em negociação</p>
            @break

        @case('producao_pendente')
            @php
                $total = \App\Domains\Producao\Models\Entregavel::whereIn('status', ['em_producao', 'revisao'])->count();
            @endphp
            <div class="text-2xl font-bold text-[var(--text)]">{{ $total }}</div>
            <p class="text-xs text-[var(--text-muted)]">Entregáveis em produção/revisão</p>
            @break

        @default
            <p class="text-sm text-[var(--text-muted)]">Widget indisponível.</p>
    @endswitch
</x-ui.card>
