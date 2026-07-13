<?php

namespace App\Core\Engines;

use App\Core\Models\Webhook;
use App\Core\Support\CompanyContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class WebhookEngine
{
    public function trigger(string $event, ?Model $subject = null): void
    {
        $hooks = Webhook::query()
            ->where('company_id', app(CompanyContext::class)->id())
            ->where('active', true)
            ->whereJsonContains('events', $event)
            ->get();

        foreach ($hooks as $hook) {
            $this->send($hook, $event, $subject);
        }
    }

    protected function send(Webhook $hook, string $event, ?Model $subject): void
    {
        $payload = [
            'event' => $event,
            'occurred_at' => now()->toIso8601String(),
            'subject' => $subject ? $subject->toArray() : null,
        ];

        $body = json_encode($payload);

        try {
            $request = Http::timeout(5)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-AgencyOS-Event' => $event,
                ])
                ->withHeaders($hook->headers ?? []);

            if ($hook->secret) {
                $request->withHeader(
                    'X-AgencyOS-Signature',
                    'sha256=' . hash_hmac('sha256', $body, $hook->secret)
                );
            }

            $request->post($hook->url, $payload);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
