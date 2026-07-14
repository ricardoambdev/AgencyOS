<?php

namespace Tests\Feature;

use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function owner()
    {
        $this->seed();
        return User::where('email', 'admin@agencyos.com')->first();
    }

    protected function member()
    {
        $this->seed();
        return User::where('email', 'membro@agencyos.com')->first();
    }

    public function test_admin_dashboard_loads_for_owner(): void
    {
        $this->actingAs($this->owner())
            ->get('/admin')
            ->assertStatus(200)
            ->assertSee('Painel Administrativo');
    }

    public function test_member_is_forbidden_from_admin(): void
    {
        $this->actingAs($this->member())
            ->get('/admin')
            ->assertStatus(403);
    }

    public function test_resource_index_and_crud_flow(): void
    {
        $user = $this->owner();

        $this->actingAs($user)
            ->get('/admin/leads')
            ->assertStatus(200)
            ->assertSee('Leads');

        $this->actingAs($user)
            ->get('/admin/leads/create')
            ->assertStatus(200);

        $this->actingAs($user)
            ->post('/admin/leads', [
                'name' => 'Lead Admin',
                'email' => 'adminlead@example.com',
                'company_name' => 'Empresa X',
                'status' => 'new',
                'value' => 1000,
            ])
            ->assertRedirect('/admin/leads');

        $this->assertDatabaseHas('leads', ['email' => 'adminlead@example.com']);

        $lead = \App\Domains\Crm\Models\Lead::where('email', 'adminlead@example.com')->first();
        $this->assertNotNull($lead);

        $this->actingAs($user)
            ->put('/admin/leads/' . $lead->id, [
                'name' => 'Lead Admin Editado',
                'email' => 'adminlead@example.com',
                'status' => 'contact',
            ])
            ->assertRedirect('/admin/leads');

        $this->assertDatabaseHas('leads', ['name' => 'Lead Admin Editado']);

        $this->actingAs($user)
            ->get('/admin/leads/export')
            ->assertStatus(200)
            ->assertHeader('content-type', 'text/csv; charset=utf-8');

        $this->actingAs($user)
            ->delete('/admin/leads/' . $lead->id)
            ->assertRedirect('/admin/leads');

        $this->assertDatabaseMissing('leads', ['email' => 'adminlead@example.com']);
    }

    public function test_show_resource_loads(): void
    {
        $user = $this->owner();
        $lead = \App\Domains\Crm\Models\Lead::where('email', 'lead@teste.com')->first();

        $this->actingAs($user)
            ->get('/admin/leads/' . $lead->id)
            ->assertStatus(200)
            ->assertSee($lead->name);
    }
}
