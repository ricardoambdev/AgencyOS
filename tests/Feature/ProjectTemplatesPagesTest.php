<?php

namespace Tests\Feature;

use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTemplatesPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function user()
    {
        $this->seed();
        return User::where('email', 'admin@agencyos.com')->first();
    }

    public function test_templates_index_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/templates');
        $response->assertStatus(200);
        $response->assertSee('Novo Template');
    }

    public function test_templates_create_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/templates/create');
        $response->assertStatus(200);
    }

    public function test_templates_store_and_show(): void
    {
        $user = $this->user();
        $this->actingAs($user)->post('/templates', [
            'name' => 'Template Teste',
            'is_active' => '1',
            'tasks' => [],
            'checklist' => [],
        ]);
        $this->actingAs($user)->get('/templates')->assertSee('Template Teste');
        $created = \App\Domains\Projeto\Models\ProjectTemplate::where('name', 'Template Teste')->firstOrFail();
        $this->actingAs($user)->get("/templates/{$created->ulid}")->assertStatus(200);
    }

    public function test_templates_apply_page_renders(): void
    {
        $user = $this->user();
        $this->actingAs($user)->post('/templates', [
            'name' => 'Template Apply',
            'is_active' => '1',
            'tasks' => [],
            'checklist' => [],
        ]);
        $template = \App\Domains\Projeto\Models\ProjectTemplate::where('name', 'Template Apply')->firstOrFail();
        $this->actingAs($user)->get("/templates/{$template->ulid}/apply")->assertStatus(200);
    }
}
