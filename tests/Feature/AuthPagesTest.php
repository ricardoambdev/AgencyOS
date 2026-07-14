<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthPagesTest extends TestCase
{
    public function test_login_page_renders_with_design_system(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Entrar no AgencyOS');
        $response->assertSee('Esqueci minha senha');
    }

    public function test_register_page_renders_with_design_system(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Criar nova empresa');
        $response->assertSee('workspace_template');
    }
}
