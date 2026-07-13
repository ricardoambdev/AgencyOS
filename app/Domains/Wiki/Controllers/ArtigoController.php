<?php

namespace App\Domains\Wiki\Controllers;

use App\Core\Engines\TimelineEngine;
use App\Domains\Usuario\Models\User;
use App\Domains\Wiki\Actions\CreateArtigoAction;
use App\Domains\Wiki\Actions\DeleteArtigoAction;
use App\Domains\Wiki\Actions\UpdateArtigoAction;
use App\Domains\Wiki\Models\Artigo;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArtigoController extends Controller
{
    public static function categorias(): array
    {
        return [
            'geral' => 'Geral',
            'processos' => 'Processos',
            'marcas' => 'Marcas',
            'ferramentas' => 'Ferramentas',
            'clientes' => 'Clientes',
            'treinamento' => 'Treinamento',
        ];
    }

    public static function status(): array
    {
        return [
            'rascunho' => 'Rascunho',
            'publicado' => 'Publicado',
        ];
    }

    public function index(Request $request): View
    {
        $query = Artigo::with('author');

        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->string('q').'%')
                  ->orWhere('body', 'like', '%'.$request->string('q').'%');
            });
        }

        $artigos = $query->latest()->paginate(15)->withQueryString();
        $porCategoria = Artigo::select('category')
            ->selectRaw('count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        return view('wiki.index', compact('artigos', 'porCategoria'));
    }

    public function create(): View
    {
        return view('wiki.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'category' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'client_visible' => ['nullable', 'boolean'],
        ]);

        $data['author_id'] = auth()->id();
        $this->authorize('create', Artigo::class);
        $artigo = app(CreateArtigoAction::class)->handle($data);

        app(TimelineEngine::class)->record($artigo, 'created', 'Artigo criado');

        return redirect()->route('wiki.show', $artigo)->with('status', 'Artigo criado.');
    }

    public function show(Artigo $artigo): View
    {
        $this->authorize('view', $artigo);
        $artigo->load(['author', 'timeline.user', 'comments.user', 'attachments']);

        return view('wiki.show', compact('artigo'));
    }

    public function edit(Artigo $artigo): View
    {
        $this->authorize('update', $artigo);

        return view('wiki.edit', compact('artigo'));
    }

    public function update(Request $request, Artigo $artigo): RedirectResponse
    {
        $this->authorize('update', $artigo);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'category' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'client_visible' => ['nullable', 'boolean'],
        ]);

        $oldStatus = $artigo->status;
        app(UpdateArtigoAction::class)->handle($artigo, $data);

        if (($data['status'] ?? null) && $data['status'] !== $oldStatus) {
            app(TimelineEngine::class)->record($artigo, 'status', 'Status alterado', $oldStatus.' → '.$artigo->status);
        }

        return redirect()->route('wiki.show', $artigo)->with('status', 'Artigo atualizado.');
    }

    public function destroy(Artigo $artigo): RedirectResponse
    {
        $this->authorize('delete', $artigo);
        app(DeleteArtigoAction::class)->handle($artigo);

        return redirect()->route('wiki.index')->with('status', 'Artigo removido.');
    }
}
