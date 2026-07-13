<?php

namespace App\Http\Controllers;

use App\Core\Engines\TimelineEngine;
use App\Core\Support\DashboardWidget;
use App\Domains\Agenda\Models\Evento;
use App\Domains\Crm\Models\Lead;
use App\Domains\Cliente\Models\Cliente;
use App\Domains\Financeiro\Models\Invoice;
use App\Domains\Projeto\Models\Projeto;
use App\Domains\Projeto\Models\Task;
use App\Domains\Usuario\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'leads' => Lead::count(),
            'clientes' => Cliente::count(),
            'projetos' => Projeto::count(),
            'tasks' => Task::where('status', '!=', 'done')->count(),
        ];

        $financial = [
            'receber' => Invoice::where('status', '!=', 'paid')->sum('total'),
            'recebido' => Invoice::where('status', 'paid')->sum('total'),
        ];

        $timeline = app(TimelineEngine::class)->forContext();
        $myTasks = Task::where('assignee_id', auth()->id())
            ->where('status', '!=', 'done')
            ->with('project')
            ->take(10)
            ->get();
        $events = Evento::where('start_at', '>=', now()->startOfDay())
            ->orderBy('start_at')
            ->take(8)
            ->get();

        $widgets = DashboardWidget::forUser(auth()->user());

        return view('dashboard.index', compact('stats', 'financial', 'timeline', 'myTasks', 'events', 'widgets'));
    }

    public function customize(): View
    {
        $available = DashboardWidget::available();
        $selected = \App\Core\Models\UserDashboard::where('user_id', auth()->id())
            ->orderBy('order')
            ->pluck('widget')
            ->toArray();

        return view('dashboard.customize', compact('available', 'selected'));
    }

    public function updateCustomization(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'widgets' => 'nullable|array',
            'widgets.*' => 'string',
        ]);

        $user = auth()->user();
        $keys = $data['widgets'] ?? [];

        \App\Core\Models\UserDashboard::where('user_id', $user->id)->delete();

        foreach ($keys as $order => $key) {
            if (! array_key_exists($key, DashboardWidget::available())) {
                continue;
            }

            \App\Core\Models\UserDashboard::create([
                'user_id' => $user->id,
                'widget' => $key,
                'order' => $order,
            ]);
        }

        return redirect()->route('dashboard')->with('status', 'Dashboard personalizado salvo.');
    }
}
