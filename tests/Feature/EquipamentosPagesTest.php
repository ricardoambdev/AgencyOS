<?php

namespace Tests\Feature;

use App\Domains\Equipamento\Models\Equipamento;
use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipamentosPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function user()
    {
        $this->seed();
        return User::where('email', 'admin@agencyos.com')->first();
    }

    public function test_equipamentos_index_renders(): void
    {
        $response = $this->actingAs($this->user())->get('/equipamentos');
        $response->assertStatus(200);
        $response->assertSee('Novo Equipamento');
    }

    public function test_equipamentos_create_shows_new_fields(): void
    {
        $response = $this->actingAs($this->user())->get('/equipamentos/create');
        $response->assertStatus(200);
        $response->assertSee('Situação');
        $response->assertSee('Valor do equipamento');
        $response->assertSee('Data de aquisição');
        $response->assertSee('Equipamento possui seguro');
    }

    public function test_store_equipamento_with_value_insurance_and_situation(): void
    {
        $user = $this->user();

        $response = $this->actingAs($user)->post('/equipamentos', [
            'name' => 'Notebook Dell',
            'type' => 'hardware',
            'status' => 'disponivel',
            'situacao' => 'funcional',
            'valor' => '3500.00',
            'tem_seguro' => '1',
            'valor_seguro' => '500.00',
            'purchase_date' => '2024-01-15',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('equipamentos', [
            'name' => 'Notebook Dell',
            'situacao' => 'funcional',
            'valor' => '3500.00',
            'tem_seguro' => true,
            'valor_seguro' => '500.00',
        ]);

        $eq = Equipamento::where('name', 'Notebook Dell')->first();
        $view = $this->actingAs($user)->get('/equipamentos/'.$eq->ulid);
        $view->assertStatus(200);
        $view->assertSee('R$ 3.500,00');
        $view->assertSee('Sim');
    }

    public function test_tempo_de_uso_por_extenso(): void
    {
        $umAnoMeio = new Equipamento(['purchase_date' => now()->subMonths(18)]);
        $this->assertEquals('1 ano e meio', $umAnoMeio->tempoDeUso());

        $doisAnosDoisMeses = new Equipamento(['purchase_date' => now()->subMonths(26)]);
        $this->assertEquals('2 anos e 2 meses', $doisAnosDoisMeses->tempoDeUso());

        $umMes = new Equipamento(['purchase_date' => now()->subMonth()]);
        $this->assertEquals('1 mês', $umMes->tempoDeUso());

        $semData = new Equipamento();
        $this->assertNull($semData->tempoDeUso());
    }
}
