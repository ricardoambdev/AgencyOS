<?php

namespace Tests\Feature;

use App\Domains\Crm\Models\Lead;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_protected_routes(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get('/leads')->assertRedirect(route('login'));
    }

    public function test_login_succeeds_with_valid_credentials(): void
    {
        $user = $this->ownerUser(['password' => bcrypt('secret123')]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $user = $this->ownerUser(['password' => bcrypt('secret123')]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertRedirect();

        $this->assertGuest();
    }

    public function test_logout_redirects_to_login(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_password_reset_flow_works(): void
    {
        $user = $this->ownerUser(['email' => 'reset@example.com', 'password' => bcrypt('oldpass123')]);

        $this->post(route('password.email'), ['email' => 'reset@example.com'])
            ->assertRedirect();

        $token = \Illuminate\Support\Facades\Password::createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'reset@example.com',
            'password' => 'newpass123',
            'password_confirmation' => 'newpass123',
        ])->assertRedirect(route('login'));

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpass123', $user->fresh()->password));
    }

    public function test_lead_requires_authentication_to_be_created(): void
    {
        $this->post('/leads', ['name' => 'Sem auth'])->assertRedirect(route('login'));
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_email_verification_flow_works(): void
    {
        \Illuminate\Support\Facades\Notification::fake();

        $this->post(route('register'), [
            'name' => 'Novo User',
            'email' => 'novo@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'company' => 'Empresa Teste',
        ])->assertRedirect(route('verification.notice'));

        $user = \App\Domains\Usuario\Models\User::where('email', 'novo@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);

        \Illuminate\Support\Facades\Notification::assertSentTo(
            $user,
            \Illuminate\Auth\Notifications\VerifyEmail::class
        );

        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->get($url)->assertRedirect(route('dashboard'));

        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
