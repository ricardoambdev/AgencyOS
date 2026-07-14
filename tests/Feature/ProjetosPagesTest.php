<?php

namespace Tests\Feature;

use App\Domains\Cliente\Models\Cliente;
use App\Domains\Projeto\Models\Projeto;
use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjetosPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function user()
    {
        $this->seed();
        return User::where('email', 'admin@agencyos.com')->first();
    }

    public function test_projetos_index_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/projetos');
        $response->assertStatus(200);
        $response->assertSee('Novo Projeto');
    }

    public function test_projetos_create_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/projetos/create');
        $response->assertStatus(200);
    }

    public function test_projeto_show_renders(): void
    {
        $user = $this->user();
        $cliente = Cliente::first();
        $this->actingAs($user)->post('/projetos', [
            'client_id' => $cliente->id,
            'name' => 'Projeto Teste',
        ]);
        $projeto = Projeto::where('name', 'Projeto Teste')->firstOrFail();
        $this->actingAs($user)->get("/projetos/{$projeto->ulid}")->assertStatus(200);
    }
}
