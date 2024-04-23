<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanFoto extends Model
{
    use HasFactory;

    protected $table = 'laporan_fotos';

    protected $guarded = ['id'];

    public function foto()
    {
        return $this->belongsTo(Foto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisLaporan()
    {
        return $this->belongsTo(JenisLaporan::class, 'jenis_laporan_id');
    }
}
