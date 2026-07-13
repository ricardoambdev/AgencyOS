<?php

namespace App\Jobs;

use App\Core\Engines\ReportEngine;
use App\Domains\Usuario\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BuildReportExport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $report,
        public string $format = 'csv',
        public ?int $userId = null
    ) {
        $this->onQueue('exports');
    }

    public function handle(): void
    {
        abort_unless(in_array($this->report, ['crm', 'projetos', 'faturamento', 'tarefas', 'carga']), 404);

        $rows = app(ReportEngine::class)->export($this->report);
        $stamp = now()->format('Y-m-d-H-i-s');
        $path = "exports/relatorio-{$this->report}-{$stamp}.{$this->format}";

        if ($this->format === 'xlsx') {
            $xml = '<?xml version="1.0"?>' . "\n";
            $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" ';
            $xml .= 'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">';
            $xml .= '<Worksheet ss:Name="' . htmlspecialchars($this->report) . '"><Table>';
            foreach ($rows as $line) {
                $xml .= '<Row>';
                foreach ($line as $cell) {
                    $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars((string) $cell) . '</Data></Cell>';
                }
                $xml .= '</Row>';
            }
            $xml .= '</Table></Worksheet></Workbook>';
            \Illuminate\Support\Facades\Storage::disk('local')->put($path, $xml);
        } else {
            $csv = fopen('php://temp', 'r+');
            foreach ($rows as $line) {
                fputcsv($csv, $line, ';');
            }
            rewind($csv);
            $content = stream_get_contents($csv);
            fclose($csv);
            \Illuminate\Support\Facades\Storage::disk('local')->put($path, $content);
        }
    }
}
