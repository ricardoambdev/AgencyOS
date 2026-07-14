<?php

namespace Tests\Feature;

use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_renders_with_new_design_system(): void
    {
        $this->seed();
        $user = User::where('email', 'admin@agencyos.com')->first();
        $this->assertNotNull($user);

        $this->actingAs($user)
            ->get('/')
            ->assertStatus(200)
            ->assertSee('Agency')
            ->assertSee('Olá');
    }

    public function test_sidebar_menu_tree_contains_expected_items(): void
    {
        $tree = \App\Core\Models\MenuItem::tree();
        $labels = $tree->pluck('label')->toArray();
        $this->assertContains('Dashboard', $labels);
        $this->assertContains('Configurações', $labels);
    }
}
