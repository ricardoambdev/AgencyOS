<?php

namespace App\Domains\Comercial\Controllers;

use App\Core\Concerns\HasActivity;
use App\Domains\Cliente\Models\Cliente;
use App\Domains\Comercial\Actions\CreateContrato;
use App\Domains\Comercial\Actions\DeleteContrato;
use App\Domains\Comercial\Actions\UpdateContrato;
use App\Domains\Comercial\Models\Contrato;
use App\Domains\Usuario\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContratoController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Contrato::class);

        $filters = $request->only(['status', 'type', 'client_id', 'q']);
        $contratos = Contrato::with(['cliente', 'responsavel'])
            ->filter($filters)
            ->orderByDesc('created_at')
            ->paginate(15);

        $clientes = Cliente::orderBy('name')->get();

        $ativos = Contrato::where('status', 'ativo')->sum('value');
        $pipeline = Contrato::where('status', 'rascunho')->sum('value');

        return view('comercial.index', compact('contratos', 'clientes', 'ativos', 'pipeline', 'filters'));
    }

    public function create()
    {
        $this->authorize('create', Contrato::class);

        $clientes = Cliente::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('comercial.create', compact('clientes', 'users'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Contrato::class);

        $data = $request->validate([
            'client_id' => 'nullable|exists:clientes,id',
            'responsavel_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'type' => 'nullable|in:fixed,hourly,retainer',
            'value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|in:rascunho,ativo,encerrado,cancelado',
            'renewal_type' => 'nullable|in:none,mensal,anual',
            'renewal_date' => 'nullable|date',
            'signed_at' => 'nullable|date',
            'description' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|max:20480',
        ]);

        $contrato = app(CreateContrato::class)->handle($data);
        $contrato->syncTags(explode(',', $request->input('tags', '')));
        $this->storeFiles($contrato, $request);

        return redirect()->route('comercial.show', $contrato)
            ->with('success', 'Contrato criado.');
    }

    public function show(Contrato $contrato)
    {
        $this->authorize('view', $contrato);
        $contrato->load(['cliente', 'responsavel', 'comments.user', 'attachments', 'timeline.user']);

        return view('comercial.show', compact('contrato'));
    }

    public function edit(Contrato $contrato)
    {
        $this->authorize('update', $contrato);

        $clientes = Cliente::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('comercial.edit', compact('contrato', 'clientes', 'users'));
    }

    public function update(Request $request, Contrato $contrato)
    {
        $this->authorize('update', $contrato);

        $data = $request->validate([
            'client_id' => 'nullable|exists:clientes,id',
            'responsavel_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'type' => 'nullable|in:fixed,hourly,retainer',
            'value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|in:rascunho,ativo,encerrado,cancelado',
            'renewal_type' => 'nullable|in:none,mensal,anual',
            'renewal_date' => 'nullable|date',
            'signed_at' => 'nullable|date',
            'description' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|max:20480',
        ]);

        app(UpdateContrato::class)->handle($contrato, $data);
        $contrato->syncTags(explode(',', $request->input('tags', '')));
        $this->storeFiles($contrato, $request);

        return redirect()->route('comercial.show', $contrato)
            ->with('success', 'Contrato atualizado.');
    }

    public function destroy(Contrato $contrato)
    {
        $this->authorize('delete', $contrato);
        app(DeleteContrato::class)->handle($contrato);

        return redirect()->route('comercial.index')
            ->with('success', 'Contrato excluído.');
    }

    public function downloadAttachment(Contrato $contrato, \App\Core\Models\Attachment $attachment)
    {
        $this->authorize('view', $contrato);

        abort_if($attachment->entity_id !== $contrato->id, 404);

        return Storage::disk('local')->download($attachment->path, $attachment->name);
    }

    protected function storeFiles(Contrato $contrato, Request $request): void
    {
        if (! $request->hasFile('files')) {
            return;
        }

        foreach ($request->file('files') as $file) {
            if (! $file) {
                continue;
            }

            $path = $file->store('contratos/'.$contrato->id, 'local');
            $contrato->attachments()->create([
                'company_id' => $contrato->company_id,
                'user_id' => auth()->id(),
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }
    }
}
