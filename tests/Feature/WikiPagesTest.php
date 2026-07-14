<?php

namespace Tests\Feature;

use App\Domains\Usuario\Models\User;
use App\Domains\Wiki\Models\Artigo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WikiPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function user()
    {
        $this->seed();
        return User::where('email', 'admin@agencyos.com')->first();
    }

    public function test_wiki_index_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/wiki');
        $response->assertStatus(200);
        $response->assertSee('Novo Artigo');
    }

    public function test_wiki_create_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/wiki/create');
        $response->assertStatus(200);
    }

    public function test_artigo_show_renders(): void
    {
        $user = $this->user();
        $this->actingAs($user)->post('/wiki', ['title' => 'Artigo Teste', 'body' => 'Conteúdo']);
        $artigo = Artigo::where('title', 'Artigo Teste')->firstOrFail();
        $this->actingAs($user)->get("/wiki/{$artigo->ulid}")->assertStatus(200);
    }
}
