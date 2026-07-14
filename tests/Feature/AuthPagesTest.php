<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthPagesTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_forgot_password_renders(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
        $response->assertSee('Recuperar senha');
    }

    public function test_reset_password_renders(): void
    {
        $response = $this->get(route('password.reset', ['token' => 'dummy']));
        $response->assertStatus(200);
        $response->assertSee('Redefinir senha');
    }

    public function test_verify_email_view_compiles(): void
    {
        $html = view('auth.verify-email')->render();
        $this->assertStringContainsString('Confirme seu e-mail', $html);
    }
}
