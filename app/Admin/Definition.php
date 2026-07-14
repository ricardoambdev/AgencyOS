<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Describe um recurso administrável de forma genérica.
 * Colunas e campos são inferidos do modelo, podendo ser sobrescritos.
 */
class Definition
{
    public string $slug;
    public string $model;
    public string $label;
    public string $icon = 'box';
    public array $searchable = [];
    public array $columnOverrides = [];
    public array $fieldOverrides = [];
    public array $hiddenColumns = ['id', 'company_id', 'deleted_at', 'password', 'remember_token'];
    public array $hiddenFields = ['id', 'company_id', 'created_at', 'updated_at', 'deleted_at', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'];
    public array $relations = [];

    public static array $relationMap = [
        'owner_id' => [\App\Domains\Usuario\Models\User::class, 'name'],
        'user_id' => [\App\Domains\Usuario\Models\User::class, 'name'],
        'client_id' => [\App\Domains\Cliente\Models\Cliente::class, 'name'],
        'lead_id' => [\App\Domains\Crm\Models\Lead::class, 'name'],
        'project_id' => [\App\Domains\Projeto\Models\Projeto::class, 'name'],
        'task_id' => [\App\Domains\Projeto\Models\Task::class, 'title'],
        'company_id' => [\App\Domains\Company\Models\Company::class, 'name'],
        'role_id' => [\App\Domains\Company\Models\Role::class, 'name'],
        'template_id' => [\App\Domains\Projeto\Models\ProjectTemplate::class, 'name'],
        'assignee_id' => [\App\Domains\Usuario\Models\User::class, 'name'],
        'responsavel_id' => [\App\Domains\Usuario\Models\User::class, 'name'],
        'parent_id' => null,
    ];

    public function __construct(array $config)
    {
        $this->slug = $config['slug'];
        $this->model = $config['model'];
        $this->label = $config['label'] ?? Str::title($config['slug']);
        $this->icon = $config['icon'] ?? 'box';
        $this->searchable = $config['searchable'] ?? [];
        $this->columnOverrides = $config['columns'] ?? [];
        $this->fieldOverrides = $config['fields'] ?? [];
        $this->hiddenColumns = array_merge($this->hiddenColumns, $config['hidden_columns'] ?? []);
        $this->hiddenFields = array_merge($this->hiddenFields, $config['hidden_fields'] ?? []);
        $this->relations = $config['relations'] ?? [];
    }

    public function newModel(): Model
    {
        return new $this->model;
    }

    public function columns(): array
    {
        $model = $this->newModel();
        $fillable = method_exists($model, 'getFillable') ? $model->getFillable() : [];
        $base = array_merge(['id'], $fillable, ['created_at']);
        $cols = [];
        foreach ($base as $name) {
            if (in_array($name, $this->hiddenColumns, true)) {
                continue;
            }
            $cols[$name] = $this->columnOverrides[$name] ?? $this->inferColumn($name, $model);
        }
        return $cols;
    }

    protected function inferColumn(string $name, Model $model): array
    {
        $type = 'text';
        if (Str::endsWith($name, '_at') || Str::endsWith($name, '_date') || $name === 'date') {
            $type = 'datetime';
        } elseif (in_array($name, ['status', 'type', 'kind', 'stage', 'priority', 'state'], true)) {
            $type = 'badge';
        } elseif (Str::endsWith($name, '_id')) {
            $type = 'relation';
        } elseif (is_bool($model->{$name} ?? null)) {
            $type = 'boolean';
        }
        return ['label' => $this->labelFor($name), 'type' => $type];
    }

    public function fields(): array
    {
        $model = $this->newModel();
        $fillable = method_exists($model, 'getFillable') ? $model->getFillable() : [];
        $fields = [];
        foreach ($fillable as $name) {
            if (in_array($name, $this->hiddenFields, true)) {
                continue;
            }
            $fields[$name] = $this->fieldOverrides[$name] ?? $this->inferField($name, $model);
        }
        return $fields;
    }

    protected function inferField(string $name, Model $model): array
    {
        if (array_key_exists($name, self::$relationMap) || Str::endsWith($name, '_id')) {
            return ['label' => $this->labelFor($name), 'type' => 'relation', 'required' => false];
        }
        if ($name === 'password') {
            return ['label' => 'Senha', 'type' => 'password', 'required' => false];
        }
        if (Str::contains($name, ['description', 'body', 'notes', 'obs', 'comment', 'definition', 'options', 'actions', 'conditions'])) {
            return ['label' => $this->labelFor($name), 'type' => 'textarea', 'required' => false];
        }
        if (in_array($name, ['email'])) {
            return ['label' => 'E-mail', 'type' => 'email', 'required' => false];
        }
        if (Str::endsWith($name, '_at') || Str::endsWith($name, '_date') || $name === 'date') {
            return ['label' => $this->labelFor($name), 'type' => 'datetime', 'required' => false];
        }
        if (in_array($name, ['status', 'type', 'kind', 'stage', 'priority', 'state'])) {
            return ['label' => $this->labelFor($name), 'type' => 'select', 'options' => [], 'required' => false];
        }
        if (in_array($name, ['value', 'total', 'price', 'amount', 'hours', 'qty', 'quantity', 'order', 'budget', 'sum', 'tax'])) {
            return ['label' => $this->labelFor($name), 'type' => 'number', 'required' => false];
        }
        if (Str::endsWith($name, '_url') || Str::endsWith($name, 'link')) {
            return ['label' => $this->labelFor($name), 'type' => 'url', 'required' => false];
        }
        return ['label' => $this->labelFor($name), 'type' => 'text', 'required' => false];
    }

    public function labelFor(string $name): string
    {
        return Str::title(str_replace('_', ' ', $name));
    }

    public function relationFor(string $name): ?array
    {
        if (isset(self::$relationMap[$name])) {
            return self::$relationMap[$name];
        }
        if (Str::endsWith($name, '_id')) {
            return null;
        }
        return null;
    }
}
