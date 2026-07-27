<?php

namespace App\Services\Export;

use Google\Client as GoogleClient;
use Google\Service\Sheets as GoogleSheets;
use Google\Service\Sheets\Spreadsheet;
use Google\Service\Sheets\ValueRange;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class GoogleSheetsExporter
{
    protected ?GoogleSheets $sheetsService = null;

    /**
     * Attempt to initialise the Google Sheets API client.
     * Requires a service-account JSON key at config path `services.google.credentials_path`.
     */
    protected function getService(): GoogleSheets
    {
        if ($this->sheetsService) {
            return $this->sheetsService;
        }

        $credentialsPath = config('services.google.credentials_path');

        if (!$credentialsPath || !file_exists($credentialsPath)) {
            throw new \RuntimeException('Google service-account credentials file not configured or missing.');
        }

        $client = new GoogleClient();
        $client->setAuthConfig($credentialsPath);
        $client->addScope(GoogleSheets::SPREADSHEETS);
        $client->setAccessType('offline');

        $this->sheetsService = new GoogleSheets($client);

        return $this->sheetsService;
    }

    /**
     * Export data to a NEW Google Sheet and return the spreadsheet URL.
     */
    public function export(Collection $data, array $columns, array $headers, string $title = 'flowexa Export'): string
    {
        $service = $this->getService();

        // Create new spreadsheet
        $spreadsheet = new Spreadsheet([
            'properties' => ['title' => $title],
        ]);
        $spreadsheet = $service->spreadsheets->create($spreadsheet);
        $spreadsheetId = $spreadsheet->getSpreadsheetId();

        // Build rows
        $rows = [$headers]; // first row = headers
        foreach ($data as $row) {
            $line = [];
            foreach ($columns as $col) {
                $value = data_get($row, $col);
                if (is_array($value) || is_object($value)) {
                    $value = json_encode($value);
                }
                $line[] = $value;
            }
            $rows[] = $line;
        }

        // Write to Sheet1
        $range = 'Sheet1!A1';
        $body = new ValueRange(['values' => $rows]);
        $service->spreadsheets_values->update($spreadsheetId, $range, $body, [
            'valueInputOption' => 'RAW',
        ]);

        Log::info("Exported {$data->count()} rows to Google Sheet: {$spreadsheetId}");

        return $spreadsheet->getSpreadsheetUrl();
    }

    /**
     * Append data to an EXISTING Google Sheet.
     */
    public function appendTo(string $spreadsheetId, Collection $data, array $columns, string $sheetName = 'Sheet1'): void
    {
        $service = $this->getService();

        $rows = [];
        foreach ($data as $row) {
            $line = [];
            foreach ($columns as $col) {
                $value = data_get($row, $col);
                if (is_array($value) || is_object($value)) {
                    $value = json_encode($value);
                }
                $line[] = $value;
            }
            $rows[] = $line;
        }

        $range = "{$sheetName}!A1";
        $body = new ValueRange(['values' => $rows]);
        $service->spreadsheets_values->append($spreadsheetId, $range, $body, [
            'valueInputOption' => 'RAW',
            'insertDataOption' => 'INSERT_ROWS',
        ]);
    }
}
