<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_dashboard_with_widgets(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Meus Projetos');
    }

    public function test_member_can_view_dashboard(): void
    {
        $user = $this->memberUser();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk();
    }

    public function test_owner_can_open_dashboard_customization(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->get(route('dashboard.customize'))
            ->assertOk();
    }

    public function test_owner_can_persist_dashboard_customization(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->post(route('dashboard.customize.update'), [
                'widgets' => ['meus_projetos', 'minhas_tarefas', 'faturamento_mes'],
            ])
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('user_dashboards', [
            'user_id' => $user->id,
            'widget' => 'meus_projetos',
        ]);
    }
}
