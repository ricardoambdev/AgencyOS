<?php

namespace App\Domains\Config\Controllers;

use App\Core\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected array $keys = [
        'company_name' => 'Nome da empresa',
        'timezone' => 'Fuso horário',
        'currency' => 'Moeda padrão',
        'language' => 'Idioma padrão',
        'portal_subdomain' => 'Subdomínio do portal',
    ];

    public function index()
    {
        return view('config.index');
    }

    public function editSettings()
    {
        $keys = $this->keys;
        $values = [];
        $companyId = app(\App\Core\Support\CompanyContext::class)->id();

        foreach (array_keys($keys) as $key) {
            $setting = Setting::where('company_id', $companyId)->where('key', $key)->first();
            $values[$key] = $setting ? (is_array($setting->value) ? ($setting->value[0] ?? '') : $setting->value) : '';
        }

        return view('config.settings.edit', compact('keys', 'values'));
    }

    public function updateSettings(Request $request)
    {
        $rules = [];
        foreach (array_keys($this->keys) as $key) {
            $rules[$key] = 'nullable|string|max:255';
        }
        $data = $request->validate($rules);

        $companyId = app(\App\Core\Support\CompanyContext::class)->id();

        foreach ($this->keys as $key => $label) {
            Setting::updateOrCreate(
                ['company_id' => $companyId, 'key' => $key],
                ['value' => $data[$key] ?? '']
            );
        }

        return redirect()->route('config.settings.edit')
            ->with('status', 'Configurações salvas.');
    }
}
