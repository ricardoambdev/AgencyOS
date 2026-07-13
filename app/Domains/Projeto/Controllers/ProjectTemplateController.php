<?php

namespace App\Domains\Projeto\Controllers;

use App\Domains\Cliente\Models\Cliente;
use App\Domains\Config\Models\CustomField;
use App\Domains\Projeto\Actions\CreateProjectTemplate;
use App\Domains\Projeto\Actions\DeleteProjectTemplate;
use App\Domains\Projeto\Actions\UpdateProjectTemplate;
use App\Domains\Projeto\Actions\CreateProjetoAction;
use App\Domains\Projeto\Actions\CreateTaskAction;
use App\Domains\Projeto\Models\ProjectTemplate;
use App\Domains\Usuario\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectTemplateController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', ProjectTemplate::class);

        $templates = ProjectTemplate::withCount('templateTasks')->latest()->paginate(15);

        return view('project_templates.index', compact('templates'));
    }

    public function create()
    {
        $this->authorize('create', ProjectTemplate::class);

        return view('project_templates.create', ['template' => null]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', ProjectTemplate::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'checklist' => 'nullable|array',
            'checklist.*' => 'nullable|string|max:255',
            'tasks' => 'nullable|array',
            'tasks.*.title' => 'nullable|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.priority' => 'nullable|string',
            'tasks.*.estimated_hours' => 'nullable|numeric|min:0',
        ]);

        $data['checklist'] = collect($request->input('checklist', []))->filter(fn ($c) => ! empty(trim((string) $c)))->values()->all();

        $template = app(CreateProjectTemplate::class)->handle($data);

        return redirect()->route('templates.show', $template)
            ->with('status', 'Template criado.');
    }

    public function show(ProjectTemplate $template)
    {
        $this->authorize('view', $template);
        $template->load(['templateTasks', 'timeline.user']);

        return view('project_templates.show', compact('template'));
    }

    public function edit(ProjectTemplate $template)
    {
        $this->authorize('update', $template);

        return view('project_templates.edit', compact('template'));
    }

    public function update(Request $request, ProjectTemplate $template)
    {
        $this->authorize('update', $template);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'checklist' => 'nullable|array',
            'checklist.*' => 'nullable|string|max:255',
            'tasks' => 'nullable|array',
            'tasks.*.title' => 'nullable|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.priority' => 'nullable|string',
            'tasks.*.estimated_hours' => 'nullable|numeric|min:0',
        ]);

        $data['checklist'] = collect($request->input('checklist', []))->filter(fn ($c) => ! empty(trim((string) $c)))->values()->all();

        app(UpdateProjectTemplate::class)->handle($template, $data);

        return redirect()->route('templates.show', $template)
            ->with('status', 'Template atualizado.');
    }

    public function destroy(ProjectTemplate $template)
    {
        $this->authorize('delete', $template);
        app(DeleteProjectTemplate::class)->handle($template);

        return redirect()->route('templates.index')
            ->with('status', 'Template excluído.');
    }

    public function apply(ProjectTemplate $template)
    {
        $this->authorize('view', $template);

        $clientes = Cliente::orderBy('name')->get();
        $owners = User::orderBy('name')->get();
        $customFields = CustomField::where('entity_type', \App\Domains\Projeto\Models\Projeto::class)
            ->where('company_id', app(\App\Core\Support\CompanyContext::class)->id())
            ->orderBy('order')->get();

        return view('project_templates.apply', compact('template', 'clientes', 'owners', 'customFields'));
    }

    public function storeApply(Request $request, ProjectTemplate $template)
    {
        $this->authorize('view', $template);

        $data = $request->validate([
            'client_id' => 'required|exists:clientes,id',
            'name' => 'required|string|max:255',
            'status' => 'nullable|string',
            'owner_id' => 'nullable|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'budget' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $projeto = app(CreateProjetoAction::class)->handle($data);

        foreach ($template->templateTasks as $task) {
            app(CreateTaskAction::class)->handle([
                'project_id' => $projeto->id,
                'title' => $task->title,
                'description' => $task->description,
                'priority' => $task->priority,
                'estimated_hours' => $task->estimated_hours,
                'status' => 'pending',
            ]);
        }

        foreach (array_values($template->checklist ?? []) as $index => $label) {
            \App\Core\Models\ChecklistItem::create([
                'company_id' => app(\App\Core\Support\CompanyContext::class)->id(),
                'project_id' => $projeto->id,
                'label' => $label,
                'done' => false,
                'order' => $index,
            ]);
        }

        $projeto->syncCustomFieldsFromRequest($request);

        return redirect()->route('projetos.show', $projeto)
            ->with('status', 'Projeto criado a partir do template.');
    }
}
