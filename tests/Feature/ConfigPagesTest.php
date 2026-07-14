<?php

namespace Tests\Feature;

use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfigPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function user()
    {
        if (! isset($this->cachedUser)) {
            $this->seed();
            $this->cachedUser = User::where('email', 'admin@agencyos.com')->first();
        }
        return $this->cachedUser;
    }

    public function test_config_pages_render(): void
    {
        $routes = [
            '/config',
            '/config/settings/edit',
            '/config/roles',
            '/config/roles/create',
            '/config/workflow-states',
            '/config/workflows',
            '/config/workflows/create',
            '/config/custom-fields',
            '/config/custom-fields/create',
            '/config/menu',
            '/config/automations',
            '/config/webhooks',
        ];

        $user = $this->user();
        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(200, "Falhou em: {$route}");
        }
    }

    public function test_config_edit_pages_render(): void
    {
        $user = $this->user();

        $role = \App\Domains\Company\Models\Role::first();
        $this->actingAs($user)->get("/config/roles/{$role->ulid}/edit")->assertStatus(200);

        $state = \App\Core\Models\WorkflowState::first();
        if ($state) {
            $this->actingAs($user)->get("/config/workflow-states/{$state->ulid}/edit")->assertStatus(200);
        }

        $wf = \App\Core\Models\Workflow::first();
        if ($wf) {
            $this->actingAs($user)->get("/config/workflows/{$wf->ulid}/edit")->assertStatus(200);
        }
    }
}
