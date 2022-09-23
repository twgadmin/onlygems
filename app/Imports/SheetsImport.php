<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;

class SheetsImport implements  WithMultipleSheets, WithChunkReading, WithStartRow, WithCalculatedFormulas, ShouldQueue
{
    public function sheets(): array
    {
        return [
            'Serial Sheet' => new SheetsImportHelper()
        ];
    }

    public function chunkSize(): int
    {
        return 15;
    }

    public function startRow(): int
    {
        return 2;
    }
}
