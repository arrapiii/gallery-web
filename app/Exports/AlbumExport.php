<?php

namespace App\Exports;

use App\Models\Album;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AlbumExport implements WithMultipleSheets
{
    use Exportable;

   protected $albumId;

    public function __construct($albumId)
    {
        $this->albumId = $albumId;
    }

    public function sheets(): array
    {
        return [
            'Albums' => new AlbumSheet($this->albumId),
        ];
    }
}
