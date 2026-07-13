<?php

namespace App\Domains\Cliente\Controllers;

use App\Core\Engines\CommentEngine;
use App\Core\Engines\NotificationEngine;
use App\Core\Engines\TimelineEngine;
use App\Core\Models\Attachment;
use App\Domains\Cliente\Models\Cliente;
use App\Domains\Projeto\Models\Projeto;
use App\Domains\Projeto\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalController extends Controller
{
    protected function cliente(string $token): Cliente
    {
        $cliente = Cliente::withoutGlobalScope('company')
            ->where('portal_token', $token)
            ->firstOrFail();

        app(\App\Core\Support\CompanyContext::class)->set($cliente->company_id);

        return $cliente;
    }

    protected function resolveProject(Cliente $cliente, string $ulid): Projeto
    {
        return $cliente->projetos()->withoutGlobalScope('company')->where('ulid', $ulid)->firstOrFail();
    }

    public function show(string $token): View
    {
        $cliente = $this->cliente($token);
        $cliente->load(['projetos' => fn ($q) => $q->withCount([
            'tasks as tasks_count' => fn ($q) => $q->withoutGlobalScope('company'),
            'tasks as done_tasks_count' => fn ($q) => $q->withoutGlobalScope('company')->where('status', 'done'),
        ])]);

        return view('portal.dashboard', compact('cliente', 'token'));
    }

    public function project(string $token, string $ulid): View
    {
        $cliente = $this->cliente($token);
        $projeto = $this->resolveProject($cliente, $ulid);
        $projeto->load([
            'tasks' => fn ($q) => $q->withoutGlobalScope('company')->with('assignee'),
            'comments' => fn ($q) => $q->withoutGlobalScope('company')->whereIn('visibility', ['public', 'client'])->latest(),
            'attachments' => fn ($q) => $q->withoutGlobalScope('company'),
            'timeline' => fn ($q) => $q->withoutGlobalScope('company')->latest(),
        ]);

        return view('portal.project', compact('cliente', 'projeto', 'token'));
    }

    public function comment(Request $request, string $token): RedirectResponse
    {
        $cliente = $this->cliente($token);

        $data = $request->validate([
            'entity_type' => ['required', 'in:App\Domains\Projeto\Models\Projeto,App\Domains\Projeto\Models\Task'],
            'entity_id' => ['required', 'string'],
            'body' => ['required', 'string'],
        ]);

        $model = $data['entity_type']::withoutGlobalScope('company')->where('ulid', $data['entity_id'])->firstOrFail();

        abort_unless($this->ownsEntity($cliente, $model), 403);

        app(CommentEngine::class)->add($model, null, $data['body'], 'client');

        $redirect = $model instanceof Projeto
            ? route('portal.project', ['token' => $token, 'projeto' => $model->ulid])
            : route('portal.project', ['token' => $token, 'projeto' => $model->project->ulid]);

        return redirect($redirect)->with('status', 'Comentário enviado ao time.');
    }

    public function approve(Request $request, string $token): RedirectResponse
    {
        $cliente = $this->cliente($token);

        $data = $request->validate([
            'entity_type' => ['required', 'in:App\Domains\Projeto\Models\Projeto,App\Domains\Projeto\Models\Task'],
            'entity_id' => ['required', 'string'],
        ]);

        $model = $data['entity_type']::withoutGlobalScope('company')->where('ulid', $data['entity_id'])->firstOrFail();
        abort_unless($this->ownsEntity($cliente, $model), 403);

        $label = $model instanceof Projeto ? 'Projeto' : 'Tarefa';
        $modelName = $model->name ?? $model->title;
        $model->update(['status' => $model instanceof Projeto ? 'cliente' : 'done']);

        app(TimelineEngine::class)->record($model, 'approval', "{$label} aprovado(a) pelo cliente");

        $notifyUser = $model->owner_id ?? $model->assignee_id;
        if ($notifyUser) {
            app(NotificationEngine::class)->notify(
                $notifyUser,
                "Aprovação do cliente",
                "{$label} \"{$modelName}\" foi aprovado(a) por {$cliente->name}.",
                null
            );
        }

        $projeto = $model instanceof Projeto ? $model : $model->project;

        return redirect()->route('portal.project', ['token' => $token, 'projeto' => $projeto->ulid])
            ->with('status', 'Aprovado com sucesso.');
    }

    public function download(string $token, int $attachment)
    {
        $cliente = $this->cliente($token);
        $attachment = Attachment::withoutGlobalScope('company')->findOrFail($attachment);
        abort_unless($this->ownsEntity($cliente, $attachment->entity), 403);

        abort_unless($attachment->entity_type === Projeto::class || $attachment->entity_type === Task::class, 403);
        abort_unless(\Illuminate\Support\Facades\Storage::disk('local')->exists($attachment->path), 404);

        return response()->download(
            \Illuminate\Support\Facades\Storage::disk('local')->path($attachment->path),
            $attachment->name
        );
    }

    protected function ownsEntity(Cliente $cliente, $entity): bool
    {
        if ($entity instanceof Projeto) {
            return $entity->client_id === $cliente->id;
        }

        if ($entity instanceof Task) {
            return $entity->project && $entity->project->client_id === $cliente->id;
        }

        if ($entity instanceof Attachment) {
            if ($entity->entity_type === Projeto::class) {
                $proj = Projeto::withoutGlobalScope('company')->where('ulid', $entity->entity_id)->first();

                return $proj && $proj->client_id === $cliente->id;
            }

            if ($entity->entity_type === Task::class) {
                $task = Task::withoutGlobalScope('company')->where('ulid', $entity->entity_id)->first();

                return $task && $task->project && $task->project->client_id === $cliente->id;
            }

            return false;
        }

        return false;
    }
}
