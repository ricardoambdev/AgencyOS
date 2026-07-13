<?php

namespace App\Core\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Domains\Company\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
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
        return static::tree();
    }

    /**
     * Retorna o menu como árvore (itens de topo + possíveis filhos).
     */
    public static function tree(): \Illuminate\Support\Collection
    {
        $companyId = app(\App\Core\Support\CompanyContext::class)->id();

        if ($companyId) {
            $all = static::query()->where('company_id', $companyId)->orderBy('order')->get();
            if ($all->isNotEmpty()) {
                return static::buildFromModels($all);
            }
        }

        return static::buildFromDefaults();
    }

    protected static function buildFromModels(Collection $all): \Illuminate\Support\Collection
    {
        return $all->whereNull('parent_id')->values()->map(function ($item) use ($all) {
            $item->setAttribute('children', $all->where('parent_id', $item->id)->values());
            return $item;
        });
    }

    protected static function buildFromDefaults(): \Illuminate\Support\Collection
    {
        $defs = static::defaultItems();
        $top = $defs->whereNull('parent_id');

        return $top->map(function ($d) use ($defs) {
            $kids = $defs->where('parent_id', $d['id'] ?? null)->values()->map(fn ($c) => new static($c))->all();
            $d['children'] = $kids;
            return new static($d);
        })->values();
    }

    public static function defaultItems(): \Illuminate\Support\Collection
    {
        $defaults = [
            ['id' => 'dashboard', 'label' => 'Dashboard', 'route' => 'dashboard', 'match' => 'dashboard', 'icon' => 'layout-dashboard'],
            ['id' => 'crm', 'label' => 'CRM', 'route' => 'leads.index', 'match' => 'leads.*', 'icon' => 'users'],
            ['id' => 'clientes', 'label' => 'Clientes', 'route' => 'clientes.index', 'match' => 'clientes.*', 'icon' => 'briefcase'],
            ['id' => 'projetos', 'label' => 'Projetos', 'route' => 'projetos.index', 'match' => 'projetos.*', 'icon' => 'folder-kanban'],
            ['id' => 'templates', 'label' => 'Templates', 'route' => 'templates.index', 'match' => 'templates.*', 'icon' => 'layout-template'],
            ['id' => 'agenda', 'label' => 'Agenda', 'route' => 'agenda.index', 'match' => 'agenda.*', 'icon' => 'calendar'],
            ['id' => 'producao', 'label' => 'Produção', 'route' => 'producao.index', 'match' => 'producao.*', 'icon' => 'factory'],
            ['id' => 'wiki', 'label' => 'Wiki', 'route' => 'wiki.index', 'match' => 'wiki.*', 'icon' => 'book-open'],
            ['id' => 'equipamentos', 'label' => 'Equipamentos', 'route' => 'equipamentos.index', 'match' => 'equipamentos.*', 'icon' => 'monitor'],
            ['id' => 'horas', 'label' => 'Horas', 'route' => 'horas.index', 'match' => 'horas.*', 'icon' => 'clock'],
            ['id' => 'comercial', 'label' => 'Comercial', 'route' => 'comercial.index', 'match' => 'comercial.*', 'icon' => 'handshake'],
            ['id' => 'financeiro', 'label' => 'Financeiro', 'route' => 'financeiro.index', 'match' => 'financeiro.*', 'icon' => 'wallet'],
            ['id' => 'relatorios', 'label' => 'Relatórios', 'route' => 'relatorios.index', 'match' => 'relatorios.*', 'icon' => 'bar-chart-3'],
            ['id' => 'favoritos', 'label' => 'Favoritos', 'route' => 'favoritos.index', 'match' => 'favoritos.*', 'icon' => 'star'],
            ['id' => 'config', 'label' => 'Configurações', 'icon' => 'settings'],
            ['id' => 'config_workflows', 'label' => 'Workflows', 'route' => 'config.workflows.index', 'match' => 'config.workflows.*', 'icon' => 'git-branch', 'parent_id' => 'config'],
            ['id' => 'config_automations', 'label' => 'Automações', 'route' => 'config.automations.index', 'match' => 'config.automations.*', 'icon' => 'zap', 'parent_id' => 'config'],
            ['id' => 'config_webhooks', 'label' => 'Webhooks', 'route' => 'config.webhooks.index', 'match' => 'config.webhooks.*', 'icon' => 'webhook', 'parent_id' => 'config'],
            ['id' => 'config_roles', 'label' => 'Funções', 'route' => 'config.roles.index', 'match' => 'config.roles.*', 'icon' => 'shield', 'parent_id' => 'config'],
            ['id' => 'config_fields', 'label' => 'Campos', 'route' => 'config.custom-fields.index', 'match' => 'config.custom-fields.*', 'icon' => 'form-input', 'parent_id' => 'config'],
            ['id' => 'config_menu', 'label' => 'Menu', 'route' => 'config.menu.index', 'match' => 'config.menu.*', 'icon' => 'menu', 'parent_id' => 'config'],
            ['id' => 'config_settings', 'label' => 'Geral', 'route' => 'config.index', 'match' => 'config.index,config.settings.*', 'icon' => 'sliders', 'parent_id' => 'config'],
        ];

        return collect($defaults)->map(fn ($d) => $d);
    }
}
