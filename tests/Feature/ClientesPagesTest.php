<?php

namespace Tests\Feature;

use App\Domains\Cliente\Models\Cliente;
use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientesPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function user()
    {
        $this->seed();
        return User::where('email', 'admin@agencyos.com')->first();
    }

    public function test_clientes_index_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/clientes');
        $response->assertStatus(200);
        $response->assertSee('Novo Cliente');
    }

    public function test_clientes_create_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/clientes/create');
        $response->assertStatus(200);
    }

    public function test_cliente_show_renders(): void
    {
        $user = $this->user();
        $this->actingAs($user)->post('/clientes', ['name' => 'Cliente Teste']);
        $cliente = Cliente::where('name', 'Cliente Teste')->firstOrFail();
        $this->actingAs($user)->get("/clientes/{$cliente->ulid}")->assertStatus(200);
    }
}
