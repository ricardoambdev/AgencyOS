<?php

namespace App\Domains\Horas\Controllers;

use App\Domains\Horas\Actions\CreateLancamentoHoraAction;
use App\Domains\Horas\Actions\DeleteLancamentoHoraAction;
use App\Domains\Horas\Actions\UpdateLancamentoHoraAction;
use App\Domains\Horas\Models\LancamentoHora;
use App\Domains\Projeto\Models\Projeto;
use App\Domains\Projeto\Models\Task;
use App\Domains\Usuario\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LancamentoHoraController extends Controller
{
    public function index(Request $request): View
    {
        $query = LancamentoHora::with('user', 'project', 'task');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->integer('project_id'));
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }
        if ($request->filled('from')) {
            $query->where('date', '>=', $request->string('from'));
        }
        if ($request->filled('to')) {
            $query->where('date', '<=', $request->string('to'));
        }
        if ($request->filled('q')) {
            $query->where('description', 'like', '%'.$request->string('q').'%');
        }

        $lancamentos = $query->latest('date')->paginate(20)->withQueryString();

        $totais = [
            'horas' => $lancamentos->sum('hours'),
            'faturar' => LancamentoHora::where('billable', true)->sum('hours'),
        ];

        $projetos = Projeto::orderBy('name')->get();
        $users = User::all();

        return view('horas.index', compact('lancamentos', 'totais', 'projetos', 'users'));
    }

    public function create(): View
    {
        $projetos = Projeto::orderBy('name')->get();
        $users = User::all();

        return view('horas.create', compact('projetos', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'project_id' => ['nullable', 'exists:projetos,id'],
            'task_id' => ['nullable', 'exists:tasks,id'],
            'date' => ['required', 'date'],
            'hours' => ['required', 'numeric', 'min:0.01', 'max:24'],
            'description' => ['nullable', 'string'],
            'billable' => ['nullable', 'boolean'],
        ]);

        $data['user_id'] ??= auth()->id();
        $this->authorize('create', LancamentoHora::class);
        $lancamento = app(CreateLancamentoHoraAction::class)->handle($data);

        return redirect()->route('horas.index')->with('status', 'Lançamento registrado.');
    }

    public function show(LancamentoHora $lancamento): View
    {
        $this->authorize('view', $lancamento);
        $lancamento->load(['user', 'project', 'task', 'comments.user', 'timeline.user']);

        return view('horas.show', compact('lancamento'));
    }

    public function edit(LancamentoHora $lancamento): View
    {
        $this->authorize('update', $lancamento);
        $projetos = Projeto::orderBy('name')->get();
        $users = User::all();

        return view('horas.edit', compact('lancamento', 'projetos', 'users'));
    }

    public function update(Request $request, LancamentoHora $lancamento): RedirectResponse
    {
        $this->authorize('update', $lancamento);
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'project_id' => ['nullable', 'exists:projetos,id'],
            'task_id' => ['nullable', 'exists:tasks,id'],
            'date' => ['required', 'date'],
            'hours' => ['required', 'numeric', 'min:0.01', 'max:24'],
            'description' => ['nullable', 'string'],
            'billable' => ['nullable', 'boolean'],
        ]);

        app(UpdateLancamentoHoraAction::class)->handle($lancamento, $data);

        return redirect()->route('horas.index')->with('status', 'Lançamento atualizado.');
    }

    public function destroy(LancamentoHora $lancamento): RedirectResponse
    {
        $this->authorize('delete', $lancamento);
        app(DeleteLancamentoHoraAction::class)->handle($lancamento);

        return redirect()->route('horas.index')->with('status', 'Lançamento removido.');
    }

    public function tasksForProject(Request $request)
    {
        $tasks = Task::where('project_id', $request->integer('project_id'))
            ->orderBy('title')
            ->get(['id', 'title']);

        return response()->json($tasks);
    }
}
