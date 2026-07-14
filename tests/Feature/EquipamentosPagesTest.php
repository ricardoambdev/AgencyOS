<?php

namespace Tests\Feature;

use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipamentosPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function user()
    {
        $this->seed();
        return User::where('email', 'admin@agencyos.com')->first();
    }

    public function test_equipamentos_index_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/equipamentos');
        $response->assertStatus(200);
        $response->assertSee('Novo Equipamento');
    }

    public function test_equipamentos_create_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/equipamentos/create');
        $response->assertStatus(200);
    }
}
