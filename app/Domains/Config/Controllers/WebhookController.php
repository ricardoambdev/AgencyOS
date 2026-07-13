<?php

namespace App\Domains\Config\Controllers;

use App\Core\Models\Webhook;
use App\Core\Support\EntityCatalog;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebhookController extends Controller
{
    public function index(): View
    {
        $webhooks = Webhook::query()->latest()->paginate(15);

        return view('config.webhooks.index', compact('webhooks'));
    }

    public function create(): View
    {
        $events = EntityCatalog::events();

        return view('config.webhooks.edit', compact('events'))->with('webhook', null);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'url' => ['required', 'url'],
            'secret' => ['nullable', 'string', 'max:255'],
            'events' => ['array'],
            'events.*' => ['string', 'in:' . implode(',', array_keys(EntityCatalog::events()))],
            'headers' => ['nullable', 'string'],
            'active' => ['boolean'],
        ]);

        Webhook::create([
            'company_id' => app(\App\Core\Support\CompanyContext::class)->id(),
            'name' => $data['name'],
            'url' => $data['url'],
            'secret' => $data['secret'] ?? null,
            'events' => $data['events'] ?? [],
            'headers' => $this->parseHeaders($data['headers'] ?? null),
            'active' => (bool) ($data['active'] ?? true),
        ]);

        return redirect()->route('config.webhooks.index')->with('status', 'Webhook criado.');
    }

    public function edit(Webhook $webhook): View
    {
        $events = EntityCatalog::events();

        return view('config.webhooks.edit', compact('events', 'webhook'));
    }

    public function update(Request $request, Webhook $webhook): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'url' => ['required', 'url'],
            'secret' => ['nullable', 'string', 'max:255'],
            'events' => ['array'],
            'events.*' => ['string', 'in:' . implode(',', array_keys(EntityCatalog::events()))],
            'headers' => ['nullable', 'string'],
            'active' => ['boolean'],
        ]);

        $webhook->update([
            'name' => $data['name'],
            'url' => $data['url'],
            'secret' => $data['secret'] ?? $webhook->secret,
            'events' => $data['events'] ?? [],
            'headers' => $this->parseHeaders($data['headers'] ?? null),
            'active' => (bool) ($data['active'] ?? true),
        ]);

        return redirect()->route('config.webhooks.index')->with('status', 'Webhook atualizado.');
    }

    public function destroy(Webhook $webhook): RedirectResponse
    {
        $webhook->delete();

        return redirect()->route('config.webhooks.index')->with('status', 'Webhook removido.');
    }

    protected function parseHeaders(?string $raw): ?array
    {
        if (! $raw) {
            return null;
        }

        $headers = [];
        foreach (explode("\n", $raw) as $line) {
            if (! str_contains($line, ':')) {
                continue;
            }
            [$k, $v] = explode(':', $line, 2);
            $headers[trim($k)] = trim($v);
        }

        return $headers ?: null;
    }
}
