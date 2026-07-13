<?php

namespace App\Domains\Config\Controllers;

use App\Core\Models\Workflow;
use App\Core\Support\EntityCatalog;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkflowController extends Controller
{
    public function index(): View
    {
        $workflows = Workflow::query()->latest()->paginate(15);

        return view('config.workflows.index', compact('workflows'));
    }

    public function create(): View
    {
        $entityTypes = EntityCatalog::entityTypes();
        $allStatuses = $this->statusCatalog();

        return view('config.workflows.edit', compact('entityTypes', 'allStatuses'))
            ->with('workflow', null)
            ->with('entityType', old('entity_type', ''))
            ->with('definition', ['states' => [], 'transitions' => []]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'entity_type' => ['required', 'string'],
            'active' => ['boolean'],
            'states' => ['array'],
            'states.*.key' => ['required_with:states', 'string'],
            'states.*.label' => ['nullable', 'string'],
            'transitions' => ['array'],
            'transitions.*.from' => ['required_with:transitions', 'string'],
            'transitions.*.to' => ['required_with:transitions', 'string'],
        ]);

        $definition = $this->buildDefinition($data);

        Workflow::create([
            'company_id' => app(\App\Core\Support\CompanyContext::class)->id(),
            'name' => $data['name'],
            'entity_type' => $data['entity_type'],
            'definition' => $definition,
            'active' => (bool) ($data['active'] ?? true),
        ]);

        return redirect()->route('config.workflows.index')->with('status', 'Workflow criado.');
    }

    public function edit(Workflow $workflow): View
    {
        $entityTypes = EntityCatalog::entityTypes();
        $allStatuses = $this->statusCatalog();
        $definition = $workflow->definition ?? ['states' => [], 'transitions' => []];

        return view('config.workflows.edit', compact('entityTypes', 'allStatuses', 'workflow', 'definition'))
            ->with('entityType', old('entity_type', $workflow->entity_type));
    }

    public function update(Request $request, Workflow $workflow): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'entity_type' => ['required', 'string'],
            'active' => ['boolean'],
            'states' => ['array'],
            'states.*.key' => ['required_with:states', 'string'],
            'states.*.label' => ['nullable', 'string'],
            'transitions' => ['array'],
            'transitions.*.from' => ['required_with:transitions', 'string'],
            'transitions.*.to' => ['required_with:transitions', 'string'],
        ]);

        $workflow->update([
            'name' => $data['name'],
            'entity_type' => $data['entity_type'],
            'definition' => $this->buildDefinition($data),
            'active' => (bool) ($data['active'] ?? true),
        ]);

        return redirect()->route('config.workflows.index')->with('status', 'Workflow atualizado.');
    }

    public function destroy(Workflow $workflow): RedirectResponse
    {
        $workflow->delete();

        return redirect()->route('config.workflows.index')->with('status', 'Workflow removido.');
    }

    protected function buildDefinition(array $data): array
    {
        $states = [];
        foreach ($data['states'] ?? [] as $s) {
            if (! empty($s['key'])) {
                $states[$s['key']] = $s['label'] ?: $s['key'];
            }
        }

        $transitions = [];
        foreach ($data['transitions'] ?? [] as $t) {
            if (! empty($t['from']) && ! empty($t['to'])) {
                $transitions[] = ['from' => $t['from'], 'to' => $t['to']];
            }
        }

        return ['states' => $states, 'transitions' => $transitions];
    }

    protected function statusCatalog(): array
    {
        $catalog = [];
        foreach (array_keys(EntityCatalog::entityTypes()) as $type) {
            $catalog[$type] = EntityCatalog::statusesFor($type);
        }

        return $catalog;
    }
}
