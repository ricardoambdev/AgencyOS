<?php

namespace Tests\Feature;

use App\Domains\Cliente\Models\Cliente;
use App\Domains\Comercial\Models\Contrato;
use App\Domains\Crm\Models\Lead;
use App\Domains\Equipamento\Models\Equipamento;
use App\Domains\Horas\Models\LancamentoHora;
use App\Domains\Projeto\Models\ProjectTemplate;
use App\Domains\Projeto\Models\Projeto;
use App\Domains\Projeto\Models\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DomainsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_lead(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->post('/leads', ['name' => 'Lead Teste'])
            ->assertRedirect();

        $this->assertDatabaseHas('leads', ['name' => 'Lead Teste']);
    }

    public function test_can_create_cliente(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->post('/clientes', ['name' => 'Cliente Teste'])
            ->assertRedirect();

        $this->assertDatabaseHas('clientes', ['name' => 'Cliente Teste']);
    }

    public function test_can_create_projeto_linked_to_cliente(): void
    {
        $user = $this->ownerUser();
        $cliente = Cliente::create([
            'company_id' => app(\App\Core\Support\CompanyContext::class)->id(),
            'name' => 'Cliente Base',
        ]);

        $this->actingAs($user)
            ->post('/projetos', [
                'client_id' => $cliente->id,
                'name' => 'Projeto Teste',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('projetos', ['name' => 'Projeto Teste', 'client_id' => $cliente->id]);
    }

    public function test_can_create_contrato(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->post('/comercial', ['title' => 'Contrato Teste'])
            ->assertRedirect();

        $this->assertDatabaseHas('contratos', ['title' => 'Contrato Teste']);
    }

    public function test_can_create_equipamento(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->post('/equipamentos', ['name' => 'Notebook'])
            ->assertRedirect();

        $this->assertDatabaseHas('equipamentos', ['name' => 'Notebook']);
    }

    public function test_can_create_lancamento_de_hora(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->post('/horas', [
                'date' => '2026-07-13',
                'hours' => 4,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('lancamentos_horas', ['hours' => 4, 'date' => '2026-07-13 00:00:00']);
    }

    public function test_can_create_template_and_apply_it(): void
    {
        $user = $this->ownerUser();
        $cliente = Cliente::create([
            'company_id' => app(\App\Core\Support\CompanyContext::class)->id(),
            'name' => 'Cliente Template',
        ]);

        $this->actingAs($user)
            ->post('/templates', [
                'name' => 'Template Site',
                'tasks' => [
                    ['title' => 'Briefing', 'priority' => 'high', 'estimated_hours' => 2],
                    ['title' => 'Design', 'priority' => 'medium', 'estimated_hours' => 8],
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('project_templates', ['name' => 'Template Site']);
        $template = ProjectTemplate::where('name', 'Template Site')->first();
        $this->assertDatabaseCount('project_template_tasks', 2);

        $this->actingAs($user)
            ->post(route('templates.apply.store', $template), [
                'client_id' => $cliente->id,
                'name' => 'Site Cliente X',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('projetos', ['name' => 'Site Cliente X']);
        $projeto = Projeto::where('name', 'Site Cliente X')->first();
        $this->assertDatabaseCount('tasks', 2);
        $this->assertEquals(2, Task::where('project_id', $projeto->id)->count());
    }
}
