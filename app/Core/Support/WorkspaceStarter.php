<?php

namespace App\Core\Support;

use App\Core\Models\MenuItem;
use App\Core\Models\WorkflowState;
use App\Domains\Crm\Models\Lead;
use App\Domains\Cliente\Models\Cliente;
use App\Domains\Projeto\Models\Projeto;
use App\Domains\Projeto\Models\Task;
use App\Domains\Financeiro\Models\Invoice;
use App\Domains\Company\Models\Company;

class WorkspaceStarter
{
    public static function available(): array
    {
        return [
            'agency' => 'Agência / Estúdio',
            'clinic' => 'Clínica / Saúde',
            'ecommerce' => 'E-commerce / Varejo',
            'saas' => 'SaaS / Produto',
        ];
    }

    public static function seed(Company $company, string $template = 'agency'): void
    {
        $definition = self::definitions()[$template] ?? self::definitions()['agency'];

        foreach ($definition['states'] as $entityType => $states) {
            foreach ($states as $index => $state) {
                WorkflowState::create([
                    'company_id' => $company->id,
                    'entity_type' => $entityType,
                    'slug' => $state['slug'],
                    'name' => $state['name'],
                    'color' => $state['color'] ?? null,
                    'order' => $index,
                    'is_initial' => $index === 0,
                    'is_final' => $state['final'] ?? false,
                ]);
            }
        }

        $order = 0;
        foreach ($definition['menu'] as $item) {
            MenuItem::create([
                'company_id' => $company->id,
                'label' => $item['label'],
                'route' => $item['route'] ?? null,
                'url' => $item['url'] ?? null,
                'order' => $order++,
            ]);
        }
    }

