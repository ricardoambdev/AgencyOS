<?php

namespace App\Domains\Crm\Controllers;

use App\Domains\Crm\Actions\CreateLeadAction;
use App\Domains\Crm\Actions\DeleteLeadAction;
use App\Domains\Crm\Actions\UpdateLeadAction;
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
        $statuses = ['new', 'contacted', 'qualified', 'proposal', 'won', 'lost'];

        return view('crm.leads.index', compact('leads', 'statuses'));
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
            'status' => ['nullable', 'string'],
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
            'status' => ['nullable', 'string'],
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
