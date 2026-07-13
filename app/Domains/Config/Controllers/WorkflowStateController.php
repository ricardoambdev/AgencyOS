<?php

namespace App\Domains\Config\Controllers;

use App\Core\Models\WorkflowState;
use App\Core\Support\CompanyContext;
use App\Core\Support\EntityCatalog;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkflowStateController extends Controller
{
    public function index(Request $request): View
    {
        $entityTypes = EntityCatalog::entityTypes();
        $selected = $request->get('entity_type', array_key_first($entityTypes));

        $states = WorkflowState::query()
            ->where('company_id', app(CompanyContext::class)->id())
            ->where('entity_type', $selected)
            ->orderBy('order')
            ->get();

        return view('config.workflow-states.index', compact('entityTypes', 'selected', 'states'));
    }

    public function create(Request $request): View
    {
        $entityTypes = EntityCatalog::entityTypes();
        $selected = $request->get('entity_type', array_key_first($entityTypes));

        return view('config.workflow-states.edit', [
            'entityTypes' => $entityTypes,
            'selected' => $selected,
            'state' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'entity_type' => ['required', 'string'],
            'slug' => ['required', 'string', 'regex:/^[a-z0-9_]+$/'],
            'name' => ['required', 'string', 'max:120'],
            'color' => ['nullable', 'string', 'max:30'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_initial' => ['boolean'],
            'is_final' => ['boolean'],
        ]);

        $companyId = app(CompanyContext::class)->id();

        WorkflowState::updateOrCreate(
            ['company_id' => $companyId, 'entity_type' => $data['entity_type'], 'slug' => $data['slug']],
            [
                'company_id' => $companyId,
                'entity_type' => $data['entity_type'],
                'slug' => $data['slug'],
                'name' => $data['name'],
                'color' => $data['color'] ?: null,
                'order' => (int) ($data['order'] ?? 0),
                'is_initial' => (bool) ($data['is_initial'] ?? false),
                'is_final' => (bool) ($data['is_final'] ?? false),
            ]
        );

        return redirect()->route('config.workflow-states.index', ['entity_type' => $data['entity_type']])
            ->with('status', 'Estado salvo.');
    }

    public function edit(WorkflowState $workflowState): View
    {
        $entityTypes = EntityCatalog::entityTypes();

        return view('config.workflow-states.edit', [
            'entityTypes' => $entityTypes,
            'selected' => $workflowState->entity_type,
            'state' => $workflowState,
        ]);
    }

    public function update(Request $request, WorkflowState $workflowState): RedirectResponse
    {
        $data = $request->validate([
            'entity_type' => ['required', 'string'],
            'slug' => ['required', 'string', 'regex:/^[a-z0-9_]+$/'],
            'name' => ['required', 'string', 'max:120'],
            'color' => ['nullable', 'string', 'max:30'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_initial' => ['boolean'],
            'is_final' => ['boolean'],
        ]);

        $workflowState->update([
            'entity_type' => $data['entity_type'],
            'slug' => $data['slug'],
            'name' => $data['name'],
            'color' => $data['color'] ?: null,
            'order' => (int) ($data['order'] ?? 0),
            'is_initial' => (bool) ($data['is_initial'] ?? false),
            'is_final' => (bool) ($data['is_final'] ?? false),
        ]);

        return redirect()->route('config.workflow-states.index', ['entity_type' => $data['entity_type']])
            ->with('status', 'Estado atualizado.');
    }

    public function destroy(WorkflowState $workflowState): RedirectResponse
    {
        $entityType = $workflowState->entity_type;
        $workflowState->delete();

        return redirect()->route('config.workflow-states.index', ['entity_type' => $entityType])
            ->with('status', 'Estado removido.');
    }
}
