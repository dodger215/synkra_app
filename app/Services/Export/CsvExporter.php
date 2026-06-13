<?php

namespace App\Services\Export;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExporter
{
    /**
     * Export an Eloquent collection to a CSV download response.
     *
     * @param  Collection  $data
     * @param  array       $columns  e.g. ['id','name','sku'] – keys from the model
     * @param  array       $headers  Human-readable headers (same order as $columns)
     * @param  string      $filename
     */
    public function export(Collection $data, array $columns, array $headers, string $filename = 'export.csv'): StreamedResponse
    {
        return new StreamedResponse(function () use ($data, $columns, $headers) {
            $handle = fopen('php://output', 'w');

            // BOM for Excel UTF-8 compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            // Header row
            fputcsv($handle, $headers);

            // Data rows
            foreach ($data as $row) {
                $line = [];
                foreach ($columns as $col) {
                    $value = data_get($row, $col);

                    // Convert arrays/objects to JSON
                    if (is_array($value) || is_object($value)) {
                        $value = json_encode($value);
                    }

                    $line[] = $value;
                }
                fputcsv($handle, $line);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
