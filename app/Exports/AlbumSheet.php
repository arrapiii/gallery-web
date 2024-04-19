<?php

namespace App\Exports;

use App\Models\Album;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpParser\Node\Stmt\Return_;

class AlbumSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithDrawings, WithCustomStartCell, WithColumnWidths, WithTitle
{
    protected $albumId;
    protected $rowCount = 0;

    public function __construct($albumId)
    {
        $this->albumId = $albumId;
    }

    public function collection()
    {
        // Retrieve data related to the provided album ID
        $album = Album::findOrFail($this->albumId);
        return $album->photos; // Assuming 'photos' is the relationship method in the Album model
    }

    public function title(): string
    {
        return 'Data Album';
    }

    public function map($photo): array
    {
        $this->rowCount++;

        // Get the image URL
        $imageUrl = Storage::url($photo->lokasi_file);

        return [
            $this->rowCount, // ID starts from 1 and increments
            $photo->judul_foto,
            $photo->deskripsi_foto,
            $photo->created_at->toDateTimeString(),
            $photo->updated_at->toDateTimeString(),
            ''
        ];
    }

    public function drawings(): array
    {
        $drawings = [];
        $imageRow = 5; // Start image placement from row 5 (assuming headers occupy rows 2-4)

        // Iterate over the photos collection
        foreach ($this->collection() as $photo) {
            $imageUrl = public_path(Storage::url($photo->lokasi_file));

            // Check if the image file exists
            if (file_exists($imageUrl)) {
                $drawing = new Drawing();
                $drawing->setName($photo->judul_foto);
                $drawing->setDescription($photo->judul_foto);
                $drawing->setPath($imageUrl);

                $drawing->setHeight(200); // Adjust image height as needed
                $drawing->setWidth(200); // Adjust image height as needed

                // Set cell coordinates for the image (adjust column letter if needed)
                $drawing->setCoordinates('G' . $imageRow);

                 // Set alignment to center the image
                $drawing->setOffsetX(0);
                $drawing->setOffsetY(0);

                // Add the drawing object to the $drawings array
                $drawings[] = $drawing;

                // Increment image row for subsequent images
                $imageRow++;
            }
        }

        return $drawings;
    }


    public function startCell(): string
    {
        return 'B2'; // Set the start cell to B2
    }

    public function headings(): array
    {
        $album = Album::findOrFail($this->albumId);

        // Format the date
        $formattedDate = strtoupper(Carbon::now()->isoFormat('DD MMMM YYYY'));

        // Create the headings array
        return [
            [$album->nama_album], // Album name
            [$formattedDate], // Current date
            ['ID', 'Title', 'Description', 'Created At', 'Updated At', 'Image'], // Column headings for photos
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 10, // Custom width for column B
            'C' => 20, // Custom width for column C
            'D' => 30, // Custom width for column D
            'E' => 20, // Custom width for column E
            'F' => 20, // Custom width for column F
            'G' => 50, // Custom width for column G (for images)
        ];
    }

    public function styles(Worksheet $sheet)
{
    
    // Determine the last row of the data collection
    $lastRow = $this->rowCount + 4; // Add 3 to account for the header rows
    // Set the header background color to blue and apply border
    $sheet->getStyle('B2:G4')->applyFromArray([
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

     // Apply styling from B5 to the last row of the data collection
     $sheet->getStyle('B5:G' . $lastRow)->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_MEDIUM,
                'color' => ['rgb' => '000000'],
            ],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ]);

    // Iterate over drawings and adjust row heights
    foreach ($this->drawings() as $drawing) {
        $rowHeight = $drawing->getHeight() + 10; // Add some padding around the image
        $sheet->getRowDimension($drawing->getCoordinates()[1])->setRowHeight($rowHeight);
    }

    // Center image drawings
    foreach ($this->drawings() as $drawing) {
        $column = $drawing->getCoordinates()[0]; // Get the column of the drawing
        $columnWidth = $sheet->getColumnDimension($column)->getWidth(); // Get the width of the column
        $drawingWidth = $drawing->getWidth(); // Get the width of the drawing
        $offsetX = ($columnWidth - $drawingWidth) / 2; // Calculate the horizontal offset
        $drawing->setOffsetX($offsetX); // Set the horizontal offset to center the drawing
    }


    // Merge cells for rows 2 and 3 as benchmark merge cells from row 4
    $sheet->mergeCells('B2:G2');
    $sheet->mergeCells('B3:G3');

    // Set the alignment for the rest of the cells to center
    $sheet->getStyle('A5:G' . ($this->rowCount + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A5:G' . ($this->rowCount + 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
}

}
