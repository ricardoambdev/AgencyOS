<?php

namespace App\Core\Support;

class Capabilities
{
    public static function groups(): array
    {
        return [
            'Geral' => [
                'dashboard.view' => 'Ver dashboard',
                'relatorios.view' => 'Ver relatórios',
            ],
            'CRM & Clientes' => [
                'crm.view' => 'Ver CRM',
                'crm.manage' => 'Gerenciar CRM',
                'clientes.view' => 'Ver clientes',
                'clientes.manage' => 'Gerenciar clientes',
            ],
            'Projetos & Produção' => [
                'projetos.view' => 'Ver projetos',
                'projetos.manage' => 'Gerenciar projetos',
                'agenda.view' => 'Ver agenda',
                'agenda.manage' => 'Gerenciar agenda',
                'producao.view' => 'Ver produção',
                'producao.manage' => 'Gerenciar produção',
                'wiki.view' => 'Ver wiki',
                'wiki.manage' => 'Gerenciar wiki',
            ],
            'Operacional' => [
                'equipamentos.view' => 'Ver equipamentos',
                'equipamentos.manage' => 'Gerenciar equipamentos',
                'horas.view' => 'Ver horas',
                'horas.manage' => 'Gerenciar horas',
                'comercial.view' => 'Ver comercial',
                'comercial.manage' => 'Gerenciar comercial',
            ],
            'Financeiro' => [
                'financeiro.view' => 'Ver financeiro',
                'financeiro.manage' => 'Gerenciar financeiro',
            ],
            'Configuração' => [
                'config.manage' => 'Gerenciar configurações (workflows, webhooks, campos, funções)',
            ],
        ];
    }

    public static function all(): array
    {
        $flat = [];

        foreach (self::groups() as $caps) {
            $flat = array_merge($flat, $caps);
        }

        return $flat;
    }

    public static function has(string $key): bool
    {
        return array_key_exists($key, self::all());
    }
}
