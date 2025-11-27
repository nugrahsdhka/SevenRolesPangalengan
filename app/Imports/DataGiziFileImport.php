<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataGiziFileImport implements WithMultipleSheets
{
    public function __construct(
        private string $period,
        private int $userId
    ) {}

    /**
     * Hanya proses sheet index 0 (Sheet1).
     */
    public function sheets(): array
    {
        return [
            0 => new DataGiziImport($this->period, $this->userId),
        ];
    }
}
