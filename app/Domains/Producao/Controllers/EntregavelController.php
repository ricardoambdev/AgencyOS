<?php

namespace App\Domains\Producao\Controllers;

use App\Core\Engines\NotificationEngine;
use App\Core\Engines\TimelineEngine;
use App\Core\Models\Attachment;
use App\Domains\Producao\Actions\CreateEntregavelAction;
use App\Domains\Producao\Actions\DeleteEntregavelAction;
use App\Domains\Producao\Actions\UpdateEntregavelAction;
use App\Domains\Producao\Models\Entregavel;
use App\Domains\Projeto\Models\Projeto;
use App\Domains\Usuario\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EntregavelController extends Controller
{
    public static function tipos(): array
    {
        return [
            'banner' => 'Banner',
            'video' => 'Vídeo',
            'social' => 'Post social',
            'site' => 'Site / Landing',
            'impresso' => 'Material impresso',
            'apresentacao' => 'Apresentação',
            'outro' => 'Outro',
        ];
    }

    public static function status(): array
    {
        return [
            'briefing' => 'Briefing',
            'em_producao' => 'Em Produção',
            'revisao' => 'Em Revisão',
            'aprovado' => 'Aprovado',
            'entregue' => 'Entregue',
        ];
    }

    public function index(Request $request): View
    {
        $query = Entregavel::with('projeto', 'owner');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->integer('project_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('tipo')) {
            $query->where('type', $request->string('tipo'));
        }
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->string('q').'%')
                  ->orWhere('description', 'like', '%'.$request->string('q').'%');
            });
        }

        $entregaveis = $query->latest()->paginate(15)->withQueryString();
        $projetos = Projeto::orderBy('name')->get();

        return view('producao.index', compact('entregaveis', 'projetos'));
    }

    public function create(): View
    {
        $projetos = Projeto::orderBy('name')->get();
        $owners = User::all();

        return view('producao.create', compact('projetos', 'owners'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_id' => ['required', 'exists:projetos,id'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
            'client_visible' => ['nullable', 'boolean'],
            'files.*' => ['nullable', 'file', 'max:10240'],
        ]);

        $this->authorize('create', Entregavel::class);

        $entregavel = app(CreateEntregavelAction::class)->handle($data);
        $this->storeFiles($request, $entregavel);

        app(TimelineEngine::class)->record($entregavel, 'created', 'Entregável criado');

        return redirect()->route('producao.show', $entregavel)->with('status', 'Entregável criado.');
    }

    public function show(Entregavel $entregavel): View
    {
        $this->authorize('view', $entregavel);
        $entregavel->load(['projeto.client', 'owner', 'timeline.user', 'comments.user', 'attachments']);
        $statusOptions = self::status();

        return view('producao.show', compact('entregavel', 'statusOptions'));
    }

    public function edit(Entregavel $entregavel): View
    {
        $this->authorize('update', $entregavel);
        $projetos = Projeto::orderBy('name')->get();
        $owners = User::all();

        return view('producao.edit', compact('entregavel', 'projetos', 'owners'));
    }

    public function update(Request $request, Entregavel $entregavel): RedirectResponse
    {
        $this->authorize('update', $entregavel);
        $data = $request->validate([
            'project_id' => ['required', 'exists:projetos,id'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
            'client_visible' => ['nullable', 'boolean'],
            'files.*' => ['nullable', 'file', 'max:10240'],
        ]);

        $oldStatus = $entregavel->status;
        app(UpdateEntregavelAction::class)->handle($entregavel, $data);
        $this->storeFiles($request, $entregavel);

        if (($data['status'] ?? null) && $data['status'] !== $oldStatus) {
            app(TimelineEngine::class)->record($entregavel, 'status', 'Status alterado', $oldStatus.' → '.$entregavel->status);
        }

        return redirect()->route('producao.show', $entregavel)->with('status', 'Entregável atualizado.');
    }

    public function destroy(Entregavel $entregavel): RedirectResponse
    {
        $this->authorize('delete', $entregavel);
        app(DeleteEntregavelAction::class)->handle($entregavel);

        return redirect()->route('producao.index')->with('status', 'Entregável removido.');
    }

    public function aprovar(Request $request, Entregavel $entregavel): RedirectResponse
    {
        $this->authorize('update', $entregavel);
        $next = $request->boolean('deliver') ? 'entregue' : 'aprovado';

        $entregavel->update(['status' => $next]);
        app(TimelineEngine::class)->record($entregavel, 'approved', $next === 'entregue' ? 'Entregue ao cliente' : 'Aprovado');

        if ($entregavel->owner_id) {
            app(NotificationEngine::class)->notify(
                $entregavel->owner_id,
                $next === 'entregue' ? 'Entregável entregue' : 'Entregável aprovado',
                $entregavel->name,
                route('producao.show', $entregavel)
            );
        }

        return redirect()->route('producao.show', $entregavel)->with('status', 'Status atualizado.');
    }

    public function novaVersao(Entregavel $entregavel): RedirectResponse
    {
        $this->authorize('update', $entregavel);
        $entregavel->increment('version');
        app(TimelineEngine::class)->record($entregavel, 'version', 'Nova versão v'.$entregavel->version);

        return redirect()->route('producao.show', $entregavel)->with('status', 'Versão '.$entregavel->version.' criada.');
    }

    public function downloadAttachment(Attachment $attachment)
    {
        $path = Storage::disk('local')->path($attachment->path);

        abort_unless(file_exists($path), 404);

        return response()->download($path, $attachment->name);
    }

    protected function storeFiles(Request $request, Entregavel $entregavel): void
    {
        if (! $request->hasFile('files')) {
            return;
        }

        $files = $request->file('files', []);
        $files = is_array($files) ? $files : [$files];

        foreach ($files as $file) {
            $stored = $file->store('entregaveis/'.($entregavel->company_id ?? 0), 'local');

            Attachment::create([
                'company_id' => $entregavel->company_id,
                'entity_type' => Entregavel::class,
                'entity_id' => $entregavel->getKey(),
                'user_id' => auth()->id(),
                'name' => $file->getClientOriginalName(),
                'path' => $stored,
                'disk' => 'local',
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }
    }
}
