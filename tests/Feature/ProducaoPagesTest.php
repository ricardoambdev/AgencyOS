<?php

namespace Tests\Feature;

use App\Domains\Projeto\Models\Projeto;
use App\Domains\Producao\Models\Entregavel;
use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProducaoPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function user()
    {
        $this->seed();
        return User::where('email', 'admin@agencyos.com')->first();
    }

    public function test_producao_index_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/producao');
        $response->assertStatus(200);
        $response->assertSee('Novo Entregável');
    }

    public function test_producao_create_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/producao/create');
        $response->assertStatus(200);
    }

    public function test_entregavel_show_renders(): void
    {
        $user = $this->user();
        $projeto = Projeto::first();
        $this->actingAs($user)->post('/producao', [
            'project_id' => $projeto->id,
            'name' => 'Entregável Teste',
        ]);
        $entregavel = Entregavel::where('name', 'Entregável Teste')->firstOrFail();
        $this->actingAs($user)->get("/producao/{$entregavel->ulid}")->assertStatus(200);
    }
}
