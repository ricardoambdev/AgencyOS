<?php

namespace Tests\Feature;

use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendaPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function user()
    {
        $this->seed();
        return User::where('email', 'admin@agencyos.com')->first();
    }

    public function test_agenda_index_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/agenda');
        $response->assertStatus(200);
        $response->assertSee('Novo Evento');
    }

    public function test_agenda_create_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/agenda/create');
        $response->assertStatus(200);
    }

    public function test_agenda_store_and_show(): void
    {
        $user = $this->user();
        $this->actingAs($user)->post('/agenda', [
            'title' => 'Reuniao Teste',
            'start_at' => now()->format('Y-m-d\TH:i'),
            'user_id' => $user->id,
        ]);
        $this->actingAs($user)->get('/agenda')->assertSee('Reuniao Teste');
    }
}
