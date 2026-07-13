<?php

namespace Tests\Feature;

use App\Core\Models\WorkflowState;
use App\Domains\Crm\Models\Lead;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkflowStateTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_and_use_custom_lead_state(): void
    {
        $user = $this->ownerUser();
        $companyId = app(\App\Core\Support\CompanyContext::class)->id();

        $this->actingAs($user)
            ->post(route('config.workflow-states.store'), [
                'entity_type' => Lead::class,
                'slug' => 'negociando',
                'name' => 'Em Negociação',
                'color' => '#f59e0b',
                'order' => 3,
                'is_initial' => false,
                'is_final' => false,
            ])
            ->assertRedirect(route('config.workflow-states.index', ['entity_type' => Lead::class]));

        $this->assertDatabaseHas('workflow_states', [
            'company_id' => $companyId,
            'entity_type' => Lead::class,
            'slug' => 'negociando',
            'name' => 'Em Negociação',
        ]);

        $resolved = WorkflowState::resolve(Lead::class, $companyId);
        $this->assertArrayHasKey('negociando', $resolved);
        $this->assertEquals('Em Negociação', $resolved['negociando']);
        $this->assertArrayNotHasKey('contact', $resolved);
    }

    public function test_custom_state_appears_in_lead_form_and_validates(): void
    {
        $user = $this->ownerUser();
        $companyId = app(\App\Core\Support\CompanyContext::class)->id();

        WorkflowState::create([
            'company_id' => $companyId,
            'entity_type' => Lead::class,
            'slug' => 'qualificado_v2',
            'name' => 'Qualificado (novo)',
            'order' => 2,
            'is_initial' => true,
        ]);

        $this->actingAs($user)
            ->get(route('leads.create'))
            ->assertOk()
            ->assertSee('Qualificado (novo)');

        $this->actingAs($user)
            ->post(route('leads.store'), [
                'name' => 'Lead Custom',
                'status' => 'qualificado_v2',
                'value' => 100,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('leads', [
            'name' => 'Lead Custom',
            'status' => 'qualificado_v2',
        ]);
    }

    public function test_rejects_invalid_slug(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->post(route('config.workflow-states.store'), [
                'entity_type' => Lead::class,
                'slug' => 'Bad Slug!',
                'name' => 'Inválido',
            ])
            ->assertSessionHasErrors('slug');
    }
}
