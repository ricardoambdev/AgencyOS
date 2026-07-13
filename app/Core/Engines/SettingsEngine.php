<?php

namespace App\Core\Engines;

use App\Core\Models\Setting;
use App\Core\Support\CompanyContext;
use Illuminate\Support\Facades\Cache;

class SettingsEngine
{
    public function get(string $key, $default = null)
    {
        $companyId = app(CompanyContext::class)->id();

        return Cache::remember("setting:{$companyId}:{$key}", 3600, function () use ($key, $default, $companyId) {
            $row = Setting::query()
                ->where('company_id', $companyId)
                ->where('key', $key)
                ->first();

            return $row ? $row->value : $default;
        });
    }

    public function set(string $key, $value): void
    {
        $companyId = app(CompanyContext::class)->id();

        Setting::query()->updateOrCreate(
            ['company_id' => $companyId, 'key' => $key],
            ['value' => $value]
        );

        Cache::forget("setting:{$companyId}:{$key}");
    }

    public function all(): array
    {
        $companyId = app(CompanyContext::class)->id();

        return Setting::query()
            ->where('company_id', $companyId)
            ->pluck('value', 'key')
            ->toArray();
    }
}
