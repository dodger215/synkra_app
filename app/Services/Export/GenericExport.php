<?php

namespace App\Services\Export;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GenericExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    protected Collection $data;
    protected array $columns;
    protected array $headers;
    protected string $sheetTitle;

    public function __construct(Collection $data, array $columns, array $headers, string $sheetTitle = 'Sheet1')
    {
        $this->data = $data;
        $this->columns = $columns;
        $this->headers = $headers;
        $this->sheetTitle = $sheetTitle;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function map($row): array
    {
        $line = [];
        foreach ($this->columns as $col) {
            $value = data_get($row, $col);
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            }
            $line[] = $value;
        }
        return $line;
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }
}
