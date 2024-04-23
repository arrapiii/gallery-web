<?php

namespace Database\Seeders;

use App\Models\JenisLaporan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JenisLaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $jenisLaporans = [
            [
                'jenis_laporan' => 'Spam',
            ],
            [
                'jenis_laporan' => 'Sexual Activity',
            ],
            [
                'jenis_laporan' => 'Self Harm',
            ],
            [
                'jenis_laporan' => 'Graphic Violence',
            ],
            [
                'jenis_laporan' => 'Privacy Violation',
            ],
        ];

        foreach ($jenisLaporans as $jenisLaporan) {
            JenisLaporan::create($jenisLaporan);
        }
    }
}