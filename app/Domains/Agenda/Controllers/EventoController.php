<?php

namespace App\Domains\Agenda\Controllers;

use App\Domains\Agenda\Models\Evento;
use App\Domains\Usuario\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventoController extends Controller
{
    public function index(): View
    {
        $events = Evento::with('owner', 'project')
            ->where('start_at', '>=', now()->startOfMonth())
            ->orderBy('start_at')
            ->paginate(20);

        return view('agenda.index', compact('events'));
    }

    public function create(): View
    {
        $users = User::all();

        return view('agenda.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date'],
            'all_day' => ['nullable', 'boolean'],
            'location' => ['nullable', 'string'],
            'user_id' => ['nullable', 'exists:users,id'],
            'project_id' => ['nullable', 'exists:projetos,id'],
        ]);

        $data['user_id'] ??= auth()->id();
        Evento::create($data);

        return redirect()->route('agenda.index')->with('status', 'Evento criado.');
    }

    public function destroy(Evento $evento): RedirectResponse
    {
        $evento->delete();

        return redirect()->route('agenda.index')->with('status', 'Evento removido.');
    }
}
