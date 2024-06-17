<?php

namespace App\Exports;

use App\Models\AktivitasUser;
use Illuminate\Support\Carbon;
use App\Models\LaporanAktivitas;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class AktivitasUserSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithCustomStartCell, WithColumnWidths, WithMapping, WithTitle, WithStyles, WithDrawings
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

          // Format the created_at date in Indonesian locale
        $formattedDate = Carbon::parse($aktivitas->created_at)->locale('id')->isoFormat('DD MMMM YYYY HH:mm:ss');

        return [
            ++$count,
            $aktivitas->aktivitas,
            '',
            $formattedDate,
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 5; // Start from row 5
    
        foreach ($this->aktivitas as $aktivitas) {
            if (!empty($aktivitas->foto)) {
                $imageUrl = public_path(Storage::url($aktivitas->foto));
    
                if (file_exists($imageUrl)) {
                    $drawing = new Drawing();
                    $drawing->setPath($imageUrl);
                    $drawing->setHeight(200);
                    $drawing->setWidth(200);
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setCoordinates('D' . $row); // Draw in column D and specified row
                    $drawings[] = $drawing;
                }
            }
            $row++; // Increment row for the next iteration 
        }
    
        return $drawings;
    }
    

    public function startCell(): string
    {
        return 'B2';
    }

    public function columnWidths(): array
    {
        return [
            'B' => 5,
            'C' => 40,
            'D' => 30,
            'E' => 30,
        ];
    }


    public function title(): string
    {
        return 'Aktivitas User';
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $lastDataRow = $highestRow;

        $padding = 0; // Define padding
        $rowHeight = 350 + $padding; // Define initial row height
        
        for ($row = 5; $row <= $lastDataRow; $row += $padding + 1) {
            $sheet->getRowDimension($row)->setRowHeight($rowHeight);
        }
        

    // Set the header background color to blue and apply border
    $sheet->getStyle('B2:E4')->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['rgb' => 'c2d9ff'], // Dill background color
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_MEDIUM,
                'color' => ['rgb' => '000000'], // Black solid border
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

     // Apply styles to data collection rows
     $sheet->getStyle('B5:E'.$lastDataRow)->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_MEDIUM,
                'color' => ['rgb' => '000000'], // Black solid border
            ],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ]);

    // Merge cells for rows 2 and 3 as benchmark merge cells from row 4
    $sheet->mergeCells('B2:E2');
    $sheet->mergeCells('B3:E3');

    $sheet->getStyle('B2:E' . $lastDataRow)->getAlignment()->setWrapText(true);
    }
}

