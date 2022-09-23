<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;

class CardSheetImport implements  WithMultipleSheets, WithChunkReading, WithStartRow, WithCalculatedFormulas
//, ShouldQueue
{
    public function sheets(): array
    {
        return [
            'Sheet1' => new CardSheetImportHelper()
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function startRow(): int
    {
        return 2;
    }
}
