<?php

namespace Tests\Feature;

use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceiroPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function user()
    {
        $this->seed();
        return User::where('email', 'admin@agencyos.com')->first();
    }

    public function test_financeiro_index_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/financeiro');
        $response->assertStatus(200);
        $response->assertSee('Nova Fatura');
    }

    public function test_financeiro_create_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/financeiro/create');
        $response->assertStatus(200);
    }
}
