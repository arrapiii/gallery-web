<?php

namespace App\Exports;

use App\Models\AktivitasUser;
use App\Models\LaporanAktivitas;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AktivitasUserSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithCustomStartCell, WithColumnWidths, WithMapping, WithTitle
{
    protected $aktivitas;
    protected $rowCount = 0;

    public function __construct($aktivitas)
    {
        $this->aktivitas = $aktivitas;
    }

    public function collection()
    {
        return $this->aktivitas;
    }

    public function headings(): array
    {
        // Get the user associated with the first Aktivitas entry
        $user = $this->aktivitas->first()->user;

        // Format the current date
        $formattedDate = strtoupper(Carbon::now()->isoFormat('DD MMMM YYYY'));

        // Return the headings with user's name
        return [
            ['Log Aktivitas', $user->name],
            [$formattedDate],
            ['ID', 'Aktivitas', 'Foto', 'Created At'],
        ];
    }
    
    
    public function map($aktivitas): array
    {
        static $count = 0;

        return [
            ++$count,
            $aktivitas->aktivitas,
            $aktivitas->foto,
            $aktivitas->created_at,
        ];
    }

    public function startCell(): string
    {
        return 'B2';
    }

    public function columnWidths(): array
    {
        return [
            'B' => 20,
            'C' => 30,
            'D' => 20,
            'E' => 20,
        ];
    }


    public function title(): string
    {
        return 'Aktivitas User';
    }

    public function styles(Worksheet $sheet)
    {
         // Determine the last row of the data collection
         $lastRow = $this->rowCount + 4; // Add 3 to account for the header rows
         // Set the header background color to blue and apply border
         $sheet->getStyle('B2:E4')->applyFromArray([
             'fill' => [
                 'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                 'color' => ['rgb' => 'c2d9ff'],
             ],
             'borders' => [
                 'allBorders' => [
                     'borderStyle' => Border::BORDER_MEDIUM,
                     'color' => ['rgb' => '000000'],
                 ],
             ],
             'font' => [
                 'bold' => true,
             ],
             'alignment' => [
                 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
             ],
         ]);

          // Merge cells for rows 2 and 3 as benchmark merge cells from row 4
            $sheet->mergeCells('B2:E2');
            $sheet->mergeCells('B3:E3');
    }
}