    public static function definitions(): array
    {
        return [
            'agency' => [
                'states' => [
                    Lead::class => [
                        ['slug' => 'new', 'name' => 'Novo', 'color' => '#64748b'],
                        ['slug' => 'contact', 'name' => 'Contatado', 'color' => '#0ea5e9'],
                        ['slug' => 'qualified', 'name' => 'Qualificado', 'color' => '#8b5cf6'],
                        ['slug' => 'proposal', 'name' => 'Proposta', 'color' => '#f59e0b'],
                        ['slug' => 'won', 'name' => 'Ganho', 'color' => '#10b981', 'final' => true],
                        ['slug' => 'lost', 'name' => 'Perdido', 'color' => '#ef4444', 'final' => true],
                    ],
                    Projeto::class => [
                        ['slug' => 'briefing', 'name' => 'Briefing', 'color' => '#64748b'],
                        ['slug' => 'producao', 'name' => 'Produção', 'color' => '#0ea5e9'],
                        ['slug' => 'cliente', 'name' => 'Com Cliente', 'color' => '#f59e0b'],
                        ['slug' => 'finalizado', 'name' => 'Finalizado', 'color' => '#10b981', 'final' => true],
                    ],
                    Task::class => [
                        ['slug' => 'todo', 'name' => 'A fazer', 'color' => '#64748b'],
                        ['slug' => 'doing', 'name' => 'Em andamento', 'color' => '#0ea5e9'],
                        ['slug' => 'review', 'name' => 'Revisão', 'color' => '#f59e0b'],
                        ['slug' => 'done', 'name' => 'Concluída', 'color' => '#10b981', 'final' => true],
                    ],
                    Invoice::class => [
                        ['slug' => 'draft', 'name' => 'Rascunho', 'color' => '#64748b'],
                        ['slug' => 'sent', 'name' => 'Enviada', 'color' => '#0ea5e9'],
                        ['slug' => 'paid', 'name' => 'Paga', 'color' => '#10b981', 'final' => true],
                        ['slug' => 'overdue', 'name' => 'Vencida', 'color' => '#ef4444'],
                    ],
                ],
                'menu' => [
                    ['label' => 'Dashboard', 'route' => 'dashboard'],
                    ['label' => 'CRM', 'route' => 'leads.index'],
                    ['label' => 'Clientes', 'route' => 'clientes.index'],
                    ['label' => 'Projetos', 'route' => 'projetos.index'],
                    ['label' => 'Produção', 'route' => 'producao.index'],
                    ['label' => 'Financeiro', 'route' => 'financeiro.index'],
                    ['label' => 'Relatórios', 'route' => 'relatorios.index'],
                ],
            ],
            'clinic' => [
                'states' => [
                    Lead::class => [
                        ['slug' => 'new', 'name' => 'Novo Paciente', 'color' => '#64748b'],
                        ['slug' => 'contact', 'name' => 'Contatado', 'color' => '#0ea5e9'],
                        ['slug' => 'scheduled', 'name' => 'Agendado', 'color' => '#8b5cf6'],
                        ['slug' => 'attended', 'name' => 'Atendido', 'color' => '#10b981', 'final' => true],
                        ['slug' => 'lost', 'name' => 'Desistiu', 'color' => '#ef4444', 'final' => true],
                    ],
                    Projeto::class => [
                        ['slug' => 'triagem', 'name' => 'Triagem', 'color' => '#64748b'],
                        ['slug' => 'em_atendimento', 'name' => 'Em Atendimento', 'color' => '#0ea5e9'],
                        ['slug' => 'finalizado', 'name' => 'Finalizado', 'color' => '#10b981', 'final' => true],
                    ],
                    Task::class => [
                        ['slug' => 'todo', 'name' => 'A fazer', 'color' => '#64748b'],
                        ['slug' => 'doing', 'name' => 'Em andamento', 'color' => '#0ea5e9'],
                        ['slug' => 'done', 'name' => 'Concluída', 'color' => '#10b981', 'final' => true],
                    ],
                    Invoice::class => [
                        ['slug' => 'draft', 'name' => 'Rascunho', 'color' => '#64748b'],
                        ['slug' => 'sent', 'name' => 'Enviada', 'color' => '#0ea5e9'],
                        ['slug' => 'paid', 'name' => 'Paga', 'color' => '#10b981', 'final' => true],
                    ],
                ],
                'menu' => [
                    ['label' => 'Dashboard', 'route' => 'dashboard'],
                    ['label' => 'Pacientes', 'route' => 'leads.index'],
                    ['label' => 'Clientes', 'route' => 'clientes.index'],
                    ['label' => 'Agenda', 'route' => 'agenda.index'],
                    ['label' => 'Financeiro', 'route' => 'financeiro.index'],
                ],
            ],
            'ecommerce' => [
                'states' => [
                    Lead::class => [
                        ['slug' => 'new', 'name' => 'Novo Lead', 'color' => '#64748b'],
                        ['slug' => 'qualified', 'name' => 'Qualificado', 'color' => '#8b5cf6'],
                        ['slug' => 'won', 'name' => 'Comprou', 'color' => '#10b981', 'final' => true],
                        ['slug' => 'lost', 'name' => 'Sem interesse', 'color' => '#ef4444', 'final' => true],
                    ],
                    Projeto::class => [
                        ['slug' => 'briefing', 'name' => 'Briefing', 'color' => '#64748b'],
                        ['slug' => 'producao', 'name' => 'Produção', 'color' => '#0ea5e9'],
                        ['slug' => 'finalizado', 'name' => 'Entregue', 'color' => '#10b981', 'final' => true],
                    ],
                    Task::class => [
                        ['slug' => 'todo', 'name' => 'A fazer', 'color' => '#64748b'],
                        ['slug' => 'doing', 'name' => 'Em andamento', 'color' => '#0ea5e9'],
                        ['slug' => 'done', 'name' => 'Concluída', 'color' => '#10b981', 'final' => true],
                    ],
                    Invoice::class => [
                        ['slug' => 'draft', 'name' => 'Rascunho', 'color' => '#64748b'],
                        ['slug' => 'paid', 'name' => 'Paga', 'color' => '#10b981', 'final' => true],
                        ['slug' => 'overdue', 'name' => 'Vencida', 'color' => '#ef4444'],
                    ],
                ],
                'menu' => [
                    ['label' => 'Dashboard', 'route' => 'dashboard'],
                    ['label' => 'Leads', 'route' => 'leads.index'],
                    ['label' => 'Clientes', 'route' => 'clientes.index'],
                    ['label' => 'Projetos', 'route' => 'projetos.index'],
                    ['label' => 'Financeiro', 'route' => 'financeiro.index'],
                ],
            ],
            'saas' => [
                'states' => [
                    Lead::class => [
                        ['slug' => 'new', 'name' => 'Novo Lead', 'color' => '#64748b'],
                        ['slug' => 'trial', 'name' => 'Trial', 'color' => '#0ea5e9'],
                        ['slug' => 'paid', 'name' => 'Cliente Pago', 'color' => '#10b981', 'final' => true],
                        ['slug' => 'churned', 'name' => 'Churn', 'color' => '#ef4444', 'final' => true],
                    ],
                    Projeto::class => [
                        ['slug' => 'backlog', 'name' => 'Backlog', 'color' => '#64748b'],
                        ['slug' => 'doing', 'name' => 'Em Desenvolvimento', 'color' => '#0ea5e9'],
                        ['slug' => 'released', 'name' => 'Lançado', 'color' => '#10b981', 'final' => true],
                    ],
                    Task::class => [
                        ['slug' => 'todo', 'name' => 'A fazer', 'color' => '#64748b'],
                        ['slug' => 'doing', 'name' => 'Em andamento', 'color' => '#0ea5e9'],
                        ['slug' => 'done', 'name' => 'Concluída', 'color' => '#10b981', 'final' => true],
                    ],
                    Invoice::class => [
                        ['slug' => 'draft', 'name' => 'Rascunho', 'color' => '#64748b'],
                        ['slug' => 'sent', 'name' => 'Enviada', 'color' => '#0ea5e9'],
                        ['slug' => 'paid', 'name' => 'Paga', 'color' => '#10b981', 'final' => true],
                    ],
                ],
                'menu' => [
                    ['label' => 'Dashboard', 'route' => 'dashboard'],
                    ['label' => 'Leads', 'route' => 'leads.index'],
                    ['label' => 'Clientes', 'route' => 'clientes.index'],
                    ['label' => 'Projetos', 'route' => 'projetos.index'],
                    ['label' => 'Relatórios', 'route' => 'relatorios.index'],
                ],
            ],
        ];
    }
}
