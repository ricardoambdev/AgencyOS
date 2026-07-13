<?php

namespace App\Domains\Config\Controllers;

use App\Core\Models\MenuItem;
use App\Core\Support\CompanyContext;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $items = MenuItem::query()
            ->where('company_id', app(CompanyContext::class)->id())
            ->orderBy('order')
            ->get();

        return view('config.menu.index', compact('items'));
    }

    public function create(): View
    {
        return view('config.menu.edit', ['item' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'route' => ['nullable', 'string'],
            'match' => ['nullable', 'string'],
            'url' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:30'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        MenuItem::create([
            'company_id' => app(CompanyContext::class)->id(),
            'label' => $data['label'],
            'route' => $data['route'] ?? null,
            'match' => $data['match'] ?? null,
            'url' => $data['url'] ?? null,
            'icon' => $data['icon'] ?? null,
            'order' => (int) ($data['order'] ?? 0),
        ]);

        return redirect()->route('config.menu.index')->with('status', 'Item de menu adicionado.');
    }

    public function edit(MenuItem $menuItem): View
    {
        return view('config.menu.edit', ['item' => $menuItem]);
    }

    public function update(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'route' => ['nullable', 'string'],
            'match' => ['nullable', 'string'],
            'url' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:30'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $menuItem->update([
            'label' => $data['label'],
            'route' => $data['route'] ?? null,
            'match' => $data['match'] ?? null,
            'url' => $data['url'] ?? null,
            'icon' => $data['icon'] ?? null,
            'order' => (int) ($data['order'] ?? 0),
        ]);

        return redirect()->route('config.menu.index')->with('status', 'Item de menu atualizado.');
    }

    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        $menuItem->delete();

        return redirect()->route('config.menu.index')->with('status', 'Item de menu removido.');
    }
}
