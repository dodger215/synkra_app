<?php

namespace App\Services\Export;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetExport implements WithMultipleSheets
{
    protected array $sheets;

    /**
     * @param GenericExport[] $sheets  Array of GenericExport instances (one per sheet)
     */
    public function __construct(array $sheets)
    {
        $this->sheets = $sheets;
    }

    public function sheets(): array
    {
        return $this->sheets;
    }
}
