<?php

namespace App\Domains\Config\Controllers;

use App\Domains\Config\Actions\CreateCustomField;
use App\Domains\Config\Actions\DeleteCustomField;
use App\Domains\Config\Actions\UpdateCustomField;
use App\Domains\Config\Models\CustomField;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public static function entityTypes(): array
    {
        return [
            \App\Domains\Crm\Models\Lead::class => 'Lead (CRM)',
            \App\Domains\Cliente\Models\Cliente::class => 'Cliente',
            \App\Domains\Projeto\Models\Projeto::class => 'Projeto',
            \App\Domains\Projeto\Models\Task::class => 'Tarefa',
            \App\Domains\Financeiro\Models\Invoice::class => 'Fatura',
            \App\Domains\Comercial\Models\Contrato::class => 'Contrato',
            \App\Domains\Producao\Models\Entregavel::class => 'Entregável',
            \App\Domains\Wiki\Models\Artigo::class => 'Artigo',
            \App\Domains\Equipamento\Models\Equipamento::class => 'Equipamento',
            \App\Domains\Horas\Models\LancamentoHora::class => 'Lançamento de Hora',
        ];
    }

    public function index()
    {
        $this->authorize('viewAny', CustomField::class);

        $fields = CustomField::orderBy('entity_type')->orderBy('order')->paginate(20);

        return view('config.custom-fields.index', compact('fields'));
    }

    public function create()
    {
        $this->authorize('create', CustomField::class);

        $entityTypes = self::entityTypes();
        $types = CustomField::TYPES;

        return view('config.custom-fields.create', compact('entityTypes', 'types'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', CustomField::class);

        $data = $request->validate([
            'entity_type' => 'required|string',
            'name' => 'required|string|max:60|regex:/^[a-z0-9_]+$/',
            'label' => 'required|string|max:120',
            'type' => 'required|in:'.implode(',', CustomField::TYPES),
            'options' => 'nullable|array',
            'options.*.value' => 'nullable|string',
            'options.*.label' => 'nullable|string',
            'is_filterable' => 'nullable|boolean',
            'is_required' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        app(CreateCustomField::class)->handle($data);

        return redirect()->route('config.custom-fields.index')
            ->with('status', 'Campo personalizado criado.');
    }

    public function edit(CustomField $customField)
    {
        $this->authorize('update', $customField);

        $entityTypes = self::entityTypes();
        $types = CustomField::TYPES;

        return view('config.custom-fields.edit', compact('customField', 'entityTypes', 'types'));
    }

    public function update(Request $request, CustomField $customField)
    {
        $this->authorize('update', $customField);

        $data = $request->validate([
            'entity_type' => 'required|string',
            'name' => 'required|string|max:60|regex:/^[a-z0-9_]+$/',
            'label' => 'required|string|max:120',
            'type' => 'required|in:'.implode(',', CustomField::TYPES),
            'options' => 'nullable|array',
            'options.*.value' => 'nullable|string',
            'options.*.label' => 'nullable|string',
            'is_filterable' => 'nullable|boolean',
            'is_required' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        app(UpdateCustomField::class)->handle($customField, $data);

        return redirect()->route('config.custom-fields.index')
            ->with('status', 'Campo personalizado atualizado.');
    }

    public function destroy(CustomField $customField)
    {
        $this->authorize('delete', $customField);
        app(DeleteCustomField::class)->handle($customField);

        return redirect()->route('config.custom-fields.index')
            ->with('status', 'Campo personalizado excluído.');
    }
}
