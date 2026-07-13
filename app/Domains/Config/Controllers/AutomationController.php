<?php

namespace App\Domains\Config\Controllers;

use App\Core\Models\Automation;
use App\Core\Support\EntityCatalog;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AutomationController extends Controller
{
    public function index(): View
    {
        $automations = Automation::query()->latest()->paginate(15);

        return view('config.automations.index', compact('automations'));
    }

    public function create(): View
    {
        $events = EntityCatalog::events();
        $actionTypes = EntityCatalog::actionTypes();
        $fieldCatalog = $this->fieldCatalog();

        return view('config.automations.edit', compact('events', 'actionTypes', 'fieldCatalog'))
            ->with('automation', null);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'event' => ['required', 'string', 'in:' . implode(',', array_keys(EntityCatalog::events()))],
            'active' => ['boolean'],
            'conditions' => ['array'],
            'conditions.*.field' => ['nullable', 'string'],
            'conditions.*.operator' => ['nullable', 'string'],
            'conditions.*.value' => ['nullable', 'string'],
            'actions' => ['array'],
            'actions.*.type' => ['required_with:actions', 'string'],
        ]);

        Automation::create([
            'company_id' => app(\App\Core\Support\CompanyContext::class)->id(),
            'name' => $data['name'],
            'event' => $data['event'],
            'conditions' => $this->buildConditions($data['conditions'] ?? []),
            'actions' => $this->buildActions($data['actions'] ?? []),
            'active' => (bool) ($data['active'] ?? true),
        ]);

        return redirect()->route('config.automations.index')->with('status', 'Automação criada.');
    }

    public function edit(Automation $automation): View
    {
        $events = EntityCatalog::events();
        $actionTypes = EntityCatalog::actionTypes();
        $fieldCatalog = $this->fieldCatalog();

        return view('config.automations.edit', compact('events', 'actionTypes', 'fieldCatalog', 'automation'))
            ->with('automation', $automation);
    }

    public function update(Request $request, Automation $automation): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'event' => ['required', 'string', 'in:' . implode(',', array_keys(EntityCatalog::events()))],
            'active' => ['boolean'],
            'conditions' => ['array'],
            'conditions.*.field' => ['nullable', 'string'],
            'conditions.*.operator' => ['nullable', 'string'],
            'conditions.*.value' => ['nullable', 'string'],
            'actions' => ['array'],
            'actions.*.type' => ['required_with:actions', 'string'],
        ]);

        $automation->update([
            'name' => $data['name'],
            'event' => $data['event'],
            'conditions' => $this->buildConditions($data['conditions'] ?? []),
            'actions' => $this->buildActions($data['actions'] ?? []),
            'active' => (bool) ($data['active'] ?? true),
        ]);

        return redirect()->route('config.automations.index')->with('status', 'Automação atualizada.');
    }

    public function destroy(Automation $automation): RedirectResponse
    {
        $automation->delete();

        return redirect()->route('config.automations.index')->with('status', 'Automação removida.');
    }

    protected function buildConditions(array $conditions): array
    {
        return collect($conditions)
            ->filter(fn ($c) => ! empty($c['field']))
            ->map(fn ($c) => [
                'field' => $c['field'],
                'operator' => $c['operator'] ?? '=',
                'value' => $c['value'] ?? null,
            ])
            ->values()
            ->all();
    }

    protected function buildActions(array $actions): array
    {
        return collect($actions)
            ->filter(fn ($a) => ! empty($a['type']))
            ->map(function ($a) {
                return match ($a['type']) {
                    'notify' => [
                        'type' => 'notify',
                        'title' => $a['title'] ?? 'Automação',
                        'body' => $a['body'] ?? '',
                        'link' => $a['link'] ?? null,
                        'user_id' => $a['user_id'] ?? null,
                    ],
                    'timeline' => [
                        'type' => 'timeline',
                        'title' => $a['title'] ?? 'Automação executada',
                    ],
                    'webhook' => [
                        'type' => 'webhook',
                        'url' => $a['url'] ?? '',
                    ],
                    default => ['type' => $a['type']],
                };
            })
            ->values()
            ->all();
    }

    protected function fieldCatalog(): array
    {
        $catalog = [];
        foreach (EntityCatalog::events() as $event => $label) {
            $type = EntityCatalog::eventClassFor($event);
            if ($type) {
                $catalog[$event] = EntityCatalog::fieldsFor($type);
            }
        }

        return $catalog;
    }
}
