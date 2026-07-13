<?php

namespace App\Core\Support;

use App\Domains\Usuario\Models\User;

class DashboardWidget
{
    public static function available(): array
    {
        return [
            'meus_projetos' => [
                'label' => 'Meus Projetos',
                'description' => 'Projetos sob sua responsabilidade',
            ],
            'minhas_tarefas' => [
                'label' => 'Minhas Tarefas',
                'description' => 'Tarefas atribuídas a você',
            ],
            'faturamento_mes' => [
                'label' => 'Faturamento do Mês',
                'description' => 'Total de faturas pagas no mês',
            ],
            'horas_mes' => [
                'label' => 'Horas do Mês',
                'description' => 'Lançamentos de horas no mês',
            ],
            'contratos_ativos' => [
                'label' => 'Contratos Ativos',
                'description' => 'Quantidade de contratos ativos',
            ],
            'pipeline_crm' => [
                'label' => 'Pipeline de CRM',
                'description' => 'Leads em negociação',
            ],
            'producao_pendente' => [
                'label' => 'Produção Pendente',
                'description' => 'Entregáveis em produção/revisão',
            ],
        ];
    }

    public static function defaultWidgets(): array
    {
        return ['meus_projetos', 'minhas_tarefas', 'faturamento_mes', 'horas_mes', 'contratos_ativos', 'pipeline_crm'];
    }

    public static function forUser(User $user): array
    {
        $selected = \App\Core\Models\UserDashboard::where('user_id', $user->id)
            ->orderBy('order')
            ->pluck('widget')
            ->toArray();

        return $selected ?: self::defaultWidgets();
    }
}
