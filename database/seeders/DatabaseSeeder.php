<?php

namespace Database\Seeders;

use App\Core\Support\CompanyContext;
use App\Core\Models\Automation;
use App\Core\Models\Webhook;
use App\Core\Models\Workflow;
use App\Domains\Agenda\Models\Evento;
use App\Domains\Crm\Models\Lead;
use App\Domains\Cliente\Models\Cliente;
use App\Domains\Comercial\Models\Contrato;
use App\Domains\Config\Models\CustomField;
use App\Domains\Projeto\Models\ProjectTemplate;
use App\Domains\Company\Models\Company;
use App\Domains\Company\Models\CompanyUser;
use App\Domains\Company\Models\Role;
use App\Domains\Financeiro\Models\Invoice;
use App\Domains\Projeto\Models\Projeto;
use App\Domains\Projeto\Models\Task;
use App\Domains\Equipamento\Models\Equipamento;
use App\Domains\Horas\Models\LancamentoHora;
use App\Domains\Producao\Models\Entregavel;
use App\Domains\Wiki\Models\Artigo;
use App\Domains\Usuario\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::create([
            'name' => 'Agência Demo',
            'slug' => 'agencia-demo',
            'workspace_template' => 'agency',
        ]);

        $role = Role::create([
            'company_id' => $company->id,
            'name' => 'Proprietário',
            'slug' => 'owner',
            'capabilities' => ['*'],
        ]);

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@agencyos.com',
            'password' => Hash::make('password'),
        ]);

        CompanyUser::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'role_id' => $role->id,
            'status' => 'active',
            'accepted_at' => now(),
        ]);

        app(CompanyContext::class)->set($company->id);

        $cliente = Cliente::create([
            'name' => 'Cliente Exemplo LTDA',
            'email' => 'contato@cliente.com',
            'document' => '12.345.678/0001-90',
            'type' => 'company',
            'owner_id' => $user->id,
        ]);

        Lead::create([
            'name' => 'Lead Interessado',
            'email' => 'lead@teste.com',
            'company_name' => 'Empresa Teste',
            'source' => 'Site',
            'status' => 'new',
            'value' => 5000,
            'owner_id' => $user->id,
        ]);

        $projeto = Projeto::create([
            'client_id' => $cliente->id,
            'name' => 'Site Institucional',
            'status' => 'producao',
            'owner_id' => $user->id,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'budget' => 15000,
            'description' => 'Criação de site institucional.',
        ]);

        Task::create([
            'project_id' => $projeto->id,
            'title' => 'Briefing com cliente',
            'status' => 'done',
            'priority' => 'high',
            'assignee_id' => $user->id,
        ]);

        Task::create([
            'project_id' => $projeto->id,
            'title' => 'Desenvolvimento das telas',
            'status' => 'doing',
            'priority' => 'medium',
            'assignee_id' => $user->id,
        ]);

        Evento::create([
            'title' => 'Reunião de kickoff',
            'start_at' => now()->addDays(2)->setHour(14),
            'user_id' => $user->id,
            'project_id' => $projeto->id,
        ]);

        Invoice::create([
            'number' => 'FAT-0001',
            'client_id' => $cliente->id,
            'project_id' => $projeto->id,
            'status' => 'sent',
            'issued_at' => now(),
            'due_at' => now()->addDays(15),
            'subtotal' => 15000,
            'tax' => 0,
            'total' => 15000,
        ]);

        Workflow::create([
            'company_id' => $company->id,
            'name' => 'Fluxo de Projetos',
            'entity_type' => Projeto::class,
            'active' => true,
            'definition' => [
                'states' => [
                    'briefing' => 'Briefing', 'planejamento' => 'Planejamento',
                    'producao' => 'Produção', 'revisao' => 'Revisão',
                    'cliente' => 'Com Cliente', 'finalizado' => 'Finalizado',
                ],
                'transitions' => [
                    ['from' => 'briefing', 'to' => 'planejamento'],
                    ['from' => 'planejamento', 'to' => 'producao'],
                    ['from' => 'producao', 'to' => 'revisao'],
                    ['from' => 'revisao', 'to' => 'cliente'],
                    ['from' => 'cliente', 'to' => 'finalizado'],
                    ['from' => 'revisao', 'to' => 'producao'],
                ],
            ],
        ]);

        Automation::create([
            'company_id' => $company->id,
            'name' => 'Avisar dono do lead',
            'event' => 'LeadCreated',
            'active' => true,
            'conditions' => [],
            'actions' => [
                ['type' => 'notify', 'title' => 'Novo lead recebido', 'body' => 'Um novo lead foi criado no CRM.', 'link' => null, 'user_id' => null],
                ['type' => 'timeline', 'title' => 'Lead capturado via automação'],
            ],
        ]);

        Automation::create([
            'company_id' => $company->id,
            'name' => 'Avisar dono do projeto',
            'event' => 'ProjectCreated',
            'active' => true,
            'conditions' => [],
            'actions' => [
                ['type' => 'notify', 'title' => 'Projeto iniciado', 'body' => 'Um novo projeto foi criado.', 'link' => null, 'user_id' => null],
            ],
        ]);

        Webhook::create([
            'company_id' => $company->id,
            'name' => 'Exemplo (Zapier/Make)',
            'url' => 'https://example.com/hooks/agencyos',
            'secret' => 'secretdemo',
            'events' => ['LeadCreated', 'ProjectCreated'],
            'headers' => ['X-AgencyOS-Source' => 'agencia-demo'],
            'active' => true,
        ]);

        Entregavel::create([
            'project_id' => $projeto->id,
            'name' => 'Homepage - Versão final',
            'type' => 'site',
            'status' => 'revisao',
            'owner_id' => $user->id,
            'due_date' => now()->addDays(10),
            'description' => 'Layout responsivo da home com hero, serviços e depoimentos.',
            'client_visible' => true,
        ]);

        Entregavel::create([
            'project_id' => $projeto->id,
            'name' => 'Pack de redes sociais (5 peças)',
            'type' => 'social',
            'status' => 'em_producao',
            'owner_id' => $user->id,
            'due_date' => now()->addDays(5),
            'description' => 'Posts para Instagram e LinkedIn.',
        ]);

        Artigo::create([
            'title' => 'Como enviar arquivos para o cliente',
            'body' => "1. Aces o projeto.\n2. Abra a aba de entregáveis.\n3. Faça upload do arquivo e marque como visível no portal.\n4. Avise o cliente pela aprovação.",
            'category' => 'processos',
            'status' => 'publicado',
            'author_id' => $user->id,
            'client_visible' => true,
        ]);

        Artigo::create([
            'title' => 'Padrão de nomenclatura de arquivos',
            'body' => "Use sempre: cliente_peca_versao.ext\nExemplo: acme_logo_v3.ai",
            'category' => 'marcas',
            'status' => 'publicado',
            'author_id' => $user->id,
        ]);

        Artigo::create([
            'title' => 'Onboarding de novos colaboradores',
            'body' => "Apresente as ferramentas internas, o fluxo de aprovação e a base de conhecimento.",
            'category' => 'treinamento',
            'status' => 'rascunho',
            'author_id' => $user->id,
        ]);

        Equipamento::create([
            'name' => 'MacBook Pro 16"',
            'type' => 'hardware',
            'status' => 'em_uso',
            'owner_id' => $user->id,
            'serial' => 'MBP-2024-001',
            'purchase_date' => now()->subMonths(8),
            'description' => 'Notebook de design.',
        ]);

        Equipamento::create([
            'name' => 'Câmera DSLR',
            'type' => 'hardware',
            'status' => 'disponivel',
            'serial' => 'CAM-001',
            'description' => 'Câmera para produção audiovisual.',
        ]);

        LancamentoHora::create([
            'user_id' => $user->id,
            'project_id' => $projeto->id,
            'date' => now()->subDays(2),
            'hours' => 4,
            'description' => 'Briefing e estruturação',
            'billable' => true,
        ]);

        LancamentoHora::create([
            'user_id' => $user->id,
            'project_id' => $projeto->id,
            'task_id' => Task::where('project_id', $projeto->id)->first()?->id,
            'date' => now()->subDay(),
            'hours' => 6,
            'description' => 'Desenvolvimento das telas',
            'billable' => true,
        ]);

        Contrato::create([
            'client_id' => $cliente->id,
            'responsavel_id' => $user->id,
            'title' => 'Contrato de Retainer Mensal',
            'type' => 'retainer',
            'value' => 12000,
            'currency' => 'BRL',
            'start_date' => now()->startOfMonth(),
            'renewal_type' => 'mensal',
            'renewal_date' => now()->addMonth()->startOfMonth(),
            'status' => 'ativo',
            'signed_at' => now()->subDays(10),
            'description' => 'Retainer mensal de design e desenvolvimento.',
        ]);

        Contrato::create([
            'client_id' => $cliente->id,
            'responsavel_id' => $user->id,
            'title' => 'Projeto Website Institucional',
            'type' => 'fixed',
            'value' => 45000,
            'currency' => 'BRL',
            'start_date' => now()->subDays(20),
            'end_date' => now()->addMonths(2),
            'status' => 'ativo',
            'signed_at' => now()->subDays(18),
            'description' => 'Criação de site institucional.',
        ]);

        $template = ProjectTemplate::create([
            'name' => 'Website Institucional',
            'description' => 'Estrutura padrão para projetos de site.',
            'is_active' => true,
        ]);
        $template->templateTasks()->createMany([
            ['title' => 'Kickoff com cliente', 'priority' => 'high', 'estimated_hours' => 2, 'order' => 0],
            ['title' => 'Wireframes', 'priority' => 'high', 'estimated_hours' => 8, 'order' => 1],
            ['title' => 'Design visual', 'priority' => 'medium', 'estimated_hours' => 16, 'order' => 2],
            ['title' => 'Implementação', 'priority' => 'medium', 'estimated_hours' => 40, 'order' => 3],
            ['title' => 'Homologação', 'priority' => 'low', 'estimated_hours' => 6, 'order' => 4],
        ]);

        CustomField::create([
            'entity_type' => \App\Domains\Projeto\Models\Projeto::class,
            'name' => 'canal_aquisicao',
            'label' => 'Canal de Aquisição',
            'type' => 'select',
            'options' => ['indicacao' => 'Indicação', 'google' => 'Google', 'social' => 'Redes Sociais', 'outbound' => 'Outbound'],
            'is_filterable' => true,
            'is_required' => false,
            'order' => 1,
        ]);

        $memberRole = Role::create([
            'company_id' => $company->id,
            'name' => 'Membro',
            'slug' => 'member',
            'capabilities' => [
                'dashboard.view', 'relatorios.view',
                'crm.view', 'clientes.view', 'projetos.view', 'agenda.view',
                'producao.view', 'wiki.view', 'equipamentos.view', 'horas.view',
                'comercial.view', 'financeiro.view',
            ],
        ]);

        $member = User::create([
            'name' => 'Colaborador',
            'email' => 'membro@agencyos.com',
            'password' => Hash::make('password'),
        ]);

        \App\Domains\Company\Models\CompanyUser::create([
            'company_id' => $company->id,
            'user_id' => $member->id,
            'role_id' => $memberRole->id,
            'status' => 'active',
            'accepted_at' => now(),
        ]);

        foreach ([
            'company_name' => 'AgencyOS Demo',
            'timezone' => 'America/Sao_Paulo',
            'currency' => 'BRL',
            'language' => 'pt-BR',
            'portal_subdomain' => 'demo',
        ] as $key => $value) {
            \App\Core\Models\Setting::create([
                'company_id' => $company->id,
                'key' => $key,
                'value' => $value,
            ]);
        }

        $this->command->info('Demo criado: admin@agencyos.com / password (Proprietário) e membro@agencyos.com / password (Membro)');
    }
}
