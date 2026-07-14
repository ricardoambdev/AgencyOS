<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Definition;
use App\Admin\Registry;
use App\Core\Engines\ImportExportEngine;
use App\Core\Support\CompanyContext;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    protected function companyId(): ?int
    {
        return app(CompanyContext::class)->id();
    }

    protected function scoped(Definition $def)
    {
        $model = $def->newModel();
        $query = $model->newQuery();
        if (Schema::hasColumn($model->getTable(), 'company_id')) {
            $query->where('company_id', $this->companyId());
        }
        return $query;
    }

    protected function relationOptions(Definition $def, string $field, ?int $companyId): array
    {
        $map = $def->relationFor($field);
        if ($map === null) {
            // self relation (ex.: parent_id)
            $related = $def->model;
            $title = 'name';
        } else {
            [$related, $title] = $map;
        }
        $q = $related::query();
        if (Schema::hasColumn((new $related)->getTable(), 'company_id') && $companyId) {
            $q->where('company_id', $companyId);
        }
        $titleCol = Schema::hasColumn((new $related)->getTable(), $title) ? $title : 'id';

        return $q->orderBy($titleCol)->pluck($titleCol, 'id')->toArray();
    }

    public function dashboard()
    {
        $companyId = $this->companyId();
        $stats = [
            'leads' => \App\Domains\Crm\Models\Lead::where('company_id', $companyId)->count(),
            'clientes' => \App\Domains\Cliente\Models\Cliente::where('company_id', $companyId)->count(),
            'projetos' => \App\Domains\Projeto\Models\Projeto::where('company_id', $companyId)->count(),
            'usuarios' => \App\Domains\Usuario\Models\User::count(),
        ];

        $recent = \App\Core\Models\Timeline::where('company_id', $companyId)
            ->orderByDesc('created_at')->limit(10)->get();

        $resources = Registry::all();

        return view('admin.dashboard', compact('stats', 'recent', 'resources'));
    }

    public function index(Request $request, string $resource)
    {
        $def = Registry::get($resource) ?? abort(404);
        $columns = $def->columns();
        $relationDisplays = [];
        foreach ($columns as $col => $cfg) {
            if ($cfg['type'] === 'relation') {
                $relationDisplays[$col] = $this->relationOptions($def, $col, $this->companyId());
            }
        }
        $query = $this->scoped($def);

        if ($q = $request->get('q')) {
            $query->where(function ($b) use ($def, $q) {
                foreach ($def->searchable as $col) {
                    $b->orWhere($col, 'like', "%{$q}%");
                }
            });
        }

        if ($status = $request->get('status')) {
            if (Schema::hasColumn($def->newModel()->getTable(), 'status')) {
                $query->where('status', $status);
            }
        }

        $sort = $request->get('sort');
        $dir = $request->get('dir') === 'desc' ? 'desc' : 'asc';
        if ($sort && isset($columns[$sort])) {
            $query->orderBy($sort, $dir);
        } else {
            $query->latest('id');
        }

        $rows = $query->paginate(15)->withQueryString();

        return view('admin.resource.index', compact('def', 'columns', 'rows', 'relationDisplays', 'resource'));
    }

    public function show(string $resource, $id)
    {
        $def = Registry::get($resource) ?? abort(404);
        $model = $this->scoped($def)->findOrFail($id);

        return view('admin.resource.show', compact('def', 'model', 'resource'));
    }

    public function create(string $resource)
    {
        $def = Registry::get($resource) ?? abort(404);
        $fields = $def->fields();
        $options = $this->buildOptions($def, $fields);

        return view('admin.resource.form', compact('def', 'fields', 'options', 'resource'))->with('model', null);
    }

    public function store(Request $request, string $resource)
    {
        $def = Registry::get($resource) ?? abort(404);
        $data = $this->validated($request, $def, null);
        $model = $def->newModel();
        if (Schema::hasColumn($model->getTable(), 'company_id')) {
            $data['company_id'] = $this->companyId();
        }
        $model->fill($data)->save();

        return Redirect::route('admin.resource.index', $resource)
            ->with('status', $def->label . ' criado(a) com sucesso.');
    }

    public function edit(string $resource, $id)
    {
        $def = Registry::get($resource) ?? abort(404);
        $model = $this->scoped($def)->findOrFail($id);
        $fields = $def->fields();
        $options = $this->buildOptions($def, $fields);

        return view('admin.resource.form', compact('def', 'fields', 'options', 'model', 'resource'));
    }

    public function update(Request $request, string $resource, $id)
    {
        $def = Registry::get($resource) ?? abort(404);
        $model = $this->scoped($def)->findOrFail($id);
        $data = $this->validated($request, $def, $id);
        $model->fill($data)->save();

        return Redirect::route('admin.resource.index', $resource)
            ->with('status', $def->label . ' atualizado(a) com sucesso.');
    }

    public function destroy(string $resource, $id)
    {
        $def = Registry::get($resource) ?? abort(404);
        $model = $this->scoped($def)->findOrFail($id);
        if (method_exists($model, 'forceDelete')) {
            $model->forceDelete();
        } else {
            $model->delete();
        }

        return Redirect::route('admin.resource.index', $resource)
            ->with('status', $def->label . ' excluído(a) com sucesso.');
    }

    public function export(string $resource)
    {
        $def = Registry::get($resource) ?? abort(404);
        $columns = array_keys($def->columns());
        $rows = $this->scoped($def)->get($columns);
        $csv = ImportExportEngine::exportCsv($rows, $columns);

        return response()->streamDownload(fn () => print($csv), $resource . '.csv', [
            'Content-Type' => 'text/csv; charset=utf-8',
        ]);
    }

    public function import(string $resource)
    {
        $def = Registry::get($resource) ?? abort(404);
        return view('admin.resource.import', compact('def', 'resource'));
    }

    public function importStore(Request $request, string $resource)
    {
        $def = Registry::get($resource) ?? abort(404);
        $request->validate(['file' => 'required|file']);
        $path = $request->file('file')->getRealPath();
        $data = ImportExportEngine::importCsv($path);

        $model = $def->model;
        $created = 0;
        foreach ($data as $row) {
            $row = array_filter($row, fn ($v) => $v !== '' && $v !== null);
            if (Schema::hasColumn((new $model)->getTable(), 'company_id')) {
                $row['company_id'] = $this->companyId();
            }
            if (! empty($row)) {
                $model::create($row);
                $created++;
            }
        }

        return Redirect::route('admin.resource.index', $resource)
            ->with('status', "$created registro(s) importado(s).");
    }

    protected function buildOptions(Definition $def, array $fields): array
    {
        $companyId = $this->companyId();
        $options = [];
        foreach ($fields as $name => $cfg) {
            if ($cfg['type'] === 'relation') {
                $options[$name] = $this->relationOptions($def, $name, $companyId);
            } elseif ($cfg['type'] === 'select') {
                $options[$name] = $cfg['options'] ?? [];
            }
        }
        return $options;
    }

    protected function validated(Request $request, Definition $def, $id): array
    {
        $fields = $def->fields();
        $rules = [];
        foreach ($fields as $name => $cfg) {
            if ($cfg['type'] === 'relation') {
                $rules[$name] = 'nullable|integer';
            } elseif ($cfg['type'] === 'number') {
                $rules[$name] = 'nullable|numeric';
            } elseif ($cfg['type'] === 'email') {
                $rules[$name] = 'nullable|email';
            } elseif ($cfg['type'] === 'boolean') {
                $rules[$name] = 'nullable|boolean';
            } else {
                $rules[$name] = 'nullable|string';
            }
        }

        $data = $request->validate($rules);
        $model = $def->newModel();
        $casts = method_exists($model, 'getCasts') ? $model->getCasts() : [];

        foreach ($fields as $name => $cfg) {
            if ($cfg['type'] === 'password') {
                if (empty($data[$name])) {
                    unset($data[$name]);
                } else {
                    $data[$name] = Hash::make($data[$name]);
                }
                continue;
            }
            if (isset($casts[$name]) && Str::contains($casts[$name], ['array', 'json', 'collection'])) {
                if (isset($data[$name]) && is_string($data[$name]) && trim($data[$name]) !== '') {
                    $decoded = json_decode($data[$name], true);
                    $data[$name] = json_last_error() === JSON_ERROR_NONE ? $decoded : $data[$name];
                }
            }
        }

        return $data;
    }
}
