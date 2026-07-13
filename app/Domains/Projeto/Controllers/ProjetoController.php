<?php

namespace App\Domains\Projeto\Controllers;

use App\Domains\Cliente\Models\Cliente;
use App\Domains\Projeto\Actions\CreateProjetoAction;
use App\Domains\Projeto\Actions\DeleteProjetoAction;
use App\Domains\Projeto\Actions\UpdateProjetoAction;
use App\Domains\Projeto\Models\Projeto;
use App\Domains\Projeto\Models\Task;
use App\Domains\Usuario\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjetoController extends Controller
{
    public function index(): View
    {
        $projetos = Projeto::with('client', 'owner')->latest()->paginate(15);

        return view('projetos.index', compact('projetos'));
    }

    public function create(): View
    {
        $clientes = Cliente::all();
        $owners = User::all();

        return view('projetos.create', compact('clientes', 'owners'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_id' => ['required', 'exists:clientes,id'],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'string'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'budget' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string'],
        ]);

        $this->authorize('create', Projeto::class);
        $projeto = app(CreateProjetoAction::class)->handle($data);
        $projeto->syncCustomFieldsFromRequest($request);
        $projeto->syncTags(explode(',', $request->input('tags', '')));

        return redirect()->route('projetos.index')->with('status', 'Projeto criado.');
    }

    public function show(Projeto $projeto): View
    {
        $this->authorize('view', $projeto);
        $projeto->load(['client', 'owner', 'timeline.user', 'comments.user']);
        $tasks = $projeto->tasks()->with('assignee')->orderBy('status')->get();
        $users = User::all();
        $checklist = \App\Core\Models\ChecklistItem::where('project_id', $projeto->getKey())
            ->orderBy('order')->get();

        return view('projetos.show', compact('projeto', 'tasks', 'users', 'checklist'));
    }

    public function edit(Projeto $projeto): View
    {
        $this->authorize('update', $projeto);
        $clientes = Cliente::all();
        $owners = User::all();

        return view('projetos.edit', compact('projeto', 'clientes', 'owners'));
    }

    public function update(Request $request, Projeto $projeto): RedirectResponse
    {
        $this->authorize('update', $projeto);
        $data = $request->validate([
            'client_id' => ['required', 'exists:clientes,id'],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'string'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'budget' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string'],
        ]);

        if (! empty($data['status']) && $data['status'] !== $projeto->status) {
            $allowed = app(\App\Core\Engines\WorkflowEngine::class)
                ->canTransition(\App\Domains\Projeto\Models\Projeto::class, $projeto->status, $data['status']);

            if (! $allowed) {
                return redirect()->back()->with('error', 'Transição de status não permitida pelo workflow.');
            }
        }

        app(UpdateProjetoAction::class)->handle($projeto, $data);
        $projeto->syncCustomFieldsFromRequest($request);
        $projeto->syncTags(explode(',', $request->input('tags', '')));

        return redirect()->route('projetos.index')->with('status', 'Projeto atualizado.');
    }

    public function destroy(Projeto $projeto): RedirectResponse
    {
        $this->authorize('delete', $projeto);
        app(DeleteProjetoAction::class)->handle($projeto);

        return redirect()->route('projetos.index')->with('status', 'Projeto removido.');
    }

    public function storeTask(Request $request, Projeto $projeto): RedirectResponse
    {
        $this->authorize('update', $projeto);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'string'],
            'priority' => ['nullable', 'string'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
        ]);

        app(\App\Domains\Projeto\Actions\CreateTaskAction::class)->handle($data + ['project_id' => $projeto->id]);

        return redirect()->route('projetos.show', $projeto)->with('status', 'Tarefa adicionada.');
    }

    public function toggleChecklist(\App\Core\Models\ChecklistItem $item): RedirectResponse
    {
        $this->authorize('update', $item->project);

        $item->update(['done' => ! $item->done]);

        return redirect()->route('projetos.show', $item->project);
    }
}
