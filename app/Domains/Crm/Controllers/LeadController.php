<?php

namespace App\Domains\Crm\Controllers;

use App\Domains\Crm\Actions\CreateLeadAction;
use App\Domains\Crm\Actions\DeleteLeadAction;
use App\Domains\Crm\Actions\UpdateLeadAction;
use App\Core\Engines\ImportExportEngine;
use App\Domains\Crm\Models\Lead;
use App\Domains\Usuario\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lead::with('owner');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $leads = $query->latest()->paginate(15);
        $statuses = \App\Core\Models\WorkflowState::resolve(Lead::class);

        return view('crm.leads.index', compact('leads', 'statuses'));
    }

    public function export(Request $request)
    {
        $this->authorize('viewAny', Lead::class);

        $leads = Lead::query()->latest()->get();
        $csv = ImportExportEngine::exportCsv($leads, ['name', 'email', 'company_name', 'phone', 'value', 'status']);

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="leads.csv"');
    }

    public function import(): View
    {
        $this->authorize('create', Lead::class);

        return view('crm.leads.import');
    }

    public function storeImport(Request $request): RedirectResponse
    {
        $this->authorize('create', Lead::class);

        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $path = $request->file('file')->getRealPath();
        $rows = ImportExportEngine::parseCsv($path);

        $created = ImportExportEngine::importCsv(Lead::class, $rows, [
            'nome' => 'name',
            'name' => 'name',
            'email' => 'email',
            'empresa' => 'company_name',
            'company_name' => 'company_name',
            'telefone' => 'phone',
            'phone' => 'phone',
            'valor' => 'value',
            'value' => 'value',
            'status' => 'status',
        ]);

        return redirect()->route('leads.index')
            ->with('status', "{$created} lead(s) importado(s).");
    }

    public function create(): View
    {
        $owners = User::all();

        return view('crm.leads.create', compact('owners'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'company_name' => ['nullable', 'string'],
            'source' => ['nullable', 'string'],
            'status' => ['nullable', 'string', \Illuminate\Validation\Rule::in(array_keys(\App\Core\Models\WorkflowState::resolve(Lead::class)))],
            'value' => ['nullable', 'numeric'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->authorize('create', Lead::class);
        $lead = app(CreateLeadAction::class)->handle($data);
        $lead->syncTags(explode(',', $request->input('tags', '')));

        return redirect()->route('leads.index')->with('status', 'Lead criado com sucesso.');
    }

    public function show(Lead $lead): View
    {
        $this->authorize('view', $lead);
        $lead->load(['owner', 'timeline.user', 'comments.user', 'attachments']);

        return view('crm.leads.show', compact('lead'));
    }

    public function edit(Lead $lead): View
    {
        $this->authorize('update', $lead);
        $owners = User::all();

        return view('crm.leads.edit', compact('lead', 'owners'));
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $this->authorize('update', $lead);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'company_name' => ['nullable', 'string'],
            'source' => ['nullable', 'string'],
            'status' => ['nullable', 'string', \Illuminate\Validation\Rule::in(array_keys(\App\Core\Models\WorkflowState::resolve(Lead::class)))],
            'value' => ['nullable', 'numeric'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ]);

        app(UpdateLeadAction::class)->handle($lead, $data);
        $lead->syncTags(explode(',', $request->input('tags', '')));

        return redirect()->route('leads.index')->with('status', 'Lead atualizado.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $this->authorize('delete', $lead);
        app(DeleteLeadAction::class)->handle($lead);

        return redirect()->route('leads.index')->with('status', 'Lead removido.');
    }
}
