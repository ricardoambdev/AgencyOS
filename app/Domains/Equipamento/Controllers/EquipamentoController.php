<?php

namespace App\Domains\Equipamento\Controllers;

use App\Core\Engines\TimelineEngine;
use App\Domains\Equipamento\Actions\CreateEquipamentoAction;
use App\Domains\Equipamento\Actions\DeleteEquipamentoAction;
use App\Domains\Equipamento\Actions\UpdateEquipamentoAction;
use App\Domains\Equipamento\Models\Equipamento;
use App\Domains\Usuario\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipamentoController extends Controller
{
    public static function tipos(): array
    {
        return [
            'hardware' => 'Hardware',
            'software' => 'Software',
            'veiculo' => 'Veículo',
            'mobiliario' => 'Mobiliário',
            'outro' => 'Outro',
        ];
    }

    public static function status(): array
    {
        return [
            'disponivel' => 'Disponível',
            'em_uso' => 'Em uso',
            'manutencao' => 'Manutenção',
            'descartado' => 'Descartado',
        ];
    }

    public function index(Request $request): View
    {
        $query = Equipamento::with('owner');

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->string('q').'%')
                  ->orWhere('serial', 'like', '%'.$request->string('q').'%')
                  ->orWhere('description', 'like', '%'.$request->string('q').'%');
            });
        }

        $equipamentos = $query->latest()->paginate(15)->withQueryString();

        return view('equipamentos.index', compact('equipamentos'));
    }

    public function create(): View
    {
        $owners = User::all();

        return view('equipamentos.create', compact('owners'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'serial' => ['nullable', 'string', 'max:255'],
            'purchase_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
            'valor' => ['nullable', 'numeric', 'min:0'],
            'tem_seguro' => ['nullable', 'boolean'],
            'valor_seguro' => ['nullable', 'numeric', 'min:0', 'required_if:tem_seguro,1'],
            'situacao' => ['nullable', 'string', 'in:funcional,desatualizada,quebrada'],
        ]);

        $this->authorize('create', Equipamento::class);
        $equipamento = app(CreateEquipamentoAction::class)->handle($data);
        $equipamento->syncTags(explode(',', $request->input('tags', '')));

        app(TimelineEngine::class)->record($equipamento, 'created', 'Equipamento cadastrado');

        return redirect()->route('equipamentos.show', $equipamento)->with('status', 'Equipamento cadastrado.');
    }

    public function show(Equipamento $equipamento): View
    {
        $this->authorize('view', $equipamento);
        $equipamento->load(['owner', 'timeline.user', 'comments.user', 'attachments']);

        return view('equipamentos.show', compact('equipamento'));
    }

    public function edit(Equipamento $equipamento): View
    {
        $this->authorize('update', $equipamento);
        $owners = User::all();

        return view('equipamentos.edit', compact('equipamento', 'owners'));
    }

    public function update(Request $request, Equipamento $equipamento): RedirectResponse
    {
        $this->authorize('update', $equipamento);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'serial' => ['nullable', 'string', 'max:255'],
            'purchase_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
            'valor' => ['nullable', 'numeric', 'min:0'],
            'tem_seguro' => ['nullable', 'boolean'],
            'valor_seguro' => ['nullable', 'numeric', 'min:0', 'required_if:tem_seguro,1'],
            'situacao' => ['nullable', 'string', 'in:funcional,desatualizada,quebrada'],
        ]);

        $oldStatus = $equipamento->status;
        app(UpdateEquipamentoAction::class)->handle($equipamento, $data);
        $equipamento->syncTags(explode(',', $request->input('tags', '')));

        if (($data['status'] ?? null) && $data['status'] !== $oldStatus) {
            app(TimelineEngine::class)->record($equipamento, 'status', 'Status alterado', $oldStatus.' → '.$equipamento->status);
        }

        return redirect()->route('equipamentos.show', $equipamento)->with('status', 'Equipamento atualizado.');
    }

    public function destroy(Equipamento $equipamento): RedirectResponse
    {
        $this->authorize('delete', $equipamento);
        app(DeleteEquipamentoAction::class)->handle($equipamento);

        return redirect()->route('equipamentos.index')->with('status', 'Equipamento removido.');
    }
}
