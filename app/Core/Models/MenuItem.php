<?php

namespace App\Core\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Domains\Company\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    use BelongsToCompany;

    protected $table = 'menu_items';

    protected $fillable = [
        'company_id', 'label', 'route', 'match', 'url', 'icon', 'order', 'parent_id',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function href(): string
    {
        if ($this->url) {
            return $this->url;
        }

        if ($this->route) {
            return route($this->route);
        }

        return '#';
    }

    public function isActive(): bool
    {
        if ($this->match) {
            return request()->routeIs($this->match);
        }

        if ($this->route) {
            return request()->routeIs($this->route, $this->route . '.*');
        }

        return false;
    }

    public static function forCompany(): \Illuminate\Support\Collection
    {
        $companyId = app(\App\Core\Support\CompanyContext::class)->id();

        if ($companyId) {
            $items = static::query()->where('company_id', $companyId)->orderBy('order')->get();
            if ($items->isNotEmpty()) {
                return $items;
            }
        }

        return static::defaultItems();
    }

    public static function defaultItems(): \Illuminate\Support\Collection
    {
        $defaults = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'match' => 'dashboard'],
            ['label' => 'CRM', 'route' => 'leads.index', 'match' => 'leads.*'],
            ['label' => 'Clientes', 'route' => 'clientes.index', 'match' => 'clientes.*'],
            ['label' => 'Projetos', 'route' => 'projetos.index', 'match' => 'projetos.*'],
            ['label' => 'Templates', 'route' => 'templates.index', 'match' => 'templates.*'],
            ['label' => 'Agenda', 'route' => 'agenda.index', 'match' => 'agenda.*'],
            ['label' => 'Produção', 'route' => 'producao.index', 'match' => 'producao.*'],
            ['label' => 'Wiki', 'route' => 'wiki.index', 'match' => 'wiki.*'],
            ['label' => 'Equipamentos', 'route' => 'equipamentos.index', 'match' => 'equipamentos.*'],
            ['label' => 'Horas', 'route' => 'horas.index', 'match' => 'horas.*'],
            ['label' => 'Comercial', 'route' => 'comercial.index', 'match' => 'comercial.*'],
            ['label' => 'Financeiro', 'route' => 'financeiro.index', 'match' => 'financeiro.*'],
            ['label' => 'Relatórios', 'route' => 'relatorios.index', 'match' => 'relatorios.*'],
            ['label' => 'Favoritos', 'route' => 'favoritos.index', 'match' => 'favoritos.*'],
            ['label' => 'Workflows', 'route' => 'config.workflows.index', 'match' => 'config.workflows.*'],
            ['label' => 'Automações', 'route' => 'config.automations.index', 'match' => 'config.automations.*'],
            ['label' => 'Webhooks', 'route' => 'config.webhooks.index', 'match' => 'config.webhooks.*'],
            ['label' => 'Funções', 'route' => 'config.roles.index', 'match' => 'config.roles.*'],
            ['label' => 'Campos', 'route' => 'config.custom-fields.index', 'match' => 'config.custom-fields.*'],
            ['label' => 'Configurações', 'route' => 'config.index', 'match' => 'config.index,config.settings.*'],
        ];

        return collect($defaults)->map(fn ($d) => new static($d));
    }
}
