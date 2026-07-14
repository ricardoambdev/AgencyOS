<?php

namespace Tests\Feature;

use App\Domains\Cliente\Models\Cliente;
use App\Domains\Comercial\Models\Contrato;
use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComercialPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function user()
    {
        $this->seed();
        return User::where('email', 'admin@agencyos.com')->first();
    }

    public function test_comercial_index_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/comercial');
        $response->assertStatus(200);
        $response->assertSee('Novo Contrato');
    }

    public function test_comercial_create_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/comercial/create');
        $response->assertStatus(200);
    }

    public function test_contrato_show_renders(): void
    {
        $user = $this->user();
        $cliente = Cliente::first();
        $this->actingAs($user)->post('/comercial', [
            'client_id' => $cliente->id,
            'title' => 'Contrato Teste',
        ]);
        $contrato = Contrato::where('title', 'Contrato Teste')->firstOrFail();
        $this->actingAs($user)->get("/comercial/{$contrato->ulid}")->assertStatus(200);
    }
}
