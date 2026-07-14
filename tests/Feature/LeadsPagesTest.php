<?php

namespace Tests\Feature;

use App\Domains\Crm\Models\Lead;
use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadsPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function user()
    {
        $this->seed();
        return User::where('email', 'admin@agencyos.com')->first();
    }

    public function test_leads_index_renders_with_design_system(): void
    {
        $response = $this->actingAs($this->user())->get('/leads');
        $response->assertStatus(200);
        $response->assertSee('CRM');
        $response->assertSee('Novo Lead');
    }

    public function test_leads_create_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/leads/create');
        $response->assertStatus(200);
    }

    public function test_lead_show_renders(): void
    {
        $user = $this->user();
        $statuses = \App\Core\Models\WorkflowState::resolve(\App\Domains\Crm\Models\Lead::class);
        $initial = array_key_first($statuses);

        $this->actingAs($user)->post('/leads', [
            'name' => 'Lead Teste',
            'email' => 'lead-teste@example.com',
            'status' => $initial,
        ]);

        $lead = Lead::where('name', 'Lead Teste')->firstOrFail();
        $this->actingAs($user)->get("/leads/{$lead->ulid}")->assertStatus(200);
    }
}
