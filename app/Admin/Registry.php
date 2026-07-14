<?php

namespace App\Admin;

class Registry
{
    /**
     * @return array<string, array>
     */
    public static function all(): array
    {
        return [
            'leads' => [
                'model' => \App\Domains\Crm\Models\Lead::class,
                'label' => 'Leads', 'icon' => 'users',
                'searchable' => ['name', 'email', 'company_name'],
            ],
            'clientes' => [
                'model' => \App\Domains\Cliente\Models\Cliente::class,
                'label' => 'Clientes', 'icon' => 'briefcase',
                'searchable' => ['name', 'email', 'document'],
            ],
            'projetos' => [
                'model' => \App\Domains\Projeto\Models\Projeto::class,
                'label' => 'Projetos', 'icon' => 'folder-kanban',
                'searchable' => ['name', 'status'],
            ],
            'tarefas' => [
                'model' => \App\Domains\Projeto\Models\Task::class,
                'label' => 'Tarefas', 'icon' => 'list-todo',
                'searchable' => ['title', 'status'],
            ],
            'templates' => [
                'model' => \App\Domains\Projeto\Models\ProjectTemplate::class,
                'label' => 'Templates de Projeto', 'icon' => 'layout-template',
                'searchable' => ['name'],
            ],
            'invoices' => [
                'model' => \App\Domains\Financeiro\Models\Invoice::class,
                'label' => 'Faturas', 'icon' => 'file-text',
                'searchable' => ['number', 'status'],
            ],
            'contratos' => [
                'model' => \App\Domains\Comercial\Models\Contrato::class,
                'label' => 'Contratos', 'icon' => 'file-signature',
                'searchable' => ['title', 'status'],
            ],
            'eventos' => [
                'model' => \App\Domains\Agenda\Models\Evento::class,
                'label' => 'Eventos', 'icon' => 'calendar',
                'searchable' => ['title'],
            ],
            'equipamentos' => [
                'model' => \App\Domains\Equipamento\Models\Equipamento::class,
                'label' => 'Equipamentos', 'icon' => 'monitor',
                'searchable' => ['name', 'serial'],
            ],
            'horas' => [
                'model' => \App\Domains\Horas\Models\LancamentoHora::class,
                'label' => 'Lançamentos de Hora', 'icon' => 'clock',
                'searchable' => ['description'],
            ],
            'wiki' => [
                'model' => \App\Domains\Wiki\Models\Artigo::class,
                'label' => 'Artigos Wiki', 'icon' => 'book-open',
                'searchable' => ['title', 'category'],
            ],
            'campos' => [
                'model' => \App\Domains\Config\Models\CustomField::class,
                'label' => 'Campos Personalizados', 'icon' => 'form-input',
                'searchable' => ['name', 'label'],
            ],
            'workflows' => [
                'model' => \App\Core\Models\Workflow::class,
                'label' => 'Workflows', 'icon' => 'git-branch',
                'searchable' => ['name', 'entity_type'],
            ],
            'automacoes' => [
                'model' => \App\Core\Models\Automation::class,
                'label' => 'Automações', 'icon' => 'zap',
                'searchable' => ['name', 'event'],
            ],
            'webhooks' => [
                'model' => \App\Core\Models\Webhook::class,
                'label' => 'Webhooks', 'icon' => 'webhook',
                'searchable' => ['name', 'url'],
            ],
            'menu' => [
                'model' => \App\Core\Models\MenuItem::class,
                'label' => 'Itens de Menu', 'icon' => 'menu',
                'searchable' => ['label', 'route'],
            ],
            'workflow-states' => [
                'model' => \App\Core\Models\WorkflowState::class,
                'label' => 'Estados de Workflow', 'icon' => 'flag',
                'searchable' => ['name', 'entity_type', 'slug'],
            ],
            'empresas' => [
                'model' => \App\Domains\Company\Models\Company::class,
                'label' => 'Empresas', 'icon' => 'building-2',
                'searchable' => ['name', 'slug'],
                'hidden_fields' => ['id', 'slug', 'created_at', 'updated_at'],
            ],
            'usuarios' => [
                'model' => \App\Domains\Usuario\Models\User::class,
                'label' => 'Usuários', 'icon' => 'user',
                'searchable' => ['name', 'email'],
                'hidden_fields' => ['id', 'email_verified_at', 'created_at', 'updated_at', 'remember_token'],
            ],
            'funcoes' => [
                'model' => \App\Domains\Company\Models\Role::class,
                'label' => 'Funções', 'icon' => 'shield',
                'searchable' => ['name', 'slug'],
            ],
        ];
    }

    public static function get(string $slug): ?Definition
    {
        $all = static::all();
        return isset($all[$slug]) ? new Definition(array_merge($all[$slug], ['slug' => $slug])) : null;
    }
}
