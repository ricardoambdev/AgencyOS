<?php

namespace Tests\Feature;

use App\Domains\Crm\Models\Lead;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_global_search_returns_results(): void
    {
        $user = $this->ownerUser();
        Lead::create([
            'company_id' => app(\App\Core\Support\CompanyContext::class)->id(),
            'name' => 'Lead Especial Busca',
        ]);

        $this->actingAs($user)
            ->get('/search?q='.urlencode('Especial Busca'))
            ->assertOk()
            ->assertSee('Lead Especial Busca');
    }

    public function test_reports_index_loads(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->get(route('relatorios.index'))
            ->assertOk();
    }

    public function test_report_export_downloads_csv(): void
    {
        $user = $this->ownerUser();

        $response = $this->actingAs($user)
            ->get(route('relatorios.export', 'projetos'))
            ->assertOk();

        $this->assertStringContainsString('text/csv', (string) $response->headers->get('Content-Type'));
    }

    public function test_report_export_downloads_excel(): void
    {
        $user = $this->ownerUser();

        $response = $this->actingAs($user)
            ->get(route('relatorios.export', 'projetos') . '?format=xlsx')
            ->assertOk();

        $this->assertStringContainsString('vnd.ms-excel', (string) $response->headers->get('Content-Type'));
    }

    public function test_report_export_renders_pdf_view(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->get(route('relatorios.export', 'projetos') . '?format=pdf')
            ->assertOk()
            ->assertSee('Projetos por Status');
    }

    public function test_export_is_queued_and_writes_file(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');
        $user = $this->ownerUser();

        \Illuminate\Support\Facades\Queue::fake();
        $this->actingAs($user)->get(route('relatorios.export', 'tarefas') . '?format=xlsx')->assertOk();
        \Illuminate\Support\Facades\Queue::assertPushed(\App\Jobs\BuildReportExport::class, function ($job) {
            return $job->report === 'tarefas' && $job->format === 'xlsx';
        });

        (new \App\Jobs\BuildReportExport('tarefas', 'csv', $user->id))->handle();
        $files = \Illuminate\Support\Facades\Storage::disk('local')->allFiles('exports');
        $this->assertNotEmpty($files);
        $this->assertTrue(collect($files)->contains(fn ($f) => str_contains($f, 'relatorio-tarefas')));
    }
}
