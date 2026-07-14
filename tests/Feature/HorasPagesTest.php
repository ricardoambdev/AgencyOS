<?php

namespace Tests\Feature;

use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HorasPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function user()
    {
        $this->seed();
        return User::where('email', 'admin@agencyos.com')->first();
    }

    public function test_horas_index_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/horas');
        $response->assertStatus(200);
        $response->assertSee('Lançar Horas');
    }

    public function test_horas_create_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/horas/create');
        $response->assertStatus(200);
    }
}
