<?php

namespace App\Domains\Relatorios\Controllers;

use App\Core\Engines\ReportEngine;
use App\Http\Controllers\Controller;
use App\Jobs\BuildReportExport;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $engine = app(ReportEngine::class);

        return view('relatorios.index', [
            'kpis' => $engine->kpis(),
            'crm' => $engine->crmFunnel(),
            'projetos' => $engine->projectsByStatus(),
            'faturamento' => $engine->billingSummary(),
            'faturamento_mes' => $engine->billingByMonth(),
            'tarefas' => $engine->tasksByStatus(),
            'prioridades' => $engine->tasksByPriority(),
            'carga' => $engine->workload(),
        ]);
    }

    public function export(Request $request, string $report)
    {
        abort_unless(in_array($report, ['crm', 'projetos', 'faturamento', 'tarefas', 'carga']), 404);

        $rows = app(ReportEngine::class)->export($report);
        $filename = "relatorio-{$report}-" . now()->format('Y-m-d');

        $format = $request->get('format', 'csv');

        BuildReportExport::dispatch($report, $format, auth()->id());

        if ($format === 'xlsx') {
            return $this->excelExport($rows, $report, $filename);
        }

        if ($format === 'pdf') {
            $titles = [
                'crm' => 'Funil de CRM',
                'projetos' => 'Projetos por Status',
                'faturamento' => 'Faturamento',
                'tarefas' => 'Tarefas por Status',
                'carga' => 'Carga de Trabalho',
            ];

            return response()->view('relatorios.print', [
                'rows' => $rows,
                'title' => $titles[$report] ?? $report,
                'generatedAt' => now()->format('d/m/Y H:i'),
            ])
                ->header('Content-Type', 'text/html; charset=utf-8')
                ->header('Content-Disposition', "inline; filename=\"{$filename}.html\"");
        }

        $csv = fopen('php://temp', 'r+');
        foreach ($rows as $line) {
            fputcsv($csv, $line, ';');
        }
        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        return response($content)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}.csv\"");
    }

    protected function excelExport(array $rows, string $report, string $filename): \Symfony\Component\HttpFoundation\Response
    {
        $xml = '<?xml version="1.0"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" ';
        $xml .= 'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">';
        $xml .= '<Worksheet ss:Name="' . htmlspecialchars($report) . '"><Table>';

        foreach ($rows as $line) {
            $xml .= '<Row>';
            foreach ($line as $cell) {
                $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars((string) $cell) . '</Data></Cell>';
            }
            $xml .= '</Row>';
        }

        $xml .= '</Table></Worksheet></Workbook>';

        return response($xml)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}.xls\"");
    }
}
