<?php

namespace App\Exports;

use App\Models\Album;
use App\Models\LikeFoto;
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

        $imageUrl = Storage::url($photo->lokasi_file);
        $likeCount = $photo->likes->count();
        
        $likeCount = $likeCount ?? 'Zero';

        date_default_timezone_set('Asia/Jakarta');
        
        $createdAt = Carbon::parse($photo->created_at)->locale('id_ID')->format('d F Y H:i:s');
        $updatedAt = Carbon::parse($photo->updated_at)->locale('id_ID')->format('d F Y H:i:s');
        
        return [
            $this->rowCount,
            $photo->judul_foto,
            $photo->deskripsi_foto,
            $likeCount,
            $createdAt,
            $updatedAt,
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

                $drawing->setHeight(200);
                $drawing->setWidth(200);

                $drawing->setCoordinates('H' . $imageRow);

                $drawing->setOffsetX(0);
                $drawing->setOffsetY(0);

                $drawings[] = $drawing;

                // Increment image row for subsequent images
                $imageRow++;
            }
        }

        return $drawings;
    }


    public function startCell(): string
    {
        return 'B2';
    }

    public function headings(): array
    {
        $album = Album::findOrFail($this->albumId);

        $formattedDate = strtoupper(Carbon::now()->isoFormat('DD MMMM YYYY'));

        return [
            [$album->nama_album],
            [$formattedDate],
            ['ID', 'Title', 'Description', 'Jumlah Like', 'Created At', 'Updated At', 'Image'],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 10,
            'C' => 20,
            'D' => 30,
            'E' => 15,
            'F' => 20, 
            'G' => 20,
            'H' => 50, 
        ];
    }

    public function styles(Worksheet $sheet)
    {
        
        // Determine the last row of the data collection
        $lastRow = $this->rowCount + 4; // Add 3 to account for the header rows
        // Set the header background color to blue and apply border
        $sheet->getStyle('B2:H4')->applyFromArray([
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
        $sheet->getStyle('B5:H' . $lastRow)->applyFromArray([
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
        $sheet->mergeCells('B2:H2');
        $sheet->mergeCells('B3:H3');

        // Set the alignment for the rest of the cells to center
        $sheet->getStyle('A5:H' . ($this->rowCount + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5:H' . ($this->rowCount + 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getStyle('B2:H' . $lastRow)->getAlignment()->setWrapText(true);
    }

}
