<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AktivitasUserExport implements WithMultipleSheets
{
    use Exportable;

    protected $aktivitas;

    public function __construct($aktivitas)
    {
        $this->aktivitas = $aktivitas;
    }
    
    public function sheets(): array

    
    {
        return [
            new AktivitasUserSheet($this->aktivitas),
        ];
    }
}
