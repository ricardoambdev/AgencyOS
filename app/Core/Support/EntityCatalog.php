<?php

namespace App\Core\Support;

class EntityCatalog
{
    public static function entityTypes(): array
    {
        return [
            \App\Domains\Crm\Models\Lead::class => 'Lead (CRM)',
            \App\Domains\Cliente\Models\Cliente::class => 'Cliente',
            \App\Domains\Projeto\Models\Projeto::class => 'Projeto',
            \App\Domains\Projeto\Models\Task::class => 'Tarefa',
            \App\Domains\Financeiro\Models\Invoice::class => 'Fatura',
        ];
    }

    public static function statusesFor(string $entityType): array
    {
        return match ($entityType) {
            \App\Domains\Crm\Models\Lead::class => [
                'new' => 'Novo', 'contact' => 'Contatado', 'qualified' => 'Qualificado',
                'proposal' => 'Proposta', 'won' => 'Ganho', 'lost' => 'Perdido',
            ],
            \App\Domains\Cliente\Models\Cliente::class => [
                'active' => 'Ativo', 'inactive' => 'Inativo',
            ],
            \App\Domains\Projeto\Models\Projeto::class => [
                'briefing' => 'Briefing', 'planejamento' => 'Planejamento', 'producao' => 'Produção',
                'revisao' => 'Revisão', 'cliente' => 'Com Cliente', 'finalizado' => 'Finalizado',
            ],
            \App\Domains\Projeto\Models\Task::class => [
                'pending' => 'Pendente', 'doing' => 'Em Andamento', 'review' => 'Revisão', 'done' => 'Concluída',
            ],
            \App\Domains\Financeiro\Models\Invoice::class => [
                'draft' => 'Rascunho', 'sent' => 'Enviada', 'paid' => 'Paga',
                'overdue' => 'Vencida', 'cancelled' => 'Cancelada',
            ],
            \App\Domains\Producao\Models\Entregavel::class => [
                'briefing' => 'Briefing', 'em_producao' => 'Em Produção', 'revisao' => 'Em Revisão',
                'aprovado' => 'Aprovado', 'entregue' => 'Entregue',
            ],
            \App\Domains\Wiki\Models\Artigo::class => [
                'rascunho' => 'Rascunho', 'publicado' => 'Publicado',
            ],
            \App\Domains\Comercial\Models\Contrato::class => [
                'rascunho' => 'Rascunho', 'ativo' => 'Ativo', 'encerrado' => 'Encerrado', 'cancelado' => 'Cancelado',
            ],
            default => [],
        };
    }

    public static function events(): array
    {
        return [
            'LeadCreated' => 'Lead criado',
            'ClientCreated' => 'Cliente criado',
            'ProjectCreated' => 'Projeto criado',
            'TaskCreated' => 'Tarefa criada',
        ];
    }

    public static function fieldsFor(string $entityType): array
    {
        $common = ['status' => 'Status', 'owner_id' => 'Responsável'];

        return match ($entityType) {
            \App\Domains\Crm\Models\Lead::class => array_merge($common, ['value' => 'Valor']),
            \App\Domains\Projeto\Models\Projeto::class => array_merge($common, ['budget' => 'Orçamento']),
            \App\Domains\Projeto\Models\Task::class => array_merge($common, ['priority' => 'Prioridade', 'due_date' => 'Data de entrega']),
            \App\Domains\Cliente\Models\Cliente::class => $common,
            \App\Domains\Financeiro\Models\Invoice::class => array_merge($common, ['total' => 'Total']),
            default => $common,
        };
    }

    public static function actionTypes(): array
    {
        return [
            'notify' => 'Notificar usuário',
            'timeline' => 'Registrar na timeline',
            'webhook' => 'Chamar webhook (HTTP)',
        ];
    }

    public static function eventClassFor(string $event): ?string
    {
        return match ($event) {
            'LeadCreated' => \App\Domains\Crm\Events\LeadCreated::class,
            'ClientCreated' => \App\Domains\Cliente\Events\ClientCreated::class,
            'ProjectCreated' => \App\Domains\Projeto\Events\ProjectCreated::class,
            'TaskCreated' => \App\Domains\Projeto\Events\TaskCreated::class,
            default => null,
        };
    }
}
