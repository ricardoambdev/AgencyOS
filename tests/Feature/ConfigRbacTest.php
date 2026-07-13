<?php

namespace Tests\Feature;

use App\Domains\Config\Models\CustomField;
use App\Domains\Config\Models\Webhook;
use App\Domains\Company\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConfigRbacTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_access_config_hub(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->get(route('config.index'))
            ->assertOk();
    }

    public function test_member_is_forbidden_from_config_hub(): void
    {
        $user = $this->memberUser();

        $this->actingAs($user)
            ->get(route('config.index'))
            ->assertForbidden();
    }

    public function test_member_is_forbidden_from_creating_custom_fields(): void
    {
        $user = $this->memberUser();

        $this->actingAs($user)
            ->post(route('config.custom-fields.store'), [
                'entity_type' => 'App\Domains\Projeto\Models\Projeto',
                'name' => 'campo_x',
                'label' => 'Campo X',
                'type' => 'text',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('custom_fields', 0);
    }

    public function test_owner_can_create_role(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->post(route('config.roles.store'), [
                'name' => 'Editor',
                'slug' => 'editor',
                'capabilities' => ['crm.view', 'projeto.view'],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('roles', ['slug' => 'editor']);
    }

    public function test_owner_can_create_custom_field(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->post(route('config.custom-fields.store'), [
                'entity_type' => 'App\Domains\Projeto\Models\Projeto',
                'name' => 'campo_teste',
                'label' => 'Campo Teste',
                'type' => 'select',
                'options' => [['value' => 'a', 'label' => 'A']],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('custom_fields', ['name' => 'campo_teste']);
    }

    public function test_owner_can_create_webhook(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->post(route('config.webhooks.store'), [
                'name' => 'Meu Webhook',
                'url' => 'https://example.com/hook',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('webhooks', ['name' => 'Meu Webhook']);
    }
}
