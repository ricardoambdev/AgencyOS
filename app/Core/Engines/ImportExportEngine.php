<?php

namespace App\Core\Engines;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Support\CompanyContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\LazyCollection;

class ImportExportEngine
{
    public static function importCsv(string $modelClass, array $rows, array $columnMap): int
    {
        $companyId = app(CompanyContext::class)->id();
        $created = 0;

        foreach ($rows as $row) {
            $attributes = ['company_id' => $companyId];
            $hasData = false;

            foreach ($columnMap as $csvHeader => $modelField) {
                if (! array_key_exists($csvHeader, $row)) {
                    continue;
                }
                $value = trim((string) $row[$csvHeader]);
                if ($value !== '') {
                    $hasData = true;
                }
                $attributes[$modelField] = $value;
            }

            if (! $hasData) {
                continue;
            }

            if (method_exists($modelClass, 'bootBelongsToCompany')) {
                // company_id já definido acima
            }

            $modelClass::create($attributes);
            $created++;
        }

        return $created;
    }

    public static function exportCsv(\Illuminate\Database\Eloquent\Collection $models, array $columns): string
    {
        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, $columns);

        foreach ($models as $model) {
            $line = [];
            foreach ($columns as $column) {
                $line[] = $model->{$column} ?? '';
            }
            fputcsv($csv, $line);
        }

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        return $content;
    }

    public static function parseCsv(string $path): array
    {
        $records = LazyCollection::make(function () use ($path) {
            $handle = fopen($path, 'r');
            while (($line = fgetcsv($handle)) !== false) {
                yield $line;
            }
            fclose($handle);
        });

        $header = null;
        $rows = [];
        foreach ($records as $index => $line) {
            if ($index === 0) {
                $header = $line;
                continue;
            }
            if (empty(array_filter($line))) {
                continue;
            }
            $rows[] = array_combine($header, $line);
        }

        return $rows;
    }
}
