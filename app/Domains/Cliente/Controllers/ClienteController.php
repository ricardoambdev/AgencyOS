<?php

namespace App\Domains\Cliente\Controllers;

use App\Domains\Cliente\Actions\CreateClienteAction;
use App\Domains\Cliente\Actions\DeleteClienteAction;
use App\Domains\Cliente\Actions\UpdateClienteAction;
use App\Domains\Cliente\Models\Cliente;
use App\Domains\Usuario\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $clientes = Cliente::with('owner')->latest()->paginate(15);

        return view('clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        $owners = User::all();

        return view('clientes.create', compact('owners'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'document' => ['nullable', 'string'],
            'type' => ['nullable', 'in:company,person'],
            'address' => ['nullable', 'string'],
            'owner_id' => ['nullable', 'exists:users,id'],
        ]);

        $this->authorize('create', Cliente::class);
        $cliente = app(CreateClienteAction::class)->handle($data);
        $cliente->syncTags(explode(',', $request->input('tags', '')));

        return redirect()->route('clientes.index')->with('status', 'Cliente criado.');
    }

    public function show(Cliente $cliente): View
    {
        $this->authorize('view', $cliente);
        $cliente->load(['owner', 'timeline.user', 'comments.user', 'projetos']);

        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente): View
    {
        $this->authorize('update', $cliente);
        $owners = User::all();

        return view('clientes.edit', compact('cliente', 'owners'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $this->authorize('update', $cliente);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'document' => ['nullable', 'string'],
            'type' => ['nullable', 'in:company,person'],
            'address' => ['nullable', 'string'],
            'owner_id' => ['nullable', 'exists:users,id'],
        ]);

        app(UpdateClienteAction::class)->handle($cliente, $data);
        $cliente->syncTags(explode(',', $request->input('tags', '')));

        return redirect()->route('clientes.index')->with('status', 'Cliente atualizado.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $this->authorize('delete', $cliente);
        app(DeleteClienteAction::class)->handle($cliente);

        return redirect()->route('clientes.index')->with('status', 'Cliente removido.');
    }
}
