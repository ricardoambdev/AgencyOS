<?php

namespace Tests\Feature;

use App\Core\Models\MenuItem;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_menu_item_and_it_overrides_default(): void
    {
        $user = $this->ownerUser();
        $companyId = app(\App\Core\Support\CompanyContext::class)->id();

        $this->assertGreaterThan(0, MenuItem::forCompany()->count());
        $this->assertFalse(MenuItem::forCompany()->contains(fn ($i) => $i->label === 'Meu Item'));

        $this->actingAs($user)
            ->post(route('config.menu.store'), [
                'label' => 'Meu Item',
                'route' => 'leads.index',
                'match' => 'leads.*',
                'order' => 1,
            ])
            ->assertRedirect(route('config.menu.index'));

        $this->assertDatabaseHas('menu_items', ['company_id' => $companyId, 'label' => 'Meu Item']);

        $labels = MenuItem::forCompany()->pluck('label')->all();
        $this->assertContains('Meu Item', $labels);
        $this->assertNotContains('Dashboard', $labels);
    }
}
