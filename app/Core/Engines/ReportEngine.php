<?php

namespace App\Core\Engines;

use App\Core\Support\EntityCatalog;
use App\Domains\Crm\Models\Lead;
use App\Domains\Financeiro\Models\Invoice;
use App\Domains\Projeto\Models\Projeto;
use App\Domains\Projeto\Models\Task;
use App\Domains\Usuario\Models\User;
use Illuminate\Support\Facades\DB;

class ReportEngine
{
    public function kpis(): array
    {
        return [
            'leads' => Lead::count(),
            'leads_value' => (float) Lead::sum('value'),
            'clientes' => \App\Domains\Cliente\Models\Cliente::count(),
            'projetos_ativos' => Projeto::whereNotIn('status', ['finalizado'])->count(),
            'tarefas_abertas' => Task::whereNotIn('status', ['done'])->count(),
            'faturamento' => (float) Invoice::where('status', 'paid')->sum('total'),
            'a_receber' => (float) Invoice::whereIn('status', ['sent', 'overdue'])->sum('total'),
        ];
    }

    public function crmFunnel(): array
    {
        $labels = EntityCatalog::statusesFor(Lead::class);
        $rows = Lead::query()
            ->select('status', DB::raw('count(*) as total'), DB::raw('coalesce(sum(value),0) as value'))
            ->groupBy('status')
            ->get();

        return $this->shape($labels, $rows, 'status', 'total', 'value');
    }

    public function projectsByStatus(): array
    {
        $labels = EntityCatalog::statusesFor(Projeto::class);
        $rows = Projeto::query()
            ->select('status', DB::raw('count(*) as total'), DB::raw('coalesce(sum(budget),0) as value'))
            ->groupBy('status')
            ->get();

        return $this->shape($labels, $rows, 'status', 'total', 'value');
    }

    public function billingSummary(): array
    {
        $labels = EntityCatalog::statusesFor(Invoice::class);
        $rows = Invoice::query()
            ->select('status', DB::raw('count(*) as total'), DB::raw('coalesce(sum(total),0) as value'))
            ->groupBy('status')
            ->get();

        $byStatus = $this->shape($labels, $rows, 'status', 'total', 'value');

        return [
            'by_status' => $byStatus,
            'total_emitido' => (float) Invoice::sum('total'),
            'recebido' => (float) Invoice::where('status', 'paid')->sum('total'),
            'a_receber' => (float) Invoice::whereIn('status', ['sent', 'overdue'])->sum('total'),
            'atrasado' => (float) Invoice::where('status', 'overdue')->sum('total'),
        ];
    }

    public function billingByMonth(int $months = 6): array
    {
        $since = now()->subMonths($months - 1)->startOfMonth();

        $invoices = Invoice::query()
            ->where('issued_at', '>=', $since)
            ->select('issued_at', 'total')
            ->get();

        $map = [];
        foreach ($invoices as $invoice) {
            $key = $invoice->issued_at?->format('Y-m');
            if ($key) {
                $map[$key] = ($map[$key] ?? 0) + (float) $invoice->total;
            }
        }

        $series = [];
        for ($i = 0; $i < $months; $i++) {
            $key = now()->subMonths($months - 1 - $i)->format('Y-m');
            $series[] = [
                'mes' => $key,
                'label' => \Carbon\Carbon::createFromFormat('Y-m', $key)->translatedFormat('M/y'),
                'total' => (float) ($map[$key] ?? 0),
            ];
        }

        return $series;
    }

    public function tasksByStatus(): array
    {
        $labels = EntityCatalog::statusesFor(Task::class);
        $rows = Task::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        return $this->shape($labels, $rows, 'status', 'total', null);
    }

    public function tasksByPriority(): array
    {
        $labels = ['low' => 'Baixa', 'medium' => 'Média', 'high' => 'Alta', 'urgent' => 'Urgente'];
        $rows = Task::query()
            ->select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->get();

        return $this->shape($labels, $rows, 'priority', 'total', null);
    }

    public function workload(): array
    {
        return User::query()
            ->withCount(['assignedTasks as total' => fn ($q) => $q])
            ->withCount(['assignedTasks as open' => fn ($q) => $q->whereNotIn('status', ['done'])])
            ->get()
            ->map(fn ($u) => [
                'user' => $u->name,
                'total' => $u->total,
                'open' => $u->open,
            ])
            ->all();
    }

    public function export(string $report): array
    {
        return match ($report) {
            'crm' => [
                ['Status', 'Quantidade', 'Valor'],
                ...collect($this->crmFunnel())->map(fn ($r) => [$r['label'], $r['count'], number_format($r['value'], 2, ',', '.')]),
            ],
            'projetos' => [
                ['Status', 'Quantidade', 'Orçamento'],
                ...collect($this->projectsByStatus())->map(fn ($r) => [$r['label'], $r['count'], number_format($r['value'], 2, ',', '.')]),
            ],
            'faturamento' => [
                ['Status', 'Quantidade', 'Total'],
                ...collect($this->billingSummary()['by_status'])->map(fn ($r) => [$r['label'], $r['count'], number_format($r['value'], 2, ',', '.')]),
            ],
            'tarefas' => [
                ['Status', 'Quantidade'],
                ...collect($this->tasksByStatus())->map(fn ($r) => [$r['label'], $r['count']]),
            ],
            'carga' => [
                ['Usuário', 'Total de Tarefas', 'Em Aberto'],
                ...collect($this->workload())->map(fn ($r) => [$r['user'], $r['total'], $r['open']]),
            ],
            default => [['Relatório desconhecido']],
        };
    }

    protected function shape(array $labels, $rows, string $key, ?string $countCol, ?string $valueCol): array
    {
        $lookup = [];
        foreach ($rows as $row) {
            $lookup[$row->{$key}] = $row;
        }

        $max = 0;
        $result = [];

        foreach ($labels as $code => $label) {
            $row = $lookup[$code] ?? null;
            $count = $row ? (int) $row->{$countCol} : 0;
            $value = ($valueCol && $row) ? (float) $row->{$valueCol} : 0;
            $max = max($max, $count);
            $result[] = ['code' => $code, 'label' => $label, 'count' => $count, 'value' => $value];
        }

        foreach ($result as &$r) {
            $r['pct'] = $max > 0 ? round($r['count'] / $max * 100) : 0;
        }

        return $result;
    }
}
