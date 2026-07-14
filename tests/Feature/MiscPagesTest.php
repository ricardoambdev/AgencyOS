<?php

namespace Tests\Feature;

use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiscPagesTest extends TestCase
{
    use RefreshDatabase;

    protected $cachedUser;

    protected function user()
    {
        if (! isset($this->cachedUser)) {
            $this->seed();
            $this->cachedUser = User::where('email', 'admin@agencyos.com')->first();
        }
        return $this->cachedUser;
    }

    public function test_misc_pages_render(): void
    {
        $user = $this->user();

        $this->actingAs($user)->get('/favoritos')->assertStatus(200);
        $this->actingAs($user)->get('/notifications')->assertStatus(200);
        $this->actingAs($user)->get('/search?q=teste')->assertStatus(200);
        $this->actingAs($user)->get('/relatorios')->assertStatus(200);
        $this->actingAs($user)->get(route('leads.import'))->assertStatus(200);
        $this->actingAs($user)->get(route('dashboard.customize'))->assertStatus(200);
    }

    public function test_portal_dashboard_renders(): void
    {
        $this->seed();
        $cliente = \App\Domains\Cliente\Models\Cliente::where('email', 'contato@cliente.com')->first();
        $cliente->update(['portal_token' => 'tokenteste123']);

        $this->get('/portal/tokenteste123')->assertStatus(200);
    }
}
