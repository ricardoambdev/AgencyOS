<?php

namespace Tests\Feature;

use App\Core\Models\ChecklistItem;
use App\Domains\Cliente\Models\Cliente;
use App\Domains\Projeto\Models\ProjectTemplate;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TemplateChecklistTest extends TestCase
{
    use RefreshDatabase;

    public function test_applying_template_creates_checklist_items(): void
    {
        $user = $this->ownerUser();
        $companyId = app(\App\Core\Support\CompanyContext::class)->id();

        $cliente = Cliente::create([
            'company_id' => $companyId,
            'name' => 'Cliente Checklist',
        ]);

        $template = ProjectTemplate::create([
            'company_id' => $companyId,
            'name' => 'Template com Checklist',
            'checklist' => ['Briefing', 'Contrato', 'Kickoff'],
        ]);

        $this->actingAs($user)
            ->post(route('templates.apply.store', $template), [
                'client_id' => $cliente->id,
                'name' => 'Projeto do Checklist',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('checklist_items', ['label' => 'Briefing', 'done' => false]);
        $this->assertDatabaseHas('checklist_items', ['label' => 'Contrato', 'done' => false]);
        $this->assertDatabaseHas('checklist_items', ['label' => 'Kickoff', 'done' => false]);

        $item = ChecklistItem::where('label', 'Briefing')->first();
        $this->actingAs($user)
            ->post(route('projetos.checklist.toggle', $item))
            ->assertRedirect();

        $this->assertTrue($item->fresh()->done);
    }
}
